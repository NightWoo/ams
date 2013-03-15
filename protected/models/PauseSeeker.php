<?php
Yii::import('application.models.AR.monitor.LinePauseAR');
Yii::import('application.models.AR.NodeAR');
Yii::import('application.models.User');

class PauseSeeker
{
	public function __construct(){
	}
	
	public function query($startTime, $endTime, $section, $pauseType, $causeType, $dutyDepartment, $pauseReason, $curPage, $perPage, $orderBy) {
		$conditions = array();
		if(!empty($startTime)){
			$conditions[] = "pause_time >=	'$startTime'";
		}
		if(!empty($endTime)){
			$conditions[] = "pause_time <=	'$endTime'";
		}
		if(!empty($section)){
			$sql = "SELECT id FROM node WHERE section='$section'";
			$nodeIds = Yii::app()->db->createCommand($sql)->queryColumn();
			if(empty($nodeIds)) {
				return 0;	
			}
			$nodeIdStr = join(',', $nodeIds);
			$conditions[] = "node_id IN ($nodeIdStr)";
		}
		if(!empty($pauseType)){
			$conditions[] = "pause_type = '$pauseType'";
		} else {
			$conditions[] ="(pause_type = '紧急停止' OR pause_type = '设备故障' OR pause_type = '质量关卡' OR pause_type = '工位呼叫')";
		}
		if(!empty($causeType)){
			$conditions[] = "cause_type = '$causeType'";
		}
		if(!empty($dutyDepartment)){
			$conditions[] = "duty_department = '$dutyDepartment'";
		}
		if(!empty($pauseReason)){
			$conditions[] = "remark LIKE '%$pauseReason%'";
		}
		
		$condition = join(' AND ', $conditions);
		
		// $limit = $perPage;
		// $offset = ($curPage - 1) * $perPage;

		$limit = "";
        if(!empty($perPage)) {
            $offset = ($curPage - 1) * $perPage;
            $limit = "LIMIT $offset, $perPage";
        }
		
		$sql = "SELECT * FROM pause WHERE $condition ORDER BY pause_time $orderBy $limit";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		
		$countSql = "SELECT count(*) FROM pause WHERE $condition";
		$total = Yii::app()->db->createCommand($countSql)->queryScalar();
		
		foreach($datas as &$data) {
			$editor = User::model()->findByPk($data['editor']);
			if(!empty($editor)){
				$data['editor_name'] =	$editor->display_name;
			}else{
				$data['editor_name'] =	'-';
			}
			$node = NodeAR::model()->findByPk($data['node_id']);
			if(!empty($node)){
				$data['node_name'] = $node->display_name; 
			}else{
				$data['node_name'] = '-'; 
			}
			
			if(($data['recover_time'] == 0)){
				//$howlong = (gettimeofday("YmdHis") - strtotime($data['pause_time'])) / 60;
				//$data['howlong'] = intval($howlong);
				$howlong = (gettimeofday("YmdHis") - strtotime($data['pause_time']));
				$howlongMM = intval($howlong / 60);
				$howlongSS = intval($howlong % 60);
				$data['howlong'] = $howlongMM . '分' . sprintf("%02d", $howlongSS) . '秒';
			}else {
				//$howlong = (strtotime($data['recover_time']) - strtotime($data['pause_time'])) / 60;
				//$data['howlong'] = intval($howlong);
				$howlong = (strtotime($data['recover_time']) - strtotime($data['pause_time']));
				$data['howlong'] = intval($howlong);
				$howlongMM = intval($howlong / 60);
				$howlongSS = intval($howlong % 60);
				$data['howlong'] = $howlongMM . '分' . sprintf("%02d", $howlongSS) . '秒';
			}
		}
		
		return array($total, $datas);
	}

