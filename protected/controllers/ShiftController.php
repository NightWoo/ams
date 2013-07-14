<?php
Yii::import('application.models.PauseSeeker');
Yii::import('application.models.DepartmentSeeker');
Yii::import('application.models.Shift');
Yii::import('application.models.AR.PauseAR');
Yii::import('application.models.AR.ShiftRecordAR');


class ShiftController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionQuery() {
		$shiftDate = $this->validateStringVal('shiftDate', '');
		try{
			if(empty($shiftDate)) $shiftDate = DateUtil::getLastDate();
			$ret = ShiftRecordAR::model()->findAll("shift_date = '$shiftDate'");
			$this->renderJsonBms(true, 'OK', $ret);
		}catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionSave() {
		$id = $this->validateIntVal('id', 0);
		$lineSpeed = $this->validateIntVal('lineSpeed', 120);
		$startTime = $this->validateStringVal('startTime', '');
		$endTime = $this->validateStringVal('endTime', '');
		try{
			if(empty($startTime) || empty($endTime)){
				throw new Exception ('起止时间均不可为空');
			}

			if(strtotime($endTime) <=strtotime($startTime)){
				throw new Exception("结束时间不能大于起始时间");

			}

			$shiftAR = ShiftRecordAR::model()->findByPk($id);
			if(empty($shiftAR)){
				$shiftAR = new ShiftRecordAR();
			}
			$shiftAR->line_speed = $lineSpeed;
			$shiftAR->start_time = $startTime;
			$shiftAR->end_time = $endTime;
			$shiftAR->save();

			$shift = new Shift();
			$shift->updateCapacityDaily($shiftAR->shift_date);

			$this->renderJsonBms(true, 'OK', '');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionDelete() {
		$id = $this->validateIntVal('id', 0);
        try{
        	$opUserId = Yii::app()->user->id;
            $user = User::model()->findByPk($opUserId);
            if(!$user->admin) {
                BmsLogger::warning($opUserId . " try to delete plan pause record @ " .$id);
                throw new Exception ('不要做坏事，有记录的！！');
            }
			$shiftAR = ShiftRecordAR::model()->findByPk($id);
			if(!empty($shiftAR)){
				$shiftAR->delete();
			}

			$shift = new Shift();
			$shift->updateCapacityDaily($shiftAR->shift_date);

            $this->renderJsonBms(true, 'OK', '');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}
}