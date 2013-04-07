<?php
Yii::import('application.models.AR.NodeAR');
Yii::import('application.models.AR.NodeTraceAR');
Yii::import('application.models.AR.CarAR');
Yii::import('application.models.User');

class NodeSeeker
{
	public function __construct(){
	}

	public static $SECTION_NODEID_MAP = array(
					"PBS" => 1,
					"T0" =>2,
					"T1" =>3,
					"T2" =>4,
					"T3" =>5,
					"C1" =>6,
					"C2" =>7,
					"F1" =>8,
					"F2" =>9,
					"VQ1" =>10,
					"CHECK_IN" =>18,
					"CHECK_OUT" =>19
				  );
	
	public function queryTrace($stime, $etime, $series, $node, $curPage, $perPage) {
		list($stime, $etime) = $this->reviseSETime($stime, $etime);
		if(empty($node)){
			throw new Exception("车辆明细查询必须选择节点", 1);
		} else {
			$nodeId = self::$SECTION_NODEID_MAP[$node];
		}

        $sql = "SELECT id,display_name FROM user";
        $users = Yii::app()->db->createCommand($sql)->queryAll();
        $userInfos = array();
        foreach($users as $user) {
            $userInfos[$user['id']] = $user['display_name'];
        }

        $sql = "SELECT id,name FROM car_config";
        $configs = Yii::app()->db->createCommand($sql)->queryAll();
        $configInfos = array();
        foreach($configs as $config) {
            $configInfos[$config['id']] = $config['name'];
        }

        $sql = "SELECT id,display_name FROM node";
        $nodes = Yii::app()->db->createCommand($sql)->queryAll();
        $nodeInfos = array();
        foreach($nodes as $node) {
            $nodeInfos[$node['id']] = $node['display_name'];
        }

		$conditions = array("node_id=$nodeId");

		if(!empty($stime)) {
            $conditions[] = "pass_time >= '$stime'";
        }
        if(!empty($etime)) {
            $conditions[] = "pass_time <= '$etime'";
        }

        if(!empty($series)){
	        $arraySeries = $this->parseSeries($series);
	        $cTmp = array(); 
	        foreach($arraySeries as $series){
	        	$cTmp[] = "car_series='$series'";
	        }
	        $conditions[] = "(" . join(' OR ', $cTmp) . ")";
        };

        $condition = join(' AND ', $conditions);

        $limit = "";
        if(!empty($perPage)) {
            $offset = ($curPage - 1) * $perPage;
            $limit = "LIMIT $offset, $perPage";
        }

        $dataSql = "SELECT n.node_id, n.car_id, n.user_id, n.pass_time, c.vin, c.series, c.serial_number, c.type, c.color, c.config_id, c.remark, c.status, c.cold_resistant, c.special_order, c.distributor_name
        		FROM node_trace AS n 
        		LEFT JOIN car AS c
        		ON n.car_id=c.id
        		WHERE $condition
        		ORDER BY n.pass_time DESC
        		$limit";

        $datas = Yii::app()->db->createCommand($dataSql)->queryAll();
        foreach($datas as &$data){
        	if($data['series'] == '6B') $data['series'] = '思锐';

        	if($data['cold_resistant'] == 1){
        		$data['cold_resistant'] = '耐寒';
        	} else {
        		$data['cold_resistant'] = '非耐寒';
        	}

        	if(!empty($data['user_id'])){
        		$data['user_name'] = $userInfos[$data['user_id']];
        	} else {
        		$data['user_name'] = '-';
        	}
        	if(!empty($data['driver_id'])) {
				$data['driver_name'] = $userInfos[$data['driver_id']];
			} else {
				$data['driver_name'] = $data['user_name'];
			}

        	if(!empty($data['config_id'])){
        		$data['config_name'] = $configInfos[$data['config_id']];
        	} else {
        		$data['config_name'] = '-';
        	}
        	$data['node_name'] = $nodeInfos[$data['node_id']];
        }

        $countSql = "SELECT count(id) FROM node_trace WHERE $condition";
        $total = Yii::app()->db->createCommand($countSql)->queryScalar();

		return array($total, $datas);
	}

	private function reviseSETime($stime,$etime) {
		$s = strtotime($stime);
		$e = strtotime($etime);
	
		$sd = date('Ymd', $s);
		$ed = date('Ymd', $e);
		
		$sm = date('m', $s);
		$em = date('m', $e);

		$lastHour = ($e - $s) / 3600;
		$lastDay = (strtotime($ed) - strtotime($sd)) / 86400;//days

		$ret = array();
		if($lastHour <= 24) {//hour
			$format = 'Y-m-d H';
			$stime = date($format, $s) . ":00:00";
			$eNextH = strtotime('+1 hour', $e);
			$etime = date($format, $eNextH) . ":00:00";
		} elseif($lastDay <= 31) {//day
			$format = 'Y-m-d';
			//$stime = date($format, $s) . " 00:00:00";				
			//$etime = date($format, $e) . " 23:59:59";
			$stime = date($format, $s) . " 08:00:00";								//added by wujun
			$eNextD = strtotime('+1 day', $e);		//next day						//added by wujun
			$etime = date($format, $eNextD) . " 07:59:59";	//befor next workday	//added by wujun
		} else {//month
			$format = 'Y-m';
			//$stime = date($format, $s) . "-01 00:00:00";
			//$etime = date('Y-m-t', $e) . " 23:59:59";
			$stime = date($format, $s) . "-01 08:00:00";	//firstday				//added by wujun
			$eNextM = strtotime('+1 month', $e);			//next month			//added by wujun
			$etime = date('Y-m', $eNextM) . "-01 07:59:59";	//next month firstday	//added by wujun
		}


		return array($stime, $etime);
	}

	private function parseSeries($series) {
		if(empty($series) || $series === 'all') {
            $series = array('F0', 'M6', '6B');
        } else {
            $series = explode(',', $series);
        }
		return $series;
	}
}
