<?php
Yii::import('application.models.AR.WarehouseAR');
Yii::import('application.models.AR.LaneAR');
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.CarAR');
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
		$conditions['match'] = "series=? AND car_type=? AND color=? AND cold_resistant=? AND order_config_id=?";
		$conditions['free'] = "status=? AND free_seat>?";
		$condition = join(' AND ', $conditions);
		$condition .= ' ORDER BY id ASC';
		$values = array($car->series, $car->type, $car->color, $car->cold_resistant, $orderConfigId, 0, 0);

		if($car->special_property == 0){//普通车辆查找同型车列
			$row = WarehouseAR::model()->find($condition, $values);
		} else if ($car->special_property == 1){//出口车扔到X区
			$row = WarehouseAR::model()->find('area=?', array('X'));
		} else if ($car->special_property == 2){//降级车扔到Y区
			$row = WarehouseAR::model()->find('area=?', array('Y'));
		}

		//如无同型车列		
		if(empty($row)) {
			//在该车系库区区查找空车列，并生成同型车列
			// $voidRow = WarehouseAR::model()->find('status=? AND quantity=? AND series=? AND area=? ORDER BY id ASC', array(0, 0, $car->series, 'A'));
			$voidRow = WarehouseAR::model()->find('status=? AND quantity=? AND series=? ORDER BY id ASC', array(0, 0, $car->series));
			if(!empty($voidRow) && !empty($orderConfigId)) {
				$row = $voidRow;
				$row->car_type = $car->type;
				$row->color = $car->color;
				$row->cold_resistant = $car->cold_resistant;
				//$row->car_year = $carYear;
				$row->order_config_id = $orderConfigId;
			} else {
				//如果连空车列都没有就扔到周转区Z
				$row = WarehouseAR::model()->find('area=?', array('Z'));
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

			$car->warehouse_id = $row->id;
			$car->area = $row->area;
			$car->status = '成品库_' . $row->row;

			$row->save();
			$car->save();
		} else {
			throw new Exception('成品库无可用车列');
		}

		$data =array();
		$data['vin'] = $car->vin;
		$data['row'] = $row->row;
		$data['area'] = $row->area;

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
			// if(!empty($row)){
			// 	$row->quantity -= 1;
			// 	$row->save();
			// }

			$lane = LaneAR::model()->findByPk($order->lane_id)->name;
			$laneName='';
			if(!empty($lane)) {
				$laneName = '_' . $lane;
			}

			$car->status = '公司外' . $laneName;
			$car->lane_id = $order->lane_id;
			$car->distributor_name = $order->distributor_name;
			$car->distributor_code = $order->distributor_code;
			// $car->order_detail_id = $order->order_detail_id;
			$car->warehouse_id = 0;
			$car->area = 'out';

			$order->save();
			$car->save();

			
			$data['vin'] = $car->vin;				
			$data['lane'] = $lane;
			$data['order_id'] = $car->order_id;				
			$data['order_number'] = $order->order_number;
			$data['distributor_name'] = $order->distributor_name;
			$data['carrier'] = $order->carrier;				
		}

		return $data;
	}

	public function clearRow($wearehouseId) {

	}
	
}
