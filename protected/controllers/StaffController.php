<?php
Yii::import('application.models.AR.HR.HrStaffAR');
Yii::import('application.models.AR.HR.HrPositionAR');
Yii::import('application.models.AR.HR.HrTransferAR');
Yii::import('application.models.HR.HrStaff');
Yii::import('application.models.HR.HrApproval');
Yii::import('application.models.HR.HrStaffSeeker');

class StaffController extends BmsBaseController
{
  /**
   * Declares class-based actions.
   */
  public function actions() {
    return array();
  }

  public function actionIndex() {

  }

  public function actionSaveStaff() {
    $id = $this->validateIntVal('staffId',  0);
    $staffData = $this->validateStringVal('staffData', '{}');
    $expData = $this->validateStringVal('expData', '[]');

    $transaction = Yii::app()->db->beginTransaction();
    try {
      $staff = HrStaff::createById($id);
      $staff->save($staffData);
      $staff->saveExp($expData);
      $transaction->commit();
      $this->renderJsonApp(true, 'save success', '');
    } catch (Exception $e) {
      $transaction->rollback();
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionQueryTansferInfo() {
    $employeeNumber = $this->validateStringVal('employeeNumber', '');

    try {
      $seeker = new HrStaffSeeker();
      $basicInfo = $seeker->queryTransferBasicInfo($employeeNumber);
      $applyInfo = array();
      $approvalRecords = array();
      if (!empty($basicInfo)) {
        $applyInfo = $seeker->queryTransferApplyInfo($basicInfo['id']);
        if (!empty($applyInfo)) {
          $approvalRecords = $seeker->queryApprovalInfo($applyInfo['id']);
        }
      }

      $ret = array(
        'basicInfo' => $basicInfo,
        'applyInfo' => $applyInfo,
        'approvalRecords' => $approvalRecords
      );
      $this->renderJsonApp(true, 'OK', $ret);
    } catch (Exception $e) {
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionGetProvinceCityList()  {
    try {
      $seeker = new HrStaffSeeker();
      $data = $seeker->provinceCityList();
      $this->renderJsonApp(true, 'save success', $data);
    } catch (Exception $e) {
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionApplyTransfer() {
    $id = $this->validateIntVal('staffId', 0);
    $applyForm = $this->validateStringVal('applyForm', '{}');

    $transaction = Yii::app()->db->beginTransaction();
    try {
      $staff = HrStaff::createById($id);
      $data = $staff->applyTransfer($applyForm);
      $transaction->commit();

      $seeker = new HrStaffSeeker();
      $applyInfo = array();
      $approvalRecords = array();
      if (!empty($staff)) {
        $applyInfo = $seeker->queryTransferApplyInfo($id);
        if (!empty($applyInfo)) {
          $approvalRecords = $seeker->queryApprovalInfo($applyInfo['id']);
        }
      }
      $ret = array(
        'applyInfo' => $applyInfo,
        'approvalRecords' => $approvalRecords
      );
      $this->renderJsonApp(true, 'apply success', $ret);
    } catch (Exception $e) {
      $transaction->rollback();
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionGetApprovalInfo() {
    $employeeNumber = $this->validateStringVal('employeeNumber', '');

    try {
      $seeker = new HrStaffSeeker();
      $data = $seeker->queryApprovalInfo($employeeNumber);

      $this->randerJsonApp(true, 'ok', $data);
    } catch (Exception $e) {
      $this->randerJsonApp(false, $e->getMessage());
    }
  }

  public function actionGetMyApproval() {
    try {
      $seeker = new HrStaffSeeker();
      $data = $seeker->queryMyApproval();
      $this->renderJsonApp(true, 'ok', $data);
    } catch (Exception $e) {
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionSubmitApproval() {
    $approvalForm = $this->validateStringVal('approvalForm', '{}');
    $transferDate = $this->validateStringVal('transferDate', '');
    $transaction = Yii::app()->db->beginTransaction();
    try {
      $approvalForm = is_array($approvalForm) ? $approvalForm : CJSON::decode($approvalForm);
      $approval = HrApproval::createById($approvalForm['id']);
      $approval->approve($approvalForm);

      if (!empty($transferDate)) {

        $transfer = HrTransferAR::model()->findByPk($approvalForm['transfer_id']);
        $transfer->transfer_date = $transferDate;
        $transfer->save();
      }

      $transaction->commit();

      $approvalRecords = array();
      if (!empty($approvalForm)) {
        $seeker = new HrStaffSeeker();
        $approvalRecords = $seeker->queryApprovalInfo($approvalForm['transfer_id']);
      }

      $this->renderJsonApp(true, 'ok', $approvalRecords);
    } catch (Exception $e) {
      $transaction->rollback();
      $this->renderJsonApp(false, $e->getMessage());
    }
  }
}