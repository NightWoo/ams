<?php
Yii::import('application.models.AR.HR.HrStaffAR');
Yii::import('application.models.AR.HR.HrStaffExpAR');
Yii::import('application.models.AR.HR.HrTransferAR');
Yii::import('application.models.HR.HrStaffPositionAR');
Yii::import('application.models.HR.HrApproval');
Yii::import('application.models.HR.HrStaffSeeker');
Yii::import('application.models.HR.OrgStructureSeeker');
Yii::import('application.models.HR.HrApprovalProcess');

class HrStaff {
  private $_ar;
  public function __construct($staffId = 0) {
    if (empty($staffId)) {
      $this->_ar = new HrStaffAR();
    } else {
      $this->_ar = HrStaffAR::model()->findByPk($staffId);
    }
  }

  public static function createById($id) {
    $c = __class__;
    return new $c($id);
  }

  public function __get($attr) {
    return $this->{$attr};
  }

  public function save($staffData) {
    $staffData = is_array($staffData) ? $staffData : CJSON::decode($staffData);
    if (empty($this->_ar->id)) {
      $staffData['create_time'] = date('YmdHis');
    }
    foreach($staffData as $key => $value) {
      $this->_ar->$key = $value;
    }
    $this->_ar->save();

    $staff->positionStart($staffData['dept_id'], $staffData['position_id'], $staffData['start_date']);
  }

  public function saveExp($expData) {
    $expData = is_array($expData) ? $expData : CJSON::decode($expData);
    foreach ($expData as $exp) {
      $expAr = new HrStaffExpAR();
      foreach ($exp as $key => $value) {
        $expAr->$key = $value;
      }
      if (empty($expAr->staff_id)) {
        $expAr->staff_id = $this->_ar->id;
      }
      $expAr->save();
    }
  }

  public function remove() {
    $this->_ar->removed = 1;
    $this->_ar->save();
  }

  public function applyTransfer($applyForm) {
    $apply =  is_array($applyForm) ? $applyForm : CJSON::decode($applyForm);
    $apply['staff_id'] = $this->_ar->id;
    $apply['dept_id'] = $this->_ar->dept_id;
    $apply['position_id'] = $this->_ar->position_id;
    $apply['create_time'] = date('YmdHis');

    list($type, $processDepts) = HrApprovalProcess::typeProcess($apply['dept_id'], $apply['apply_dept_id']);

    $apply['process_type'] = $type;

    $transfer = HrTransfer::createById();
    $transfer->save($apply);

    HrApproval::createProcess($transfer->_ar->id, $processDepts);

    return $transfer->_ar;
  }

  public function positionStart($deptId, $positionId, $startDate='') {
    $staffPosition= new HrStaffPositionAR();
    $staffPosition->staff_id = $this->_ar->id;
    $staffPosition->dept_id = $deptId;
    $staffPosition->position_id = $positionId;
    $staffPosition->start_date = empty($startDate) ? date('Y-m-d') : $startDate;
    $staffPosition->save();
  }

}