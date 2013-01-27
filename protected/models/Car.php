<?php
Yii::import('application.models.AR.*');
Yii::import('application.models.Node');
Yii::import('application.models.User');
Yii::import('application.models.VinManager');
Yii::import('application.models.CarYear');

class Car
{
	private $vin;
	private $car; 
	private $carYear;	
	private $config;
	private $configList;
	protected function __construct($vin){
		$this->vin = $vin;
		$this->car = VinManager::getCar($vin);
		$vin = $this->car->vin;		//added by wujun
		if(empty($this->car)){
			throw new Exception('vin ' . $this->vin . ' 对应的整车不存在');
		}
		$this->carYear = CarYear::getCarYear($vin);
		$this->config = CarConfigAR::model()->findByPk($this->car->config_id);
	}

	public static function create($vin) {
		$c = __class__;
		return new $c($vin);
	}

	public function __get($attr) {
		return $this->{$attr};
	}

	//fill extend info
	public function matchPlan($date) {
		$configId = $this->car->config_id;
		$carSeries = $this->car->series;		//added by wujun
		$data = $this->car->attributes;

		$planSeeker = new PlanSeeker();
		$plans = $planSeeker->search($date, $carSeries, '', false);
	
		$plansArray = array();
		foreach($plans as $plan) {
			if(!isset($plansArray[$plan['plan_date']])) {
				$plansArray[$plan['plan_date']] = array();
			}
			$plansArray[$plan['plan_date']][] = $plan;
		}
		$curPrority = PHP_INT_MAX;
		$data['adapt_plan'] = false;
		foreach($plansArray as $plans) {
			foreach($plans as $plan) {
				$priority = intval($plan['priority']);
				if($curPrority > $priority) {
					if($plan['car_body'] === $data['type'] && $plan['color'] === $data['color'] && intval($plan['total']) > intval($plan['ready']) && $data['special_order'] === $plan['special_order']) {			//added by wujun
						$data['plan_id'] = $plan['id'];
						$data['car_year'] = $plan['car_year'];
						$data['config_name'] = $plan['config_name'];
						$data['order_type'] = $plan['order_type'];
						$data['special_order'] = $plan['special_order'];
						$data['cold_resistant'] = $plan['cold_resistant'];		//added by wujun
						$data['remark'] = $plan['remark'];
						$data['adapt_plan'] = true;
						$curPrority = $priority;
					}
				}
			}
			if($data['adapt_plan']) {
				break;
			}	
		}
		return $data;
	}

	protected function getBodyMap() {
		if($this->car->series === 'F0') {
			$sql = "SELECT car_type FROM car_body_type_map WHERE series='F0' AND car_body = '{$this->car->type}'";
			return Yii::app()->db->createCommand($sql)->queryColumn();
		}
		return array($this->car->type);
	}

	public function finish() {
		if(empty($this->car->finish_time)) {
			$this->car->finish_time = date('Y-m-d H:i:s');
			$this->car->save();
		}
	}

	public function generateSerialNumber() {
		$series = $this->car->series;
		$snClass = "SerialNumber" . $series . "AR";
		
		$curYear = date('Y');
		$logYear = CurrentYearAR::model()->find('series=? AND cur_year=?', array($series, $curYear));
		if(empty($logYear)) {//must truncate SerialNumber
			$tableName = $snClass::model()->tableName();
			$sql = "TRUNCATE table $tableName";
			Yii::app()->db->createCommand($sql)->execute();

			$cur = new CurrentYearAR();
			$cur->series = $series;
			$cur->cur_year = $curYear;
			$cur->save();
		}

		
		$sn = new $snClass();
		$sn->save();
		//$year = $this->car->vin[9];
		$year = CarYear::getYearCode($curYear);
		$this->car->serial_number = $year . sprintf("%06d", $sn->id);
		$this->car->save();
	}

	public function addToPlan($date, $planId) {
		$exist = PlanAR::model()->findByPk($planId);
		if(empty($exist)) {
			throw new Exception($date . ' selected plan is not valid');
		}
		$this->car->plan_id = $planId;
		$this->car->config_id = $exist->config_id;
		$this->car->type = $exist->car_type;						//added by wujun
		$this->car->cold_resistant = $exist->cold_resistant;		//added by wujun
		$this->car->assembly_line = $exist->assembly_line;			//added by wujun
		$this->car->remark = $exist->remark;						//added by wujun
		$this->car->save();
	
		$sql = "UPDATE plan_assembly SET ready=ready+1 WHERE id=$planId";
		Yii::app()->db->createCommand($sql)->execute();
	}

