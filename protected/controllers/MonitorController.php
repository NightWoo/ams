<?php
Yii::import('application.models.MonitorSeeker');
Yii::import('application.models.SeriesSeeker');

class MonitorController extends BmsBaseController
{
	private static $title_map = array(
		'T' => '内饰',
		'C' => '底盘',
		'F' => '最终',
	);

	// private static $SERIES_NAME = array('F0'=>'F0','M6'=>'M6','6B'=>'思锐');

	public function actionDebug() {
		$dpu1 = "20.33%";
		$dpu2 = "30.2%";
		echo $dpu1 + $dpu2;
	}

	public function actionShowInfo() {
		$seeker = new MonitorSeeker();

		list($stime, $etime) = $this->getSETime();

		$dpus = array(
			'VQ1' => $seeker->queryDPU($stime, $etime, 'VQ1'),
			'VQ2' => $seeker->queryDPU($stime, $etime, 'VQ2_ALL'),
			'VQ3' => $seeker->queryDPU($stime, $etime, 'VQ3'),
			);
		$dpus['total'] = '-';
		foreach($dpus as $dpu) {
			if($dpu !== '-') {
				$dpus['total'] += $dpu;
			}
		}
		$drrs = array(
			'VQ1' => $seeker->queryQualified($stime, $etime, 'VQ1'),
            'VQ2' => $seeker->queryQualified($stime, $etime, 'VQ2_ALL'),
            'VQ3' => $seeker->queryQualified($stime, $etime, 'VQ3'),
			);
		$drrs['total'] = '-';
        foreach($drrs as $drr) {
            if($drr !== '-') {
				if($drrs['total'] === '-') {
					$drrs['total'] = 100;	//modified wujun
				}
                $drrs['total'] *= $drr;
				$drrs['total'] /= 100;
				$drrs['total'] = round($drrs['total'], 2);
                //$drrs['total'] .= "%";	//modified wujun
            }
        }
        $drrs['total'] .= "%";	//modified wujun


		$balances = array(
			'VQ1' => $seeker->queryBalanceCount('VQ1'),
			'VQ2' => $seeker->queryBalanceCount('VQ2'),
			'VQ3' => $seeker->queryBalanceCount('VQ3'),
		);

		$lineRunTime = $seeker->queryLineRunTime($stime, $etime);
        $lineSpeed = $seeker->queryLineSpeed();
        $data = array(
            'line_speed' => $lineSpeed,
            'line_run_time' => intval($lineRunTime / 60),
            'line_urate' =>  $seeker->queryLineURate($stime, $etime),
            'pause_time' => $seeker->queryLinePauseDetail($stime, $etime),
			'DPU' => $dpus,
			'DRR' => $drrs,
			'balance' => $balances,
			'pause_seat' => $seeker->queryPauseSeat($stime, $etime),
		);

        $this->renderJsonBms(true, 'OK', $data);
	}

	public function actionShowLabel() {
		$type = $this->validateStringVal('type', '');//production,quality,balance
		$seeker = new MonitorSeeker();

		list($stime, $etime) = $this->getSETime();

        $data = array(
			'type' => $type,
			'list' => $seeker->queryLabel($type,$stime, $etime),
        );

        $this->renderJsonBms(true, 'OK', $data);
	}

