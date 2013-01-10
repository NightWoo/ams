<?php
Yii::import('application.models.AR.DistributorAR');

class DistributorSeeker
{
	public function __construct(){
	}
	
	public function getNameList($token) {
		$sql = "SELECT display_name FROM distributor WHERE display_name LIKE '%$token%'";
		return Yii::app()->db->createCommand($sql)->queryColumn();
	}

	public function getDistributorId($distributorName) {
		$sql = "SELECT id AS distributor_id, display_name AS distributor_name, name FROM distributor WHERE display_name LIKE '%$distributorName%'";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		return $datas;
	}
}
