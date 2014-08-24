<?php
class SubConfigSeeker
{
	private $type = 'subInstrument';
	public function __construct($type = 'subInstrument') {
		$this->type = $type;
	}


	//0 not print , 1 printed, 2 forbid print
	public function queryAll($vin = null, $status = 0, $stime = null, $etime = null, $top=0, $sortType="ASC", $closed=0) {
		$conditions = array("q.type='{$this->type}'");
		if($status != -1) {
			$conditions[] = "q.status = $status";
		}
		if($closed != -1) {
			$conditions[] = "q.closed = $closed";
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
		$sql = "SELECT c.serial_number,c.vin,c.series,c.type,c.cold_resistant,c.color,c.special_order,c.remark,cc.name as config_name,q.id,q.queue_time as queueTime,q.status,c.engine_code FROM sub_config_car_queue q,car c,car_config cc WHERE $condition ORDER BY queue_time $sortType";
		if(!empty($top)){
			$sql .= " LIMIT 0,$top";
		}

		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		//added by wujun
		foreach($datas as &$data){
			$data['type_name'] = $this->cutCarType($data['type']);
			$data['cold'] = $data['cold_resistant'] == 0 ? "非耐寒" : "耐寒";
		}

		return $datas;
	}

	public function countQueue($status = 0, $stime = null, $etime = null) {
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
		$condition = join(' AND ', $conditions);
		$countSql = "SELECT count(*) FROM sub_config_car_queue q WHERE $condition";
		$count = Yii::app()->db->createCommand($countSql)->queryScalar();

		return $count;
	}

	public function validate($vin) {
		$sql = "SELECT status FROM sub_config_car_queue WHERE vin='$vin' AND type='$this->type'";


		$exist = Yii::app()->db->createCommand($sql)->queryScalar();
		if(!is_bool($exist) && $exist == 0) {
			return;
		}
		if($exist == 1) {
			return;
		}
		if($exist == 2) {
			$info = array(1=> "已经被",2=>"禁止");
			throw new Exception("$vin 配置{$info[$exist]}打印" );
		}
		throw new Exception("$vin 不存在待打印配置");
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
