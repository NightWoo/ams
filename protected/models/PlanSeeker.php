<?php
Yii::import('application.models.AR.PlanAR');
Yii::import('application.models.Car');
Yii::import('application.models.ConfigSeeker');
class PlanSeeker
{
	public function __construct() {
	}

	public function search($date, $series, $line, $exactly = true, $matchSerach=false) {
        $datetime = strtotime($date);
        $date = date('Y-m-d',$datetime);

		if($exactly) {
			$condition = "plan_date=?";
		} else {
			$condition = "plan_date>=?";
		}
		$values = array($date);

		if(!empty($series)) {
			$seriesArray = Series::parseSeries($series);
			$cc = join("','", $seriesArray);

			$condition .= " AND car_series IN ('$cc')";
			// $values[] = $series;
		}
		if(!empty($line)) {
            $condition .= " AND assembly_line=?";
			$values[] = $line;
        }
        if($matchSerach){
	        $condition .= " AND is_frozen=0";
        }
        //modifed by wujun
        $plans = PlanAR::model()->findAll($condition . ' ORDER BY plan_date, priority ASC', $values);

        $datas = array();
        $seeker = new ConfigSeeker();
        foreach ($plans as $plan) {
            $temp = $plan->getAttributes();
            $temp['config_name'] = $seeker->getName($temp['config_id']);
            $temp['car_type_name'] = $this->cutCarType($temp['car_type']);
            $datas[] = $temp;
        }

        return $datas;
    }

	//added by wujun
	public function generateBatchNumber($planDate) {
		$date = strtotime($planDate);
		$year = date("Y", $date);
		$yearCode = CarYear::getYearCode($year);
		$monthDay = date("md", $date);

		$ret = $yearCode . $monthDay;

		$sql = "SELECT batch_number FROM plan_assembly WHERE batch_number LIKE '$ret%' ORDER BY batch_number DESC";
		$lastSerial = Yii::app()->db->createCommand($sql)->queryScalar();
		$lastKey = intval(substr($lastSerial, 5 , 3));

		$ret .= sprintf("%03d", (($lastKey + 1) % 1000));

		return $ret;

	}

	//added by wujun
	public function query($stime, $etime, $series, $line, $curPage=0, $perPage=0) {
		if(empty($stime) || empty($etime)){
			throw new Exception("起始时间和结束时间均不可为空", 1);
		} else {
			$s = strtotime($stime);
			$e = strtotime($etime);
			$sdate = date('Y-m-d', $s);
			$edate = date('Y-m-d', $e);
		}

		if($sdate > $edate) {
			throw new Exception("起始时间不能大于结束时间！", 1);
		} else {
			$condition = "plan_date>='$sdate' AND plan_date<='$edate'";
		}

		$values = array($sdate, $edate);

		$arraySeries = Series::parseSeries($series);

		if(!empty($line)) {
            $condition .= " AND assembly_line='$line'";
			$values[] = $line;
        }

        $sqls=array();
        foreach($arraySeries as $series) {
        	$sqls[] = "SELECT * FROM plan_assembly WHERE $condition AND car_series='$series'";
        }
        $sql = join(' UNION ', $sqls);

       $limit = "";
        if(!empty($perPage)) {
            $offset = ($curPage - 1) * $perPage;
            $limit = "LIMIT $offset, $perPage";
        }

        $sql .= " ORDER BY plan_date, batch_number ASC $limit";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();

        $countSql = "SELECT count(*) FROM plan_assembly WHERE $condition";
		$total = Yii::app()->db->createCommand($countSql)->queryScalar();

		$seeker = new ConfigSeeker();
		foreach($datas as &$data) {
			if($data['car_series'] == '6B'){
				$data['car_series'] = '思锐';
			}
			$data['config_name'] = $seeker->getName($data['config_id']);
			$data['car_type_name'] = $this->cutCarType($data['car_type']);

			$data['cold'] = $data['cold_resistant'] == 1 ? "耐寒" : "非耐寒";
		}

        return array($total,  $datas);
	}

