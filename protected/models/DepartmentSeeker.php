<?php
Yii::import('application.models.AR.DutyDepartmentAR');

class DepartmentSeeker
{
	public function __construct(){
	}
	
	public function getNameList($name, $type, $all=false) {
		$upperName = strtoupper($name);
		$enabled = $all ? "" : "is_enabled=1 AND ";
		$sql = "SELECT display_name FROM duty_department WHERE $enabled type = '$type' AND upper(display_name) LIKE '%$upperName%'";
		return Yii::app()->db->createCommand($sql)->queryColumn();
	}

	public function getDutyGroupList($type) {
		$sql = "SELECT DISTINCT(`group`) as duty_group FROM duty_department WHERE is_enabled=1 AND type = '$type' AND `group`<>'' ORDER BY duty_group";
		return Yii::app()->db->createCommand($sql)->queryAll();
	}
}
