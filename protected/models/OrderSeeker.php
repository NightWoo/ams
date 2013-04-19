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

	public function query($standbyDate, $orderNumber, $distributor, $status='all', $series='') {

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
		
		$sql = "SELECT id, order_number, priority, standby_date, amount, hold, count, series, car_type, color, cold_resistant, order_config_id, distributor_name, lane_id, remark, status FROM bms.order WHERE $condition ORDER BY lane_id, priority, `status` ASC";
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
			if($detail['cold_resistant'] == 0){
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

	private function parseStatus($status) {
		if($status === 'all') {
            $status = array(0, 1, 2);
        } else {
            $status = explode(',', $status);
        }
		return $status;
	}

}
