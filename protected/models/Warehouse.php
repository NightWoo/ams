<?php
Yii::import('application.models.AR.WarehouseAR');
Yii::import('application.models.WarehouseSeeker');

class Warehouse
{
	public function __construct(){
	}

	public function checkin($vin) {
		$car = CarAR::model()->find('vin=?', array($vin));
		//$carYear = CarYear::getCarYear($vin);
		
		$orderConfigId = 0;
		$configId = $car->config_id;
		$config = CarConfigAR::model()->findByPk($configId);
		if(!empty($config)) {
			$orderConfigId = $config->order_config_id;
		}

		$conditions = array();
		$conditions['match'] = "series=? AND car_type=? AND color=? AND cold_resistant=? AND order_config_id=?";
		$conditions['free'] = "status=?";
		$condition = join(' AND ', $conditions);
		$condition .= ' ORDER BY id ASC';
		$values = array($car->series, $car->type, $car->color, $car->cold_resistant, $orderConfigId, 0);

		//查找同型车列
		$row = WarehouseAR::model()->find($condition, $values);
		//如无同型车列		
		if(empty($row)) {
			//查找空车列，并生成同型车列
			$voidRow = WarehouseAR::model()->find('status=? AND quantity=? AND series=? AND area=? ORDER BY id ASC', array(0, 0, $car->series, 'A'));
			if(!empty($voidRow) && !empty($orderConfigId)) {
				$row = $voidRow;
				$row->car_type = $car->type;
				$row->color = $car->color;
				$row->cold_resistant = $car->cold_resistant;
				//$row->car_year = $carYear;
				$row->order_config_id = $orderConfigId;
			} else {
				//将F0存于于周转C区
				if($car->series == 'F0') {
					$row = WarehouseAR::model()->find('area=?', array('C'));
				} else {
					//将非F0车系存于B区
					$row = WarehouseAR::model()->find('area=? AND series=?', array('B', $car->series));
				}
			}
		} 

		if(!empty($row)){
			//进入同型车列
			$row->quantity += 1;
			if($row->quantity == $row->capacity) {
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
		$data['row'] = $row->row;
		$data['area'] = $row->area;

		return $data;
	}

	public function checkout($vin) {
		$car = CarAr::model()->find('vin=?', array($vin));
		$order = OrderAR::model()->findByPk($car->order_id);
		$row = WarehouseAR::model()->findByPk($car->warehouse_id);
		$data = array();
		
		if(empty($order)){
			throw new Exception('该车未匹配订单，或订单不存在，无法出库');
		} else {
			$order->count += 1;
			if(!empty($row)){
				$row->quantity -= 1;
				$row->save();
			}
			$lane = $order->lane;
			$distributorId = $order->distributor_id;
			$car->lane = $lane;
			$car->distributor_id = $distributorId;

			$distributor = '';
			if(!empty($distributorId)) {
				$distributor = DistributorAR::model()->findByPk($distributorId);
				$distributor = '_' . $distributor->display_name; 
			}

			$laneName='';
			if(!empty($lane)) {
				$laneName = '_' . $lane;
			}

			$car->status = '公司外' . $laneName . $distributor;
			$car->warehouse_id = 0;
			$car->area = 'Departure';

			$order->save();
			$car->save();

			
			$data['vin'] = $car->vin;				
			$data['lane'] = $car->lane;
			$data['order_id'] = $car->order_id;				
			$data['order_number'] = $order->order_number;
			$data['carrier'] = $order->carrier;				
		}

		return $data;
	}

	public function clearRow($wearehouseId) {

	}
	
}
