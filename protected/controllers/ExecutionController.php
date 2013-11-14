<?php
Yii::import('application.models.Car');
Yii::import('application.models.Fault');
Yii::import('application.models.TestlineSeeker');
Yii::import('application.models.AR.PlanAR');
Yii::import('application.models.AR.CarAR');
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.WarehouseAR');
Yii::import('application.models.AR.ComponentAR');
Yii::import('application.models.Rpc.RpcService');
class ExecutionController extends BmsBaseController
{
	public static $NODE_MAP = array(
		'T11','T21','T32','C10','C21','F10',
        'T11_2','T21_2','T32_2','C10_2','C21_2','F10_2',
	);

	public static $MERGED_VIEW = "T11-F10";

	public static $QUERY_PRIVILAGE = array(
		'CarQuery' => array('READ_ONLY', 'CAR_QUERY', 'CAR_QUERY_ASSEMBLY'),
		'ManufactureQuery' => array('READ_ONLY', 'FAULT_QUERY', 'NODE_QUERY', 'FAULT_QUERY_ASSEMBLY', 'NODE_QUERY_ASSEMBLY'),
		'ComponentQuery' => array('READ_ONLY', 'COMPONENT_TRACE_QUERY'),
        'NodeQuery' => array('READ_ONLY', 'FAULT_QUERY', 'NODE_QUERY', 'FAULT_QUERY_ASSEMBLY', 'NODE_QUERY_ASSEMBLY'),
        'BalanceQuery' => array('READ_ONLY', 'FAULT_QUERY', 'NODE_QUERY', 'FAULT_QUERY_ASSEMBLY', 'NODE_QUERY_ASSEMBLY'),
        'OrderCarQuery' => array('READ_ONLY', 'FAULT_QUERY', 'NODE_QUERY', 'FAULT_QUERY_ASSEMBLY', 'NODE_QUERY_ASSEMBLY'),
        'WarehouseQuery' => array('READ_ONLY', 'FAULT_QUERY', 'NODE_QUERY', 'FAULT_QUERY_ASSEMBLY', 'NODE_QUERY_ASSEMBLY'),
		'ReplacementQuery' => array('REPARES_STORE_QUERY'),
	);

    public static $CHILD_VIEW_PRIVILAGE = array(
        'PBS' => array('DATA_INPUT_PBS'),
        'F20' => array('DATA_INPUT_VQ1'),
        'VQ1' => array('DATA_INPUT_VQ1'),
        'VQ2RoadTestFinished' => array('DATA_INPUT_VQ2'),
        'VQ2LeakTest' => array('DATA_INPUT_VQ2'),
        'VQ3' => array('DATA_INPUT_VQ3'),
        'SUB' => array('DATA_INPUT_SUB'),
    );

    public static $CHILD_NODE_PRIVILAGE = array(
        'S1' => array('DATA_INPUT_SPS_S1'),
        'S2' => array('DATA_INPUT_SPS_S2'),
        'S3' => array('DATA_INPUT_SPS_S3'),
        'frontBumper' => array('DATA_INPUT_SPS_FB'),
        'rearBumper' => array('DATA_INPUT_SPS_RB'),
        'T0' => array('DATA_INPUT_T0'),
        'T0_2' => array('DATA_INPUT_T0_2'), 
        'T11' => array('DATA_INPUT_T11_F10'), 'T21' => array('DATA_INPUT_T11_F10'), 'T32' => array('DATA_INPUT_T11_F10'), 'C10' => array('DATA_INPUT_T11_F10'),'C21' => array('DATA_INPUT_T11_F10'),'F10' => array('DATA_INPUT_T11_F10'),
        'T11_2' => array('DATA_INPUT_T11_F10_2'),'T21_2' => array('DATA_INPUT_T11_F10_2'),'T32_2' => array('DATA_INPUT_T11_F10_2'),'C10_2' => array('DATA_INPUT_T11_F10_2'),'C21_2' => array('DATA_INPUT_T11_F10_2'),'F10_2' => array('DATA_INPUT_T11_F10_2'),
        'CHECK_IN' => array('DATA_INPUT_WAREHOUSE'),'OutStandby'=>array('DATA_INPUT_WAREHOUSE'), 'CHECK_OUT' => array('DATA_INPUT_WAREHOUSE'),
    );
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	/**
     */
    public function actionIndex()
    {
        $this->render('assembly/dataInput/NodeSelect',array(''));
    }

    /**
	 */
	public function actionHome()
	{
		$this->render('executionHome',array(''));
	}

