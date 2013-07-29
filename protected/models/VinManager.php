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
			throw new Exception("vin号 $vin 不存在");
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
	
	public static function validateDigit9($vin) {
		$replacement = array(
			"0" => "0",
			"1" => "1",
			"2" => "2",
			"3" => "3",
			"4" => "4",
			"5" => "5",
			"6" => "6",
			"7" => "7",
			"8" => "8",
			"9" => "9",
			"A" => "1",
			"B" => "2",
			"C" => "3",
			"D" => "4",
			"E" => "5",
			"F" => "6",
			"G" => "7",
			"H" => "8",
			"J" => "1",
			"K" => "2",
			"L" => "3",
			"M" => "4",
			"N" => "5",
			"P" => "7",
			"R" => "9",
			"S" => "2",
			"T" => "3",
			"U" => "4",
			"V" => "5",
			"W" => "6",
			"X" => "7",
			"Y" => "8",
			"Z" => "9",
		);

		$weight = array(
			0 => 8,
			1 => 7,
			2 => 6,
			3 => 5,
			4 => 4,
			5 => 3,
			6 => 2,
			7 => 10,
			9 => 9,
			10 => 8,
			11 => 7,
			12 => 6,
			13 => 5,
			14 => 4,
			15 => 3,
			16 => 2,
		);
		$success = true;
		$message = "VIN第9位校验成功！";
		$vin = strtoupper($vin);
		$len = strlen($vin);
		if($len != 17) {
			$success = false;
			$message = "VIN长度有误，请确认！";
			return array("success"=>$success,"message"=>$message);
		}
		$digitArray = str_split($vin);
		$sum = 0;
		for($i=0;$i<$len;$i++){
			if($i==8) continue;
			if(!array_key_exists($digitArray[$i], $replacement)){
				$success = false;
				$message = "VIN中存在非法字符 “".$digitArray[$i]. "” ，请确认！";
				return array("success"=>$success,"message"=>$message);
			}
			$sum += $replacement[$digitArray[$i]] * $weight[$i];
		}

		$digitValidated = $sum%11 == 10 ? "X" : $sum%11;
		if($digitValidated != $digitArray[8]){
			$success = false;
			$message = $vin . "第9位校验失败！请联系信息系统管理员";
		}

		return array("success"=>$success,"message"=>$message);
	}
}
