<?php
Yii::import('application.models.Car');
Yii::import('application.models.VinManager');
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
			$car->leftNode('PBS');

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

			$data = array(
				'car' => $car->car,
				'components' => $car->getConfigDetail($nodeName),
			);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
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

			$car->passNode('CHECK_OUT');
            $this->renderJsonBms(true, 'OK', $car->car);
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

	public function actionTest()  {
		$vin = $this->validateStringVal('vin', '');
		$car = VinManager::importCar($vin);

		var_dump($car);
	}

}