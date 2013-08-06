<?php
Yii::import('application.models.AR.NodeAR');
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.LaneAR');
Yii::import('application.models.AR.WarehouseAR');
Yii::import('application.models.AR.monitor.LinePauseAR');
Yii::import('application.models.PauseSeeker');

class ReportSeeker
{
	public function __construct(){
	}

	private static $NODE_BALANCE_STATE = array(
		'PBS' => array('彩车身库'),
		'onLine' => array('T1工段' ,'T2工段', 'T3工段', 'C1工段', 'C2工段', 'F1工段', 'F2工段', 'VQ1检验'),
		'onLine-2' => array('II_T1工段' ,'II_T2工段', 'II_T3工段', 'II_C1工段', 'II_C2工段', 'II_F1工段', 'II_F2工段', 'II_VQ1检验'),
		'onLine-all' => array('T1工段' ,'T2工段', 'T3工段', 'C1工段', 'C2工段', 'F1工段', 'F2工段', 'VQ1检验'),
		'onLine-2' => array('II_T1工段' ,'II_T2工段', 'II_T3工段', 'II_C1工段', 'II_C2工段', 'II_F1工段', 'II_F2工段', 'II_VQ1检验', 'II_T1工段' ,'II_T2工段', 'II_T3工段', 'II_C1工段', 'II_C2工段', 'II_F1工段', 'II_F2工段', 'II_VQ1检验'),
		'VQ1' => array('VQ1异常','VQ1退库'),
		'VQ1-NORMAL' => array('VQ1异常'),
		'VQ1-RETURN'=> array('VQ1退库'),
		'VQ2' => array('VQ1合格', '出生产车间', '检测线缓冲','VQ2检测线', 'VQ2路试', 'VQ2淋雨', 'VQ2异常.路试', 'VQ2异常.漏雨', 'VQ2退库'),
		'VQ2-NORMAL' => array('VQ1合格', '出生产车间', '检测线缓冲','VQ2检测线', 'VQ2路试', 'VQ2淋雨', 'VQ2异常.路试', 'VQ2异常.漏雨'),
		'VQ2-RETURN'=> array('VQ2退库'),
		'VQ3' => array('VQ3检验' ,'VQ3合格', 'VQ3异常','VQ3退库'),
		'VQ3-OK' => array('VQ3合格'),
		'VQ3-NORMAL' => array('VQ3检验' ,'VQ3合格', 'VQ3异常'),
		'VQ3-RETURN'=> array('VQ3退库'),
		'recycle' => array('VQ1异常','VQ1合格', 'VQ1退库', '出生产车间', '检测线缓冲','VQ2检测线','VQ2路试', 'VQ2淋雨', 'VQ2异常.路试', 'VQ2异常.漏雨' , 'VQ2退库', 'VQ3检验' ,'VQ3合格', 'VQ3异常', 'VQ3退库'),
		'WH' => array('成品库','WDI'),
		'WHin' => array('成品库'),
		'WH-0' =>array('成品库'),
		'WH-27-export' =>array('成品库'),
		'WH-27-normal' =>array('成品库'),
		'WH-35' =>array('成品库'),
		'WH-X' =>array('成品库'),
		'WH-WDI' =>array('WDI'),
		'assembly' => array('T1工段' ,'T2工段', 'T3工段', 'C1工段', 'C2工段', 'F1工段', 'F2工段', 'VQ1检验','VQ1异常','VQ1合格', '出生产车间' , 'VQ1退库', '检测线缓冲','VQ2检测线','VQ2路试', 'VQ2淋雨', 'VQ2异常.路试', 'VQ2异常.漏雨' , 'VQ2退库', 'VQ3检验' ,'VQ3合格', 'VQ3异常' , 'VQ3退库','成品库'),
	);

	private static $COUNT_POINT_DAILY = array(
		"assemblyPlan1"=>"计划",
		"assemblyCount1" => "完成",
		"completion1" => "完成率",
		"assemblyPlan2"=>"计划",
		"assemblyCount2" => "完成",
		"completion2" => "完成率",
		"warehouseCount" => "入库",
		"distributeCount" => "出库",
		"onlineBalance" => "在制车",
		"recycleBalance" => "周转车",
		"warehouseBalance" => "库存",
		"assemblyMonth" => "上线",
		"warehouseMonth" => "入库",
		"distributeMonth" => "出库",
	);

	private static $COLD_RESISTANT = array('非耐寒','耐寒');

	private static $SERIES_NAME = array(
			'F0' => 'F0',
			'M6' => 'M6',
			'6B' => '思锐'
	);

	private static $PAUSE_CAUSE_TYPE = array(
		"生产组织","品质异常","物料供给","设备故障","其他"
	);

	private static $RECYCLE_BALANCE_STATE = array(
		"onLine" => "I线",
		"onLine-2" => "II线",
		"VQ1" => "VQ1",
		"VQ2" => "VQ2",
		"VQ3" => "VQ3",
	);


