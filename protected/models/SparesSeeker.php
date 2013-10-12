<?php
Yii::import('application.models.Component');
Yii::import('application.models.AR.ComponentAR');
Yii::import('application.models.AR.NodeTraceAR');

class SparesSeeker 
{
	public function __construct(){
	}

	private static $SERIES_NAME = array('F0'=>'F0','M6'=>'M6','6B'=>'思锐');

	public function querySparesTrace ($traceId) {
		if(empty($traceId)) {
			throw new Exception ('node_trace_id can not be null');
		}

		$sql = "SELECT node_trace_id,vin, component_id, component_name,component_code, provider_name, bar_code, is_collateral
				FROM view_spare_replacement WHERE node_trace_id = $traceId";
		$data = Yii::app()->db->createCommand($sql)->queryAll();

		return $data;
	}

	public function queryReplacementDetail ($stime, $etime, $series, $line, $dutyId, $curPage=0, $perPage=0) {
		if(empty($stime) || empty($etime)) {
			throw new Exception("查询起止均时间不可为空");
		}

		$conditions = array("replace_time>='$stime'","replace_time<'$etime'");
		if(!empty($series)) {
			$arraySeries = $this->parseSeries($series);
			foreach($arraySeries as $series){
	        	$cTmp[] = "series='$series'";
	        }
	        $conditions[] = "(" . join(' OR ', $cTmp) . ")";
		}
		if(!empty($line)) {
			$conditions[] = "assembly_line='$line'";
		}
		if(!empty($dutyId)) {
			$conditions[] = "duty_department_id=$dutyId";
		}
		$condition = join(" AND ", $conditions);

		$limit = "";
        if(!empty($perPage)) {
            $offset = ($curPage - 1) * $perPage;
            $limit = "LIMIT $offset, $perPage";
        }

        $dataSql = "SELECT assembly_line, series, vin, component_code, sap_code, component_name, provider_name,provider_code,factory_code, bar_code, is_collateral,treatment, unit_price, duty_department_name, fault_component_name, fault_mode, duty_area, replace_time, handler
        			FROM view_spare_replacement
        			WHERE $condition
        			ORDER BY replace_time ASC
        			$limit";
        $datas = Yii::app()->db->createCommand($dataSql)->queryAll();

        $countSql = "SELECT COUNT(*) FROM view_spare_replacement WHERE $condition";
        $total = Yii::app()->db->createCommand($countSql)->queryScalar();

        return array($total, $datas);
	}

