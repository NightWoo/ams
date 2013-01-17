<?php
Yii::import('application.models.AR.PlanAR');
Yii::import('application.models.Car');
Yii::import('application.models.ConfigSeeker');
class PlanSeeker
{
	public function __construct() {
	}

	public function search($date, $series, $line, $exactly = true) {
        $datetime = strtotime($date);
        $date = date('Y-m-d',$datetime);

		if($exactly) {
			$condition = "plan_date=?";
		} else {
			$condition = "plan_date>=?";
		}
		$values = array($date);
		if(!empty($series)) {
			$condition .= " AND car_series=?";
			$values[] = $series;
		}
		if(!empty($line)) {
            $condition .= " AND assembly_line=?";
			$values[] = $line;
        }
        //modifed by wujun
        $plans = PlanAR::model()->findAll($condition . ' ORDER BY plan_date, priority asc', $values);

        $datas = array();
        $seeker = new ConfigSeeker();
        foreach ($plans as $plan) {
            $temp = $plan->getAttributes();
            $temp['config_name'] = $seeker->getName($temp['config_id']);
            $datas[] = $temp;
        }

        return $datas;
    }

	//added by wujun
	public function generateBatchNumber($planDate) {
		$date = strtotime($planDate);
		$year = date("Y", $date);
		$yearCode = CarYear::getYearCode($year);
		$monthDay = date("md", $date);
		
		$ret = $yearCode . $monthDay;
		
		$sql = "SELECT batch_number FROM plan_assembly WHERE batch_number LIKE '$ret%' ORDER BY batch_number DESC";
		$lastSerial = Yii::app()->db->createCommand($sql)->queryScalar();
		$lastKey = intval(substr($lastSerial, 5 , 3));
		
		$ret .= sprintf("%03d", (($lastKey + 1) % 1000));
		
		return $ret;
		
	}

	//added by wujun
	public function query($stime, $etime, $series, $line, $curPage, $perPage) {
		if(empty($stime) || empty($etime)){
			throw new Exception("起始时间和结束时间均不可为空", 1);
		} else {
			$s = strtotime($stime);
			$e = strtotime($etime);
			$sdate = date('Y-m-d', $s);
			$edate = date('Y-m-d', $e);
		}

		if($sdate > $edate) {
			throw new Exception("起始时间不能大于结束时间！", 1);
		} else {
			$condition = "plan_date>='$sdate' AND plan_date<='$edate'";
		}

		$values = array($sdate, $edate);

		$arraySeries = $this->parseSeries($series);

		if(!empty($line)) {
            $condition .= " AND assembly_line='$line'";
			$values[] = $line;
        }

        $sqls=array();
        foreach($arraySeries as $series) {
        	$sqls[] = "SELECT * FROM plan_assembly WHERE $condition AND car_series='$series'";
        }
        $sql = join(' UNION ', $sqls);

        $limit = $perPage;
		$offset = ($curPage - 1) * $perPage;

        $sql .= " ORDER BY plan_date, batch_number ASC LIMIT $offset, $limit";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();

        $countSql = "SELECT count(*) FROM plan_assembly WHERE $condition";
		$total = Yii::app()->db->createCommand($countSql)->queryScalar();

		$seeker = new ConfigSeeker();
		foreach($datas as &$data) {
			$data['config_name'] = $seeker->getName($data['config_id']);
		}

        return array($total,  $datas); 
	}

	private function parseSeries($series) {
		if(empty($series) || $series === 'all') {
            $series = array('F0', 'M6');
        } else {
            $series = explode(',', $series);
        }
		return $series;
	}
		
}