	public function actionShowProductInfo() {
		$seeker = new MonitorSeeker();

		list($stime, $etime) = $this->getSETime();

		$lineRunTime = $seeker->queryLineRunTime($stime, $etime);
        $lineSpeed = $seeker->queryLineSpeed();

		$nodes = array('VQ1' => 'VQ1', 'VQ2_LEAK'=> 'VQ2', 'VQ2_ROAD' => 'ROAD_TEST_FINISH', 'VQ3' => 'VQ3');
		$seriesArray = SeriesSeeker::findAllCode();
		$seriesArray[] = 'all';
		$drrs = array();
		foreach($seriesArray as $series) {
			foreach($nodes as $key => $node) {
				$drrs[$key][$series] = $seeker->queryQualified($stime, $etime, $node, $series, 2);
			}
		}

		$balance = array();

		$nodes = array('VQ1' => 'VQ1', 'VQ2'=> 'VQ2', 'VQ3' => 'VQ3');
		foreach($seriesArray as $series) {
			foreach($nodes as $key => $node) {
				$balance[$key][$series] = $seeker->queryBalanceCount($node, $series);
			}
		}

		$startTime = DateUtil::getCurDate() . " 08:00:00";
		$endTime = date('Y-m-d H:i:s');

		$wareHourseCar = array();
		foreach($seriesArray as $series) {
			$wareHourseCar[$series] = $seeker->queryWareHourseCars('成品库', $series, null, null);
		}

		$blockRates = $seeker->queryBlockRate();
		$blockQuantity = $seeker->queryBlockQuantity();
		$capacityRate =$seeker->queryCapacityRate();
		$period = $seeker->queryPeriod();

        $data = array(
            'line_speed' => $lineSpeed,
            'line_run_time' => intval($lineRunTime / 60),
            'line_urate' =>  $seeker->queryLineURate($stime, $etime),
            'pause_time' => $seeker->queryLinePauseDetail($stime, $etime),
			'balance' => $balance,
			'pass_car' => $seeker->queryWareHousePassCars($stime, $etime),
			'warehourse_cars' => $wareHourseCar,
			'drr' => $drrs,
			'block_rate' => $blockRates,
			'block_quantity' => $blockQuantity,
			'capacity_rate' => $capacityRate,
			'period' => $period,
		);

        $this->renderJsonBms(true, 'OK', $data);
	}

	public function actionShowBalanceDetail() {
		$node = $this->validateStringVal('node', 'PBS');//production,quality,balance
        $seeker = new MonitorSeeker();

		$data = $seeker->queryBalanceDetail($node);

        $this->renderJsonBms(true, 'OK', $data);
	}

	public function actionShowWarehouseAreaBalance() {
        $block = $this->validateStringVal('block', 'A');
		$block = strtoupper($block);
        $seeker = new MonitorSeeker();

        $data = $seeker->queryWarehouseBlockBalance($block);

        $this->renderJsonBms(true, 'OK', $data);
    }

    public function actionShowLaneInfo() {
        $seeker = new MonitorSeeker();
        $data = $seeker->queryLaneInfo();
        $this->renderJsonBms(true, 'OK', $data);
    }


	public function actionShowWarehouseBlockBalance() {
        $block = $this->validateStringVal('block', 'A01');
        $seeker = new MonitorSeeker();

        $data = $seeker->queryWarehouseBlockBalance($block);

        $this->renderJsonBms(true, 'OK', $data);
    }

	public function actionShowWarehouseBalanceDetail() {
        $suffix = $this->validateStringVal('row', 'A011');
        $type = $this->validateStringVal('type', 'row');
        $seeker = new MonitorSeeker();

        $data = $seeker->queryWarehouseBalanceDetail($suffix, $type);

        $this->renderJsonBms(true, 'OK', $data);
    }

	public function actionQuerySection() {
		$section = $this->validateStringVal('section','');
        $seeker = new MonitorSeeker();
        $data = array(
            'title' => self::$title_map[substr($section, 0, 1)] . substr($section, 1),
            'seat_list' => $seeker->querySeats($section),
            'active_section' => $section,
        );

		$this->renderJsonBms(true, 'OK', $data);
	}

	public function actionShowSectionPanel() {
		$section = $this->validateStringVal('section','');

		$seeker = new MonitorSeeker();


		list($stime, $etime) = $this->getSETime();

		list($planCars, $finishCars) = $seeker->queryPlan($section, $stime);
		$linePT = $seeker->queryLinePauseTime('', $stime, $etime, 'without_plan_to_pause');
		$sectionPT = $seeker->queryLinePauseTime($section, $stime, $etime);
		$lineSpeed = $seeker->queryLineSpeed();
		$lineStatus = $seeker->queryLineStatus($stime, $etime);
		$data = array(
			'dpu' => $seeker->queryDPU($stime, date('Y-m-d H:i:s')),
            'qrate' => $seeker->queryQualified($stime, date('Y-m-d H:i:s')),
			'other_section_calls' => $seeker->queryOtherCall($section, $stime),
			'cur_time' => date('g : i A'),
			'line_speed' => $lineSpeed,
			'line_status' => $lineStatus,
			'section_pause_time' => intval($sectionPT / 60),
			'line_pause_time' => intval($linePT / 60),
			'line_urate' =>  $seeker->queryLineURate($stime, $etime),
			'finish_cars' => $finishCars,
			'plan_cars' => $planCars,
		);

		$this->renderJsonBms(true, 'OK', $data);

	}