	public function queryCostTrend ($stime, $etime, $series, $line, $dutyId) {
		if(empty($stime) || empty($etime)) {
			throw new Exception("查询起止均时间不可为空");
		}
		$conditions = array();
		if(!empty($line)) {
			$conditions[] = "assembly_line='$line'";
		}
		if(!empty($dutyId)) {
			$conditions[] = "duty_department_id=$dutyId";
		}
		$condition = join(" AND ", $conditions);

		$arraySeries = $this->parseSeries($series);

		$queryTimes = $this->parseQueryTime($stime,$etime);
		$ret = array();
		$dataSeriesX = array();
		$dataSeriesY = array();
		$retTotal = array();
		foreach($arraySeries as $series) {
			$retTotal[self::$SERIES_NAME[$series]] = 0;
		}

		foreach($queryTimes as $queryTime) {
			$st = $queryTime['stime'];
			$et = $queryTime['etime'];
			$temp = array();
			foreach($arraySeries as $series) {
				$curCondition = "replace_time>='$st' AND replace_time<'$et' AND series='$series'";
				$curCondition = empty($condition) ? $curCondition : $curCondition . " AND " . $condition;
				$sql = "SELECT SUM(unit_price) FROM view_spare_replacement WHERE $curCondition";
				$sum = Yii::app()->db->createCommand($sql)->queryScalar();

				$carSql = "SELECT COUNT(*) FROM car WHERE assembly_time>='$st' AND assembly_time<'$et' AND series='$series'";
				if(!empty($line)) $carSql .= "AND assembly_line='$line'";
				$cars = Yii::app()->db->createCommand($carSql)->queryScalar();

				$temp[self::$SERIES_NAME[$series]] = empty($cars) ? "0.00" : sprintf("%.2f", round($sum/$cars, 2)) ;
				$dataSeriesY[self::$SERIES_NAME[$series]][] = empty($cars) ? null : round($sum/$cars, 2);;
				// $temp[self::$SERIES_NAME[$series]] = $sum;
				// $dataSeriesY[self::$SERIES_NAME[$series]][] = empty($sum) ? null : round($sum,2);

			}
			$ret[] = array_merge(array('time'=>$queryTime['point']), $temp);
			$dataSeriesX[] = $queryTime['point'];
		}

		foreach($arraySeries as $series) {
			$totalCondition = "replace_time>='$stime' AND replace_time<'$etime' AND series='$series'";
			$totalCondition = empty($condition) ? $totalCondition : $totalCondition . " AND " . $condition;
			$totalSql = "SELECT SUM(unit_price) FROM view_spare_replacement WHERE $totalCondition";
			$sumTotal = Yii::app()->db->createCommand($totalSql)->queryScalar();
			$retTotal[self::$SERIES_NAME[$series]] = sprintf("%.2f", round($sumTotal, 2));
			$carSeries[] = self::$SERIES_NAME[$series];
		}

		return array(
			'carSeries'=>$carSeries, 
			'detail'=>$ret, 
			'total'=>$retTotal, 
			'series'=> array('x'=>$dataSeriesX, 'y'=>$dataSeriesY)
		);
	}

