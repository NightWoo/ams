<?php
Yii::import('application.models.FaultSeeker');
Yii::import('application.models.FaultBaseSeeker');
Yii::import('application.models.AR.*');

class Fault
{
	private $tablePrefix;
	private $vin;
	private $faults;
	private $others;
	protected function __construct($tablePrefix, $vin, $faults, $others = null){
		$this->tablePrefix = $tablePrefix;
		
		$this->vin = $vin;
		$this->faults = is_array($faults) ? $faults : CJSON::decode($faults);
		$this->others = $others;
	}
		
	public static function createSeeker() {
		return new FaultSeeker();
	}

	public static function createBaseSeeker() {
        return new FaultBaseSeeker();
    }

	public static function createSeekerByComponent($component, $series) {
		return new FaultComponentSeeker($component, $series);
	}

	public static function create($tablePrefix, $vin, $faults, $others = null) {
		$c = __class__;
		return new $c($tablePrefix, $vin, $faults, $others);
	}

		
	public function save($statusPrefix, $iswdi = false) {
		$car = Car::create($this->vin);
		$series = $car->car->series;
		
		$userId = Yii::app()->user->id;
		$curtime = date('Y-m-d H:i:s');
		
		$faultClass = $this->getFaultClass($this->tablePrefix . "_" . $series);
	
		$allFaults = array();
		if($statusPrefix === '离线') {
			$allFaults = $this->faults;
		} else {
			foreach($this->faults as $fault) {
				$modeId = $fault['faultId'];
				$standard = FaultStandardAR::model()->findByPk($modeId);
				if(empty($standard)) {
					//throw new Exception('mode is not exist @modeId=' .$modeId);
					//no fault
					continue;
				}

				//component
				$componentId = $standard->component_id;

				$fixed = empty($fault['fixed']) ? false : $fault['fixed'];
				$key = "{$componentId}_{$modeId}";
				if(isset($allFaults[$key]) && $fixed) {
					continue;
				}				
				
				$allFaults[$key] = $fault;
			}
		}
	
		foreach($allFaults as $fault) {
			$modeId = $fault['faultId'];
			$fixed = empty($fault['fixed']) ? false : $fault['fixed']; 
			$status = $fixed ? $statusPrefix . '修复' : '未修复';
			
			$standard = FaultStandardAR::model()->findByPk($modeId);
			if(empty($standard)) {
				//throw new Exception('mode is not exist @modeId=' .$modeId);
				//no fault
				continue;
			}

			//component
			$componentId = $standard->component_id;
			$sql = "SELECT display_name FROM component WHERE id={$componentId}";
			$component = Yii::app()->db->createCommand($sql)->queryScalar();
			if(empty($component)) {
				throw new Exception('standard fault\'s component is not exist @standardId=' .$modeId);
			}
		
			if(isset($fault['dutyDepartment'])) {	
				$exist = $faultClass::model()->find('car_id=? AND fault_id=? AND duty_department=? AND status=?', array($car->car->id,$standard->id,$fault['dutyDepartment'], '未修复'));
			} else {
				$exist = $faultClass::model()->find('car_id=? AND fault_id=? AND status=?', array($car->car->id,$standard->id, '未修复'));
			}
			if(!empty($exist)) {
				if($status === '离线修复') {
					//updater
					$exist->status = $status;
					if(isset($fault['newDutyDepartment'])){
						$exist->duty_department = $fault['newDutyDepartment'];
					}
					// $exist->updator = $userId;
					$exist->updator = empty($this->others['checker']) ? $userId : $this->others['checker'];
					$exist->modify_time = date('YmdHis');
					$exist->save();
					continue;
				}
			}
			if($statusPrefix === '离线' && !$iswdi) {
				continue;
			}
			//wdi need save

			$ar = new $faultClass();
			$ar->car_id = $car->car->id;
			$ar->create_time = $curtime;
			$ar->component_id = $componentId;
			$ar->component_name = $component;
			$ar->fault_id = $standard->id;
			$ar->fault_level = $standard->level;
			$ar->fault_mode = $standard->mode;
			$ar->fault_description = $standard->description;
			$ar->status = $status;
			// $ar->creator = $userId;
			$ar->creator = empty($this->others['checker']) ? $userId : $this->others['checker'];
			// $ar->updator = $userId;
			$ar->updator = empty($this->others['checker']) ? $userId : $this->others['checker'];
			$ar->node_trace_id = empty($this->others['traceId']) ? 0 : $this->others['traceId'];

			if($iswdi) {
				$ar->modify_time = $curtime;
                $ar->create_time = empty($this->others['checkTime']) ? $curtime : $this->others['checkTime'];
				$ar->checker1 = empty($this->others['checker']) ? 0 : $this->others['checker'];
				$ar->checker2 = empty($this->others['subChecker']) ? 0 : $this->others['subChecker'];
            }
			
			if(isset($fault['dutyDepartment'])) {
				$ar->duty_department = $fault['dutyDepartment'];
			}			

			$ar->save();
		}
		// if(!$iswdi)
		// $car->detectStatus();	
	}

