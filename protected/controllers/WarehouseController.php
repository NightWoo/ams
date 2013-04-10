<?php
Yii::import('application.models.Warehouse');
Yii::import('application.models.WarehouseSeeker');
Yii::import('application.models.CarSeeker');
Yii::import('application.models.AR.WarehouseAR');

class WarehouseController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionCheckinDetail(){
		$startTime = $this->validateStringVal('startTime', '');
		$endTime = $this->validateStringVal('endTime', '');
		$series = $this->validateStringVal('series', '');
        $curPage = $this->validateIntVal('curPage', 1);
		$perPage = $this->validateIntVal('perPage', 20);
		try{
			$seeker = new CarSeeker();
			list($total, $datas) = $seeker->queryCheckinDetail($startTime, $endTime, $series, $curPage, $perPage);
			$ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'data' => $datas,
                    );

			$this->renderJsonBms(true, 'OK', $ret);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function actionExportCheckinDetail() {
		$startTime = $this->validateStringVal('startTime', '');
		$endTime = $this->validateStringVal('endTime', '');
		$series = $this->validateStringVal('series', '');
		try{
			$seeker = new CarSeeker();
			list($total, $datas) = $seeker->queryCheckinDetail($startTime, $endTime, $series);
			$content = "car_ID,库道,流水号,VIN,车系,车型,配置,耐寒性,颜色,发动机号,入库时间,备注,特殊订单号,线别,下线时间\n";
            foreach($datas as $data) {
                $content .= "{$data['car_id']},";
                $content .= "{$data['row']},";
                $content .= "{$data['serial_number']},";
                $content .= "{$data['vin']},";
                $content .= "{$data['series']},";
				$data['type'] = str_replace(",", "，",$data['type']);
                $content .= "{$data['type']},";
                $content .= "{$data['config_name']},";
                $content .= "{$data['cold']},";
                $content .= "{$data['color']},";
                $content .= "{$data['engine_code']},";
                $content .= "{$data['warehouse_time']},";
                $data['remark'] = str_replace(",", "，",$data['remark']);
                $data['remark'] = str_replace(PHP_EOL, '', $data['remark']);
                $content .= "{$data['remark']},";
                $content .= "{$data['special_order']},";
                $content .= "{$data['assembly_line']},";
                $content .= "{$data['finish_time']},";
                $content .= "\n";
            }
            $export = new Export('出库明细_' .date('YmdHi'), $content);
            $export->toCSV();
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}

	}

	public function actionCheckoutDetail(){
		$startTime = $this->validateStringVal('startTime', '');
		$endTime = $this->validateStringVal('endTime', '');
		$series = $this->validateStringVal('series', '');
		$curPage = $this->validateIntVal('curPage', 1);
		$perPage = $this->validateIntVal('perPage', 20);
		try{
			$seeker = new CarSeeker();
			list($total, $datas) = $seeker->queryCheckoutDetail($startTime, $endTime, $series, $curPage, $perPage);
			$ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'data' => $datas,
                    );

			$this->renderJsonBms(true, 'OK', $ret);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function actionExportCheckoutDetail() {
		$startTime = $this->validateStringVal('startTime', '');
		$endTime = $this->validateStringVal('endTime', '');
		$series = $this->validateStringVal('series', '');
		try{
			$seeker = new CarSeeker();
			list($total, $datas) = $seeker->queryCheckoutDetail($startTime, $endTime, $series);
			$content = "订单号,车道,经销商,VIN,车系,车型,配置,耐寒性,颜色,发动机号,入库时间,备注,特殊订单号\n";
            foreach($datas as $data) {
                $content .= "{$data['order_number']},";
                $content .= "{$data['lane']},";
                $content .= "{$data['distributor_name']},";
                $content .= "{$data['vin']},";
                $content .= "{$data['series']},";
				$data['type'] = str_replace(",", "，",$data['type']);
                $content .= "{$data['type']},";
                $content .= "{$data['config_name']},";
                $content .= "{$data['cold']},";
                $content .= "{$data['color']},";
                $content .= "{$data['engine_code']},";
                $content .= "{$data['distribute_time']},";
                $data['remark'] = str_replace(",", "，",$data['remark']);
                $data['remark'] = str_replace(PHP_EOL, '', $data['remark']);
                $content .= "{$data['remark']},";
                $content .= "{$data['special_order']},";
                $content .= "\n";
            }
            $export = new Export('出库明细_' .date('YmdHi'), $content);
            $export->toCSV();
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}

	}
}
