<?php
Yii::import('application.models.Car');
Yii::import('application.models.VinManager');
Yii::import('application.models.SubConfigSeeker');
Yii::import('application.models.AR.WarehouseAR');
Yii::import('application.models.AR.CarConfigAR');
class CarController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionShow() {
		$vin = $this->validateStringVal('vin', '');
		try{
			$car = Car::create($vin);
			$data = $car->car;
			
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false , $e->getMessage());
		}
	}

 	public function actionGetCar(){						//use webservice to get car data, added by wujun
		$vin = $this->validateStringVal('vin', '');		//use webservice to get car data, added by wujun
		VinManager::getCar($vin);						//use webservice to get car data, added by wujun
	}													//use webservice to get car data, added by wujun
	
	public function actionValidatePbs() {
        $vin = $this->validateStringVal('vin', '');
        try{
            //$data = VinManager::importCar($vin);
			$data = VinManager::getCar($vin);			//use webservice to get car data, added by wujun
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }


	public function actionValidateNode() {
        $vin = $this->validateStringVal('vin', '');
		$nodeName = $this->validateStringVal('currentNode', '');
        try{
			if(empty($nodeName)) {
				throw new Exception('node cannot be empty');
			}
            $enterNode = Node::createByName($nodeName);
			$leftNode = $enterNode->getParentNode();
            
            $car = Car::create($vin);
			//$car->leftNode($leftNode->name);
            
            $data = $car->car;
            if(!empty($data['warehouse_id'])){
                $row = WarehouseAR::model()->findByPk($data['warehouse_id'])->row;
                $data['status'] .= '_' . $row ;
            }

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }

	
	public function actionValidateF20() {
        $vin = $this->validateStringVal('vin', '');
        try{
            $car = Car::create($vin);
			//$car->leftNode('F10');
			$car->checkTraceGasolineEngine();
			
            if($car->car->series === 'F0'){
                $car->checkTraceGearBox();
				$absTrace = $car->checkTraceABS();
				if(!empty($absTrace)){
					$barCode = $absTrace->bar_code;
                	$abs = $car->getAbsInfo($barCode);
				}
            }
            $data = $car->car;
			

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }

	public function actionValidateVQ1() {
        $vin = $this->validateStringVal('vin', '');
        try{
            $car = Car::create($vin);
			$car->leftNode('F20');
			$car->passNode('LEFT_WORK_SHOP');
            $data = $car->car;

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }

	public function actionValidateRTF() {
        $vin = $this->validateStringVal('vin', '');
        try{
            $car = Car::create($vin);

			//if($car->car->series != 'M6'){
				 $car->leftNode('VQ1');
			//}

			$car->checkTestLinePassed();
            $fault = Fault::createSeeker();
            $exist = $fault->exist($car, '未修复', array('VQ1_STATIC_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ1还有未修复的故障');
            }
            //$car->passNode('VQ3');
            $data = $car->car;

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }

    public function actionValidateVQ2Leak() {
        $vin = $this->validateStringVal('vin', '');
        try{
            $car = Car::create($vin);
			
			//if($car->car->series != 'M6'){
				$car->leftNode('ROAD_TEST_FINISH');
			//}

            $fault = Fault::createSeeker();
            $exist = $fault->exist($car, '未修复', array('VQ1_STATIC_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ1还有未修复的故障');
            }
			$exist = $fault->exist($car, '未修复', array('VQ2_ROAD_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ2路试还有未修复的故障');
            }
            //$car->passNode('VQ3');
            $data = $car->car;

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }

    public function actionValidateWarehouseReturn() {
        $vin = $this->validateStringVal('vin', '');
        $nodeName = $this->validateStringVal('currentNode', '');
        try{
            if(empty($nodeName)) {
                throw new Exception('node cannot be empty');
            }
            $enterNode = Node::createByName($nodeName);
            // $leftNode = $enterNode->getParentNode();
            
            $car = Car::create($vin);
            //$car->leftNode($leftNode->name);
            if($car->car->warehouse_time='0000-00-00 00:00:00'){
                throw new Exception($vin . '未入库，无法操作退回');
            }
            
            $data = $car->car;
            if(!empty($data['warehouse_id'])){
                $row = WarehouseAR::model()->findByPk($data['warehouse_id'])->row;
                $data['status'] .= '_' . $row ;
            }

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }

	public function actionMatchPlan() {
		$vin = $this->validateStringVal('vin', '');
        try{
            $car = Car::create($vin);
			//$car->leftNode('PBS');

			//“当天计划”的有效时间是指“当天上午08:00至次日上午07：59分”
			//
			$curDate = DateUtil::getCurDate();
            $data = $car->matchPlan($curDate);
            if($data['adapt_plan'] === false) {
                throw new Exception($vin . '对应的车辆没匹配到合适的生产计划');
            }
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }


	public function actionViewComponents() {
		$vin = $this->validateStringVal('vin', '');
		$nodeName = $this->validateStringVal('currentNode', '');
		try{
			if(empty($nodeName)) {
                throw new Exception('node cannot be empty');
            }
            $enterNode = Node::createByName($nodeName);
            $leftNode = $enterNode->getParentNode();

            $car = Car::create($vin);
            //$car->leftNode($leftNode->name);
			if(empty($car->config->name)){
				throw new Exception($vin . '无配置');
			}
            $configName = $car->config->name;
			$data = array(
				'car' => $car->car,
				'components' => $car->getConfigDetail($nodeName),
                'config' => $configName,
			);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}

    //added by wujun
    public function actionValidateBarCode(){
        $vin = $this->validateStringVal('vin', '');
        $componentId = $this->validateIntVal('componentId', '');
        $barCode = $this->validateStringVal('barCode');
        try{
            $car = Car::create($vin);
            $car->checkBarCode($componentId, $barCode);
            $this->renderJsonBms(true, 'OK' , $barCode);
        } catch(Exception $e){
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

	public function actionShowTrace() {
		$vin = $this->validateStringVal('vin', '');
        $node = $this->validateStringVal('node', '');
        $series = $this->validateStringVal('series', '');
		$serialNumber = $this->validateStringVal('serialNumber', '');
        try{
            $seeker = new CarSeeker();
            $vin = $seeker->queryCar($vin,$series,$serialNumber);
            if(empty($vin)){
                throw new Exception('查无车辆');
            }
            $car = Car::create($vin);
            $data = $car->getAllTrace($node);
            $status = $car->car->status;
            if(!empty($car->car->warehouse_id)){
                $status .= '-' . WarehouseAR::model()->findByPk($car->car->warehouse_id)->row;
            }
            if(!empty($car->car->lane_id)){
                $status .= '-' . LaneAR::model()->findByPk($car->car->lane_id)->name;
            }
            if(!empty($car->car->distributor_name)){
                $status .= '-' . $car->car->distributor_name;
            }

            $carData=$car->car->getAttributes();
            if($carData['series']==='6B'){
                $carData['series'] = '思锐';
            }
            $carData['config_name'] = '';
            if(!empty($car->car->config_id)){
                $carData['config_name'] = CarConfigAR::model()->findByPk($car->car->config_id)->name;
            }

            $this->renderJsonBms(true, 'OK', array('traces' => $data, 'car'=> $carData, 'status' =>$status));
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}

	public function actionExportTrace() {
        $vin = $this->validateStringVal('vin', '');
        $node = $this->validateStringVal('node', '');
        try{
            $car = Car::create($vin);
            $datas = $car->getAllTrace($node);
			
			$title = "录入时间,节点,故障记录,故障状态,录入人员,备注\n";
			$content = "";
			foreach($datas as $data) {
				$content .= "{$data['create_time']},";
				$content .= "{$data['node_name']},";
				$content .= "{$data['fault']},";
				$content .= "{$data['fault_status']},";
				$content .= "{$data['user_name']},";
				$content .= "{$data['modify_time']}";
				$content .= "\n";
			}
			$export = new Export($vin . '车辆查询_' .date('Ymd'), $title . $content);
			$export->toCSV();


        } catch(Exception $e) {
        }
    }

	public function actionValidateLWS() {
        $vin = $this->validateStringVal('vin', '');
        try{
            $car = Car::create($vin);
				
			$car->leftNode('VQ1');

			$fault = Fault::createSeeker();
            $exist = $fault->exist($car, '未修复', array('VQ1_STATIC_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ1还有未修复的故障');
            }
            $car->passNode('ENTER_CHECK_SHOP');
            $data = $car->car;

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }


	public function actionValidateCL() {
        $vin = $this->validateStringVal('vin', '');
        try{
            $car = Car::create($vin);
            $car->leftNode('ENTER_CHECK_SHOP');
            $car->passNode('ROAD_TEST_START');
            $data = $car->car;

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }



	public function actionValidateVQ3() {
        try{
            $vin = $this->validateStringVal('vin', '');

            $car = Car::create($vin);
			
			//if($car->car->series != 'M6'){
				$car->leftNode('VQ2');
			//}

            $fault = Fault::createSeeker();
            $exist = $fault->exist($car, '未修复', array('VQ2_ROAD_TEST_', 'VQ2_LEAK_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ2还有未修复的故障');
            }
			$exist = $fault->exist($car, '未修复', array('VQ1_STATIC_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ1还有未修复的故障');
            }
			
			if($car->car->warehouse_time>'0000-00-00 00:00:00') {
                throw new Exception ($vin .'已入库，无法录入VQ3');
            }
			
			if($car->car->distribute_time>'0000-00-00 00:00:00') {
                throw new Exception ($vin .'已出库，无法录入VQ3');
            }

            $car->passNode('CHECK_IN');


            $this->renderJsonBms(true, 'OK', $car->car);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

	public function actionValidateWDI() {
        $vin = $this->validateStringVal('vin', '');
        try{
            $car = Car::create($vin);

            $data = $car->car;

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }


	public function actionValidateCI() {
        try{
            $vin = $this->validateStringVal('vin', '');
            $area = $this->validateIntVal('area',0);

            $car = Car::create($vin);

			//if($car->car->series != 'M6'){
				$car->leftNode('VQ3');
			//}

			$fault = Fault::createSeeker();
        	$exist = $fault->exist($car, '未修复', array('VQ3_FACADE_TEST_'));
			if(!empty($exist)) {
            	throw new Exception ('VQ3还有未修复故障');
        	}
			$exist = $fault->exist($car, '未修复', array('VQ2_ROAD_TEST_', 'VQ2_LEAK_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ2还有未修复的故障');
            }
            $exist = $fault->exist($car, '未修复', array('VQ1_STATIC_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ1还有未修复的故障');
            }
			if($car->car->warehouse_id > 0 && $car->car->warehouse_id <= 200){
				$row = WarehouseAR::model()->findByPk($car->car->warehouse_id)->row;
				throw new Exception ('此车状态为成品库_'. $row .'，不可重复入库');
			}
			
			if($car->car->distribute_time != '0000-00-00 00:00:00'){
				throw new Exception($vin . '已出库，不可再入库');
			}

			//$car->passNode('CHECK_OUT');

            $data = $car->car;
            if(!empty($data['warehouse_id'])){
                $row = WarehouseAR::model()->findByPk($data['warehouse_id'])->row;
                $data['status'] .= '_' . $row ;
            }

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionValidateDataThrow() {
        $vin = $this->validateStringVal('vin', '');
        try{
            $car = Car::create($vin);

            $data = $car->car;

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }


	public function actionEnterCI() {
        try{
            $vin = $this->validateStringVal('vin', '');
            $area = $this->validateIntVal('area',0);

            $car = Car::create($vin);
            $car->leftNode('VQ3');
            $car->enterNode('CHECK_IN',0,true);

            $this->renderJsonBms(true, 'OK', $vin);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

	public function actionSearchSubConfigQueue() {
		$type = $this->validateStringVal('type', 'subInstrument');
		$stime = $this->validateStringVal('stime');
		$etime = $this->validateStringVal('etime');
		$status = $this->validateIntVal('status', 0);
		$vin = $this->validateStringVal('vin');
		
		$seeker = new SubConfigSeeker($type);
		$datas = $seeker->queryAll($vin, $status, $stime, $etime);
		$this->renderJsonBms(true, 'OK', $datas);
	}

	public function actionPrintSubConfig() {
		try{
            $vin = $this->validateStringVal('vin', '');
			$type = $this->validateStringVal('type', 'subInstrument');
            $car = Car::create($vin);
			$datas = $car->generateSubConfigData($type);
			
            $this->renderJsonBms(true, 'OK', $datas);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }

	}

	public function actionValidateSubConfig() {
		try{
            $vin = $this->validateStringVal('vin', '');
            $type = $this->validateStringVal('type', 'subInstrument');
			
			$seeker = new SubConfigSeeker($type);
			$seeker->validate($vin);

			$data = VinManager::getCar($vin);

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
	}

    public function actionQueryBalanceDetail() {
        try{
            $state = $this->validateStringVal('state', 'WH');
            $series = $this->validateStringVal('series', '');
            $curPage = $this->validateIntVal('curPage', 1);
            $perPage = $this->validateIntVal('perPage', 20);
            
            $seeker = new CarSeeker();
            $whAvailableOnly = false;
            if($state === 'WHin'){
                $whAvailableOnly = true;
            }
            list($total, $data) = $seeker->queryBalanceDetail($state, $series, $curPage, $perPage, $whAvailableOnly);
            $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'data' => $data,
                    );
            $this->renderJsonBms(true, 'OK', $ret);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionQueryBalanceAssembly(){
        try{
            $state = $this->validateStringVal('state', 'assembly'); 
            $seeker = new CarSeeker();
            $data = $seeker-> queryAssemblyBalance($state);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e){
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionQueryBalanceDistribute() {
        try{
            $state = $this->validateStringVal('state', 'assembly');
            $series = $this->validateStringVal('series', 'F0');
            $seeker = new CarSeeker();
            $data = $seeker -> balanceDistribute($state, $series);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e){
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionExportBalanceDetail() {
        $state = $this->validateStringVal('state', 'WH');
        $series = $this->validateStringVal('series', '');
        try{
            $seeker = new CarSeeker();
            $whAvailableOnly = false;
            if($state === 'WHin'){
                $whAvailableOnly = true;
            }
            list($total, $datas) = $seeker->queryBalanceDetail($state, $series, 0, 0, $whAvailableOnly);
            
            $title = "carID,流水号,VIN,车系,颜色,车型,车型/配置,耐寒性,状态,下线时间,入库时间,特殊订单号,备注,库区\n";
            $content = "";
            foreach($datas as $data) {
                $content .= "{$data['car_id']},";
                $content .= "{$data['serial_number']},";
                $content .= "{$data['vin']},";
                $content .= "{$data['series']},";
                $content .= "{$data['color']},";
				$data['type'] = str_replace(",", "，",$data['type']);
                $content .= "{$data['type']},";
                $content .= "{$data['type_info']},";
                $content .= "{$data['cold']},";
                $content .= "{$data['status']},";
                $content .= "{$data['finish_time']},";
                $content .= "{$data['warehouse_time']},";
                $content .= "{$data['special_order']},";
                $data['remark'] = str_replace(",", "，",$data['remark']);
                $data['remark'] = str_replace(PHP_EOL, '', $data['remark']);
                $content .= "{$data['remark']},";
                $content .= "{$data['row']},";
                $content .= "\n";
            }
            $export = new Export($state . '结存查询_' .date('Ymd'), $title . $content);
            $export->toCSV();


        } catch(Exception $e) {
        }
    }

    public function actionThrowOutPrintDataOne() {
        try{
            $vin = $this->validateStringVal('vin', '');
            
            $car = Car::create($vin);

            $outDate = ($car->car->distribute_time > '0000-00-00 00:00:00') ? $car->car->distribute_time : date("Y-m-d h:m:s");
            $clientIp = $_SERVER["REMOTE_ADDR"];
            $data = $car->throwCertificateData($outDate, $clientIp);
            $car->throwInspectionSheetData();
            $this->renderJsonBms(true, $vin . '成功抛送合格证与厂检单数据' , $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionThrowMarkPrint() {
        try{
            $vin = $this->validateStringVal('vin', '');
            
            $car = Car::create($vin);

            $data = $car->throwMarkPrintData();
            $this->renderJsonBms(true, $vin . '成功抛送铭牌数据' , $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionThrowStoreIn(){
        try{
            $vin = $this->validateStringVal('vin', '');
            $car = Car::create($vin);
            
            $row = 'Z000';
            if(!empty($car->car->warehouse_id)){
                $row = WarehouseAR::model()->findByPk($car->car->warehouse_id)->row;
            }else if(!empty($car->car->old_wh_id)){
                $row = WarehouseAR::model()->findByPk($car->car->old_wh_id)->row;
            }
            $driverName = '汪辉';
            $inDate = $car->car->warehouse_time;

            $vinMessage = $car->throwVinStoreIn($car->vin, $row, $driverName, $inDate);

            $this->renderJsonBms(true, '操作完成，'. $vinMessage->StoreInResult, $vinMessage);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionThrowStoreOut(){
        try{
            $vin = $this->validateStringVal('vin', '');
            $car = Car::create($vin);
            $lane = '';
            if(!empty($car->car->lane_id)){
                $lane = LaneAR::model()->findByPk($car->car->lane_id)->name;
            }
            $order = OrderAR::model()->findByPk($car->car->order_id);
            $orderNumber = $order->order_number;
            $orderDetailId = $order->order_detail_id;
            $outDate = $car->car->distribute_time;
            
            $vinMessage = $car->throwVinStoreOut($vin, $lane, $orderNumber, $orderDetailId, $car->car->distributor_name, $car->car->engine_code, $outDate);
            $this->renderJsonBms(true, '操作完成，'. $vinMessage->StoreOutResult, $vinMessage);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionQueryVins(){
        try{
            $vinText = $this->validateStringVal('vinText', '');
            $seeker = new CarSeeker();
            $data = $seeker->queryVins($vinText);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false,$e->getMessage(),null);
        }
    }

	public function actionTest()  {
		$vin = $this->validateStringVal('vin', '');
		$car = VinManager::importCar($vin);

		var_dump($car);
	}

}
