<?php
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.CarAR');
Yii::import('application.models.AR.WarehouseAR');
Yii::import('application.models.AR.CarConfigAR');
Yii::import('application.models.OrderSeeker');

class Order
{
	public function __construct(){
	}

	public function match($series, $carType, $configId, $color, $coldResistant, $date) {
		$success = false;
		$data = array();

		$config = CarConfigAR::model()->findByPk($configId);
		if(empty($config)){
			$orderConfigId = 0;
		} else {
			$orderConfigId = $config->order_config_id;
		}
		
		$seeker =new OrderSeeker;
		$order = $seeker->matchQuery($series, $carType, $orderConfigId, $color, $coldResistant, $date);

		if(!empty($order)) {
			$order->hold += 1;
			$order->save();
			
			$data['orderId'] = $order->id;
			$data['orderNumber'] = $order->order_number;
			//$data['lane'] = $order->lane;
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

				$matchCondition = "warehouse_id>1 AND series=? AND type=? AND color=? AND cold_resistant=? AND config_id IN $configId ORDER BY finish_time ASC";
				$values = array($order->series, $order->car_type, $order->color, $order->cold_resistant);
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
				$warehouse->status = 0;
				if($warehouse->quantity == 0) {
					$warehouse->car_type = '';
					$warehouse->color = '';
					$warehouse->order_config_id = 0;
					$warehouse->cold_resistant = '';
					//$warehouse->car_year = '';
				}

				$matchedOrder->hold += 1;
				$matchedCar->order_id = $matchedOrder->id;
				$matchedCar->warehouse_id = 1;		//WDI
				$matchedCar->status = '成品库WDI';
				$matchedCar->area = 'WDI';

				$warehouse->save();
				$matchedCar->save();
				$matchedOrder->save();
				
				$data['vin'] = $matchedCar->vin;
				$data['type'] = $matchedCar->type;
				$data['series'] = $matchedCar->series;
				$data['color'] = $matchedCar->color;
				$data['order_number'] = $matchedOrder->order_number;
				$data['order_id'] = $matchedOrder->id;
				$data['row'] = $warehouse->row;
			}
		} else {
			throw new Exception('暂无可备车辆');
		}
		return $data;

	}
	
}
