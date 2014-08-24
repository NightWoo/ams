<?php
class SpsSeeker
{
	private $point = 'S1';
	public function __construct($point = 'S1') {
		$this->point = $point;
	}

	private static $POINT_FILED_MAP = array(
		'S1' => 's1',
		"S2" => 's2',
		"S3" => 's3',
		"frontBumper" => 'fb',
		"rearBumper" => 'rb'
	);


	//0 not print , 1 printed, 2 forbid print
	public function queryAll($vin = null, $status = 0, $stime = null, $etime = null, $top=0, $sortType="ASC") {
		$conditions = array("q.point='{$this->point}'");
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
			$conditions = array("q.point='{$this->point}'", "q.vin='$vin'");
		}
		$conditions[] = "q.car_id=c.id";
		$conditions[] = "c.config_id=cc.id";

		$condition = join(' AND ', $conditions);
		$sql = "SELECT c.serial_number,c.sps_serial,c.vin,c.series,c.status as car_status,c.type,c.cold_resistant,c.color,c.special_order,c.remark,cc.name as config_name,q.id,q.queue_time,q.status as sps_status,q.check_time FROM sps_queue q,car c,car_config cc WHERE $condition ORDER BY queue_time $sortType";
		if(!empty($top)){
			$sql .= " LIMIT 0,$top";
		}

		$datas = Yii::app()->db->createCommand($sql)->queryAll();


		//added by wujun
		foreach($datas as &$data){
			$data['type_name'] = $this->cutCarType($data['type']);
		}

		return $datas;
	}

	//0 not print , 1 printed, 2 forbid print
	public function queryAllPbs($vin = null, $status = 0, $stime = null, $etime = null, $top=0, $sortType="ASC") {
		$field = 'q.point_' . self::$POINT_FILED_MAP[$this->point];
		if($status != -1) {
			$conditions[] = "$field = $status";
		}
		if(!empty($stime)) {
			$conditions[] = "q.queue_time >= '$stime'";
		}
		if(!empty($etime)) {
			$conditions[] = "q.queue_time <= '$etime'";
		}
		if(!empty($vin)) {
			$conditions = array("q.vin='$vin'");
		}
		$conditions[] = "q.car_id=c.id";
		$conditions[] = "c.config_id=cc.id";

		$condition = join(' AND ', $conditions);
		$sql = "SELECT c.serial_number,q.id as sps_serial,c.vin,c.series,c.status as car_status,c.type,c.cold_resistant,c.color,c.special_order,c.remark,cc.name as config_name,q.id,q.queue_time, $field as sps_status,q.check_time FROM pbs_queue q,car c,car_config cc WHERE $condition ORDER BY q.id $sortType";
		if(!empty($top)){
			$sql .= " LIMIT 0,$top";
		}

		$datas = Yii::app()->db->createCommand($sql)->queryAll();


		//added by wujun
		foreach($datas as &$data){
			$data['type_name'] = $this->cutCarType($data['type']);
		}

		return $datas;
	}

	public function countQueuePbs($status = 0, $stime = null, $etime = null) {
		$field = 'q.point_' . self::$POINT_FILED_MAP[$this->point];
		if($status != -1) {
			$conditions[] = "$field = $status";
		}
		if(!empty($stime)) {
			$conditions[] = "q.queue_time >= '$stime'";
		}
		if(!empty($etime)) {
			$conditions[] = "q.queue_time <= '$etime'";
		}

		$condition = join(' AND ', $conditions);
		$countSql = "SELECT count(*) FROM pbs_queue q WHERE $condition";
		$count = Yii::app()->db->createCommand($countSql)->queryScalar();

		return $count;
	}

	public function countQueue($status = 0, $stime = null, $etime = null) {
		$conditions = array("q.point='{$this->point}'");
		if($status != -1) {
			$conditions[] = "q.status = $status";
		}
		if(!empty($stime)) {
			$conditions[] = "q.queue_time >= '$stime'";
		}
		if(!empty($etime)) {
			$conditions[] = "q.queue_time <= '$etime'";
		}
		$condition = join(' AND ', $conditions);
		$countSql = "SELECT count(*) FROM sps_queue q WHERE $condition";
		$count = Yii::app()->db->createCommand($countSql)->queryScalar();

		return $count;
	}

	public function validatePbs($vin) {
		$field = 'point_' . self::$POINT_FILED_MAP[$this->point];
		$sql = "SELECT $field FROM pbs_queue WHERE vin='$vin'";

		$exist = Yii::app()->db->createCommand($sql)->queryScalar();
		if(!is_bool($exist) && $exist == 0) {
			return;
		}
		if($exist == 1) {
			return;
		}
		if($exist == 2) {
			$info = array(1=> "已经",2=>"禁止");
			throw new Exception("$vin {$info[$exist]}分拣" );
		}
		throw new Exception("$vin 尚未进入分拣列队");
	}

	public function validate($vin) {
		$sql = "SELECT status FROM sps_queue WHERE vin='$vin' AND point='$this->point'";

		$exist = Yii::app()->db->createCommand($sql)->queryScalar();
		if(!is_bool($exist) && $exist == 0) {
			return;
		}
		if($exist == 1) {
			return;
		}
		if($exist == 2) {
			$info = array(1=> "已经",2=>"禁止");
			throw new Exception("$vin {$info[$exist]}分拣" );
		}
		throw new Exception("$vin 尚未进入分拣列队");
	}


	//added by wujun
	private function cutCarType($type) {
		$length = strlen($type);
        $typeName = '';
		$i = 0;
        while($i < $length){
            if($type[$i] === '(' || $i === stripos($type, '（')){
            	break;
            } else {
            	$typeName .= $type[$i];
            	$i++;
            }
        }
        if(empty($typeName)){
        	$typeName = $type;
        }

        return $typeName;
	}
}
