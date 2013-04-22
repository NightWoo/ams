<?php
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.OrderConfigAR');
Yii::import('application.models.AR.DistributorAR');
Yii::import('application.models.AR.CarTypeMapAR');
Yii::import('application.models.AR.LaneAR');
Yii::import('application.models.Order');

class OrderSeeker
{
	public function __construct(){
	}

	private static $COLD_RESISTANT = array('非耐寒','耐寒');

	public function getOriginalOrders($orderNumber) {
        if(empty($orderNumber)){
        	throw new Exception ('订单号不能为空');
        }
        $sql = "SELECT DATAK40_DGMXID AS order_detail_id, DATAK40_JXSMC AS distributor, DATAK40_DGDH AS order_number, DATAK40_CXMC AS series, DATAK40_CLDM AS car_type_code, DATAK40_CLXH AS sell_car_type, DATAK40_BZCX AS car_model, DATAK40_CXSM AS car_type_description, DATAK40_CLYS AS sell_color, DATAK40_VINMYS AS color, DATAK40_DGSL AS amount, DATAK40_XZPZ AS options, DATAK40_DDXZ AS order_nature, DATAK40_DDLX AS cold_resistant, DATAK40_NOTE AS remark, DATAK40_JZPZ AS additions, DATAK40_SSDW AS production_base, DATAK40_JXSDM AS distributor_code
                FROM DATAK40_CLDCKMX
                WHERE DATAK40_SSDW = '3' AND DATAK40_DGDH = '$orderNumber'";
		
		$tdsSever = Yii::app()->params['tds_SELL'];
        $tdsDB = Yii::app()->params['tds_dbname_BYDDATABASE'];
        $tdsUser = Yii::app()->params['tds_SELL_username'];
        $tdsPwd = Yii::app()->params['tds_SELL_password'];
       
        $orders = $this->mssqlQuery($tdsSever, $tdsUser, $tdsPwd, $tdsDB, $sql);

        foreach($orders as &$order){
            $order['car_type'] = $order['car_model']. "(" . $order['car_type_description'] . ")";
            $order['config_description'] = '';
            if(!empty($order['options'])){
            	$order['config_description'] .= $order['options'];
            	if(!empty($order['additions'])) $order['config_description'] .= $order['additions'];
            }else if(!empty($order['additions'])){
            	$order['config_description'] .= $order['additions'];
            }
            $order['cold_resistant'] == '耐寒型' ? $order['cold_resistant'] = '1' : $order['cold_resistant'] = '0';

            $sql="SELECT SUM(amount) FROM `order` WHERE order_detail_id='{$order['order_detail_id']}'";
            $amountSum = Yii::app()->db->createCommand($sql)->queryScalar();
            $order['amount'] -= $amountSum;
            if($order['amount']<0) $order['amount'] = 0;
        }

        return $orders;
	}

	public function mssqlQuery($tdsSever, $tdsUser, $tdsPwd, $tdsDB, $sql){
		//php 5.4 linux use pdo cannot connet to ms sqlsrv db 
        //use mssql_XXX instead

		$mssql=mssql_connect($tdsSever, $tdsUser, $tdsPwd);
        if(empty($mssql)) {
            throw new Exception("cannot connet to sqlserver $tdsSever, $tdsUser ");
        }
        mssql_select_db($tdsDB ,$mssql);
        
        //query
        $result = mssql_query($sql);
        $datas = array(); 
        while($ret = mssql_fetch_assoc($result)){
        	$datas[] = $ret;
        }
        //disconnect
        mssql_close($mssql);

        //convert to UTF-8
        foreach($datas as &$data){
            foreach($data as $key => $value){
                $data[$key] = iconv('GB2312','UTF-8', $value);
            }
        }

        return $datas;
	}

