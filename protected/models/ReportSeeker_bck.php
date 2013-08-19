<?php
Yii::import('application.models.AR.NodeAR');
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.LaneAR');
Yii::import('application.models.AR.WarehouseAR');
Yii::import('application.models.AR.monitor.LinePauseAR');
Yii::import('application.models.CarSeeker');

class ReportSeeker
{
	public function __construct(){
	}

	private static $NODE_BALANCE_STATE = array(
		'PBS' => array('彩车身库'),
		'onLine' => array('T1工段' ,'T2工段', 'T3工段', 'C1工段', 'C2工段', 'F1工段', 'F2工段', 'VQ1检验'),
		'onLine-2' => array('II_T1工段' ,'II_T2工段', 'II_T3工段', 'II_C1工段', 'II_C2工段', 'II_F1工段', 'II_F2工段', 'II_VQ1检验'),
		'VQ1' => array('VQ1异常','VQ1退库'),
		'VQ1-NORMAL' => array('VQ1异常'),
		'VQ1-RETURN'=> array('VQ1退库'),
		'VQ2' => array('VQ1合格', '出生产车间', '检测线缓冲','VQ2检测线', 'VQ2路试', 'VQ2淋雨', 'VQ2异常.路试', 'VQ2异常.漏雨', 'VQ2退库'),
		'VQ2-NORMAL' => array('VQ1合格', '出生产车间', '检测线缓冲','VQ2检测线', 'VQ2路试', 'VQ2淋雨', 'VQ2异常.路试', 'VQ2异常.漏雨'),
		'VQ2-RETURN'=> array('VQ2退库'),
		'VQ3' => array('VQ3检验' ,'VQ3合格', 'VQ3异常','VQ3退库'),
		'VQ3-OK' => array('VQ3合格'),
		'VQ3-NORMAL' => array('VQ3检验' ,'VQ3合格', 'VQ3异常'),
		'recycle' => array('VQ1异常','VQ1合格', 'VQ1退库', '出生产车间', '检测线缓冲','VQ2检测线','VQ2路试', 'VQ2淋雨', 'VQ2异常.路试', 'VQ2异常.漏雨' , 'VQ2退库', 'VQ3检验' ,'VQ3合格', 'VQ3异常', 'VQ3退库'),
		'VQ3-RETURN'=> array('VQ3退库'),
		'WH' => array('成品库','WDI'),
		'WHin' => array('成品库'),
		'WH-0' =>array('成品库'),
		'WH-27-export' =>array('成品库'),
		'WH-27-normal' =>array('成品库'),
		'WH-35' =>array('成品库'),
		'WH-X' =>array('成品库'),
		'WH-14' =>array('成品库'),
		'WH-13' =>array('成品库'),
		'WH-15' =>array('成品库'),
		'WH-T' =>array('成品库'),
		'WH-WDI' =>array('WDI'),
		'assembly' => array('T1工段' ,'T2工段', 'T3工段', 'C1工段', 'C2工段', 'F1工段', 'F2工段', 'VQ1检验','VQ1异常','VQ1合格', '出生产车间' , 'VQ1退库', '检测线缓冲','VQ2检测线','VQ2路试', 'VQ2淋雨', 'VQ2异常.路试', 'VQ2异常.漏雨' , 'VQ2退库', 'VQ3检验' ,'VQ3合格', 'VQ3异常' , 'VQ3退库','成品库'),
	);

	private static $COUNT_POINT_DAILY = array(
		"assemblyCount" => "上线",
		// "finishCount" => "下线",
		"warehouseCount" => "入库",
		"distributeCount" => "出库",
		"assemblyMonth" => "月上线",
		// "finishMonth" => "月下线",
		"warehouseMonth" => "月入库",
		"distributeMonth" => "月出库",
		"recycleBalance" => "周转车",
		"warehouseBalance" => "库存",
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
		"VQ1" => "VQ1",
		"VQ2" => "VQ2",
		"VQ3" => "VQ3",
	);


