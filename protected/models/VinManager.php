<?php
Yii::import('application.models.AR.*');
class VinManager
{
	public static function importCar($vin) {
		$car = CarAR::model()->find('vin=?',array($vin));
		if(!empty($car)) {
			return $car;
		}
		$sql = "SELECT AUTO_ID as id,
					   AUTO_VINCODE as vin,
                       AUTO_TYPENAME as series,
					   AUTO_STYLENAME as type,
					   AUTO_COLORNAME as color,
                       AUTO_REMARK as remark
                  FROM view_VINM_AUTO
                 WHERE AUTO_VINCODE = '$vin'";
	
		$tdsSever = Yii::app()->params['tds_server'];
		$tdsDB = Yii::app()->params['tds_dbname'];
		$tdsUser = Yii::app()->params['tds_username'];
		$tdsPwd = Yii::app()->params['tds_password'];
		
		//php 5.4 linux use pdo cannot connet to ms sqlsrv db 
		//use mssql_XXX instead

		//connect
		$mssql=mssql_connect($tdsSever, $tdsUser, $tdsPwd);
		if(empty($mssql)) {
			throw new Exception("cannot connet to sqlserver $tdsSever, $tdsUser ");
		}
		mssql_select_db($tdsDB ,$mssql);
		
		//query
		$result = mssql_query($sql);
		$auto =  mssql_fetch_row($result);
		
		//disconnect
		mssql_close($mssql);
	
		if(empty($auto)) {
			throw new Exception("vin $vin not exit in vinm_auto");
		}
		$car = new CarAR();
		$car->id = intval($auto[0]);
		$car->vin = trim($auto[1]);
		$car->series = trim($auto[2]);
		$car->type = trim($auto[3]);
		$car->color = trim($auto[4]);
		$car->remark = trim($auto[5]);
		$car->create_time = date("YmdHis");
		$car->user_id = Yii::app()->user->id;
		$car->save();

		return $car;
	}
	
	//get car data from webservice http://10.23.86.111/[path]/getCar.asmx
	//written by wujun
	public static function getCar($vin){
		//if car data exist in table 'car',return
		//$car = CarAR::model()->find('vin=?',array($vin));
		//if(!empty($car)) {
		//	return $car;
		//} else 
		if (strlen($vin) >= 9) {
			$vin = strtoupper($vin);												//added by wujun
			$car = CarAR::model()->find("upper(vin) LIKE ?", array("%$vin"));		//added by wujun
			if(!empty($car)){														//added by wujun
				return $car;														//added by wujun
			}																		//added by wujun
		}
		//call webservice to get car data
		$client = new SoapClient(Yii::app()->params['vinm_wsdl']);
		$result = $client -> getCar(array('VinCode'=>$vin));
		
		//convert the result(stdClsss object) to array
		$resArray =(array)$result->getCarResult;
		
		
		$label = array("id","series","type","color","special_order");
		$data = array_combine($label,$resArray['string']);
		
		if(empty($data['id']) || empty($data['series'])) {
			throw new Exception("vin $vin not exit in vinm");
		}
		$car = new CarAR();
		$car->id = intval($data['id']);
		$car->vin = trim($vin);
		$car->series = trim($data['series']);
		$car->type = trim($data['type']);
		$car->color = trim($data['color']);
		$car->special_order = trim($data['special_order']);
		//$car->remark = trim($data['remark']);
		$car->create_time = date("YmdHis");
		$car->user_id =yii::app()->user->id;
		$car->save();
		
		return $car;
	}
	
}
