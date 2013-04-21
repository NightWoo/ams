<?php
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.CarAR');
Yii::import('application.models.AR.WarehouseAR');
Yii::import('application.models.AR.CarConfigAR');
Yii::import('application.models.AR.CarTypeMapAR');
Yii::import('application.models.AR.LaneAR');
Yii::import('application.models.OrderSeeker');

class Order
{
	public function __construct(){
	}

	public function checkDetail($details){
		$orders = CJSON::decode($details);
		if(empty($orders)){
			return;
		}
		$data = array();
		foreach($orders as $order){
			$sql = "SELECT order_detail_id FROM `order` WHERE order_detail_id={$order['orderDetailId']}";
			$detailId = Yii::app()->db->createCommand($sql)->queryScalar();
			if(!empty($detailId)) $data[]=$detailId;
		}
		return $data;
	}
	
	public function genernate($details){
		$orders = CJSON::decode($details);
		if(empty($orders)){
			return;
		}
		foreach($orders as $order){
			$ar = new OrderAR();
			$ar->order_number = $order['orderNumber'];
			$ar->order_detail_id = $order['orderDetailId'];
			$ar->order_nature = $order['orderNature'];
			$ar->standby_date = $order['standbyDate'];
			$ar->amount = $order['amount'];
			$ar->series = $order['series'];
			$ar->car_type = $order['carType'];
			$ar->color = $order['color'];
			$ar->cold_resistant = $order['coldResistant'];
			$ar->order_config_id = $order['orderConfigId'];
			$ar->config_description = $order['configDescription'];
			$ar->remark = $order['remark'];
			$ar->distributor_code = $order['distributorCode'];
			$ar->distributor_name = $order['distributorName'];
			$ar->sell_car_type = $order['sellCarType'];
			$ar->sell_color = $order['sellColor'];
			$ar->board_number = $order['boardNumber'];
			$ar->lane_id = $order['laneId'];

			$ar->create_time = date('YmdHis');
			$ar->user_id = Yii::app()->user->id;

			$ar->save();
		}
	}

	public function split($orderId, $number=0, $laneId=0){
		if(empty($number)) return;
		$old = OrderAR::model()->findByPk($orderId);
		if(empty($old)) return;

		$remain = $old->amount - $old->hold;
		if($number > $old->amount) {
			throw new Exception('本订单需备数量'. $old->amount . '小于分拆数量，无法完成分拆');
		}
		if($number > $remain){
			throw new Exception('本订单需备数量：'. $old->amount . '，已备数量：'. $old->hold .'，待备数量小于分拆数量，无法完成分拆');
		} else {
			$old->amount -= $number; 
			$new = new OrderAR();
			$new->order_number = $old->order_number;
			$new->standby_date = $old->standby_date;
			$new->amount = $number;
			$new->lane_id = $laneId;
			$new->series = $old->series;
			$new->car_type = $old->car_type;
			$new->color = $old->color;
			$new->car_year = $old->car_year;
			$new->cold_resistant = $old->cold_resistant;
			$new->order_config_id = $old->order_config_id;
			$new->remark = $old->remark;
			$new->order_detail_id = $old->order_detail_id;
			$new->order_nature = $old->order_nature;
			$new->distributor_name = $old->distributor_name;
			$new->distributor_code = $old->distributor_code;
			$new->country = $old->country;
			$new->city = $old->city;
			$new->carrier = $old->carrier;
			$new->sell_car_type = $old->sell_car_type;
			$new->sell_color = $old->sell_color;
			$new->config_description = $old->config_description;
			$new->modify_time = date('YmdHis');
			$new->user_id = Yii::app()->user->id;

			$new->save();
			$old->save();
		}


	}