	public function queryManufactureDaily($date) {
		list($stime, $etime) = $this->reviseDailyTime($date);
		list($sMonth, $eDate) = $this->reviseDailyMonth($date);
		$countArray = array();
		$countArray["assemblyCount"] = $this->countCarByPoint($stime, $etime, "assembly");
		// $countArray["finishCount"] = $this->countCarByPoint($stime, $etime, "finish");
		$countArray["warehouseCount"] = $this->countCarByPoint($stime, $etime, "warehouse");
		$countArray["distributeCount"] = $this->countCarByPoint($stime, $etime, "distribute");

		$dataSeriesX = array();
		$dataSeriesY = array();
		foreach($countArray as $point => $count){
			$dataSeriesX[] = self::$COUNT_POINT_DAILY[$point];
			foreach(self::$SERIES_NAME as $series => $seriesName){
				$dataSeriesY[$seriesName][] = $count[$series];
			}
		}
		$columnSeries = array("x"=>$dataSeriesX,"y"=>$dataSeriesY);

		$countArray["assemblyMonth"] = $this->countCarByPoint($sMonth, $eDate, "assembly");
		// $countArray["finishMonth"] = $this->countCarByPoint($sMonth, $eDate, "finish");
		$countArray["warehouseMonth"] = $this->countCarByPoint($sMonth, $eDate, "warehouse");
		$countArray["distributeMonth"] = $this->countCarByPoint($sMonth, $eDate, "distribute");

		$countArray["recycleBalance"] = array("F0"=>"","M6"=>"","6B"=>"");
		$countArray["warehouseBalance"] = array("F0"=>"","M6"=>"","6B"=>"");
		$curDate = DateUtil::getCurDate();
		if(strtotime($date) < strtotime($curDate)){
			$nextDay = date("Y-m-d", strtotime('+1 day', strtotime($date)));
			$countArray["recycleBalance"] = $this->queryRecycleBalanceGroupBySeries($date, $nextDay);
			$countArray["warehouseBalance"] = $this->queryWarehouseBalanceGroupBySeries($date, $nextDay);
		} else {
			$countArray["recycleBalance"] = $this->countCarByState("recycle");
			$countArray["warehouseBalance"] = $this->countCarByState("WH");
		}

		foreach($countArray as &$count){
			$sum = "";
			foreach($count as &$seriesCount){
				$sum += $seriesCount;
				if($seriesCount === "") $seriesCount = "-";
			}
			$count['sum'] = $sum === "" ? "-" : $sum;
		}

		$carSeeker = new CarSeeker();
		$recyclePeriod = $carSeeker->queryRecycleBalancePeriod("recycle", "all");

		$countSeries = $seriesName = self::$SERIES_NAME;

		$countSeries['sum'] = "总计";

		$ret = array(
			"countPoint" => self::$COUNT_POINT_DAILY,
			"countSeries" => $countSeries,
			"count" => $countArray,
			"carSeries" => array_values($seriesName),
			"columnSeries" => $columnSeries,
			"dataDonut" => $recyclePeriod["dataDonut"],
		);

		return $ret;
	}

	public function queryCompletion($date, $timespan){
		switch($timespan) {
			case "monthly":
				list($stime, $etime) = $this->reviseMonthlyTime($date);
				break;
			case "yearly":
				list($stime, $etime) = $this->reviseYearlyTime($date);
				break;
			default:
				list($stime, $etime) = $this->reviseDailyTime($date);
		}
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
			//count assembly cars
			$countTmp = array();
			$completionTmp = array();

			$sDate = substr($queryTime['stime'], 0, 10);
			$eDate = substr($queryTime['etime'], 0, 10);
			$completionArray = $this->queryPlanCompletion($sDate,$eDate);
			$readySum = 0;
			$totalSum = 0;
			foreach($completionArray as $series => $count){
				$columnSeriesY[$seriesArray[$series]][] = $count['ready'];
				$countTmp[$seriesArray[$series]] =  $count['ready'];
				$countTotal[$seriesArray[$series]] += $count['ready'];

				$readySum += $count['ready'];
				$totalSum += $count['total'];
			}

			$rate = empty($totalSum) ? null : round(($readySum/$totalSum) , 2);
			$lineSeriesY[] = $rate;
			$completionTmp['completion'] = $rate;
			$completionTmp['totalSum'] = $totalSum;
			$completionTmp['readySum'] = $readySum;

			$completionTotal['totalSum'] += $totalSum;
			$completionTotal['readySum'] += $readySum;
			$columnSeriesX[] = $queryTime['point'];
			$countDetail[] = array_merge(array('time' => $queryTime['point']), $countTmp);
			$completionDetail[] = array_merge(array('time' => $queryTime['point']), $completionTmp);
		}
		$completionTotal['completion'] = empty($completionTotal['totalSum']) ? null : round(($completionTotal['readySum']/$completionTotal['totalSum']) , 2);

