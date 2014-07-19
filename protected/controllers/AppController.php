<?php

class AppController extends Controller
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
	    	// else if ($error['code'] == 401)
	    	// 	$this->redirect('/bms/login.php');
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 */
	public function actionIndex()
	{
		if(empty(Yii::app()->user) || Yii::app()->user->name === 'Guest') {
			// $this->render('login');
			$this->redirect('/bms/login.php');
			// throw new CHttpException(401,'The specified post cannot be found.');
		} else {
			 $this->render('index');
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
				$this->redirect('/bms/site/index');
				// $this->render('home');
				Yii::app()->end();
		}
		// display the login form
		$this->redirect('/bms/login.php');
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect('/bms');
	}

	public function actionPannelIndex ($pannel) {
		$this->render('pannels/'. $pannel);
	}
}
