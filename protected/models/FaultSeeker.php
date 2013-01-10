<?php
Yii::import('application.models.Component');
class FaultSeeker
{
	public function __construct(){
	}

	public function getAllByCategory($faultCategory, $mode = '') {
		$sql = "SELECT * FROM fault_component_category WHERE category_key='{$faultCategory}'";
		$components = Yii::app()->db->createCommand($sql)->queryAll();
		$kindMaps = array(
			'all' => "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15",
			'assembly' => '1,2,3,4,5',
			'paint' => '6,7,8,9',
			'welding' => '10,11,12,13',
		);
		if($faultCategory === 'VQ3_facade_test') {
			$kindMaps = array(
					'all' => "6,7,8,9",
					'assembly' => '6,7,8,9',
					'paint' => '6,7,8,9',
					'welding' => '6,7,8,9',
			);
		}
		
		//falut mode
		foreach($components as &$component) {
			if(empty($mode)) {
				$sql = "SELECT id,level,mode FROM fault_standard WHERE component_id={$component['component_id']} AND kind_id IN ({$kindMaps['all']})";
            	$component['fault_mode'] = Yii::app()->db->createCommand($sql)->queryAll();

				$sql = "SELECT id,level,mode FROM fault_standard WHERE component_id={$component['component_id']} AND kind_id IN ({$kindMaps['assembly']})";
                $component['assembly_fault_mode'] = Yii::app()->db->createCommand($sql)->queryAll();


				$sql = "SELECT id,level,mode FROM fault_standard WHERE component_id={$component['component_id']} AND kind_id IN ({$kindMaps['paint']})";
                $component['paint_fault_mode'] = Yii::app()->db->createCommand($sql)->queryAll();


				$sql = "SELECT id,level,mode FROM fault_standard WHERE component_id={$component['component_id']} AND kind_id IN ({$kindMaps['welding']})";
                $component['welding_fault_mode'] = Yii::app()->db->createCommand($sql)->queryAll();


			} else if($mode === 'leak') {
				$sql = "SELECT id FROM fault_standard WHERE component_id={$component['component_id']} AND mode = '漏水'";
				$component['fault_id'] = Yii::app()->db->createCommand($sql)->queryScalar();
			}
		}

		return $components;
	
	}

	public function exist($car, $status, $tablePrefixs = array()) {
		$series = $car->car->series;
		$default_tablePrefixs = array(
			'VQ1_STATIC_TEST_',
			'VQ2_ROAD_TEST_',
			'VQ2_LEAK_TEST_',
			'VQ3_FACADE_TEST_',
		);
		if(empty($tablePrefixs)) {
			$tablePrefixs = $default_tablePrefixs;
		}
		$total = 0;
		foreach($tablePrefixs as $tablePrefix) {
        	$table = $tablePrefix . $series;
        	$sql = "SELECT count(*) FROM $table WHERE car_id={$car->car->id} AND status = '$status'";

        	$total += Yii::app()->db->createCommand($sql)->queryScalar();
		}
		return !empty($total);
	}

		

