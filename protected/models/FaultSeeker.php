<?php
Yii::import('application.models.Component');
class FaultSeeker
{
	public function __construct(){
	}

	public function getAllByCategory($faultCategory, $mode = '', $series = '') {
		$sql = "SELECT * FROM fault_component_category WHERE 1=1";
		if(!empty($faultCategory)) {
			$sql .= " AND category_key='{$faultCategory}'";
		}
		if(!empty($series)) {
			$sql .= " AND series='$series'";
		}
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
				// $sql = "SELECT id FROM fault_standard WHERE component_id={$component['component_id']} AND mode LIKE '%漏水%'";
				$sql = "SELECT id,level,mode FROM fault_standard WHERE component_id={$component['component_id']} AND mode LIKE '%漏水%'";
				$component['fault_id'] = Yii::app()->db->createCommand($sql)->queryScalar();
				$component['leak_fault_model'] = Yii::app()->db->createCommand($sql)->queryAll();
			}
		}

		return $components;

	}

	public function exist($car, $status, $tablePrefixs = array()) {
		$series = $car->car->series;
		$default_tablePrefixs = array(
			'VQ1_STATIC_TEST_',
			'VQ1_STATIC_TEST_2_',
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

		//list($stime, $etime) = $this->reviseSETime($stime, $etime);		//added by wujun
		$arraySeries = Series::parseSeries($series);
		$tables = $this->parseTables($nodeName,$series);
		if(empty($tables)) {//
			return $this->queryNodeTrace($series, $stime, $etime, $nodeName,$curPage, $perPage);
		}
		$name = Series::getNameList();

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

		$dutyList = $this->dutyList();

		$conditions = array();
		$validConditions = array();
		if(!empty($component)) {
			$conditions[] = "c.component_name LIKE '%$component%'";
			$validConditions[] = "c.component_name LIKE '%$component%'";
		}
		if(!empty($mode)) {
            $conditions[] = "c.fault_mode LIKE '%$mode%'";
            $validConditions[] = "c.fault_mode LIKE '%$mode%'";
        }

		if(!empty($stime)) {
			$conditions[] = "c.create_time >= '$stime'";
			$validConditions[] = "n.pass_time >= '$stime'";
		}
 	    if(!empty($etime)) {
            $conditions[] = "c.create_time < '$etime'";
			$validConditions[] = "n.pass_time < '$etime'";
        }
		$condition = join(' AND ', $conditions);
		$validCondition = join( ' AND ', $validConditions);
		if(!empty($condition)) {
			$condition = 'AND (' . $condition . ')';
		}
		if(!empty($validCondition)) {
			$condition .= ' OR (' . $validCondition ;
		}

		$limit = "";
		if(!empty($perPage)) {
			$offset = ($curPage - 1) * $perPage;
			$limit = "LIMIT $offset, $perPage";
		}
		$checkerParam = "'' as checker, '' as sub_checker, ";
		if($nodeName === 'WDI') {
			$checkerParam = "c.checker1 as checker, c.checker2 as sub_checker, ";
		}
		$dataSqls = array();
		$countSqls = array();
		foreach($tables as $table=>$nodeId) {
			$traceSeriesConditon = '';
			foreach($arraySeries as $series) {
				if(strstr($table , $series) === $series) {
					$traceSeriesConditon = " AND n.car_series = '$series'";
					break;
				}
			}

			if(!empty($condition)) {
				$curCondition = $condition . " AND n.node_id=$nodeId ". $traceSeriesConditon .")";
			} else {
				$curCondition = "AND n.node_id=$nodeId AND ". $traceSeriesConditon ;
			}

			//wdi need checker1 checker2
			//要求n.pass_time 和 c.create_time都在查询时间条件区间内（实际生产中，同一辆车在不同日期录入多次但不是每次都有故障：比如一辆车今天录其合格，后天录其有故障，如果查询条件为今天则其为合格；如果查询条件为后天，则其为有故障）
			// $timeCondition = "n.pass_time >= '$stime' AND n.pass_time < '$etime' AND c.create_time >= '$stime' AND c.create_time < '$etime'";
			// $dataSqls[] = "(SELECT $checkerParam n.car_id, n.user_id,n.driver_id, n.pass_time, c.create_time, c.modify_time, c.updator, c.component_name, c.fault_mode, c.status as fault_status, c.duty_department as duty_department, '$nodeId' as 'node_id' FROM node_trace AS n LEFT JOIN $table AS c ON n.car_id=c.car_id AND $timeCondition WHERE n.node_id=$nodeId AND $timeCondition $curCondition ORDER BY n.pass_time DESC)";
			// $countSqls[] = "SELECT count(*) FROM node_trace AS n LEFT JOIN $table AS c ON n.car_id=c.car_id AND $timeCondition WHERE n.node_id=$nodeId AND $timeCondition  $curCondition";
			$dataSqls[] = "(SELECT $checkerParam n.car_id, n.user_id,n.driver_id, n.pass_time, c.create_time, c.modify_time, c.updator, c.component_name, c.fault_mode, c.status as fault_status, c.duty_department as duty_department, '$nodeId' as 'node_id' FROM node_trace AS n LEFT JOIN $table AS c ON n.car_id=c.car_id AND n.id = c.node_trace_id WHERE n.node_id=$nodeId $curCondition ORDER BY n.pass_time DESC)";
			$countSqls[] = "SELECT count(*) FROM node_trace AS n LEFT JOIN $table AS c ON n.car_id=c.car_id AND n.id = c.node_trace_id WHERE n.node_id=$nodeId $curCondition";
		}

		$dataSql = join(' UNION ALL ', $dataSqls);
		$dataSql .= " $limit";

		$datas = Yii::app()->db->createCommand($dataSql)->queryAll();

		$cars = array();
		$ret = array();
		foreach($datas as &$data) {
			$carId = $data['car_id'];
			if(empty($data['fault_mode'])) {
                $data['fault_status'] = '合格';
                $data['fault_mode'] = '-';
                $data['component_name'] = '-';
                $data['create_time'] = $data['pass_time'];
                $data['modify_time'] = $data['pass_time'];
            }
			if(empty($cars[$carId])) {
                $cars[$carId] = CarAR::model()->findByPk($carId);
            }
			if(empty($cars[$carId])) {
				continue;
			}
			$data['vin'] = $cars[$carId]->vin;
			$data['series'] = $cars[$carId]->series;
			$data['line'] = $cars[$carId]->assembly_line;
			if(!empty($data['updator'])) {
				$data['user_name'] = $userInfos[$data['updator']];
			} else {
				$data['user_name'] = $userInfos[$data['user_id']];
			}
			if(!empty($data['checker'])) {
				$data['checker'] = empty($userInfos[$data['checker']]) ? '' : $userInfos[$data['checker']];
			}
			if(!empty($data['sub_checker'])) {
                $data['sub_checker'] = empty($userInfos[$data['sub_checker']]) ? '' : $userInfos[$data['sub_checker']];
            }

			if(!empty($data['driver_id'])) {
				$data['driver_name'] = $userInfos[$data['driver_id']];
			} else {
				$data['driver_name'] = $data['user_name'];
			}
			$data['node_name'] = $nodeInfos[$data['node_id']];
			if(empty($data['duty_department'])){
				$data['duty_department'] = '-';
			}
			else{
			 	$data['duty_department'] = $dutyList[$data['duty_department']];
			}

			$data['series'] = $name[$data['series']];

			$data['pass_time'] = substr($data['pass_time'],0,16);
			$data['create_time'] = substr($data['create_time'],0,16);
			$data['modify_time'] = substr($data['modify_time'],0,16);

			// $key = join('_', $data);
			$key =  $data['series']
					.$data['vin']
					.$data['component_name']
					.$data['fault_mode']
					.$data['fault_status']
					.$data['duty_department']
					.$data['node_name']
					.$data['driver_name']
					.$data['user_name']
					.$data['create_time']
					.$data['modify_time'];
			$ret[$key] = $data;
		}

		$total = 0;
		foreach($countSqls as $countSql) {
			$total += Yii::app()->db->createCommand($countSql)->queryScalar();
		}

		$ret = array_values($ret);
		return array($total, $ret);
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
            $conditions[] = "pass_time < '$etime'";
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

		$sql = "SELECT car_id, vin, series, pass_time as create_time, '-' as modify_time, node_id,node_trace.user_id as updator, '-' as component_name, '-' as fault_mode, '合格' as fault_status, '-' as duty_department, assembly_line as `line`, '' as checker, '' as sub_checker FROM node_trace,car $condition $limit";
        $traces = Yii::app()->db->createCommand($sql)->queryAll();


		foreach($traces as &$data) {
            $data['user_name'] = $userInfos[$data['updator']];
            $data['node_name'] = $nodeInfos[$data['node_id']];
            if(!empty($data['driver_id'])) {
				$data['driver_name'] = $userInfos[$data['driver_id']];
			} else {
				$data['driver_name'] = $data['user_name'];
			}

			$data['create_time'] = substr($data['create_time'],0,16);
        }

		$sql = "SELECT count(*) FROM node_trace,car $condition";
		$total = Yii::app()->db->createCommand($sql)->queryScalar();

		return array($total, $traces);
	}

	//distribute chart

	public function queryDistribute($component, $mode, $series, $stime, $etime, $node) {

		//list($stime, $etime) = $this->reviseSETime($stime, $etime);		//added by wujun

		$tables = $this->parseTables($node,$series);
		if(empty($tables)) {
			return array(0, array());
		}

		$name = Series::getNameList();
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
            $conditions[] = "create_time < '$etime'";
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

			if(empty($seriesChartData[$name[$data['series']]])) {
                $seriesChartData[$name[$data['series']]] = array(
                    'id'    => $name[$data['series']],
                    'name'  => $name[$data['series']],
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
			++ $seriesChartData[$name[$data['series']]]['count'];
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

	public function queryDutyDistribution($component, $mode, $series, $stime, $etime, $node) {
		$tables = $this->parseTables($node,$series);
		if(empty($tables)) {
			return array(0, array());
		}
		$nodeIdStr = $this->parseNodeId($node);

		$name = Series::getNameList();
		$sql = "SELECT * FROM node";
        $nodes = Yii::app()->db->createCommand($sql)->queryAll();

        $nodeInfos = array();
        foreach($nodes as $node) {
            $nodeInfos[$node['id']] = $node['display_name'];
        }

        $dutyList = $this->dutyList();

        $arraySeries = Series::parseSeries($series);
        $traceSeriesConditon = array();
        foreach($arraySeries as $series) {
            $traceSeriesConditon[] = "car_series = '$series'";
        }

        $traceSeriesConditon = "(" . join(' OR ', $traceSeriesConditon) . ")";

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
            $conditions[] = "create_time < '$etime'";
        }
		$condition = join(' AND ', $conditions);
		if(!empty($condition)) {
			$condition = 'WHERE ' . $condition;
		}

		$dataSqls = array();
		$countSqls = array();
		foreach($tables as $table=>$nodeId) {
			$dataSqls[] = "(SELECT car_id, component_id,component_name, fault_id, fault_mode,duty_department, status as fault_status, '$nodeId' as 'node_id' FROM $table $condition)";
		}

		$sql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$stime' AND pass_time < '$etime' AND node_id IN ($nodeIdStr) AND $traceSeriesConditon";
        $cars = Yii::app()->db->createCommand($sql)->queryScalar();

		$dataSql = join(' UNION ALL ', $dataSqls);
		$datas = Yii::app()->db->createCommand($dataSql)->queryAll();

		$total = count($datas);
		$dutyDepartments = array();
		foreach($datas as $data) {
			$duty = $dutyList[$data['duty_department']];
			if(empty($dutyDepartments[$duty])){
				$dutyDepartments[$duty] = array(
					'name' => $duty,
					'count' => 0,
				);
			}
			++ $dutyDepartments[$duty]['count'];
		}

		//sort
		ksort($dutyDepartments);
		$dutyDepartments = array_values($dutyDepartments);
		$dutyCount = count($dutyDepartments);
		$temps = array();

		for($i = 0; $i < $dutyCount; $i++){
			$max = 1 - PHP_INT_MAX;
			$curIndex = -1;
			for($j = 0; $j < $dutyCount; $j++){
				if(empty($dutyDepartments[$j]['sorted'])){
					if($max < $dutyDepartments[$j]['count']){
						$max = $dutyDepartments[$j]['count'];
						$curIndex = $j;
					}
				}
			}
			$temps[] = $dutyDepartments[$curIndex];
			$dutyDepartments[$curIndex]['sorted'] = true;
		}

		$key = 0;
		$pSeriesX = array();
		$pSeriesY = array();
		$pColumnY = array();
		$pSeriesP = array();
		$pn = 0;
		$detail = array();
		// $totalDutys = 0;
		foreach($temps as $temp) {
			if($key / $total < 1) {
				$temp['dpu'] = empty($cars) ? '-' :round($temp['count'] / $cars, 3);
				$temp['percentage'] = $temp['count'] / $total;
				$key += $temp['count'];

				$pn +=$temp['percentage'];
				$pColumnY[] = $temp['count'];
				$pSeriesY[] = $pn;
				$pSeriesP[] = $temp['percentage'];
				$pSeriesX[] = $temp['name'];
				$temp['percentage'] = round($temp['percentage'], 3) * 100 . "%";
				$detail[] = $temp;
				// ++ $totalDutys;
			}
		}

		return array('detail'=> $detail, 'series' => array('x' => $pSeriesX, 'y' => $pSeriesY, 'column' => $pColumnY, 'p' => $pSeriesP));

	}

	public function queryDPU($component, $mode, $series, $stime, $etime, $node) {
		$tables = $this->parseTables($node,$series);
		$name = Series::getNameList();
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
		$retTotal = array();

		$arraySeries = Series::parseSeries($series);

		foreach($arraySeries as $series){
				$retTotal[$name[$series]] = array(
									'faultTotal' => 0,
        							'carTotal' => 0,
        							'dpuTotal' => 0,
								);
		}

		foreach($queryTimes as $queryTime) {
			$ss = $queryTime['stime'];
			$ee = $queryTime['etime'];
			$cc = $conditions;
	        if(!empty($ss)) {
				$cc[] = "create_time >= '$ss'";
			}
			if(!empty($ee)) {
				$cc[] = "create_time < '$ee'";
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
				$sql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$ss' AND pass_time < '$ee' AND node_id IN ($nodeIdStr) AND car_series= '$series'";
				$cars = Yii::app()->db->createCommand($sql)->queryScalar();

					$temp[$name[$series]] = array(
							'series' => $name[$series],
							'faults' => $total,
							'cars' => $cars,
							'dpu' => empty($cars) ? '-' : round($total / $cars, 2),
							);
					if(empty($dataSeriesY[$name[$series]])) $dataSeriesY[$name[$series]] = array();
					$dataSeriesY[$name[$series]][] = empty($cars) ? null : round($total / $cars, 2);
					// $retTotal[$name[$series]]['faultTotal'] += $total;
					// $retTotal[$name[$series]]['carTotal'] += $cars;


			}
			$ret[] = array_merge(array('time' => $queryTime['point']), $temp);
			$dataSeriesX[] = $queryTime['point'];
        }

        //total
        $con = $conditions;
        if(!empty($stime)) {
			$con[] = "create_time>='$stime'";
		}
		if(!empty($etime)) {
			$con[] = "create_time<'$etime'";
		}
		$totalCondition = join(' AND ', $con);
		if(!empty($totalCondition)) {
			$totalCondition = 'WHERE ' . $totalCondition;
		}
        foreach($arraySeries as $series) {
			$tables = $this->parseTables($node,$series);
			$totalTotal = 0;
			foreach($tables as $table=>$nodeName) {
				$totalCountSql = "SELECT count(*) FROM $table $totalCondition";
				$totalTotal += Yii::app()->db->createCommand($totalCountSql)->queryScalar();
			}
			$totalSql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$stime' AND pass_time < '$etime' AND node_id IN ($nodeIdStr) AND car_series= '$series'";
			$cars = Yii::app()->db->createCommand($totalSql)->queryScalar();

			$retTotal[$name[$series]]['faultTotal'] = $totalTotal;
			$retTotal[$name[$series]]['carTotal'] = $cars;
        	$retTotal[$name[$series]]['dpuTotal'] = empty($retTotal[$name[$series]]['carTotal']) ? '-' : round($retTotal[$name[$series]]['faultTotal'] / $retTotal[$name[$series]]['carTotal'], 2);
        }

        $carSeries = array();
        foreach ($arraySeries as $series){
        	$carSeries[] = $name[$series];
        }
		return array('carSeries' => $carSeries, 'detail' => $ret, 'total' => $retTotal, 'series' => array('x' => $dataSeriesX, 'y' => $dataSeriesY));
	}


	public function queryPlaton($series, $stime, $etime, $node, $component, $mode) {

		//list($stime, $etime) = $this->reviseSETime($stime, $etime);		//added by wujun

		$tables = $this->parseTables($node,$series);
		if(empty($tables)) {
			return array();
		}
		$nodeIdStr = $this->parseNodeId($node);

		$arraySeries = Series::parseSeries($series);
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
            $conditions[] = "create_time < '$etime'";
        }
        if(!empty($component)) {
			$conditions[] = "component_name LIKE '%$component%'";
		}
		if(!empty($mode)) {
            $conditions[] = "fault_mode LIKE '%$mode%'";
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
				$temp['percentage'] = $temp['count'] / $total;
				$key += $temp['count'];

				$pn += $temp['percentage'];
				$pColumnY[] = $temp['count'];
				$pSeriesY[] = $pn;
				$pSeriesX[] = $temp['name'];
				$temp['percentage'] = round($temp['percentage'], 3) * 100 . "%";
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
		$arraySeries = Series::parseSeries($series);
		$name = Series::getNameList();

		$queryTimes = $this->parseQueryTime($stime,$etime);
		$detail = array();
		$dataSeriesX = array();
		$dataSeriesY = array();
		$retTotal = array();

		foreach($arraySeries as $series){
			$retTotal[$name[$series]] = array(
									'qualifiedTotal' => 0,
        							'carTotal' => 0,
        							'rateTotal' => 0,
								);
		}

		foreach($queryTimes as $queryTime) {
			$ss = $queryTime['stime'];
			$ee = $queryTime['etime'];
			$cc = array("status != '在线修复'");
	        if(!empty($ss)) {
				$cc[] = "create_time >= '$ss'";
			}
			if(!empty($ee)) {
				$cc[] = "create_time < '$ee'";
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

				$sql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$ss' AND pass_time < '$ee' AND node_id IN ($nodeIdStr) AND car_series = '$series'";
				$cars = Yii::app()->db->createCommand($sql)->queryScalar();

				$rate = empty($cars) ? null : round(($cars - $faults) / $cars, 3);
				$temp[$name[$series]] = array(
					'qualified' => $cars - $faults,
					'total' => $cars,
					'rate' => empty($cars) ? '-' : $rate * 100 . "%",
				);
				$dataSeriesY[$name[$series]][] = $rate;
				$retTotal[$name[$series]]['qualifiedTotal'] += $temp[$name[$series]]['qualified'];
				$retTotal[$name[$series]]['carTotal'] += $cars;
			}
			$detail[] = array_merge(array('time' => $queryTime['point']), $temp);
			$dataSeriesX[] = $queryTime['point'];

        }

         //total
        $con = array("status != '在线修复' AND status != '合格'");
        if(!empty($stime)) {
			$con[] = "create_time>='$stime'";
		}
		if(!empty($etime)) {
			$con[] = "create_time<'$etime'";
		}
		$totalCondition = join(' AND ', $con);
		if(!empty($totalCondition)) {
			$totalCondition = 'WHERE ' . $totalCondition;
		}
        foreach($arraySeries as $series) {
			$totalTotal = 0;
			$dataSqls = array();
			$tables = $this->parseTables($node, $series);
			foreach($tables as $table=>$nodeName) {
				$dataSqls[] = "SELECT car_id FROM $table $totalCondition";
			}
			$sql = join(' UNION ALL ', $dataSqls);
			$datas = Yii::app()->db->createCommand($sql)->queryColumn();
			$datas = array_unique($datas);
			$totalFaults = count($datas);

			$totalSql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$stime' AND pass_time < '$etime' AND node_id IN ($nodeIdStr) AND car_series= '$series'";
			$cars = Yii::app()->db->createCommand($totalSql)->queryScalar();

			$retTotal[$name[$series]]['qualifiedTotal'] = $cars - $totalFaults;
			$retTotal[$name[$series]]['carTotal'] = $cars;
        	$retTotal[$name[$series]]['rateTotal'] = empty($retTotal[$name[$series]]['carTotal']) ? '-' : round($retTotal[$name[$series]]['qualifiedTotal'] / $retTotal[$name[$series]]['carTotal'], 3) * 100 . '%';
        }

        $carSeries = array();
        foreach ($arraySeries as $series){
        	$carSeries[] = $name[$series];
        }
		return array('carSeries' => $carSeries, 'detail'=> $detail, 'total'=>$retTotal, 'series' => array('x' => $dataSeriesX, 'y' => $dataSeriesY));
	}


	public function queryCars($series, $stime, $etime, $node, $returnNode=0) {
		$nodeIdStr = $this->parseNodeId($node);
		$traceTable = 'node_trace';
		if(!empty($returnNode)){
			$traceTable = 'warehouse_return_trace';
		}
		$arraySeries = Series::parseSeries($series);

		$queryTimes = $this->parseQueryTime($stime,$etime);
		$ret = array();
		$dataSeriesX = array();
		$dataSeriesY = array();
		$retTotal = array();
		foreach($arraySeries as $series){
			if($series == '6B'){
				$retTotal['思锐'] = 0;
			}else {
				$retTotal[$series] = 0;
			}
		}
		foreach($queryTimes as $queryTime) {
			$ss = $queryTime['stime'];
			$ee = $queryTime['etime'];
			$temp = array();
			foreach($arraySeries as $series) {
				$sql = "SELECT count(DISTINCT car_id) FROM $traceTable WHERE pass_time >= '$ss' AND pass_time < '$ee' AND node_id IN ($nodeIdStr) AND car_series = '$series'";

				$cars = Yii::app()->db->createCommand($sql)->queryScalar();

				if($series == '6B'){
					$temp['思锐'] = $cars;
					$dataSeriesY['思锐'][] = intval($cars);
					// $retTotal['思锐'] += intval($cars);
				}else {
					$temp[$series] = $cars;
					$dataSeriesY[$series][] = intval($cars);
					// $retTotal[$series] += intval($cars);
				}
			}
			$ret[] = array_merge(array('time' => $queryTime['point']), $temp);
			$dataSeriesX[] = $queryTime['point'];
        }


        foreach($arraySeries as $key => &$series){
	        $totalSql = "SELECT count(DISTINCT car_id) FROM $traceTable WHERE pass_time >= '$stime' AND pass_time < '$etime' AND node_id IN ($nodeIdStr) AND car_series = '$series'";
        	$countTotal = Yii::app()->db->createCommand($totalSql)->queryScalar();
        	// if($series == '6B') $arraySeries[$key] = '思锐';
        	if($series == '6B') $series = '思锐';
        	$retTotal[$series] = intval($countTotal);
        }
		return array('carSeries' => $arraySeries, 'detail'=>$ret, 'total'=>$retTotal, 'series' => array('x' => $dataSeriesX, 'y' => $dataSeriesY));
	}

	public function queryCarsAllSeris($stime,$etime,$node){
		$nodeIdStr = $this->parseNodeId($node);
		$arraySeries = array('全车系');
		$queryTimes = $this->parseQueryTime($stime,$etime);
		$ret = array();
		$dataSeriesX = array();
		$dataSeriesY = array();
		$retTotal = array();
		foreach($queryTimes as $queryTime) {
			$ss = $queryTime['stime'];
			$ee = $queryTime['etime'];
			$temp = array();
			foreach($arraySeries as $series) {
				$sql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$ss' AND pass_time <= '$ee' AND node_id IN ($nodeIdStr)";

				$cars = Yii::app()->db->createCommand($sql)->queryScalar();
				$temp[$series] = $cars;
				$dataSeriesY[$series][] = intval($cars);
				$retTotal[$series] += intval($cars);
			}
			$ret[] = array_merge(array('time' => $queryTime['point']), $temp);
			$dataSeriesX[] = $queryTime['point'];
        }
        return array('carSeries' => $arraySeries, 'detail'=>$ret, 'total'=>$retTotal, 'series' => array('x' => $dataSeriesX, 'y' => $dataSeriesY));
	}

	public function queryCarFaults($carId,$nodeName,$series){
		$tables = $this->parseTables($nodeName, $series);
		$dutyList = $this->dutyList();
		$dutyArea = $this->dutyArea();
		$faults = array();
		foreach($tables as $table => $nodeId){
			$sql = "SELECT id, car_id, component_name, fault_mode, `status`, create_time, updator, duty_department
					FROM $table 
					WHERE car_id = $carId";
			$datas = Yii::app()->db->createCommand($sql)->queryAll();
			foreach($datas as &$data){
				$data['duty'] = $dutyList[$data['duty_department']];
				$data['user_name'] = empty($data['updator']) ? "-" : User::model()->findByPk($data['updator'])->display_name;
				$data['node_name'] = NodeAR::model()->findByPk($nodeId)->display_name;
				$data['duty_area'] = $dutyArea[$table];
			}
			$faultClass = $this->getFaultClass($table);
			$faults[$faultClass] = $datas;
		}
		return $faults;
	}

	protected function parseTables($node, $series) {
		$tablePrefixs = array(
			'VQ1_STATIC_TEST' => 10,
			'VQ1_STATIC_TEST_2' => 209,
			'VQ2_ROAD_TEST' => 15,
			'VQ2_LEAK_TEST' => 16,
			'VQ3_FACADE_TEST' => 17,
			'WDI_TEST' => 95,
		);
		$nodeTables = array(
			'VQ1' => 'VQ1_STATIC_TEST',
			'VQ1_2' => 'VQ1_STATIC_TEST_2',
			'ROAD_TEST_FINISH' => 'VQ2_ROAD_TEST',
			'VQ2' => 'VQ2_LEAK_TEST',
			'VQ3' => 'VQ3_FACADE_TEST',
			'WDI' => 'WDI_TEST',
		);

		$temps = array();
		if(empty($node) || $node === 'all') {
			$temps = $tablePrefixs;
		} elseif($node === 'VQ2_ALL') {
			$temps = array(
				'VQ2_ROAD_TEST' => 15,
            	'VQ2_LEAK_TEST' => 16,
			);
		} elseif($node === 'VQ1_ALL') {
			$temps = array(
				'VQ1_STATIC_TEST' => 10,
            	'VQ1_STATIC_TEST_2' => 209,
			);
		} elseif(!empty($nodeTables[$node])) {
			$temps = array($nodeTables[$node]=>$tablePrefixs[$nodeTables[$node]]);
		}

		$tables = array();
		if(empty($series) || $series === 'all') {
			$series = array('F0', 'M6', '6B');
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
			'T0_2'  => 201,
            'VQ1' => 10,
            'VQ1_2' => 209,
			'CHECK_LINE' => 13,
            'ROAD_TEST_FINISH' => 15,
            'VQ2' => 16,
			'VQ2_ALL' => '15,16',
			// 'VQ2_ALL' => '13,15,16',
            'VQ3' => 17,
			'CHECK_IN' => 18,
			'CHECK_OUT' => 19,
			'WDI'  => 95,
			'OutStandby'  => 96,
			'WAREHOUSE_RETURN'=>97,
			'DETECT_SHOP_LEAVE'=>98,
			'DETECT_SHOP_RETURN'=>99,
        );

		if(empty($node) || $node === 'all') {
			return join(',', array_values($nodeIds));
		} else {
			return $nodeIds[$node];
		}

	}

	// private function parseSeries($series) {
	// 	if(empty($series) || $series === 'all') {
 //            $series = array('F0', 'M6', '6B');
 //        } else {
 //            $series = explode(',', $series);
 //        }
	// 	return $series;
	// }

	//modified by wujun
	public function parseQueryTime($stime,$etime) {

		// list($stime, $etime) = $this->reviseSETime($stime, $etime);		//added by wujun
		$s = strtotime($stime);
		$e = strtotime($etime);

		$sd = date('Ymd', $s);
		$ed = date('Ymd', $e);

		$lastHour = ($e - $s) / 3600;
		$lastDay = (strtotime($ed) - strtotime($sd)) / 86400;//days

		$ret = array();
		if($lastHour <= 24) {//hour
			$pointFormat = 'H';
			$format = 'Y-m-d H:i:s';
			$slice = 3600;
		} elseif($lastDay <= 31) {//day
			$pointFormat = 'm-d';
			$format = 'Y-m-d H:i:s';
			$slice = 86400;
		} else {//month
			$pointFormat = 'Y-m';
			$format = 'Y-m-d H:i:s';
			// $slice = 86400 * intval(date('t' ,$s));
		}

		//首个分割段
		$t0 = $s;

		if($pointFormat === 'H'){
			// $t = $t0 + ($slice - ($t0%$slice));
			$eNextH = strtotime('+1 hour', $t0);			//next hour
			$ee = date('Y-m-d H', $eNextH) . ":00:00";
			$t = strtotime($ee);
		} else if($pointFormat === 'm-d'){
			$eNextD = strtotime('+1 day', $t0);		//next day
			$ee = date(('Y-m-d'), $eNextD) . " 08:00:00";
			$t = strtotime($ee);
		} else if($pointFormat === 'Y-m'){
			$lastDay = strtotime(date("Y-m-t", $t0));
			$eNextM = strtotime('+1 day', $lastDay);
			// $eNextM = strtotime('+1 month', $t0);			//next month
			$ee = date('Y-m', $eNextM) . "-01 08:00:00";
			$t = strtotime($ee);
		}

		if($pointFormat === 'H') {
				$point = date($pointFormat, $t0) . '～' . date($pointFormat, $t) . '点';
			} else {
				$point = date($pointFormat, $t0);
		}
		$ret[] = array(
				'stime' => date($format, $t0),
				'etime' => date($format, $t),
				'point' => $point,
		);

		// $t = $s;
		while($t < $e) {
			if($pointFormat === 'H') {
				$point = date($pointFormat, $t) . '～' . date($pointFormat, $t + $slice) . '点';
			} else {
				$point = date($pointFormat, $t);
			}

			//added by wujun
			if($pointFormat === 'Y-m') {
				$eNextM = strtotime('first day of next month', $t);
				// $eNextM = strtotime('+1 month', $t);			//next month			//added by wujun
				$ee = date('Y-m-d', $eNextM) . " 08:00:00";	//next month firstday	//added by wujun
				$etmp = strtotime($ee);	//next month firstday	//added by wujun
			} else {
				$etmp = $t+$slice;
			}
			if($etmp>=$e){
				$etmp=$e;
			}

			$ret[] = array(
				'stime' => date($format, $t),
				'etime' => date($format, $etmp),
				'point' => $point,
			);
			$t = $etmp;
		}

		return $ret;
	}

	private function reviseSETime($stime,$etime) {
		//cancel the reviseSETime
		return array($stime, $etime);

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

	// public function seriesName(){
	// 	$seriesName = array(
	// 		'F0' => 'F0',
	// 		'M6' => 'M6',
	// 		'6B' => '思锐'
	// 	);

	// 	return $seriesName;
	// }

	public function dutyList(){
		$list = array();
		$sql = "SELECT id,name,display_name FROM duty_department";
		$dutyDepartments = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($dutyDepartments as $department){
			$list[$department['id']] = $department['display_name'];
		}
		$list['assembly'] = '总装';
		$list['paint'] = '涂装';
		$list['welding'] = '焊装';
		$list[''] = '-';
		$list['0'] = '-';

		return $list;
	}

	public function dutyArea() {
		$list = array(
			'VQ1_STATIC_TEST_F0' => 'VQ1',
			'VQ1_STATIC_TEST_M6' => 'VQ1',
			'VQ1_STATIC_TEST_6B' => 'VQ1',
			'VQ1_STATIC_TEST_2_F0' => 'VQ1',
			'VQ1_STATIC_TEST_2_M6' => 'VQ1',
			'VQ1_STATIC_TEST_2_6B' => 'VQ1',
			'VQ2_ROAD_TEST_F0' => 'VQ2',
			'VQ2_ROAD_TEST_M6' => 'VQ2',
			'VQ2_ROAD_TEST_6B' => 'VQ2',
			'VQ2_LEAK_TEST_F0' => 'VQ2',
			'VQ2_LEAK_TEST_M6' => 'VQ2',
			'VQ2_LEAK_TEST_6B' => 'VQ2',
			'VQ3_FACADE_TEST_F0' => 'VQ3',
			'VQ3_FACADE_TEST_M6' => 'VQ3',
			'VQ3_FACADE_TEST_6B' => 'VQ3',
			'WDI_TEST_F0' => 'WH',
			'WDI_TEST_M6' => 'WH',
			'WDI_TEST_6B' => 'WH',
		);
		return $list;
	}

	public function getFaultClass($tableName) {
		$splits = explode('_', $tableName);

		$cls = ucwords($splits[0]);
		$total = count($splits);
		for($i = 1; $i < $total; ++ $i) {
			$cls .= ucFirst(strtolower($splits[$i]));
		}

		return $cls . "AR";
	}
}