	public function queryManufactureDaily($date) {
		list($stime, $etime) = $this->reviseDailyTime($date);
		list($sMonth, $eDate) = $this->reviseDailyMonth($date);
		$countArray = array();
		foreach(self::$COUNT_POINT_DAILY as $key => $name){
			foreach(self::$SERIES_NAME as $series => $seriesName){
				$countArray[$key][$series] = null;
			}
		}

		$countArray["assemblyCount1"] = $this->countCarByPoint($stime, $etime, "assembly", "I");
		$countArray["assemblyCount2"] = $this->countCarByPoint($stime, $etime, "assembly", "II");

		

		$countArray["warehouseCount"] = $this->countCarByPoint($stime, $etime, "warehouse");
		$countArray["distributeCount"] = $this->countCarByPoint($stime, $etime, "distribute");

		$countArray["assemblyMonth"] = $this->countCarByPoint($sMonth, $eDate, "assembly");
		$countArray["warehouseMonth"] = $this->countCarByPoint($sMonth, $eDate, "warehouse");
		$countArray["distributeMonth"] = $this->countCarByPoint($sMonth, $eDate, "distribute");

		$countArray["recycleBalance"] = array("F0"=>"","M6"=>"","6B"=>"");
		$countArray["warehouseBalance"] = array("F0"=>"","M6"=>"","6B"=>"");
		$curDate = DateUtil::getCurDate();
		if(strtotime($date) < strtotime($curDate)){
			$nextDay = date("Y-m-d", strtotime('+1 day', strtotime($date)));
			$countArray["onlineBalance"] = $this->queryOnlineBalanceGroupBySeries($date, $nextDay);
			$countArray["recycleBalance"] = $this->queryRecycleBalanceGroupBySeries($date, $nextDay);
			$countArray["warehouseBalance"] = $this->queryWarehouseBalanceGroupBySeries($date, $nextDay);
		} else {
			$countArray["onlineBalance"] = $this->countCarByState("online-all");
			$countArray["recycleBalance"] = $this->countCarByState("recycle");
			$countArray["warehouseBalance"] = $this->countCarByState("WH");
		}

		$sPlanDate = substr($stime, 0, 10);
		$ePlanDate = substr($etime, 0, 10);
		
		$lineArray = array(1=>"I",2=>"II");
		foreach($lineArray as $i => $line){
			$completion = $this->queryPlanCompletion($sPlanDate, $ePlanDate, $line);
			$assemblyPlanText = "assemblyPlan" . $i;
			$completionText = "completion" . $i;
			foreach(self::$SERIES_NAME as $series => $seriesName){
				$countArray[$completionText][$series] = $completion[$series]['completion'];
				$countArray[$assemblyPlanText][$series] = $completion[$series]['total'];
			} 
		}

		foreach($countArray as $point => &$count){
			$sum = "";
			foreach($count as &$seriesCount){
				$sum += $seriesCount;
				if($point == "completion1" || $point == "completion2"){
					if(!is_null($seriesCount)){
						$seriesCount = $seriesCount == 0 ? 0 : $seriesCount*100 . "%";
					}
				}
				if(is_null($seriesCount)) $seriesCount = "-";
			}
			$count['sum'] = $sum === "" ? "-" : $sum;
		}

		$countArray["completion1"]['sum'] = empty($countArray["assemblyPlan1"]['sum']) ? "-" : ($countArray["assemblyCount1"]['sum'] == 0 ? 0 : round($countArray["assemblyCount1"]['sum']/$countArray["assemblyPlan1"]['sum'], 2)*100 . "%");
		$countArray["completion2"]['sum'] = empty($countArray["assemblyPlan2"]['sum']) ? "-" : ($countArray["assemblyCount2"]['sum'] == 0 ? 0 : round($countArray["assemblyCount2"]['sum']/$countArray["assemblyPlan2"]['sum'], 2)*100 . "%");

		$countSeries = $seriesName = self::$SERIES_NAME;
		$countSeries['sum'] = "总计";
		$ret = array(
			"countPoint" => self::$COUNT_POINT_DAILY,
			"countSeries" => $countSeries,
			"count" => $countArray,
			"carSeries" => array_values($seriesName),
		);

		return $ret;
	}

	public function queryCompletion($date, $timespan){
		list($stime, $etime) = $this->reviseTime($date, $timespan);
		$timeArray = $this->parseQueryTime($stime, $etime, $timespan);
		$seriesArray = self::$SERIES_NAME;
		$countDetail = array();
		$countTotal = array();
		$completionDetail = array();
		$completionTotal =array(
			"totalSum" => 0,
			"readySum" => 0,
			"completion" => 0,
		);
		$columnSeriesX = array();
		$columnSeriesY = array();
		$lineSeriesY = array();
		foreach($seriesArray as $series => $seriesName){
			$countTotal[$seriesName] = 0;
			$columnSeriesY[$seriesName] = array();
		}

		foreach($timeArray as $queryTime){
			$curDate = DateUtil::getCurDate() . " 08:00:00";
			if($queryTime['stime']<$curDate){
				$sDate = substr($queryTime['stime'], 0, 10);
				$eDate = substr($queryTime['etime'], 0, 10);
				$completionArray = $this->queryPlanCompletion($sDate,$eDate);
				$assemblyCount = $this->countCarByPoint($queryTime['stime'], $queryTime['etime'], 'assembly');
				$readySum = 0;
				$totalSum = 0;
				foreach($completionArray as $series => $count){
					// $columnSeriesY[$seriesArray[$series]][] = $count['ready'];
					// $countTmp[$seriesArray[$series]] =  $count['ready'];
					// $countTotal[$seriesArray[$series]] += $count['ready'];

					$readySum += $count['ready'];
					$totalSum += $count['total'];
				}
				foreach($seriesArray as $series => $seriesName){
					$columnSeriesY[$seriesName][] = $assemblyCount[$series];
				}
				$rate = empty($totalSum) ? null : round(($readySum/$totalSum) , 2);
				$lineSeriesY[] = $rate;
			} else {
				foreach($seriesArray as $series => $seriesName){
					$columnSeriesY[$seriesName][] = null;
				}
				$lineSeriesY[] = null;
			}

			$columnSeriesX[] = $queryTime['point'];
		}

		$ret = array(
			"carSeries" => array_values(self::$SERIES_NAME),
			"series" => array(
				'x' => $columnSeriesX,
				'column' => $columnSeriesY,
				'line' => $lineSeriesY,
			),
		);

		return $ret;
	}

	public function queryPlanCompletion($sDate, $eDate, $line=""){
		$sql = "SELECT car_series as series, SUM(total) as total, SUM(ready) as ready FROM plan_assembly WHERE plan_date>='$sDate' AND plan_date<'$eDate'";
		if(!empty($line)) $sql .= " AND assembly_line='$line'";
		$sql .= " GROUP BY series";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		$count = array(
			"F0"=>array(),
			"M6"=>array(),
			"6B"=>array(),
		);
		foreach($count as $key => &$one){
			$one = array('total'=>null, 'ready'=>null, 'completion'=>null);
		}
		foreach($datas as $data){
			$totalValue = intval($data['total']);
			$readyValue = intval($data['ready']);
			$count[$data['series']]['total'] = isset($data['total']) ? $totalValue : 0;
			$count[$data['series']]['ready'] = isset($data['ready']) ? $readyValue : 0;
			$count[$data['series']]['completion'] = empty($totalValue) ? null : round(($readyValue/$totalValue) , 2);
		}

		return $count;
	}

