<?php
Yii::import('application.models.AR.*');
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
}
