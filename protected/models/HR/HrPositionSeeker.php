<?php
Yii::import('application.models.AR.HR.HrPositionAR');
Yii::import('application.models.AR.HR.HrGradeAR');
class HrPositionSeeker
{
    public function __construct () {

    }

    public function getGradeList () {
        $sql = "SELECT *
                FROM hr_grade
                ORDER BY
                    channel, level ASC";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();

        return $datas;
    }

    public function getPositionList ($channel='', $level=0, $all = false) {
        $conditions = array();
        if(!empty($channel)) {
            $conditions[] = "channel = '$channel'";
        }
        if(!empty($level)) {
            $conditions[] = "level=$level";
        }
        if(!$all) {
            $conditions[] = "removed = 0";
        }
        if(!empty($conditions)){
            $condition = "WHERE ". join(" AND ", $conditions);
        }
        $sql = "SELECT *
                FROM view_hr_position
                $condition
                ORDER BY channel, level ASC";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();

        return $datas;
    }

    public function getPositionDetail ($positionId) {
        $sql = "SELECT * FROM view_hr_position WHERE id=$positionId";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return $data;
    }
}