	public function query($standbyDate, $orderNumber, $distributor, $status='all', $series='', $orderBy='lane_id,priority,`status`') {

		$statusArray = $this->parseStatus($status);
		$condition = "`status` IN(" . join(",", $statusArray) . ")";
		
		if(!empty($standbyDate)){
			$condition .= " AND standby_date='$standbyDate'";
		}

		if(!empty($orderNumber)){
			$condition .= " AND order_number LIKE '%$orderNumber'";
		}

		if(!empty($distributor)){
			$condition .= " AND distributor_name LIKE '%$distributor%'";
		}

		if(!empty($series)){
			$condition .= " AND series='$series'";
		}
		
		$sql = "SELECT id, order_number, board_number, priority, standby_date, amount, hold, count, series, car_type, color, cold_resistant, order_config_id, distributor_name, lane_id, remark, status, create_time, standby_finish_time, out_finish_time FROM bms.order WHERE $condition ORDER BY $orderBy ASC";
		$orderList = Yii::app()->db->createCommand($sql)->queryAll();
		if(empty($orderList)){
			throw new Exception("查无订单");
		}

		foreach($orderList as &$detail) {
			if(!empty($detail['order_config_id'])){
				$detail['order_config_name'] = OrderConfigAR::model()->findByPk($detail['order_config_id'])->name;
			}
			$detail['car_model'] = CarTypeMapAR::model()->find("car_type=?", array($detail['car_type']))->car_model;
			
			$detail['lane_name'] = '';
			$lane = LaneAR::model()->findByPk($detail['lane_id']);
			if(!empty($lane)) $detail['lane_name'] = $lane->name;
			if(!empty($detail['order_config_name'])){
				$detail['car_type_config'] = $detail['car_model']. "/" . $detail['order_config_name'];
			}else {
				$detail['car_type_config'] = $detail['car_model'];
			}
			if($detail['cold_resistant'] == 1){
				$detail['cold'] = '耐寒';
			} else {
				$detail['cold'] = '非耐寒';
			}

			$detail['remain'] =  $detail['amount']; - $detail['hold'];
		}

		return $orderList;
	}

	public function matchQuery($series, $carType, $orderConfigId, $color, $coldResistant, $date) {

		if(empty($date)){
			$date = date('Y-m-d');
		}

		$conditions = array();
		$conditions['order'] = "status=1 AND standby_date='$date' AND order_config_id='$orderConfigId' AND hold<amount";
		$conditions['car'] = "series='$series' AND car_type='$carType' AND color='$color' AND cold_resistant='$coldResistant'";

		$condition = join(' AND ', $conditions);

		$sql = "SELECT id, standby_date, priority, amount, hold, count, series, car_type, color, car_year, cold_resistant, order_config_id, lane_id, carrier, order_number
				  FROM bms.order
				 WHERE $condition
			  ORDER BY priority ASC";

		$order = OrderAR::model()->findBySql($sql);

		return $order;
	}

	public function getLaneInfo(){
		$laneSql = "SELECT id,name FROM lane";
		$lanes = Yii::app()->db->createCommand($laneSql)->queryAll();
		$laneArray = array();
		$laneInfo = array();
		$totalToPrint = 0;
		foreach($lanes as $lane){
			$laneArray[$lane['id']] = $lane['name'];
			$countSum = 0;
			$amountSum = 0;
			$toPrint = 0;
			$sql = "SELECT id,amount,hold,count,lane_id,`status`,is_printed 
					FROM `order` 
					WHERE lane_id='{$lane['id']}' AND (`status`=1 OR `status`=2) AND is_printed=0";
			$orders = Yii::app()->db->createCommand($sql)->queryAll();
			foreach($orders as $order){
				$countSum += $order['count'];
				$amountSum += $order['amount'];
				if($order['count'] == $order['amount']){
					++$toPrint;
					++$totalToPrint;
				}
			}
			$laneInfo[$lane['id']] = array(
					'name' => $lane['name'],
					'toPrint' => $toPrint,
					'countSum' => $countSum,
					'amountSum' => $amountSum,
			);
		}

		return array('totalToPrint'=>$totalToPrint, 'laneInfo'=>$laneInfo);
	}

