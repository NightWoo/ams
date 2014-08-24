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

    public function getHighlevel ($positionId) {
        $levelSql = "SELECT level FROM hr_grade WHERE id=$positionId";
        $level = Yii::app()->db->createCommand($levelSql)->queryScalar();
        if ($level>1) {
            $sql = "SELECT id, display_name
                    FROM view_hr_position
                    WHERE level<=$level AND id<>$positionId
                    ORDER BY level DESC";
            $datas = Yii::app()->db->createCommand($sql)->queryAll();
        }
        if (empty($datas)) {
            $datas = array();
        }
        return $datas;
    }

    public function getPositionDetail ($positionId) {
        $sql = "SELECT * FROM view_hr_position WHERE id=$positionId";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return $data;
    }

    public function gradePositionList () {
        $sql = "SELECT id, grade_name, display_name FROM view_hr_position WHERE removed=0 ORDER BY level ASC";
        $positions = Yii::app()->db->createCommand($sql)->queryAll();
        $datas = array();
        foreach ($positions as $position) {
            if (empty($datas[$position['grade_name']])) {
                $datas[$position['grade_name']] = array();
            }
            array_push($datas[$position['grade_name']], $position);
        }
        return $datas;
    }
}