<?php
Yii::import('application.models.AR.DeviceParameterAR');


class DeviceParameterController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionQuery() {
		$parameterName = $this->validateStringVal('parameterName', '');
		try{
			$ret = DeviceParameterAR::model()->find("name = '$parameterName'");
			$this->renderJsonBms(true, 'OK', $ret);
		}catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionSave() {
		$parameterName = $this->validateStringVal('parameterName', '');
		$parameterValue = $this->validateIntVal('parameterValue', 0);
		try{
			$parameter = DeviceParameterAR::model()->find("name = '$parameterName'");
			$parameter->value = $parameterValue;
			$parameter->save();

			$this->renderJsonBms(true, 'parameter saved', $parameter);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

}