	public function queryManufactureUse($date, $timespan){
		list($stime, $etime) = $this->reviseTime($date, $timespan);
		$timeArray = $this->parseQueryTime($stime, $etime, $timespan);
		$causeArray = self::$PAUSE_CAUSE_TYPE;

		$columnSeriesX = array();
		$columnSeriesY = array();
		$lineSeriesY = array();
		foreach($causeArray as $cause){
			$pauseTotal[$cause] = 0;
			$columnSeriesY[$cause] = array();
		}
		$pauseTotal['总计'] = 0;

		foreach($timeArray as $queryTime){
			$curDate = DateUtil::getCurDate() . " 08:00:00";
			if($queryTime['stime']<$curDate){
				$pauseTmp = array();
				$useTmp = array();
				$howlongSum = 0;
				$causeDistribute = $this->queryPauseCauseDistribute($queryTime['stime'], $queryTime['etime']);
				$useTmp = $this->queryUseRate($queryTime['stime'], $queryTime['etime']);
				foreach($causeDistribute as $causeType => $howlong){
					$columnSeriesY[$causeType][] = $howlong;
				}
				$lineSeriesY[] = $useTmp['useRate'];
			} else {
				foreach($causeArray as $causeType){
					$columnSeriesY[$causeType][] = null;
				}
				$lineSeriesY[] = null;
			}

			$columnSeriesX[] = $queryTime['point'];
		}

		$pauseDetail = array();
		if($timespan == "monthly") $pauseDetail = $this->queryPauseDetail($date);

		$ret = array(
			"causeArray" => $causeArray,
			"pauseDetail" => $pauseDetail,
			"series" => array(
				'x' => $columnSeriesX,
				'column' => $columnSeriesY,
				'line' => $lineSeriesY,
			),
		);

		return $ret;
	}

	public function queryPauseRecourd($stime, $etime){
		$sql = "SELECT id, cause_type, pause_time, recover_time, TIMESTAMPDIFF(second,pause_time,recover_time) AS howlong FROM pause WHERE pause_type!='计划停线' AND pause_time>='$stime' AND pause_time<'$etime' AND recover_time>'0000-00-00 00:00:00'";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		return $datas;
	}

	public function queryPauseCauseDistribute($stime, $etime){
		$datas = $this->queryPauseRecourd($stime, $etime);

		$causeDistribute = array();
		foreach(self::$PAUSE_CAUSE_TYPE as $causeType){
			$causeDistribute[$causeType] = null;
		}
		foreach($datas as $data){
			if($data['cause_type'] == ""){
				$causeDistribute['其他'] += $data['howlong'];
			} else {
				$causeDistribute[$data['cause_type']] += $data['howlong'];
			}
		}

		return $causeDistribute;
	}

	public function queryUseRateBase($stime, $etime){

		$datas = $this->queryShiftRecord($stime, $etime);
		$use = array();

		$capacity = null;
		$prodution = null;
		$runTime = null;
		foreach($datas as $data){
			$capacity += $data['capacity'];
			$prodution += $data['prodution'];
			$runTime += $data['run_time'];
		}

		$rate = empty($capacity)? null : round($prodution / $capacity, 2);

		$use['runTime'] = $runTime;
		$use['useRate'] = $rate;
		$use['capacity'] = $capacity;
		$use['prodution'] = $prodution;

		return $use;
	}

	public function queryUseRate($stime, $etime){

		$data = $this->queryCapacityDaily($stime, $etime);
		$use = array();

		$capacity = null;
		$prodution = null;
		$runTime = null;
		if(!empty($data)){
			$capacity = $data['capacity'];
			$prodution = $data['prodution'];
			$runTime = $data['run_time'];
		}

		$rate = empty($capacity)? null : round($prodution / $capacity, 2);

		$use['useRate'] = $rate;
		$use['runTime'] = $runTime;
		$use['capacity'] = $capacity;
		$use['prodution'] = $prodution;

		return $use;
	}

	public function queryCapacityDaily($stime, $etime, $line=""){
		$sDate = substr($stime, 0, 10);
		$eDate = substr($etime, 0, 10);

		$condition = " work_date>='$sDate' AND work_date<'$eDate'";
		if(!empty($line)){
			$condition .= " AND line='$line'";
		}

		$sql = "SELECT SUM(capacity) AS capacity, SUM(run_time) AS run_time FROM manufacture_capacity_daily WHERE $condition";
		$datas = Yii::app()->db->createCommand($sql)->queryRow();
		$datas['prodution'] = $this->countOnline($stime, $etime);

		return $datas;
	}

	public function queryShiftRecord($stime, $etime, $line=""){
		$sDate = substr($stime, 0, 10);
		$eDate = substr($etime, 0, 10);

		$condition = " shift_date>='$sDate' AND shift_date<'$eDate'";
		if(!empty($line)){
			$condition .= " AND line='$line'";
		}

		$sql = "SELECT * FROM shift_record WHERE $condition";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		foreach($datas as &$data){
			$runTime = strtotime($data['end_time']) - strtotime($data['start_time']) - 7199;
			$sql = "SELECT SUM(TIMESTAMPDIFF(second,pause_time,recover_time)) AS plan_pause_time FROM pause WHERE pause_time>='{$data['start_time']}' AND pause_time<='{$data['end_time']}' AND pause_type='计划停线'";
			$planPauseTime = Yii::app()->db->createCommand($sql)->queryScalar();
			$data['run_time'] = intval($runTime - $planPauseTime);
			$data['capacity'] = intval($data['run_time'] / $data['line_speed']);
			$data['prodution'] = $this->countOnline($data['start_time'], $data['end_time']);
		}

		return $datas;
	}

