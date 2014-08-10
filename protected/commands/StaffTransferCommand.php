<?php
Yii::import('application.models.AR.HR.HrStaffAR');
Yii::import('application.models.AR.HR.HrStaffPositionAR');

class StaffTransferCommand extends CConsoleCommand
{

	public function actionTransferActive () {
		$date = date('Y-m-d');
		$positionRecords = HrStaffPositionAR::model()->findAll("start_date<='$date' AND status=0");
		if (!empty($positionRecords)) {
			foreach ($positionRecords as &$transfer) {
				$staff = HrStaffAR::model()->findByPk($transfer->staff_id);
				if (!empty($staff)) {
					$transaction = Yii::app()->db->beginTransaction();
					try {
						$staff->dept_id = $transfer->dept_id;
						$staff->position_id = $transfer->position_id;
						$staff->save();
						$transfer->status = 1;
						$transfer->save();
						$transaction->commit();
					} catch (Exception $e) {
						$transaction->rollback();
					}
				}
			}
		}
	}
}