	public function getConfigDetail($node) {
		$seeker = Config::create($this->car->config_id);
		return $seeker->getDetail($this->car, $node);
	}

	public function enterNode($nodeName,$driverId = 0, $onlyOnce = false) {
		$node = Node::createByName($nodeName);
		if(!$node->exist()){
			throw new Exception('不存在名字为' . $nodeName . '的节点');
		}

		$nodeId = $node->id;
		$exist = NodeTraceAR::model()->find('car_id =? AND node_id=?', array($this->car->id,$nodeId));
		//
		if($onlyOnce && !empty($exist)){
			throw new Exception('不能重复进入节点' .$node->display_name);
		}

		if(!empty($driverId)) {
			$user = User::model()->findByPk($driverId);
			if(empty($user)) {
				throw new Exception($driverId . '的司机不存在');
			}
		}
		//if(empty($exist)) {
			$passtime = date('YmdHis');
			$trace = new NodeTraceAR();
			$trace->node_id = $nodeId;
			$trace->user_id = Yii::app()->user->id;
			$trace->driver_id = $driverId;
			$trace->pass_time = $passtime;
			$trace->car_id = $this->car->id;
			$trace->car_series = $this->car->series;
			$trace->save();


			//car status 
			$this->detectStatus($node);
		//}
	}

	public function detectStatus($node = null) {
		if(empty($node)) {
			$sql = "SELECT max(node_id) FROM node_trace WHERE car_id={$this->car->id}";
			$nodeId = Yii::app()->db->createCommand($sql)->queryScalar();
			$node = Node::create($nodeId);
		}
		$zone = $node->main_zone;
		if($node->name === 'VQ1') {
			$fault = Fault::createSeeker();
            $exist = $fault->exist($this, '未修复', array('VQ1_STATIC_TEST_'));
			if(!empty($exist)) {
				$zone = $node->slave_zone;
			}	
		}

		
		if($node->name === 'ROAD_TEST_FINISH') {
            $fault = Fault::createSeeker();
            $exist = $fault->exist($this, '未修复', array('VQ2_ROAD_TEST_'));
            if(!empty($exist)) {
				$zone = $node->slave_zone;
            }
        }


		if($node->name === 'VQ2') {
            $fault = Fault::createSeeker();
            $exist = $fault->exist($this, '未修复', array('VQ2_LEAK_TEST_'));
            if(!empty($exist)) {
				$zone = $node->slave_zone;
            }
        }

		if($node->name === 'VQ3') {
            $fault = Fault::createSeeker();
            $exist = $fault->exist($this, '未修复', array('VQ3_FACADE_TEST_'));
            if(!empty($exist)) {
				$zone = $node->slave_zone;
            }
        }


		if($node->name === 'CHECK_IN') {
        }


		if($node->name === 'CHECK_OUT') {
        }

		$this->car->status = $zone;
		$this->car->save();
	}

	public function leftNode($nodeName) {
        $node = Node::createByName($nodeName);
        if(!$node->exist()){
            throw new Exception('不存在名字为' . $nodeName . '的节点');
        }
		if(YII_DEBUG) {
			return;
		}
        $nodeId = $node->id;
        $exist = NodeTraceAR::model()->find('car_id =? AND node_id=?', array($this->car->id,$nodeId));
        if(empty($exist)){
            throw new Exception($this->vin .'还没进入' .$node->display_name);
        }
	}

	public function passNode($nodeName) {
		$node = Node::createByName($nodeName);
        if(!$node->exist()){
            throw new Exception('不存在名字为' . $nodeName . '的节点');
        }
		if(YII_DEBUG) {
            return;
        }

        $nodeId = $node->id;
        $exist = NodeTraceAR::model()->find('car_id =? AND node_id=?', array($this->car->id,$nodeId));
        if(!empty($exist)){
            throw new Exception($this->vin .'已经通过' .$node->display_name);
        }
    }


	
	//pass node cars
	public static function countPassNode($nodeName, $date) {
		$curdaytime = strtotime($date);
		$curday = date('Y-m-d',$curdaytime);
		$curdaytime = strtotime($curday);
		$nextdaytime = $curdaytime + 86400;
		$nextday = date('Y-m-d',$nextdaytime);

        $node = Node::createByName($nodeName);
        if(!$node->exist()){
            throw new Exception('不存在名字为' . $nodeName . '的节点');
        }
        $nodeId = $node->id;
        $passCount = NodeTraceAR::model()->count('car_id =? AND node_id=? AND pass_time > ? AND pass_time < ? ', array($this->car->id, $nodeId, $curday, $nextday));
		
		return $passCount;
	}

