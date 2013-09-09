<?php
Yii::import('application.models.AR.*');
Yii::import('application.models.*');

class DebugController extends BmsBaseController
{
	private static $SERIES = array(
		'F0' => 'F0',
		'M6' => 'M6',
		'6B' => '思锐',
	);

	private static $STATES=array('onLine','onLine-2','VQ1','VQ2','VQ3','WH');

	public function actionTest () {
		$vin = $this->validateStringVal('vin', '');
		try {
			list($LC0TypeArray,$LC0Type) = $this->getLC0Type();
			list($LC0ConfigArray,$LCOConfig) = $this->getLC0Config();
			$LC0TypeColorText = $this->getLC0TypeColorText();
			$ret = " AND (vin LIKE 'LGX%' OR type IN $LC0Type OR special_property=1 OR config_id IN $LCOConfig OR $LC0TypeColorText)";
			$this->renderJsonBms(true, 'OK', $ret);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	private function getLC0Type () {
		$sql = "SELECT car_type FROM lc0_unlock WHERE category='type'";
		$LC0TypeArray = Yii::app()->db->createCommand($sql)->queryColumn();
		$LC0Type = "('" . join("','", $LC0TypeArray) . "')";
		return array($LC0TypeArray,$LC0Type);
	}

	private function getLC0Config () {
		$sql = "SELECT car_config FROM lc0_unlock WHERE category='config' AND car_config>0";
		$LC0ConfigArray = Yii::app()->db->createCommand($sql)->queryColumn();
		$LC0Config = "(" . join(",", $LC0ConfigArray) . ")";
		return array($LC0ConfigArray,$LC0Config);
	}

	private function getLC0TypeColorText () {
		$sql = "SELECT car_type,car_colors FROM lc0_unlock WHERE category='type_color'";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		$textArray = array();
		foreach($datas as $data){
			$textArray[] = "(type='" . $data['car_type'] . "' AND color IN (" . $data['car_colors'] ."))"; 
		}

		$text = join(" OR ", $textArray);

		return $text;
	}
}