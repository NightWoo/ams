<?php
Yii::import('application.models.AR.monitor.LineRunAR');
class LineStatusCommand extends CConsoleCommand
{
	public function run($args) {
		$sysUid = $this->getSystemUserId();
		$curDate = DateUtil::getCurDate();
		$curTime = $curDate . ' 08:00:00';
		if(date("Y-m-d H:i:s") < $curTime) {
			return;
		}
		$lastStatus = LineRunAR::model()->find('1 order by create_time desc');
		if(!empty($lastStatus) && $lastStatus->event === '启动' && $lastStatus->create_time < $curTime) {
			//last event is run and time < 8:00 ,add stop and run status
			$lineStop = new LineRunAR();
			$lineStop->event = '停止';
			$lineStop->remark = "stop by system";
			$lineStop->user_id = $sysUid;
			$lineStop->create_time = $curDate . ' 07:59:59';
			$lineStop->save();


			$lineStart = new LineRunAR();
            $lineStart->event = '启动';
            $lineStart->remark = "start by system";
            $lineStart->user_id = $sysUid;
            $lineStart->create_time = $curTime;
            $lineStart->save();
			
		}
	}

	private function getSystemUserId() {
		$sql = "SELECT id FROM user WHERE username='system'";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}

}
