<?php

class SmsService
{
	protected $service;
	public function __construct() {
		$this->service = Yii::app()->params['sms_service'];
	}


	/**
	* call the SMS service to send message to phonenumbers
	* @param string $content, the message content
	* @param string $phoneNumbers, the receivers nubmers split by ","
	*/
	public function send ($content, $phoneNumbers){
		ini_set('soap.wsdl_cache_enabled',0);
		ini_set('soap.wsdl_cache_ttl',0);
		$client = new SoapClient($this->service);
		$params = array(
			'content'=>$content,
			'phoneNumbers'=>$phoneNumbers,
		);
		$result = (array)$client->send($params);

		return $result;
	}

	public function getRemain () {
		$client = new SoapClient($service);
		$result = (array)$client->getRemain();
		return $result;
	}
}