<?php
class Query
{
	public function getDutyDepartment($node) {
		$sql = "SELECT id,display_name as name,is_enabled FROM duty_department WHERE type='$node'";
		return Yii::app()->db->createCommand($sql)->queryAll();
	}
}