		$ret = array(
			"carSeries" => array_values(self::$SERIES_NAME),
			"countDetail" => $countDetail,
			"completionDetail" => $completionDetail,
			"countTotal" => $countTotal,
			"completionTotal" => $completionTotal,
			"series" => array(
				'x' => $columnSeriesX,
				'column' => $columnSeriesY,
				'line' => $lineSeriesY,
			),
		);

		return $ret;
	}

	public function queryPlanCompletion($sDate, $eDate){
		$sql = "SELECT car_series as series, SUM(total) as total, SUM(ready) as ready FROM plan_assembly WHERE plan_date>='$sDate' AND plan_date<'$eDate' GROUP BY series";
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
		switch($timespan) {
			case "monthly":
				list($stime, $etime) = $this->reviseMonthlyTime($date);
				break;
			case "yearly":
				list($stime, $etime) = $this->reviseYearlyTime($date);
				break;
			default:
				list($stime, $etime) = $this->reviseDailyTime($date);
		}
		$timeArray = $this->parseQueryTime($stime, $etime, $timespan);
		$causeArray = self::$PAUSE_CAUSE_TYPE;
		// $pauseDetail = array();
		// $pauseTotal = array();
		// $useDetail = array();
		// $useTotal = array(
		// 	"runTime" => null,
		// 	"useRate" => null,
		// 	"capacity" => null,
		// 	"prodution" => null,
		// );
		$columnSeriesX = array();
		$columnSeriesY = array();
		$lineSeriesY = array();
		foreach($causeArray as $cause){
			$pauseTotal[$cause] = 0;
			$columnSeriesY[$cause] = array();
		}
		$pauseTotal['总计'] = 0;

		foreach($timeArray as $queryTime){
			$pauseTmp = array();
			$useTmp = array();
			$howlongSum = 0;

			$causeDistribute = $this->queryPauseCauseDistribute($queryTime['stime'], $queryTime['etime']);
			$useTmp = $this->queryUseRate($queryTime['stime'], $queryTime['etime']);
			foreach($causeDistribute as $causeType => $howlong){
				$columnSeriesY[$causeType][] = $howlong;
				// $pauseTmp[$causeType] = round($howlong / 60);
				// $pauseTotal[$causeType] += $howlong;
				// $howlongSum += $howlong;
				// $pauseTotal['总计'] += $howlong;
			}
			// $pauseTmp['总计'] = round($howlongSum/60);

			$columnSeriesX[] = $queryTime['point'];
			// $pauseDetail[] = array_merge(array('time'=> $queryTime['point']), $pauseTmp);

			$lineSeriesY[] = $useTmp['useRate'];
			// $useTotal['runTime'] += $useTmp['runTime'];
			// $useTotal['capacity'] += $useTmp['capacity'];
			// $useTotal['prodution'] += $useTmp['prodution'];
			// $useTmp['useRate'] = empty($useTmp['capacity']) ? "-" : round($useTmp['useRate'],2)*100 . "%";
			// $useTmp['runTime'] = round($useTmp['runTime'] / 60);
			// $useDetail[] = array_merge(array('time'=>$queryTime['point']), $useTmp);
		}
		// $useTotal['useRate'] = empty($useTotal['capacity']) ? '-' : round($useTotal['prodution'] / $useTotal['capacity'], 2) * 100 . '%';
		// $useTotal['runTime'] = round($useTotal['runTime'] / 60);
		// foreach($pauseTotal as &$causeTotal){
		// 	$causeTotal = round($causeTotal / 60);
		// }

		$ret = array(
			"causeArray" => $causeArray,
			// "pauseDetail" => $pauseDetail,
			// "useDetail" => $useDetail,
			// "pauseTotal" => $pauseTotal,
			// "useTotal" => $useTotal,
			"series" => array(
				'x' => $columnSeriesX,
				'column' => $columnSeriesY,
				'line' => $lineSeriesY,
			),
		);

		return $ret;
	}

	public function queryPauseRecourd($stime, $etime){
		$sql = "SELECT id, cause_type, pause_time, recover_time, TIMESTAMPDIFF(second,pause_time,recover_time) AS howlong FROM pause WHERE pause_time>='$stime' AND pause_time<'$etime' AND recover_time>'0000-00-00 00:00:00'";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		// foreach($datas as &$data) {
		// 	if(($data['recover_time']) == '0000-00-00 00:00:00'){
		// 		$data['howlong'] = (strtotime(date("Y-m-d H:i:s")) - strtotime($data['pause_time']));
		// 	}else {
		// 		$data['howlong'] = (strtotime($data['recover_time']) - strtotime($data['pause_time']));
		// 	}
		// }

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

	public function queryUseRate($stime, $etime){

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

	public function queryRecycleChart($date, $timespan) {
		switch($timespan) {
			case "monthly":
				list($stime, $etime) = $this->reviseMonthlyTime($date);
				break;
			case "yearly":
				list($stime, $etime) = $this->reviseYearlyTime($date);
				break;
			default:
				list($stime, $etime) = $this->reviseDailyTime($date);
		}

		$timeArray = $this->parseQueryTime($stime, $etime, $timespan);
		$stateArray= self::$RECYCLE_BALANCE_STATE;
		$columnSeriesX = array();
		$columnSeriesY = array();
		$lineSeriesY = array();
		foreach(self::$RECYCLE_BALANCE_STATE as $state => $stateName){
			$columnSeriesY[$stateName] = array();
		}

		foreach($timeArray as $queryTime) {
			$sDate = substr($queryTime['stime'], 0, 10);
			$eDate = substr($queryTime['etime'], 0, 10);
			$balanceArray = $this->queryRecycleBalance($sDate, $eDate);
			foreach($balanceArray as $state => $count){
				$columnSeriesY[$stateArray[$state]][] = $count;
			}
			$period = $this->queryAssemblyPeriod($queryTime['stime'], $queryTime['etime']);
			$lineSeriesY[] = $period;
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
		$sql = "SELECT state, (sum(count)/count(DISTINCT work_date)) as count FROM balance_daily WHERE work_date>='$sDate' AND work_date<'$eDate' GROUP BY series,state";
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
		$sql = "SELECT id as car_id, vin, assembly_time,finish_time,warehouse_time FROM car WHERE $condition";
		$cars = Yii::app()->db->createCommand($sql)->queryAll();
		$countSql = "SELECT COUNT(DISTINCT id) FROM car WHERE $condition";
		$count = intval(Yii::app()->db->createCommand($countSql)->queryScalar());

		$howlong = null;
		foreach($cars as $car){
			$warehouseTime = $car['warehouse_time']>'0000-00-00 00:00:00' ? $car['warehouse_time'] : date("Y-m-d H:i:s");
			$howlong += (strtotime($warehouseTime) - strtotime($car['assembly_time']));
		}
		$period = empty($count) ? null : $howlong / $count;
		$period = empty($period) ? null : round(($period / 3600), 1);

		return $period;
	}

	public function countOnline($stime, $etime){
		$sql = "SELECT COUNT(id) FROM car WHERE assembly_time>='$stime' AND assembly_time<'$etime'";
		$data = Yii::app()->db->createCommand($sql)->queryScalar();
		return $data;
	}

	public function queryOvertimeCars(){
		$sql = "SELECT id as car_id, serial_number, vin, type, series,config_id,color, assembly_line, finish_time, warehouse_time, TIMESTAMPDIFF(hour,finish_time,CURRENT_TIMESTAMP) AS recycle_period, `status`
				FROM car
				WHERE finish_time>'0000-00-00 00:00:00' AND warehouse_time='0000-00-00 00:00:00' AND `status`>'' AND TIMESTAMPDIFF(hour,finish_time,CURRENT_TIMESTAMP)>=72
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
			if(!array_key_exists($car['status'], $tablePrefixMap)) continue;
			if($car['status'] == "VQ1异常") {
				$table = "VQ1_STATIC_TEST" . $lineSuffix[$car['assembly_line']] . "_" . $car['series'];
			} else {
				$table = $tablePrefixMap[$car['status']] . "_" . $car['series'];
			}
			$faults = $this->queryUnsolvedFaults($car['car_id'],$table);
			$car['faults'] = join("、", $faults);
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

	public function queryCarDetail($date, $point, $timeSpan="daily"){
		switch($timeSpan) {
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

		$data = $this->queryDetailByPoint($stime, $etime, $point);

		return $data;
	}

	public function countCarByPoint($stime,$etime,$point="assembly"){
		$point .= "_time";
		$sql = "SELECT series, COUNT(id) as `count` FROM car WHERE $point>='$stime' AND $point<'$etime' GROUP BY series";
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
}
