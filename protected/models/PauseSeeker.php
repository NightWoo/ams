<?php
Yii::import('application.models.AR.monitor.LinePauseAR');
Yii::import('application.models.AR.NodeAR');
Yii::import('application.models.User');

class PauseSeeker
{
	public function __construct(){
	}
	
	public function query($startTime, $endTime, $section, $pauseType, $dutyDepartment, $curPage, $perPage, $orderBy) {
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
		}
		if(!empty($dutyDepartment)){
			$conditions[] = "duty_department = '$dutyDepartment'";
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
}
