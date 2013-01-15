<?php
Yii::import('application.models.AR.monitor.*');

class MonitorSeeker
{
	public function __construct(){
	}

	public function querySeats($section) {
		$sql = "SELECT name FROM node WHERE section='$section' AND type!='device'";
		$seats = Yii::app()->db->createCommand($sql)->queryColumn();
		$ret = array();
		foreach($seats as $seat) {
			$seat = substr($seat, 1);
			$ret[] = sprintf('%02d', $seat);
		}
		sort($ret);
		return $ret;
	}

	public function queryLabel($type, $stime,$etime) {
		$ret = array();
		if(empty($type)) {
			$types = array('production','quality','balance');
			foreach($types as $type) {
				$method = "query" . ucFirst($type) . "Label";
				if(method_exists($this, $method)) {
					$ret[$type] = $this->$method($stime,$etime);
				} 
			}
		} else {
			$method = "query" . ucFirst($type) . "Label";
			if(method_exists($this, $method)) {
				$ret = $this->$method($stime,$etime);
			}
		}
		return $ret;
	}

	public function queryProductionLabel($stime,$etime) {
		//pbs t0 vq1
		$date = date("Y-m-d", strtotime($stime));
		$planCars = $this->queryPlanCars($date);
		return array(
			'PBS' => $this->queryFinishCars($stime,$etime,'PBS'),
			//'T0'  => $this->queryFinishCars($stime,$etime, 'T0') . "/$planCars",
			'T0'  => $this->queryFinishCars($stime,$etime, 'T0'),
			//'VQ1' => $this->queryFinishCars($stime,$etime, 'VQ1') . "/$planCars",
			'VQ1' => $this->queryFinishCars($stime,$etime, 'VQ1'),
		);
	}

	public function queryQualityLabel($stime,$etime) {
		$dpu = $this->queryDPU($stime,$etime);
		$qua = $this->queryQualified($stime,$etime);
		return array(
				//'VQ1' => "$dpu/$qua",
				'VQ1' => "$qua",
			);	
	}

	public function queryBalanceLabel($stime,$etime) {
		return array(
			'PBS' => $this->queryStateCars(array('彩车身库')),
			'VQ1' => $this->queryStateCars(array('VQ1异常','整车下线','出生产车间','检测线缓冲')),
		);
	}

	public function queryBalanceDetail($node) {
		if($node === 'PBS') {
			$states = array('彩车身库');
		} elseif($node === 'VQ1') {
			$states = array('VQ1异常','整车下线','出生产车间','检测线缓冲');
		} elseif($node === 'VQ1-EXCEPTION') {
			$states = array('VQ1异常');
		} elseif($node === 'VQ2') {
			$states = array('VQ2异常.路试','VQ2异常.漏雨');
		} elseif($node === 'VQ3') {
			$states = array('VQ3异常');
		}
		$str = "'" . join("','", $states) . "'";
		$sql = "SELECT series,vin,type,color,modify_time as time FROM car WHERE status IN ($str)";
        return Yii::app()->db->createCommand($sql)->queryAll();
	}

	public function queryStateCars($states,$stime = null, $etime = null) {
		$conditions = array();
		if(!empty($stime)) {
			$conditions[] = "modify_time >= '$stime'";
		}
		if(!empty($etime)) {
            $conditions[] = "modify_time <= '$etime'";
        }   
        $condition = join(' AND ', $conditions);

		$str = "'" . join("','", $states) . "'";
		$sql = "SELECT count(*) FROM car WHERE status IN ($str) $condition";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}

	public function queryPlanCars($date) {
		$seeker = new PlanSeeker();
        $plans = $seeker->search($date, '', '');
        $planCars = 0;
        $finishCars = 0;

        //stat car pass node
        foreach($plans as $plan) {
            $planCars += intval($plan['total']);
            //$finishCars += intval($plan['finished']);
        }
		return $planCars;
	}

	public function queryFinishCars($stime, $etime, $node) {
		$sql = "SELECT id FROM node WHERE name='$node'";
        $nodeId = Yii::app()->db->createCommand($sql)->queryScalar();
        $sql = "SELECT count(distinct car_id) FROM node_trace WHERE pass_time>'$stime' AND pass_time < '$etime' AND node_id=$nodeId";
        $finishCars = Yii::app()->db->createCommand($sql)->queryScalar();
        return $finishCars;
	}

