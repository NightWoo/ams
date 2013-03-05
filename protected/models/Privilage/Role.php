<?php
Yii::import('application.models.AR.*');
Yii::import('application.models.User');

class Role
{
	private $_ar;
	private $_points;
	private $_super;
	private function __construct($id) {
		$this->_ar = RoleAR::model()->findByPk($id);
        $sql = "SELECT point FROM role_privilage WHERE role_id=$id";
        $this->_points = Yii::app()->db->createCommand($sql)->queryColumn();
        $this->_super = in_array('ALL', $this->_points);
	} 

	public static function create($id) {
		$c = __class__;
		return new $c($id);
	}

	public function check($point) {
		if($this->_super) {
			return true;
		}

		return in_array($point, $this->_points);
	}

	public function batchCheck($points) {
		$ret = array();
		foreach($points as $point) {
			$ret[] = array($point => $this->check($point));
		}

		return $point;
	}

	public static function addToUser($roleIds, $userId) {
		$user = User::model()->findByPk($userId);
		if(empty($user)) {	
			throw new Exception("userId $userId not exist!!");
		}
		$sql = "SELECT role_id FROM user_role WHERE user_id=$userId";
		$hasRoles = Yii::app()->db->createCommand($sql)->queryColumn();
		$toAdd = array_diff($roleIds, $hasRoles);
		$toDel = array_diff($hasRoles, $roleIds);
		foreach($toAdd as $roleId) {
			$sql = "INSERT INTO user_role(user_id,role_id) VALUES($userId, $roleId)";
			Yii::app()->db->createCommand($sql)->execute();
		}

		foreach($toDel as $role) {
			$sql = "DELETE FROM user_role WHERE user_id=$userId AND role_id=$roleId";
			Yii::app()->db->createCommand($sql)->execute();
		}
		
	}
}
