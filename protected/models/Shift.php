<?php
Yii::import('application.models.AR.PauseAR');
Yii::import('application.models.AR.ShiftRecordAR');
Yii::import('application.models.AR.ManufactureCapacityDailyAR');
Yii::import('application.models.ReportSeeker');

class Shift
{
	public function __construct(){
	}

	public function updateCapacityDaily($workDate){
		$seeker = new ReportSeeker();
		list($stime, $etime) = $seeker->reviseDailyTime($workDate);
		$use =$seeker->queryUseRateBase($stime, $etime);

		$capacityAR = ManufactureCapacityDailyAR::model()->find("work_date='$workDate'");
        if(empty($capacityAR)) $capacityAR = new ManufactureCapacityDailyAR();
        $capacityAR->work_date = $workDate;
        $capacityAR->capacity = $use['capacity'];
        $capacityAR->run_time = $use['runTime'];
        $capacityAR->save();
	}
}