	public function match($series, $carType, $configId, $color, $coldResistant, $date) {
		$success = false;
		$data = array();
		$orderConfigId = 0;

		$config = CarConfigAR::model()->findByPk($configId);
		if(!empty($config)){
			$orderConfigId = $config->order_config_id;
		}
		
		$seeker =new OrderSeeker;
		$order = $seeker->matchQuery($series, $carType, $orderConfigId, $color, $coldResistant, $date);

		if(!empty($order)) {
			$order->hold += 1;
			$order->save();
			
			$data['orderId'] = $order->id;
			$data['orderNumber'] = $order->order_number;
			// $data['lane_id'] = $order->lane_id;
			$success = true;
		}

		return array($success, $data);
	}

	public function getCarStandby($standbyDate) {
		//$matchedOrder = new OrderAR;
		$data = array();

		$condition = "standby_date=? AND status=1 AND amount>hold ORDER BY priority ASC";
		$orders = OrderAR::model()->findAll($condition, array($standbyDate));
		if(!empty($orders)){
			foreach($orders as $order) {
				$sql = "SELECT id FROM car_config WHERE order_config_id = $order->order_config_id";
        		$configId = Yii::app()->db->createCommand($sql)->queryColumn();
        		$configId = "(" . join(',', $configId) . ")";

				$matchCondition = "warehouse_id>1 AND warehouse_id<1000 AND series=? AND color=? AND cold_resistant=? AND config_id IN $configId AND warehouse_time>'0000-00-00 00:00:00'";
				$values = array($order->series, $order->color, $order->cold_resistant);
				
				//先看库里面有没这么多一个单需要的车，如果不够，不备此单
				// $count = CarAR::model()->count($matchCondition, $values);
				// $need = $order->amount - $order->hold;
				// if($count<$need) continue;

				$matchCondition .= "  ORDER BY warehouse_time ASC";
				$car = CarAR::model()->find($matchCondition, $values);
				 if(!empty($car)){
				 	//$carYear = CarYear::getCarYear($car->vin);
					//if($carYear == $order->car_year) {
				 		$matchedOrder = $order;
				 		$matchedCar = $car;
				 		break;
				 	//}
				 }
			}
		}

		if(!empty($matchedCar)){
			$warehouse = WarehouseAR::model()->findByPk($matchedCar->warehouse_id);
			if(!empty($warehouse)){
				$warehouse->quantity -= 1;
				//$warehouse->status = 0;
				if($warehouse->quantity == 0) {
					$warehouse->car_type = '';
					$warehouse->color = '';
					$warehouse->order_config_id = 0;
					$warehouse->cold_resistant = 0;
					//$warehouse->car_year = '';

					$warehouse->free_seat = $warehouse->capacity;
					$warehouse->status = 0;
				}

				$matchedOrder->hold += 1;
				if($matchedOrder->hold == $matchedOrder->amount){
					$matchedOrder->standby_finish_time = date('YmdHis');
				}

				$matchedCar->order_id = $matchedOrder->id;
				// $matchedCar->lane_id = $matchedOrder->lane_id;
				$matchedCar->old_wh_id = $matchedCar->warehouse_id;
				$matchedCar->warehouse_id = 1;		//WDI
				$matchedCar->status = 'WDI';
				$matchedCar->area = 'WDI';

				$warehouse->save();
				$matchedCar->save();
				$matchedOrder->save();
				
				$configName = CarConfigAR::model()->findByPk($matchedCar->config_id)->name;
				$carModel = CarTypeMapAR::model()->find('car_type=?', array($matchedCar->type))->car_model;

				$data['vin'] = $matchedCar->vin;
				$data['type'] = $matchedCar->type;
				$data['type_info'] = $carModel. "/" . $configName;
				$data['series'] = $matchedCar->series;
				$data['color'] = $matchedCar->color;
				$data['order_number'] = $matchedOrder->order_number;
				$data['distributor_name'] = $matchedOrder->distributor_name;
				$data['order_id'] = $matchedOrder->id;
				$data['row'] = $warehouse->row;
				$data['cold_resistant'] = ($matchedCar->cold_resistant == 1)? '耐寒':'非耐寒';
				$data['lane'] = LaneAR::model()->findByPk($order->lane_id)->name;
			}
		} else {
			throw new Exception('暂无可备车辆');
		}
		return $data;

	}
	
}