	public function queryLinePauseTime($section, $stime, $etime, $mode = 'all', $type = '') {
		$condition = '';
		if(!empty($section)) {
			$sql = "SELECT id FROM node WHERE section='$section'";
			if($type === 'device') {
				$sql = "SELECT id FROM node WHERE name='$section'";
			} else {
				$sql .= " AND type != 'device'";
			}
			$nodeIds = Yii::app()->db->createCommand($sql)->queryColumn();
			if(empty($nodeIds)) {
				return 0;
			}
			$nodeIdStr = join(',', $nodeIds);
			$condition = "AND node_id IN ($nodeIdStr)";
		}
		$values = array($stime, $etime);
		if($mode == 'without_plan_to_pause' ) {
			$condition .= " AND pause_type != ?";
			$values[] = '计划停线';
		}
		$pauses = LinePauseAR::model()->findAll("pause_time >= ? AND pause_time <= ? $condition", $values);
		$total = 0;
		foreach($pauses as $pause) {
			$ps = strtotime($pause->pause_time);
			$pe = strtotime($pause->recover_time);
			if($pause->status == 1) {
				$pe = time();
			}

			$total += $pe - $ps;
		}

		return $total;
	}

	public function queryPlan($section, $date) {
		$seeker = new PlanSeeker();
		$plans = $seeker->search($date, '', '');
		$planCars = 0;
		$finishCars = 0;

		//stat car pass node
		foreach($plans as $plan) {
			$planCars += intval($plan['total']);
			//$finishCars += intval($plan['finished']);
		}
		if(empty($section)) {
			$node = 'T0';		//modifed by wujun
			$sql = "SELECT id FROM node WHERE name='$node'";
		} else {
			$sql = "SELECT id FROM node WHERE section='$section' AND type='normal'";
		}
		$nodeId = Yii::app()->db->createCommand($sql)->queryScalar();
		$sql = "SELECT count(distinct car_id) FROM node_trace WHERE pass_time>'$date' AND node_id=$nodeId";
		$finishCars = Yii::app()->db->createCommand($sql)->queryScalar();
		
		return array($planCars, $finishCars);
	}

	//run time
	public function queryLineRunTime($stime, $etime) {
		//deleted by wujun
		//$lineRuns = LineRunAR::model()->findAll('event=? AND create_time >= ? AND create_time <=?', array('启动', $stime , $etime));
        //$lineStops = LineRunAR::model()->findAll('event=? AND create_time >= ? AND create_time <=?', array('停止', $stime, $etime));

		//$linePauses = LinePauseAR::model()->findAll("pause_time > ? AND pause_type=?" , array($stime, '计划停线'));

		//$diff = count($lineRuns) - count($lineStops);
		//if($diff > 1 || $diff < 0) {
		//	throw new Exception('line run/stop record error');
		//}
        //$lineRunTime = 0;
		//$trips = count($lineRuns);
        //for($i = 0; $i < $trips; $i ++) {
		//	$lineRun = $lineRuns[$i];	
        //   $lrST = strtotime($lineRun->create_time);
        //    if(!empty($lineStops[$i])) {
		//		$lineStop = $lineStops[$i];
        //        $lrET = strtotime($lineStop->create_time);
		//	} else {
        //        $lrET = time();
        //    }

        //    $lineRunTime += $lrET - $lrST;
		//}

		$lineRunTime = strtotime($etime) - strtotime($stime);	
        $linePauses = LinePauseAR::model()->findAll("pause_time > ? AND pause_type=?" , array($stime, '计划停线'));
		$planPauseTime = 0;
		foreach($linePauses as $linePause) {
			if($linePause->status == 1) {
				$planPauseTime += (time() - strtotime($linePause->pause_time));
			} else {
				$planPauseTime += (strtotime($linePause->recover_time) - strtotime($linePause->pause_time));
			}
		}

		//日常休息时间，此为临时方案，最终将合并到计划停线中，为可维护的“计划停线”，设备将根据
		$restTime = $this->getRestTime($etime);
		$lineRunTime = $lineRunTime - $planPauseTime - $restTime;

		return $lineRunTime;
	}

