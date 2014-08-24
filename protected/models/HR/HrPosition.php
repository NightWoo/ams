<?php
Yii::import('application.models.AR.HR.HrPositionAR');
Yii::import('application.models.AR.HR.HrGradeAR');
Yii::import('application.models.HR.HrPositionSeeker');

class HrPosition {
    private $_ar;
    public function __construct ($positionId = 0) {
        if(empty($positionId)) {
            $this->_ar = new HrPositionAR();
        } else {
            $this->_ar = HrPositionAR::model()->findByPk($positionId);
        }
    }

    public static function createById($id) {
        $c = __class__;
        return new $c($id);
    }

    public function __get ($attr) {
        return $this->{$attr};
    }

    public function save ($detail) {
        $detail = is_array($detail) ? $detail : CJSON::decode($detail);
        foreach($detail as $key => $value) {
            $this->_ar->$key = $value;
        }
        $this->_ar->save();
    }

    public function remove () {
        $this->_ar->removed = 1;
        $this->_ar->save();
    }

    public static function getPositionList ($channel='', $level=0) {
        $seeker = new HrPositionSeeker();
        $datas = $seeker->getPositionList($channel, $level);

        return $datas;
    }

    public static function getGradeList () {
        $seeker = new HrPositionSeeker;
        $datas = $seeker->getGradeList();
        $ret = array();
        foreach($datas as $data) {
            if(empty($data['channel'])) {
                $ret[$data['channel']] = array();
            }
            $ret[$data['channel']][] = array(
                'id'=>$data['id'],
                'grade_name'=>$data['grade_name'],
                'grade'=>$data['grade'],
                'level'=>$data['level']
            );
        }

        return $ret;
    }
}