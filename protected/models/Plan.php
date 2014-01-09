<?php
Yii::import('application.models.AR.PlanAR');
Yii::import('application.models.AR.CarConfigAR');
Yii::import('application.models.AR.ConfigSapMapAR');
Yii::import('application.models.AR.ProductionVersionAR');
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
		if(empty($configSap)) {
			throw new Exception("SAP料号不存在");
		} else {
			$materialCode = $configSap->material_code;
			$data['material_code'] = $materialCode;
		}
		$prodVersionAr = ProductionVersionAR::model()->find("series=? AND line=?", array($data['car_series'], $data['assembly_line']));
		$prodVersion = empty($prodVersionAr) ? "" : $prodVersionAr->production_version;
		$dateTime = strtotime($data['plan_date']);
		$startDate = date("Ymd", $dateTime);
		$endDate = date("Ymd", strtotime("+2 day", $dateTime));

		$sapCreate = $this->createInSap($materialCode, $data['total'], $startDate, $endDate, $prodVersion);
		if(!empty($sapCreate) && "success" == $sapCreate[0] && !empty($sapCreate[3])){
			$data['plan_number'] = $sapCreate[3];
		} else {
			throw new Exception("SAP计划编号生成失败，无法完成新增。消息类型[". $sapCreate[1] . "]，描述[". $sapCreate[2] ."]");
		}

		$this->save($data);
	}

	public function modify($data) {
		if($data['total'] < $this->ar->ready) {
			throw new Exception("计划数量大于完成数量，请确认");
		}

		// $planNumber = $this->ar->plan_number;
		// if(!empty($planNumber)) {
		// 	$checkRet = $this->checkPlanInSap($planNumber);
		// 	if("success" == $checkRet[0] && false !== strpos($checkRet[3],"CRTD")) {
		// 		$dateTime = strtotime($data['plan_date']);
		// 		$startDate = date("Ymd", $dateTime);
		// 		$endDate = date("Ymd", strtotime("+2 day", $dateTime));
		// 		$modification = $this->modifyInSap($planNumber, $total, $startDate, $endDate);
		// 		if("success" != $modification[0] || "E" == $modification[1] || "W" == $modification[1] || "A" == $modification[1]) {
		// 			throw new Exception("SAP计划修改失败，无法完成修改。消息类型[". $modification[1] . "]，描述[". $modification[2] ."]");
		// 		}
		// 	} else {
		// 		throw new Exception("此计划对应的SAP计划已下达，无法修改，请先联系计划部SAP计划员修改SAP数据后，联系AMS管理员修改AMS数据");
		// 	}
		// }
		$this->save($data);

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

	public function remove() {
		if(empty($this->ar)) {
			throw new Exception("计划不存在");
		}

		if(0 < $this->ar->ready) {
			throw new Exception("此计划已经进行，不可删除");
		} 
		// if(!empty($this->ar->plan_number)) {
		// 	$checkRet = $this->checkPlanInSap($this->ar->plan_number);
		// 	if("success" != $checkRet[0] && (false !== strpos($checkRet[3],"CRTD") || "X" == $checkRet[5])) {
		// 		$deleteRet = $this->removeInSap($this->ar->plan_number);
		// 		$lowers = PlanAR::model()->findAll('priority>? AND plan_date=?' ,array($this->ar->priority, $this->ar->plan_date));
  //               if(!empty($lowers)) {
  //                   foreach($lowers as $lower) {
  //                       $lower->priority = $lower->priority - 1;
  //                       $lower->save();
  //                   }
  //               }
		// 	} else {
		// 		throw new Exception("SAP系统计划已下达，无法删除");
		// 	}
		// }
		$this->ar->delete();
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

	public function createInSap ($material, $quantity, $startDate, $endDate="", $prodVersion="", $plant="C113", $type="QC01") {
		try {
			$client = @new SoapClient(Yii::app()->params['saprfc']);
			$params = array(
				"material"=>$material,
				"quantity"=>$quantity,
				"startDate"=>$startDate,
				"endDate"=>$endDate,
				"prodVersion"=>$prodVersion,
				"plant"=>$plant,
				"orderType"=>$type
			);
			$result = $client->createPlan($params);
			$ret = (array)$result->createPlanResult;
			return $ret['string'];
		} catch(Exception $e) {
			$ret = array("fail", "E", "createInSap fail", "");
		}
	}

	public function modifyInSap ($planNumber, $quantity, $startDate="", $endDate="") {
		try {
			$client = @new SoapClient(Yii::app()->params['saprfc']);
			$params = array(
				"orderNumber"=>$planNumber,
				"quantity"=>$quantity,
				"startDate"=>$startDate,
				"endDate"=>$endDate,
			);
			$result = $client->modifyPlan($params);
			$ret = (array)$result->modifyPlanResult;
			return $ret['string'];
		} catch(Exception $e) {
			$ret = array("fail", "E", "modifyInSap fail", "");
		}
	}

	public function removeInSap ($planNumber) {
		try {
			$client = @new SoapClient(Yii::app()->params['saprfc']);
			$params = array(
				"orderNumber"=>$planNumber,
			);
			$result = $client->deletePlan($params);
			$ret = (array)$result->deletePlanResult;
			return $ret['string'];
		} catch(Exception $e) {
			$ret = array("fail", "E", "removeInSap fail", "");
		}
	}

	public function checkPlanInSap ($planNumber) {
		try {
			$client = @new SoapClient(Yii::app()->params['saprfc']);
			$params = array(
				"orderNumber"=>$planNumber,
			);
			$result = $client->checkOrder($params);
			$ret = (array)$result->checkOrderResult;
			return $ret['string'];
		} catch(Exception $e) {
			$ret = array("fail", "E", "checkPlanInSap fail", "" , "", "");
		}
	}
}