	//added by wujun
	public function getRestTime($etime) {
		$workDate = DateUtil::getCurDate();
		$thisDate = date("Y-m-d");
		$etimeHM = date("H:i", strtotime($etime));

		$restTime = 0;
		if($etimeHM >= "08:00" && $etimeHM < "11:40"){

		}
		if($etimeHM >= "11:40" && $etimeHM < "12:40"){
			$restTime = strtotime($etime) - strtotime($thisDate . " 11:40:00");
		}
		if($etimeHM >= "12:40" && $etimeHM < "17:00"){
			$restTime = 3600;
		}
		if($etimeHM >= "17:00" && $etimeHM < "18:00"){
			$restTime = 3600 + (strtotime($etime) - strtotime($thisDate . " 18:00:00"));
		}
		if($etimeHM >= "18:00" && $etimeHM < "23:30"){
			$restTime = 7200;
		}
		if($etimeHM >= "23:30" || $etimeHM < "00:30"){
			$restTime = 7200 + (strtotime($etime) - strtotime($workDate . " 23:30:00"));
		}
		if($etimeHM >= "00:30" && $etimeHM < "05:00"){
			$restTime = 10800;
		}
		if($etimeHM >= "05:00" && $etimeHM < "08:00"){
			$restTime = 10800 + (strtotime($etime) - strtotime($thisDate . " 05:00:00"));
		}

		return $restTime;
	}
		

	public function queryLineURate($stime , $etime) {
		//$linePauseTime = $this->queryLinePauseTime('', $stime, $etime, 'without_plan_to_pause');
		//$lineRunTime = $this->queryLineRunTime($stime, $etime);		
		//$rate = '-';
		// if(!empty($lineRunTime)) {
		//	 $rate = intval(100 - 100 * $linePauseTime / $lineRunTime);
		//	 $rate = "$rate%";
		// }
		//added by wujun
		$lineRunTime = $this->queryLineRunTime($stime, $etime);
		$node = 'T0';
		$online = $this->queryFinishCars($stime, $etime, $node);
		$lineSpeed = $this->queryLineSpeed();

		$rate = '-';
		if(!empty($lineRunTime) && !empty($lineSpeed)){			
			$capacity = $lineRunTime / $lineSpeed;
			$rate = intval(100 * $online / $capacity);
			if($rate > 100){
				$rate = "100%";
			} else {
				$rate = "$rate%";	
			}
		}
		
		return $rate;
	}

	public function queryLineStatus($stime , $etime) {
		$lineRun = LineRunAR::model()->find('event=? AND create_time >= ?', array('启动', $stime ));

        $lineStop = LineRunAR::model()->find('event=? AND create_time >= ?', array('停止', $stime));

		$linePause = LinePauseAR::model()->find("status = ? AND pause_time > ?" , array(1,$stime));

		$status = 'play';
		if(empty($lineRun) || !empty($lineStop)) {//
			$status = 'halt';
		} elseif(!empty($linePause)) {
			if($linePause->pause_type === '计划停线') {
				$status = 'white-pause';
			} else {
				$status = 'red-pause';
			}
		}
		return $status;
	}

	public function queryLineSpeed() {
		$sql = "SELECT value FROM device_parameter WHERE name='line_speed'";
		$value = Yii::app()->db->createCommand($sql)->queryScalar();
		return intval($value);
	}

	public function queryPauseSeat($stime , $etime) {
		$linePause = LinePauseAR::model()->find("status = ? AND pause_time > ?" , array(1,$stime));
		$seat = '';
		if(!empty($linePause)) {
			$sql = "SELECT name FROM node WHERE id=" . $linePause->node_id;
			$seat = Yii::app()->db->createCommand($sql)->queryScalar();
		}

		return $seat;
	}

	public function queryLinePauseDetail($stime , $etime) {
		$sections = array(
			'T1','T2','T3','C1','C2','F1','F2','VQ1','L1','EF1','EF2','EF3'
		);
		$ret = array();
		$ret['total'] = 0;
		foreach($sections as $section) {
			$type = '';
			if(in_array($section, array('L1','EF1','EF2','EF3'))) {
				$type = 'device';
			}
			$time = $this->queryLinePauseTime($section, $stime, $etime, 'all', $type);
			
			$ret[$section] = intval($time / 60);
			$ret['total'] += $ret[$section];
		}
		//merge L1, EF1 EF2 EF3
		$ret['device'] = $ret['L1'] + $ret['EF1'] + $ret['EF2'] + $ret['EF3'];
        return $ret;
    }


