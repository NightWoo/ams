<?php
Yii::import('application.models.AR.WarehouseCountDailyAR');
class WarehouseCountCommand extends CConsoleCommand
{	
	private static $SERIES = array(
		'F0' => 'F0',
		'M6' => 'M6',
		'6B' => '思锐',
	);

	public function actionCountMorning() {
		$lastDate = DateUtil::getLastDate();
		$curDate = DateUtil::getCurDate();
		$seriesArray = self::$SERIES;
		$monthStart = date("Y-m", strtotime($lastDate)) . "-01 08:00:00";

		$countDate = $curDate;
		$workDate = $lastDate;
		$log = '早';

		$stime = $lastDate . " 08:00:00";
		$etime = $curDate . " 08:00:00";
		foreach($seriesArray as $series => $seriesName){
			$checkin = $this->countCheckin($stime, $etime, $series);
			$this->countRecord('入库',$checkin,$series,$countDate,$workDate,$log);
			throwTextData('入库',$checkin,$series,$countDate,$log);

			$monthCheckin = $this->countCheckin($monthStart, $etime, $series);
			$this->countRecord('已入',$monthCheckin,$series,$countDate,$workDate,$log);
			throwTextData('已入',$monthCheckin,$series,$countDate,$log);

			$checkout = $this->countCheckout($stime, $etime, $series);
			$this->countRecord('出库',$checkout,$series,$countDate,$workDate,$log);
			throwTextData('出库',$checkout,$series,$countDate,$log);

			$monthCheckout = $this->countCheckout($monthStart, $etime, $series);
			$this->countRecord('已出',$monthCheckout,$series,$countDate,$workDate,$log);
			throwTextData('已出',$monthCheckout,$series,$countDate,$log);
			
			$balance = $this->countBalance($series);
			$this->countRecord('库存',$balance,$series,$countDate,$workDate,$log);
			throwTextData('库存',$balance,$series,$countDate,$log);
		}
	}

	public function actionCountAfternoon() {
		$lastDate = DateUtil::getLastDate();
		$curDate = DateUtil::getCurDate();
		$seriesArray = self::$SERIES;
		$monthStart = date("Y-m", strtotime($lastDate)) . "-01 08:00:00";

		$countDate = $curDate;
		$workDate = $curDate;
		$log = '晚';

		$stime = $curDate . " 08:00:00";
		$etime = $curDate . " 17:30:00";

		foreach($seriesArray as $series => $seriesName){
			$checkin = $this->countCheckin($stime, $etime, $series);
			$this->countRecord('入库',$checkin,$series,$countDate,$workDate,$log);
			throwTextData('入库',$checkin,$series,$countDate,$log);

			$monthCheckin = $this->countCheckin($monthStart, $etime, $series);
			$this->countRecord('已入',$monthCheckin,$series,$countDate,$workDate,$log);
			throwTextData('已入',$monthCheckin,$series,$countDate,$log);

			$checkout = $this->countCheckout($stime, $etime, $series);
			$this->countRecord('出库',$checkout,$series,$countDate,$workDate,$log);
			throwTextData('出库',$checkout,$series,$countDate,$log);

			$monthCheckout = $this->countCheckout($monthStart, $etime, $series);
			$this->countRecord('已出',$monthCheckout,$series,$countDate,$workDate,$log);
			throwTextData('已出',$monthCheckout,$series,$countDate,$log);
			
			$balance = $this->countBalance($series);
			$this->countRecord('库存',$balance,$series,$countDate,$workDate,$log);
			throwTextData('库存',$balance,$series,$countDate,$log);
		}
	}

	private function countCheckin($stime,$etime,$series) {
		$sql = "SELECT COUNT(id) FROM car WHERE series='$series' AND warehouse_time>='$stime' AND warehouse_time<'$etime'";
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		return $count;
	}

	private function countCheckout($stime,$etime,$series) {
		$sql = "SELECT COUNT(id) FROM car WHERE series='$series' AND distribute_time>='$stime' AND distribute_time<'$etime'";
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		return $count;
	}

	private function countBalance($series, $all=false) {
		$sql = "SELECT COUNT(id) FROM car WHERE series='$series' AND (`status`='成品库' OR `status`='WDI')";
		if(!$all){
			$sql .= " AND warehouse_id < 1000 AND special_property <> 9";
		}
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		return $count;
	}

	private function throwTextData($countType,$count,$series,$date,$log) {
		$client = new SoapClient(Yii::app()->params['ams2vin_note']);
		$params = array(
			'Date'=>$date, 
			'AutoType'=>$series, 
			'Sum'=>$count,
			'StatType'=>$countType,
			'NoteLog'=>$log,
		);
		if(!empty($time)){
			$params['Date'] = $time;
		}
		$result = (array)$client -> NoteStat($params);

		return $result;
	}

	private function countRecord($countType,$count,$series,$countDate,$workDate,$log){
		$ar = new WarehouseCountDailyAR();
		$ar->series = $series;
		$ar->count = $count;
		$ar->count_type = $countType;
		$ar->count_date = $countDate;
		$ar->work_date = $workDate;
		$ar->log = $log;
		$ar->save();
	}
}