	public function query($component, $mode, $series, $stime, $etime, $nodeName,$curPage, $perPage) {

		list($stime, $etime) = $this->reviseSETime($stime, $etime);		//added by wujun

		$tables = $this->parseTables($nodeName,$series);	
		if(empty($tables)) {//
			return $this->queryNodeTrace($series, $stime, $etime, $nodeName,$curPage, $perPage);
		}
		$sql = "SELECT * FROM node";
		$nodes = Yii::app()->db->createCommand($sql)->queryAll();

		$sql = "SELECT id,display_name FROM user";
		$users = Yii::app()->db->createCommand($sql)->queryAll();
		$userInfos = array();
		foreach($users as $user) {
			$userInfos[$user['id']] = $user['display_name'];
		}
		$userInfos[0] = '-';
		
		$nodeInfos = array();
		foreach($nodes as $node) {
			$nodeInfos[$node['id']] = $node['display_name'];
		}
		
		$conditions = array();
		$validConditions = array();
		if(!empty($component)) {
			$conditions[] = "component_name LIKE '%$component%'";
		}
		if(!empty($mode)) {
            $conditions[] = "fault_mode LIKE '%$mode%'";
        }
		if(!empty($stime)) {
			$conditions[] = "create_time >= '$stime'";
			$validConditions[] = "pass_time >= '$stime'";
		}
 	    if(!empty($etime)) {
            $conditions[] = "create_time <= '$etime'";
			$validConditions[] = "pass_time <= '$etime'";
        }
		$condition = join(' AND ', $conditions);
		if(!empty($condition)) {
			$condition = 'WHERE ' . $condition;
		}
		
		$limit = "";
		if(!empty($perPage)) {
			$offset = ($curPage - 1) * $perPage;
			$limit = "LIMIT $offset, $perPage";
		}
		$dataSqls = array();
		$countSqls = array();
		foreach($tables as $table=>$nodeId) {
			$dataSqls[] = "(SELECT car_id, create_time, modify_time, updator, component_name, fault_mode, status as fault_status, '$nodeId' as 'node_id' FROM $table $condition)";
			$countSqls[] = "SELECT count(*) FROM $table $condition";
		}

		//
		$nodeId = $this->parseNodeId($nodeName);
		if(empty($nodeName)) {
			$nodeId = "10,13,15,16,17";
		}
		$validConditions[] = " node_id IN ($nodeId)";
		$condition = join(' AND ', $validConditions);
        if(!empty($condition)) {
            $condition = 'WHERE ' . $condition;
        }

		
		$dataSqls[] = $sql = "(SELECT car_id, pass_time as create_time, '-' as modify_time, node_trace.user_id as updator, '-' as component_name, '-' as fault_mode, '合格' as fault_status, node_id FROM node_trace $condition)";

		$dataSql = join(' UNION ALL ', $dataSqls);
		$dataSql .= " $limit";

		$datas = Yii::app()->db->createCommand($dataSql)->queryAll();

		$cars = array();
		foreach($datas as &$data) {
			$carId = $data['car_id'];
			if(empty($cars[$carId])) {
				$cars[$carId] = CarAR::model()->findByPk($carId);
			}
			$data['vin'] = $cars[$carId]->vin;
			$data['series'] = $cars[$carId]->series;
			$data['user_name'] = $userInfos[$data['updator']];
			$data['node_name'] = $nodeInfos[$data['node_id']];
		}

		$total = 0;
		foreach($countSqls as $countSql) {
			$total += Yii::app()->db->createCommand($countSql)->queryScalar();
		}
		return array($total, $datas);
	}

	public function queryNodeTrace($series, $stime, $etime, $node,$curPage, $perPage) {
        $condition = "";
		$conditions = array("node_trace.car_id=car.id");
        if(!empty($node)) {
            $sql = "SELECT id FROM node WHERE name='$node'";
            $nodeId = Yii::app()->db->createCommand($sql)->queryScalar();
            $conditions[] = "node_id=$nodeId";
        }
		
		if(!empty($stime)) {
            $conditions[] = "pass_time >= '$stime'";
        }
        if(!empty($etime)) {
            $conditions[] = "pass_time <= '$etime'";
        }

        $sql = "SELECT id,display_name FROM user";
        $users = Yii::app()->db->createCommand($sql)->queryAll();
        $userInfos = array();
        foreach($users as $user) {
            $userInfos[$user['id']] = $user['display_name'];
        }
		$sql = "SELECT * FROM node";
        $nodes = Yii::app()->db->createCommand($sql)->queryAll();

        $nodeInfos = array();
        foreach($nodes as $node) {
            $nodeInfos[$node['id']] = $node['display_name'];
        }


		$condition = join(' AND ', $conditions);
        if(!empty($condition)) {
            $condition = 'WHERE ' . $condition;
        }

        $limit = "";
        if(!empty($perPage)) {
            $offset = ($curPage - 1) * $perPage;
            $limit = "LIMIT $offset, $perPage";
        }

		$sql = "SELECT car_id, vin, series, pass_time as create_time, '-' as modify_time, node_id,node_trace.user_id as updator, '-' as component_name, '-' as fault_mode, '合格' as fault_status FROM node_trace,car $condition $limit";
        $traces = Yii::app()->db->createCommand($sql)->queryAll();
		
		
		foreach($traces as &$data) {
            $data['user_name'] = $userInfos[$data['updator']];
            $data['node_name'] = $nodeInfos[$data['node_id']];
        }

		$sql = "SELECT count(*) FROM node_trace,car $condition";	
		$total = Yii::app()->db->createCommand($sql)->queryScalar();

		return array($total, $traces);
	}

