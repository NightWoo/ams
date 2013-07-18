<?php
Yii::import('application.models.ReportSeeker');
Yii::import('application.models.PlanSeeker');
Yii::import('application.models.AR.ManufactureCapacityDailyAR');

class ReportController extends BmsBaseController
{

	public function actionDebug(){
        $seeker= new ReportSeeker();
        $date = "2013-06-21";
        $timespan = "monthly";
        $ret = $seeker->queryPauseDetail($date);
		$this->renderJsonBms(true, 'OK', $ret);
	}

	public function actionQueryManufactureDaily() {
		$date = $this->validateStringVal("date", "");
		if(empty($date)) $date = DateUtil::getCurDate();
        try{
            $seeker = new ReportSeeker();
            $data = $seeker->queryManufactureDaily($date);

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
	}

	public function actionExportCars() {
		$date = $this->validateStringVal("date", "");
		$point = $this->validateStringVal("point", "assembly");
		$timespan = $this->validateStringVal("timespan", "daily");
		try{
	        $seeker = new ReportSeeker();
			$datas = $seeker->queryCarDetail($date, $point, $timespan);
            $content = "carID,线别,流水号,VIN,车系,车型,配置,耐寒性,颜色,发动机号,状态,上线时间,下线时间,入库时间,出库时间,备注,库位,订单号,经销商,发车道\n";
            foreach($datas as $data) {
                $content .= "{$data['car_id']},";
                $content .= "{$data['assembly_line']},";
                $content .= "{$data['serial_number']},";
                $content .= "{$data['vin']},";
                $content .= "{$data['series']},";
				$data['type'] = str_replace(",", "，",$data['type']);
                $content .= "{$data['type']},";
                $content .= "{$data['config_name']},";
                $content .= "{$data['cold']},";
                $content .= "{$data['color']},";
                $content .= "{$data['engine_code']},";
                $content .= "{$data['status']},";
                $content .= "{$data['assembly_time']},";
                $content .= "{$data['finish_time']},";
                $content .= "{$data['warehouse_time']},";
                $content .= "{$data['distribute_time']},";
                $data['remark'] = str_replace(",", "，",$data['remark']);
                $data['remark'] = str_replace(PHP_EOL, '', $data['remark']);
                $content .= "{$data['remark']},";
                $content .= "{$data['row']},";
                $content .= "{$data['order_number']},";
                $content .= "{$data['distributor_name']},";
                $content .= "{$data['lane']},";
                $content .= "\n";
            }
            $export = new Export($point . '_detail_'. $timespan. "_" .date('YmdHi'), $content);
            $export->toCSV();
        } catch(Exception $e) {
            echo $e->getMessage();
        }
	}

    public function actionQueryCompletion() {
        $date = $this->validateStringVal("date", "");
        $timespan = $this->validateStringVal("timespan", "monthly");
        try{
            $seeker = new ReportSeeker();
            $data = $seeker->queryCompletion($date, $timespan);

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionQueryUse() {
        $date = $this->validateStringVal("date", "");
        $timespan = $this->validateStringVal("timespan", "monthly");
        try{
            $seeker = new ReportSeeker();
            $data = $seeker->queryManufactureUse($date, $timespan);

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionQueryRecycleChart() {
        $date = $this->validateStringVal("date", "");
        $timespan = $this->validateStringVal("timespan", "monthly");
        try{
            $seeker = new ReportSeeker();
            $data = $seeker->queryRecycleChart($date, $timespan);

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionQueryWarehouseChart() {
        $date = $this->validateStringVal("date", "");
        $timespan = $this->validateStringVal("timespan", "monthly");
        if(empty($date)) $date = DateUtil::getCurDate();
        try{
            $seeker = new ReportSeeker();
            $data = $seeker->queryWarehouseChart($date, $timespan);

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionQueryOvertimeOrders() {
        try{
            $seeker = new ReportSeeker();
            $data = $seeker->queryOvertimeOrders();
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }


    public function actionQueryOvertimeCars() {
        try{
            $seeker = new ReportSeeker();
            $data = $seeker->queryOvertimeCars();
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionUpdateCapacityMonthly() {
        try{
            $date = $this->validateStringVal("date", "");
            $seeker = new ReportSeeker();
            list($stime, $etime) = $seeker->reviseMonthlyTime($date);
            $timespan = "monthly";
            $timeArray = $seeker->parseQueryTime($stime, $etime, $timespan);
            foreach($timeArray as $queryTime){
                $use = $seeker->queryUseRateBase($queryTime['stime'], $queryTime['etime']);
                $workDate = date("Y-m-d", strtotime($queryTime['stime']));
                $capacityAR = ManufactureCapacityDailyAR::model()->find("work_date='$workDate'");
                if(empty($capacityAR)) $capacityAR = new ManufactureCapacityDailyAR();
                $capacityAR->work_date = $workDate;
                $capacityAR->capacity = $use['capacity'];
                $capacityAR->run_time = $use['runTime'];
                $capacityAR->save();
            }

            $this->renderJsonBms(true, 'OK', $timeArray);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }
}