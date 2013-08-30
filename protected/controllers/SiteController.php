<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 */
	public function actionIndex()
	{
		if(empty(Yii::app()->user) || Yii::app()->user->name === 'Guest') {
			$this->render('login');
		} else {
			 $this->render('home');
			//$this->redirect('/bms/execution/home');
		} 
		
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
				echo CActiveForm::validate($model);
				Yii::app()->end();
		}

		// collect user input data
		$model->attributes=$_POST;
		if(!empty($_POST['rememberMe']) && $_POST['rememberMe'] === 'true') {
			$model->rememberMe = true;
		}
		// validate user input and redirect to the previous page if valid
		if($model->validate() && $model->login()) {
				//$this->redirect('/bms/execution/home');
				$this->render('home');
				Yii::app()->end();
		}
		// display the login form
		$this->redirect('/bms');

	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect('/bms');
	}

	public function actionEfficiencyPannelIndex () {
		$this->render('pannels/efficiencyPannel');
	}
}
