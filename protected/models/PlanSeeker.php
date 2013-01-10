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
		
}
