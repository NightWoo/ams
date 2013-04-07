<?php

// this contains the application parameters that can be maintained via GUI
return array(
	// this is displayed in the header section
	'title'=>'BMS',
	// this is used in error pages
	'adminEmail'=>'webmaster@example.com',
	// number of posts displayed per page
	'postsPerPage'=>10,
	// maximum number of comments that can be displayed in recent comments portlet
	'recentCommentCount'=>10,
	// maximum number of tags that can be displayed in tag cloud portlet
	'tagCloudCount'=>20,
	// whether post comments need to be approved before published
	'commentNeedApproval'=>true,
	// the copyright information displayed in the footer section
	'copyrightInfo'=>'Copyright &copy; 2012 by BMS.',

	'htmlPath' => dirname(__FILE__). '/../../views/',

	//mailer
	'mail_enable' => false,
	'mail_from' => 'byd_admin@163.com',
	'mail_fromName' => 'admin',
	'mail_host' => 'smtp.163.com',
	'mail_port' => 25,
	'mailer' => 'smtp',
	'smtp_username' => 'byd_admin@163.com',
	'smtp_password' => 'byd@123',
	'SEND_ALL_MAIL_TO' => 'byd_admin@163.com',


	//vinm db config
	'tds_server' => 'Server80',
	'tds_username' => 'VINLABEL',
	'tds_password' => 'VINLABEL',
	'tds_dbname' => 'VINM_AUTO',

	//HGZ
	'tds_SELL' => 'SELL',
	'tds_SELL_username' => 'vinm',
	'tds_SELL_password' => 'vinm_2011',
	'tds_dbname_BYDDATABASE' => 'BYDDATABASE',

	
	'tds_HGZ' => 'HGZ',
	'tds_HGZ_username' => 'hgz',
	'tds_HGZ_password' => 'Byd xa_2006',
	'tds_dbname_HGZ_DATABASE' => 'HGZ_DATABASE',



	//vinm web service soap wsdl 
	//'vinm_wsdl' => "http://192.168.1.25/carInfo/carInfo.asmx?wsdl",
	'vinm_wsdl' => "http://10.23.86.220/carInfo/carInfo.asmx?wsdl",
);
