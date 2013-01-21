<?php
Yii::import('application.models.AR.monitor.LinePauseAR');
Yii::import('application.models.AR.NodeAR');
Yii::import('application.models.User');

class PauseSeeker
{
	public function __construct(){
	}
	
	public function query($startTime, $endTime, $section, $causeType, $dutyDepartment, $pauseReason, $curPage, $perPage, $orderBy) {
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
		
		$limit = $perPage;
		$offset = ($curPage - 1) * $perPage;
		
		$sql = "SELECT * FROM pause WHERE $condition ORDER BY pause_time $orderBy LIMIT $offset,$limit";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		
		$countSql = "SELECT count(*) FROM pause WHERE $condition";
		$total = Yii::app()->db->createCommand($countSql)->queryScalar();
		
		foreach($datas as &$data) {
			$editor = User::model()->findByPk($data['editor']);
			if(!empty($editor)){
				$data['editor_name'] =	$editor->display_name;
			}
			$node = NodeAR::model()->findByPk($data['node_id']);
			if(!empty($node)){
				$data['node_name'] = $node->display_name; 
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

	public function queryDistribute($stime, $etime, $section, $causeType, $dutyDepartment, $pauseReason){
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
			// $node = NodeAR::model()->findByPk($data['node_id']);
			// if(!empty($node)){
			// 	$data['section'] = $node->section; 
			// }
			
			if(($data['recover_time'] == 0)){
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
		$cSeries = array();
		foreach ($causeTypeChartData as &$chartData) {
			$percentage = round($chartData['howlong'] / $sum, 3);
			$chartData['percentage'] = $percentage * 100 . "%";
			$cSeries[] = array($chartData['name'], $percentage);
			$howlong = $chartData['howlong'] ;
			$howlongMM = intval($howlong / 60);
			$howlongSS = intval($howlong % 60);
			$chartData['howlong'] = $howlongMM . '分' . sprintf("%02d", $howlongSS) . '秒';
		}

		$dSeries = array();
		foreach ($dutyDepartmentChartData as &$chartData) {
			$percentage = round($chartData['howlong'] / $sum, 3);
			$chartData['percentage'] = $percentage * 100 . "%";
			$dSeries[] = array($chartData['name'], $percentage);
			$howlong = $chartData['howlong'] ;
			$howlongMM = intval($howlong / 60);
			$howlongSS = intval($howlong % 60);
			$chartData['howlong'] = $howlongMM . '分' . sprintf("%02d", $howlongSS) . '秒';
		}

	return array(
			'cause_type_chart_data' => array('detail' => array_values($causeTypeChartData), 'series' => $cSeries),
			'duty_department_chart_data' => array('detail' => array_values($dutyDepartmentChartData), 'series' => $dSeries),
		);
	}

}