	//distribute chart
		
	public function queryDistribute($component, $mode, $series, $stime, $etime, $node) {

		list($stime, $etime) = $this->reviseSETime($stime, $etime);		//added by wujun
		
		$tables = $this->parseTables($node,$series);	
		if(empty($tables)) {
			return array(0, array());
		}

		$sql = "SELECT * FROM node";
        $nodes = Yii::app()->db->createCommand($sql)->queryAll();

        $nodeInfos = array();
        foreach($nodes as $node) {
            $nodeInfos[$node['id']] = $node['display_name'];
        }


		$conditions = array();
		if(!empty($component)) {
			$conditions[] = "component_name LIKE '%$component%'";
		}
		if(!empty($mode)) {
            $conditions[] = "fault_mode LIKE '%$mode%'";
        }
		if(!empty($stime)) {
			$conditions[] = "create_time >= '$stime'";
		}
 	    if(!empty($etime)) {
            $conditions[] = "create_time <= '$etime'";
        }
		$condition = join(' AND ', $conditions);
		if(!empty($condition)) {
			$condition = 'WHERE ' . $condition;
		}
		
		$dataSqls = array();
		$countSqls = array();
		foreach($tables as $table=>$nodeId) {
			$dataSqls[] = "(SELECT car_id, component_id,component_name, fault_id, fault_mode, status as fault_status, '$nodeId' as 'node_id' FROM $table $condition)";
		}

		$dataSql = join(' UNION ALL ', $dataSqls);

		$datas = Yii::app()->db->createCommand($dataSql)->queryAll();


		$chartDatas = array();
		$componentChartData = array();
		$faultModeChartData = array();
		$seriesChartData = array();
		$nodeChartData = array();
		$cars = array();
		$total = 0;
		foreach($datas as &$data) {
			$carId = $data['car_id'];
			if(empty($cars[$carId])) {
				$cars[$carId] = CarAR::model()->findByPk($carId);
			}
			$data['vin'] = $cars[$carId]->vin;
			$data['series'] = $cars[$carId]->series;
			
			if(empty($componentChartData[$data['component_id']])) {
				$componentChartData[$data['component_id']] = array(
					'id'    => $data['component_id'],
					'name'  => $data['component_name'],
					'count' => 0,
				);
			}

			if(empty($faultModeChartData[$data['fault_mode']])) {
                $faultModeChartData[$data['fault_mode']] = array(
                    'id'    => $data['fault_id'],
                    'name'  => $data['fault_mode'],
                    'count' => 0,
                );  
            } 

			if(empty($seriesChartData[$data['series']])) {
                $seriesChartData[$data['series']] = array(
                    'id'    => $data['series'],
                    'name'  => $data['series'],
                    'count' => 0,
                );
            }
			
			if(empty($nodeChartData[$data['node_id']])) {
                $nodeChartData[$data['node_id']] = array(
                    'id'    => $data['node_id'],
                    'name'  => $nodeInfos[$data['node_id']],
                    'count' => 0,
                );
            }

			
			
			++ $componentChartData[$data['component_id']]['count'];
			++ $faultModeChartData[$data['fault_mode']]['count'];
			++ $seriesChartData[$data['series']]['count'];
			++ $nodeChartData[$data['node_id']]['count'];
			++ $total;
		}
		$cSeries = array();
		foreach ($componentChartData as &$chartData) {
			$percentage = round($chartData['count'] / $total, 3);
			$chartData['percentage'] = $percentage * 100 . "%";
			$cSeries[] = array($chartData['name'],$percentage);
		}
		$fSeries = array();
		foreach ($faultModeChartData as &$chartData) {
			$percentage = round($chartData['count'] / $total, 3);
            $chartData['percentage'] = $percentage * 100 . "%";
			$fSeries[] = array($chartData['name'], $percentage);
        }
		$sSeries = array();
		foreach ($seriesChartData as &$chartData) {
			$percentage = round($chartData['count'] / $total, 3);
            $chartData['percentage'] = $percentage * 100 . "%";
			$sSeries[] = array($chartData['name'], $percentage);
        }
		$nSeries = array();
		foreach ($nodeChartData as &$chartData) {
			$percentage = round($chartData['count'] / $total, 3);
            $chartData['percentage'] = $percentage * 100 . "%";
			$nSeries[] = array($chartData['name'], $percentage);
        }
		
		return array(
			'component_chart_data' => array('detail' => array_values($componentChartData), 'series' => $cSeries),
			'fault_mode_chart_data' => array('detail' => array_values($faultModeChartData), 'series' => $fSeries),
			'series_chart_data' => array('detail' => array_values($seriesChartData), 'series' => $sSeries),
			'node_chart_data' => array('detail' => array_values($nodeChartData), 'series' => $nSeries),
		);
	}

