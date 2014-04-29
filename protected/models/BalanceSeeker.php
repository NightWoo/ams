<?php
Yii::import('application.models.AR.CarAR');
Yii::import('application.models.Car');

class BalanceSeeker
{
	public function __construct () {

	}

	private static $NODE_BALANCE_STATE = array(
		'PBS' => array('彩车身库','预上线'),
		'PBS-inventory' => array('彩车身库'),
		'PBS-inQueue' => array('预上线'),
		'onLine-all' => array('T1工段' ,'T2工段', 'T3工段', 'C1工段', 'C2工段', 'F1工段', 'F2工段', 'VQ1检验','II_T1工段' ,'II_T2工段', 'II_T3工段', 'II_C1工段', 'II_C2工段', 'II_F1工段', 'II_F2工段', 'II_VQ1检验'),
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
		'WH-1' => array('成品库'),
		'WH-2' => array('成品库'),
		'WH-5' => array('成品库'),
		'WH-3' => array('成品库'),
		'WH-4' => array('成品库'),
		//'WH-5' => array('成品库'),
		'WH-27-export' => array('成品库'),
		'WH-X' => array('成品库'),
		//'WH-T' => array('成品库'),
		'WH-WDI' => array('WDI'),
		'assembly' => array('T1工段' ,'T2工段', 'T3工段', 'C1工段', 'C2工段', 'F1工段', 'F2工段', 'VQ1检验', 'II_T1工段' ,'II_T2工段', 'II_T3工段', 'II_C1工段', 'II_C2工段', 'II_F1工段', 'II_F2工段', 'II_VQ1检验', 'VQ1异常','VQ1合格', '出生产车间' , 'VQ1退库', '检测线缓冲','VQ2检测线','VQ2路试', 'VQ2淋雨', 'VQ2异常.路试', 'VQ2异常.漏雨' , 'VQ2退库', 'VQ3检验' ,'VQ3合格', 'VQ3异常' , 'VQ3退库','成品库','WDI'),
	);

	private static $COLD_RESISTANT = array('非耐寒','耐寒');

