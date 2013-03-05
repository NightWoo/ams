<?php
Yii::import('application.models.Privilage.*');
class RoleController extends BmsBaseController
{
	public function actionShowAll() {
		$data = RoleSeeker::queryAll();

		$this->renderJsonBms(true, 'OK', $data);
	}

	//add role to user
	public function actionAddToUser()  {
		try {
			Yii::app()->permitManager->check(array('BASE_DATA_EDIT'));
		
			$userId = $this->validateIntVal('userId');
			$roleIds = $this->validateArrayVal('roleIds');

			$data = Role::addToUser($roleIds, $userId);

			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false,  $e->getMessage());
		}
	}
}