	public function queryDPU($component, $mode, $series, $stime, $etime, $node) {
		$tables = $this->parseTables($node,$series);	
		if(empty($tables)) {
			return array();
		}
		$nodeIdStr = $this->parseNodeId($node);
		$conditions = array();
		if(!empty($component)) {
			$conditions[] = "component_name LIKE '%$component%'";
		}
		if(!empty($mode)) {
            $conditions[] = "fault_mode LIKE '%$mode%'";
        }

		$queryTimes = $this->parseQueryTime($stime,$etime);
		$ret = array();
		$dataSeriesX = array();
		$dataSeriesY = array();

		$arraySeries = $this->parseSeries($series);
		foreach($queryTimes as $queryTime) {
			$ss = $queryTime['stime'];
			$ee = $queryTime['etime'];
			$cc = $conditions;
	        if(!empty($ss)) {
				$cc[] = "create_time >= '$ss'";
			}   
			if(!empty($ee)) {
				$cc[] = "create_time <= '$ee'";		
			}   
			$condition = join(' AND ', $cc);
			if(!empty($condition)) {
				$condition = 'WHERE ' . $condition;
			}
			$temp = array();
			foreach($arraySeries as $series) {
				$tables = $this->parseTables($node,$series);
				$total = 0;
				foreach($tables as $table=>$nodeName) {
					$countSql = "(SELECT count(*) FROM $table $condition)";
					$total += Yii::app()->db->createCommand($countSql)->queryScalar();
				}
				$sql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$ss' AND pass_time <= '$ee' AND node_id IN ($nodeIdStr) AND car_series= '$series'";
				$cars = Yii::app()->db->createCommand($sql)->queryScalar();
				
				$temp[$series] = array(
						'series' => $series,
						'faults' => $total,
						'cars' => $cars,
						'dpu' => empty($cars) ? '-' : round($total / $cars, 2),
						);
				if(empty($dataSeriesY[$series])) $dataSeriesY[$series] = array();
				$dataSeriesY[$series][] = empty($cars) ? 0 : round($total / $cars, 2);
			}
			$ret[] = array_merge(array('time' => $queryTime['point']), $temp);
			$dataSeriesX[] = $queryTime['point'];
        }

        //added by wujun，计算“合计”
        $retTotal = array();
        foreach($arraySeries as $series) {
        	list($stT, $etT) = $this->reviseSETime($stime, $etime);
        	$faultTotal = 0;
        	foreach($tables as $table=>$nodeName) {
        		$countSql = "SELECT COUNT(*) FROM $table WHERE create_time >= '$stT' AND create_time <= '$etT'";
        		$faultTotal += Yii::app()->db->createCommand($countSql)->queryScalar();
        	}        	
        	$sql = "SELECT COUNT(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$stT' AND pass_time <= '$etT' AND node_id IN ($nodeIdStr) AND car_series = '$series'";
        	$carTotal = Yii::app()->db->createCommand($sql)->queryScalar();
        	$retTotal[] = array(
        		'series' => $series, 
        		'faultTotal' => $faultTotal,
        		'carTotal' => $carTotal,
        		'dpuTotal' => empty($carTotal) ? 0 : round($faultTotal / $carTotal, 2),
        	);
        }

		return array('carSeries' => $arraySeries, 'detail' => $ret, 'total' => $retTotal, 'series' => array('x' => $dataSeriesX, 'y' => $dataSeriesY));
	}


