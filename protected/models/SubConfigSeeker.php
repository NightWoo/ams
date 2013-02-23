<?php
class SubConfigSeeker
{
	private $type = 'subInstrument';
	public function __construct($type = 'subInstrument') {
		$this->type = $type;
	}


	//0 not print , 1 printed, 2 forbid print
	public function queryAll($vin = null, $status = 0, $stime = null, $etime = null) {
		$conditions = array("q.type='{$this->type}'");
		if($status != -1) {
			$conditions[] = "q.status = $status";
		}
		if(!empty($stime)) {
			$conditions[] = "q.queue_time >= '$stime'";
		}
		if(!empty($etime)) {
			$conditions[] = "q.queue_time <= '$etime'";
		}
		if(!empty($vin)) {
			$conditions = array("q.type='{$this->type}'", "q.vin='$vin'");
		}
		$conditions[] = "q.car_id=c.id";
		$conditions[] = "c.config_id=cc.id";

		$condition = join(' AND ', $conditions);
		$sql = "SELECT c.serial_number,c.vin,c.series,c.type,c.cold_resistant,c.color,c.special_order,c.remark,cc.name as config_name,q.id,q.queue_time as queueTime,q.status FROM sub_config_car_queue q,car c,car_config cc WHERE $condition ";


		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		return $datas;
	}

	public function validate($vin) {
		$sql = "SELECT count(*) FROM sub_config_car_queue WHERE vin='$vin' AND status=0 AND type='$this->type'";


		$exist = Yii::app()->db->createCommand($sql)->queryScalar();

		if($exist == 0) {
			throw new Exception("$vin 不存在或者已经被打印" );
		}
	}
}