	public function actionShowShopPanel() {
        $seeker = new MonitorSeeker();

		list($stime, $etime) = $this->getSETime();

        list($planCars, $finishCars) = $seeker->queryPlan('', $stime);
        $linePT = $seeker->queryLinePauseTime('', $stime, $etime, 'without_plan_to_pause');
		$lineSpeed = $seeker->queryLineSpeed();
		$lineStatus = $seeker->queryLineStatus($stime, $etime);
        $data = array(
			'title' => '总装I线',
            'dpu' => $seeker->queryDPU($stime, date('Y-m-d H:i:s')),
			'qrate' => $seeker->queryQualified($stime, date('Y-m-d H:i:s')),
            'cur_time' => date('g : i A'),
            'line_speed' => $lineSpeed,
			'line_status' => $lineStatus,
            'section_pause_time' => 0,
            'line_pause_time' => intval($linePT / 60),
            'line_urate' =>  $seeker->queryLineURate($stime, $etime),
            'finish_cars' => $finishCars,
            'plan_cars' => $planCars,
        );
		$this->renderJsonBms(true, 'OK', $data);
	}

	public function actionShowSectionStatus() {
		$section = $this->validateStringVal('section','');

        $seeker = new MonitorSeeker();

		list($stime, $etime) = $this->getSETime();

		$data = $seeker->queryCallStatus($section, $stime);
		$this->renderJsonBms(true, 'OK',  $data);
	}

	//added by wujun
	public function actionShowHomeEfficiency () {
		$date = DateUtil::getCurDate();
		list($stime, $etime) = $this->getSETime();
		$seeker = new MonitorSeeker();
		$data = array();
		$planCars = $seeker->queryPlanCars($date);
		$data['onLine'] = $seeker->queryFinishCars($stime, $etime, 'T0') . " / $planCars";

		$wareHousePass = $seeker->queryWareHousePassCars($stime, $etime);
		$data['checkin'] = $wareHousePass['warehourse_in']['all'] . " / $planCars";
		$standbyPlan = $seeker->queryStandbyPlan($date);
		$standbyPlan = empty($standbyPlan) ? 0 : $standbyPlan;
		$data['checkout'] = $wareHousePass['warehourse_out']['all'] . " / $standbyPlan";
		$data['lineURate'] = $seeker->queryLineURate($stime, $etime);

		$this->renderJsonBms(true, 'OK', $data);
	}

	public function actionShowHomecost () {
		$date = DateUtil::getCurDate();
		list($stime, $etime) = $this->getSETime();
		$seeker = new SparesSeeker();
		$data = array();
		$seriesList = Series::getNameList();
		foreach($seriesList as $series=>$seriesName) {
			$unitCost = $seeker->queryUnitCost($stime, $etime, $series, 'I');
			$data[$series] = sprintf("%.2f", $unitCost);
		}
		$unitCostTotal = $seeker->queryUnitCost($stime, $etime, '', 'I');
		$data['total'] = sprintf("%.2f", $unitCostTotal);

		$this->renderJsonBms(true, 'OK', $data);
	}


	private function getSETime() {
		$etime = date('Y-m-d H:i:s');

		$date = DateUtil::getCurDate();				//modified by wujun
		//$stime = date('Y-m-d') . " 08:00:00";		//modified by wujun
		$stime = $date . " 08:00:00";				//modified by wujun
		if($stime > $etime) {
			$stime = date('Y-m-d', time() - 86400) . " 08:00:00";
		}

		return array($stime, $etime);
	}
}