	public function queryByBoard($boardNumber){
		$sql = "SELECT board_number,id as order_id,lane_id, order_number, distributor_name, amount, hold, count, series, car_type, color, cold_resistant, order_config_id
				FROM `order`
				WHERE board_number='$boardNumber' AND (`status`=1 OR `status`=2) AND is_printed=0";
		$orders = Yii::app()->db->createCommand($sql)->queryAll();

		foreach($orders as &$order) {
			if(!empty($order['order_config_id'])){
				$order['order_config_name'] = OrderConfigAR::model()->findByPk($order['order_config_id'])->name;
			}
			$order['car_model'] = CarTypeMapAR::model()->find("car_type=?", array($order['car_type']))->car_model;
			
			$order['lane_name'] = '';
			$lane = LaneAR::model()->findByPk($order['lane_id']);
			if(!empty($lane)) $order['lane_name'] = $lane->name;
			if(!empty($order['order_config_name'])){
				$order['car_type_config'] = $order['car_model']. "/" . $order['order_config_name'];
			}else {
				$order['car_type_config'] = $order['car_model'];
			}
			if($order['cold_resistant'] == 1){
				$order['cold'] = '耐寒';
			} else {
				$order['cold'] = '非耐寒';
			}

			$order['remain'] = $order['amount']; - $order['hold'];
		}
		return $orders;
	}

	public function queryBoardInfo(){
		$boardArray = array();
		$boardInfo = array();
		$totalToPrint = 0;

		$sql = "SELECT board_number, id,amount,hold,count,lane_id,`status`,is_printed
				 FROM `order`
				 WHERE is_printed=0 AND (`status`=1 OR `status`=2)
				 ORDER BY board_number ASC";
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($orders as $order){
			if(!in_array($order['board_number'], $boardArray)){
				$boardArray[] = $order['board_number'];
				$boardInfo[$order['board_number']] = array(
					'toPrint' => 0,
					'countSum' => 0,
					'amountSum' => 0,
				);
			}
			$boardInfo[$order['board_number']]['countSum'] += $order['count'];
			$boardInfo[$order['board_number']]['amountSum'] += $order['amount'];
			if($order['count'] == $order['amount']){
				++$boardInfo[$order['board_number']]['toPrint'];
				++$totalToPrint;
			}
		}

		return array('boardArray'=>$boardArray, 'totalToPrint'=>$totalToPrint, 'boardInfo'=>$boardInfo);
	}

	public function queryCarsById($orderId){
		$sql = "SELECT id as car_id,vin, order_id, series, type, config_id, cold_resistant,color, `status`, distribute_time, engine_code 
				FROM car 
				WHERE order_id=$orderId";
		$cars = Yii::app()->db->createCommand($sql)->queryAll();
		$configName = $this->configNameList();
		foreach($cars as &$car){
			$car['type_config'] = $configName[$car['config_id']];
			$car['cold'] = self::$COLD_RESISTANT[$car['cold_resistant']];
		}
		return $cars;
	}

	public function getNameList ($carSeries, $carType) {
		$condition = "car_series=?";
		$values = array($carSeries);
		if(!empty($carType)) {
			$condition .= " AND car_type=?";
			$values[] = $carType;
		}
		$configs = OrderConfigAR::model()->findAll($condition . ' ORDER BY id ASC', $values);
		
		$datas = array();
		foreach($configs as $config) {
			$data['config_id'] = $config->id;
			$data['config_name']= $config->name;
			$datas[]=$data;
		}
		return $datas;
	}

	public function generateBoardNumber() {
		$date = strtotime(DateUtil::getCurDate());
		$year = date("Y", $date);
		$yearCode = CarYear::getYearCode($year);
		$monthDay = date("md", $date);
		$ret = $yearCode . $monthDay;

		$sql = "SELECT board_number FROM `order` WHERE board_number LIKE '$ret%' ORDER BY board_number DESC";
		$lastSerial = Yii::app()->db->createCommand($sql)->queryScalar();
		$lastKey = intval(substr($lastSerial, 5 , 3));
		
		$ret .= sprintf("%03d", (($lastKey + 1) % 1000));
		
		return $ret;
	}

	private function parseStatus($status) {
		if($status === 'all') {
            $status = array(0, 1, 2);
        } else {
            $status = explode(',', $status);
        }
		return $status;
	}

	private function configNameList(){
		$configName = array();
		$sql = "SELECT car_config_id, order_config_id , name , car_model FROM view_config_name";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($datas as $data){
			$configName[$data['car_config_id']] = $data['car_model'] . '/' . $data['name'];
		}
		return $configName;
	}

}