	public function queryBalancePeriod ($state, $series) {
		$intervalMap=array(
			'assembly' => 'period_all',
			'onLine-all' => 'assembly_period',
			'onLine' => 'assembly_period',
			'onLine-2' => 'assembly_period',
			'VQ1' => 'vq1_period',
			'VQ2' => 'vq2_period',
			'VQ3' => 'vq3_period',
			'recycle' => 'recycle_period',
			'WH' => 'warehouse_period',
			'WH-WDI' => 'standby_period',
			'WHin' => 'inventory_period'
		);

		$seriesArray = Series::parseSeries($series);
		$seriesName = Series::getNameList();

		$dataSeriesX = array();
		$dataSeriesY = array();
		$detail = array();
		$seriesTotal = array();

		foreach($seriesArray as $series){
			$seriesTotal[$seriesName[$series]] = 0;
		}

		$span = 256;
		$intercept = 8;
		$periodSegmentArray = $this->periodSegmentArray($span,$intercept);
		foreach($periodSegmentArray as $key => $value) {
			$dataSeriesX[] = $key;
			$temp['periodSegment'] = $key;
			foreach($seriesArray as $series) {
				$dataSeriesY[$seriesName[$series]][] = null;
				$temp[$seriesName[$series]] = 0;
			}
			$detail[] = $temp;
		}

		$whAvailableOnly = $state == 'WHin' ? true : false;
		foreach($seriesArray as $series){
			$cars = $this->queryBalanceCars($state, $series, $whAvailableOnly);
			foreach($cars as &$car) {
				$vq1StartTime = $car['vq1_return_time'] > "0000-00-00 00:00:00" ? $car['vq1_return_time'] : $car['finish_time'];
				$vq2StartTime = $car['vq2_return_time'] > "0000-00-00 00:00:00" ? $car['vq2_return_time'] : $car['vq1_finish_time'];
				$vq3StartTime = $car['vq3_return_time'] > "0000-00-00 00:00:00" ? $car['vq3_return_time'] : $car['vq2_finish_time'];

				$car['assembly_period'] = $this->calculatePeriod($car['assembly_time'], $car['finish_time']) / 3600;
				$car['vq1_period'] = $this->calculatePeriod($vq1StartTime, $car['vq1_finish_time']) / 3600;
				$car['vq2_period'] = $this->calculatePeriod($vq2StartTime, $car['vq2_finish_time']) / 3600;
				$car['vq3_period'] = $this->calculatePeriod($vq3StartTime, $car['warehouse_time']) / 3600;
				$car['inventory_period'] = $this->calculatePeriod($car['warehouse_time'], $car['standby_time']) / 3600;
				$car['standby_period'] = $this->calculatePeriod($car['standby_time'], $car['distribute_time']) / 3600;
				$car['manufacture_period'] = $car['assembly_period'] + $car['vq1_period'] + $car['vq2_period'] + $car['vq3_period'];
				$car['recycle_period'] = $car['vq1_period'] + $car['vq2_period'] + $car['vq3_period'];
				$car['warehouse_period'] = $car['inventory_period'] + $car['standby_period'];
				$car['period_all'] = $car['assembly_period'] + $car['vq1_period'] + $car['vq2_period'] + $car['vq3_period'] + $car['inventory_period'] + $car['standby_period'];

				$yIndex = intval($car[$intervalMap[$state]] / $intercept);
				$maxIndex = $span / $intercept;
				$car['yIndex'] = ($yIndex < $maxIndex) ? $yIndex : $maxIndex;

				$dataSeriesY[$seriesName[$series]][$car['yIndex']]++;
				$detail[$car['yIndex']][$seriesName[$series]]++;
				$seriesTotal[$seriesName[$car['series']]]++;
			}
		}

		$carSeries = array();
		foreach($seriesArray as $key => $series){
			$carSeries[] = $seriesName[$series];
		}

		return array(
			'carSeries' => $carSeries,
			'periodInterval' => $intervalMap[$state],
			'detail' => $detail,
			'seriesTotal' => $seriesTotal,
			'series' => array(
				'x' => $dataSeriesX,
				'y' => $dataSeriesY
			)
		);
	}

	public function queryBalanceCars ($state, $series='', $whAvailableOnly=false) {
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
		$condition = "status IN ($str)";
		if(!empty($series)){
			$condition .= " AND series='$series'";
		}
		if($whAvailableOnly){
			$condition .= " AND warehouse_id > 1 AND warehouse_id < 1000 AND special_property=0";
		}

		$sql = "SELECT id AS car_id, vin, assembly_line, `status`, special_property, series, assembly_time, finish_time, vq1_finish_time, vq2_finish_time, warehouse_time, standby_time, distribute_time, vq1_return_time, vq2_return_time, vq3_return_time
				FROM car
				WHERE $condition";
		$cars = Yii::app()->db->createCommand($sql)->queryAll();

		return $cars;
	}

	private function calculatePeriod ($start, $end) {
		$time = 0;
		if($start > '0000-00-00 00:00:00') {
			if($end == '0000-00-00 00:00:00') {
				$time = time() - strtotime($start);
			} else if ($end > $start) {
				$time = strtotime($end) - strtotime($start);
			}
		}
		return $time;
	}

	private function periodArray () {
		$periodArray = array(
			'&lt;4H' => array('low'=>0,'high'=>4),
			'4-8H' => array('low'=>4,'high'=>8),
			'8-12H' => array('low'=>8,'high'=>12),
			'12-16H' => array('low'=>12,'high'=>16),
			'16-20H' => array('low'=>16,'high'=>20),
			'20-24H' => array('low'=>20,'high'=>24),
			'>24H' => array('low'=>24,'high'=>0),
		);

		return $periodArray;
	}

	private function periodSegmentArray ($span=128,$intercept=8) {
		$segments = ceil($span/$intercept);
		$periodSegmentArray = array();
		for($i=0;$i<$segments;$i++) {
			$low = $i * $intercept;
			$high = ($i + 1) * $intercept;
			$text = $low . "-" . $high;
			$periodSegmentArray[$text] = array('low'=>$low, 'high'=>$high);
		}
		$lastText = ">". $span;
		$periodSegmentArray[$lastText] = array('low'=>$span, 'high'=>0);

		return $periodSegmentArray;
	}

