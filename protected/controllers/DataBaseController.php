<?php
Yii::import('application.models.AR.*');
Yii::import('application.models.*');

class DataBaseController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionQueryWarehouseCountRevise () {
		$type = $this->validateStringVal('type', '');
		try{
			$ret = WarehouseCountReviseAR::model()->findAll("count_type = '$type'");
			$this->renderJsonBms(true, 'OK', $ret);
		}catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionSaveWarehouseCountRevise () {
		$type = $this->validateStringVal('type', '');
		$series = $this->validateStringVal('series', '');
		$value = $this->validateIntVal('value', 0);
		try{
			$revise = WarehouseCountReviseAR::model()->find("count_type=? AND series=?", array($type, $series));
			if(!empty($revise)) {
				$revise->count = $value;
				$revise->save();
			}

			$this->renderJsonBms(true, 'parameter saved', $revise);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
}