	public function queryDPU($stime, $etime, $node = 'VQ1') {
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
		
		$cars = 0;
		$total = 0;
		$nodeIdStr = $this->parseNodeId($node);
		$arraySeries = $this->parseSeries('all');

		foreach($arraySeries as $series) {
			$tables = $this->parseTables($node,$series);
			foreach($tables as $table=>$nodeName) {
				$countSql = "(SELECT count(*) FROM $table $condition)";
				$total += Yii::app()->db->createCommand($countSql)->queryScalar();
			}
			$sql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$stime' AND pass_time <= '$etime' AND node_id IN ($nodeIdStr) AND car_series='$series'";
			$cars += Yii::app()->db->createCommand($sql)->queryScalar();
				
        }
		$dpu = '-';
		if(!empty($cars)) {
			$dpu = round($total / $cars, 2);
		}
		return $dpu;
	}

	public function queryQualified($stime, $etime, $node = "VQ1") {
		$cars = 0;
        $faults = 0;
        $nodeIdStr = $this->parseNodeId($node);
        $arraySeries = $this->parseSeries('all');


		$conditions = array("status != '在线修复'");
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
			$faults += count($datas);

			$sql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time >= '$stime' AND pass_time <= '$etime' AND node_id IN ($nodeIdStr) AND car_series = '$series'";
			$cars += Yii::app()->db->createCommand($sql)->queryScalar();
		}  
		$rate = empty($cars) ? 0 : round(($cars - $faults) / $cars, 3);
		$rate = empty($cars) ? '-' : $rate * 100 . "%";


