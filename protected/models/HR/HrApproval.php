<?php
Yii::import('application.models.AR.HR.HrApprovalAR');
Yii::import('application.models.AR.HR.HrApprovalSubAR');
Yii::import('application.models.AR.HR.HrTransferAR');
Yii::import('application.models.AR.HR.HrProcedurePointAR');
Yii::import('application.models.HR.HrStaff');

class HrApproval {
  private $_ar;

  public function __construct($approvalId=0) {
    if (empty($approvalId)) {
      $this->_ar = new HrApprovalAR();
    } else {
      $this->_ar = HrApprovalAR::model()->findByPk($approvalId);
    }
  }

  public static function createById($id=0) {
    $c = __class__;
    return new $c($id);
  }

  public function create($data) {
    foreach ($data as $key => $value) {
      $this->_ar->$key = $value;
    }
    $this->_ar->create_time = date('YmdHis');
    $this->_ar->save();
    $this->createSub();
  }

  public static function createProcess($transferId, $processDepts) {
    foreach ($processDepts as $procedureId => $deptId) {
      if (!empty($deptId)) {
        $data = array(
          'transfer_id' => $transferId,
          'procedure_id' => $procedureId,
          'dept_id' => $deptId
        );
        if ($procedureId == 1) {
          $data['status'] = 1;
        }
        $apporval = HrApproval::createById();
        $apporval->create($data);
      }
    }
  }

  public function createSub() {
    $points = HrProcedurePointAR::model()->findAll('procedure_id=?', array($this->_ar->procedure_id));
    if (!empty($points)) {
      foreach ($points as $point) {
        $sub = new HrApprovalSubAR();
        $sub->approval_id = $this->_ar->id;
        $sub->procedure_point_id = $point->id;
        $sub->create_time = date('YmdHis');
        $sub->save();
      }
    }
  }

  public function approve($approvalForm) {
    $this->_ar->conclusion = $approvalForm['conclusion'];
    $this->_ar->comment = $approvalForm['comment'];
    $this->_ar->status = 2;
    $this->_ar->user_id = Yii::app()->user->id;
    $this->_ar->save();
    if (!empty($approvalForm['sub'])) {
      foreach ($approvalForm['sub'] as $sub) {
        $this->approveSub($sub);
      }
    }
    if ($approvalForm['conclusion'] == 1) {
      $this->activeNext();
    } else {
      $this->closeReject();
    }
  }

  public function approveSub($sub) {

    $subAr = HrApprovalSubAR::model()->findByPk($sub['id']);
    $subAr->conclusion = $sub['conclusion'];
    $subAr->save();
  }

  public function activeNext() {
    $approvalAr = HrApprovalAR::model()->find('transfer_id=? AND status=0 ORDER BY procedure_id ASC', array($this->_ar->transfer_id));
    if (!empty($approvalAr)) {
      if ($approveAr->dept_id>0) {
        $approvalAr->status = 1;
        $approvalAr->save();
      } else { // 自动结案
        $approvalAr->status = 2;
        $approvalAr->conclusion = 1;
        $approvalAr->user_id = 2;
        $approvalAr->save();

        $transfer = HrTransfer::createById($approvalAr->transfer_id);
        $transfer->closeApproved();
      }
    }
  }

  public function closeReject() {
    $approvalArs = HrApprovalAR::model()->findAll('transfer_id=? AND status=0 ORDER BY procedure_id ASC', array($this->_ar->transfer_id));
    foreach ($approvalArs as $ar) {
      if ($ar->procedure_id == 5) {
        $ar->status = 2;
        $ar->conclusion = 0;
        $transfer = HrTransfer::createById($this->_ar->transfer_id);
        $transfer->closeReject();
      } else {
        $ar->status = -1;
      }
      $ar->save();
    }
  }

  public function closeApproved() {
    $this->_ar->status = 2;
    $this->_ar->conclusion = 1;
    $this->_ar->user_id = 2;
    $this->_ar->save();

    $transfer = HrTransfer::createById($this->_ar->transfer_id);
    $transfer->closeApproved();
  }
}