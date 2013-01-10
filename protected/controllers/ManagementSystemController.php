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
    public function actionIndex()
    {
        $this->render('ManagementSystemHome');
    }

    /**
	 */
	public function actionHome()
	{
		$this->render('ManagementSystemHome');
	}

    
}
