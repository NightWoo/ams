<?php
Yii::import('application.models.AR.ShiftRecordAR');
class ShiftRecordCommand extends CConsoleCommand
{
	public function run($args) {
		
		$lineSpeed = array(
			'0' => 120,
			'1' => 120
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
				$ar->end_time = $curDate . ' 05:59:59';
			}

			$ar->save();
		}
	}

}
