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

	public function checkin($vin, $forceToAreaT=false) {
		$car = CarAR::model()->find('vin=?', array($vin));
		//$carYear = CarYear::getCarYear($vin);
		if(!$forceToAreaT){
			//map the order_config
			$orderConfigId = 0;
			$configId = $car->config_id;
			$config = CarConfigAR::model()->findByPk($configId);
			if(!empty($config)) {
				$orderConfigId = $config->order_config_id;
			}

			$conditions = array();
			switch($car->series){
				case "F0" :
					$conditions['area'] = "(id>1 AND id<200)";
					break;
				case "M6" :
					$conditions['area'] = "(id>400 AND id<500) ";
					break;
				case "6B" :
					$conditions['area'] = "((id>400 AND id<500) OR (id>618 AND id<700))";
					break;
				default:
			}
			
			$conditions['match'] = "(series=? OR series='') AND car_type=? AND color=? AND cold_resistant=? AND order_config_id=? AND special_property=?";
			$conditions['free'] = "status=0 AND free_seat>0";
			$condition = join(' AND ', $conditions);
			$condition .= ' ORDER BY id ASC';
			$values = array($car->series, $car->type, $car->color, $car->cold_resistant, $orderConfigId, $car->special_property);
			// 寻找同型车列
			$row = WarehouseAR::model()->find($condition, $values);

			//如无同型车列		
			if(empty($row)) {
				//查找空车列，并生成同型车列
				$voidCondtion = $conditions['area'] . " AND status=0 AND quantity=0 AND (series=? OR series='') AND special_property=? AND free_seat>0 ORDER BY id ASC";
				$voidRow = WarehouseAR::model()->find($voidCondtion, array($car->series, $car->special_property));

				if(!empty($voidRow) && !empty($orderConfigId)){
					$row = $voidRow;
					$row->car_type = $car->type;
					$row->color = $car->color;
					$row->cold_resistant = $car->cold_resistant;
					$row->order_config_id = $orderConfigId;
					$row->save();
				} else {
					//根据特殊属性找到合适车列
					if($car->special_property == 1){
						$row = WarehouseAR::model()->findByPk(200);
					} else if ($car->special_property == 0) {
						if($car->series == '6B'){
							// $row = WarehouseAR::model()->find("id=? AND quantity<capacity", array(600));
						}
					} else if ($car->special_property == 9) {
						$row = WarehouseAR::model()->findByPk(1000);
					}
				}
			} 
		}

		if(empty($row)){
			$row = WarehouseAR::model()->findByPk(2000);
		}

		//如明确了进入的车列
		if(!empty($row)){
			//进入车列
			$row->quantity += 1;
			$row->free_seat -= 1;
			if($row->free_seat == 0) {
				$row->status = 1;
			}
			$row->save();
			
			//原库位数量减1
			if($car->warehouse_id>900){
				$oldRow = WarehouseAR::model()->findByPk($car->warehouse_id);
				$oldRow->saveCounters(array('quantity'=>-1));
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
			if($order->count == $order->amount) {
				throw new Exception('此订单出库数量已满，无法出库');
			}
			$order->saveCounters(array('count'=>1));
			// $order->count += 1;
			// $order->save();
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
					$out_finish_time=date("YmdHis");
					$sql = "UPDATE `order` SET `status`=2, out_finish_time='$out_finish_time' WHERE board_number='$boardNumber' AND `status`=1";
					Yii::app()->db->createCommand($sql)->execute();
					// $order->out_finish_time = $out_finish_time;
					// $order->status = 2;
					// $order->save();
					$order->saveAttributes(array("out_finish_time"=>"$out_finish_time","status"=>2));
				}
			}

			$rowWDI = WarehouseAR::model()->findByPk(1);
			$rowWDI->saveCounters(array('quantity'=>-1));

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

			// $order->save();
		}

		return $data;
	}

	public function resetFreeSeat($warehouseId) {
		$row = WarehouseAR::model()->findByPk($warehouseId);

		if(!empty($row)){
			$row->free_seat = $row->capacity - $row->quantity;
			$row->status = 0;
			$row->save();
		}

		return $warehouseId;
	}
	
}
