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

	}
}
