<?php
Yii::import('application.models.AR.ShiftRecordAR');
Yii::import('application.models.AR.PauseAR');
Yii::import('application.models.AR.DeviceParameterAR');
Yii::import('application.models.Shift');
Yii::import('application.models.AR.ShiftRecordAR');
class ShiftRecordCommand extends CConsoleCommand
{
	public function actionAddShiftDaily() {
		$speed = $this->getLineSpeed();
		$lineSpeed = array(
			'0' => $speed,
			'1' => $speed
		);

		$curDate = DateUtil::getCurDate();
		$lastDate = DateUtil::getLastDate();

		foreach($lineSpeed as $shift => $value){
			$ar = new ShiftRecordAR();
			$ar->line = 'I';
			$ar->shift = $shift;
			$ar->shift_date = $lastDate;
			$ar->line_speed = $value;
			if($shift === 0){
				$ar->start_time = $lastDate . ' 08:00:00';
				$ar->end_time = $lastDate . ' 19:59:59';
			}else if($shift === 1){
				$ar->start_time = $lastDate . ' 20:00:00';
				$ar->end_time = $curDate . ' 07:59:59';
			}

			$ar->save();
		}

		//早例行休息
		$stime = $lastDate . " 09:50:00";
		$etime = $lastDate . " 10:00:00";
		$this->addPlanPause($stime, $etime, "早例行休息");

		//白班午休增加半小时
		// $stime = $lastDate . " 12:30:00";
		// $etime = $lastDate . " 13:00:00";
		// $this->addPlanPause($stime, $etime, "午休增加30分钟");

		//午例行休息
		$stime = $lastDate . " 15:50:00";
		$etime = $lastDate . " 16:00:00";
		$this->addPlanPause($stime, $etime, "午例行休息");

		//夜例行休息
		$stime = $lastDate . " 22:50:00";
		$etime = $lastDate . " 23:00:00";
		$this->addPlanPause($stime, $etime, "夜例行休息");

		//凌晨例行休息
		$stime = $curDate . " 03:50:00";
		$etime = $curDate . " 04:00:00";
		$this->addPlanPause($stime, $etime, "凌晨例行休息");
	}

	public function actionAddShiftAfternoon() {
		$shift = 0;
		$lineSpeed = $this->getLineSpeed();
		$curDate = DateUtil::getCurDate();

		$ar = new ShiftRecordAR();
		$ar->line = 'I';
		$ar->shift = $shift;
		$ar->shift_date = $curDate;
		$ar->line_speed = $lineSpeed;
		$ar->start_time = $curDate . ' 08:00:00';
		$ar->end_time = $curDate . ' 19:59:59';

		$ar->save();

		//早例行休息
		$stime = $curDate . " 09:50:00";
		$etime = $curDate . " 10:00:00";
		$this->addPlanPause($stime, $etime, "早例行休息");

		// //白班午休增加半小时
		// $stime = $curDate . " 12:30:00";
		// $etime = $curDate . " 13:00:00";
		// $this->addPlanPause($stime, $etime, "午休增加30分钟");

		//午例行休息
		$stime = $curDate . " 15:50:00";
		$etime = $curDate . " 16:00:00";
		$this->addPlanPause($stime, $etime, "午例行休息");
	}

	public function actionAddShiftMorning() {
		$shift = 1;
		// $lineSpeed = $this->getLineSpeed();

		// $curDate = DateUtil::getCurDate();
		// $lastDate = DateUtil::getLastDate();

		// $ar = new ShiftRecordAR();
		// $ar->line = 'I';
		// $ar->shift = $shift;
		// $ar->shift_date = $lastDate;
		// $ar->line_speed = $lineSpeed;
		// $ar->start_time = $lastDate . ' 20:00:00';
		// $ar->end_time = $curDate . ' 07:59:59';

		// $ar->save();

		// //夜例行休息
		// $stime = $lastDate . " 22:50:00";
		// $etime = $lastDate . " 23:00:00";
		// $this->addPlanPause($stime, $etime, "夜例行休息");

		// //凌晨例行休息
		// $stime = $curDate . " 03:50:00";
		// $etime = $curDate . " 04:00:00";
		// $this->addPlanPause($stime, $etime, "凌晨例行休息");
	}

	public function actionAddCapacityDaily() {
		$workDate = DateUtil::getLastDate();
		$shift = new Shift();
		$shift->updateCapacityDaily($workDate);
	}

	public function addPlanPause($stime, $etime, $remark=""){
		$pause = new PauseAR();
		$pause->line = "I";
		$pause->pause_type = "计划停线";
		$pause->status = 0;
		$pause->pause_time = $stime;
		$pause->recover_time = $etime;
		$pause->remark = $remark;
		$pause->editor = 2;
		$pause->edit_time = date('YmdHis');
		$pause->save();

	}

	public function getLineSpeed () {
		$lineSpeed = DeviceParameterAR::model()->find("name='line_speed'")->value;
		return $lineSpeed;
	}
}