	// private function parseSeries ($series) {
	// 	if(empty($series) || $series === 'all') {
 //            $series = array('F0', 'M6', '6B');
 //        } else {
 //            $series = explode(',', $series);
 //        }
	// 	return $series;
	// }

	// private function seriesName () {
	// 	$seriesName = array(
	// 		'F0' => 'F0',
	// 		'M6' => 'M6',
	// 		'6B' => '思锐'
	// 	);

	// 	return $seriesName;
	// }

	private function periodInterval () {
		$intervalMap=array(
			'assembly' => '',
			'onLine-all' => 'assemblyPeriod',
			'onLine' => 'assemblyPeriod',
			'onLine-2' => 'assemblyPeriod',
			'VQ1' => 'vq1Period',
			'VQ2' => 'vq2Period',
			'VQ3' => 'vq3Period',
			'recycle' => 'recyclePeriod',
			'WH' => 'warehousePeriod',
			'WH-WDI' => 'standbyPeriod',
			'WHin' => 'inventoryPeriod',
		);
		return $intervalMap[$state];
	}

	private function stateArray ($state) {
		$stateMap=array(
			'PBS' => array('PBS-inventory', 'PBS-inQueue'),
			'PBS-inventory' => array('PBS-inventory'),
			'PBS-inQueue' => array('PBS-inQueue'),
			'onLine-all' => array('onLine', 'onLine-2'),
			'onLine' => array('onLine'),
			'onLine-2' => array('onLine-2'),
			'VQ1' => array('VQ1-NORMAL', 'VQ1-RETURN'),
			'VQ2' => array('VQ2-NORMAL', 'VQ2-RETURN'),
			'VQ3' => array('VQ3-NORMAL','VQ3-RETURN'),
			'VQ3-OK' => array('VQ3-OK'),
			'recycle' => array('VQ1', 'VQ2', 'VQ3'),
			//'WH' => array('WH-1', 'WH-2', 'WH-3', 'WH-4', 'WH-5', 'WH-X', 'WH-T', 'WH-27-export', 'WH-WDI'),
			'WH' => array('WH-1', 'WH-2', 'WH-5','WH-3', 'WH-4', 'WH-X','WH-27-export', 'WH-WDI'),
			'WH-WDI' => array('WH-WDI'),
			'WHin' => array('WHin'),
			'assembly' => array('PBS', 'onLine', 'onLine-2', 'VQ1', 'VQ2', 'VQ3', 'WH'),
			'mergeRecyle' => array('PBS','onLine', 'onLine-2', 'recycle', 'WH'),
		);
		return $stateMap[$state];
	}

	private function stateName () {
		$stateName = array(
			'PBS' => 'PBS',
			'onLine' => 'I线',
			'onLine-2' => 'II线',
			'VQ1' => 'VQ1',
			'VQ1-NORMAL' => '正常VQ1',
			'VQ1-RETURN' => 'VQ1退库',
			'VQ2' => 'VQ2',
			'VQ2-NORMAL' => '正常VQ2',
			'VQ2-RETURN' => 'VQ2退库',
			'VQ3-NORMAL' => '正常VQ3',
			'VQ3-RETURN' => 'VQ3退库',
			'VQ3-OK' => 'VQ3合格',
			'VQ3' => 'VQ3',
			'recycle' => '周转车',
			'WH' => '成品库',
			'WHin' => '成品库可备',
			'assembly' => '总装',
			'WH-1' => '1号库',
			'WH-2' => '2号库',
			'WH-5' => '3号库(油库区)',
			'WH-3' => '4号库(35#)',
			'WH-4' => '5号库(14#)',
			//'WH-5' => '3号库(油库区)',
			'WH-27-export' => '出口车(27#)',
			'WH-X' => 'X(非商品车区)',
			//'WH-T' => 'T(非库位区)',
			'WH-WDI' => 'WDI',
		);

		return $stateName;
	}
}