	public function queryPauseDetail($date) {
		list($stime, $etime) = $this->reviseDailyTime($date);
		// $seeker = new PauseSeeker();
		// $curPage = 1;
		// $perPage = 10;
		// $orderBy = "ORDER BY howlong DESC";
		// list($total, $datas) = $seeker->query($stime, $etime, "", "", "", "", "", $curPage, $perPage, $orderBy);
		$conditions = array();
		$conditions[] = "(pause_type = '紧急停止' OR pause_type = '设备故障' OR pause_type = '质量关卡' OR pause_type = '工位呼叫')";
		$conditions[] = "pause_time>='$stime' AND pause_time<'$etime'";
		$condition = join(" AND ", $conditions);
		$sql = "SELECT remark AS pause_reason,cause_type,node_id,duty_department, SUM(TIMESTAMPDIFF(second,pause_time,recover_time)) AS howlong 
				FROM pause 
				WHERE $condition 
				GROUP BY pause_reason,cause_type,duty_department
				ORDER BY howlong DESC 
				LIMIT 0,5";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($datas as &$data){
			$detailSql = "SELECT id, node_id,TIMESTAMPDIFF(second,pause_time,recover_time) AS howlong, pause_time, recover_time
						FROM pause
						WHERE $condition AND remark = '{$data['pause_reason']}' AND cause_type='{$data['cause_type']}' AND duty_department='{$data['duty_department']}';
						ORDER BY pause_time ASC";
			$details = Yii::app()->db->createCommand($detailSql)->queryAll();
			foreach($details as &$detail){
				$howlong = is_null($detail['howlong']) ? (gettimeofday("YmdHis") - strtotime($detail['pause_time'])) : $detail['howlong'];
				$detail['howlong'] = $this->secondConvertToMMss($howlong);

				$node = NodeAR::model()->findByPk($detail['node_id']);
				$detail['node_name'] = empty($node)? '-' : $node->display_name;
			}
			$data['details'] = $details;
			$data['howlong'] = $this->secondConvertToMMss($data['howlong']);
		}

