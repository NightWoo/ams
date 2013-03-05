<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to 'column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();


	public function validateStringVal($paramName, $default = '') {
		$paramsVal = empty($_REQUEST[$paramName]) ? $default : trim($_REQUEST[$paramName]);

		return $paramsVal;
	}
	
	public function validateIntVal($paramName, $default = 0) {
		$paramsVal = empty($_REQUEST[$paramName]) ? $default : intval($_REQUEST[$paramName]);
		return $paramsVal;
	}

	public function validateArrayVal($paramName, $default = array()) {
		$paramsVal = empty($_REQUEST[$paramName]) ? $default : $_REQUEST[$paramName];

		if(!is_array($paramsVal)) {
			$paramsVal = array($paramsVal);
		}

		return $paramsVal;
	}
	
}