	public function addTraceComponents($node, $componentCodeText) {
		$codeList = CJSON::decode($componentCodeText);
		if(empty($codeList)){
			return;
		}
		$nodeId = $node->id;
		$series = $this->car->series;
		$ctClass = $series . "ComponentTraceAR";
		Yii::import('application.models.AR.' .$ctClass);
		$messages = array();
		foreach($codeList as $componentId=>$code) {
            if(empty($code)) {
                continue;
            }
			try {
            	$this->checkBarCode($componentId, $code);
			} catch(Exception $e) {
				$messages[] = $e->getMessage();
			}
		}
		if(!empty($messages)) {
			$message = join("<br>", $messages);
			throw new Exception($message);
		}
		foreach($codeList as $componentId=>$code) {
			if(empty($code)) {
				continue;
			}
			//$this->checkBarCode($componentId, $code);	
			$trace = $ctClass::model()->find('car_id=? AND component_id=? AND node_id=?', array($this->car->id, $componentId, $nodeId));
			if(empty($trace)) {
				$trace = new $ctClass();
				$trace->car_id = $this->car->id;
				$trace->car_series = $this->car->series;
				$trace->car_type = $this->car->type;
				$trace->vin = $this->car->vin;
				$trace->component_id = $componentId;
				$trace->component_name = $this->getComponentName($componentId);
				$trace->node_id = $nodeId;
				$trace->create_time = date('YmdHis');
			}
			$trace->user_id = Yii::app()->user->id;
			$trace->user_display_name = Yii::app()->user->name;
			$trace->provider = $this->calProvider($code, $componentId);
			$trace->bar_code = $code;
			$trace->save();
		}
			
	}

	private function calProvider($barCode, $componentId) {
		if(empty($barCode)) {	
			return '';
		}
		$providerCode = '';
		$length = strlen($barCode);
		if($length == 17){//零部件代码为 6-8位
			//len 17 may be an engine
			$sub = substr($barCode, 3);
			if($sub == 'BYD') {
			} else {
				$providerCode = substr($barCode,0, 5);
			}
		} else if($length == 18){//零部件代码为 7-9位
			$providerCode = substr($barCode,0, 6);
		}
		if(!empty($providerCode)) {
			$p = ProviderAR::model()->find('code=?' , array($providerCode));
			if(!empty($p)) {
				return $p->display_name;
			}
		}

		return $this->getSpecialProvider($componentId);
	}

	private function getSpecialProvider($componentId) {
		$provider = '';
		if($componentId == 526 || $componentId == 533) {
			$p = ProviderAR::model()->findByPk(52);	
			$provider = $p->display_name;
		} elseif($componentId == 750) {
			$p = ProviderAR::model()->findByPk(19);
			$provider = $p->display_name;
		}
		
		return $provider;
	}


	public function checkBarCode($componentId, $code) {
		$configListComponent = CarConfigListAR::model()->find('config_id=? AND component_id=?',array($this->car->config_id,$componentId));
		if($configListComponent->istrace != 1) {//no need to check unique
			return;
		}
		if(empty($code)) {
			return;
		}
		$allSeries = array(
			'F0',
			'M6',
		);

		foreach($allSeries as $series) {
			$ctClass = $series . "ComponentTraceAR";
        	Yii::import('application.models.AR.' .$ctClass);

			$exist = $ctClass::model()->find('bar_code = ?', array($code));
			if(!empty($exist)) {
				if($exist->car_id == $this->car->id && $exist->component_id=$componentId) {
					continue;
				}
				throw new Exception("条码" .$code . " 已经存在!");
			}
		}
	}

	//371QA汽油机总成
	public function checkTraceGasolineEngine() {
		$componentName = '371QA汽油机总成';
		return $this->checkTraceComponentByName($componentName);
	}

	//变速箱总成
	public function checkTraceGearBox() {
		$componentName = '变速箱总成';
		return $this->checkTraceComponentByName($componentName);
	}

