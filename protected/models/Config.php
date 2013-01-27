<?php
Yii::import('application.models.AR.CarConfigAR');
Yii::import('application.models.AR.CarConfigListAR');
class Config
{
	private $configAR;

	protected function __construct($configAR) {
		$this->configAR = $configAR;
	}

	public static function create($configId) {
		$configAR = CarConfigAR::model()->findByPk($configId);
		if(empty($configAR)) {
			throw new Exception('no such car config');
		}
		$c = __class__;
        $config = new $c($configAR);

		return $config;
	}

	public static function createByName($configName) {
		$configAR = CarConfigAR::model()->find('name=?', array($configId));
		if(empty($configAR)) {
            throw new Exception('no such car config');
        }

		$c = __class__;
        $config = new $c($configAR);

		return $config;
	}	

	public function getDetail($car, $nodeName, $trace = 1) {
		$node = Node::createByName($nodeName);
        if(!$node->exist()) {
            throw new Exception('node ' . $nodeName . ' is not exit');
        }

		$ctClass = ucFirst($car->series) . "ComponentTraceAR";
		if(empty($node)) {
            $configLists = CarConfigListAR::model()->findAll('config_id=? AND istrace!=0', array($this->configAR->id));
			$traceComponents = $ctClass::model()->findAll('car_id=?',array($car->id));
        } else {
        	$configLists = CarConfigListAR::model()->findAll('config_id=? AND node_id=? AND istrace!=0', array($this->configAR->id, $node->id));
			$traceComponents = $ctClass::model()->findAll('car_id=? AND node_id=?',array($car->id,$node->id));
		}
			
		$datas = array();
		$traceList = array();
		foreach($traceComponents as $trace) {
			$traceList[$trace['component_id']] = $trace;
		}
		foreach($configLists as $configList) {
			$componentId = $configList['component_id'];
			$temp = $this->getComponent($componentId);
			$temp['provider_code'] = $this->getProvider($configList['provider_id']);
			$temp['bar_code'] = empty($traceList[$componentId]) ? '' : $traceList[$componentId]['bar_code'];
			$datas[] = $temp;
		}

		return $datas;
	}

	public function getProvider($providerId) {
		$sql = "SELECT code FROM provider WHERE id=$providerId";

		return Yii::app()->db->createCommand($sql)->queryScalar();
	}

	public function getComponent($componentId) {
		$sql = "SELECT id, display_name as name, code, simple_code, display_name FROM component WHERE id=$componentId";
		return Yii::app()->db->createCommand($sql)->queryRow();
	}

	//added by wujun
	public static function copyConfigList($originalId, $clonedId) {
		$seeker = new ConfigSeeker();
		$details = $seeker->getListDetail($originalId);
		if(!empty($details)){
			foreach($details as $detail) {
				$ar = new CarConfigListAR();
				$ar->config_id = $clonedId;
				$ar->user_id = Yii::app()->user->id;
				$ar->create_time = date("YmdHis");
				$ar->istrace = $detail['istrace'];
				$ar->provider_id = $detail['provider_id'];
				$ar->component_id = $detail['component_id'];
				$ar->node_id = $detail['node_id'];
				$ar->remark = $detail['remark'];
				$ar->save();
			}
		} else {
			throw new Exception('there is no detail in this car_config');
		}			
	}

	public function addMainConfigPage($file) {
	}
}