	public function queryPlaton($series, $stime, $etime, $node) {
		
		list($stime, $etime) = $this->reviseSETime($stime, $etime);		//added by wujun

		$tables = $this->parseTables($node,$series);	
		if(empty($tables)) {
			return array();
		}
		$nodeIdStr = $this->parseNodeId($node);

		$arraySeries = $this->parseSeries($series);
        $traceSeriesConditon = array();
        foreach($arraySeries as $series) {
            $traceSeriesConditon[] = "car_series = '$series'";
        }
        $traceSeriesConditon = "(" . join(' OR ', $traceSeriesConditon) . ")";


		$conditions = array();
		if(!empty($stime)) {
            $conditions[] = "create_time >= '$stime'";
        }
        if(!empty($etime)) {
            $conditions[] = "create_time <= '$etime'";
        }
        $condition = join(' AND ', $conditions);
        if(!empty($condition)) {
            $condition = 'WHERE ' . $condition;
        }
  		 
        $dataSqls = array();
        foreach($tables as $table=>$nodeName) {
            $dataSqls[] = "(SELECT car_id, component_id,component_name, fault_id, fault_mode, status as fault_status, '$nodeName' as 'node_name' FROM $table $condition)";
        }
		$sql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$stime' AND pass_time <= '$etime' AND node_id IN ($nodeIdStr) AND $traceSeriesConditon";
        $cars = Yii::app()->db->createCommand($sql)->queryScalar();


        $dataSql = join(' UNION ALL ', $dataSqls);
        $datas = Yii::app()->db->createCommand($dataSql)->queryAll();

		$total = count($datas);
		$faultModes = array();
		foreach($datas as $data) {
			$fault = $data['component_name']. $data['fault_mode'];
			if(empty($faultModes[$fault])) {
				$faultModes[$fault] = array(
					'name' => $fault,
					'count' => 0,
				);	
			}
			++ $faultModes[$fault]['count'];
		}

		//sort
		ksort($faultModes);
		$faultModes = array_values($faultModes);
		$modes = count($faultModes);
		$temps = array();
		
		for($i = 0; $i < $modes; $i ++) {
			$max = 1 - PHP_INT_MAX;
			$curIndex = -1;
			for($j = 0; $j < $modes; $j ++) {
				if(empty($faultModes[$j]['sorted'])) {
					if($max < $faultModes[$j]['count']) {
						$max = $faultModes[$j]['count'];
						$curIndex = $j;
					}
				}
			}
			$temps[] = $faultModes[$curIndex];
			$faultModes[$curIndex]['sorted'] = true;
		}
		$key = 0;
		$pSeriesX = array();
		$pSeriesY = array();
		$pColumnY = array();
		$pn = 0;
		$detail = array();
		$totalFaults = 0;
		foreach($temps as $temp) {
			if($key / $total < 0.8) {
				$temp['dpu'] = empty($cars) ? '-' : round($temp['count'] / $cars, 3);
				$temp['percentage'] = round($temp['count'] / $total, 3);
				$key += $temp['count'];

				$pn += $temp['percentage'];
				$pColumnY[] = $temp['count'];
				$pSeriesY[] = $pn;
				$pSeriesX[] = $temp['name'];
				$temp['percentage'] = $temp['percentage'] * 100 . "%";
				$detail[] = $temp;
				++ $totalFaults;
				if($totalFaults >= 15) {
					break;
				}
			}
		}
		return array('detail'=> $detail, 'series' => array('x' => $pSeriesX, 'y' => $pSeriesY, 'column' => $pColumnY));
	}