	public function checkTraceComponentByName($componentName) {
		$componentIds = $this->getComponentIds($componentName);
		if(empty($componentIds)) {
			throw new Exception($this->vin . ' 没有零部件 ' . $componentName . ' !');
		}
		$series = $this->car->series;
        $ctClass = $series . "ComponentTraceAR";
        Yii::import('application.models.AR.' .$ctClass);
		$componentIdText = join(',', $componentIds);
		$exist = $ctClass::model()->find("car_id=? AND component_id IN ($componentIdText) ", array($this->car->id));
		
		if(empty($exist)) {
			throw new Exception($this->vin . ' 还没有追溯零部件 ' . $componentName . ' !');
		}
		return $exist;
	}

	public function getComponentName($componentId) {
		$sql = "SELECT display_name FROM component WHERE id=$componentId";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}

	public function getProvider($componentId) {
		$c = CarConfigListAR::model()->find('component_id = ? AND config_id = ?', array($componentId, $this->car->config_id));

		$p = ProviderAR::model()->findByPk($c->provider_id);
		
		return empty($p) ? '' : $p->name;
	}

	public function getComponentIds($componentName) {
        $sql = "SELECT id FROM component WHERE display_name='$componentName'";
        return Yii::app()->db->createCommand($sql)->queryColumn();
    }


	//in vq2 
	public function addGasBagTraceCode($gasBagCode) {
		if(empty($gasBagCode)) {
			return;
		}
		$node = Node::createByName('ROAD_TEST_FINISH');	
	    $this->addTraceComponents($node, '{692:"'.$gasBagCode.'"}');	
	}

	public function getAllTrace($node) {
		$condition = "";
		if(!empty($node)) {
			$sql = "SELECT id FROM node WHERE name='$node'";
			$nodeId = Yii::app()->db->createCommand($sql)->queryScalar();
			$condition = " AND node_id=$nodeId";
		}
		$sql = "SELECT * FROM node_trace WHERE car_id={$this->car->id} $condition";
		$traces = Yii::app()->db->createCommand($sql)->queryAll();
		
		$sql = "SELECT * FROM node";
		$nodes = Yii::app()->db->createCommand($sql)->queryAll();

		$sql = "SELECT id,display_name FROM user";
		$users = Yii::app()->db->createCommand($sql)->queryAll();
		$userInfos = array();
		foreach($users as $user) {
			$userInfos[$user['id']] = $user['display_name'];
		}
		$userInfos[0] = '-';
		
		$nodeInfos = array();
		foreach($nodes as $node) {
			$nodeInfos[$node['id']] = $node['display_name'];
		}

		$series = $this->car->series;
		$sql = "SELECT create_time,modify_time,updator,component_name, fault_mode,status FROM VQ1_STATIC_TEST_$series WHERE car_id={$this->car->id}";
		$vq1s = Yii::app()->db->createCommand($sql)->queryAll();

		$sql = "SELECT create_time,modify_time,updator,component_name, fault_mode,status FROM VQ2_ROAD_TEST_$series WHERE car_id={$this->car->id}";
        $roads = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "SELECT create_time,modify_time,updator,component_name, fault_mode,status FROM VQ2_LEAK_TEST_$series WHERE car_id={$this->car->id}";
        $leaks = Yii::app()->db->createCommand($sql)->queryAll();


        $sql = "SELECT create_time,modify_time,updator,component_name, fault_mode,status FROM VQ3_FACADE_TEST_$series WHERE car_id={$this->car->id}";
        $vq3s = Yii::app()->db->createCommand($sql)->queryAll();


		$datas = array();
		$processed = array();
		foreach($traces as $trace) {
			$name = $nodeInfos[$trace['node_id']];
			$values = array();
			if(!empty($processed[$trace['node_id']])) {
				continue;
			}
			$processed[$trace['node_id']] = $trace['node_id'];
			switch($trace['node_id']) {
				case 10:
					$values = $vq1s;
					break;
				case 14:
					break;
				case 15:
					$values = $roads;
					break;
				case 16:
					$values = $leaks;
                    break;
				case 17:
					$values = $vq3s;
                    break;
				default:
					;
			} 
			if(empty($values)) {
				$trace['create_time'] = $trace['pass_time'];
				$trace['node_name'] = $name;
				$trace['fault'] = '-';
				$trace['fault_status'] = '-';
				$trace['user_name'] = $userInfos[$trace['user_id']];
				$trace['modify_time'] = '-';
				$datas[] = $trace;
			} else {
				foreach($values as $value) {
					$datas[] = array(
						'create_time' => $value['create_time'],
						'node_name' => $name,
						'fault' => $value['component_name'] . $value['fault_mode'],
						'fault_status' => $value['status'],
						'user_name' => $userInfos[$value['updator']],
						'modify_time' => $value['modify_time'],
					);
				}
			}
		}
		return $datas;
	}


