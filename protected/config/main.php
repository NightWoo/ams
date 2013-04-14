<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$logDir = '/home/work/bms/web/logs/';
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'BMS',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.Mailer.*',
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'class' => 'BmsUser',
			'allowAutoLogin'=>true,
			'stateKeyPrefix' => 'bms',
		),
		'permitManager' => array(
			'class' => 'application.models.Privilage.PermitManager',
		),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=bms',
			'emulatePrepare' => true,
			// 'username' => 'root',
			// 'password' => '',
			'username' => 'bms_w',
			'password' => '123',
			'charset' => 'utf8',
		),
		'dbTest'=>array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=test',
			'emulatePrepare' => true,
			'username' => 'bms_w',
			'password' => '123',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            	'errorAction'=>'site/error',
        	),
		'smarty'=>array(
    		'class'=>'application.extensions.CSmarty',
		),
        'urlManager'=>array(
        	'urlFormat'=>'path',
			'showScriptName' => true,
        	'rules'=>array(
			'<controller:\w+>/<action:\w+>'=>'<controller>/<action>'
        	),
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'      => 'CFileLogRoute',
					'levels'     => 'trace, info, error, warning',
					'categories' => 'bms',
					'logPath'    => $logDir ,
					'logFile'    => 'bms.log',
					'maxFileSize'=> 2000000,
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
