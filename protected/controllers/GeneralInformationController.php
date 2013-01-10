<?php
Yii::import('application.models.User');
class GeneralInformationController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionGeneralIndex() {
		$this->render('generalIndex');
	}

	public function actionAccountMaintain() {
		$this->render('maintain/accountMaintain');
	}

	public function actionComponentMaintain() {
		$this->render('basicData/componentMaintain');
	}

	public function actionFaultMaintain() {
		$this->render('basicData/faultMaintain');
	}
}