	public function moveToArea($area) {
		$fault = Fault::createSeeker();
		$exist = $fault->exist($this, '未修复');
		if(!empty($exist)) {
			throw new Exception ('some exception has not been solve yet');
		}
		
		//$this->car->finish_time = date('Y-m-d H:i:s');
		$this->car->area = $area;
		$node = Node::createByName("CHECK_IN");
		$this->car->status = sprintf('%s%02d', $node->main_zone, $area);

		$this->car->save();

		//$sql = "UPDATE plan_assembly SET finished=finished+1 WHERE id={$this->car->plan_id}";
       // Yii::app()->db->createCommand($sql)->execute();

	}

	public function moveToLane($lane) {
        $this->car->lane = $lane;
		$node = Node::createByName("CHECK_OUT");
		$distributor = '';
		if(!empty($this->car->distributor_id)) {
			$distributor = DistributorAR::model()->findByPk($this->car->distributor_id);
			$distributor = $distributor->display_name; 
		}
        $this->car->status = sprintf('%s%2d_%s', $node->main_zone, $lane, $distributor);
		
        $this->car->save();
    }

	public function generateConfigData() {
        $barcodeGenerator = BarCodeGenerator::create("BCGcode39");
        $vinBarCodePath = "tmp/" .$this->car->vin .".jpeg";
        $barcodeGenerator->generate($this->car->vin,'./' .$vinBarCodePath);
		$config = CarConfigAR::model()->findByPk($this->car->config_id);
        $ret = array(
            'vinBarCode' => "/bms/" .$vinBarCodePath,
            'type' => $this->car->type,
            'serialNumber' => $this->car->serial_number,
            'date' => date('Y-m-d'),
            'color' => $this->car->color,
			'config' => $config->name,
            'remark'  => $this->car->remark,
            'vinCode' => $this->car->vin,
			'frontImage' => '/bms/configImage/' .$config->id . '/front.jpg',
			'backImage' => '/bms/configImage/' .$config->id . '/back.jpg',
		);
		return $ret;
	}


	public function generateCheckTraceData() {
		$barcodeGenerator = BarCodeGenerator::create("BCGcode39");
        $vinBarCodePath = "tmp/" .$this->car->vin .".jpeg";
        $barcodeGenerator->generate($this->car->vin,'./' .$vinBarCodePath);

		//$this->checkTraceGearBox();
		$engineTrace = $this->checkTraceGasolineEngine(); 
        $engineBarCodePath = "tmp/" .$this->car->vin . "_engine.png";
		$barcodeGenerator->generate($engineTrace->bar_code,'./' . $engineBarCodePath);
			
		$ret = array(
			'vinBarCode' => "/bms/" .$vinBarCodePath,
			'engineBarCode' => "/bms/" .$engineBarCodePath,
			'type' => $this->car->type,
			'serialNumber' => $this->car->serial_number,
			'date' => date('Y-m-d'),
			'color' => $this->car->color,
			'remark'  => $this->car->remark,
			'vinCode' => $this->car->vin,
			'engineCode' => $engineTrace->bar_code,
		);

		return $ret;
	}
		
	//added by wujun
	public function matchOrder($date) {
		$series = $this->car->series;
		$carType = $this->car->type;
		$config = $this->car->config_id;
		$color = $this->car->color;
		$coldResistant = $this->car->cold_resistant;
		//$carYear = $this->carYear;
		
		$order = new Order;            
        list($success, $data) = $order->match($series, $carType, $config, $color, $coldResistant, $date);
        if($success) {
        	$area = 'WDI';
            //$this->moveToArea($area);
            $warehouse = WarehouseAR::model()->find('area=?', array('WDI'));
            $warehouse->quantity += 1;
            $warehouse->save();

            $this->car->order_id = $data['orderId'];
            $this->car->status = '成品库_' . $area;
            $this->car->warehouse_id = $warehouse->id;
            $this->car->save();

            $data['area'] = $warehouse->area;
            $data['row'] = $warehouse->row;
        }

        return array($success, $data);
	}

}
