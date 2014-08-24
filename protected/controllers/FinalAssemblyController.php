<?php
Yii::import('application.models.Car');
class FinalAssemblyController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	/**
	 */
	public function actionIndex()
	{
		$this->render('dataInput/dataInputNodeSelect',array(''));
	}
	
	public function actionChild() {
		$node = $this->validateStringVal('node','PBS');
		$this->renderFile('/bms/execution/assembly/dataInput/' . $node . ".php",array(''));	
	}

	public function actionCarInfo() {
		$vin = $this->validateStringVal('vin', '');
		$car = array();
		if(!empty($vin)) {
			$car = Car::create($vin)->car;
		}
		
		$ret = array(
			'success' => true,
			'message' => 'OK',
			'data' => $car,
		);
		$this->renderJSON($ret);
	}
	
	public function actionSavePbs() {
		$vin = $this->validateStringVal('vin', '000');
		var_dump($vin);
		$car = Car::create($vin);
                $car->intoNode('PBS');
		$ret = array(
			'success' => true,
			'message' => 'OK',
			'data' => array('vin'=>$vin),
		);
		$this->renderJSON($ret);
	}

}
