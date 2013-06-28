<?php
Yii::import('application.models.ReportSeeker');

class ReportController extends  BmsBaseController
{

	public function actionDebug(){
		$seeker= new ReportSeeker();
		$count = $seeker->countCarByPoint("2013-05-01 08:00:00","2013-05-30 08:00:00", "assembly");
		print_r($count);
	}

	public function actionQueryManufactureDaily() {
		$date = $this->validateStringVal("date", "");
		if(empty($date)) $date = DateUtil::getCurDate();

		$seeker = new ReportSeeker();
		$data = $seeker->queryManufactureDaily($date);

		$this->renderJsonBms(true, 'OK', $data);
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

}