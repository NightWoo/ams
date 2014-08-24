<?php
Yii::import('application.models.AR.BalanceDailyAR');
Yii::import('application.models.AR.BalancePlanningDivisionDailyAR');
Yii::import('application.models.CarSeeker');
class BalanceDailyCommand extends CConsoleCommand
{
	// private static $SERIES = array(
	// 	'F0' => 'F0',
	// 	'M6' => 'M6',
	// 	'6B' => '思锐',
	// );

	private static $STATES=array('onLine','onLine-2','VQ1','VQ2','VQ3','WH');

	public function actionCountMorning() {
		$lastDate = DateUtil::getLastDate();
		$curDate = DateUtil::getCurDate();

		$countDate = $curDate;
		$workDate = $lastDate;

		foreach(self::$STATES as $state){
			$seriesList = Series::getNameList();
			foreach($seriesList as $series => $seriesName){
				$balance =$this->countBalance($state, $series);
				$this->countSave($state,$balance,$series,$countDate,$workDate);
			}
		}

		$this->saveWarehousePlnningDivision($countDate, $workDate);
	}

	private function countBalance($state, $series) {
		$seeker = new CarSeeker();
		$count = $seeker->countStateCars($state,$series);
		return $count;
	}


	private function countSave($state,$count,$series,$countDate,$workDate,$log=0){
		$ar = new BalanceDailyAR();
		$ar->series = $series;
		$ar->count = $count;
		$ar->state = $state;
		$ar->count_date = $countDate;
		$ar->work_date = $workDate;
		$ar->log = $log;
		$ar->record_time = date("YmdHis");
		$ar->save();
	}

	private function saveWarehousePlnningDivision ($countDate, $workDate) {
		$sql = "SELECT series,planning_division_type_name as pdType,special_property, COUNT(car_id) as `count` FROM view_car_info_main WHERE status='成品库' OR status='WDI' GROUP BY series,PDType,special_property";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		foreach($datas as $data) {
			$ar = new BalancePlanningDivisionDailyAR();
			$ar->series = $data['series'];
			$ar->planning_division_type_name = $data['pdType'];
			$ar->special_property = $data['special_property'];
			$ar->count = $data['count'];
			$ar->state = 'WH';
			$ar->count_date = $countDate;
			$ar->work_date = $workDate;
			$ar->record_time = date("YmdHis");
			$ar->save();
		}
	}
}
