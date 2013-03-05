<?php
Yii::import('application.models.User');
Yii::import('application.models.Privilage.Role');
Yii::import('application.models.Privilage.UserRole');

class PermitManager
{
	private $_user;
	private $_userRole;
	public function __construct() {
		$this->_user = User::model()->findByPk(Yii::app()->user->id);
		$this->_userRole = new UserRole();
	}

	public function init() {
	}
	public function check($point) {
		$permit = false;
		if(is_array($point)) {
			foreach($point as $p) {
				if(($permit = $this->_userRole->check($p))) {
					break;
				}
			}
		} else {
			$permit = $this->_userRole->check($point);
		}

		if(!$permit) {
			throw new Exception('对不起你没有操作权限');
		}
	}

}