	public function queryCostDuty ($stime, $etime, $series, $line, $dutyId) {
		if(empty($stime) || empty($etime)) {
			throw new Exception("查询起止均时间不可为空");
		}

		$conditions = array("replace_time>='$stime'","replace_time<'$etime'");
		$carConditions = array("assembly_time>='$stime'", "assembly_time<'$etime'");
		if(!empty($series)) {
			$arraySeries = $this->parseSeries($series);
			foreach($arraySeries as $series){
	        	$cTmp[] = "series='$series'";
	        }
	        $seriesText = "(" . join(' OR ', $cTmp) . ")";
	        $conditions[] = $seriesText;
	        $carConditions[] = $seriesText;
		}
		if(!empty($line)) {
			$conditions[] = "assembly_line='$line'";
			$carConditions[] = "assembly_line='$line'";
		}
		if(!empty($dutyId)) {
			$conditions[] = "duty_department_id=$dutyId";
		}

		$condition = join(" AND ", $conditions);
		$dataSql = "SELECT duty_department_name as duty,unit_price,duty_area,treatment FROM view_spare_replacement WHERE $condition";
		$datas = Yii::app()->db->createCommand($dataSql)->queryAll();

		$totalSql = "SELECT SUM(unit_price) FROM view_spare_replacement WHERE $condition";
		$sumTotal = Yii::app()->db->createCommand($totalSql)->queryScalar();

		$carCondition = join(" AND ", $carConditions);
		$carsSql = "SELECT COUNT(*) FROM car WHERE $carCondition";
		$cars = Yii::app()->db->createCommand($carsSql)->queryScalar();

		$dutyChartDataTmp = array();
		$areaChartData = array();
		$treatmentData = array();
		foreach($datas as &$data) {
			if(empty($dutyChartDataTmp[$data['duty']])) {
				$dutyChartDataTmp[$data['duty']] = array('name'=>$data['duty'], 'sum'=>0);
			}
			if(empty($areaChartData[$data['duty_area']])) {
				$areaChartData[$data['duty_area']] = array('name'=>$data['duty_area'], 'sum'=>0);
			}
			// if(empty($treatmentData[$data['duty']])) {
			// 	$treatmentData[$data['duty']] = array('name'=>$data['treatment'], 'sum'=>0);
			// }

			$dutyChartDataTmp[$data['duty']]['sum'] += $data['unit_price'];
			$areaChartData[$data['duty_area']]['sum'] += $data['unit_price'];
			// $treatmentData[$data['duty']]['sum'] += $data['unit_price'];
		}

		$dutyChartData = $this->multi_array_sort($dutyChartDataTmp,'sum', SORT_DESC);
		$dSeriesX = array();
		$dSeriesY = array();
		$dColumnY = array();
		$dSeriesP = array();
		$detail = array();
		$sumSum = 0;
		foreach($dutyChartData as &$data){
			$percentage = empty($sumTotal) ? null : round($data['sum'] / $sumTotal, 3);
			$sumSum += $data['sum'];
			$dColumnY[] = $data['sum'];
			$dSeriesY[] = empty($sumTotal) ? null : round($sumSum / $sumTotal, 3);
			$dSeriesP[] = $percentage;
			$dSeriesX[] = $data['name'];
			$data['percentage'] = empty($percentage) ? 0 : $percentage * 100 ."%";
			$data['unitCost'] = empty($cars) ? 0 : sprintf("%.2f", round($data['sum'] / intval($cars), 2));
			$detail['dutyDepartment'][] = $data;
		}

		$cSeries = array();
		foreach($areaChartData as &$data) {
			$percentage = empty($sumTotal) ? null : round($data['sum'] / $sumTotal, 3);
			$cSeries[] = array('name'=>$data['name'],'y'=>$percentage);
			$data['percentage'] = empty($percentage) ? 0 : $percentage * 100 ."%";
		}
		$detail['dutyArea'] = array_values($areaChartData);

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

	public function queryUnitCost ($stime, $etime, $series='', $line='') {
		$seriesArray = $this->parseSeries($series);
		$costSql = "SELECT SUM(unit_price) FROM view_spare_replacement WHERE replace_time>='$stime' AND replace_time<'$etime'";
		$carSql = "SELECT COUNT(*) FROM car WHERE assembly_time>='$stime' AND assembly_time<'$etime'";
		if(!empty($series)) {
			$costSql .= " AND series='$series'";
			$carSql .= " AND series='$series'";
		}
		if(!empty($line)) {
			$costSql .= "AND assembly_line='$line'";
			$carSql .= "AND assembly_line='$line'";
		}
		$cost = Yii::app()->db->createCommand($costSql)->queryScalar();
		$cars = Yii::app()->db->createCommand($carSql)->queryScalar();

		$unitCost = empty($cars) ? 0 : round($cost/$cars, 2);
		return $unitCost;
	}

	public function getHandlerTeams () {
		$sql = "SELECT DISTINCT team FROM replacement_handler";
		$teams = Yii::app()->db->createCommand($sql)->queryAll();
		return $teams;
	}

	public function getHandlers ($team) {
		$sql = "SELECT team,handler_name FROM replacement_handler WHERE team='$team'";
		$names = Yii::app()->db->createCommand($sql)->queryAll();
		return $names;
	}

	public function multi_array_sort ($multi_array,$sort_key,$sort=SORT_ASC) {  
        $key_array=array();
        if(is_array($multi_array)){  
            foreach ($multi_array as $row_array){  
                if(is_array($row_array)){  
                    $key_array[] = $row_array[$sort_key];  
                }else{  
                    return -1;  
                }  
            }  
        }else{  
            return -1;  
        }  
        array_multisort($key_array,$sort,$multi_array);  
        return $multi_array;  
    } 

	private function parseSeries ($series) {
		if(empty($series) || $series === 'all') {
            $series = array('F0', 'M6', '6B');
        } else {
            $series = explode(',', $series);
        }
		return $series;
	}

	private function parseQueryTime($stime, $etime) {
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
				$nextM = strtotime('first day of next month', $t);
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
