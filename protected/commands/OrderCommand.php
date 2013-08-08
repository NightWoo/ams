<?php
Yii::import('application.models.AR.OrderAR');
class OrderCommand extends CConsoleCommand
{
	public function run($args) {
		list($unfinishedOrders, $count) = $this->getUnfinishedOrders();
		$curDateOrders = $this->getCurDateOrders();
		$curDate = DateUtil::getCurDate();
		$lastDate = DateUtil::getLastDate();
		$maxPri = 0;
		// if(!empty($curDateOrders)) {
		// 	foreach($curDateOrders as $order) {
		// 		if($count>0){
		// 			$order->priority += $count;
		// 		}
		// 		if($order->status == 1 && $order->activate_time == '0000-00-00 00:00:00'){
		// 			$order->activate_time = date('YmdHis');
		// 			$order->lane_status = 1;
		// 		}
		// 		$order->save();
		// 		$maxPri = $order->priority;
		// 	}
		// }
		$pri = 0;
		if(!empty($unfinishedOrders)){
			foreach($unfinishedOrders as $order) {
				$order->priority = $pri;
				$order->standby_date=$curDate;
				$order->save();

				$sameBoardOrders = $this->findSameBoardOrders($order->board_number);
				if(!empty($sameBoardOrders)){
					// ++$maxPri;
					foreach($sameBoardOrders as $sameBoard){
						$sameBoard->priority = $pri;
						$sameBoard->standby_date=$curDate;
						$sameBoard->save();
					}
				}
				$pri++;
			}
		}

	}
 
	private function getCurDateOrders() {
		$curDate = DateUtil::getCurDate();
		$orders = OrderAR::model()->findAll('standby_date=? ORDER BY priority ASC', array($curDate));
		return $orders;
	}

	private function getUnfinishedOrders(){
		$lastDate = DateUtil::getLastDate();
		$orders = OrderAR::model()->findAll('standby_date=? AND status=1 AND amount>count GROUP BY board_number ORDER BY priority ASC', array($lastDate));
		$count = OrderAR::model()->count('standby_date=? AND status=1 AND amount>count ORDER BY priority ASC', array($lastDate));
		return array($orders, $count);
	}

	private function findSameBoardOrders($boardNumber){
		$orders = OrderAR::model()->findAll('board_number=?', array($boardNumber));
		return $orders;
	}
}