	public function queryDistribute($stime, $etime, $section, $pauseType, $causeType,  $dutyDepartment, $pauseReason){
		$conditions = array();
		if(!empty($stime)){
			$conditions[] = "pause_time >=	'$stime'";
		}
		if(!empty($etime)){
			$conditions[] = "pause_time <=	'$etime'";
		}
		if(!empty($section)){
			$sql = "SELECT id FROM node WHERE section='$section'";
			$nodeIds = Yii::app()->db->createCommand($sql)->queryColumn();
			if(empty($nodeIds)) {
				return 0;	
			}
			$nodeIdStr = join(',', $nodeIds);
			$conditions[] = "node_id IN ($nodeIdStr)";
		}
		if(!empty($pauseType)){
			$conditions[] = "pause_type = '$pauseType'";
		} else {
			$conditions[] ="(pause_type = '紧急停止' OR pause_type = '设备故障' OR pause_type = '质量关卡' OR pause_type = '工位呼叫')";
		}

		if(!empty($causeType)){
			$conditions[] = "cause_type = '$causeType'";
		}
		if(!empty($dutyDepartment)){
			$conditions[] = "duty_department = '$dutyDepartment'";
		}
		if(!empty($pauseReason)){
			$conditions[] = "remark LIKE '%$pauseReason%'";
		}
		
		$condition = join(' AND ', $conditions);

		$dataSql = "SELECT id, node_id, cause_type, duty_department, pause_time, recover_time FROM pause WHERE $condition";

		$datas = Yii::app()->db->createCommand($dataSql)->queryAll();

		$sum = 0;
		foreach($datas as &$data) {
			if(empty($data['recover_time'])){
				$data['howlong'] = (strtotime($etime) - strtotime($data['pause_time']));
			}else {
				//$howlong = (strtotime($data['recover_time']) - strtotime($data['pause_time'])) / 60;
				//$data['howlong'] = intval($howlong);
				$data['howlong'] = (strtotime($data['recover_time']) - strtotime($data['pause_time']));
			}
			$sum += $data['howlong'];
		}
		$causeTypeChartData = array();
		$dutyDepartmentChartData = array();
		foreach($datas as &$data) {
			if(empty($causeTypeChartData[$data['cause_type']])) {
				$causeTypeChartData[$data['cause_type']] = array(
					'name' => $data['cause_type'],
					'howlong' => 0,
				);
			}

			if(empty($dutyDepartmentChartData[$data['duty_department']])) {
				$dutyDepartmentChartData[$data['duty_department']] = array(
					'name' => $data['duty_department'],
					'howlong' => 0,
				);
			}
			$causeTypeChartData[$data['cause_type']]['howlong'] += $data['howlong'];
			$dutyDepartmentChartData[$data['duty_department']]['howlong'] += $data['howlong'];
		}

		ksort($dutyDepartmentChartData);
		$dutyChartData = array_values($dutyDepartmentChartData);
		$departments = count($dutyChartData);
		$temps = array();

		for($i = 0; $i < $departments; $i++) {
			$max = 1 - PHP_INT_MAX;
			$curIndex = -1;
			for($j = 0; $j < $departments; $j++){
				if(empty($dutyChartData[$j]['sorted'])){
					if($max < $dutyChartData[$j]['howlong']){
						$max = $dutyChartData[$j]['howlong'];
						$curIndex = $j;
					}
				}
			}
			$temps[] = $dutyChartData[$curIndex];
			$dutyChartData[$curIndex]['sorted'] = true;
		}
		$key = 0;
		$dSeriesX = array();
		$dSeriesY = array();
		$dColumnY = array();
		$dSeriesP = array();
		$pn = 0;
		$detail = array();
		$totalDepartments = 0;
		foreach($temps as $temp) {
			if($key / $sum < 1) {
				$temp['percentage'] = round($temp['howlong'] / $sum, 3);
				$key += $temp['howlong'];

				$pn += $temp['percentage'];
				$dColumnY[] = $temp['howlong'];
				$dSeriesY[] = $pn;
				$dSeriesP[] = $temp['percentage'];
				$dSeriesX[] = $temp['name'];

				$temp['percentage'] = $temp['percentage'] * 100 . "%";
				$detail['dutyDepartment'] = $temp;
				++ $totalDepartments;
				if($totalDepartments >= 15) {
					break;
				}
			}
		}

		$cSeries = array();
		$detail['causeType'] = array_values($causeTypeChartData);
		foreach ($causeTypeChartData as &$chartData) {
			$percentage = round($chartData['howlong'] / $sum, 3);
			$chartData['percentage'] = $percentage * 100 . "%";
			$cSeries[] = array('name'=>$chartData['name'],'y'=>$percentage);
			$howlong = $chartData['howlong'] ;
			$howlongMM = intval($howlong / 60);
			$howlongSS = intval($howlong % 60);
			$chartData['howlong'] = $howlongMM . '分' . sprintf("%02d", $howlongSS) . '秒';
		}

		return array(
				'detail'=> $detail, 
				'series'=> array(
							'x' => $dSeriesX,
							'y' => $dSeriesY,
							'p' => $dSeriesP,
							'column' => $dColumnY,
							'cSeries' => $cSeries,			
						   ),
			   );
	}

