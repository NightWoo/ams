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
		$orderCarType = $this->orderCarType();
		foreach($orders as $order){
			$ar = new OrderAR();
			$ar->order_number = $order['orderNumber'];
			$ar->order_detail_id = $order['orderDetailId'];
			$ar->order_nature = $order['orderNature'];
			$ar->standby_date = $order['standbyDate'];
			$ar->amount = $order['amount'];
			$ar->series = $order['series'];
			$ar->car_type = $orderCarType[$order['carType']];
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
			$ar->order_type = $order['orderType'];
			$ar->carrier = $order['carrier'];
			$ar->to_count = 1;
			if(!empty($order['country'])){
				$ar->country = $order['country'];
			}

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
			$new->to_count = 1;

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
		
		$seeker = new OrderSeeker();
		$order = $seeker->matchQuery($series, $carType, $orderConfigId, $color, $coldResistant, $date);

		if(!empty($order)) {
			$order->saveCounters(array('hold'=>1));

			if($order->hold == $order->amount && $order->standby_finish_time == '0000-00-00 00:00:00'){
				$standby_finish_time = date('YmdHis');
				$saveSuccess=$order->saveAttributes(array("standby_finish_time"=>"$standby_finish_time"));
			}
			
			$data['orderId'] = $order->id;
			$data['orderNumber'] = $order->order_number;
			$data['distributorName'] = $order->distributor_name;
			$data['activateTime'] = $order->activate_time;
			$data['laneId'] = $order->lane_id;
			$data['lane'] = LaneAR::model()->findByPk($order->lane_id)->name;

			$success = true;
		}

		return array($success, $data);
	}

	public function getCarStandby($standbyDate, $standbyArea=0, $series="") {
		//$matchedOrder = new OrderAR;
		$data = array();

		$condition = "standby_date=? AND status=1 AND amount>hold AND amount>count";

		if(!empty($series)){
	        $arraySeries = $this->parseSeries($series);
	        $cTmp = array(); 
	        foreach($arraySeries as $series){
	        	$cTmp[] = "series='$series'";
	        }
	        $condition .= " AND (" . join(' OR ', $cTmp) . ")";
        };

        $condition .= " ORDER BY priority ASC";
		$orders = OrderAR::model()->findAll($condition, array($standbyDate));
		if(!empty($orders)){
			foreach($orders as $order) {
				$sql = "SELECT id FROM car_config WHERE order_config_id = $order->order_config_id";
        		$configId = Yii::app()->db->createCommand($sql)->queryColumn();
        		$configId = "(" . join(',', $configId) . ")";

        		switch($standbyArea){
        			case 0 :
        				$matchCondition = "warehouse_id>1 AND warehouse_id<=200 AND series=? AND color=? AND cold_resistant=? AND special_property<9 AND config_id IN $configId AND warehouse_time>'0000-00-00 00:00:00'";
        				break;
        			case 14 :	
						$matchCondition = "warehouse_id>=400 AND warehouse_id<500 AND series=? AND color=? AND cold_resistant=? AND special_property<9 AND config_id IN $configId AND warehouse_time>'0000-00-00 00:00:00'";
						break;
					case 27 :	
						$matchCondition = "warehouse_id>=500 AND warehouse_id<600 AND series=? AND color=? AND cold_resistant=? AND special_property<9 AND config_id IN $configId AND warehouse_time>'0000-00-00 00:00:00'";
						break;
					case 35 :	
						$matchCondition = "warehouse_id>=600 AND warehouse_id<700 AND series=? AND color=? AND cold_resistant=? AND special_property<9 AND config_id IN $configId AND warehouse_time>'0000-00-00 00:00:00'";
						break;
					default :
        				$matchCondition = "warehouse_id>1 AND warehouse_id<=200 AND series=? AND color=? AND cold_resistant=? AND special_property<9 AND config_id IN $configId AND warehouse_time>'0000-00-00 00:00:00'";
        		}

				if($order['order_type'] === '出口'){
					$matchCondition .= " AND special_property=1 AND (UPPER(special_order)='{$order['order_number']}' OR UPPER(remark) LIKE '%{$order['order_number']}%')";
				}

				$values = array($order->series, $order->color, $order->cold_resistant);
				
				$matchCondition .= "  ORDER BY warehouse_time ASC";
				$matchedCar = CarAR::model()->find($matchCondition, $values);
				if(!empty($matchedCar)){
			 		$matchedOrder = $order;
			 		// $matchedCar = $car;
			 		break;
				 }
			}
		}

		if(!empty($matchedCar)){
			$warehouse = WarehouseAR::model()->findByPk($matchedCar->warehouse_id);
			if(!empty($warehouse)){

				$matchedOrder->saveCounters(array('hold'=>1));

				if($matchedOrder->hold == $matchedOrder->amount){
					// $standby_finish_time = date('YmdHis');
					// $matchedOrder->saveAttributes(array("standby_finish_time"=>"$standby_finish_time"));
					$matchedOrder->standby_finish_time = date('YmdHis');
					$matchedOrder->save();
				}

				$warehouse->quantity -= 1;
				if($warehouse->quantity == 0) {
					$warehouse->car_type = '';
					$warehouse->color = '';
					$warehouse->order_config_id = 0;
					$warehouse->cold_resistant = 0;
					//$warehouse->car_year = '';

					$warehouse->free_seat = $warehouse->capacity;
					$warehouse->status = 0;
				}
				$warehouse->save();
				
				$matchedCar->order_id = $matchedOrder->id;
				$matchedCar->old_wh_id = $matchedCar->warehouse_id;
				$matchedCar->warehouse_id = 1;		//WDI
				$matchedCar->status = 'WDI';
				$matchedCar->area = 'WDI';
				$matchedCar->save();

				$rowWDI = WarehouseAR::model()->findByPk(1);
				$rowWDI->saveCounters(array('quantity'=>1));
				
				$configName = CarConfigAR::model()->findByPk($matchedCar->config_id)->name;
				$carModel = CarTypeMapAR::model()->find('car_type=?', array($matchedCar->type))->car_model;

				$data['vin'] = $matchedCar->vin;
				$data['type'] = $matchedCar->type;
				$data['type_info'] = $carModel. "/" . $configName;
				$data['series'] = $matchedCar->series;
				$data['color'] = $matchedCar->color;
				$data['order_number'] = $matchedOrder->order_number;
				$data['distributor_name'] = $matchedOrder->distributor_name;
				$data['activate_time'] = $matchedOrder->activate_time;
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

	public function matchManually($orderId, $vins){
		$vins = CJSON::decode($vins);
		if(empty($orderId) || empty($vins)){
			return;
		}

		$order = OrderAR::model()->findByPk($orderId);
		if($order->amount == $order->hold){
			$orderNumber = $order->order_number;
			throw new Exception("$orderNumber已备齐，无法继续匹配车辆", 1);
		}
		$successVins = array();
		foreach($vins as $vin){
			$car = Car::create($vin);
			if(!empty($car)){
				if($car->car->warehouse_time == '0000-00-00 00:00:00'){
					throw new Exception($vin. "未入库，不可匹配订单", 1);
				}

				if($car->car->order_id > 0){
					throw new Exception($vin. "已匹配订单，不可再次匹配", 1);
				}

				if($car->car->distribute_time > '0000-00-00 00:00:00'){
					throw new Exception($vin. "已出库，不可匹配订单", 1);
				}

				$warehouseId = $car->car->warehouse_id;
				$row = WarehouseAR::model()->findByPk($warehouseId);
				$row->saveCounters(array('quantity'=>-1));

				$rowWDI = WarehouseAR::model()->findByPk(1);
				$rowWDI->saveCounters(array('quantity'=>1));

				$car->enterNode('OutStandby');
				$car->car->order_id = $order->id;
				$car->car->old_wh_id = $warehouseId;
				$car->car->warehouse_id = 1;
				$car->car->area = 'WDI';
				$car->car->save();
				$successVins[] = $vin;

				$order->saveCounters(array('hold'=>1));
				if($order->hold == $order->amount){
					if($order->activate_time == '0000-00-00 00:00:00'){
						$activate_time = date("YmdHis");
						$order->saveAttributes(array("activate_time"=>"$activate_time"));
					}
					$standby_finish_time = date("YmdHis");
					$order->saveAttributes(array("standby_finish_time"=>"$standby_finish_time"));
					break;
				}
			}
		}

		// $order->save();

		return array($order->order_number, $successVins);
	}

	public function printByOrder($orderId){
		$order = OrderAR::model()->findByPk($orderId);
		// $this->updateOrderSellInfo($order);

		if($order->amount > $order->count){
			throw new Exception("订单明细".$order->order_detail_id."_". $order->order_number. "_" . $order->distributor_name ."未完成，暂不可传输打印");
		}
		$sql = "SELECT vin FROM car WHERE order_id=$orderId AND distribute_time>'0000-00-00 00:00:00' ORDER BY distribute_time ASC";
		$vins = Yii::app()->db->createCommand($sql)->queryColumn();
		foreach($vins as $vin){
			$car = Car::create($vin);
			$outDate = $car->car->distribute_time;
            $clientIp = $_SERVER["REMOTE_ADDR"];
			$retry = 5;
			if($car->car->special_property<1){
	            do{
					$ret = $car->throwCertificateData($outDate, $clientIp);
					if($ret === false){
						$curTime = date("YmdHis");
						BmsLogger::warning($vin . " throwCertificateData failed @ " . $curTime);
					}
					--$retry;
	            } while ($ret === false &&  $retry>0);
			}
			$markOnly=false;
			if($car->car->special_property == 1){
				$markOnly=true;
			}
            $car->throwInspectionSheetData($markOnly);
		}
		$order->is_printed = 1;
		$order->save();

		return $order->board_number;
	}

	public function printByOrders($orderIds){
		foreach($orderIds as $orderId){
			$this->printByOrder($orderId);
		}

		return $orderIds;
	}

	public function printByBoard($boardNumber) {
		if(empty($boardNumber)){
			throw new Exception ('无法按空备板编号打印');
		}

		$orderSql = "SELECT id , board_number ,count, amount FROM `order` WHERE board_number='$boardNumber' AND `status`>0 AND is_printed=0";
		$orders = Yii::app()->db->createCommand($orderSql)->queryAll();

		if(!empty($orders)){
			$countSum = 0;
			$amountSum = 0;
			foreach($orders as $order){
				$countSum += $order['count'];
				$amountSum += $order['amount'];
			}
			if($amountSum > $countSum){
				throw new Exception("此板未完成，暂不可传输打印");
			}
			foreach($orders as $order){
				$this->printByOrder($order['id']);
			}
		}
		return $boardNumber;
	}

	public function updateOrderSellInfo($order = null){
		if(!empty($order)){
			$orderDetailId = $order->order_detail_id;
			$seeker = new OrderSeeker();

			$sellOrder = $seeker->getSellOrderDetail($orderDetailId);

			$order->sell_car_type = $sellOrder['sell_car_type'];
			$order->car_type = $sellOrder['car_type'];
			$order->color = $sellOrder['color'];
			$order->sell_color = $sellOrder['sell_color'];
			$order->cold_resistant = $sellOrder['cold_resistant'];

			$order->save();
		}

	}

	public function printBySpecialOrder($specialOrder, $forceThrow=false, $country='出口', $clime='出口'){
		$specialOrder = trim($specialOrder);
		$specialOrder = strtoupper($specialOrder);
		if(empty($specialOrder)){
			throw new Exception('特殊订单号不可为空');
		}
		$specialOrder = strtoupper($specialOrder);
		if(empty($country)){
			$country = '出口';
		}
		$condition = "(UPPER(special_order)='$specialOrder' OR UPPER(remark) LIKE '%$specialOrder%') AND special_property";

		$sql = "SELECT vin FROM car WHERE $condition ORDER BY serial_number ASC";
		$vins = Yii::app()->db->createCommand($sql)->queryColumn();

		$ret = $this->printByVins($vins, $specialOrder, $forceThrow);
		
		return $ret;
	}

	public function printByVins($vins, $specialOrder, $forceThrow=false){
		$total = 0;
		$certificateSuccess = 0;
		$inspectionSuccess = 0;
		$certificateFailures = array();
		$inspectionFailures = array();
		foreach($vins as $vin){
			++$total;
			$car = Car::create($vin);
			if(empty($car->car->engine_code)){
				$certificateFailures[] = $vin;
				$inspectionFailures[] = $vin;
				continue;
			}
			if($car->car->distribute_time === '0000-00-00 00:00:00'){
				$outDate = date("Y-m-d H:i:s");
			}else{
				$outDate = $car->car->distribute_time;
			}
            $clientIp = $_SERVER["REMOTE_ADDR"];
			$retry = 5;
            do{
				$ret = $car->throwCertificateDataExport($specialOrder, $forceThrow, $outDate, $clientIp);
				if($ret === false){
					$curTime = date("YmdHis");
					BmsLogger::warning($vin . " throwCertificateData failed @ " . $curTime);
					if($retry === 1){
						$certificateFailures[] = $vin;
					}
				} else {
					++$certificateSuccess; 
				}
				--$retry;
            } while ($ret === false &&  $retry>0);

            $isTLpassed = $car->isTestLinePassed();
            $throwRet = 0;
            if($isTLpassed){
	           	$throwRet = $car->throwInspectionSheetDataExport($specialOrder);
            }
           	if($throwRet>0){
           		++$inspectionSuccess;
           	}else{
           		$inspectionFailures[] = $vin;
           	}
		}

		return array(
			"total" => $total,
			"certificateSuccess" => $certificateSuccess,
			"inspectionSuccess"=>$inspectionSuccess,
			"certificateFailures" => $certificateFailures,
			"inspectionFailures" => $inspectionFailures
		);
	}

	private function orderCarType($series = ""){
		$orderCarType = array();
		$condition = "";
		if(!empty($series)){
			$condition = "WHERE series ='$series'";
		}
		$sql = "SELECT car_type, order_type_name FROM car_type_map $condition";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($datas as $data){
			$orderCarType[$data['car_type']] = $data['order_type_name'];
		}
		return $orderCarType;
	}

	private function parseSeries($series) {
		if(empty($series) || $series === 'all') {
            $series = array('F0', 'M6', '6B');
        } else {
            $series = explode(',', $series);
        }
		return $series;
	}
}
