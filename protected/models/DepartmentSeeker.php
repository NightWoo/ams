<?php
Yii::import('application.models.AR.DutyDepartmentAR');

class DepartmentSeeker
{
	public function __construct(){
	}
	
	public function getNameList($name, $type) {
		$upperName = strtoupper($name);
		$sql = "SELECT display_name FROM duty_department WHERE type = '$type' AND upper(display_name) LIKE '%$upperName%'";
		return Yii::app()->db->createCommand($sql)->queryColumn();
	}
}
