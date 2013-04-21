<?php
class Query
{
	public function queryDutyDepartment($node) {
		$sql = "SELECT id,display_name as name FROM duty_department WHERE type='$node'";
		return Yii::app()->db->createCommand($sql)->queryAll();
	}
}
