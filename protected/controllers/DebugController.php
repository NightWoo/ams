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

	private static $a = 1;

	private static $STATES=array('onLine','onLine-2','VQ1','VQ2','VQ3','WH');

	public function actionTest () {
		$vin = $this->validateStringVal('vin', '');
		try {
			$car = Car::create("LC0C14AA4D0082092");
			$ret  = $car->addGasBagTraceCode("109616029D02201367");
			$this->renderJsonBms(true, 'OK', $ret);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function a () {
		return self::$a++;
	}

	private function periodSegmentArray ($span=16,$intercept=8) {
		$segments = ceil($span/$intercept);
		$periodSegmentArray = array();
		for($i=0;$i<$segments;$i++) {
			$low = $i * $intercept;
			$high = ($i + 1) * $intercept;
			$text = $low . "-" . $high;
			$periodSegmentArray[$text] = array('low'=>$low, 'high'=>$high);
		}
		$lastText = ">". $span;
		$periodSegmentArray[$lastText] = array('low'=>$span, 'high'=>0);

		return $periodSegmentArray;
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