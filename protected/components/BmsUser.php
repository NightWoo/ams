<?php
class BmsUser extends CWebUser
{
	private $_userinfo;

	public function getDisplay_name() {
		$this->loadUser();
		return $this->_userinfo->display_name;
	}

	private function loadUser() {
		if(empty($this->_userinfo)) {
			if(!empty(Yii::app()->user->id)) {
				$this->_userinfo = User::create(Yii::app()->user->id);
			}
		}
	}
}
