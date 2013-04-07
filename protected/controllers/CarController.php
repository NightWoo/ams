<?php
Yii::import('application.models.Car');
Yii::import('application.models.VinManager');
Yii::import('application.models.SubConfigSeeker');
Yii::import('application.models.AR.WarehouseAR');
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
			$car->leftNode($leftNode->name);
            
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
			$car->leftNode('F10');
			$car->checkTraceGasolineEngine();
			//$car->checkTraceGearBox();
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

            $car->leftNode('VQ1');
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

            $car->leftNode('VQ1');

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
            $car->leftNode($leftNode->name);
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
        try{
            $car = Car::create($vin);
            $data = $car->getAllTrace($node);
            $this->renderJsonBms(true, 'OK', array('traces' => $data, 'car'=> $car->car, 'status' => $car->car->status));
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

			$car->leftNode('VQ2');

            $fault = Fault::createSeeker();
            $exist = $fault->exist($car, '未修复', array('VQ2_ROAD_TEST_', 'VQ2_LEAK_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ2还有未修复的故障');
            }
			$exist = $fault->exist($car, '未修复', array('VQ1_STATIC_TEST_'));
            if(!empty($exist)) {
                throw new Exception ($vin .'车辆在VQ1还有未修复的故障');
            }

            $car->passNode('CHECK_IN');


            $this->renderJsonBms(true, 'OK', $car->car);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }


	public function actionValidateCI() {
        try{
            $vin = $this->validateStringVal('vin', '');
            $area = $this->validateIntVal('area',0);

            $car = Car::create($vin);

			$car->leftNode('VQ3');

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
			if($car->car->warehouse_id > 1){
				$row = WarehouseAR::model()->findByPk($data['warehouse_id'])->row;
				throw new Exception ('此车状态为成品库_'. $row .'，不可重复入库');
			}

			$car->passNode('CHECK_OUT');

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
            list($total, $data) = $seeker->queryBalanceDetail($state, $series, $curPage, $perPage);
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
            list($total, $datas) = $seeker->queryBalanceDetail($state, $series, 0, 0);
            
            $title = "车系,VIN,颜色,车型,车型/配置,耐寒性,状态,库区,下线时间,入库时间,备注\n";
            $content = "";
            foreach($datas as $data) {
                $content .= "{$data['series']},";
                $content .= "{$data['vin']},";
                $content .= "{$data['color']},";
                $content .= "{$data['type']},";
                $content .= "{$data['type_info']},";
                $content .= "{$data['cold']},";
                $content .= "{$data['status']},";
                $content .= "{$data['row']},";
                $content .= "{$data['finish_time']},";
                $content .= "{$data['warehouse_time']},";
                $data['remark'] = str_replace(",", "，",$data['remark']);
                $data['remark'] = str_replace(PHP_EOL, '', $data['remark']);
                $content .= "{$data['remark']},";
                $content .= "\n";
            }
            $export = new Export($state . '结存查询_' .date('Ymd'), $title . $content);
            $export->toCSV();


        } catch(Exception $e) {
        }
    }

	public function actionTest()  {
		$vin = $this->validateStringVal('vin', '');
		$car = VinManager::importCar($vin);

		var_dump($car);
	}

}
