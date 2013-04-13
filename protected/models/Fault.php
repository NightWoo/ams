<?php
Yii::import('application.models.FaultSeeker');
Yii::import('application.models.FaultBaseSeeker');
Yii::import('application.models.AR.*');

class Fault
{
	private $tablePrefix;
	private $vin;
	private $faults;
	protected function __construct($tablePrefix, $vin, $faults){
		$this->tablePrefix = $tablePrefix;
		
		$this->vin = $vin;
		$this->faults = CJSON::decode($faults);
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

	public static function create($tablePrefix, $vin, $faults) {
		$c = __class__;
		return new $c($tablePrefix, $vin, $faults);
	}

		
	public function save($statusPrefix) {
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
		
			if(isset($fault['category'])) {	
				$exist = $faultClass::model()->find('car_id=? AND fault_id=? AND duty_department=? AND status=?', array($car->car->id,$standard->id,$fault['category'], '未修复'));
			} else {
				$exist = $faultClass::model()->find('car_id=? AND fault_id=? AND status=?', array($car->car->id,$standard->id, '未修复'));
			}
			if(!empty($exist)) {
				if($status === '离线修复') {
					//updater
					$exist->status = $status;
					$exist->updator = $userId;
					$exist->modify_time = date('YmdHis');
					$exist->save();
					continue;
				}
			}
			if($statusPrefix === '离线') {
				continue;
			}

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
			$ar->creator = $userId;
			$ar->updator = $userId;
			
			if(isset($fault['category'])) {
				$ar->duty_department = $fault['category'];
			}			

			$ar->save();
		}
		$car->detectStatus();	
	}

	public function show() {
        $car = Car::create($this->vin);
        $series = $car->car->series;
        $table = $this->tablePrefix . "_" . $series;
	
		$additional = '';	
		if($this->tablePrefix === 'VQ3_FACADE_TEST') $additional = ",duty_department as category";
		$sql = "SELECT component_id,component_name,fault_id, fault_mode, create_time, creator $additional FROM $table WHERE car_id={$car->car->id} AND status = '未修复'";

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
		$cls .= ucFirst(strtolower($splits[1]));
		$cls .= ucFirst(strtolower($splits[2]));
		$cls .= ucFirst(strtolower($splits[3]));

		return $cls . "AR";
	}
}	
