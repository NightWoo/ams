<?php
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.OrderConfigAR');
Yii::import('application.models.AR.DistributorAR');
Yii::import('application.models.Order');

class OrderSeeker
{
	public function __construct(){
	}

	public function searchByDate($standbyDate, $status=0) {
		if(empty($standbyDate)) {
			$standbyDate = DateUtil::getCurDate();			
		}

		$datetime = strtotime($standbyDate);
		$date = date('Y-m-d', $datetime);
		
		//use SQL
		$condition = "standby_date='$date'";
		if($status == 1) {
			$condition .= " AND status=$status";
		}
		$sql = "SELECT id, order_number, priority, standby_date, amount, hold, count, series, car_type, color, cold_resistant, order_config_id, distributor_id, order_type, lane, city, carrier, remark, status FROM bms.order WHERE $condition ORDER BY priority ASC";
		$orderList = Yii::app()->db->createCommand($sql)->queryAll();
		
		foreach($orderList as &$detail) {
			$detail['order_config_name'] = OrderConfigAR::model()->findByPk($detail['order_config_id'])->name;
			$detail['distributor_name'] = DistributorAR::model()->findByPk($detail['distributor_id'])->display_name;
			$amout= $detail['amount'];
			$hold = $detail['hold'];

			$detail['remain'] =  $amout - $hold;
		}

		return $orderList;

		//use AR
		/* 
		$condition = "standby_date=?";
		$values = array($date);
		$orderList = OrderAR::model()->findAll($condition . ' ORDER BY priority ASC', $values);
		$datas = array();
		foreach($orderList as $detail) {
			$temp = $detail->getAttributes();
			$temp['config_name'] = CarConfigAR::model()->findByPk($detail['config_id'])->name;
			$temp['distributor_name'] = DistributorAR::model()->findByPk($detail['distributor_id'])->display_name;
			$datas[] = $temp;
		}
		return $datas;
		*/
	}

	public function matchQuery($series, $carType, $orderConfigId, $color, $coldResistant, $date) {

		if(empty($date)){
			$date = date('Y-m-d');
		}

		$conditions = array();
		$conditions['order'] = "status=1 AND standby_date='$date' AND order_config_id='$orderConfigId' AND hold<amount";
		$conditions['car'] = "series='$series' AND car_type='$carType' AND color='$color' AND cold_resistant='$coldResistant'";

		$condition = join(' AND ', $conditions);

		$sql = "SELECT id, standby_date, priority, amount, hold, series, car_type, color, car_year, cold_resistant, order_config_id, lane, carrier, order_number
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

}
