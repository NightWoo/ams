<?php
Yii::import('application.models.AR.BalanceDailyAR');
Yii::import('application.models.CarSeeker');
class BalanceDailyCommand extends CConsoleCommand
{
	private static $SERIES = array(
		'F0' => 'F0',
		'M6' => 'M6',
		'6B' => '思锐',
	);

	private static $STATES=array('onLine','onLine-2','VQ1','VQ2','VQ3','WH');

	public function actionCountMorning() {
		$lastDate = DateUtil::getLastDate();
		$curDate = DateUtil::getCurDate();

		$countDate = $curDate;
		$workDate = $lastDate;

		foreach(self::$STATES as $state){
			foreach(self::$SERIES as $series => $seriesName){
				$balance =$this->countBalance($state, $series);
				$this->countSave($state,$balance,$series,$countDate,$workDate);
			}
		}
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

}
