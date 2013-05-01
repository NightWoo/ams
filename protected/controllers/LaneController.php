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

	public function actionQueryOrderInfo(){
		try{
			$orderSeeker = new OrderSeeker();
			list($boards, $laneCount) = $orderSeeker->queryLaneOrders();
			$data = array(
				'boards' => $boards,
				'laneCount' => $laneCount,
			);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e){
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionReleaseOrders(){
		$transaction = Yii::app()->db->beginTransaction();
		try{
			$laneId = $this->validateIntVal("laneId", 0);

			$orders = OrderAR::model()->findAll("lane_status=1 AND lane_id=?", array($laneId));
			if(!empty($orders)){
				foreach($orders as $order){
					$order->lane_status = 2;
					$order->lane_release_time = date("YmdHis");
					$order->save();
				}
			}
			$transaction->commit();
			$this->renderJsonBms(true, 'OK', $orders);
		} catch(Exception $e){
			$transaction->rollback();
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
}
