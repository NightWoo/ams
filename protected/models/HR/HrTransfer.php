<?php
Yii::import('application.models.AR.HR.HrTransferAR');
Yii::import('application.models.HR.HrStaff');

class HrTransfer {
  private $_ar;

  public function __construct($transferId=0) {
    if (empty($transferId)) {
      $this->_ar = new HrTransferAR();
    } else {
      $this->_ar = HrTransferAR::model()->findByPk($transferId);
    }
  }

  public static function createById($id=0) {
    $c = __class__;
    return new $c($id);
  }

  public function save($data) {
    foreach ($data as $key => $value) {
      $this->_ar->$key = $value;
    }
    $this->_ar->create_time = date('YmdHis');
    $this->_ar->save();
  }

  public function closeReject() {
    $this->_ar->status = -1;
    $this->_ar->save();
  }

  public function closeApproved() {
    $this->_ar->status = 1;
    $this->_ar->save();
    $staff = HrStaff::createById($this->_ar->staff_id);
    $staff->positionStart($this->_ar->apply_dept_id, $this->_ar->apply_position_id, $$this->_ar->transfer_date);
  }
}