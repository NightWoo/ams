<?php
Yii::import('application.models.AR.NodeAR');
class Node
{
	private $_ar;

	protected function __construct() {
	}	
	
	public static function create($id) {
		$c = __class__;
		$node = new $c();
		$node->_ar = NodeAR::model()->findByPk($id);
		return $node;
	}

	public static function createByName($name) {
		$name = trim($name);
		if(empty($name)) {
			throw new Exception('node name can not be null');
		}
		$c = __class__;
        $node = new $c();
        $node->_ar = NodeAR::model()->find('name = ?', array(trim($name)));
		return $node;
	} 

	public function exist() {
		return !empty($this->_ar);
	}

	public function getParentNode() {
		$parent = Node::create($this->parent_id);
		return $parent;
	}

	public function __get($param) {
		return $this->_ar->{$param};
	}	
}
