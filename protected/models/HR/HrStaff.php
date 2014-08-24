<?php
Yii::import('application.models.AR.HR.HrStaffAR');
Yii::import('application.models.AR.HR.HrStaffExpAR');
Yii::import('application.models.HR.HrTransfer');
Yii::import('application.models.AR.HR.HrStaffPositionAR');
Yii::import('application.models.AR.HR.HrResignAR');
Yii::import('application.models.AR.HR.HrResignSurveyAR');
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
      if (!empty($value)) {
        $this->_ar->$key = $value;
      }
    }
    $this->_ar->save();
    if (!empty($staffData['dept_id']) && !empty($staffData['position_id'])) {
      $this->positionStart($staffData['dept_id'], $staffData['position_id'], $staffData['start_date']);
    }
  }

  public function saveExp($expData) {
    $expData = is_array($expData) ? $expData : CJSON::decode($expData);
    foreach ($expData as $exp) {
      if (empty($exp['id'])) {
        $expAr = new HrStaffExpAR();
      } else {
        $expAr = HrStaffExpAR::model()->findByPk($exp['id']);
      }
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

  public function positionStart($deptId, $positionId, $startDate='', $transferId=0) {
    $staffPosition= new HrStaffPositionAR();
    $staffPosition->staff_id = $this->_ar->id;
    $staffPosition->dept_id = $deptId;
    $staffPosition->position_id = $positionId;
    $staffPosition->transfer_id = $transferId;
    $staffPosition->start_date = empty($startDate) ? date('Y-m-d') : $startDate;
    if ($staffPosition->start_date <= date('Y-m-d')) {
      $this->_ar->dept_id = $deptId;
      $this->_ar->position_id = $positionId;
      $this->_ar->start_date = $staffPosition->start_date;
      $this->_ar->save();
      $staffPosition->status = 1;
    }
    $staffPosition->save();
  }

  public function resign($resignForm) {
    $resignForm = is_array($resignForm) ? $resignForm : CJSON::decode($resignForm);
    $resignAr = new HrResignAR();
    $resignAr->staff_id = $this->_ar->id;
    foreach($resignForm['resign'] as $key => $value) {
      $resignAr->$key = $value;
    }
    $resignAr->create_time = date('YmdHis');
    $resignAr->save();

    $resignId = $resignAr->id;
    foreach($resignForm['resignSurvey'] as $survey) {
      $surveyAr = new HrResignSurveyAR();
      $surveyAr->resign_id = $resignId;
      $surveyAr->create_time = date('YmdHis');
      foreach ($survey as $key => $value) {
        $surveyAr->$key = $value;
      }
      $surveyAr->save();
    }
    $this->_ar->status = 1;
    $this->_ar->save();
  }
}