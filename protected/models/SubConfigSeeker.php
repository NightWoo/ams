<?php
class SubConfigSeeker
{
	private $type = 'subInstrument';
	public function __construct($type = 'subInstrument') {
		$this->type = $type;
	}


	//0 not print , 1 printed, 2 forbid print
	public function queryAll($status = 0, $stime = NULL, $etime = NULL) {
		$conditions = array("q.type='{$this->type}'");
		if($status != -1) {
			$condtions[] = "q.status = $status";
		}
		if(!empty($stime)) {
			$conditions[] = "q.queue_time >= '$stime'";
		}
		if(!empty($etime)) {
			$conditions[] = "q.queue_time <= '$etime'";
		}
		$conditions[] = "q.car_id=c.id";
		$conditions[] = "c.config_id=cc.id";

		$condition = join(' AND ', $conditions);
		$sql = "SELECT c.*,cc.name as config_name FROM sub_config_car_queue q,car c,car_config cc WHERE $condition ";


		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		return $datas;
	}
}
