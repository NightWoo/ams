<?php
Yii::import('application.models.AR.PlanAR');
Yii::import('application.models.AR.CarConfigAR');
Yii::import('application.models.AR.ConfigSapMapAR');
Yii::import('application.models.PlanSeeker');

class Plan {
	private $ar;
	public function __construct ($planId=0) {
		if(empty($planId)) {
			$this->ar = new PlanAR();
		} else {
			$this->ar = PlanAR::model()->findByPk($planId);
		}
	}

	public static function createById ($id) {
		$c = __class__;
		return new $c($id);
	}

	public function __get($attr) {
		return $this->{$attr};
	}

	public function generate($data) {
		$config = CarConfigAR::model()->find('id=?', array($data['config_id']));
		if(empty($config)) {
			throw new Exception("配置不存在");
		}
		$configSap = ConfigSapMapAR::model()->find('config_id=? AND color=?', array($data['config_id'], $data['color']));
		// if(empty($configSap)) {
		// 	throw new Exception("SAP料号不存在");
		// } else {
		// 	$materialCode = $configSap->material_code;
		// }
		// $planNumber = $this->createInSap($materialCode, $data['total'], $data['plan_date'], $endDate="", $type="", $prodVersion="", $plant="");
		// if(empty($planNumber)) {
		// 	throw new Exception("计划编号生成失败");
		// }

		// $data['plan_number'] = $planNumber;
		// $data['material_code'] = $materialCode;

		$this->save($data);
	}

	public function modify($data) {
		$this->save($data);
		// $planNumber = $this->ar->plan_number;
		// $modification = $this->modifyInSap($planNumber, $data['total'], $data['plan_date'], $endDate="", $prodVersion="");
		// if(){
		// 	throw new Exception("SAP修改计划失败");
		// }
	}

	public function save ($data) {
		$id = $this->ar->id;
		$samePriority = PlanAR::model()->find('plan_date=? AND priority=? AND id<>? ORDER BY priority desc', array($data['plan_date'],$this->ar->priority, $id));
		if(!empty($samePriority)){
			$max = PlanAR::model()->find('plan_date=? order by priority desc', array($data['plan_date']));
			$this->ar->priority = $max->priority + 1;
		}

		if(empty($this->ar->batch_number)){
			$batchNumber = $this->generateBatchNumber($data['plan_date']);
			$this->ar->batch_number = $batchNumber;
		}

		$data['user_id'] = Yii::app()->user->id;
		$data['modify_time'] = date("YmdHis");

		foreach($data as $key => $value) {
			$this->ar->$key = $value;
		}
		$this->ar->save();
	}

	public function generateBatchNumber($planDate) {
		$date = strtotime($planDate);
		$year = date("Y", $date);
		$yearCode = CarYear::getYearCode($year);
		$monthDay = date("md", $date);

		$ret = $yearCode . $monthDay;

		$sql = "SELECT batch_number FROM plan_assembly WHERE batch_number LIKE '$ret%' ORDER BY batch_number DESC";
		$lastSerial = Yii::app()->db->createCommand($sql)->queryScalar();
		$lastKey = intval(substr($lastSerial, 5 , 3));

		$ret .= sprintf("%03d", (($lastKey + 1) % 1000));

		return $ret;
	}

	public function createInSap ($material, $quantity, $startDate, $endDate="", $type="", $prodVersion="", $plant="") {
		$orderData = array();
	}

	public function modifyInSap ($planNumber, $quantity, $startDate, $endDate="", $prodVersion="") {

	}
}