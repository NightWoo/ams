<?php
Yii::import('application.models.AR.NodeAR');
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.LaneAR');
Yii::import('application.models.AR.WarehouseAR');

class PlanningDivisionReprotSeeker
{
    public function __construct () {}

    private static $COLD_RESISTANT = array('非耐寒','耐寒');

    public function queryOperationReport ($date) {
        list($stime, $etime) = $this->reviseYearlyTime($date);
        $seriesList = Series::getNameList();
        $countArray = array();

        $timeArray = $this->parseQueryTime($stime, $etime, 'yearly');


    }

    public function countCarByPoint ($stime,$etime,$point="assembly",$line="") {
        $point .= "_time";
        $sql = "SELECT series, COUNT(id) as `count` FROM car WHERE $point>='$stime' AND $point<'$etime'";
        if(!empty($line)){
            $sql .= " AND assembly_line='$line'";
        }
        $sql .= " GROUP BY series";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();

        $seriesArray = Series::getArray();
        $count = array();
        foreach($seriesArray as $series){
            $count[$series] = 0;
        }
        foreach($datas as $data){
            $count[$data['series']] = intval($data['count']);
        }

        return $count;
    }

    public function reviseDailyMonth($date) {
        $d = strtotime($date);
        $sMonth = date("Y-m-01 08:00:00", $d);
        $nextDay = strtotime('+1 day', $d);
        $eDate = date("Y-m-d 08:00:00", $nextDay);

        return array($sMonth, $eDate);
    }

    public function reviseMonthlyTime($date) {
        $d = strtotime($date);
        $nextM = strtotime('first day of next month', $d);
        $stime = date("Y-m-01 08:00:00", $d);
        $etime = date("Y-m-01 08:00:00", $nextM);

        return array($stime, $etime);
    }

    private function reviseYearlyTime ($date) {
        $d = strtotime($date);
        $nextY = strtotime('+1 year', $d);
        $stime = date("Y-01-01 08:00:00", $d);
        $etime = date("Y-01-01 08:00:00", $nextY);

        return array($stime, $etime);
    }

    public function parseQueryTime ($stime, $etime, $timespan) {
        $s = strtotime($stime);
        $e = strtotime($etime);

        $ret = array();

        switch($timespan) {
            case "monthly":
                $pointFormat = 'd';
                $format = 'Y-m-d H:i:s';
                $slice = 86400;
                break;
            case "yearly":
                $pointFormat = 'm';
                $format = 'Y-m-d H:i:s';
                break;
            default:
                $pointFormat = 'd';
                $format = 'Y-m-d H:i:s';
                $slice = 86400;
        }

        $t = $s;
        while($t<$e) {
            $point = date($pointFormat, $t);
            if($pointFormat === 'm') {
                $eNextM = strtotime('first day of next month', $t); //next month
                $ee = date('Y-m-d', $eNextM) . " 08:00:00"; //next month firstday
                $etmp = strtotime($ee); //next month firstday
            } else {
                $etmp = $t+$slice;
            }
            if($etmp>=$e){
                $etmp=$e;
            }

            $ret[] = array(
                'stime' => date($format, $t),
                'etime' => date($format, $etmp),
                'point' => $point,
            );
            $t = $etmp;
        }

        return $ret;
    }
}