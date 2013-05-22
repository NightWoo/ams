<?php

// this contains the application parameters that can be maintained via GUI
return array(
	// this is displayed in the header section
	'title'=>'BMS',
	// this is used in error pages
	'adminEmail'=>'wu.jun9@byd.com',
	// number of posts displayed per page
	'postsPerPage'=>10,
	// maximum number of comments that can be displayed in recent comments portlet
	'recentCommentCount'=>10,
	// maximum number of tags that can be displayed in tag cloud portlet
	'tagCloudCount'=>20,
	// whether post comments need to be approved before published
	'commentNeedApproval'=>true,
	// the copyright information displayed in the footer section
	'copyrightInfo'=>'Copyright &copy; 2012 by AMS.',

	'htmlPath' => dirname(__FILE__). '/../../views/',

	//mailer
	'mail_enable' => true,
	'mail_from' => 'wu.jun9@byd.com',
	'mail_fromName' => 'AMS.CS',
	'mail_host' => 'smtp11.byd.com',
	'mail_port' => 25,
	'mailer' => 'smtp',
	'smtp_username' => 'wu.jun9@byd.com',
	'smtp_password' => 'byd@user',
	'SEND_ALL_MAIL_TO' => 'wu.jun9@byd.com',


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

	'ams2vin_assembly' => "http://10.23.11.6/csvinm/WebServices/AMS2Assembly.asmx?wsdl",
	'ams2vin_store_in' => "http://10.23.11.6/csvinm/WebServices/AMS2StoreIn.asmx?wsdl",
	'ams2vin_store_out' => "http://10.23.11.6/csvinm/WebServices/AMS2StoreOut.asmx?wsdl",

	'rpc.gate' => array('host' => '127.0.0.1' ,'port' => 60000),

);