		return $rate;
	}

	public function queryOtherCall($section, $curDay) {
		//$calls = AndonCallAR::model()->findAll("status = ? AND call_time > ? ORDER by call_time DESC", array(1, $curDay));

		$pause = LinePauseAR::model()->find("status = ? AND pause_time > ?" , array(1,$curDay));


		
		$sql = "SELECT id FROM node WHERE section='$section'";
		$nodeIds = Yii::app()->db->createCommand($sql)->queryColumn();

		$callStatus = '';
		//foreach($calls as $call) {
		//	if(!in_array($call->node_id, $nodeIds)) {
		//		$callStatus[] = $this->mapSection($call->node_id) . $call->call_type;
		//	}
		//}
		if(!empty($pause) && !in_array($pause->node_id, $nodeIds)) {
			$sql = "SELECT name FROM node WHERE id={$pause->node_id}" ;
			$seat = Yii::app()->db->createCommand($sql)->queryScalar();
			$callStatus = $seat . $pause->pause_type;
		}

		return $callStatus;
	}

	public function queryCallStatus($section, $curDay) {
		$condition = "";	
		if(!empty($section)) {
            $sql = "SELECT id FROM node WHERE section='$section'";
            $nodeIds = Yii::app()->db->createCommand($sql)->queryColumn();
            if(empty($nodeIds)) {
                return array();
            }
            $nodeIdStr = join(',', $nodeIds);
            $condition = "AND node_id IN ($nodeIdStr)";
        }

	

		$calls = AndonCallAR::model()->findAll("status = ? AND call_time > ? $condition ORDER by call_time DESC", array(1, $curDay));


		$pause = LinePauseAR::model()->find("status = ? AND pause_time > ?" , array(1,$curDay));

		$seatStatus = array();
		$sectionStatus = array();
		foreach($calls as $call) {
			$otherSection = $this->mapSection($call->node_id);
			list($fullSeat, $seat) = $this->mapSeat($call->node_id);
			if(in_array($fullSeat,array('L1','EF1','EF2','EF3'))) {
				$otherSection = $fullSeat;
			}
			$callType = $this->mapCallType($call->call_type);
			if($call->call_type === '质量关卡') {
				$multi = !empty($seatStatus[$call->node_id]);
				$seatStatus[$call->node_id] = array(
                        'node_id' => $call->node_id,
                        'seat' => $seat,
                        'full_seat' => $fullSeat,
						'section' => $otherSection,
                        'background_text' => 'VQ1',
                        'background_font_color' => 'red',
                        'foreground_text' => $seat,
                        'foreground_font_color' => 'black',
                        'foreground_color' => 'yellow',
                        'multi' => $multi,
                        'flash_type' => $multi ? 'fast' : 'normal',
                        );


				$sectionStatus['VQ1'] = array(
					'section' => 'VQ1',
                    'type' => 'flash',
                    'background_text' => $otherSection,
					'background_font_color' => 'red',
					'foreground_text' => 'Q1',
					'foreground_font_color' => 'black',
					'foreground_color' => 'yellow',
                );
			} else {
				if(isset($seatStatus[$call->node_id])) {
					$seatStatus[$call->node_id]['multi'] = true;
					$seatStatus[$call->node_id]['flash_type'] = 'fast';
					continue;
				}
				$flashType = ($callType === 'A' || $callType === '设备故障' ) ? 'normal' : 'block';
				$seatStatus[$call->node_id] = array(
						'node_id' => $call->node_id,
						'seat' => $seat,
						'full_seat' => $fullSeat, 
						'section' => $otherSection,
						'background_text' => $callType === '设备故障' ? '&nbsp;' : $callType,
						'background_font_color' => $callType === 'A' ? 'yellow' : 'red',
						'foreground_text' => $callType === '设备故障' ? $fullSeat : $seat,
						'foreground_font_color' => 'black',
						'foreground_color' => 'yellow',
						'multi' => false,
						'flash_type' => $flashType,
						);
		
				$sectionStatus[$otherSection] = array(
                        'section' => $otherSection,
                        'type' => 'block',
                        'background_text' => '&nbsp;',
                        'background_font_color' => 'red',
                        'foreground_text' => $otherSection,
                        'foreground_font_color' => 'red',
                        'foreground_color' => 'grey',
                    );
			}
		}

		if(!empty($pause) ) {
			$otherSection = $this->mapSection($pause->node_id);
			list($fullSeat, $seat) = $this->mapSeat($pause->node_id);
            if(in_array($fullSeat,array('L1','EF1','EF2','EF3'))) {
                $otherSection = $fullSeat;
            }

			if(!empty($seatStatus[$pause->node_id])) {
				$seatStatus[$pause->node_id]['background_font_color'] = 'red';
				$seatStatus[$pause->node_id]['background_text'] = intval((time() - strtotime($pause->pause_time)) / 60);
				$seatStatus[$pause->node_id]['foreground_font_color'] = 'white';
				$seatStatus[$pause->node_id]['foreground_color'] = 'red';
				$seatStatus[$pause->node_id]['multi'] = false;
				$seatStatus[$pause->node_id]['flash_type'] = 'red';
			} else {
				$seatStatus[$pause->node_id] = array(
                        'node_id' => $pause->node_id,
                        'seat' => $seat,
						'section' => $otherSection,
						'full_seat' => $fullSeat,
                        'background_text' => intval((time() - strtotime($pause->pause_time)) / 60),
                        'background_font_color' => 'red',
                        'foreground_text' => $seat === '00' ? $fullSeat : $seat ,
                        'foreground_font_color' => 'white',
                        'foreground_color' => 'red',
						'multi' => isset($seatStatus[$pause->node_id]['multi']) ? $seatStatus[$pause->node_id]['multi'] : false,
						'flash_type' => 'red',
                        );

			}
			$otherSection = $this->mapSection($pause->node_id);
			if(in_array($fullSeat,array('L1','EF1','EF2','EF3'))) {
                $otherSection = $fullSeat;
            }
			if($otherSection !== $section) {
				$sectionStatus[$otherSection] = array(
						'section' => $otherSection,
						'type' => 'block',
						'background_text' => '',
						'background_font_color' => 'red',
						'foreground_text' => $otherSection,
						'foreground_font_color' => 'red',
						'foreground_color' => 'grey',
					);
			}
		} 
		
		$runStatus = $this->queryLineStatus($curDay, '');

		$retSeats = array_values($seatStatus);
		if(!empty($section)) {
			$retSeats = array();
			foreach($seatStatus as $status) {
				if($status['section'] === $section || in_array($status['section'],array('L1','EF1','EF2','EF3'))) {
					$retSeats[] = $status;
				}
			}
		}

		return array('seatStatus' => $retSeats, 'sectionStatus' => $sectionStatus, 'lineStatus' => $runStatus);
	}

	protected function mapSection($nodeId) {
		$sql = "SELECT section FROM node WHERE id=$nodeId";
        $section = Yii::app()->db->createCommand($sql)->queryScalar();
        return $section;
	}
	
	protected function mapSeat($nodeId) {
		$sql = "SELECT name,type FROM node WHERE id=$nodeId";
		$seat = Yii::app()->db->createCommand($sql)->queryRow();
	
		$name = $seat['name'];	
		if($seat['type'] !== 'device') {
			$name = sprintf('%02d', substr($name, 1));
		} else {
			$name = '00';
		}
		return array($seat['name'], $name);
	}

	

	protected function mapCallType($callType) {
		if($callType === '工位求助') {
			return 'A';
		}
		if($callType === '工段质量') {
            return 'QS';
        }
		if($callType === '质量关卡') {
            return 'QG';
        }
		if($callType === '设备故障') {
            return '设备故障';
        }

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

}