	public function queryCompletion($stime, $etime, $series, $line) {
		$arraySeries = Series::parseSeries($series);
		$queryTimes = $this->parseQueryTime($stime, $etime);
		$detail = array();
		$dataSeriesX = array();
		$dataSeriesY = array();
		$retTotal = array();
		foreach($arraySeries as $series){
			if($series == '6B'){
				$retTotal['思锐'] = 0;
				$retTotal['思锐'] = array(
									'readyTotal' => 0,
        							'totalTotal' => 0,
        							'completionTotal' => 0,
								);
			}else {
				$retTotal[$series] = 0;
				$retTotal[$series] = array(
									'readyTotal' => 0,
        							'totalTotal' => 0,
        							'completionTotal' => 0,
								);
			}
		}
		foreach($queryTimes as $queryTime) {
			$ss = $queryTime['stime'];
			$ee = $queryTime['etime'];
			$cc = array("assembly_line='$line'");
			if(!empty($ss)) {
				$cc[] = "plan_date>='$ss'";
			}
			if(!empty($ee)) {
				$cc[] = "plan_date<'$ee'";
			}
			$con = join(' AND ', $cc);
			if(!empty($con)) {
				$con = 'WHERE ' . $con;
			}
			$temp = array();
			foreach($arraySeries as $series) {

				$condition = $con . " AND car_series='$series'";
				$sql = "SELECT SUM(total) FROM plan_assembly $condition";
				$totalSum = Yii::app()->db->createCommand($sql)->queryScalar();

				$sql = "SELECT SUM(ready) FROM plan_assembly $condition";
				$readySum = Yii::app()->db->createCommand($sql)->queryScalar();

				$rate = empty($totalSum) ? null : round($readySum/$totalSum , 2);
				if($series == '6B'){
					$temp['思锐'] = array(
						'completion' => empty($totalSum) ? '-' : $rate * 100 ."%",
						'readySum' => empty($readySum) ? 0 : $readySum,
						'totalSum' => empty($totalSum) ? 0 : $totalSum,
					);
					$dataSeriesY['思锐'][] = $rate;
					$retTotal['思锐']['totalTotal'] += $readySum;
					$retTotal['思锐']['readyTotal'] += $totalSum;
				}else {
					$temp[$series] = array(
						'completion' => empty($totalSum) ? '-' : $rate * 100 ."%",
						'readySum' => empty($readySum) ? 0 : $readySum,
						'totalSum' => empty($totalSum) ? 0 : $totalSum,
					);
					$retTotal[$series]['totalTotal'] += $readySum;
					$retTotal[$series]['readyTotal'] += $totalSum;
					$dataSeriesY[$series][] = $rate;
				}
			}
			$detail[] = array_merge(array('time' => $queryTime['point']), $temp);
			$dataSeriesX[] = $queryTime['point'];
		}
		foreach($arraySeries as $series){
			if($series == '6B'){
				$retTotal['思锐']['completionTotal'] = empty($retTotal['思锐']['totalTotal']) ? '-' : ($retTotal['思锐']['totalTotal']/$retTotal['思锐']['totalTotal']) * 100 ."%";
			} else {
				$retTotal[$series]['completionTotal'] = empty($retTotal[$series]['totalTotal']) ? '-' : ($retTotal[$series]['totalTotal']/$retTotal[$series]['totalTotal']) * 100 ."%";
			}
		}

		foreach($arraySeries as $key => $series){
        	if($series == '6B') $arraySeries[$key] = '思锐';
        }

		return  array(
					'carSeries' => $arraySeries,
					'detail' => $detail,
					'total' => $retTotal,
					'series' => array(
									'x' => $dataSeriesX,
									'y' => $dataSeriesY,
								)
				);
	}

	// private function parseSeries($series) {
	// 	if(empty($series) || $series === 'all') {
 //            $series = array('F0', 'M6', '6B');
 //        } else {
 //            $series = explode(',', $series);
 //        }
	// 	return $series;
	// }

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

	private function reviseSETime($stime, $etime) {
		$format = 'Y-m-d';
		$stime = date($format, strtotime($stime));
		$etime = date($format, strtotime($etime));

		$s = strtotime($stime);
		$e = strtotime($etime);

		$lastDay = (strtotime($etime) - strtotime($stime)) / 86400;//days

		if($lastDay > 31) {
			$stime = date('Y-m', $s) . '-01';
			$etime = date('Y-m-t', $e);
		}

		return array($stime, $etime);
	}

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
