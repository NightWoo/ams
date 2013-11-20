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
	// private static $SERIES_NAME = array('F0'=>'F0','M6'=>'M6','6B'=>'思锐',"G6"=>"G6");
	private static $LC0_TYPE_ARRAY = array('QCJ7152ET2(1.5TID豪华型)','QCJ7152ET2(1.5TID尊贵型加全景)','QCJ7100L(1.0排量舒适型)','QCJ7100L(1.0排量实用型)','QCJ7152ET1(1.5TI豪华型)','QCJ6480M1(2.4排量舒适型,无后空调)','QCJ6480M1(2.4排量舒适型)','QCJ6480M(2.0排量舒适型)','BYD7100L3(1.0排量实用型)','BYD7100L3(1.0排量舒适型)','QCJ7100L(1.0排量实用型,加液压)','QCJ7100L5(1.0排量实用型北京)','QCJ7100L5(1.0排量舒适型北京)','QCJ7100L5(1.0排量尊贵型北京)','BYD6480MA5(2.4排量豪华型国五)','BYD6480MA5(2.4排量舒适型国五)','BYD6480MA5(2.4排量尊贵型国五)','BYD6480M5(2.0排量舒适型国五)','BYD6480M5(2.0排量舒适型国五,无后空调)','BYD7152ET2(1.5TID尊贵型国五)','BYD7152ET2(1.5TID尊贵型加全景国五)','BYD7152ET1(1.5TI尊贵型国五)','BYD7152ET1(1.5TI尊享型国五)','QCJ7152ET2(1.5TID旗舰型公务版)');
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

	public static function create ($vin) {
		$c = __class__;
		return new $c($vin);
	}

	public function __get($attr) {
		return $this->{$attr};
	}

	//fill extend info
	public function matchPlan ($date,$line) {
		$configId = $this->car->config_id;
		$carSeries = $this->car->series;		//added by wujun
		$data = $this->car->attributes;
		$planId = $this->car->plan_id;
		if(empty($planId)){
			$planSeeker = new PlanSeeker();
			$plans = $planSeeker->search($date, $carSeries, $line, false ,true);
		
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
		} else {
			$data['adapt_plan'] = true;
		}


		return $data;
	}

	public function checkRatioControl ($line) {
		if(!empty($this->car->skip_ratio_control)) return;
		$ratioControl = OnlineRatioControlAR::model()->find("activated=1 AND line=?", array($line));
		if(!empty($ratioControl) && $ratioControl->series != $this->car->series) {
			$seriesName = Series::getNameList();
			$activatedSeriesName = $seriesName[$ratioControl->series]; 
			$thisSeriesName = $seriesName[$this->car->series]; 
			throw new Exception("按照上线比例控制，当前应上线[" . $activatedSeriesName . "]，此车为[" . $thisSeriesName . "]不可上线，如需跳过上线比例控制，请联系生产调度。");
		}
	}

	public function ratioControlNext ($line) {
		if(!empty($this->car->skip_ratio_control)) return;
		$ratioControl = OnlineRatioControlAR::model()->find("activated=1 AND line='$line'");
		if(empty($ratioControl)) return;
		$next = OnlineRatioControlAR::model()->find("id>? AND line=? ORDER BY id ASC",array($ratioControl->id, $line));
		if(empty($next)) $next = OnlineRatioControlAR::model()->find("id<? AND line=? ORDER BY id ASC",array($ratioControl->id, $line));
		if(!empty($next)) {
			$ratioControl->activated = 0;
			$next->activated = 1;
			$ratioControl->save();
			$next->save();
		}
	}

	protected function getBodyMap () {
		if($this->car->series === 'F0') {
			$sql = "SELECT car_type FROM car_body_type_map WHERE series='F0' AND car_body = '{$this->car->type}'";
			return Yii::app()->db->createCommand($sql)->queryColumn();
		}
		return array($this->car->type);
	}

	public function assemblyTime () {
		if($this->car->assembly_time == '0000-00-00 00:00:00') {
			$this->car->assembly_time = date('YmdHis');
			$this->car->save();
		}
	}

	public function finish () {
		if($this->car->finish_time == '0000-00-00 00:00:00') {
			$this->car->finish_time = date('YmdHis');
			$this->car->save();
		}
	}

	public function vq1Finish () {
		if($this->car->finish_time > '0000-00-00 00:00:00' && $this->car->vq2_finish_time == '0000-00-00 00:00:00') {
			switch($this->car->assembly_line){
				case  "I" :
					$tablePrefix = "VQ1_STATIC_TEST_";
					break;
				case "II" :
					$tablePrefix = "VQ1_STATIC_TEST_2_";
					break;
				default :
					$tablePrefix = "VQ1_STATIC_TEST_";
			}

			$fault = Fault::createSeeker();
            $exist = $fault->exist($this, '未修复', array($tablePrefix));

            if(empty($exist)) {
				$this->car->vq1_finish_time = date('YmdHis');
				$this->car->save();
			}
		}
	}

	public function vq2Finish () {
		if($this->car->vq1_finish_time > '0000-00-00 00:00:00' && $this->car->warehouse_time == '0000-00-00 00:00:00') {
			$fault = Fault::createSeeker();
            $exist = $fault->exist($this, '未修复', array('VQ2_ROAD_TEST_', 'VQ2_LEAK_TEST_'));
            if(empty($exist)) {
            	if($this->car->vq1_finish_time ==  '0000-00-00 00:00:00') {
					$this->car->vq1_finish_time = date('YmdHis');
            	}
				$this->car->vq2_finish_time = date('YmdHis');
				$this->car->save();
            }
		}
	}

	public function warehouseTime () {
		if($this->car->warehouse_time == '0000-00-00 00:00:00') {
			$now = date('YmdHis');
			$this->car->warehouse_time = $now;
			$this->car->fifo_time = $now;
			$this->car->save();
		}
	}

	public function distributeTime () {
		if($this->car->distribute_time == '0000-00-00 00:00:00') {
			$this->car->distribute_time = date('YmdHis');
			$this->car->save();
		}
	}

	public function generateSerialNumber ($line) {
		$series = $this->car->series;
		$snClass = "SerialNumber" . strtoupper($series) . "AR";
		if($line === 'II'){
			$snClass = "SerialNumber" . strtoupper($series) . "_2AR";
		}
		
		$curYear = DateUtil::getCurYear();
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

	public function generateSpsSerial ($line) {
		$sn = new SpsSerialAR();
		$sn->save();
		$this->car->sps_serial = $line . "-" . sprintf("%06d", $sn->id);
		$this->car->save();
	}

	public function addToPlan ($date, $planId) {
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

	public function getConfigDetail ($node) {
		$seeker = Config::create($this->car->config_id);
		return $seeker->getDetail($this->car, $node);
	}

	public function enterNode ($nodeName,$driverId = 0, $onlyOnce = false, $remark = '') {
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
			$trace->remark = $remark;
			$trace->save();

			// $onlineNodes = array(
			// 	'T11','T21','T32','C10','C21','F10','F20',
	  //       	'T11_2','T21_2','T32_2','C10_2','C21_2','F10_2','F20_2'
	  //      	);
			// //car status
			// if($nodeName !== 'WDI' && $nodeName !== 'DETECT_SHOP_LEAVE' && $nodeName !== 'DETECT_SHOP_RETURN'){
			// 	//如果已经下线，录入线上节点不改变车辆状态
			// 	if(!(in_array($nodeName, $onlineNodes) && $this->car->finish_time>'0000-00-00 00:00:00')){
			// 		$this->detectStatus($node);
			// 	}
			// }
		//}
		return array($node->name, $trace->id); 
	}

	public function enterNodeWDI ($passtime,$driverId = 0) {
		$node = Node::createByName('WDI');
		if(!$node->exist()){
			throw new Exception('不存在名字为' . $nodeName . '的节点');
		}

		$nodeId = $node->id;
		$exist = NodeTraceAR::model()->find('car_id =? AND node_id=?', array($this->car->id,$nodeId));
		if(empty($passtime)){
			$passtime = date('YmdHis');
		}
		$trace = new NodeTraceAR();
		$trace->node_id = $nodeId;
		$trace->user_id = Yii::app()->user->id;
		$trace->driver_id = $driverId;
		$trace->pass_time = $passtime;
		$trace->car_id = $this->car->id;
		$trace->car_series = $this->car->series;
		$trace->save();
	}


	public function detectStatus ($nodeName = "") {
		//TODO：可以优化
		if(empty($nodeName)) {
			$sql = "SELECT distinct node_id FROM node_trace WHERE car_id={$this->car->id}";
			$traceNodes = Yii::app()->db->createCommand($sql)->queryColumn();

			$nodeId = -1;
			if(!empty($traceNodes)) {
				$str = join(',', $traceNodes);
				$sql = "SELECT id FROM node WHERE id IN ($str) ORDER BY stage DESC";
				$nodeId = Yii::app()->db->createCommand($sql)->queryScalar();
			}		
			$node = Node::create($nodeId);
		} else {
			$node = NodeAR::model()->find("name=?", array($nodeName));
		}

		$onlineNodes = array(
				'T11','T21','T32','C10','C21','F10','F20',
	        	'T11_2','T21_2','T32_2','C10_2','C21_2','F10_2','F20_2'
	    );
		if(empty($node->main_zone) || (in_array($node->name, $onlineNodes) && $this->car->finish_time>'0000-00-00 00:00:00')){
			return;
		}
		
		$zone = $node->main_zone;
		if($node->name === 'VQ1') {
			$fault = Fault::createSeeker();
            $exist = $fault->exist($this, '未修复', array('VQ1_STATIC_TEST_'));
			if(!empty($exist)) {
				$zone = $node->slave_zone;
			}	
		}

		if($node->name == 'VQ1_2') {
			$fault = Fault::createSeeker();
            $exist = $fault->exist($this, '未修复', array('VQ1_STATIC_TEST_2_'));
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

	public function recordWarehouseReturnTrace ($nodeTraceId, $returnTo) {
		$ar = new WarehouseReturnTraceAR();
		$ar->trace_id = $nodeTraceId;
		$ar->return_to = $returnTo;
		$ar->car_id = $this->car->id;
		$ar->save();
	}

	public function leftNode ($nodeName) {
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

	public function passNode ($nodeName) {
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
	public static function countPassNode ($nodeName, $date) {
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

	public function addTraceComponents ($node, $componentCodeText) {
		$codeList = CJSON::decode($componentCodeText);
		if(empty($codeList)){
			return $codeList;
		}
		$nodeId = $node->id;
		$series = strtoupper($this->car->series);
		$ctClass = "ComponentTrace{$series}AR";
		Yii::import('application.models.AR.' .$ctClass);
		$messages = array();
		foreach($codeList as $componentId=>$code) {
			if(empty($code)) {
				continue;
			}
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
			$trace->user_display_name = Yii::app()->user->display_name;
			$trace->provider = $this->calProvider($code, $componentId);
			$trace->bar_code = $code;
			$trace->save();
		}
			
	}

	private function calProvider ($barCode, $componentId , $fullname = false) {
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

	private function getSpecialProvider ($componentId) {
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


	public function checkBarCode ($componentId, $code) {
		$configListComponent = CarConfigListAR::model()->find('config_id=? AND component_id=?',array($this->car->config_id,$componentId));
		if($configListComponent->istrace != 1) {//no need to check unique
			return;
		}
		if(empty($code)) {
			return;
		}
		$allSeries = Series::getArray();

		foreach($allSeries as $series) {
			$ctClass = "ComponentTrace{$series}AR";
        	Yii::import('application.models.AR.' .$ctClass);

			$exist = $ctClass::model()->find('bar_code=? AND status<2', array($code));
			if(!empty($exist)) {
				if($exist->car_id == $this->car->id && $exist->component_id=$componentId) {
					continue;
				}
				throw new Exception("条码" . $code . " 已经存在!");
			}
		}
	}

	//汽油机
	public function checkTraceGasolineEngine () {
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
	public function checkTraceGearBox () {
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
	public function checkTraceABS () {
		
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

	public function checkTraceComponentByName ($componentName) {
		$componentIds = $this->getComponentIds($componentName);
		return $this->checkTraceComponentByIds($componentIds, $componentName);
	}

	public function checkTraceComponentByIds ($componentIds, $componentName = '') {
		if(empty($componentIds)) {
			throw new Exception($this->vin . ' 没有零部件 ' . $componentName . ' !');
		}
		$series = strtoupper($this->car->series);
        $ctClass = "ComponentTrace{$series}AR";
        Yii::import('application.models.AR.' .$ctClass);
		$componentIdText = join(',', $componentIds);
		$exist = $ctClass::model()->find("car_id=? AND component_id IN ($componentIdText) AND status<2", array($this->car->id));
		
		if(empty($exist)) {
			throw new Exception($this->vin . ' 还没有追溯零部件 ' .$componentName.  '!');
		}
		return $exist;
	}

	public function checkTraceComponentByConfig () {
		$notGood = false;
		$series = strtoupper($this->car->series);
		$traceSeeker = ComponentTrace::createSeeker();
		$componentIds = $traceSeeker->getCarAllTrace($this->car->id, $series);

		$configSeeker = new ConfigSeeker();
		$traceList = $configSeeker->getTraceList($this->car->config_id);

		$notFound = array();
		foreach($traceList as $trace){
			if(in_array($trace['component_id'], $componentIds) || in_array($trace['replacement_id'], $componentIds) || $trace['node_id'] == 15 || $trace['node_id'] == 0) continue;
			$notFound[$trace['component_id']]['name'] = ComponentAR::model()->findByPk($trace['component_id'])->display_name;
			$notFound[$trace['component_id']]['node'] = NodeAR::model()->findByPk($trace['node_id'])->display_name;
			$notGood = true;
		}

		return array("notGood" =>$notGood, "notFound" =>$notFound);			
	}

	public function getComponentName ($componentId) {
		$sql = "SELECT display_name FROM component WHERE id=$componentId";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}

	public function getProvider($componentId) {
		$c = CarConfigListAR::model()->find('component_id = ? AND config_id = ?', array($componentId, $this->car->config_id));

		$p = ProviderAR::model()->findByPk($c->provider_id);
		
		return empty($p) ? '' : $p->name;
	}

	public function getComponentIds ($componentName) {
        $sql = "SELECT id FROM component WHERE display_name='$componentName'";
        return Yii::app()->db->createCommand($sql)->queryColumn();
    }


	//in vq2 
	public function addGasBagTraceCode ($gasBagCode) {
		if(empty($gasBagCode)) {
			return;
		}
		$node = Node::createByName('ROAD_TEST_FINISH');	
	    $ret = $this->addTraceComponents($node, '{"692":"'.$gasBagCode.'"}');	
	    return $ret;
	}

	public function recordTemperature ($temperature=0, $traceId=0, $recorderId=0){
		$ar = new AirconditionTemperatureAR();
		$ar->car_id = $this->car->id;
		$ar->temperature = $temperature;
		$ar->trace_id = $traceId;
		$ar->user_id = $recorderId;
		$ar->save();
	}

	public function getTemperatureFault () {
		$series = $this->car->series;
		$faultArray = array(
			"F0"=>array("faultId"=>"14174","componentId"=>"683","dutyDepartment"=>"0"),
			"M6"=>array("faultId"=>"14175","componentId"=>"2892","dutyDepartment"=>"0"),
			"6B"=>array("faultId"=>"14176","componentId"=>"3396","dutyDepartment"=>"0"),
			"G6"=>array("faultId"=>"18758","componentId"=>"6311","dutyDepartment"=>"0"),
		);

		return $faultArray[$series];
	}

	public function getAllTrace ($node) {
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
		$sql = "SELECT node_trace_id AS trace_id, create_time,modify_time,updator,component_name, fault_mode,status FROM VQ1_STATIC_TEST_$series WHERE car_id={$this->car->id}";
		$vq1s = Yii::app()->db->createCommand($sql)->queryAll();

		$sql = "SELECT node_trace_id AS trace_id, create_time,modify_time,updator,component_name, fault_mode,status FROM VQ2_ROAD_TEST_$series WHERE car_id={$this->car->id}";
        $roads = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "SELECT node_trace_id AS trace_id, create_time,modify_time,updator,component_name, fault_mode,status FROM VQ2_LEAK_TEST_$series WHERE car_id={$this->car->id}";
        $leaks = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "SELECT node_trace_id AS trace_id, create_time,modify_time,updator,component_name, fault_mode,status FROM VQ3_FACADE_TEST_$series WHERE car_id={$this->car->id}";
        $vq3s = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "SELECT node_trace_id AS trace_id, create_time,modify_time,updator,component_name, fault_mode,status FROM VQ1_STATIC_TEST_2_$series WHERE car_id={$this->car->id}";
        $vq1_2s = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "SELECT trace_id,create_time,temperature,user_id AS updator, '' AS component_name, '' AS fault_mode, '-' AS `status`,'-' AS modify_time FROM aircondition_temperature WHERE car_id={$this->car->id}";
        $temperatures = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "SELECT node_trace_id AS trace_id, replace_time AS create_time, user_id AS updator, fault_component_name AS component_name, fault_mode, '换件' AS `status`, '-' AS modify_time FROM spare_replacement WHERE car_id={$this->car->id} GROUP BY node_trace_id";
        $replacements = Yii::app()->db->createCommand($sql)->queryAll();

		$datas = array();
		$processed = array();
		foreach($traces as $trace) {
			$name = $nodeInfos[$trace['node_id']];
			$values = array();
			// if(!empty($processed[$trace['node_id']])) {
			// 	continue;
			// }
			$processed[$trace['node_id']] = $trace['node_id'];
			switch($trace['node_id']) {
				case 10:
					foreach($vq1s as $vq1){
						if(strtotime($vq1['create_time']) <= (strtotime($trace['pass_time']) + 2) && strtotime($vq1['create_time']) >= (strtotime($trace['pass_time']) - 2)){
							$values[] = $vq1;
						}
					}
					break;
				case 14:
					break;
				case 15:
					foreach($roads as $road){
						if(strtotime($road['create_time']) <= (strtotime($trace['pass_time']) + 2) && strtotime($road['create_time']) >= (strtotime($trace['pass_time']) - 2)){
							$values[] = $road;
						}
					}
					foreach($temperatures as $temperature) {
						if($temperature['trace_id'] == $trace['id'] || (strtotime($temperature['create_time']) <= (strtotime($trace['pass_time']) + 2) && strtotime($temperature['create_time']) >= (strtotime($trace['pass_time']) - 2))){
							$values[] = $temperature;
						}
					}
					break;
				case 16:
					foreach($leaks as $leak){
						if(strtotime($leak['create_time']) <= (strtotime($trace['pass_time']) + 2) && strtotime($leak['create_time']) >= (strtotime($trace['pass_time']) - 2)){
							$values[] = $leak;
						}
					}
                    break;
				case 17:
					foreach($vq3s as $vq3){
						if(strtotime($vq3['create_time']) <= (strtotime($trace['pass_time']) + 2) && strtotime($vq3['create_time']) >= (strtotime($trace['pass_time']) - 2)){
							$values[] = $vq3;
						}
					}
                    break;
                case 209:
					foreach($vq1_2s as $vq1_2){
						if(strtotime($vq1_2['create_time']) <= (strtotime($trace['pass_time']) + 2) && strtotime($vq1_2['create_time']) >= (strtotime($trace['pass_time']) - 2)){
							$values[] = $vq1_2;
						}
					}
					break;
				case 100:
					foreach($replacements as $replacement){
						if($replacement['trace_id'] == $trace['id']){
							$values[] = $replacement;
						}
					}
					break;    
				default:
					;
			} 
			if(empty($values)) {
				$trace['create_time'] = $trace['pass_time'];
				$trace['node_name'] = $name;
				$trace['fault'] = '-';
				if(!empty($trace['remark'])){
					$trace['fault'] = $trace['remark'];
				}
				$trace['fault_status'] = '-';
				if(!empty($trace['driver_id'])){
					$trace['user_name'] = !empty($userInfos[$trace['driver_id']]) ? $userInfos[$trace['driver_id']] : "-";
				} else {
					$trace['user_name'] = !empty($userInfos[$trace['user_id']]) ? $userInfos[$trace['user_id']] : "-";
				}
				$trace['modify_time'] = '-';
				$datas[] = $trace;
			} else {
				foreach($values as $value) {
					$fault = !empty($value['temperature']) ? "空调温度：" . $value['temperature'] . "℃" : $value['component_name'] . $value['fault_mode'];
					$datas[] = array(
						'id' => $value['trace_id'],
						'create_time' => $value['create_time'],
						'node_name' => $name,
						'fault' => $fault,
						'fault_status' => $value['status'],
						'user_name' => $userInfos[$value['updator']],
						'modify_time' => $value['modify_time']
					);
				}
			}
		}
		if(!empty($datas)) {
			$sortDatas = $this->multi_array_sort($datas, 'create_time');
		} else {
			$sortDatas = array();
		}
		// return $datas;
		return $sortDatas;
	}

	public function getFaults ($node) {
		$seeker = Fault::createSeeker();
		$faults = $seeker->queryCarFaults($this->car->id,$node,$this->car->series);
		return $faults;
	}

	public function multi_array_sort ($multi_array,$sort_key,$sort=SORT_ASC) {  
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


	public function moveToArea ($area) {
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

	public function moveToLane ($lane) {
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

	public function generateConfigData () {
        $barcodeGenerator = BarCodeGenerator::create("BCGcode39");
        $vinBarCodePath = "tmp/" .$this->car->vin .".jpeg";
        $barcodeGenerator->generate($this->car->vin,'./' .$vinBarCodePath);
		$config = CarConfigAR::model()->findByPk($this->car->config_id);
		$configName = empty($config) ? "" : $config->name;
		$sideGlass = empty($config) ? "" : $config->side_glass;
		$carType = CarTypeMapAR::model()->find('car_type=?', array($this->car->type));
        $carModel = $carType->car_model;

		$types = array('front','back','front2','back2');
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
            'carModel' => $carModel,
            'sideGlass' => $sideGlass,
			'config' => $configName,
            'remark'  => $this->car->remark,
            'vinCode' => $this->car->vin,
            'coldResistant' => $this->car->cold_resistant,
			'frontImage' => $images['front'],
			'backImage' => $images['back'],
			'front2Image' => $images['front2'],
			'back2Image' => $images['back2'],
		);
		return $ret;
	}
	
	public function addSubConfig ($types = array('subInstrument','subEngine','subFrontAxle','subRearAxle','subTyre')) {
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

	public function addSpsQueue ($spsPoints = array('S1','S2','S3','frontBumper','rearBumper'), $line="I") {
		foreach($spsPoints as $point) {
			$spsQueueOne = SpsQueueAR::model()->find('car_id=? AND point=?', array($this->car->id,$point));

			if(empty($spsQueueOne)) {
				$spsQueueOne = new SpsQueueAR();
				$spsQueueOne->line = $line;
				$spsQueueOne->car_id = $this->car->id;
				$spsQueueOne->series = $this->car->series;
				$spsQueueOne->vin = $this->car->vin;
				$spsQueueOne->point = $point;
				$spsQueueOne->status = 0;
				$spsQueueOne->queue_time = date('Y-m-d H:i:s'); 
				$spsQueueOne->save();
			}
		}
	}

	public function addVinLaserQueue () {
		$queue = VinLaserQueueAR::model()->find('vin=?', array($this->car->vin));
		$statusT01 = 0; 
		$statusEngine = 0;
		if(($this->car->special_property==1 && $this->car->assembly_line=="I")){
			$statusT01 = 1; 
			$statusEngine = 1;
		} 
		if(empty($queue)) {
			$queue = new VinLaserQueueAR();
			$queue->vin = $this->car->vin;
			$queue->line = $this->car->assembly_line;
			$queue->assembly_time = date('Y-m-d H:i:s'); 
			$queue->manual = 0;
			$queue->seat_t01 = $statusT01;
			$queue->seat_t17 = 0;
			$queue->seat_engine = $statusEngine;
			$queue->save();
		}
	}
	
	//force : true force to print
	public function generateSubConfigData ($type='subInstrument', $force = false) {
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

	public function generateSpsData ($point='S1', $force = false) {
		$spsQueueOne = SpsQueueAR::model()->find('car_id=? AND point=?', array($this->car->id,$point));
		if(empty($spsQueueOne)) {//suit for those cars has passed t0 
			throw new Exception("该车不在列队中");
		}

		if(!$force) {
			if($spsQueueOne->status == 2) {//forbid print
				$info = array(1=>"已经",2=>"禁止");
				throw new Exception("该车{$info[$spsQueueOne->status]}已打印 {$point} 分拣单");
			}
		}		

        $barcodeGenerator = BarCodeGenerator::create("BCGcode39");
        $vinBarCodePath = "tmp/" .$this->car->vin .".jpeg";
        $barcodeGenerator->generate($this->car->vin,'./' .$vinBarCodePath);
		$config = CarConfigAR::model()->findByPk($this->car->config_id);

		// $images = array();
		// if(!empty($config)) {
		// 	$path = "/home/work/bms/web/bms/configImage/" . $config->id;
		// 	$name = $type . '.jpg';
		// 	$fileName = $path . '/' . $name;
		// 	$image = '';
		// 	if(file_exists($fileName)) {
		// 		$image = '/bms/configImage/' .$config->id . '/' . $name;
		// 	}
		// }

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
			// 'image' => $image,
		);
		

		if(!empty($spsQueueOne)) {
			$spsQueueOne->status = 1;
			$spsQueueOne->check_time = date("YmdHis");
			$spsQueueOne->save();
		}

		return $ret;
	}

	public function generateCheckTraceData () {
		$barcodeGenerator = BarCodeGenerator::create("BCGcode39");
        $vinBarCodePath = "tmp/" .$this->car->vin .".jpeg";
        $barcodeGenerator->generate($this->car->vin,'./' .$vinBarCodePath);
        $config = CarConfigAR::model()->findByPk($this->car->config_id);
        $configName = $config->name;
        $typeName = $this->cutCarType($this->car->type);
        $coldResistant = $this->car->cold_resistant==1? '耐寒' : '非耐寒';

		$engineTrace = $this->checkTraceGasolineEngine(); 
		if($this->car->series === 'F0'){
			// $this->checkTraceGearBox();
			$absTrace = $this->checkTraceABS();
				if(!empty($absTrace)){
					$barCode = $absTrace->bar_code;
                	$abs = $this->getAbsInfo($barCode);
			}
		}
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

	public function generateInfoPaperData () {
		$barcodeGenerator = BarCodeGenerator::create("BCGcode128");
        $vinBarCodePath = "tmp/" .$this->car->vin .".jpeg";
        $barcodeGenerator->generate($this->car->vin,'./' .$vinBarCodePath);
        $config = CarConfigAR::model()->findByPk($this->car->config_id);
        $configName = $config->name;
        $carType = CarTypeMapAR::model()->find('car_type=?', array($this->car->type));
        $carModel = $carType->car_model;
        $carTypeShort = $carType->short_name;
        $coldResistant = $this->car->cold_resistant==1? '/耐寒' : '';
		$series = Series::getName($this->car->series);

        $ret = array(
			'vinBarCode' => "/bms/" .$vinBarCodePath,
			'typeConfig' =>  $configName . $coldResistant,
			'carModel' => $carModel,
			'series' => $series,
			'serialNumber' => $this->car->serial_number,
			'line' => $this->car->assembly_line,
			'date' => date('Ymd'),
			'color' => $this->car->color,
			'remark'  => $this->car->remark,
			'vinCode' => $this->car->vin,
			'typeShort' => $carModel . '(' . $carTypeShort . ')',
		);

		return $ret;
	}
		
	//added by wujun
	public function matchOrder ($date) {
		$series = $this->car->series;
		$carType = $this->car->type;
		$config = $this->car->config_id;
		$color = $this->car->color;
		$coldResistant = $this->car->cold_resistant;
		//$carYear = $this->carYear;

		//不符合LC0规则不匹配订单
		list($LC0TypeArray,$LC0Type) = $this->getLC0Type();
		list($LC0ConfigArray,$LCOConfig) = $this->getLC0Config();
		// if(!(in_array($this->car->type, self::$LC0_TYPE_ARRAY) || substr($this->car->vin, 0,3)=='LGX' || $this->car->special_property==1)){
		if(!(in_array($this->car->type, $LC0TypeArray) || substr($this->car->vin, 0,3)=='LGX' || $this->car->special_property==1 || in_array($this->car->config_id, $LC0ConfigArray) )){
			return array(false, array());
		}
		
		$order = new Order;            
        list($success, $data) = $order->match($series, $carType, $config, $color, $coldResistant, $date);
        if($success) {
            $rowWDI = WarehouseAR::model()->findByPk(1);
			$rowWDI->saveCounters(array('quantity'=>1));
			if($this->car->warehouse_id > 0){
				$row = WarehouseAR::model()->findByPk($this->car->warehouse_id);
				$row->saveCounters(array('quantity'=>-1));
			}

            $this->car->order_id = $data['orderId'];
            $this->car->old_wh_id = $this->car->warehouse_id;
            $this->car->warehouse_id = $rowWDI->id;
            $this->car->status = 'WDI';
            $this->car->area = 'WDI';
            $this->car->standby_time = date("YmdHis");
            $this->car->save();

            $data['area'] = $rowWDI->area;
            $data['row'] = $rowWDI->row;
            $data['vin'] = $this->car->vin;
            $data['type'] = $this->car->type;
            $data['color'] = $this->car->color;
            $data['cold_resistant'] = ($this->car->cold_resistant == 1)? '耐寒':'非耐寒';
        }

        return array($success, $data);
	}

	public function warehouseReturn ($goTo, $remark, $driverId = 0) {
		//释放订单
		$this->releaseOrder();

		if($goTo === "成品库"){
			$status = "成品库";
			if($this->car->warehouse_id > 1){
				$rowNow = WarehouseAR::model()->findByPk($this->car->warehouse_id)->row;
				throw new Exception ('此车状态为成品库_'. $rowNow .'，不可退回成品库，如需重新分配库位，请操作<重新分配库位>');
			} else if($this->car->warehouse_id == 1){
				$rowWDI = WarehouseAR::model()->findByPk(1);
				$rowWDI->saveCounters(array('quantity'=>-1));
			}
			$warehouse = new Warehouse;
            $data = $warehouse->checkin($this->car->vin);
            $this->car->warehouse_id = $data['warehouse_id'];
            $this->car->area = $data['area'];

            $now = date("YmdHis");
            $this->car->warehouse_time = $now;
            $this->car->fifo_time = $now;
            $this->car->save();

		} else {
			$status = $goTo . "退库" ;
			if($this->car->warehouse_id > 1){
				$this->car->old_wh_id = $this->car->warehouse_id;
			}
			if($this->car->warehouse_id >= 1){
				$row = WarehouseAR::model()->findByPk($this->car->warehouse_id);
				$row->saveCounters(array('quantity'=>-1));
			}
			$this->car->warehouse_id = 0;
			$this->car->area = '';
			$this->car->warehouse_time = "0000-00-00 00:00:00";
			$this->car->fifo_time = "0000-00-00 00:00:00";
			$this->car->vq3_return_time = "0000-00-00 00:00:00";
			if($goTo == "VQ1") {
				$this->car->vq1_return_time = date("YmdHis");
				$this->car->vq1_finish_time = "0000-00-00 00:00:00";
				$this->car->vq2_return_time = "0000-00-00 00:00:00";
				$this->car->vq2_finish_time = "0000-00-00 00:00:00";
			} else if($goTo == "VQ2") {
				$this->car->vq2_return_time = date("YmdHis");
				$this->car->vq2_finish_time = "0000-00-00 00:00:00";
			} else if($goTo == "VQ3") {
				$this->car->vq3_return_time = date("YmdHis");
			}
		}

		$onlyOnce=false;
		list($nodeName, $traceId) = $this->enterNode("WAREHOUSE_RETURN", $driverId, $onlyOnce, $remark);
		$this->recordWarehouseReturnTrace($traceId, $goTo);
		$this->car->status = $status;

		$this->car->save();

		$ret = $this->car->getAttributes();
		$ret['row'] = '';
		if(!empty($ret['warehouse_id'])){
			$ret['row'] = WarehouseAR::model()->findByPk($ret['warehouse_id'])->row;
		}
		return $ret;
	}

	public function checkAlreadyOnline () {
		if($this->car->assembly_time > '0000-00-00 00:00:00'){
			throw new Exception($this->car->vin . '已上线');
		}
	}

	public function checkAlreadyOut () {
		if($this->car->distribute_time > '0000-00-00 00:00:00'){
			throw new Exception($this->car->vin . '已出库');
		}
	}

	public function checkAlreadyWarehouse () {
		if($this->car->warehouse_time > '0000-00-00 00:00:00'){
			throw new Exception($this->car->vin . '已入库');
		}
	}
	
	public function releaseOrder () {
		
		if($this->car->order_id>0){
			$order = OrderAR::model()->findByPk($this->car->order_id);
			$order->saveCounters(array('hold'=>-1));
			$standby_date = DateUtil::getCurDate();
			$order->saveAttributes(array("standby_date"=>"$standby_date"));
			if($this->car->distribute_time>"0000-00-00 00:00:00"){
				$order->saveCounters(array('count'=>-1));
				//优先级置顶
				$highers = OrderAR::model()->findAll('priority<? AND standby_date=? AND status=1', array($order->priority, $order->standby_date));
				if(!empty($highers)) {
					foreach($highers as $higher) {
						$higher->priority = $higher->priority + 1;
						$higher->save();
					}
				}
				$order->saveAttributes(array("priority"=>0));
				
				if($order->status == 2){
					$order->saveAttributes(array("status"=>1));
				}
			}
			$this->car->order_id = 0;
			$this->car->lane_id = 0;
			$this->car->distributor_name='';
			$this->car->distributor_code='';
			$this->car->distribute_time = '0000-00-00 00:00:00';
			$this->car->standby_time = '0000-00-00 00:00:00';
			$this->car->save();
		}
	}
	
	public function throwTestlineCarInfo () {

		//好像有点太过程化了，找时间优化
		$vin = $this->car->vin;
		$series = $this->car->series;
		$color = $this->car->color;
		$carId = $this->car->id;
		
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
				SET car_id=$carId, vin='{$vin}', series='{$series}', series_name='{$seriesName}', car_model='{$carModel}', `car_type`='{$carType}', engine_type = '{$engineType}', engine_code='{$engineCode}', color='{$color}'";
		$updatesql = "UPDATE testline_car_info
						SET car_id=$carId, series='{$series}', series_name='{$seriesName}', car_model='{$carModel}', `car_type`='{$carType}', engine_type = '{$engineType}', engine_code='{$engineCode}', color='{$color}' 
						WHERE vin='{$vin}'";
		$existsql = "SELECT vin FROM testline_car_info WHERE vin='{$vin}'";				
		
		$exist=Yii::app()->db->createCommand($existsql)->execute();
		if(empty($exist)){
			Yii::app()->db->createCommand($insertsql)->execute();
		}else{
			Yii::app()->db->createCommand($updatesql)->execute();
		}
		
	}
	
	public function checkTestLinePassed () {
		if($this->car->config_id == 45) {
			return;
		}
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

	public function isTestLinePassed () {
		$flag = false;
		$vin = $this->car->vin;
		$sql = "SELECT ToeFlag_F, LM_Flag, RM_Flag, RL_Flag, LL_Flag, Light_Flag, Slide_Flag, BrakeResistanceFlag_F, BrakeFlag_F, BrakeResistanceFlag_R, BrakeFlag_R, BrakeSum_Flag, ParkSum_Flag, Brake_Flag, Speed_Flag, GasHigh_Flag, GasLow_Flag, Final_Flag 
		FROM Summary WHERE vin='$vin'";
			
		$ret=Yii::app()->dbTest->createCommand($sql)->queryRow();
		if($ret['Final_Flag'] === 'T') 
			$flag = true;
		
		return $flag;
	}

	public function throwInspectionSheetData ($markOnly=false) {
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

		$reportPrinted = '待打印';
		if($markOnly){
			$reportPrinted = '已打印';
		}

		$insertsql = "INSERT INTO ShopPrint
				SET vin='{$vin}', Order_ID='{$cData['order_number']}', VenName='{$cData['distributor_name']}', Clime='{$cData['country']}', `Path`='{$cData['lane_name']}', Series='{$series}', Type='{$carType}', Color='{$color}', EngineType='{$engineType}', engineCode='{$engineCode}', ReportPrinted='{$reportPrinted}'";
		// $updatesql = "UPDATE ShopPrint
		// 				SET Order_ID='{$cData['order_number']}', VenName='{$cData['distributor_name']}', Clime='{$cData['country']}', `Path`='{$cData['lane_name']}', Series='{$series}', Type='{$carType}', Color='{$color}', EngineType='{$engineType}', engineCode='{$engineCode}'
		// 				WHERE vin='{$vin}'";
		$deletesql = "DELETE FROM ShopPrint WHERE vin='{$vin}'";
		$existsql = "SELECT vin,Order_ID FROM ShopPrint WHERE vin='{$vin}'";				
		
		$exist=Yii::app()->dbTest->createCommand($existsql)->execute();
		if(!empty($exist)){
			Yii::app()->dbTest->createCommand($deletesql)->execute();
		}
		
		$ret = Yii::app()->dbTest->createCommand($insertsql)->execute();

		return $ret;
	}

	public function throwInspectionSheetDataExport ($specialOrder) {
		$carId = $this->car->id;
		$vin = $this->car->vin;
		$config = $this->car->config_id;
		$series = $this->car->series;
		$color = $this->car->color;

		$carType = $this->car->type;
		$carType = str_replace("（", "(",$carType);
		$carType = str_replace("）", ")",$carType);

		$engineTrace = $this->checkTraceGasolineEngine();
		$engine = CarEngineAR::model()->find('engine_component_id=?', array($engineTrace->component_id));
		$engineType = $engine->engine_type;
		$engineCode = substr($engineTrace->bar_code, -$engine->code_digit);

		$cData = $this->getCertificateDataExport($carId);
		$country = $cData['export_country']; 
		$clime = $cData['mark_clime']; 
		$laneName = 'A';
		$reportPrinted = '待打印';

		$insertsql = "INSERT INTO ShopPrint
				SET vin='{$vin}', Order_ID='{$specialOrder}', VenName='{$country}', Clime='{$clime}', `Path`='{$laneName}', Series='{$series}', Type='{$carType}', Color='{$color}', EngineType='{$engineType}', engineCode='{$engineCode}', ReportPrinted='{$reportPrinted}' ";
		$deletesql = "DELETE FROM ShopPrint WHERE vin='{$vin}'";
		$existsql = "SELECT vin,Order_ID,ReportPrinted FROM ShopPrint WHERE vin='{$vin}'";				
		
		$exist=Yii::app()->dbTest->createCommand($existsql)->queryRow();
		if(!empty($exist)){
			if($exist['ReportPrinted'] == '待打印'){
				Yii::app()->dbTest->createCommand($deletesql)->execute();
			} else {
				$ret = 0;
				return $ret;
			}
		}
		
		$ret = Yii::app()->dbTest->createCommand($insertsql)->execute();

		return $ret;
	}

	public function throwMarkPrintData () {
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

		$insertsql = "INSERT INTO MarkPrint
							SET vin='{$vin}', Order_ID='{$cData['order_number']}', VenName='{$cData['distributor_name']}', Clime='{$cData['country']}', `Path`='{$cData['lane_name']}', Series='{$series}', Type='{$carType}', Color='{$color}', EngineType='{$engineType}', engineCode='{$engineCode}' ";			
		$deletesql = "DELETE FROM MarkPrint WHERE vin='{$vin}'";
		$existsql = "SELECT vin,Order_ID FROM MarkPrint WHERE vin='{$vin}'";				
		
		$exist=Yii::app()->dbTest->createCommand($existsql)->execute();
		if(!empty($exist)){
			Yii::app()->dbTest->createCommand($deletesql)->execute();
		}
		
		Yii::app()->dbTest->createCommand($insertsql)->execute();
		
	}

	public function getCertificateData ($carId) {
		$sql = "SELECT car_model, order_number, country, distributor_name, order_detail_id, order_nature, certificate_note, assisted_steering, tyre, lane_name, sell_color, sell_car_type
				FROM view_certificate
				WHERE car_id = $carId";
		$data = Yii::app()->db->createCommand($sql)->queryRow();
		if(!empty($data)) {
			return $data;
		}
	}

	public function getCertificateDataExport ($carId) {
		$sql = "SELECT car_model, certificate_note, assisted_steering, tyre, export_country, mark_clime
				FROM view_certificate_export
				WHERE car_id = $carId";
		$data = Yii::app()->db->createCommand($sql)->queryRow();
		if(!empty($data)) {
			return $data;
		}
	}

	public function throwCertificateData ($outDate, $computerName= '10.23.1.67', $country='国内', $district='比亚迪长沙') {
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
			$outDate = date("Y-m-d H:i:s");
		} 

		// $sql = "SELECT car_model, order_number, country, distributor_name, order_detail_id, order_nature, certificate_note, assisted_steering, tyre, lane_name, sell_color, sell_car_type
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
		if(($this->car->series == 'F0')){
			// $gearboxTrace = $this->checkTraceGearBox() ;
			// if(!empty($gearboxTrace)){
			// 	$gearboxCode = $gearboxTrace->bar_code;
			// }

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
                		   VALUES('{$cData['order_detail_id']}','{$vin}', '{$cData['car_model']}', '{$cData['sell_color']}', '{$engineCode}', '{$cData['certificate_note']}', '{$cData['order_number']}', '{$district}', '{$cData['country']}', '{$cData['order_nature']}', '{$computerName}', '{$gearboxCode}', '{$outDate}', '{$cData['assisted_steering']}', '{$carType}', '{$cData['tyre']}', '{$cData['sell_car_type']}')";
		$updatesql = "UPDATE Print_Table
						SET DGMXID='{$cData['order_detail_id']}', CLXH='{$cData['car_model']}', CLYS='{$cData['sell_color']}', FDJH='{$engineCode}', NOTE='{$cData['certificate_note']}', DGDH='{$cData['order_number']}', SCD='{$district}', CLXZ='{$cData['country']}', DDXZ='{$cData['order_nature']}',  EMP='{$computerName}', AUTO_GEARBOX='{$gearboxCode}', AUTO_DATE='{$outDate}', Zxzlxs='{$cData['assisted_steering']}', Clkx='{$carType}', Ltgg='{$cData['tyre']}', WZCLXH='{$cData['sell_car_type']}'
						WHERE VIN='{$vin}'";
		$deletesql = "DELETE FROM Print_Table WHERE VIN='{$vin}'";

		//insert
		$tdsSever = Yii::app()->params['tds_HGZ'];
        $tdsDB = Yii::app()->params['tds_dbname_HGZ_DATABASE'];
        $tdsUser = Yii::app()->params['tds_HGZ_username'];
        $tdsPwd = Yii::app()->params['tds_HGZ_password'];  

        if($this->existInHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $vin)){
	   		// $this->wrightHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $updatesql);
	   		$this->wrightHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $deletesql);
        }
      //   else{
	   		// $this->wrightHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $insertsql);
      //   }   
	   	$ret = $this->wrightHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $insertsql);

	   	return $ret;
	}

	public function throwCertificateDataExport ($specialOrder, $forceThrow=false, $outDate='',$computerName= '10.23.1.67', $district='比亚迪长沙') {
		$carId = $this->car->id;
		$vin = $this->car->vin;
		$config = $this->car->config_id;
		$coldResistant = $this->car->cold_resistant;

		if(empty($outDate)){
			$outDate = date("Y-m-d H:i:s");
		} 

		$cData = $this->getCertificateDataExport($carId);
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
		if(($this->car->series == 'F0')){
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
		$carModel = $cData['car_model'];
		$certificatenote = $cData['certificate_note'];
		$assistedStecring = $cData['assisted_steering'];
		$tyre = $cData['tyre'];
		
		if(!empty($cData['export_country'])){
			$country = $cData['export_country'];
		} else {
			$country = iconv('UTF-8', 'GB2312', '出口');
		}
		
		$orderDetailId = 0;
		$sellColor = $color;
		$sellCarType = '';

		$insertsql = "INSERT INTO Print_Table(DGMXID,VIN,CLXH,CLYS,FDJH,NOTE,DGDH,SCD,CLXZ,DDXZ,EMP,AUTO_GEARBOX,AUTO_DATE,Zxzlxs,Clkx,Ltgg,WZCLXH) 
                		   VALUES('{$orderDetailId}','{$vin}', '{$carModel}', '{$sellColor}', '{$engineCode}', '{$certificatenote}', '{$specialOrder}', '{$district}', '{$country}', '{$sellCarType}', '{$computerName}', '{$gearboxCode}', '{$outDate}', '{$assistedStecring}', '{$carType}', '{$tyre}', '{$sellCarType}')";
        $deletesql = "DELETE FROM Print_Table WHERE VIN='{$vin}'";

		//insert
		$tdsSever = Yii::app()->params['tds_HGZ'];
        $tdsDB = Yii::app()->params['tds_dbname_HGZ_DATABASE'];
        $tdsUser = Yii::app()->params['tds_HGZ_username'];
        $tdsPwd = Yii::app()->params['tds_HGZ_password'];  

        $exist = $this->existInHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $vin);
        if($exist){
        	if($forceThrow){
		   		$this->wrightHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $deletesql);
			   	$ret = $this->wrightHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $insertsql);
        	} else {
        		$ret = false;
        	}
        } else {
		   	$ret = $this->wrightHGZ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $insertsql);
        }

	   	return $ret;
	}

	public function existInHGZ ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $vin) {
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

	public function wrightHGZ ($tdsDB, $tdsSever, $tdsUser, $tdsPwd, $sql) {
   
        //connect
        $mssql=mssql_connect($tdsSever, $tdsUser, $tdsPwd);
        if(empty($mssql)) {
            throw new Exception("cannot connet to sqlserver $tdsSever, $tdsUser ");
        }
        mssql_select_db($tdsDB ,$mssql);
        
        //execute insert
        $ret = mssql_query($sql);
        
        //disconnect
        mssql_close($mssql);

        return $ret;
	}

	public function getAbsInfo ($barCode) {
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
		if(array_key_exists($providerCode,$type)){
			return array('type' => $type[$providerCode], 'provider' => $provider[$providerCode]);
		} else {
			throw new Exception('无ABS厂家信息,请联系AMS信息管理员确认ABS条码信息');
		}

	}

	public function throwVinAssembly ($vin, $point, $shift='总装1线A班', $time='') {
		ini_set('default_socket_timeout', 10);
		try{
			$client = @new SoapClient(Yii::app()->params['ams2vin_assembly']);
			$params = array(
				'Vincode'=>$vin, 
				'Work'=>$point, 
				'Team'=>$shift
			);
			if(!empty($time)){
				$params['Date'] = $time;
			}
			$result = (array)$client->Assembly($params);
		} catch(Exception $e) {
			$result = $e->getMessage();
		}

		return $result;
	}

	public function throwVinStoreIn ($vin, $row, $driverName='', $inTime='') {
		
		ini_set('default_socket_timeout', 10);
		try{
			$client = @new SoapClient(Yii::app()->params['ams2vin_store_in']);
			$params = array(
				'Vincode'=>$vin, 
				'Area'=>$row, 
				'EmpName'=>$driverName
			);
			if(!empty($inTime)){
				$params['Date'] = $inTime;
			}
			$result = $client->StoreIn($params);
		} catch(Exception $e) {
			$result = $e->getMessage();
		}

		return $result;
	}

	public function throwVinStoreOut ($vin, $lane, $orderNumber, $orderDetailId, $distributorName, $engineCode, $outTime='') {
		
		ini_set('default_socket_timeout', 10);
		try{
			$client = @new SoapClient(Yii::app()->params['ams2vin_store_out']);
			$params = array(
				'Vincode'=>$vin, 
				'Area'=>$lane, 
				'Order'=>$orderNumber,
				'OrderID'=>$orderDetailId,
				'VenName'=>$distributorName,
				'AutoEngine'=>$engineCode
			);
			if(!empty($outTime)){
				$params['Date'] = $outTime;
			}
			$result = $client->StoreOut($params);
		} catch(Exception $e) {
			$result = $e->getMessage();
		}

		return $result;
	}


	/**
	 * @return array
	 *   Result:bool, 有无记录
	 *	 TestState:string, 0:有记录未测试，1:有记录测试失败，2：有记录测试成功，“XXXX”: 无记录具体的消息字符串
	 */
	public function getIRemoteTestResult () {
		
		$vin = $this->car->vin;
		$client = new SoapClient(Yii::app()->params['IRemote_test_result']);
		$params = array(
			'vin'=>$vin, 
		);
		$result = $client -> GetIRemoteTestResult($params);

		$ret = $result->GetIRemoteTestResultResult;
		if($ret->Result){
			$testResult = 1;
			$testState = $ret->TestState;
			$msg="";
		} else {
			$testResult = 0;
			$testState = 0;
			$msg = $ret->Msg;
		}
		$this->saveIRemoteResult($testResult,$testState,$msg);

		return $result->GetIRemoteTestResultResult;
	}

	public function saveIRemoteResult ($testResult=0, $testState=0, $msg="") {
		$carId=$this->car->id;
		$ar = new IRemoteResultAR();
		$ar->car_id = $carId;
		$ar->Result = $testResult;
		$ar->TestState = $testState;
		$ar->Msg = $msg;
		$ar->user_id = Yii::app()->user->id;
		$ar->create_time = date("YmdHis");
		$ar->save();
	}

	public function getWarehouseLabel () {
		$data = $this->car->getAttributes();
		$data['row'] = '---';
		$data['driver_name'] = '';
        $data['order_number'] = '-------------------';
		$data['lane'] = '--';
		$data['activate_time'] = '----';
		if(!empty($data['order_id'])){
			$order = OrderAR::model()->findByPk($data['order_id']);
			$data['order_number'] = $order->order_number;
			$data['distributor_name'] = $order->distributor_name;
			if(!empty($order->lane_id)){
				$data['lane'] = LaneAR::model()->findByPk($order->lane_id)->name;
			}
			$data['activate_time'] = $order->activate_time;
		}
		if($data['warehouse_id'] == 1){
			$trace = NodeTraceAR::model()->find('car_id =? AND node_id=?', array($this->car->id,96));
			if(!empty($trace)){
				$driverId = $trace->driver_id;
				if(!empty($driverId)) {$data['driver_name'] = '-'. User::model()->findByPk($driverId)->display_name;}
			}
			if(!empty($data['old_wh_id'])){
				$data['row'] = WarehouseAR::model()->findByPk($data['old_wh_id'])->row;
			}else{
				$data['row'] = 'WDI';
			}
		} else if($data['warehouse_id'] > 1){
			$data['row'] = WarehouseAR::model()->findByPk($data['warehouse_id'])->row;
		}

		return $data;
	}

	public function validateVin () {
		$ret = $VinManager = VinManager::validateDigit9($this->car->vin);
		return $ret;
	}

	public function mainInfo () {
		$data = $this->car->getAttributes();
		$data['cold'] = empty($data['cold_resistant']) ? "非耐寒" : "耐寒";
		$carType = CarTypeMapAR::model()->find("car_type=?", array($data['type']));
		$carModel = !empty($carType) ? $carType->car_model : $this->cutCarType($data['type']);
		$data['type_config'] = $data['type'];
		$data['type_order_config'] = $data['type'];
		if(!empty($data['config_id'])){
			$config = CarConfigAR::model()->findByPk($data['config_id']);
			$orderConfig = OrderConfigAR::model()->findByPk($config->order_config_id);
			$data['config_name'] = $config->name;
			$data['order_config_name'] = $orderConfig->name;
			$data['type_config'] = $carModel . "/" . $config->name;
			$data['type_order_config'] = $carModel . "/" . $orderConfig->name;
		}
		$data['row'] = empty($data['warehouse_id']) ? "" : WarehouseAR::model()->findByPk($data['warehouse_id'])->row;
		$data['lane'] = empty($data['lane_id']) ? "" : LaneAR::model()->findByPk($data['lane_id'])->name;

		return $data;
	}

	public function replaceSpares ($spares, $traceId) {
		$spares = is_array($spares) ? $spares : CJSON::decode($spares);
		foreach($spares as $spare) {
			$replacementAr = new SpareReplacementAR();
			$replacementAr->car_id = $this->car->id;
			$replacementAr->series = $spare['series'];
			$replacementAr->assembly_line = $spare['line'];
			$replacementAr->node_trace_id = $traceId;
			$replacementAr->component_id = $spare['componentId'];
			$replacementAr->duty_area = $spare['dutyArea'];
			$replacementAr->fault_id = $spare['faultId'];
			$replacementAr->fault_component_name = $spare['faultComponentName'];
			$replacementAr->fault_mode = $spare['faultMode'];
			$replacementAr->provider_id = $spare['providerId'];
			$replacementAr->duty_department_id = $spare['dutyDepartmentId'];
			$replacementAr->is_collateral = $spare['isCollateral'];
			$replacementAr->treatment = empty($spare['isScrap']) ? "返修" : "报废";
			$replacementAr->unit_price = $spare['unitPrice'];
			$replacementAr->quantity = $spare['quantity'];
			$replacementAr->bar_code = $spare['barCode'];
			$replacementAr->replace_time = date("YmdHis");
			$replacementAr->handler = $spare['handler'];
			$replacementAr->user_id = Yii::app()->user->id;
			$replacementAr->save();

			if(!empty($spare['barCode'])){
				$series = strtoupper($this->car->series);
				$ctClass = "ComponentTrace{$series}AR";
				Yii::import('application.models.AR.' .$ctClass);
				$oldTraces = $ctClass::model()->findAll('car_id=? AND component_id=?', array($this->car->id, $spare['componentId']));
				foreach($oldTraces as $oldTrace) {
					if(!empty($oldTrace)) {
						$oldTrace->status = 2;
						$oldTrace->save();
					}
				}
				$newTrace = new $ctClass();
				$newTrace->car_id = $this->car->id;
				$newTrace->car_series = $this->car->series;
				$newTrace->car_type = $this->car->type;
				$newTrace->vin = $this->car->vin;
				$newTrace->component_id = $spare['componentId'];
				$newTrace->component_name = $this->getComponentName($spare['componentId']);
				$newTrace->node_id = 100;
				$newTrace->create_time = date('YmdHis');
				$newTrace->user_id = Yii::app()->user->id;
				$newTrace->user_display_name = Yii::app()->user->display_name;
				$newTrace->provider = $this->calProvider($spare['barCode'], $spare['componentId']);
				$newTrace->bar_code = $spare['barCode'];
				$newTrace->status = 1;
				$newTrace->save();

				$isEngine = CarEngineAR::model()->find("engine_component_id=?", array($spare['componentId']));
				if(!empty($isEngine)) {
					$this->updateCarEngineCode($spare['barCode']);
				}
			}
		}
	}

	private function updateCarEngineCode ($engineCode) {
		$this->car->engine_code = $engineCode;
		$this->car->save();
		$this->throwTestlineCarInfo();
	}

	private function cutCarType ($type) {
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

	//lc0 unlock temp
	private function getLC0Type () {
		$sql = "SELECT car_type FROM lc0_unlock WHERE category='type'";
		$LC0TypeArray = Yii::app()->db->createCommand($sql)->queryColumn();
		$LC0Type = "('" . join("','", $LC0TypeArray) . "')";
		return array($LC0TypeArray,$LC0Type);
	}

	private function getLC0Config () {
		$sql = "SELECT car_config FROM lc0_unlock WHERE category='config' AND car_config>0";
		$LC0ConfigArray = Yii::app()->db->createCommand($sql)->queryColumn();
		$LC0Config = "(" . join(",", $LC0ConfigArray) . ")";
		return array($LC0ConfigArray,$LC0Config);
	}

	private function getLC0TypeColorText () {
		$sql = "SELECT car_type,car_colors FROM lc0_unlock WHERE category='type_color'";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		$textArray = array();
		foreach($datas as $data){
			$textArray[] = "(type='" . $data['car_type'] . "' AND color IN (" . $data['car_colors'] ."))"; 
		}

		$text = join(" OR ", $textArray);

		return $text;
	}

}
