<?php
Yii::import('application.models.Car');
Yii::import('application.models.Fault');
Yii::import('application.models.AR.PlanAR');
Yii::import('application.models.AR.CarAR');
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.WarehouseAR');
class ExecutionController extends BmsBaseController
{
	public static $NODE_MAP = array(
		'T11','T21','T32','C10','C21','F10',
	);
	public static $MERGED_VIEW = "T11-F10";

	public static $QUERY_PRIVILAGE = array(
		'CarQuery' => array('READ_ONLY', 'CAR_QUERY', 'CAR_QUERY_ASSEMBLY'),
		'ManufactureQuery' => array('READ_ONLY', 'FAULT_QUERY', 'NODE_QUERY', 'FAULT_QUERY_ASSEMBLY', 'NODE_QUERY_ASSEMBLY'),
		'ComponentQuery' => array('READ_ONLY', 'COMPONENT_TRACE_QUERY'),
        'NodeQuery' => array('READ_ONLY', 'FAULT_QUERY', 'NODE_QUERY', 'FAULT_QUERY_ASSEMBLY', 'NODE_QUERY_ASSEMBLY'),
        'BalanceQuery' => array('READ_ONLY', 'FAULT_QUERY', 'NODE_QUERY', 'FAULT_QUERY_ASSEMBLY', 'NODE_QUERY_ASSEMBLY'),
		'OrderCarQuery' => array('READ_ONLY', 'FAULT_QUERY', 'NODE_QUERY', 'FAULT_QUERY_ASSEMBLY', 'NODE_QUERY_ASSEMBLY'),
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
            $this->render('assembly/query/' . $reportPanel);
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }        
    }
	
	public function actionChild() {
		$nodeName = $this->validateStringVal('node','NodeSelect');
		$view = $this->validateStringVal('view','NodeSelect');
		$type = $this->validateStringVal('type','subInstrument');
		if(in_array($nodeName, self::$NODE_MAP)) {
			$view = self::$MERGED_VIEW;
		}
		
		$node = Node::createByName($nodeName); 
		$this->render('assembly/dataInput/' . $view ,array('type' => $type, 'node'=>$nodeName, 'nodeDisplayName' => $node->exist() ? $node->display_name : $nodeName));	
	}

	//进入彩车身库
	public function actionEnterPbs() {
		try{
			$vin = $this->validateStringVal('vin', '');
			$car = Car::create($vin);
        	$car->enterNode('PBS', 0 , true);
			$this->renderJsonBms(true, $vin . '成功录入彩车身库', $vin);
		} catch(Exception $e) {
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
			if(empty($planId)) {
				throw new Exception('the car must fit a plan!!');
			}
            $car = Car::create($vin);
            //$car->leftNode('PBS');
			$car->enterNode('T0', 0 ,true);
			$car->generateSerialNumber();
			$car->addToPlan($date, $planId);
            $serial_number = $car->car->serial_number;      //added by wujun

            $subTypes = array('subEngine','subFrontAxle');
            $car->addSubConfig($subTypes);

			$transaction->commit();

			
			$data = $car->generateConfigData();


            $this->renderJsonBms(true, $vin . '成功录入T0', $data);   //modifed by wujun
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }


	//T11,T21,T32,C10,C21,F10
	public function actionEnterNode() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
			$nodeName = $this->validateStringVal('currentNode', 'T11');
			$componentCode = $this->validateStringVal('componentCode', '{}');

			$enterNode = Node::createByName($nodeName);
			$leftNode = $enterNode->getParentNode();

            $car = Car::create($vin);
            //$car->leftNode($leftNode->name);
			$car->enterNode($enterNode->name);

            //throw T32 data to vinm
			if($nodeName == 'T32'){
                $vinMessage = $car->throwVinAssembly($car->vin, 'I线_T32');
            }

			//save component trace
			$car->addTraceComponents($enterNode, $componentCode);

            // if($enterNode->id == 4){
            //     $subTypes = array('subEngine','subFrontAxle');
            //     $car->addSubConfig($subTypes);
            // }

            $data = $car->generateInfoPaperData();
			$transaction->commit();
            $this->renderJsonBms(true, $vin . '成功录入' . $nodeName , $data);
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

	public function actionEnterF20() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
            $car = Car::create($vin);
            //$car->leftNode('F10');
            $car->enterNode('F20');

            $vinMessage = $car->throwVinAssembly($car->vin, 'I线_F20');
			//print check trace 
			$data = $car->generateCheckTraceData();
			$transaction->commit();
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }


	public function actionEnterVQ1() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
		    $faults = $this->validateStringVal('fault', '[]');

            $car = Car::create($vin);
            $car->leftNode('F20');
			$car->passNode('LEFT_WORK_SHOP');
            $car->enterNode('VQ1');
			$car->finish();

            //throw data to vinm
			$vinMessage = $car->throwVinAssembly($car->vin, '总装下线');
			
			$fault = Fault::create('VQ1_STATIC_TEST',$vin, $faults);
            $fault->save('在线');
			$car->throwTestlineCarInfo();
			$transaction->commit();
            $this->renderJsonBms(true, 'OK');

        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

	public function actionEnterLWS() {
        try{
            $vin = $this->validateStringVal('vin', '');
			$car = Car::create($vin);
			$fault = Fault::createSeeker();
            $exist = $fault->exist($car, '未修复', array('VQ1_STATIC_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ1还有未修复的故障');
            }

            $car->leftNode('VQ1');
            $car->enterNode('LEFT_WORK_SHOP', 0);
            $this->renderJsonBms(true, 'OK', $vin);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

	public function actionEnterECS() {
        try{
            $vin = $this->validateStringVal('vin', '');
            $car = Car::create($vin);
            $car->leftNode('LEFT_WORK_SHOP');
            $car->enterNode('ENTER_CHECK_SHOP', 0);
            $this->renderJsonBms(true, 'OK', $vin);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

	public function actionEnterCL() {
        try{
            $vin = $this->validateStringVal('vin', '');
            $car = Car::create($vin);
            $car->leftNode('ENTER_CHECK_SHOP');
            $car->enterNode('CHECK_LINE', 0);
            $this->renderJsonBms(true, 'OK', $vin);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        } 
    }

	public function actionEnterRTS() {
        try{
            $vin = $this->validateStringVal('vin', '');
			$driverId = $this->validateIntVal('driverId',0);
			if(empty($driverId)) {
				throw new Exception('请选择司机后再开始路试');
			}
            $car = Car::create($vin);
            $car->leftNode('CHECK_LINE');
			$car->passNode('VQ3');
            $car->enterNode('ROAD_TEST_START', $driverId);

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
			$bagCode = $this->validateStringVal('bag', '');
            $driverId = $this->validateStringVal('driver', 0);
            
            $fault = Fault::createSeeker();
           

            if(empty($driverId)) {
                throw new Exception('必须选择驾驶员');
            }

            $car = Car::create($vin);
			
			if($car->car->series != 'M6'){
				$car->leftNode('VQ1');
			}
            
			$exist = $fault->exist($car, '未修复', array('VQ1_STATIC_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ1还有未修复的故障');
            }
			$car->checkTestLinePassed();
			$car->passNode('VQ3');
            $car->enterNode('ROAD_TEST_FINISH', $driverId);
			
			$vinMessage = $car->throwVinAssembly($car->vin, '路试');
			
			$fault = Fault::create('VQ2_ROAD_TEST',$vin, $faults);
            $fault->save('在线');

			$car->addGasBagTraceCode($bagCode);
			$transaction->commit();
            $this->renderJsonBms(true, 'OK', null);
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
            $driverId = $this->validateStringVal('driver', 0);

			
            if(empty($driverId)) {
                throw new Exception('必须选择驾驶员');
            }
			
            $car = Car::create($vin);
			
			if($car->car->series != 'M6'){
				$car->leftNode('ROAD_TEST_FINISH');
			}
			
			$fault = Fault::createSeeker();
			$exist = $fault->exist($car, '未修复', array('VQ2_ROAD_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ2还有未修复的故障');
            }
		
			
			$car->passNode('VQ3');
            $car->enterNode('VQ2', $driverId);
			
			$vinMessage = $car->throwVinAssembly($car->vin, '淋雨');

			$fault = Fault::create('VQ2_LEAK_TEST',$vin, $faults);
            $fault->save('在线');
			$transaction->commit();
            $this->renderJsonBms(true, 'OK', $vin);
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
            $car = Car::create($vin);


			$fault = Fault::createSeeker();
            $exist = $fault->exist($car, '未修复', array('VQ2_ROAD_TEST_', 'VQ2_LEAK_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ2还有未修复的故障');
            }

			
			//只要进入VQ2，则可以多次进入VQ3
			
			if($car->car->series != 'M6'){
				$car->leftNode('VQ2');
			}
            
			$car->passNode('CHECK_IN');
            $car->enterNode('VQ3');
			
			$vinMessage = $car->throwVinAssembly($car->vin, '面漆预检');
			
			$fault = Fault::create('VQ3_FACADE_TEST',$vin, $faults);
            $fault->save('在线');
			$transaction->commit();
            $this->renderJsonBms(true, 'OK', $vin);
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }


	public function actionEnterCI() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
			$area = $this->validateIntVal('lane',0);
			$car = Car::create($vin);
			
			$fault = Fault::createSeeker();
            $exist = $fault->exist($car, '未修复',array('VQ3_FACADE_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ3还有未修复的故障');
            }

            $car->leftNode('VQ3');
			$car->passNode('CHECK_OUT');
            $car->enterNode('CHECK_IN',0);

			$car->moveToArea($area);
			$transaction->commit();
            $this->renderJsonBms(true, 'OK', $vin);
        } catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }


	public function actionEnterCO() {
		$transaction = Yii::app()->db->beginTransaction();
        try{
            $vin = $this->validateStringVal('vin', '');
            $lane = $this->validateIntVal('lane',0);
            if(empty($lane)) {
                throw new Exception('no lane has selected');
            }

            $car = Car::create($vin);
            $car->leftNode('CHECK_IN');
            $car->enterNode('CHECK_OUT',0);

			$car->moveToLane($lane);
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
            //$date = date('Y-m-d');
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
			$car->checkTestLinePassed();
			if($car->car->series != 'M6'){
				$car->leftNode('VQ3');
			}       
            $car->passNode('CHECK_OUT');
			if($car->car->warehouse_id > 0){
				$row = WarehouseAR::model()->findByPk($data['warehouse_id'])->row;
				throw new Exception ('此车状态为成品库_'. $row .'，不可重复入库');
			}
			
			if($car->car->distribute_time != '0000-00-00 00:00:00'){
				throw new Exception($vin . '已出库，不可再入库');
			}
			
            $onlyOnce = false;
            $car->enterNode('CHECK_IN', $driverId, $onlyOnce);
            
            //do not make the car standby while checkin point temporally
            // list($matched, $data) = $car->matchOrder($date);
            // if($matched) {
            //     $message = $vin . '已匹配订单' . $data['orderNumber'] . '请开往WDI区';
            // } else {
                $warehouse = new Warehouse;
                $data = $warehouse->checkin($vin);
                $message = $vin . '已成功入库，请开往' . $data['row'];
                $car->car->warehouse_id = $data['warehouse_id'];
                $car->car->area = $data['area'];
                $car->car->save();
            // }
			if(!empty($driverId)){
				$driverName = User::model()->findByPk($driverId)->display_name;
			} else {
				$driverName = Yii::app()->user->display_name;
			}
			$vinMessage = $car->throwVinStoreIn($car->vin, $data['row'], $driverName);
			
            $car->warehouseTime();
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
            // $date = date('Y-m-d');

            $car = Car::create($vin);
            $car->leftNode('CHECK_IN');
			$car->checkTestLinePassed();
            $onlyOnce = true;
            $car->enterNode('CHECK_OUT', $driverId, $onlyOnce);

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
            
            $outDate = date("Y-m-d h:m:s");
            $clientIp = $_SERVER["REMOTE_ADDR"];
            $car->throwCertificateData($outDate, $clientIp);
            $car->throwInspectionSheetData();
			
			if(!empty($driverId)){
				$driverName = User::model()->findByPk($driverId)->display_name;
			} else {
				$driverName = Yii::app()->user->display_name;
			}
			$order = OrderAR::model()->findByPk($car->car->order_id);
			$orderNumber = $order->order_number;
			$orderDetailId = $order->order_detail_id;
			
			$vinMessage = $car->throwVinStoreOut($vin, $data['lane'], $orderNumber, $orderDetailId, $car->car->distributor_name, $car->car->engine_code);

            $transaction->commit();
            $this->renderJsonBms(true, $message, $data);
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
            $content = "carID,流水号,VIN,车系,颜色,车型,配置,耐寒性,状态,录入时间,经销商,特殊订单号,备注,节点,驾驶员,录入人员,订单号,发动机号\n";
            foreach($datas as $data) {
                $content .= "{$data['car_id']},";
                $content .= "{$data['serial_number']},";
                $content .= "{$data['vin']},";
                $content .= "{$data['series']},";
                $content .= "{$data['color']},";
				$data['type'] = str_replace(",", "，",$data['type']);
                $content .= "{$data['type']},";
                $content .= "{$data['type_config']},";
                $content .= "{$data['cold_resistant']},";
                $content .= "{$data['status']},";
                $content .= "{$data['pass_time']},";
                $content .= "{$data['distributor_name']},";
                $content .= "{$data['special_order']},";
                $data['remark'] = str_replace(",", "，",$data['remark']);
                $data['remark'] = str_replace(PHP_EOL, '', $data['remark']);
                $content .= "{$data['remark']},";
                $content .= "{$data['node_name']},";
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

    public function actionConfigPlan() {
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
		$this->render('assembly/dataInput/PauseEdit');	
	}
	
	//added by wujun
	public function actionOrderMaintain() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_ASSEMBLY');
            $this->render('assembly/other/OrderMaintain');	
        } catch(Exception $e) {
            if($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
	}

    //added by wujun
    public function actionOutStandbyMaintain() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_ASSEMBLY');
            $this->render('assembly/other/OutStandbyMaintain');  
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

    //added by wujun
    public function actionOutStandby() {
        $this->render('assembly/dataInput/OutStandby');  
    }

    //added by wujun
    public function actionHoldRelease() {
        try{
            Yii::app()->permitManager->check('DATA_MAINTAIN_ASSEMBLY');
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
		 try{
            $vin = $this->validateStringVal('vin', '');
			$nodeName = 'T32';
			$componentCode = $this->validateStringVal('componentCode', '{}');

			$enterNode = Node::createByName($nodeName);
			$leftNode = $enterNode->getParentNode();

            $car = Car::create($vin);

            //throw T32 data to vinm
			if($nodeName == 'T32'){
                $vinMessage = $car->throwVinAssembly($car->vin, 'I线_T32');
            }

			//save component trace
			$car->addTraceComponents($enterNode, $componentCode);



            $this->renderJsonBms(true, $vin . '成功录入' . $nodeName , $vinMessage);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }  
    }

    public function actionDataThrowtest() {
        try{
             $vin = $this->validateStringVal('vin', '');
		$sql = "SELECT ToeFlag_F, LM_Flag, RM_Flag, RL_Flag, LL_Flag, Light_Flag, Slide_Flag, BrakeResistanceFlag_F, BrakeFlag_F, BrakeResistanceFlag_R, BrakeFlag_R, BrakeSum_Flag, ParkSum_Flag, Brake_Flag, Speed_Flag, GasHigh_Flag, GasLow_Flag, Final_Flag 
		FROM Summary WHERE vin='$vin'";
			
		$ret=Yii::app()->dbTest->createCommand($sql)->execute();
		if(empty($ret)){
			throw new Exception('此车未经过检测线，请返回检测线进行检验');
		} else if($ret['Final_Flag'] == 'F') {
			throw new Exception ('此车检测线未合格，请返回检测线进行检验');
		}
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }
}
