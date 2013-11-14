<?php
Yii::import('application.models.AR.SpareReplacementAR');

class Spares
{
	public function replace ($spares) {
		$spares = is_array($spares) ? $spares : CJSON::decode($spares);
		foreach($spares as $spare) {
			$replacementAr = new SpareReplacementAR();
			$replacementAr->series = $spare['series'];
			$replacementAr->assembly_line = $spare['line'];
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
		}
	}
}