	public function queryQualified($series, $stime, $etime, $node) {
		$tables = $this->parseTables($node,$series);	
		if(empty($tables)) {
			return array();
		}
		$nodeIdStr = $this->parseNodeId($node);
		$arraySeries = $this->parseSeries($series);

		$queryTimes = $this->parseQueryTime($stime,$etime);
		$detail = array();
		$dataSeriesX = array();
		$dataSeriesY = array();
		foreach($queryTimes as $queryTime) {
			$ss = $queryTime['stime'];
			$ee = $queryTime['etime'];
			$cc = array("status != '在线修复'");
	        if(!empty($ss)) {
				$cc[] = "create_time >= '$ss'";
			}   
			if(!empty($ee)) {
				$cc[] = "create_time <= '$ee'";
			}   
			$condition = join(' AND ', $cc);
			if(!empty($condition)) {
				$condition = 'WHERE ' . $condition;
			}
			$temp = array();
			foreach($arraySeries as $series) {
				$total = 0;
				$dataSqls = array();
				$tables = $this->parseTables($node, $series);
				foreach($tables as $table=>$nodeName) {
					$dataSqls[] = "(SELECT car_id FROM $table $condition)";
				}
				$sql = join(' UNION ALL ', $dataSqls);			
				$datas = Yii::app()->db->createCommand($sql)->queryColumn();
				$datas = array_unique($datas);
				$faults = count($datas);

				$sql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$ss' AND pass_time <= '$ee' AND node_id IN ($nodeIdStr) AND car_series = '$series'";
				$cars = Yii::app()->db->createCommand($sql)->queryScalar();
			
				$rate = empty($cars) ? 0 : round(($cars - $faults) / $cars, 3);	
				$temp[$series] = array(
					'qualified' => $cars - $faults,
					'total' => $cars,
					'rate' => empty($cars) ? '-' : $rate * 100 . "%",
				);
				$dataSeriesY[$series][] = $rate;
			}	
			$detail[] = array_merge(array('time' => $queryTime['point']), $temp);
			$dataSeriesX[] = $queryTime['point'];
        }

        //added by wujun，计算“合计”
        $retTotal = array();
        foreach($arraySeries as $series) {
        	list($stT, $etT) = $this->reviseSETime($stime, $etime);

			$dataSqls = array();
			$tables = $this->parseTables($node, $series);
			foreach($tables as $table=>$nodeName) {
				$dataSqls[] = "(SELECT car_id FROM $table WHERE status != '在线修复' AND create_time >= '$stT' AND create_time <= '$etT')";
			}
			$sql = join(' UNION ALL ', $dataSqls);			
			$datas = Yii::app()->db->createCommand($sql)->queryColumn();
			$datas = array_unique($datas);
			$faults = count($datas);

        	$sql = "SELECT COUNT(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$stT' AND pass_time <= '$etT' AND node_id IN ($nodeIdStr) AND car_series = '$series'";
        	$carTotal = Yii::app()->db->createCommand($sql)->queryScalar();
        	$rate = empty($carTotal) ? 0 : round(($carTotal - $faults) / $carTotal, 3);

        	$retTotal[] = array(
        		'series' => $series, 
        		'qualifiedTotal' => $carTotal - $faults,
        		'carTotal' => $carTotal,
        		'rateTotal' => empty($carTotal) ? '-' : $rate * 100 . "%",
        	);
        }

		return array('carSeries' => $arraySeries, 'detail'=> $detail, 'total'=>$retTotal, 'series' => array('x' => $dataSeriesX, 'y' => $dataSeriesY));
	}


	public function queryCars($series, $stime, $etime, $node) {
		$nodeIdStr = $this->parseNodeId($node);
		$arraySeries = $this->parseSeries($series);

		$queryTimes = $this->parseQueryTime($stime,$etime);
		$ret = array();
		$dataSeriesX = array();
		$dataSeriesY = array();
		foreach($queryTimes as $queryTime) {
			$ss = $queryTime['stime'];
			$ee = $queryTime['etime'];
			$temp = array();
			foreach($arraySeries as $series) {
				$sql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$ss' AND pass_time <= '$ee' AND node_id IN ($nodeIdStr) AND car_series = '$series'";

				$cars = Yii::app()->db->createCommand($sql)->queryScalar();
				
				$temp[$series] = $cars;
				$dataSeriesY[$series][] = intval($cars);
			}	
			$ret[] = array_merge(array('time' => $queryTime['point']), $temp);
			$dataSeriesX[] = $queryTime['point'];
        }
        //added by wujun，计算“合计”
        $retTotal = array();
        foreach($arraySeries as $series) {
        	list($stT, $etT) = $this->reviseSETime($stime, $etime);

        	$sql = "SELECT COUNT(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$stT' AND pass_time <= '$etT' AND node_id IN ($nodeIdStr) AND car_series = '$series'";
        	$carTotal = Yii::app()->db->createCommand($sql)->queryScalar();

        	$retTotal[] = array(
        		'series' => $series, 
        		'carTotal' => $carTotal,
        	);
        }
		return array('carSeries' => $arraySeries, 'detail'=>$ret, 'total'=>$retTotal, 'series' => array('x' => $dataSeriesX, 'y' => $dataSeriesY));
	}