	public function queryUseRate($sTime, $eTime, $line) {
		$curDate = DateUtil::getCurDate();
		$sDate = date('Ymd', strtotime($sTime));
		$eDate = date('Ymd', strtotime($eTime));
		if(strtotime($eDate) >= strtotime($curDate)){
			$eDate = $curDate;
		}

		$queryTimes = $this->parseQueryTime($sDate, $eDate);
		$arrayShift = array('白班','夜班');
		$detail = array();
		$dataSeriesX = array();
		$dataSeriesY = array();
		foreach($arrayShift as $shift){
			$capacityTotal[$shift] = 0;
			$productionTotal[$shift] = 0;
		}

		foreach($queryTimes as $queryTime){
			$ss = $queryTime['stime'];
			$ee = $queryTime['etime'];
			$cc = array("line='$line'");
			if(!empty($ss)) {
				$cc[] = "shift_date>='$ss'";
			}
			if(!empty($ee)) {
				$cc[] = "shift_date<'$ee'";
			}
			$con = join(' AND ', $cc);
			if(!empty($con)) {
				$con = 'WHERE ' . $con;
			}

			$temp = array();
			foreach($arrayShift as $shift){
				if($shift === '白班'){
					$condition = $con . " AND shift=0";
				} else if ($shift === '夜班') {
					$condition = $con . " AND shift=1";
				}
				$sql = "SELECT line_speed, start_time, end_time FROM shift_record $condition";
				$datas = Yii::app()->db->createCommand($sql)->queryAll();

				$capacity = 0;
				$production = 0;
				
				foreach($datas as &$data){
					$runTime = strtotime($data['end_time']) - strtotime($data['start_time']) - 7199;
					$linePauses = LinePauseAR::model()->findAll("pause_time>=? AND pause_time<=? AND pause_type=?" , array($data['start_time'], $data['end_time'], '计划停线'));
					$planPauseTime = 0;
					foreach($linePauses as $linePause) {
						if($linePause->status == 1) {
							$planPauseTime += (time() - strtotime($linePause->pause_time));
						} else {
							$planPauseTime += (strtotime($linePause->recover_time) - strtotime($linePause->pause_time));
						}
					}
					$runTime = $runTime - $planPauseTime;
					$capacity += intval($runTime / $data['line_speed']);
					$production += $this->queryFinishCars($data['start_time'], $data['end_time'], 2);

					$capacityTotal[$shift] += $capacity;
					$productionTotal[$shift] += $production;
				}
				$rate = empty($capacity) ? null : round($production / $capacity, 2);
				$temp[$shift] = array(
									'production' => $production,
									'capacity' => $capacity,
									'rate' => empty($capacity) ? '-' : round($production / $capacity, 2) * 100 . '%',
								);
				$dataSeriesY[$shift][] = $rate;
			}
			$detail[] = array_merge(array('time' => $queryTime['point']), $temp);
			$dataSeriesX[] = $queryTime['point'];
		}

		//合计
		$retTotal = array();
		foreach($arrayShift as $shift) {
			$retTotal[] = array(
							'shift' => $shift,
							'productionTotal' => $productionTotal[$shift],
							'capacityTotal' => $capacityTotal[$shift],
							'rateTotal' =>  empty($capacityTotal[$shift]) ? '-' : round($productionTotal[$shift] / $capacityTotal[$shift], 2) * 100 . '%'
						);
		}

		return array(
					'shift' => $arrayShift,
					'detail' => $detail,
					'total' => $retTotal,
					'series' => array('x' => $dataSeriesX, 'y' => $dataSeriesY)
				);

	}

	

	public function queryFinishCars($stime, $etime, $nodeId) {
		//$sql = "SELECT id FROM node WHERE name='$node'";
        //$nodeId = Yii::app()->db->createCommand($sql)->queryScalar();
        $sql = "SELECT count(distinct car_id) FROM node_trace WHERE pass_time>'$stime' AND pass_time < '$etime' AND node_id=$nodeId";
        $finishCars = Yii::app()->db->createCommand($sql)->queryScalar();
        return $finishCars;
	}

	private function parseQueryTime($stime,$etime) {

		$format = 'Y-m-d';
		$stime = date($format, strtotime($stime));
		$etime = date($format, strtotime($etime));

		$s = strtotime($stime);
		$e = strtotime($etime);

		$lastDay = (strtotime($etime) - strtotime($stime)) / 86400;//days

		$ret = array();
		if($lastDay <= 31) {
			$pointFormat = 'm-d';
		} else {	
			$format = 'Y-m';
			$stime = date($format, $s);
			$etime = date($format, $e);
			$pointFormat = 'Y-m';
		}
		
		$t = $s;
		while($t <= $e) {
			
			$point = date($pointFormat, $t);

			if($pointFormat === 'm-d'){
				$nextD = strtotime('+1 day', $t);
				$ret[] = array(
					'stime' => date($format, $t),
					'etime' => date($format, $nextD),
					'point' => $point,
				);
				$t = $nextD;	
			} else {
				$nextM = strtotime('+1 month', $t);
				$ret[] = array(
					'stime' => date($format, $t),
					'etime' => date($format, $nextM),
					'point' => $point,
				);
				$t = $nextM;
			}		
		}

		return $ret;
	}
}
