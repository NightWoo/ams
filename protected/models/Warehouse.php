<?php
Yii::import('application.models.AR.WarehouseAR');
Yii::import('application.models.AR.LaneAR');
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.CarAR');
Yii::import('application.models.AR.UserAR');
Yii::import('application.models.WarehouseSeeker');

class Warehouse
{
	public function __construct(){
	}

	public function checkin($vin) {
		$car = CarAR::model()->find('vin=?', array($vin));
		//$carYear = CarYear::getCarYear($vin);
		
		//map the order_config
		$orderConfigId = 0;
		$configId = $car->config_id;
		$config = CarConfigAR::model()->findByPk($configId);
		if(!empty($config)) {
			$orderConfigId = $config->order_config_id;
		}

		$conditions = array();
		$conditions['match'] = "series=? AND car_type=? AND color=? AND cold_resistant=? AND order_config_id=? AND special_property=?";
		$conditions['free'] = "status=? AND free_seat>?";
		$condition = join(' AND ', $conditions);
		$condition .= ' ORDER BY id ASC';
		$values = array($car->series, $car->type, $car->color, $car->cold_resistant, $orderConfigId, $car->special_property, 0, 0);
		$row = WarehouseAR::model()->find($condition, $values);
		// if($car->special_property == 0){//普通车辆查找同型车列
		// 	$row = WarehouseAR::model()->find($condition, $values);
		// } else if ($car->special_property == 1){//出口车扔到F区
		// 	$row = WarehouseAR::model()->find('area=?', array('F'));
		// } else if ($car->special_property == 2){//降级车扔到Y区
		// 	$row = WarehouseAR::model()->find('area=?', array('Y'));
		// }

		//如无同型车列		
		if(empty($row)) {
			//在该车系库区区查找空车列，并生成同型车列
			// $voidRow = WarehouseAR::model()->find('status=? AND quantity=? AND series=? AND area=? ORDER BY id ASC', array(0, 0, $car->series, 'A'));
			$voidRow = WarehouseAR::model()->find('status=? AND quantity=? AND series=? AND special_property=? AND free_seat>0 ORDER BY id ASC', array(0, 0, $car->series, $car->special_property));
			if(!empty($voidRow) && !empty($orderConfigId)) {
				$row = $voidRow;
				$row->car_type = $car->type;
				$row->color = $car->color;
				$row->cold_resistant = $car->cold_resistant;
				//$row->car_year = $carYear;
				$row->order_config_id = $orderConfigId;
				$row->save();
			} else {
				//如果连空车列都没有就扔到周转区Z
				if($car->special_property == 1){
					$row = WarehouseAR::model()->find('area=? AND series=?', array('G', ''));
				} else if ($car->special_property == 0) {
					//$row = WarehouseAR::model()->find('area=?', array('Z'));
				} else if ($car->special_property == 9) {
					$row = WarehouseAR::model()->find('area=?', array('X'));
				}
			}
		} 

		//如明确了进入的车列
		if(!empty($row)){
			//进入车列
			$row->quantity += 1;
			$row->free_seat -= 1;
			// if($row->quantity == $row->capacity) {
			if($row->free_seat == 0) {
				$row->status = 1;
			}
			$row->save();
			
			//原库位数量减1
			if($car->warehouse_id>200){
				$oldRow = WarehouseAR::model()->findByPk($car->warehouse_id);
				$oldRow->quantity -=1;
				$oldRow->save();
			}
		} else {
			throw new Exception('库区已满，无法完成入库');
		}

		$data =array();
		$data['vin'] = $car->vin;
		$data['row'] = $row->row;
		$data['area'] = $row->area;
		$data['warehouse_id'] =  $row->id;

		return $data;
	}

	public function checkout($vin) {
		$car = CarAR::model()->find('vin=?', array($vin));
		$order = OrderAR::model()->findByPk($car->order_id);
		$row = WarehouseAR::model()->findByPk($car->warehouse_id);
		$data = array();
		
		if(empty($order)){
			throw new Exception('该车未匹配订单，或订单不存在，无法出库');
		} else {
			$order->count += 1;
			$order->save();
			if($order->amount == $order->count){
				$boardNumber = $order->board_number;
				$sql = "SELECT board_number, amount,hold,count FROM `order` WHERE board_number='$boardNumber'";
				$datas = Yii::app()->db->createCommand($sql)->queryAll();
				$amountSum = 0;
				$countSum = 0;
				foreach($datas as $data){
					$amountSum += $data['amount'];
					$countSum += $data['count'];
				}
				if($amountSum == $countSum){
					$sql = "UPDATE `order` SET `status`=2, out_finish_time=CURRENT_TIMESTAMP WHERE board_number='$boardNumber' AND `status`=1";
					Yii::app()->db->createCommand($sql)->execute();
					$order->status = 2;
					$order->out_finish_time=date("YmdHis");
					$order->save();
				}
			}

			//重复了···，备车匹配订单getCarStandby已经减过一次了
			// if(!empty($row)){
			// 	$row->quantity -= 1;
			// 	$row->save();
			// }

			$lane = LaneAR::model()->findByPk($order->lane_id)->name;
			$laneName='';
			if(!empty($lane)) {
				$laneName = '_' . $lane;
			}

			$data['vin'] = $car->vin;				
			$data['lane'] = $lane;
			$data['lane_id'] = $order->lane_id;
			$data['order_id'] = $car->order_id;				
			$data['order_number'] = $order->order_number;
			$data['distributor_name'] = $order->distributor_name;
			$data['distributor_code'] = $order->distributor_code;
			$data['carrier'] = $order->carrier;				

			$order->save();
		}

		return $data;
	}

	public function clearRow($wearehouseId) {

	}
	
}