		return $datas;
	}

	public function queryRecycleChart($date, $timespan) {
		list($stime, $etime) = $this->reviseTime($date, $timespan);
		$timeArray = $this->parseQueryTime($stime, $etime, $timespan);
		$stateArray= self::$RECYCLE_BALANCE_STATE;
		$columnSeriesX = array();
		$columnSeriesY = array();
		$lineSeriesY = array();
		foreach(self::$RECYCLE_BALANCE_STATE as $state => $stateName){
			$columnSeriesY[$stateName] = array();
		}

		foreach($timeArray as $queryTime) {
			$curDate = DateUtil::getCurDate() . " 08:00:00";
			if($queryTime['stime']<$curDate){
				$sDate = substr($queryTime['stime'], 0, 10);
				$eDate = substr($queryTime['etime'], 0, 10);
				$balanceArray = $this->queryRecycleBalance($sDate, $eDate);
				foreach($balanceArray as $state => $count){
					$columnSeriesY[$stateArray[$state]][] = $count;
				}
				$period = $this->queryAssemblyPeriod($queryTime['stime'], $queryTime['etime']);
				$lineSeriesY[] = $period;
			} else {
				foreach($balanceArray as $state => $count){
					$columnSeriesY[$stateArray[$state]][] = null;
				}
				$lineSeriesY[] = null;
			}
			$columnSeriesX[] = $queryTime['point'];
		}

		$ret = array(
			"stateArray" => array_values(self::$RECYCLE_BALANCE_STATE),
			"series" => array(
				"x" => $columnSeriesX,
				"column" => $columnSeriesY,
				"line" => $lineSeriesY,
			),
		);

		return $ret;
	}

	public function queryRecycleBalance($sDate, $eDate) {
		$sql = "SELECT state, (sum(count)/count(DISTINCT work_date)) as count FROM balance_daily WHERE work_date>='$sDate' AND work_date<'$eDate' AND state IN ('onLine','onLine-2', 'VQ1','VQ2','VQ3') GROUP BY series,state";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		$count = array();
		foreach(self::$RECYCLE_BALANCE_STATE as $state => $stateName){
			$count[$state] = null;
		}

		foreach($datas as $data){
			$count[$data['state']] += ceil($data['count']);
		}

		return $count;
	}

	public function queryOnlineBalanceGroupBySeries($sDate, $eDate) {
		$sql = "SELECT series, (sum(count)/count(DISTINCT work_date)) as count FROM balance_daily WHERE work_date>='$sDate' AND work_date<'$eDate' AND state IN ('onLine','onLine-2')  GROUP BY series";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		$count = array();
		foreach(self::$SERIES_NAME as $series => $seriesName){
			$count[$series] = null;
		}

		foreach($datas as $data){
			$count[$data['series']] += ceil($data['count']);
		}

		return $count;
	}

	public function queryRecycleBalanceGroupBySeries($sDate, $eDate) {
		$sql = "SELECT series, (sum(count)/count(DISTINCT work_date)) as count FROM balance_daily WHERE work_date>='$sDate' AND work_date<'$eDate' AND state IN ('VQ1','VQ2','VQ3')  GROUP BY series";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		$count = array();
		foreach(self::$SERIES_NAME as $series => $seriesName){
			$count[$series] = null;
		}

		foreach($datas as $data){
			$count[$data['series']] += ceil($data['count']);
		}

		return $count;
	}

	public function queryWarehouseBalanceGroupBySeries($sDate, $eDate) {
		$sql = "SELECT series, (sum(count)/count(DISTINCT work_date)) as count FROM balance_daily WHERE work_date>='$sDate' AND work_date<'$eDate' AND state ='WH'  GROUP BY series";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		$count = array();
		foreach(self::$SERIES_NAME as $series => $seriesName){
			$count[$series] = null;
		}

		foreach($datas as $data){
			$count[$data['series']] += ceil($data['count']);
		}

		return $count;
	}

	public function queryAssemblyPeriod($stime, $etime){
		$condition = "assembly_time>='$stime' AND assembly_time<'$etime'";
		$sql = "SELECT id as car_id, vin, assembly_time,finish_time,warehouse_time,TIMESTAMPDIFF(second,assembly_time,warehouse_time) AS howlong FROM car WHERE $condition";
		$cars = Yii::app()->db->createCommand($sql)->queryAll();
		$countSql = "SELECT COUNT(DISTINCT id) FROM car WHERE $condition";
		$count = intval(Yii::app()->db->createCommand($countSql)->queryScalar());

		$howlong = null;
		foreach($cars as &$car){
			if(is_null($car['howlong'])) $car['howlong'] = (strtotime(date("Y-m-d H:i:s")) - strtotime($car['assembly_time']));
			$howlong += $car['howlong'];
		}
		$period = empty($count) ? null : $howlong / $count;
		$period = empty($period) ? null : round(($period / 3600), 1);

		return $period;
	}

	public function countOnline($stime, $etime, $line='I'){
		$sql = "SELECT COUNT(id) FROM car WHERE assembly_time>='$stime' AND assembly_time<'$etime' AND assembly_line='$line'";
		$data = Yii::app()->db->createCommand($sql)->queryScalar();
		return $data;
	}

	public function queryOvertimeCars(){
		$sql = "SELECT id as car_id, serial_number, vin, type, series,config_id,color, assembly_line, finish_time, warehouse_time, TIMESTAMPDIFF(hour,assembly_time,CURRENT_TIMESTAMP) AS recycle_period, `status`
				FROM car
				WHERE finish_time>'0000-00-00 00:00:00' AND warehouse_time='0000-00-00 00:00:00' AND `status`>'' AND TIMESTAMPDIFF(hour,assembly_time,CURRENT_TIMESTAMP)>=96
				ORDER BY recycle_period DESC LIMIT 0,10";
		$cars = Yii::app()->db->createCommand($sql)->queryAll();

		$tablePrefixMap=array(
			"VQ1异常" => "VQ1_STATIC_TEST",
			"VQ2异常.路试" => "VQ2_ROAD_TEST",
			"VQ2异常.漏雨" => "VQ2_LEAK_TEST",
			"VQ3异常" => "VQ3_FACADE_TEST",
		);

		$lineSuffix = array(
			"I" => "",
			"II" => "_2",
		);

		$configName = $this->configNameList();

		foreach($cars as &$car) {
			$car['config_name'] = empty($car['config_id']) ? $car['type'] : $configName[$car['config_id']];
			$car['faults'] = "";
			$car['node_remark'] = "";
			if(!array_key_exists($car['status'], $tablePrefixMap)) continue;
			if($car['status'] == "VQ1异常") {
				$table = "VQ1_STATIC_TEST" . $lineSuffix[$car['assembly_line']] . "_" . $car['series'];
			} else {
				$table = $tablePrefixMap[$car['status']] . "_" . $car['series'];
			}
			$faults = $this->queryUnsolvedFaults($car['car_id'],$table);
			$remarks = $this->queryNodeRemark($car['car_id'],97);
			$car['faults'] = join("、", $faults);
			$car['node_remark'] = join("。" ,$remarks);
		}

		return $cars;
	}

	public function queryUnsolvedFaults($carId, $table) {
		$sql = "SELECT CONCAT(component_name,fault_mode) AS fault
				FROM $table
				WHERE car_id=$carId AND status='未修复'";
		$faults = Yii::app()->db->createCommand($sql)->queryColumn();
		$faults = array_unique($faults);
		return $faults;
	}

	public function queryNodeRemark($carId, $nodeId) {
		$sql = "SELECT remark FROM node_trace WHERE node_id=$nodeId AND car_id=$carId";
		$remarks = Yii::app()->db->createCommand($sql)->queryColumn();
		$remarks = array_unique($remarks);
		
		return $remarks;
	}

	public function queryWarehouseChart($date, $timespan) {
		list($stime, $etime) = $this->reviseTime($date, $timespan);
		$timeArray = $this->parseQueryTime($stime, $etime, $timespan);
		$seriesArray = self::$SERIES_NAME;
		$columnSeriesX = array();
		$columnSeriesY = array();
		$lineSeriesY = array();
		foreach($seriesArray as $series => $seriesName){
			$columnSeriesY[$seriesName] = array();
		}

		foreach($timeArray as $queryTime){
			$curDate = DateUtil::getCurDate() . " 08:00:00";
			if($queryTime['stime']<$curDate){
				$count = $this->countCarByPoint($queryTime['stime'], $queryTime['etime'], 'distribute');
				foreach($seriesArray as $series => $seriesName){
					$columnSeriesY[$seriesName][] = $count[$series];
				}
				$lineSeriesY[] = $this->queryWarehousePeriod($queryTime['stime'], $queryTime['etime']);
			} else {
				foreach($seriesArray as $series => $seriesName){
					$columnSeriesY[$seriesName][] = null;
				}
				$lineSeriesY[] = null;
			}

			$columnSeriesX[] = $queryTime['point'];
		}

		$ret = array(
			"carSeries" => array_values(self::$SERIES_NAME),
			"series" => array(
				'x' => $columnSeriesX,
				'column' => $columnSeriesY,
				'line' => $lineSeriesY,
			),
		);

		return $ret;
	}

	public function queryWarehousePeriod($stime, $etime){
		$condition = " `status`>0 AND activate_time>='$stime' AND activate_time<'$etime'";
		$countSql = "SELECT COUNT(DISTINCT board_number) FROM `order` WHERE $condition";
		$boardCount = Yii::app()->db->createCommand($countSql)->queryScalar();
		if(empty($boardCount)) return null;

		$sql = "SELECT 	board_number, 
						MIN(activate_time) AS min_activate, 
						MAX(activate_time) AS max_activate, 
						MIN(out_finish_time) AS min_out, 
						MAX(out_finish_time) AS max_out
				FROM 	`order`
				WHERE $condition
				GROUP BY board_number";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		
		$warehousePeriod = null;
		foreach($datas as &$data){
			//获得每板的激活、完成、释放这三个周期时间点
			$boardActivate = $data['min_activate'];
			if($data['min_out'] === '0000-00-00 00:00:00'){
				$boardOutFinish = date('Y-m-d H:i:s');
			} else {
				$boardOutFinish = $data['max_out'];
			}

			//计算成品库周期，出库完成时间-激活时间
			$data['warehousePeriod'] = strtotime($boardOutFinish) - strtotime($boardActivate);
			$warehousePeriod += $data['warehousePeriod'] ;
		}
		$warehousePeriodAvg = empty($warehousePeriod) ? 0 : round(($warehousePeriod / $boardCount / 3600), 1);

		return $warehousePeriodAvg;
	}

	public function queryOvertimeOrders() {
		$sql = "SELECT id, order_number, board_number, TIMESTAMPDIFF(second,activate_time,CURRENT_TIMESTAMP) AS warehouse_period, standby_date, amount, hold, count, series, car_type, car_model, color, cold_resistant, order_config_id, config_name, distributor_name, lane_id,lane_name, status, activate_time, out_finish_time 
				FROM view_order 
				WHERE `status` = 1 AND amount>count AND activate_time>'0000-00-00 00:00:00' AND out_finish_time='0000-00-00 00:00:00'
				ORDER BY warehouse_period DESC";
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$boards = array();
		foreach($orders as &$order){
			$order['series_name'] = self::$SERIES_NAME[$order['series']];
			$order['cold'] = self::$COLD_RESISTANT[$order['cold_resistant']];
			$order['car_type_config'] = $order['car_model'] . "/" . $order['config_name'] . "/" . $order['cold'];
			$order['warehouse_period'] = round($order['warehouse_period']/3600, 1);

			if(empty($boards[$order['board_number']])){
				$boards[$order['board_number']] = array(
					'boardNumber' => $order['board_number'],
					'boardWarehousePeriod' => $order['warehouse_period'],
					'orders' => array(),
				);
			}
			$boards[$order['board_number']]['orders'][]=$order;
		}

		array_splice($boards, 5);
		return $boards;
	}

	public function queryCarDetail($date, $point, $timespan="daily"){
		list($stime, $etime) = $this->reviseTime($date, $timespan);

		$data = $this->queryDetailByPoint($stime, $etime, $point);

		return $data;
	}

	public function countCarByPoint($stime,$etime,$point="assembly",$line=""){
		$point .= "_time";
		$sql = "SELECT series, COUNT(id) as `count` FROM car WHERE $point>='$stime' AND $point<'$etime'";
		if(!empty($line)){
			$sql .= " AND assembly_line='$line'";
		}
		$sql .= " GROUP BY series";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		$count = array(
			"F0"=>0,
			"M6"=>0,
			"6B"=>0,
		);
		foreach($datas as $data){
			$count[$data['series']] = intval($data['count']);
		}

		return $count;
	}

	public function countCarByState($state){
		if(!is_array($state)) {
			if(!empty(self::$NODE_BALANCE_STATE[$state])) {
				$states = self::$NODE_BALANCE_STATE[$state];
			} else {
				$states = array($state);
			}
		} else {
			$states = $state;
		}

		$str = "'" . join("','", $states) . "'";
		$condition = " WHERE status IN ($str)";

		$sql = "SELECT series, COUNT(id) as `count` FROM car $condition GROUP BY  series";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		$count = array(
			"F0"=>0,
			"M6"=>0,
			"6B"=>0,
		);
		foreach($datas as $data){
			$count[$data['series']] = intval($data['count']);
		}

		return $count;
	}

	public function queryDetailByPoint($stime, $etime, $point="assembly"){
		$point .= "_time";
		$sql = "SELECT id as car_id, vin, assembly_line, serial_number, series, type, config_id, cold_resistant, color,status, engine_code, assembly_time, finish_time, warehouse_time, distribute_time, warehouse_id, order_id, lane_id, distributor_name, remark, special_order
				FROM car
				WHERE $point>='$stime' AND $point<'$etime' ORDER BY assembly_time";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		$configName = $this->configNameList();
		$configName[0] = "";
		foreach($datas as &$data){
			if($data['series'] == '6B') $data['series'] = '思锐';
			$data['config_name'] = $configName[$data['config_id']];
			$data['cold'] = self::$COLD_RESISTANT[$data['cold_resistant']];

			$data['row'] = '-';
			if(!empty($data['warehouse_id'])){
				$row = WarehouseAR::model()->findByPk($data['warehouse_id']);
				if(!empty($row)) $data['row'] = $row->row;
			}

			$data['order_number'] = '-';
			if(!empty($data['order_id'])){
				$order = OrderAR::model()->findByPk($data['order_id']);
				if(!empty($order)) $data['order_number'] = $order->order_number;
			}

			$data['lane'] = '-';
			if(!empty($data['lane_id'])){
				$lane = LaneAR::model()->findByPk($data['lane_id']);
				if(!empty($lane)) $data['lane'] = $lane->name;
			}
		}

		return $datas;
	}

	public function queryQualification($point, $date, $timespan, $seriesText="all") {
		list($stime, $etime) = $this->reviseTime($date, $timespan);
		$timeArray = $this->parseQueryTime($stime, $etime, $timespan);
		$seriesArray = $this->parseSeries($seriesText);
		$columnSeriesX = array();
		$columnSeriesY = array();
		$lineSeriesY = array();
		foreach($seriesArray as $series => $seriesName){
			$countTotal[$seriesName] = 0;
			$columnSeriesY[$seriesName] = array();
		}

		foreach($timeArray as $queryTime){
			$carCountAll = $this->countCarTrace($point, $queryTime['stime'], $queryTime['etime'], $seriesText);
			$countNG = $this->countNG($point, $seriesText, $queryTime['stime'], $queryTime['etime']);
			foreach($seriesArray as $series => $seriesName){
				$faultCount = $this->countFault($point, $series, $queryTime['stime'], $queryTime['etime']);
				$columnSeriesY[$seriesName][] = empty($carCountAll) ? null : round($faultCount / $carCountAll, 2);
			}
			$lineSeriesY[] = empty($carCountAll) ? null : round(($carCountAll - $countNG) / $carCountAll, 2);
			$columnSeriesX[] = $queryTime['point'];
		}

		$ret = array(
			"carSeries" => array_values($seriesArray),
			"series" => array(
				'x' => $columnSeriesX,
				'column' => $columnSeriesY,
				'line' => $lineSeriesY,
			),
		);

		return $ret;
	}

	public function queryFaultDaily($point, $date, $seriesText="all"){
		list($stime, $etime) = $this->reviseTime($date, "daily");
		$seriesArray = $this->parseSeries($seriesText);
		$column = array(
			"columnSeriesX" => array(),
			"columnSeriesY" => array(),
		);
		$donut = array();

		foreach($seriesArray as $series => $seriesName){
			$columnSeriesY[$seriesName] = array();
		}
		$topFaults = $this->queryTopFault($point, $seriesText, $stime, $etime);
		foreach($topFaults as $one){
			// $column['columnSeriesX'][] = $seriesArray[$one['series']] . "-" . $one['fault'];
			$column['columnSeriesX'][] = $one['fault'];
			foreach($seriesArray as $series=>$seriesName){
				$column['columnSeriesY'][$seriesName][] = $series == $one['series'] ? intval($one['count']) : 0;
			}
		}

		$dutys = $this->queryFaultDutyDistribute($point, "all", $stime, $etime);
		$iColor = 0;
		foreach($dutys as $one){
			$tmp = explode("/", $one['duty']);
			$department = $tmp[0];
			$subDepartment = empty($tmp[1]) ? "-" : $tmp[1];
			if(empty($donut[$department])){
				$donut[$department] = array(
					"y"=>0,
					"colorIndex"=>$iColor++,
					"drilldown"=>array(
						'name'=>$department,
						'categories'=>array(),
						'data'=>array(),
					),
				);
			}
			$donut[$department]['y'] += $one['percentage'];
			$donut[$department]['drilldown']['categories'][] = $subDepartment;
			$donut[$department]['drilldown']['data'][] = $one['percentage'];
		}

		$ret = array(
			"carSeries" => array_values($seriesArray),
			"columnData" => $column,
			"donutData" => $donut,
		);
		return $ret;
	}

	public function countCarTrace($point, $stime, $etime, $series="") {
		$nodeIdStr = $this->parseNodeId($point);
		$sql = "SELECT count(DISTINCT car_id) FROM node_trace WHERE pass_time>='$stime' AND pass_time<'$etime' AND node_id IN ($nodeIdStr)";
		if(!empty($series) && $series != "all") $sql .= " AND car_series = '$series'";
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		return $count;
	}

	public function countFault($point, $series, $stime, $etime) {
		$tables = $this->parseTables($point, $series);
		$count = 0;
		foreach($tables as $table=>$nodeName) {
			$countSql = "SELECT count(*) FROM $table WHERE create_time>='$stime' AND create_time<'$etime'";
			$count += Yii::app()->db->createCommand($countSql)->queryScalar();
		}
		return $count;
	}

	public function countNG($point, $series, $stime, $etime) {
		$tables = $this->parseTables($point, $series);
		foreach($tables as $table=>$nodeName) {
			$dataSql[] = "(SELECT car_id FROM $table WHERE create_time>='$stime' AND create_time<'$etime' AND status <>'在线修复')";
		}
		$sql = join(" UNION ALL ", $dataSql);
		$datas = Yii::app()->db->createCommand($sql)->queryColumn();
		$datas = array_unique($datas);
		$count = count($datas);
		return $count;
	}

	public function queryFaults($point, $series, $stime, $etime) {
		$tables = $this->parseTables($point, $series);
		$dutyList = $this->dutyList();
		foreach($tables as $table=>$nodeName){
			$tmp = explode("_", $table);
			$series = end($tmp); 
			$dataSql[] = "(SELECT '$series' AS series, car_id, fault_id, fault_mode, CONCAT(component_name,fault_mode) AS fault, duty_department, FROM $table WHERE create_time>='$stime' AND create_time<'$etime')";
		}
		$sql = join(" UNION ALL ", $datasql);
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($datas as &$data) {
			$data['duty'] = $dutyList[$data['duty_department']];
		}
		return $datas;
	}

	public function queryTopFault($point, $series, $stime, $etime, $top=10) {
		$tables = $this->parseTables($point, $series);
		foreach($tables as $table=>$nodeName){
			$tmp = explode("_", $table);
			$series = end($tmp); 
			$dataSqls[] = "(SELECT '$series' AS series, car_id, fault_id, fault_mode, CONCAT(component_name,fault_mode) AS fault, duty_department FROM $table WHERE create_time>='$stime' AND create_time<'$etime')";
		}
		$datasql = join(" UNION ALL ", $dataSqls);
		$sql = "SELECT *,COUNT(fault_id) AS count FROM (" . $datasql .") t GROUP BY fault_id ORDER BY count DESC LIMIT 0,$top";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		return $datas;
	}

	public function queryFaultDutyDistribute($point, $series, $stime, $etime) {
		$tables = $this->parseTables($point, $series);
		foreach($tables as $table=>$nodeName){
			$dataSqls[] = "(SELECT duty_department FROM $table WHERE create_time>='$stime' AND create_time<'$etime')";
			$countSqls[] = "(SELECT count(*) FROM $table WHERE create_time>='$stime' AND create_time<'$etime')";
		}
		$datasql = join(" UNION ALL ", $dataSqls);
		$sql = "SELECT *,COUNT(duty_department) AS count FROM (" . $datasql .") t GROUP BY duty_department ORDER BY count DESC";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		$total = 0;
		foreach($countSqls as $countSql) {
			$total += Yii::app()->db->createCommand($countSql)->queryScalar();
		}

		$dutyList = $this->dutyList();
		foreach($datas as &$data) {
			$data['duty'] = $dutyList[$data['duty_department']];
			$data['percentage'] = $data['count'] / $total;
		}

		return $datas;
	}

	private function parseTables($point, $series="all") {
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
			'VQ2_ROAD_TEST' => 'VQ2_ROAD_TEST',
			'VQ2_LEAK_TEST' => 'VQ2_LEAK_TEST',
			'VQ3' => 'VQ3_FACADE_TEST',
			'WDI' => 'WDI_TEST',
		);

		$temps = array();
		if(empty($point) || $point === 'all') {
			$temps = $tablePrefixs;
		} else if($point === 'VQ2_ALL') {
			$temps = array(
				'VQ2_ROAD_TEST' => 15,
            	'VQ2_LEAK_TEST' => 16,
			);
		} else if($point === 'VQ1_ALL') {
			$temps = array(
				'VQ1_STATIC_TEST' => 10,
            	'VQ1_STATIC_TEST_2' => 209,
			);
		} else if(!empty($nodeTables[$point])) {
			$temps = array($nodeTables[$point]=>$tablePrefixs[$nodeTables[$point]]);
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

	private function parseNodeId($point) {
		$nodeIds = array(
            'VQ1' => 10,
            'VQ1_2' => 209,
			// 'VQ2_CHECK_LINE' => 13,
            'VQ2_ROAD_TEST' => 15,
            'VQ2_LEAK_TEST' => 16,
			'VQ2_ALL' => '15,16',
			// 'VQ2_ALL' => '13,15,16',
            'VQ3' => 17,
			// 'WDI'  => 95,
        );

		if(empty($point) || $point === 'all') {
			return join(',', array_values($nodeIds));
		} else {
			return $nodeIds[$point];
		}

	}

	public function reviseTime($date, $timespan){
		switch($timespan) {
			case "daily":
				list($stime, $etime) = $this->reviseDailyTime($date);
				break;
			case "monthly":
				list($stime, $etime) = $this->reviseMonthlyTime($date);
				break;
			case "yearly":
				list($stime, $etime) = $this->reviseYearlyTime($date);
				break;
			default:
				list($stime, $etime) = $this->reviseDailyTime($date);
		}
		return array($stime, $etime);
	}

	public function reviseDailyTime($date) {
		$d = strtotime($date);
		$nextDay = strtotime('+1 day', $d);
		$stime = date("Y-m-d 08:00:00", $d);
		$etime = date("Y-m-d 08:00:00", $nextDay);

		return array($stime, $etime);
	}

	public function reviseDailyMonth($date) {
		$d = strtotime($date);
		$sMonth = date("Y-m-01 08:00:00", $d);
		$nextDay = strtotime('+1 day', $d);
		$eDate = date("Y-m-d 08:00:00", $nextDay);

		return array($sMonth, $eDate);
	}

	public function reviseMonthlyTime($date) {
		$d = strtotime($date);
		$nextM = strtotime('first day of next month', $d);
		$stime = date("Y-m-01 08:00:00", $d);
		$etime = date("Y-m-01 08:00:00", $nextM);

		return array($stime, $etime);
	}

	private function reviseYearlyTime($date) {
		$d = strtotime($date);
		$nextY = strtotime('+1 year', $d);
		$stime = date("Y-01-01 08:00:00", $d);
		$etime = date("Y-01-01 08:00:00", $nextY);

		return array($stime, $etime);
	}

	private function stateArray($state){
		$stateMap=array(
			'PBS' => array('PBS'),
			'onLine' => array('onLine'),
			'onLine-2' => array('onLine-2'),
			'VQ1' => array('VQ1-NORMAL', 'VQ1-RETURN'),
			'VQ2' => array('VQ2-NORMAL', 'VQ2-RETURN'),
			'VQ3' => array('VQ3-NORMAL','VQ3-RETURN'),
			'VQ3-OK' => array('VQ3-OK'),
			'recycle' => array('VQ1', 'VQ2', 'VQ3'),
			// 'WH' => array('WH'),
			'WH' => array('WH-0','WH-27-export','WH-27-normal','WH-35','WH-X','WH-WDI'),
			'WHin' => array('WHin'),
			'assembly' => array('PBS', 'onLine','VQ1', 'VQ2', 'VQ3', 'WH'),
			'mergeRecyle' => array('PBS','onLine','recycle', 'WH'),
		);
		return $stateMap[$state];
	}

	private function configNameList(){
		$configName = array();
		$sql = "SELECT car_config_id, order_config_id , name , car_model FROM view_config_name";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($datas as $data){
			$configName[$data['car_config_id']] = $data['car_model'] . '/' . $data['name'];
		}
		return $configName;
	}

	public function parseQueryTime($stime, $etime, $timespan){
		$s = strtotime($stime);
		$e = strtotime($etime);

		$ret = array();

		switch($timespan) {
			case "monthly":
				$pointFormat = 'd';
				$format = 'Y-m-d H:i:s';
				$slice = 86400;
				break;
			case "yearly":
				$pointFormat = 'm';
				$format = 'Y-m-d H:i:s';
				break;
			default:
				$pointFormat = 'd';
				$format = 'Y-m-d H:i:s';
				$slice = 86400;
		}

		$t = $s;
		while($t<$e) {
			$point = date($pointFormat, $t);
			if($pointFormat === 'm') {
				$eNextM = strtotime('first day of next month', $t);			//next month			//added by wujun
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

	public function dutyList(){
		$list = array();
		$sql = "SELECT id,name,display_name FROM duty_department";
		$dutyDepartments = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($dutyDepartments as $department){
			$list[$department['id']] = $department['display_name'];
		}
		$list['assembly'] = '总装工厂';
		$list['paint'] = '涂装工厂';
		$list['welding'] = '焊装工厂';
		$list[''] = '-';

		return $list;
	}

	public function secondConvertToMMss($howlong) {
		$howlongMM = intval($howlong / 60);
		$howlongSS = intval($howlong % 60);
		$ret = $howlongMM . "'" . sprintf("%02d", $howlongSS) . "\"";
		return $ret;
	}

	private function parseSeries($series) {
		if(empty($series) || $series === 'all') {
            $seriesArray = array('F0'=>'F0', 'M6'=>'M6', '6B'=>'思锐');
        } else {
            $series = explode(',', $series);
            foreach($series as $one){
            	$seriesArray[$one] = self::$SERIES_NAME[$one];
            }
        }
		return $seriesArray;
	}
}
