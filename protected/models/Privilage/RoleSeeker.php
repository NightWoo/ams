<?php
class RoleSeeker
{
	public static function queryAll() {
		$sql = "SELECT id,name FROM role";
		return Yii::app()->db->createCommand($sql)->queryAll();
	}
}