	protected function parseTables($node, $series) {
		$tablePrefixs = array(
			'VQ1_STATIC_TEST' => 10,
			'VQ2_ROAD_TEST' => 15,
			'VQ2_LEAK_TEST' => 16,
			'VQ3_FACADE_TEST' => 17,
		);
		$nodeTables = array(
			'VQ1' => 'VQ1_STATIC_TEST',
			'ROAD_TEST_FINISH' => 'VQ2_ROAD_TEST',
			'VQ2' => 'VQ2_LEAK_TEST',
			'VQ3' => 'VQ3_FACADE_TEST',
		);

		$temps = array();
		if(empty($node) || $node === 'all') {
			$temps = $tablePrefixs;
		} elseif($node === 'VQ2_ALL') {
			$temps = array(
				'VQ2_ROAD_TEST' => 15,
            	'VQ2_LEAK_TEST' => 16,
			);
		} elseif(!empty($nodeTables[$node])) {
			$temps = array($nodeTables[$node]=>$tablePrefixs[$nodeTables[$node]]);
		}

		$tables = array();
		if(empty($series) || $series === 'all') {
			$series = array('F0', 'M6');
		} else {
			$series = explode(',', $series);
		}
		foreach($temps as $prefix=>$name) {
			foreach($series as $serie) {
				$tables[$prefix . "_" .$serie] = $name;
			}
		}

		return $tables;
	}

	private function parseNodeId($node) {
		$nodeIds = array(
			'PBS' => 1,
			'T0'  => 2,
            'VQ1' => 10,
			'CHECK_LINE' => 13,
            'ROAD_TEST_FINISH' => 15,
            'VQ2' => 16,
			'VQ2_ALL' => '13,15,16',
            'VQ3' => 17,
			'CHECK_IN' => 18,
			'CHECK_OUT' => 19,
        );

		if(empty($node) || $node === 'all') {
			return join(',', array_values($nodeIds));
		} else {
			return $nodeIds[$node];
		}

	}

	private function parseSeries($series) {
		if(empty($series) || $series === 'all') {
            $series = array('F0', 'M6');
        } else {
            $series = explode(',', $series);
        }
		return $series;
	}

	//modified by wujun
	private function parseQueryTime($stime,$etime) {
		
		list($stime, $etime) = $this->reviseSETime($stime, $etime);		//added by wujun
		$s = strtotime($stime);
		$e = strtotime($etime);
	
		$sd = date('Ymd', $s);
		$ed = date('Ymd', $e);
		
		$lastHour = ($e - $s) / 3600;
		$lastDay = (strtotime($ed) - strtotime($sd)) / 86400;//days

		$ret = array();
		if($lastHour < 24) {//hour
			$pointFormat = 'H';
			$format = 'Y-m-d H:i:s';
			$slice = 3600;
		} elseif($lastDay < 31) {//day
			$pointFormat = 'm-d';
			$format = 'Y-m-d';
			$slice = 86400;
		} else {//month
			$pointFormat = 'Y-m';
			$format = 'Y-m';
			//$slice = 86400 * 31;		//deleted by wujun
		}
		
		$t = $s;
		while($t < $e) {
			if($pointFormat === 'H') {
				$point = date($pointFormat, $t) . '～' . date($pointFormat, $t + $slice) . '点';
			} else {
				$point = date($pointFormat, $t);
			}

			//added by wujun
			if($pointFormat === 'Y-m') {
				$slice = 86400 * intval(date('t' ,$t));
			}

			$ret[] = array(
				'stime' => date($format, $t),
				'etime' => date($format, $t + $slice),
				'point' => $point,
			);	
			$t += $slice;			
		}

		return $ret;
	}

	private function reviseSETime($stime,$etime) {
		$s = strtotime($stime);
		$e = strtotime($etime);
	
		$sd = date('Ymd', $s);
		$ed = date('Ymd', $e);
		
		$sm = date('n', $s);
		$em = date('n', $e);

		$lastHour = ($e - $s) / 3600;
		$lastDay = (strtotime($ed) - strtotime($sd)) / 86400;//days

		$ret = array();
		if($lastHour < 24) {//hour
			$format = 'Y-m-d H';
			$stime = date($format, $s) . ":00:00";
			$etime = date($format, $e) . ":00:00";
		} elseif($lastDay < 31) {//day
			$format = 'Y-m-d';
			$stime = date($format, $s) . " 00:00:00";
			$etime = date($format, $e) . " 23:59:59";
		} else {//month
			$format = 'Y-m';
			$stime = date($format, $s) . "-01 00:00:00";
			$etime = date('Y-m-t', $e) . " 23:59:59";
		}


		return array($stime, $etime);
	}
}
