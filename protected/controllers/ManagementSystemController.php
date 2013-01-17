<?php
class ManagementSystemController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	/**
     */
    public function actionIndex() {
        $this->render('ManagementSystemHome');
    }

    /**
	 */
	public function actionHome() {
		$this->render('ManagementSystemHome');
	}

	public function actionQuality() {
		$view = $this->validateStringVal('view', '');
		$this->render($view);
	}

	public function actionScene() {
		$this->render('MSScene');
	}

	public function actionManpower() {
		$view = $this->validateStringVal('view', '');
		$this->render($view);
	}

	public function actionStandardForm() {
		$this->render('MSStandardForm');
	}

    
}
