<?php
class DateUtil
{
	//“当天”的有效时间是指“当天上午08:00至次日上午07：59分”
	public static function getCurDate() {
		$curTime = date("Y-m-d H:i:s");
        $curDate = date("Y-m-d");

        $beginTime = $curDate . " 08:00:00";
        if($curTime < $beginTime) {//before 8:00:00, change to last date
            $curDate = date("Y-m-d", time() - 86400);
        }

        return $curDate;
	}

	public static function getLastDate() {
        $curDate = self::getCurDate();
		$lastDate = date("Y-m-d", strtotime($curDate) - 86400);
        return $lastDate;
    }
}