	public function show() {
        $car = Car::create($this->vin);
        $series = $car->car->series;
        $table = $this->tablePrefix . "_" . $series;
	
		$additional = '';	
		//if($this->tablePrefix === 'VQ3_FACADE_TEST') 
		$sql = "SELECT f.component_id,f.component_name,f.fault_id, f.fault_mode, f.create_time, f.creator, f.duty_department as duty_department_id, d.display_name as duty_department FROM $table as f left join duty_department as d on d.id=f.duty_department WHERE f.car_id={$car->car->id} AND f.status = '未修复'";

		$faults = Yii::app()->db->createCommand($sql)->queryAll();
		if(empty($faults)) {
			throw new Exception('此车暂无未修复故障');
		}

		$sql = "SELECT id,display_name FROM user";
        $users = Yii::app()->db->createCommand($sql)->queryAll();
        $userInfos = array();
        foreach($users as $user) {
            $userInfos[$user['id']] = $user['display_name'];
        }

		foreach($faults as &$fault) {
			$fault['display_name'] = $userInfos[$fault['creator']];
		}

		return array('car'=>$car->car, 'faults' => $faults);
	}

	public function exist() {
		$car = Car::create($this->vin);
        $series = $car->car->series;
        $table = $this->tablePrefix . "_" . $series;

        $sql = "SELECT count(*) FROM $table WHERE status = '未修复'";

        $exist = Yii::app()->db->createCommand($sql)->queryScalar();

        return !empty($exist);
	}

	public function wdiNoFault(){
		$car = Car::create($this->vin);
		$series = $car->car->series;
		
		$userId = Yii::app()->user->id;
		$curtime = date('Y-m-d H:i:s');
		
		$faultClass = $this->getFaultClass($this->tablePrefix . "_" . $series);
		$ar = new $faultClass();
		$ar->car_id = $car->car->id;
		$ar->create_time = $curtime;
		
		$ar->status = '合格';
		$ar->creator = $userId;
		$ar->updator = $userId;

		$ar->modify_time = $curtime;
        $ar->create_time = empty($this->others['checkTime']) ? $curtime : $this->others['checkTime'];
		$ar->checker1 = empty($this->others['checker']) ? 0 : $this->others['checker'];
		$ar->checker2 = empty($this->others['subChecker']) ? 0 : $this->others['subChecker'];
		
		$ar->save();
	}

	//public function showGasBag($mainGasBag) {
	public function showGasBag() {
		$car = Car::create($this->vin);
		$configId =$car->car->config_id;
		//$sql = "SELECT count(*) FROM car_config_list WHERE config_id=$configId AND component_id=$mainGasBag";
		$sql = "SELECT count(*) FROM car_config_list WHERE config_id=$configId AND node_id=15";

		$exist = Yii::app()->db->createCommand($sql)->queryScalar();
		return !empty($exist);
	}

	public function getFaultClass($tableName) {
		$splits = explode('_', $tableName);

		$cls = ucwords($splits[0]);
		$total = count($splits);
		for($i = 1; $i < $total; ++ $i) {
			$cls .= ucFirst(strtolower($splits[$i]));
		}

		return $cls . "AR";
	}
}	
