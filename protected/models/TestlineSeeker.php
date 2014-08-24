<?php
Yii::import('application.models.AR.CarAR');
Yii::import('application.models.AR.OrderConfigAR');
Yii::import('application.models.User');

class Testlineseeker
{
	public function __construct(){
	}

	public function queryFromTable($item, $stime, $etime, $series, $curPage=0, $perPage=0) {
		if(empty($stime) || empty($etime)){
			throw new Exception('查询起止时间不可为空');
		}
		$view = "view_" . $item;
		$dateCol = $item . "_CheckDate";

		$condition = "$dateCol>='$stime' AND $dateCol<='$etime'";

		if(!empty($series)){
	        $arraySeries = Series::parseSeries($series);
	        $cTmp = array(); 
	        foreach($arraySeries as $series){
	        	$cTmp[] = "series='$series'";
	        }
	        $condition .= " AND (" . join(' OR ', $cTmp) . ")";
        };

		$limit = "";
        if(!empty($perPage)) {
            $offset = ($curPage - 1) * $perPage;
            $limit = "LIMIT $offset, $perPage";
        }
        

		$sql = "SELECT * FROM $view WHERE $condition $limit";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();

		$countSql = "SELECT count(*) FROM $view WHERE $condition";
		$total = Yii::app()->db->createCommand($countSql)->queryScalar();

		return array($total, $datas);
	}

	private function reviseSETime($stime,$etime) {
		//cancel the revise function
		return array($stime, $etime);

		$s = strtotime($stime);
		$e = strtotime($etime);
	
		$sd = date('Ymd', $s);
		$ed = date('Ymd', $e);
		
		$sm = date('m', $s);
		$em = date('m', $e);

		$lastHour = ($e - $s) / 3600;
		$lastDay = (strtotime($ed) - strtotime($sd)) / 86400;//days

		$ret = array();
		if($lastHour <= 24) {//hour
			$format = 'Y-m-d H';
			$stime = date($format, $s) . ":00:00";
			$eNextH = strtotime('+1 hour', $e);
			$etime = date($format, $eNextH) . ":00:00";
		} elseif($lastDay <= 31) {//day
			$format = 'Y-m-d';
			//$stime = date($format, $s) . " 00:00:00";				
			//$etime = date($format, $e) . " 23:59:59";
			$stime = date($format, $s) . " 08:00:00";								//added by wujun
			$eNextD = strtotime('+1 day', $e);		//next day						//added by wujun
			$etime = date($format, $eNextD) . " 07:59:59";	//befor next workday	//added by wujun
		} else {//month
			$format = 'Y-m';
			//$stime = date($format, $s) . "-01 00:00:00";
			//$etime = date('Y-m-t', $e) . " 23:59:59";
			$stime = date($format, $s) . "-01 08:00:00";	//firstday				//added by wujun
			$eNextM = strtotime('+1 month', $e);			//next month			//added by wujun
			$etime = date('Y-m', $eNextM) . "-01 07:59:59";	//next month firstday	//added by wujun
		}


		return array($stime, $etime);
	}

	// private function parseSeries($series) {
	// 	if(empty($series) || $series === 'all') {
 //            $series = array('F0', 'M6', '6B');
 //        } else {
 //            $series = explode(',', $series);
 //        }
	// 	return $series;
	// }
}
