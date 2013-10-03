<?php
Yii::import('application.models.AR.CarAR');
Yii::import('application.models.Car');

class BalanceSeeker
{
	public function __construct () {

	}

	private static $NODE_BALANCE_STATE = array(
		'PBS' => array('彩车身库','预上线'),
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
		'assembly' => array('T1工段' ,'T2工段', 'T3工段', 'C1工段', 'C2工段', 'F1工段', 'F2工段', 'VQ1检验', 'II_T1工段' ,'II_T2工段', 'II_T3工段', 'II_C1工段', 'II_C2工段', 'II_F1工段', 'II_F2工段', 'II_VQ1检验', 'VQ1异常','VQ1合格', '出生产车间' , 'VQ1退库', '检测线缓冲','VQ2检测线','VQ2路试', 'VQ2淋雨', 'VQ2异常.路试', 'VQ2异常.漏雨' , 'VQ2退库', 'VQ3检验' ,'VQ3合格', 'VQ3异常' , 'VQ3退库','成品库'),
	);

	private static $COLD_RESISTANT = array('非耐寒','耐寒');

	public function queryBalancePeriod ($state, $series) {
		$intercept = 4;
		$series = $this->seriesName($series);
		$seriesName = $this->seriesName();
		$periodInterval = $this->periodInterval();

		$detail = array();
		$dataSeriesX = array();
		$dataSeriesY = array();

	}

	public function countPeriodCar ($state, $series='') {
		
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

	private function periodInterval ($span=128,$intercept=8) {
		$segments = ceil($span/$intercept);
		$periodInterval = array();
		for($i=0;$i<$segments;$i++) {
			$low = $i * $intercept;
			$high = ($i + 1) * $intercept;
			$text = $low . "-" . $high;
			$periodInterval[$text] = array('low'=>$low, 'high'=>$high);
		}

		return $periodInterval;
	}

	private function parseSeries ($series) {
		if(empty($series) || $series === 'all') {
            $series = array('F0', 'M6', '6B');
        } else {
            $series = explode(',', $series);
        }
		return $series;
	}

	private function seriesName () {
		$seriesName = array(
			'F0' => 'F0',
			'M6' => 'M6',
			'6B' => '思锐'
		);

		return $seriesName;
	}

	private function stateArray ($state) {
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
			'WH' => array('WH-0','WH-27-export','WH-27-normal','WH-35','WH-14','WH-X','WH-T','WH-13','WH-15','WH-WDI'),
			'WHin' => array('WHin'),
			'assembly' => array('PBS', 'onLine', 'onLine-2', 'VQ1', 'VQ2', 'VQ3', 'WH'),
			'mergeRecyle' => array('PBS','onLine', 'onLine-2','recycle', 'WH'),
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
			'WH-0' => '成品库区',
			'WH-27-export' => '27#出口',
			'WH-27-normal' => '27#普通',
			'WH-35' => '35#厂房',
			'WH-X' => '异常X',
			'WH-WDI' => 'WDI',
			'WH-14' => '14#厂房',
			'WH-13' => '13#厂房',
			'WH-15' => '15#厂房',
			'WH-T' => '临时T',
		);

		return $stateName;
	}
}