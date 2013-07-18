<?php
Yii::import('application.models.Order');
Yii::import('application.models.OrderSeeker');
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.LaneOrderAR');
Yii::import('application.models.AR.WarehouseAR');

class OrderController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionGetOriginalOrders(){
		$orderNumber = $this->validateStringVal('orderNumber', '');
		try {
			$seeker = new OrderSeeker();
			$orders = $seeker->getOriginalOrders($orderNumber);
			$this->renderJsonBms(true, 'OK', $orders);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionCheckDetail(){
		try{
			$details = $this->validateStringVal('orderDetails', '{}');
			$order = new Order();
			$data = $order->checkDetail($details);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function actionGenerate(){
		$transaction = Yii::app()->db->beginTransaction();
		try{
			$details = $this->validateStringVal('orderDetails', '{}');
			$order = new Order();
			$order->genernate($details);
			$transaction->commit();
			$this->renderJsonBms(true, 'OK', null);
		} catch(Exception $e) {
			$transaction->rollback();
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function actionGetSpecialOrders(){
		$specialNumber = $this->validateStringVal('specialNumber', '');
		try {
			$seeker = new OrderSeeker();
			$orders = $seeker->getSpecialOrders($specialNumber);
			$this->renderJsonBms(true, 'OK', $orders);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionGetBoardNumber() {
		try {
			$seeker = new OrderSeeker();
			$data = $seeker->generateBoardNumber();
			$this->renderJsonBms(true, 'OK', $data);
		}catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}	
	}

	public function actionQuery(){
		try{
			$standbyDate = $this->validateStringVal('standbyDate', '');
			$standbyDateEnd = $this->validateStringVal('standbyDateEnd', '');
			$orderNumber = $this->validateStringVal('orderNumber', '');
			$boardNumber = $this->validateStringVal('boardNumber', '');
			$distributor = $this->validateStringVal('distributor', '');
			$status = $this->validateStringVal('status', '0');
			$series = $this->validateStringVal('series', '');		
			$orderBy = $this->validateStringVal('orderBy', 'board_number,lane_id,priority,`status`');		

			$seeker = new OrderSeeker();
			$data = $seeker-> query($standbyDate, $orderNumber, $distributor, $status, $series, $orderBy, $standbyDateEnd, $boardNumber);

			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionQueryBoardOrders(){
		try{
			$standbyDate = $this->validateStringVal('standbyDate', '');
			$standbyDateEnd = $this->validateStringVal('standbyDateEnd', '');
			$orderNumber = $this->validateStringVal('orderNumber', '');
			$boardNumber = $this->validateStringVal('boardNumber', '');
			$distributor = $this->validateStringVal('distributor', '');
			$carrier = $this->validateStringVal('carrier', '');
			$status = $this->validateStringVal('status', '0');
			$series = $this->validateStringVal('series', '');		
			$orderBy = $this->validateStringVal('orderBy', 'board_number,lane_id,priority,`status`');		

			$seeker = new OrderSeeker();
			$data = $seeker-> queryBoardOrders($standbyDate, $orderNumber, $distributor, $status, $series, $orderBy, $standbyDateEnd, $boardNumber, $carrier);

			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionQueryBoardInfo(){
		try{
			$orderSeeker = new OrderSeeker();
			$data = $orderSeeker->queryBoardInfo();
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e){
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionQueryOrderInBoardInfo(){
		try{
			$orderSeeker = new OrderSeeker();
			$data = $orderSeeker->queryOrderInBoardInfo();
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e){
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionInc() {
		$id = $this->validateIntVal('id', 0);
		try {
			$order = OrderAR::model()->findByPk($id);
			if(!empty($order)) {
				$higher = OrderAR::model()->find('priority=? AND standby_date=? AND status=1', array($order->priority - 1, $order->standby_date));
				if(!empty($higher)){
					$order->priority = $higher->priority;
					$higher->priority = $higher->priority + 1;
					
					$order->save();
					$higher->save();
				}
			}
			$this->renderJsonBms(true, 'OK', '');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionTop() {
		$id = $this->validateIntVal('id', 0);
		try {
			$order = OrderAR::model()->findByPk($id);
			if(!empty($order)) {
				$highers = OrderAR::model()->findAll('priority<? AND standby_date=? AND status=1', array($order->priority, $order->standby_date));
				if(!empty($highers)) {
					$order->priority = 0;
					foreach($highers as $higher) {
						$higher->priority = $higher->priority + 1;
						$higher->save();
					}
					$order->save();
				}
			}
			$this->renderJsonBms(true, 'OK', '');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}	
	}

	public function actionSave() {
		$id = $this->validateIntVal('id', '');
		$standbyDate = $this->validateStringVal('standbyDate', date('Y-m-d'));
		$status = $this->validateIntVal('status', 0);
		$laneId = $this->validateStringVal('laneId', '');
		$distributorName = $this->validateStringVal('distributorName', '');
		$amount = $this->validateStringVal('amount', '');
		$series = $this->validateStringVal('series', '');
		$carType = $this->validateStringVal('carType', '');
		$orderConfigId = $this->validateIntVal('orderConfigId', 0);
		$color =$this->validateStringVal('color', '');
		$coldResistant = $this->validateIntVal('coldResistant', 0);
		$remark = $this->validateStringVal('remark', '');
		$boardNumber = $this->validateStringVal('boardNumber', '');
		$carrier = $this->validateStringVal('carrier', '');
		$toCount = $this->validateIntVal('toCount', 0);
		try {
			if(empty($standbyDate)) {
				throw new Exception('备车日期不能为空');
			}
			if(empty($series)) {
				throw new Exception('车系不能为空');
			}
			if(empty($carType)) {
				throw new Exception('车型不能为空');
			}
			if(empty($orderConfigId)) {
				throw new Exception('配置不能为空');
			}
			if(empty($color)) {
				throw new Exception('颜色不能为空');
			}
			if(empty($distributorName)) {
				throw new Exception('经销商不能为空');
			}

			if($status == 1 && $laneId == 0){
				throw new Exception('激活订单必须选择车道');
			}

			$order = OrderAR::model()->findByPk($id);
			if(empty($order)) {
				$order = new OrderAR();
				$order->id = $id;
				$order->create_time = date('YmdHis');
				$order->order_number = "NBDD-" . date('YmdHis');
				$order->order_type = "内部";
				$order->sell_color = $color;
				// $order->save();
				// $max = OrderAR::model()->find('standby_date=? AND status=1 ORDER BY priority DESC', array($standbyDate));
				// $order->priority = 0;
				// if(!empty($max) && $status == 1) {
				// 	$order->priority = $max->priority + 1;
				// }
			}
			if($status == 1 ){
				$samePriority = OrderAR::model()->find('standby_date=? AND status=1 AND priority=?', array($standbyDate, $order->priority));
				if(!empty($samePriority) && $samePriority->id != $order->id) {
					$max = OrderAR::model()->find('standby_date=? AND status=1 ORDER BY priority DESC', array($standbyDate));
					$order->priority = $max->priority + 1;
				}

			}else{
				$order->priority = 0;
			}

			if(empty($laneId)) $order->priority = 0;

			$order->standby_date = $standbyDate;
			$order->status = $status;
			$order->lane_id = $laneId;
			$order->distributor_name = $distributorName;
			$order->amount = $amount;
			$order->series = $series;
			$order->car_type = $carType;
			$order->order_config_id = $orderConfigId;
			$order->color = $color;
			$order->cold_resistant = $coldResistant;
			$order->remark = $remark;
			$order->board_number = $boardNumber;
			$order->carrier = $carrier;
			$order->to_count = $toCount;

			$order->modify_time = date('YmdHis');
			$order->user_id = Yii::app()->user->id;

			if($order->amount <= $order->hold && $order->standby_finish_time === '0000-00-00 00:00:00'){
				$order->standby_finish_time = date("YmdHis");
			}

			if($order->status == 1){
				$curDate = DateUtil::getCurDate();
				if($order->activate_time == '0000-00-00 00:00:00' && $order->standby_date == $curDate){
					$order->activate_time = date('YmdHis');
					$order->lane_status = 1;
				}
			}
			
			if($order->status === 2){
				if($order->standby_finish_time === '0000-00-00 00:00:00' && $order->amount === $order->hold){
					$order->standby_finish_time = date("YmdHis");
				}
				if($order->out_finish_time === '0000-00-00 00:00:00' && $order->amount === $order->count){
					$order->out_finish_time = date("YmdHis");
				}
				$order->to_count = 0;	
			}

			//update from sell order
			// $seeker = new OrderSeeker();
			// $sellOrder = $seeker->getSellOrderDetail($order->order_detail_id);
			// if(!empty($sellOrder)){
			// 	$order->sell_car_type = $sellOrder['sell_car_type'];
			// 	$order->car_type = $sellOrder['car_type'];
			// 	$order->color = $sellOrder['color'];
			// 	$order->sell_color = $sellOrder['sell_color'];
			// 	// $order->cold_resistant = $sellOrder['cold_resistant'];

			// }

			$order->save();

			$this->renderJsonBms(true, 'OK', '');

		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionSplit(){
		$orderId = $this->validateIntVal('id', 0);
		$number = $this->validateIntVal('number', 0);
		$laneId = $this->validateIntVal('laneId', 0);
		try{
			$order = new Order();
			$order->split($orderId, $number, $laneId); 

			$this->renderJsonBms(true, 'OK', '');
		} catch(Exception $e){
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionDelete() {
		$id = $this->validateIntVal('id', 0);
		try {
			$order = OrderAR::model()->findByPk($id);
			if(!empty($order)) {
				if($order->status == 1){
					$lowers = OrderAR::model()->findAll('standby_date=? AND priority>?', array($order->standby_date, $order->priority));
					foreach($lowers as $lower) {
						$lower->priority -= 1;
						$lower->save();
					}
				}
				$order->delete();
			}
			$this->renderJsonBms(true, 'OK', '');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionGetDistributorList() {
		$name = $this->validateStringVal('distributorName', '');
		try {
			if(empty($name)){
				throw new Exception('distributor name cannot be null');
			}
			$seeker = new DistributorSeeker;
			$data = $seeker->getNameList($name);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionGetDistributorId() {
		$name = $this->validateStringVal('distributorName', '');
		try {
			if(empty($name)){
				throw new Exception('distributor name cannot be null');
			}
			$seeker = new DistributorSeeker;
			$data = $seeker->getDistributorId($name);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionGetCarStandby() {
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$driverId = $this->validateIntVal('driverId', 0);
			$standbyArea = $this->validateIntVal('standbyArea', 0);
			$series = $this->validateStringVal('series', '');
			$curDate = DateUtil::getCurDate();
			$order = new Order;
			$data = $order->getCarStandby($curDate, $standbyArea, $series);

			$car = Car::create($data['vin']);
			$car->throwMarkPrintData();
			$car->enterNode('OutStandby', $driverId);
			$driverName = User::model()->findByPk($driverId)->display_name;
			$data['driver_name'] = $driverName;

			$transaction->commit();
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$transaction->rollback();
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionMatchManually(){
		$transaction = Yii::app()->db->beginTransaction();
		try {
			Yii::app()->permitManager->check('ORDER_MATCH_MANUALLY');
			$orderId = $this->validateIntVal('orderId', 0);
			$vins = $this->validateStringVal('vins', '{}');
			$order = new Order();
			list($orderNumber, $successVins) = $order->matchManually($orderId, $vins);

			$data = array(
				'orderNumber' => $orderNumber,
				'successVins' => $successVins,
			);
			
			$transaction->commit();
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$transaction->rollback();
			if($e->getMessage() == 'permission denied'){
				$this->renderJsonBms(false, '抱歉，您无此操作权限');
			}else{
				$this->renderJsonBms(false, $e->getMessage());
			}
		}
	}

	public function actionHoldRelease() {
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$vin = $this->validateStringVal('vin', '');
			//$releaseOnly = $this->validateIntVal('releaseOnly', 0);
			$toVQ3 = $this->validateIntVal('toVQ3', 0);

			//$car = CarAR::model()->find('vin=?', array($vin));
			$car = Car::create($vin);
			$order = OrderAR::model()->findByPk($car->car->order_id);
			$data = array();

			// if(strstr($car->car->status, '公司外') !== false) {
			// 	throw new Exception($car->vin. '已出库，无法释放订单');
			// }

			if(!empty($order)) {
				///释放占位订单
				$order->hold -= 1;
				$order->standby_date = DateUtil::getCurDate();
				//如果已出库，备车数量亦需减1
				if(strstr($car->car->status, '公司外') !== false) {
					$order->count -= 1;

					//优先级置顶
					$highers = OrderAR::model()->findAll('priority<? AND standby_date=? AND status=1', array($order->priority, $order->standby_date));
					if(!empty($highers)) {
						$order->priority = 0;
						foreach($highers as $higher) {
							$higher->priority = $higher->priority + 1;
							$higher->save();
						}
						$order->save();
					}
				}
				
				if($order->status == 2){
					$order->status =1;
				}
				$car->car->order_id = 0;
				$car->car->lane_id = 0;
				$car->car->distributor_name='';
				$car->car->distributor_code='';
				$car->car->distribute_time = '0000-00-00 00:00:00';
				$car->car->save();
				$order->save();
				$message = $vin . '已释放订单' . $order->order_number .'_'. $order->distributor_name . '备车占位';

			} else {
				throw new Exception($vin . '释放订单失败，订单不存在或未匹配订单');
			}

			//退回成品库异常区
			if(empty($toVQ3)) {
				$car->enterNode('CHECK_IN');

				//异常区X warehouse_id=1000
				$warehouse = WarehouseAR::model()->findByPk(1000);
				$warehouse->quantity += 1;
				$warehouse->free_seat -= 1;

				$car->car->warehouse_id = 1000;
                $car->car->area = $warehouse->area;
                $car->car->status = '成品库';
                $car->car->save();

				$message = $vin . '已成功退回成品库异常区X，请开往' . $warehouse->row;

				// $oldRow = WarehouseAR::model()->findByPk($car->car->warehouse_id);
				// if(!empty($oldRow)){
				// 	$oldRow->quantity -= 1;
				// 	$oldRow->save();
				// }
			}

			//退回VQ3
			if($toVQ3 == 1){
				$car->enterNode('VQ3');
				$car->warehouseReturn('VQ3');
				$car->car->status = 'VQ3退库';
				$car->car->warehouse_id = 0;
				$car->car->warehouse_time = '0000-00-00 00:00:00';
				$car->car->area = '';
				$car->car->save();
				$message = $vin . '已成功退回VQ3，请开往VQ3面漆修正区';
			}

			$transaction->commit();
			$this->renderJsonBms(true, $message, $data);
		} catch(Exception $e) {
			$transaction->rollback();
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionQueryOrderCars() {
		try{
			$standbyDate = $this->validateStringVal('standbyDate', '');
			$standbyDateEnd = $this->validateStringVal('standbyDateEnd', '');
			$orderNumber = $this->validateStringVal('orderNumber', '');
			$boardNumber = $this->validateStringVal('boardNumber', '');
			$distributor = $this->validateStringVal('distributor', '');
			$carrier = $this->validateStringVal('carrier', '');
			$status = $this->validateStringVal('status', '0');
			$series = $this->validateStringVal('series', '');
			$curPage = $this->validateIntVal('curPage', 1);
            $perPage = $this->validateIntVal('perPage', 20);
			$orderBy = $this->validateStringVal('orderBy', 'lane_id,priority,`status`');

			$seeker = new CarSeeker();
			list($total, $data) = $seeker-> queryOrderCar($standbyDate, $orderNumber, $distributor, $status, $series, $curPage, $perPage,$orderBy, $standbyDateEnd, $boardNumber, $carrier);

			$ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'data' => $data,
                    );
			$this->renderJsonBms(true, 'OK', $ret);
		} catch (Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionExportOrderCars() {
		try{
			$standbyDate = $this->validateStringVal('standbyDate', '');
			$standbyDateEnd = $this->validateStringVal('standbyDateEnd', '');
			$orderNumber = $this->validateStringVal('orderNumber', '');
			$boardNumber = $this->validateStringVal('boardNumber', '');
			$distributor = $this->validateStringVal('distributor', '');
			$status = $this->validateStringVal('status', '0');
			$series = $this->validateStringVal('series', '');
			$orderBy = $this->validateStringVal('orderBy', 'lane_id,priority,`status`');

			$seeker = new CarSeeker();
			list($total, $datas) = $seeker-> queryOrderCar($standbyDate, $orderNumber, $distributor, $status, $series, 0, 0,$orderBy,$standbyDateEnd, $boardNumber);
			$content = "车道,订单号,经销商,流水号,VIN,车系,配置,耐寒性,颜色,发动机号,出库时间,原库道\n";
            foreach($datas as $data) {
                $content .= "{$data['lane']},";
                $content .= "{$data['order_number']},";
                $content .= "{$data['distributor_name']},";
                $content .= "{$data['serial_number']},";
                $content .= "{$data['vin']},";
                if($data['series'] === '6B'){
                	$data['series'] = '思锐';
                }
                $content .= "{$data['series']},";
                $content .= "{$data['config_name']},";
                $content .= "{$data['cold']},";
                $content .= "{$data['color']},";
                $content .= "{$data['engine_code']},";
                if($data['distribute_time'] === '0000-00-00 00:00:00'){
                	$data['distribute_time'] = '未出库';
                }
                $content .= "{$data['distribute_time']},";
                $content .= "{$data['row']},";
                $content .= "\n";
            }
			
			$export = new Export('订单车辆明细_' .date('YmdHi'), $content);
            $export->toCSV();
		} catch (Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionQueryPeriod() {
		try{
			$startDate = $this->validateStringVal('startDate', '');
			$endDate = $this->validateStringVal('endDate', '');
			$status = $this->validateStringVal('status', '0');
			$seeker = new OrderSeeker();
			$data = $seeker->queryPeriod($startDate, $endDate);

			$this->renderJsonBms(true, 'OK',  $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionQueryByBoard() {
		try{
			$boardNumber = $this->validateStringVal('boardNumber', '');
			$seeker = new OrderSeeker();
			$data = $seeker->queryByBoard($boardNumber);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionQueryCarsById() {
		try{
			$orderId = $this->validateIntVal('orderId', 0);
			$seeker = new OrderSeeker();
			$data = $seeker->queryCarsById($orderId);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionQueryCarsByIds() {
		try{
			$orderIds = $this->validateArrayVal('orderIds', array());
			$seeker = new OrderSeeker();
			$data = $seeker->queryCarsByIds($orderIds);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionQueryCarsBySpecialOrder() {
		try{
			$specialOrder = $this->validateStringVal('specialOrder', '');
			$seeker = new OrderSeeker();
			list($cars,$total,$isGood) = $seeker->queryCarsBySpecialOrder($specialOrder);
			$ret = array(
				'cars' => $cars,
				'total' => $total,
				'isGood' => $isGood,
				);
			$this->renderJsonBms(true, 'OK', $ret);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionPrintBySpecialOrder() {
		
		try{
			$specialOrder = $this->validateStringVal('specialOrder', '');
			$force = $this->validateIntVal('forceThrow', 0);
			$forceArray = array(false,true);
			$order = new Order();
			$ret = $order->printBySpecialOrder($specialOrder, $forceArray[$force]);

			$this->renderJsonBms(true, 'print success', $ret);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionPrintByOrder() {
		try{
			$orderId = $this->validateIntVal('orderId', 0);
			$order = new Order();
			$board = $order->printByOrder($orderId);

			$this->renderJsonBms(true, 'print success', $board);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionPrintByOrders() {
		try{
			$orderIds = $this->validateArrayVal('orderIds', array());
			$order = new Order();
			$data = $order->printByOrders($orderIds);

			$this->renderJsonBms(true, '打印传输成功', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionPrintByBoard() {
		try{
			$boardNumber = $this->validateStringVal('boardNumber', '');
			$order = new Order();
			$board = $order->printByBoard($boardNumber);

			$this->renderJsonBms(true, 'print success', $board);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionGetOrderConfig() {
		$carSeries = $this->validateStringVal('carSeries', '');
		$carType = $this->validateStringVal('carType', '');
		try {
			$config = new OrderSeeker();
			$data = $config->getConfigList($carSeries, $carType);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());	
		}		
	}

	public function actionGetLaneList() {
		try{
			$seeker = new OrderSeeker();
			$data = $seeker->getLaneList();
			$this->renderJsonBms(true, 'OK', $data);
		}catch(exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
}
