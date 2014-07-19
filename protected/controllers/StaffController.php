<?php
Yii::import('application.models.AR.HR.HrStaffAR');
Yii::import('application.models.AR.HR.HrPositionAR');
Yii::import('application.models.HR.HrStaff');
Yii::import('application.models.HR.HrStaffSeeker');

class StaffController extends BmsBaseController
{
  /**
   * Declares class-based actions.
   */
  public function actions () {
    return array();
  }

  public function actionIndex () {

  }

  public function actionSaveStaff () {
    $id = $this->validateIntVal('staffId',  0);
    $staffData = $this->validateStringVal('staffData', '{}');
    $expData = $this->validateStringVal('expData', '[]');

    $transaction = Yii::app()->db->beginTransaction();
    try {
      $staff = HrStaff::createById($id);
      $staff->save($staffData);
      $staff->saveExp($expData);
      $transaction->commit();
      $this->renderJsonBms(true, 'save success', '');
    } catch (Exception $e) {
      $transaction->rollback();
      $this->renderJsonBms(false, $e->getMessage());
    }
  }

  public function actionGetProvinceCityList ()  {
    try {
      $seeker = new HrStaffSeeker();
      $data = $seeker->provinceCityList();
      $this->renderJsonBms(true, 'save success', $data);
    } catch (Exception $e) {
      $this->renderJsonBms(false, $e->getMessage());
    }
  }
}