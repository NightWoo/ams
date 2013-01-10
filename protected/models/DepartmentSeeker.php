<?php
Yii::import('application.models.AR.DutyDepartmentAR');

class DepartmentSeeker
{
	public function __construct(){
	}
	
	public function getNameList($token, $type) {
		$sql = "SELECT display_name FROM duty_department WHERE type = '$type' AND display_name LIKE '%$token%'";
		return Yii::app()->db->createCommand($sql)->queryColumn();
	}
}
