<?php
Yii::import('application.models.AR.HR.HrStaffAR');
Yii::import('application.models.AR.HR.HrStaffExpAR');
Yii::import('application.models.HR.HrStaffSeeker');

class HrStaff {
  private $_ar;
  public function __construct ($staffId = 0) {
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

  public function __get ($attr) {
    return $this->{$attr};
  }

  public function save ($staffData) {
    $staffData = is_array($staffData) ? $staffData : CJSON::decode($staffData);
    if (empty($this->_ar->id)) {
      $staffData['create_time'] = date('YmdHis');
    }
    foreach($staffData as $key => $value) {
      $this->_ar->$key = $value;
    }
    $this->_ar->save();
  }

  public function saveExp ($expData) {
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

  public function remove () {
    $this->_ar->removed = 1;
    $this->_ar->save();
  }
}