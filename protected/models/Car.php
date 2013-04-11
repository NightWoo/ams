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
		if($this->car->finish_time == '0000-00-00 00:00:00') {
			$this->car->finish_time = date('YmdHis');
			$this->car->save();
		}
	}

	public function warehouseTime() {
		if($this->car->warehouse_time == '0000-00-00 00:00:00') {
			$this->car->warehouse_time = date('YmdHis');
			$this->car->save();
		}
	}

	public function distributeTime() {
		if($this->car->distribute_time == '0000-00-00 00:00:00') {
			$this->car->distribute_time = date('YmdHis');
			$this->car->save();
		}
	}

	public function generateSerialNumber() {
		$series = $this->car->series;
		$snClass = "SerialNumber" . strtoupper($series) . "AR";
		
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
		$this->car->special_property = $exist->special_property;						//added by wujun
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
		//TODO：可以优化
		if(empty($node)) {
			$sql = "SELECT distinct node_id FROM node_trace WHERE car_id={$this->car->id}";
			$traceNodes = Yii::app()->db->createCommand($sql)->queryColumn();

			$nodeId = -1;
			if(!empty($traceNodes)) {
				$str = join(',', $traceNodes);
				$sql = "SELECT id FROM node WHERE id IN ($str) ORDER BY stage DESC";
				$nodeId = Yii::app()->db->createCommand($sql)->queryScalar();
			}		
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

		//if(YII_DEBUG) {
		// 	return;
		//}
		
        $nodeId = $node->id;
        $exist = NodeTraceAR::model()->find('car_id =? AND node_id=?', array($this->car->id,$nodeId));
        if(empty($exist)){
            throw new Exception($this->vin .'还没录入' .$node->display_name);
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
		$series = strtoupper($this->car->series);
		$ctClass = "ComponentTrace{$series}AR";
		Yii::import('application.models.AR.' .$ctClass);
		$messages = array();
		//deleted by wujun debug
		// foreach($codeList as $componentId=>$code) {
  //           if(empty($code)) {
  //               continue;
  //           }
		// 	try {
  //           	$this->checkBarCode($componentId, $code);
		// 	} catch(Exception $e) {
		// 		$messages[] = $e->getMessage();
		// 	}
		// }
		// if(!empty($messages)) {
		// 	$message = join("<br>", $messages);
		// 	throw new Exception($message);
		// }
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

	private function calProvider($barCode, $componentId , $fullname = false) {
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
				if($fullname){
					return $p->name;
				} else {
					return $p->display_name;
				}
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
			'6B',
		);

		foreach($allSeries as $series) {
			$ctClass = "ComponentTrace{$series}AR";
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

	//汽油机
	public function checkTraceGasolineEngine() {
		$sql = "SELECT engine_component_id FROM car_engine WHERE car_series='{$this->car->series}'";

		$componentIds = Yii::app()->db->createCommand($sql)->queryColumn();
		
		$str = join(',', $componentIds);

		$sql = "SELECT c.id,c.name FROM car_config_list l, component c WHERE c.id=l.component_id AND l.config_id={$this->car->config_id} AND c.id IN ($str) AND l.istrace=1";

		$component = Yii::app()->db->createCommand($sql)->queryRow();


		if(empty($component)) {
			throw new Exception('该车配置不存在可追溯的汽油机');
		}
			
		return $this->checkTraceComponentByIds(array($component['id']), $component['name']);
	}

	//变速箱总成
	public function checkTraceGearBox() {
		// $componentName = '变速箱总成';
		// return $this->checkTraceComponentByName($componentName);
		$sql = "SELECT gearbox_component_id FROM car_gearbox WHERE car_series='{$this->car->series}'";

		$componentIds = Yii::app()->db->createCommand($sql)->queryColumn();
		
		$str = join(',', $componentIds);

		$sql = "SELECT c.id,c.name FROM car_config_list l, component c WHERE c.id=l.component_id AND l.config_id={$this->car->config_id} AND c.id IN ($str) AND l.istrace=1";

		$component = Yii::app()->db->createCommand($sql)->queryRow();


		if(empty($component)) {
			return;
		}
			
		return $this->checkTraceComponentByIds(array($component['id']), $component['name']);
	}

	//ABS or ESC
	public function checkTraceABS() {
		
		$sql = "SELECT abs_component_id FROM car_abs WHERE car_series='{$this->car->series}'";

		$componentIds = Yii::app()->db->createCommand($sql)->queryColumn();
		
		$str = join(',', $componentIds);

		$sql = "SELECT c.id,c.name FROM car_config_list l, component c WHERE c.id=l.component_id AND l.config_id={$this->car->config_id} AND c.id IN ($str) AND l.istrace=1";

		$component = Yii::app()->db->createCommand($sql)->queryRow();


		if(empty($component)) {
			return;
		}
			
		return $this->checkTraceComponentByIds(array($component['id']), $component['name']);
	}

	public function checkTraceComponentByName($componentName) {
		$componentIds = $this->getComponentIds($componentName);
		return $this->checkTraceComponentByIds($componentIds, $componentName);
	}

	public function checkTraceComponentByIds($componentIds, $componentName = '') {
		if(empty($componentIds)) {
			throw new Exception($this->vin . ' 没有零部件 ' . $componentName . ' !');
		}
		$series = strtoupper($this->car->series);
        $ctClass = "ComponentTrace{$series}AR";
        Yii::import('application.models.AR.' .$ctClass);
		$componentIdText = join(',', $componentIds);
		$exist = $ctClass::model()->find("car_id=? AND component_id IN ($componentIdText) ", array($this->car->id));
		
		if(empty($exist)) {
			throw new Exception($this->vin . ' 还没有追溯零部件 ' .$componentName.  '!');
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
				if(!empty($trace['driver_id'])){
					$trace['user_name'] = $userInfos[$trace['driver_id']];
				} else {
					$trace['user_name'] = $userInfos[$trace['user_id']];
				}
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
		$sortDatas = $this->multi_array_sort($datas, 'create_time');
		// return $datas;
		return $sortDatas;
	}

	function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC){  
        if(is_array($multi_array)){  
            foreach ($multi_array as $row_array){  
                if(is_array($row_array)){  
                    $key_array[] = $row_array[$sort_key];  
                }else{  
                    return -1;  
                }  
            }  
        }else{  
            return -1;  
        }  
        array_multisort($key_array,$sort,$multi_array);  
        return $multi_array;  
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

		$types = array('front','back');
		$images = array();
		if(!empty($config)) {
			$path = "/home/work/bms/web/bms/configImage/" . $config->id;
			foreach($types as $type) {
				$name = $type . '.jpg';
				$fileName = $path . '/' . $name;
				$images[$type] = '';
				if(file_exists($fileName)) {
					$images[$type] = '/bms/configImage/' .$config->id . '/' . $name;
				}
			}
		}

        $ret = array(
            'vinBarCode' => "/bms/" .$vinBarCodePath,
            'type' => $this->car->type,
            'serialNumber' => $this->car->serial_number,
			'series' => $this->car->series,
            'date' => date('Y-m-d'),
            'color' => $this->car->color,
			'config' => $config->name,
            'remark'  => $this->car->remark,
            'vinCode' => $this->car->vin,
            'coldResistant' => $this->car->cold_resistant,
			'frontImage' => $images['front'],
			'backImage' => $images['back'],
		);
		return $ret;
	}
	
	public function addSubConfig($types = array('subInstrument','subEngine','subFrontAxle','subRearAxle')) {
		// $types = array('subInstrument','subEngine','subFrontAxle','subRearAxle');
		foreach($types as $type) {
			$subConfig = SubConfigCarQueueAR::model()->find('car_id=? AND type=?', array($this->car->id,$type));

			if(empty($subConfig)) {
				$subConfig = new SubConfigCarQueueAR();

				$subConfig->car_id = $this->car->id;
				$subConfig->vin = $this->car->vin;
				$subConfig->type = $type;
				$subConfig->status = 0;
				$subConfig->queue_time = date('Y-m-d H:i:s'); 
				$subConfig->save();
			}
		}
	}
	
	//force : true force to print
	public function generateSubConfigData($type='subInstrument', $force = false) {
		$subConfig = SubConfigCarQueueAR::model()->find('car_id=? AND type=?', array($this->car->id,$type));
		if(empty($subConfig)) {//suit for those cars has passed t0 
			//$this->addSubConfig();
			//$subConfig = SubConfigCarQueueAR::model()->find('car_id=? AND type=?', array($this->car->id,$type));
			throw new Exception("不存在分装配置");
		}

		if(!$force) {
			if($subConfig->status == 2) {//forbid print
				$info = array(1=>"已经",2=>"禁止");
				throw new Exception("该分装配置{$info[$subConfig->status]}打印");
			}
		}		

        $barcodeGenerator = BarCodeGenerator::create("BCGcode39");
        $vinBarCodePath = "tmp/" .$this->car->vin .".jpeg";
        $barcodeGenerator->generate($this->car->vin,'./' .$vinBarCodePath);
		$config = CarConfigAR::model()->findByPk($this->car->config_id);

		$images = array();
		if(!empty($config)) {
			$path = "/home/work/bms/web/bms/configImage/" . $config->id;
			$name = $type . '.jpg';
			$fileName = $path . '/' . $name;
			$image = '';
			if(file_exists($fileName)) {
				$image = '/bms/configImage/' .$config->id . '/' . $name;
			}
		}

        $ret = array(
            'vinBarCode' => "/bms/" .$vinBarCodePath,
            'type' => $this->car->type,
            'serialNumber' => $this->car->serial_number,
			'series' => $this->car->series,
            'date' => date('Y-m-d'),
            'color' => $this->car->color,
			'config' => $config->name,
            'remark'  => $this->car->remark,
            'vinCode' => $this->car->vin,
            'coldResistant' => $this->car->cold_resistant,
			'image' => $image,
		);
		

		if(!empty($subConfig)) {
			$subConfig->status = 1;
			$subConfig->save();
		}

		return $ret;
	}

	public function generateCheckTraceData() {
		$barcodeGenerator = BarCodeGenerator::create("BCGcode39");
        $vinBarCodePath = "tmp/" .$this->car->vin .".jpeg";
        $barcodeGenerator->generate($this->car->vin,'./' .$vinBarCodePath);
        $config = CarConfigAR::model()->findByPk($this->car->config_id);
        $configName = $config->name;
        $typeName = $this->cutCarType($this->car->type);
        $coldResistant = $this->car->cold_resistant==1? '耐寒' : '非耐寒';

		//$this->checkTraceGearBox();
		$engineTrace = $this->checkTraceGasolineEngine(); 
        $engineBarCodePath = "tmp/" .$this->car->vin . "_engine.png";
        $engineCode = $engineTrace->bar_code;
		$barcodeGenerator->generate($engineCode,'./' . $engineBarCodePath);

		$this->car->engine_code = $engineCode;
		$this->car->save();
			
		$ret = array(
			'vinBarCode' => "/bms/" .$vinBarCodePath,
			'engineBarCode' => "/bms/" .$engineBarCodePath,
			'type' => $typeName . '/' . $configName . '/' . $coldResistant,
			'serialNumber' => $this->car->serial_number,
			'date' => date('Y-m-d'),
			'color' => $this->car->color,
			'remark'  => $this->car->remark,
			'vinCode' => $this->car->vin,
			'engineCode' => $engineTrace->bar_code,
			'series' => $this->car->series,
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
            $this->car->status = 'WDI';
            $this->car->warehouse_id = $warehouse->id;
            $this->car->save();

            $data['area'] = $warehouse->area;
            $data['row'] = $warehouse->row;
        }

        return array($success, $data);
	}
	
	
	public function throwTestlineCarInfo(){

		//好像有点太过程化了，找时间优化
		$vin = $this->car->vin;
		$series = $this->car->series;
		$color = $this->car->color;
		
		$carType = $this->car->type;
		$carType = str_replace("（", "(",$carType);
		$carType = str_replace("）", ")",$carType);
		
		$carModel = CarTypeMapAR::model()->find('car_type=?', array($this->car->type))->car_model;
		$seriesName = CarSeriesAR::model()->find('series=?', array($this->car->series))->name;
		
		$engineTrace = $this->checkTraceGasolineEngine();
		// $engineCode = $engineTrace->bar_code;
		$engine = CarEngineAR::model()->find('engine_component_id=?', array($engineTrace->component_id));
		$engineType = $engine->engine_type;
		$engineCode = substr($engineTrace->bar_code, -$engine->code_digit);
		
		$insertsql = "INSERT INTO testline_car_info
				SET vin='{$vin}', series='{$series}', series_name='{$seriesName}', car_model='{$carModel}', `car_type`='{$carType}', engine_type = '{$engineType}', engine_code='{$engineCode}', color='{$color}'";
		$updatesql = "UPDATE testline_car_info
						SET series='{$series}', series_name='{$seriesName}', car_model='{$carModel}', `car_type`='{$carType}', engine_type = '{$engineType}', engine_code='{$engineCode}', color='{$color}' 
						WHERE vin='{$vin}'";
		$existsql = "SELECT vin FROM testline_car_info WHERE vin='{$vin}'";				
		
		$exist=Yii::app()->db->createCommand($existsql)->execute();
		if(empty($exist)){
			Yii::app()->db->createCommand($insertsql)->execute();
		}else{
			Yii::app()->db->createCommand($updatesql)->execute();
		}
		
	}
	
	public function checkTestLinePassed() {
		$vin = $this->car->vin;
		$sql = "SELECT ToeFlag_F, LM_Flag, RM_Flag, RL_Flag, LL_Flag, Light_Flag, Slide_Flag, BrakeResistanceFlag_F, BrakeFlag_F, BrakeResistanceFlag_R, BrakeFlag_R, BrakeSum_Flag, ParkSum_Flag, Brake_Flag, Speed_Flag, GasHigh_Flag, GasLow_Flag, Final_Flag 
		FROM Summary WHERE vin='$vin'";
			
		$ret=Yii::app()->dbTest->createCommand($sql)->queryRow();
		if(empty($ret)){
			throw new Exception('此车未经过检测线，请返回检测线进行检验');
		} else if($ret['Final_Flag'] == 'F') {
			throw new Exception ('此车检测线未合格，请返回检测线进行检验');
		}
		
		return;
	}

	public function throwInspectionSheetData() {
		//好像有点太过程化了，找时间优化
		$carId = $this->car->id;
		$vin = $this->car->vin;
		$config = $this->car->config_id;
		$series = $this->car->series;
		$color = $this->car->color;

		$carType = $this->car->type;
		$carType = str_replace("（", "(",$carType);
		$carType = str_replace("）", ")",$carType);

		$engineTrace = $this->checkTraceGasolineEngine();
		// $engineCode = $engineTrace->bar_code;
		$engine = CarEngineAR::model()->find('engine_component_id=?', array($engineTrace->component_id));
		$engineType = $engine->engine_type;
		$engineCode = substr($engineTrace->bar_code, -$engine->code_digit);

		$cData = $this->getCertificateData($carId);

		$insertsql = "INSERT INTO ShopPrint
				SET vin='{$vin}', Order_ID='{$cData['order_number']}', VenName='{$cData['distributor_name']}', Clime='{$cData['country']}', `Path`='{$cData['lane_name']}', Series='{$series}', Type='{$carType}', Color='{$color}', EngineType='{$engineType}', engineCode='{$engineCode}' ";
		$updatesql = "UPDATE ShopPrint
						SET Order_ID='{$cData['order_number']}', VenName='{$cData['distributor_name']}', Clime='{$cData['country']}', `Path`='{$cData['lane_name']}', Series='{$series}', Type='{$carType}', Color='{$color}', EngineType='{$engineType}', engineCode='{$engineCode}'
						WHERE vin='{$vin}'";
		$existsql = "SELECT vin,Order_ID FROM ShopPrint WHERE vin='{$vin}'";				
		
		$exist=Yii::app()->dbTest->createCommand($existsql)->execute();
		if(empty($exist)){
			Yii::app()->dbTest->createCommand($insertsql)->execute();
		}else{
			Yii::app()->dbTest->createCommand($updatesql)->execute();
		}
	}

	public function getCertificateData($carId) {
		$sql = "SELECT car_model, order_number, country, distributor_name, order_detail_id, order_nature, certificate_note, assisted_stecring, tyre, lane_name, sell_color, sell_car_type
				FROM view_certificate
				WHERE car_id = $carId";
		$data = Yii::app()->db->createCommand($sql)->queryRow();
		if(!empty($data)) {
			return $data;
		}
	}

	public function throwCertificateData($outDate, $computerName= '10.23.1.67', $country='国内', $district='比亚迪长沙') {
		$carId = $this->car->id;
		$vin = $this->car->vin;
		$config = $this->car->config_id;
		$coldResistant = $this->car->cold_resistant;

		// unnecessary
		// if($this->car->series == '6B'){
		// 	$cSeries = iconv('UTF-8', 'GB2312', '思锐');
		// } else {
		// 	$cSeries = iconv('UTF-8', 'GB2312', $this->car->series);
		// }

		if(empty($outDate)){
			$outDate = date("Y-m-d h:m:s");
		} 

		// $sql = "SELECT car_model, order_number, country, distributor_name, order_detail_id, order_nature, certificate_note, assisted_stecring, tyre, lane_name, sell_color, sell_car_type
		// 		FROM view_certificate
		// 		WHERE car_id = $carId";
		// $cData = Yii::app()->db->createCommand($sql)->queryRow();
		$cData = $this->getCertificateData($carId);
		foreach($cData as $key => $data){
			$cData[$key] = iconv('UTF-8', 'GB2312', $data);
		}

		$carType = iconv('UTF-8', 'GB2312', $this->car->type);
		$color = iconv('UTF-8', 'GB2312', $this->car->color);
		$district = iconv('UTF-8', 'GB2312', $district);

		$engineTrace = $this->checkTraceGasolineEngine();
		$engineCode = $engineTrace->bar_code;

		$gearboxCode = '';
		$absInfo = '';
		if(($this->car->series != 'M6')){
			$gearboxTrace = $this->checkTraceGearBox() ;
			$gearboxCode = $gearboxTrace->bar_code;

			$absTrace = $this->checkTraceABS();
			if(!empty($absTrace) && ($this->car->series == 'F0')){
				$barCode = $absTrace->bar_code;
				$abs = $this->getAbsInfo($barCode);
				$absInfo = '';
				$absInfo = "ABS系统控制器型号：" . $abs['type'] . "；ABS系统控制器生产企业：" . $abs['provider'];
				$absInfo = iconv('UTF-8', 'GB2312', $absInfo);
				$cData['certificate_note'] .= $absInfo;
			}
		}

		$insertsql = "INSERT INTO Print_Table(DGMXID,VIN,CLXH,CLYS,FDJH,NOTE,DGDH,SCD,CLXZ,DDXZ,EMP,AUTO_GEARBOX,AUTO_DATE,Zxzlxs,Clkx,Ltgg,WZCLXH) 
                		   VALUES('{$cData['order_detail_id']}','{$vin}', '{$cData['car_model']}', '{$cData['sell_color']}', '{$engineCode}', '{$cData['certificate_note']}', '{$cData['order_number']}', '{$district}', '{$cData['country']}', '{$cData['order_nature']}', '{$computerName}', '{$gearboxCode}', '{$outDate}', '{$cData['assisted_stecring']}', '{$carType}', '{$cData['tyre']}', '{$cData['sell_car_type']}')";
		$updatesql = "UPDATE Print_Table
						SET DGMXID='{$cData['order_detail_id']}', CLXH='{$cData['car_model']}', CLYS='{$cData['sell_color']}', FDJH='{$engineCode}', NOTE='{$cData['certificate_note']}', DGDH='{$cData['order_number']}', SCD='{$district}', CLXZ='{$cData['country']}', DDXZ='{$cData['order_nature']}',  EMP='{$computerName}', AUTO_GEARBOX='{$gearboxCode}', AUTO_DATE='{$outDate}', Zxzlxs='{$cData['assisted_stecring']}', Clkx='{$carType}', Ltgg='{$cData['tyre']}', WZCLXH='{$cData['sell_car_type']}'
						WHERE VIN='{$vin}'";

		//insert
		$tdsSever = Yii::app()->params['tds_HGZ'];
        $tdsDB = Yii::app()->params['tds_dbname_HGZ_DATABASE'];
        $tdsUser = Yii::app()->params['tds_HGZ_username'];
        $tdsPwd = Yii::app()->params['tds_HGZ_password'];  
        if($this->existInHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $vin)){
	   		$this->wrightHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $updatesql);
        }else{
	   		$this->wrightHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $insertsql);
        }   
	}

	public function existInHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $vin){
		$exist = false;

		$sql = "SELECT VIN,DGDH,DGMXID FROM Print_Table WHERE VIN='{$vin}'";
		//connect
        $mssql=mssql_connect($tdsSever, $tdsUser, $tdsPwd);
        if(empty($mssql)) {
            throw new Exception("cannot connet to sqlserver $tdsSever, $tdsUser ");
        }
        mssql_select_db($tdsDB ,$mssql);
        
        //execute insert
        $ret=mssql_query($sql);
        
        //disconnect
        mssql_close($mssql);

        if(mssql_num_rows($ret) > 0){
        	$exist = true;
        }

        return $exist;
	}

	public function wrightHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $sql) {
   
        //connect
        $mssql=mssql_connect($tdsSever, $tdsUser, $tdsPwd);
        if(empty($mssql)) {
            throw new Exception("cannot connet to sqlserver $tdsSever, $tdsUser ");
        }
        mssql_select_db($tdsDB ,$mssql);
        
        //execute insert
        mssql_query($sql);
        
        //disconnect
        mssql_close($mssql);
	}

	public function getAbsInfo($barCode) {
		$providerCode = substr($barCode, 0, 6);
		$type = array(
						'118627' => 'DBC7',
						'104029' => 'ABS',
						'102442' => 'WX80'
						);

		$provider = array(
							'118627' => '京西重工（上海）有限公司',
							'104029' => '博世汽车部件（苏州）有限公司',
							'102442' => '浙江万向精工有限公司'
							);

		return array('type' => $type[$providerCode], 'provider' => $provider[$providerCode]);
	}

	public function throwVinAssembly($vin, $point, $shift='总装I线-A班'){
		
		// $ponit = iconv('UTF-8', 'GB2312', $ponit);
		// $shift = iconv('UTF-8', 'GB2312', $shift);

		$client = new SoapClient(Yii::app()->params['ams2vin_assembly']);
		// $client->soap_defencoding = 'utf-8';
		// $client->decode_utf8 = false;
		$params = array(
			'Vincode'=>$vin, 
			'Work'=>$point, 
			'Team'=>$shift
		);
		
		$result = $client -> Assembly($params);

		return $result;
	}

	public function throwVinStorIn($vin, $row, $driverName=''){
		
		// $row = iconv('UTF-8', 'GB2312', $row);
		// $driverName = iconv('UTF-8', 'GB2312', $driverName);

		$client = new SoapClient(Yii::app()->params['ams2vin_store_in']);
		// $client->soap_defencoding = 'utf-8';
		// $client->decode_utf8 = false;
		$params = array(
			'Vincode'=>$vin, 
			'Area'=>$row, 
			'EmpName'=>$driverName
		);
		$result = $client -> StoreIn($params);

		return $result;
	}

	public function throwVinStorOut($vin, $lane, $order, $orderDetailId, $distributorName, $engineCode){
		
		// $distributorName = iconv('UTF-8', 'GB2312', $distributorName);

		$client = new SoapClient(Yii::app()->params['ams2vin_store_in']);
		// $client->soap_defencoding = 'utf-8';
		// $client->decode_utf8 = false;
		$params = array(
			'Vincode'=>$vin, 
			'Area'=>$lane, 
			'Order'=>$order,
			'OrderID'=>$orderDetailId,
			'VenName'=>$distributorName,
			'AutoEngine'=>$engineCode
		);
		$result = $client -> StoreOut($params);

		return $result;
	}

	private function cutCarType($type) {
		$length = strlen($type);
        $typeName = '';
		$i = 0;
        while($i < $length){
            if($type[$i] === '(' || $i === stripos($type, '（')){
            	break;
            } else {	
            	$typeName .= $type[$i];
            	$i++;
            }
        }
        if(empty($typeName)){
        	$typeName = $type;
        }

        return $typeName;
	}

}
