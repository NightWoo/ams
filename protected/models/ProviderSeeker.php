<?php
Yii::import('application.models.AR.PoviderAR');

class ProviderSeeker
{
	public function __construct(){
	}
	
	public function getNameList($token) {
		$sql = "SELECT display_name FROM provider WHERE display_name LIKE '%$token%'";
		return Yii::app()->db->createCommand($sql)->queryColumn();
	}

	public function getProviderCode($providerName) {
		$sql = "SELECT id AS provider_id, code AS provider_code, display_name AS provider_name, name FROM provider WHERE display_name LIKE '%$providerName%'";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		return $datas;
	}
}
