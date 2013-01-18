<?php
Yii::import('application.models.User');
Yii::import('application.models.Privilage.Role');
class PermitManager
{
	private $_user;
	private $_role;
	public function __construct() {
		$this->_user = User::model()->findByPk(Yii::app()->user->id);
		$this->_role = Role::create($this->_user->role_id);
	}

	public function init() {
	}
	public function check($point) {
		$permit = false;
		if(!empty($this->_role)) {
			if(is_array($point)) {
				foreach($point as $p) {
					if(($permit = $this->_role->check($p))) {
						break;
					}
				}
			} else {
				$permit = $this->_role->check($point);
			}
		}

		if(!$permit) {
			throw new Exception('对不起你没有操作权限');
		}
	}

}