    /**   query  actions
     */
    public function actionQuery()
    {
        $queryPanel = $this->validateStringVal('type','CarQuery');
		try{
			Yii::app()->permitManager->check(self::$QUERY_PRIVILAGE[$queryPanel]);
			$this->render('assembly/query/' . $queryPanel,array(''));
		} catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
		}
    }

    public function actionReport(){
        $reportPanel = $this->validateStringVal('type','WarehouseReport');
        try{
            $this->render('assembly/report/' . $reportPanel);
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }        
    }
	
	public function actionChild() {
        $nodeName = $this->validateStringVal('node','NodeSelect');
		$line = $this->validateStringVal('line','I');
		$view = $this->validateStringVal('view','NodeSelect');
        $type = $this->validateStringVal('type','subInstrument');
		$point = $this->validateStringVal('point','S1');
        try{
            if(in_array($nodeName, self::$NODE_MAP)) {
                $view = self::$MERGED_VIEW;
            }
            
            $node = Node::createByName($nodeName);
            if(array_key_exists($nodeName, self::$CHILD_NODE_PRIVILAGE)) {
                Yii::app()->permitManager->check(self::$CHILD_NODE_PRIVILAGE[$nodeName]);
            }
            if(array_key_exists($view, self::$CHILD_VIEW_PRIVILAGE)) {
                Yii::app()->permitManager->check(self::$CHILD_VIEW_PRIVILAGE[$view]);
            }

            $this->render('assembly/dataInput/' . $view ,array('type' => $type, 'node'=>$nodeName, 'nodeDisplayName' => $node->exist() ? $node->display_name : $nodeName, 'line'=>$line, 'point' => $point,));  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
	}

	//进入彩车身库
	public function actionEnterPbs() {
        $vin = $this->validateStringVal('vin', '');
        $date = $this->validateStringVal('date', date('Y-m-d'));
        $planId = $this->validateIntVal('planId', 0);
        $line = $this->validateStringVal('line', 'I');
        $transaction = Yii::app()->db->beginTransaction();
        try{
			$car = Car::create($vin);
            if(!empty($car->car->plan_id)) {
                throw new Exception("此车已匹配计划");
            }
            $car->checkAlreadyOnline();
            $car->enterNode('PBS');
            $car->detectStatus('PBS');
            if(($car->car->series == "6B" || $car->car->series == "M6") && empty($car->car->plan_id)){
                $car->addToPlan($date, $planId);
                $spsPoints = array('S1','S2','S3','frontBumper','rearBumper');
                $car->addSpsQueue($spsPoints);
                $car->generateSpsSerial($line);
            }
            $transaction->commit();
			$this->renderJsonBms(true, $vin . '成功录入彩车身库', $vin);
		} catch(Exception $e) {
            $transaction->rollback();
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	//打印上线
	public function actionEnterT0() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
			$planId = $this->validateIntVal('planId', 0);
			$date = $this->validateStringVal('date', date('Y-m-d'));
            $currentNode = $this->validateStringVal('currentNode' , 'T0');
            $line = $this->validateStringVal('line', 'I');
			if(empty($planId)) {
				throw new Exception('the car must fit a plan!!');
			}
            $car = Car::create($vin);
            $car->checkAlreadyOut();
            $car->checkAlreadyWarehouse();
            //$car->leftNode('PBS');
			$car->enterNode($currentNode, 0 ,true);
            $car->detectStatus($currentNode);
            $car->assemblyTime();
            if(empty($car->car->plan_id)) {
                $car->addToPlan($date, $planId);
            }
            $car->generateSerialNumber($line);
            $serial_number = $car->car->serial_number;      //added by wujun
            $car->ratioControlNext($line);
            $car->addVinLaserQueue();
            if($currentNode === 'T0'){
                $subTypes = array('subEngine','subFrontAxle','subInstrument','subTyre');
                $car->addSubConfig($subTypes);
            }

			$transaction->commit();

    		$data = $car->generateConfigData();


            $this->renderJsonBms(true, $vin . '成功录入T0', $data);   //modifed by wujun
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }


	//T11,T21,T32,C10,C21,F10
    //T11_2,T21_2,T32_2,C10_2,C21_2,F10_2
	public function actionEnterNode() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
			$nodeName = $this->validateStringVal('currentNode', 'T11');
			$componentCode = $this->validateStringVal('componentCode', '{}');

			$enterNode = Node::createByName($nodeName);
			$leftNode = $enterNode->getParentNode();

            $car = Car::create($vin);
            $car->checkAlreadyOut();
            $car->checkAlreadyWarehouse();
            //$car->leftNode($leftNode->name);
			$car->enterNode($enterNode->name);

            //throw T32 data to vinm

            //save component trace
            $car->addTraceComponents($enterNode, $componentCode);

            $data = $car->generateInfoPaperData();
            $car->detectStatus($enterNode->name);
            $transaction->commit();

            $this->renderJsonBms(true, $vin . '成功录入' . $nodeName , $data);
            if($nodeName == 'T32' || $nodeName == 'T32_2'){
                $vinMessage = $car->throwVinAssembly($car->vin, 'I线_T32');
            }
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

	public function actionEnterF20() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
            $nodeName = $this->validateStringVal('currentNode', 'F20');
            $car = Car::create($vin);
            $car->checkAlreadyOut();
            $car->checkAlreadyWarehouse();
            //$car->leftNode('F10');
            $car->enterNode($nodeName);
            $car->detectStatus($nodeName);
            //print check trace 
            $data = $car->generateCheckTraceData();
            $transaction->commit();

            $this->renderJsonBms(true, 'OK', $data);
            $vinMessage = $car->throwVinAssembly($car->vin, 'I线_F20');
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }


	public function actionEnterVQ1() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
            $nodeName = $this->validateStringVal('currentNode', 'VQ1');
		    $faults = $this->validateStringVal('fault', '[]');

            $car = Car::create($vin);
            $car->checkAlreadyOut();
            $car->checkAlreadyWarehouse();
            $enterNode = Node::createByName($nodeName);
            $leftNode = $enterNode->getParentNode();
            $car->leftNode($leftNode->name);
            // $car->passNode('LEFT_WORK_SHOP');
            $shouldCheck = true;
            $ff = CJSON::decode($faults);
            if(!empty($ff)){
                foreach($ff as $f){
                    //如果有离线修复故障，则不校验
                    if(!$f['fixed']){
                        $shouldCheck = false;
                        break;
                    }
                }
            }
            if($shouldCheck){
                if($car->car->series == "6B"  && $car->car->type != "QCJ7152ET1(1.5TI豪华型)" && $car->car->type != "QCJ7152ET2(1.5TID豪华型)"){
                    $IRemote = $car->getIRemoteTestResult();
                    if(!($IRemote->Result) || $IRemote->TestState != "2"){
                        throw new Exception($car->car->vin . '未通过云系统测试，不可录入下线合格，请先完成云系统测试');
                    }
                }

                $checkTrace = $car->checkTraceComponentByConfig();
                if($car->car->series != 'G6' && $checkTrace['notGood']) throw new Exception("此车追溯零部件记录不完整，不可录入下线合格，请联系相关责任人补录数据");
                
                $vinValidate = $car->validateVin();
                if(!$vinValidate['success']) throw new Exception("此车" . $vinValidate['message']);
            }

            list($nodeName, $traceId) = $car->enterNode($nodeName);

            $tablePrefix = "VQ1_STATIC_TEST";
            if($nodeName == "VQ1_2") $tablePrefix .= "_2";

            $fault = Fault::create($tablePrefix, $vin, $faults, array("traceId"=>$traceId));
            $fault->save('在线');

            $car->detectStatus($nodeName);
            $car->finish();
            $car->vq1finish();
            $transaction->commit();

            $car->throwTestlineCarInfo();
            $this->renderJsonBms(true, 'OK', null);
			$vinMessage = $car->throwVinAssembly($car->vin, '总装下线');
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

	public function actionEnterCL() {
        try{
            $vin = $this->validateStringVal('vin', '');
            $car = Car::create($vin);
            $car->checkAlreadyOut();
            $car->checkAlreadyWarehouse();
            // $car->leftNode('ENTER_CHECK_SHOP');
            $car->enterNode('CHECK_LINE', 0);
            $car->detectStatus('CHECK_LINE');
            $this->renderJsonBms(true, 'OK', $vin);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        } 
    }

	public function actionEnterRTF() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
			$faults = $this->validateStringVal('fault', '[]');
			$barCode = $this->validateStringVal('barCode', '');
            $driverId = $this->validateIntVal('driver', 0);
            $temperature = $this->validateStringVal('temperature', 0);
            
            $fault = Fault::createSeeker();
           
            if(empty($driverId)) {
                throw new Exception('必须选择驾驶员');
            }

            $enterNode = Node::createByName("ROAD_TEST_FINISH");
            $car = Car::create($vin);
            $car->checkAlreadyOut();
			$car->checkAlreadyWarehouse();
			$car->leftNode('CHECK_LINE');
            
			$exist = $fault->exist($car, '未修复', array('VQ1_STATIC_TEST_','VQ1_STATIC_TEST_2_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ1还有未修复的故障');
            }
			// $car->checkTestLinePassed();  //already validate in CarController actionValidateRTF()
			$car->passNode('VQ3');
            $faults = CJSON::decode($faults);
            if($temperature>20){
                $tempFault = $car-> getTemperatureFault();
                array_push($faults, $tempFault);
            }
            list($nodeName, $traceId) = $car->enterNode('ROAD_TEST_FINISH', $driverId);
            $fault = Fault::create('VQ2_ROAD_TEST',$vin, $faults, array("traceId"=>$traceId));
            $fault->save('在线');

            // $car->addGasBagTraceCode($bagCode);
            $car->addTraceComponents($enterNode, $barCode);
            $car->recordTemperature($temperature, $traceId, $driverId);
            $car->detectStatus($nodeName);
            $transaction->commit();


            $this->renderJsonBms(true, 'OK', null);
			$testlineTrace = NodeTraceAR::model()->find('car_id =? AND node_id=?', array($car->car->id,13));
            if(!empty($testlineTrace)){
                $testlineTime = $testlineTrace->pass_time;
                $shift='总装1线A班';
                $vinMessage = $car->throwVinAssembly($car->vin, '检测线', $shift, $testlineTime);
            }
			$vinMessage = $car->throwVinAssembly($car->vin, '路试');
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }   
    }   


	public function actionEnterVQ2Leak() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
			$faults = $this->validateStringVal('fault', '[]');
            $driverId = $this->validateIntVal('driver', 0);

			
            if(empty($driverId)) {
                throw new Exception('必须选择驾驶员');
            }
			
            $car = Car::create($vin);
			$car->checkAlreadyOut();
            $car->checkAlreadyWarehouse();
			$car->leftNode('ROAD_TEST_FINISH');
			$car->passNode('VQ3');
            
            $fault = Fault::createSeeker();
            $exist = $fault->exist($car, '未修复', array('VQ2_ROAD_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ2还有未修复的故障');
            }
        
            
            list($nodeName, $traceId) = $car->enterNode('VQ2', $driverId);
            
            $fault = Fault::create('VQ2_LEAK_TEST',$vin, $faults, array("traceId"=>$traceId));
            $fault->save('在线');
            $car->detectStatus($nodeName);
            $car->vq2finish();
            $transaction->commit();

            $this->renderJsonBms(true, 'OK', $vin);
			$vinMessage = $car->throwVinAssembly($car->vin, '淋雨');
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }


	public function actionEnterVQ3() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
			$faults = $this->validateStringVal('fault', '');
            $driverId = $this->validateStringVal('driver', 0);
            $car = Car::create($vin);
            $car->checkAlreadyOut();
            $car->checkAlreadyWarehouse();
			$fault = Fault::createSeeker();
            $exist = $fault->exist($car, '未修复', array('VQ2_ROAD_TEST_', 'VQ2_LEAK_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ2还有未修复的故障');
            }
			
			if($car->car->warehouse_time>'0000-00-00 00:00:00') {
                throw new Exception ($vin .'已入库，无法录入VQ3');
            }
			
			if($car->car->distribute_time>'0000-00-00 00:00:00') {
                throw new Exception ($vin .'已出库，无法录入VQ3');
            }
			
			//只要进入VQ2，则可以多次进入VQ3
			$car->leftNode('VQ2');
            
			$car->passNode('CHECK_IN');
            list($nodeName, $traceId) = $car->enterNode('VQ3', $driverId);
            $others = array(
                'checker' => $driverId,
                'traceId' => $traceId,
            );
            
            $fault = Fault::create('VQ3_FACADE_TEST',$vin, $faults, $others);
            $fault->save('在线');
            $car->detectStatus($nodeName);
            $transaction->commit();

            $this->renderJsonBms(true, 'OK', $vin);
			$vinMessage = $car->throwVinAssembly($car->vin, '面漆预检');
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }


	public function actionEnterWDI() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
			$faults = $this->validateStringVal('fault', '');
			$checkTime = $this->validateStringVal('checkTime', '');
			$checker = $this->validateStringVal('checker', '');
			$subChecker = $this->validateStringVal('subChecker', '');
            $car = Car::create($vin);
	
            // $car->enterNode('WDI');
            list($nodeName, $traceId) = $car->enterNodeWDI($checkTime);
            $others = array(
                'checkTime' => $checkTime,
                'checker' => $checker,
                'subChecker' => $subChecker,
                'traceId' => $traceId,
            );
            $fault = Fault::create('WDI_TEST',$vin, $faults, $others);
            $fault->save('离线' , true);//is wdi
            if($faults === '[]'){
                $fault->wdiNoFault();
            }
            $car->detectStatus($nodeName);
			$transaction->commit();
            $this->renderJsonBms(true, 'OK', $vin);
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    //added by wujun
    //checkin warehouse
    public function actionWarehouseCheckin() {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $vin = $this->validateStringVal('vin', '');
            $driverId = $this->validateIntVal('driverId', 0);
            $areaT = $this->validateIntVal('areaT', 0);
            $date = DateUtil::getCurDate();

            $car = Car::create($vin);

            $fault = Fault::createSeeker();
            $exist = $fault->exist($car, '未修复', array('VQ3_FACADE_TEST_'));
            if(!empty($exist)) {
                throw new Exception ('VQ3还有未修复故障');
            }
            $exist = $fault->exist($car, '未修复', array('VQ2_ROAD_TEST_', 'VQ2_LEAK_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ2还有未修复的故障');
            }
            $exist = $fault->exist($car, '未修复', array('VQ1_STATIC_TEST_', 'VQ1_STATIC_TEST_2_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ1还有未修复的故障');
            }
			// $car->checkTestLinePassed();
			$car->leftNode('VQ3');
            $car->passNode('CHECK_OUT');
			if($car->car->warehouse_id > 0){
				$row = WarehouseAR::model()->findByPk($car->car->warehouse_id)->row;
				throw new Exception ('此车状态为成品库_'. $row .'，不可重复入库');
			}
			
			$car->checkAlreadyOut();
			
            $onlyOnce = false;
            list($nodeName, $tarceId) = $car->enterNode('CHECK_IN', $driverId, $onlyOnce);
            $car->detectStatus($nodeName);

            $matched = false;
            if($car->car->special_property<9){
                list($matched, $data) = $car->matchOrder($date);
            }
            if($matched) {
                $message = $vin . '已匹配订单' . $data['orderNumber'] .'-'. $data['distributorName'] .'-'. $data['lane'] .'，请开往WDI区';
                $car->throwMarkPrintData();
                list($nodeName, $traceId) = $car->enterNode('OutStandby', $driverId);
                $car->detectStatus($nodeName);
            } else {
                $warehouse = new Warehouse;
                $forceToAreaT = empty($areaT)? false : true;
                $data = $warehouse->checkin($vin, $forceToAreaT);
                $message = $vin . '已成功入库，请开往' . $data['row'];
                $car->car->warehouse_id = $data['warehouse_id'];
                $car->car->area = $data['area'];
                $car->car->save();

                $data['lane'] = '--';
                $data['orderNumber'] = '-------------------';
                $data['distributorName'] = '-------------------';
            }
			if(!empty($driverId)){
				$driverName = User::model()->findByPk($driverId)->display_name;
			} else {
				$driverName = Yii::app()->user->display_name;
			}
            
            $car->warehouseTime();
            
            $transaction->commit();
            $this->renderJsonBms(true, $message, $data);

            //open gate
            $rpc = new RpcService();
            $clientIp = $_SERVER["REMOTE_ADDR"];
            $data['clientIp'] = $clientIp;
            $host='10.23.86.80';
            $ret = $rpc->openGate($host);
            
			$vinMessage = $car->throwVinStoreIn($car->vin, $data['row'], $driverName);
        } catch(Exception $e) {
            $transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage());
        }
    }
	
	public function actionWarehouseRelocateSubmit() {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $vin = $this->validateStringVal('vin', '');
            $driverId = $this->validateIntVal('driverId', 0);
            $date = DateUtil::getCurDate();

            $car = Car::create($vin);

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
            
			// $car->checkTestLinePassed();
			$car->leftNode('VQ3');
			if($car->car->warehouse_id == 0 || $car->car->status != '成品库' || $car->car->warehouse_id <1000){
				throw new Exception ('此车不在成品库中，状态为['. $car->car->status .']，不可重复分配库位');
			}
			
			$car->checkAlreadyOut();

            $matched = false;
            if($car->car->special_property<9){
                list($matched, $data) = $car->matchOrder($date);
            }
            if($matched) {
                $message = $vin . '已匹配订单' . $data['orderNumber'] .'-'. $data['distributorName'] .'-'. $data['lane'] .'，请开往WDI区';
                $car->throwMarkPrintData();
                list($nodeName, $traceId) = $car->enterNode('OutStandby', $driverId);
                $car->detectStatus($nodeName);
            } else {
                $warehouse = new Warehouse;
                $data = $warehouse->checkin($vin);
                $message = $vin . '已重新分配库位，请开往' . $data['row'];
                $car->car->warehouse_id = $data['warehouse_id'];
                $car->car->fifo_time = date("YmdHis");
                $car->car->area = $data['area'];
                $car->car->save();

                $data['lane'] = '--';
                $data['orderNumber'] = '-------------------';
                $data['distributorName'] = '-------------------';
            }

			if(!empty($driverId)){
				$driverName = User::model()->findByPk($driverId)->display_name;
			} else {
				$driverName = Yii::app()->user->display_name;
			}
			
            $transaction->commit();
            $this->renderJsonBms(true, $message, $data);
        } catch(Exception $e) {
            $transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    //added by wujun
    //checkin warehouse
    public function actionWarehouseCheckout() {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $vin = $this->validateStringVal('vin', '');
            $driverId = $this->validateIntVal('driverId', 0);

            $car = Car::create($vin);
            $car->leftNode('CHECK_IN');
            if($car->car->distribute_time > '0000-00-00 00:00:00' || $car->car->distributor_name != ''){
                throw new Exception($car->car->vin . "已出库，不可重复出库");
            }
            if(empty($car->car->engine_code)){
                throw new Exception($car->car->vin . "系统未记录发动机号，无法出库");
            }

            if($car->car->series == 'F0'){
                $absTrace = $car->checkTraceABS();
            }

			// $car->checkTestLinePassed();
            $onlyOnce = false;
            list($nodeName, $traceId) = $car->enterNode('CHECK_OUT', $driverId, $onlyOnce);
            $car->detectStatus($nodeName);
            $data = '';
            $warehouse = new Warehouse;
            $data = $warehouse->checkout($vin);
            $message = $vin . '已成功出库，请开往车道' . $data['lane'] . '['. $data['distributor_name'] .']';

            $car->car->lane_id = $data['lane_id'];
            $car->car->distributor_name = $data['distributor_name'];
            $car->car->distributor_code = $data['distributor_code'];
            // $car->order_detail_id = $order->order_detail_id;
            $car->car->warehouse_id = 0;
            $car->car->area = 'out';
            $car->car->save();
            $car->distributeTime();
			
			if(!empty($driverId)){
				$driverName = User::model()->findByPk($driverId)->display_name;
			} else {
				$driverName = Yii::app()->user->display_name;
			}
			$order = OrderAR::model()->findByPk($car->car->order_id);
			$orderNumber = $order->order_number;
			$orderDetailId = $order->order_detail_id;
			
            $transaction->commit();
            $this->renderJsonBms(true, $message, $data);
            //open gate
            $clientIp = $_SERVER["REMOTE_ADDR"];
            $data['clientIp'] = $clientIp;
            $rpc = new RpcService();
            $host='10.23.86.82';
            $ret = $rpc->openGate($host);
			$vinMessage = $car->throwVinStoreOut($vin, $data['lane'], $orderNumber, $orderDetailId, $car->car->distributor_name, $car->car->engine_code);
        } catch(Exception $e) {
            $transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionReturnSubmit() {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $vin = $this->validateStringVal('vin', '');
            $driverId = $this->validateIntVal('driverId', 0);
            $goTo = $this->validateStringVal('goTo', 'VQ3');
            $remark = $this->validateStringVal('remark', '');

            $car = Car::create($vin);
            $data = $car->warehouseReturn($goTo, $remark);

            if($goTo === "成品库") {
                $message = $vin . "已成功退回库，请开往" . $data['row']; 
            } else {
                $message = $vin . "已成功退出库，请返回" . $goTo; 
            }
            
            $transaction->commit();
            $this->renderJsonBms(true, $message, $data);
        } catch(Exception $e) {
            $transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionCheckPaperPrint() {
        try{
            $vin = $this->validateStringVal('vin', '');
            $car = Car::create($vin);
            $data = $car->generateCheckTraceData();

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionConfigPaperMainPrint() {
        try{
            $vin = $this->validateStringVal('vin', '');
            $car = Car::create($vin);
            $data = $car->generateConfigData();

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionCarLabelAssemblyPrint() {
        try{
            $vin = $this->validateStringVal('vin', '');
            $car = Car::create($vin);
            $data = $car->generateInfoPaperData();
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionGetWarehouseLabel() {
        try {
            $vin = $this->validateStringVal('vin', '');

            $car = Car::create($vin);
            $data = $car->getWarehouseLabel();

            $this->renderJsonBms(true, '打印成功', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionCarAccessSubmit() {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $vin = $this->validateStringVal('vin', '');
            $driverId = $this->validateIntVal('driverId', 0);
            $node = $this->validateStringVal('node', '');
            $remark = $this->validateStringVal('remark', '');

            $car = Car::create($vin);
            list($nodeName, $traceId) = $car->enterNode($node,$driverId,false,$remark);
            $nodeAr = Node::createByName($nodeName);
            $nodeDisName = $nodeAr->display_name;
            $message = $car->car->vin. '成功录入' . $nodeDisName;

            $transaction->commit();
            $this->renderJsonBms(true, $message, $car);
        } catch(Exception $e) {
            $transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionQueryNodeTrace() {
        $series = $this->validateStringVal('series', '');
        $stime = $this->validateStringVal('stime', '');
        $etime = $this->validateStringVal('etime', '');
        $node = $this->validateStringVal('node', '');
        $perPage = $this->validateIntVal('perPage', 20);
        $curPage = $this->validateIntVal('curPage', 1);
        try{
            $seeker = new NodeSeeker();
            list($total, $data) = $seeker->queryTrace($stime, $etime, $series, $node, $curPage, $perPage);
            $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'data' => $data,
                    );
            $this->renderJsonBms(true, 'OK', $ret);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionExportNodeTrace() {
        $series = $this->validateStringVal('series', '');
        $stime = $this->validateStringVal('stime', '');
        $etime = $this->validateStringVal('etime', '');
        $node = $this->validateStringVal('node', '');
        try{
            $seeker = new NodeSeeker();
            list($total, $datas) = $seeker->queryTrace($stime, $etime, $series, $node, 0, 0);
            $content = "carID,流水号,VIN,车系,颜色,车型,配置,生产配置,耐寒性,状态,录入时间,经销商,特殊订单号,车辆备注,节点,退回,节点备注,录入人员,录入用户,订单号,发动机号\n";
            foreach($datas as $data) {
                $content .= "{$data['car_id']},";
                $content .= "{$data['serial_number']},";
                $content .= "{$data['vin']},";
                $content .= "{$data['series']},";
                $content .= "{$data['color']},";
				$data['type'] = str_replace(",", "，",$data['type']);
                $content .= "{$data['type']},";
                $content .= "{$data['type_config']},";
                $content .= "{$data['config_name']},";
                $content .= "{$data['cold_resistant']},";
                $content .= "{$data['status']},";
                $content .= "{$data['pass_time']},";
                $content .= "{$data['distributor_name']},";
                $content .= "{$data['special_order']},";
                $data['remark'] = str_replace(",", "，",$data['remark']);
                $data['remark'] = str_replace(PHP_EOL, '', $data['remark']);
                $content .= "{$data['remark']},";
                $content .= "{$data['node_name']},";
                $content .= "{$data['return_to']},";
                $data['node_remark'] = str_replace(",", "，",$data['node_remark']);
                $data['node_remark'] = str_replace(PHP_EOL, '', $data['node_remark']);
                $content .= "{$data['node_remark']},";
                $content .= "{$data['driver_name']},";
                $content .= "{$data['user_name']},";
                $content .= "{$data['order_number']},";
                $content .= "{$data['engine_code']},";
                $content .= "\n";
            }
            $export = new Export('生产车辆明细_' .date('YmdHi'), $content);
            $export->toCSV();
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionQueryTestLineRecords() {
        $item = $this->validateStringVal('item', 'NCA');
        $stime = $this->validateStringVal('stime', '');
        $etime = $this->validateStringVal('etime', '');
        $series = $this->validateStringVal('series', '');
        $perPage = $this->validateIntVal('perPage', 20);
        $curPage = $this->validateIntVal('curPage', 1);
        try{
            $seeker = new TestlineSeeker();
            list($total, $data) = $seeker->queryFromTable($item, $stime, $etime, $series, $curPage, $perPage);
            $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'data' => $data,
                    );
            $this->renderJsonBms(true, 'OK', $ret);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
        
    }

    public function actionExportTestLineRecords() {
        $item = $this->validateStringVal('item', 'NCA');
        $stime = $this->validateStringVal('stime', '');
        $etime = $this->validateStringVal('etime', '');
        $series = $this->validateStringVal('series', '');
        try{
            $seeker = new TestlineSeeker();
            list($total, $datas) = $seeker->queryFromTable($item, $stime, $etime, $series, 0, 0);
            switch($item) {
                case "NCA":
                    $content = "carID,车系,VIN,前左轮,前右轮,前总前束,前轮评价,后左轮,后右轮,后总前束,后轮评价,总评价\n";
                    foreach($datas as $data) {
                        $content .= "{$data['car_id']},";
                        $content .= "{$data['series_name']},";
                        $content .= "{$data['vin']},";
                        $content .= "{$data['ToeLeft_F']},";
                        $content .= "{$data['ToeRight_F']},";
                        $content .= "{$data['ToeTotal_F']},";
                        $content .= "{$data['ToeFlag_F']},";
                        $content .= "{$data['ToeLeft_R']},";
                        $content .= "{$data['ToeRight_R']},";
                        $content .= "{$data['ToeTotal_R']},";
                        $content .= "{$data['ToeFlag_R']},";
                        $content .= "{$data['Toe_Flag']},";
                        $content .= "\n";
                    }
                    break;
                
                case "Light":
                    $content = "单位：发光强度(cd)、上下/左右偏角(cm/10m)、照射高度(cm)\n";
                    $content .= "carID,车系,VIN,左远发光强度,左远上下偏角,左远左右偏角,左远照射高度,左远评价,左近发光强度,左近上下偏角,左近左右偏角,左近照射高度,左近评价,右远发光强度,右远上下偏角,右远左右偏角,右远照射高度,右远评价,右近发光强度,右近上下偏角,右近左右偏角,右近照射高度,右近评价,总评价\n";
                    foreach($datas as $data) {
                        $content .= "{$data['car_id']},";
                        $content .= "{$data['series_name']},";
                        $content .= "{$data['vin']},";
                        $content .= "{$data['LM_Inten']},";
                        $content .= "{$data['LM_UDAngle']},";
                        $content .= "{$data['LM_LRAngle']},";
                        $content .= "{$data['LM_Height']},";
                        $content .= "{$data['LM_Flag']},";
                        $content .= "{$data['LL_Inten']},";
                        $content .= "{$data['LL_UDAngle']},";
                        $content .= "{$data['LL_LRAngle']},";
                        $content .= "{$data['LL_Height']},";
                        $content .= "{$data['LL_Flag']},";
                        $content .= "{$data['RM_Inten']},";
                        $content .= "{$data['RM_UDAngle']},";
                        $content .= "{$data['RM_LRAngle']},";
                        $content .= "{$data['RM_Height']},";
                        $content .= "{$data['RM_Flag']},";
                        $content .= "{$data['RL_Inten']},";
                        $content .= "{$data['RL_UDAngle']},";
                        $content .= "{$data['RL_LRAngle']},";
                        $content .= "{$data['RL_Height']},";
                        $content .= "{$data['RL_Flag']},";
                        $content .= "{$data['Light_Flag']},";
                        $content .= "\n";
                    }
                    break;

                case "Slide":
                    $content = "carID,车系,VIN,侧滑(m/km),评价\n";
                    foreach($datas as $data) {
                        $content .= "{$data['car_id']},";
                        $content .= "{$data['series_name']},";
                        $content .= "{$data['vin']},";
                        $content .= "{$data['Slide']},";
                        $content .= "{$data['Slide_Flag']},";
                        $content .= "\n";
                    }
                    break;

                case "Brake":
                    $content = "carID,车系,VIN,前轴轴荷(×10N),前轴左制动力(×10N),前轴右制动力(×10N),前轴和(%),前轴差(%),前轴左阻滞(%),前轴右阻滞(%),前轴阻滞评价,前轴评价,后轴轴荷(×10N),后轴左制动力(×10N),后轴右制动力(×10N),后轴和(%),后轴差(%),后轴左阻滞(%),后轴右阻滞(%),后轴阻滞评价,后轴评价,整车制动力(×10N),整车和(%),整车评价,驻车制动力(×10N),驻车和(%),驻车评价,总评价\n";
                    foreach($datas as $data) {
                        $content .= "{$data['car_id']},";
                        $content .= "{$data['series_name']},";
                        $content .= "{$data['vin']},";
                        $content .= "{$data['AxleWeight_F']},";
                        $content .= "{$data['Brake_FL']},";
                        $content .= "{$data['Brake_FR']},";
                        $content .= "{$data['BrakeSumPer_F']},";
                        $content .= "{$data['BrakeDiffPer_F']},";
                        $content .= "{$data['BrakeResistance_FL']},";
                        $content .= "{$data['BrakeResistance_FR']},";
                        $content .= "{$data['BrakeResistanceFlag_F']},";
                        $content .= "{$data['BrakeFlag_F']},";
                        $content .= "{$data['AxleWeight_R']},";
                        $content .= "{$data['Brake_RL']},";
                        $content .= "{$data['Brake_RR']},";
                        $content .= "{$data['BrakeSumPer_R']},";
                        $content .= "{$data['BrakeDiffPer_R']},";
                        $content .= "{$data['BrakeResistance_RL']},";
                        $content .= "{$data['BrakeResistance_RR']},";
                        $content .= "{$data['BrakeResistanceFlag_R']},";
                        $content .= "{$data['BrakeFlag_R']},";
                        $content .= "{$data['BrakeSum']},";
                        $content .= "{$data['BrakeSumPer']},";
                        $content .= "{$data['BrakeSum_Flag']},";
                        $content .= "{$data['ParkSum']},";
                        $content .= "{$data['ParkSumPer']},";
                        $content .= "{$data['ParkSum_Flag']},";
                        $content .= "{$data['Brake_Flag']},";
                        $content .= "\n";
                    }
                    break;

                case "Speed":
                    $content = "carID,车系,VIN,标称值(km/h),实测值(km/h),误差(±),评价\n";
                    foreach($datas as $data) {
                        $content .= "{$data['car_id']},";
                        $content .= "{$data['series_name']},";
                        $content .= "{$data['vin']},";
                        $content .= "40,";
                        $content .= "{$data['SpeedValue']},";
                        $content .= "{$data['SpeedInaccuracy']},";
                        $content .= "{$data['Speed_Flag']},";
                        $content .= "\n";
                    }
                    break;

                case "Gas":
                    $content = "carID,车系,VIN,低怠速HC(ppm),低怠速CO(%),低怠速评价,高怠速HC(ppm),高怠速CO(%),高怠速评价,总评价\n";
                    foreach($datas as $data) {
                        $content .= "{$data['car_id']},";
                        $content .= "{$data['series_name']},";
                        $content .= "{$data['vin']},";
                        $content .= "{$data['GasHC_Low']},";
                        $content .= "{$data['GasCO_Low']},";
                        $content .= "{$data['GasLow_Flag']},";
                        $content .= "{$data['GasHC_High']},";
                        $content .= "{$data['GasCO_High']},";
                        $content .= "{$data['GasHigh_Flag']},";
                        $content .= "{$data['Gas_Flag']},";
                        $content .= "\n";
                    }
                    break;
                default:
                    $content = "";
                    break;
            }

            $export = new Export('检测线_'.$item .date('YmdHi'), $content);
            $export->toCSV();

        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
        
    }

    public function actionMonitoringIndex() {
		
        $this->render('assembly/monitoring/monitoringIndex');
        // $this->render('assembly/monitoring/monitoringIndex_2');
    }

    public function actionMonitoringSection() {
		$section = $this->validateStringVal('section');
        $this->render('assembly/monitoring/sectionBoard',array('section'=>$section));
    }

    public function actionMonitoringWorkshop() {
        $this->render('assembly/monitoring/workshopBoard');
    }

    public function actionPlanMaintain() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_ASSEMBLY');
            $this->render('assembly/other/PlanAssembly');
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }
	
	//added by wujun
	public function actionConfigMaintain() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_ASSEMBLY');
    		$this->render('assembly/other/ConfigMaintain');
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
	}

    public function actionOrderConfigMaintain() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_ASSEMBLY');
            $this->render('assembly/other/OrderConfigMaintain');
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionAccessoryListMaintain() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_ASSEMBLY');
            $this->render('assembly/other/AccessoryListMaintain');
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }
	
	//added by wujun
	public function actionConfigList() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_ASSEMBLY');
            $this->render('assembly/other/ConfigList');
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
	}
	
	//added by wujun
	public function actionConfigPaper() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_ASSEMBLY');
            $this->render('assembly/other/ConfigPaper');	
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
	}
	
	//added by wujun
	public function actionPauseEdit() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_ASSEMBLY');
    		$this->render('assembly/dataInput/PauseEdit');	
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
	}
	
	//added by wujun
	public function actionOrderMaintain() {
        try{
            Yii::app()->permitManager->check('ORDER_MAINTAIN');
            $this->render('assembly/other/OrderMaintain');	
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
	}

    public function actionWarehouseAdjust() {
        try{
            Yii::app()->permitManager->check('WAREHOUSE_MAINTAIN');
            $this->render('assembly/other/WarehouseAdjust');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionWarehousePrint() {
        try{
            Yii::app()->permitManager->check('WAREHOUSE_PRINT');
            $this->render('assembly/dataInput/WarehousePrint');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionWarehousePrintOrderInBoard() {
        try{
            Yii::app()->permitManager->check('WAREHOUSE_PRINT');
            $this->render('assembly/dataInput/WarehousePrintOrderInBoard');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionWarehousePrintExport() {
        try{
            Yii::app()->permitManager->check('WAREHOUSE_PRINT');
            $this->render('assembly/dataInput/WarehousePrintExport');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionlaneManage() {
        try{
            Yii::app()->permitManager->check('ORDER_MAINTAIN');
            $this->render('assembly/dataInput/LaneManage');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    //added by wujun
    public function actionPlanPause() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_ASSEMBLY');
            $this->render('assembly/other/PlanPause');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    //added by ccx
    public function actionSubQueueMaintain() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_ASSEMBLY');
            $this->render('assembly/other/SubQueueMaintain');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionSpsQueueMaintain() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_SPS');
            $this->render('assembly/other/SPSQueueMaintain');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    //added by wujun
    public function actionDataThrow() {
        try{
            Yii::app()->permitManager->check('ORDER_MAINTAIN');
            $this->render('assembly/other/DataThrow');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionAccessoryListPrint() {
        $this->render('assembly/dataInput/AccessoryListPrint');  
    }

    public function actionOutStandby() {
        $this->render('assembly/dataInput/OutStandby');  
    }

    public function actionOutStandby35() {
        $this->render('assembly/dataInput/OutStandby35');  
    }

    public function actionOutStandby27() {
        $this->render('assembly/dataInput/OutStandby27');  
    }

    public function actionOutStandby14() {
        $this->render('assembly/dataInput/OutStandby14');  
    }

    public function actionWarehouseLabel() {
        $this->render('assembly/dataInput/WarehouseLabel');  
    }
	
	public function actionWarehouseRelocate() {
        $this->render('assembly/dataInput/WarehouseRelocate');  
    }

    public function actionWarehouseReturn() {
        try{
            Yii::app()->permitManager->check('WAREHOUSE_MAINTAIN');
            $this->render('assembly/other/WarehouseReturn');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionCheckPaper() {
        try{
            Yii::app()->permitManager->check('CHECKPAPER_PRINT');
            $this->render('assembly/other/CheckPaper');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionConfigPaperMain() {
        $this->render('assembly/other/ConfigPaperMain');  
    }

    public function actionCarLabelAssembly() {
        $this->render('assembly/other/CarLabelAssembly');  
    }

    public function actionFaultDutyEdit() {
        try{
            Yii::app()->permitManager->check('FAULT_DUTY_EDIT');
            $this->render('assembly/other/FaultDutyEdit');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionDetectShopAccess() {
        try{
            Yii::app()->permitManager->check('CAR_ACCESS_CONTROL');
            $this->render('assembly/dataInput/DetectShopAccess');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    //added by wujun
    public function actionHoldRelease() {
        try{
            Yii::app()->permitManager->check('WAREHOUSE_MAINTAIN');
            $this->render('assembly/dataInput/HoldRelease');  
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    //added by wujun
    public function actionWelcomeShop() {
        $this->render('assembly/monitoring/workshopWelcome');
    }

    //added by wujun
    public function actionWelcomeSection() {
        $this->render('assembly/monitoring/sectionBoardWelcome');
    }

    //added by wujun
    public function actionTest() {
        // $transaction = Yii::app()->db->beginTransaction();
		 try{
            $vin = $this->validateStringVal('vin', '');
            $car = Car::create($vin);
            $ret = ($car->car->special_property==1 && $car->car->assembly_line=="I") ? 1:0; 
            $this->renderJsonBms(true, $ret, $ret);
        } catch(Exception $e) {
            // $transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionThrowOutDataOne() {
        try{
            $vin = $this->validateStringVal('vin', '');
            
            $car = Car::create($vin);

            $outDate = ($car->car->distribute_time > '0000-00-00 00:00:00') ? $car->car->distribute_time : date("Y-m-d h:m:s");
            $clientIp = $_SERVER["REMOTE_ADDR"];
            $data = $car->throwCertificateData($outDate, $clientIp);
            // $car->throwInspectionSheetData();
            $this->renderJsonBms(true, $vin . '成功录入' , $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionVinStoreIn(){
        try{
            $vin = $this->validateStringVal('vin', '');
            $car = Car::create($vin);
            
            $row = '';
            $driverName = '樊后来';
            $inDate = $car->car->warehouse_time;


            $this->renderJsonBms(true, $vin . '成功录入' , $vinMessage);
            $vinMessage = $car->throwVinStoreIn($car->vin, $row, $driverName, $inDate);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionVinStoreOut(){
        try{
            $vin = $this->validateStringVal('vin', '');
            $car = Car::create($vin);
            $lane = '';
            $order = OrderAR::model()->findByPk($car->car->order_id);
            $orderNumber = $order->order_number;
            $orderDetailId = $order->order_detail_id;
            $outDate = $car->car->distribute_time;
            
            $this->renderJsonBms(true, $vin . '成功录入' , $vinMessage);
            $vinMessage = $car->throwVinStoreOut($vin, $lane, $orderNumber, $orderDetailId, $car->car->distributor_name, $car->car->engine_code, $outDate);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionTestMailer(){
        try{
            $mailer = new BmsMailer();
            $mes = $mailer->sendMail('mailerTest', 'this is a test', 'wu.jun9@byd.com');
            
            // $fp = fsockopen("smtp.163.com",25,$errno,$errstr,60); 
            // if(! $fp) 
            //     $mes = '$errstr   ($errno) <br> \n '; 
            // else 
            //     $mes = 'ok <br> \n ';
            $this->renderJsonBms(true, $mes, $mes);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }
}
