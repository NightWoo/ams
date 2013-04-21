<?php
Yii::import('application.models.Order');
Yii::import('application.models.OrderSeeker');
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.LaneAR');
Yii::import('application.models.AR.WarehouseAR');

class LaneController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionOrderInfo(){
		try{
			$orderSeeker = new OrderSeeker();
			$data = $orderSeeker->getLaneInfo();
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e){
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
}
