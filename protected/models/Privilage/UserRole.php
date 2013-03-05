<?php
Yii::import("application.models.Privilage.*");
class UserRole
{
	private $_roles = array();
	public function __construct() {
		$sql = "SELECT role_id FROM user_role WHERE user_id=".Yii::app()->user->id;
		$roleIds = Yii::app()->db->createCommand($sql)->queryColumn();
		
		foreach($roleIds as $roleId) {
			$this->_roles[] = Role::create($roleId);
		}
	}

	public function check($point) {
		$ret = false;
		foreach($this->_roles as $role) {
			$ret = $role->check($point);
			if($ret) {
				break;
			}
		}
		return $ret;
	}
}
