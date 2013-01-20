<?php
Yii::import('application.models.MonitorSeeker');
class MonitorController extends BmsBaseController
{
	private static $title_map = array(
		'T' => '内饰',
		'C' => '底盘',
		'F' => '最终',
	);

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

		$vq1Balance = $seeker->queryBalanceCount('VQ1');
		$vq2Balance = $seeker->queryBalanceCount('VQ2');
		$vq3Balance = $seeker->queryBalanceCount('VQ3');

		$drrs = array(
            'VQ2_LEAK' => $seeker->queryQualified($stime, $etime, 'VQ2', 2),
            'VQ2_ROAD' => $seeker->queryQualified($stime, $etime, 'ROAD_TEST_FINISH', 2),
            'VQ3' => $seeker->queryQualified($stime, $etime, 'VQ3', 2),
            );

        $data = array(
            'line_speed' => $lineSpeed,
            'line_run_time' => intval($lineRunTime / 60),
            'line_urate' =>  $seeker->queryLineURate($stime, $etime),
            'pause_time' => $seeker->queryLinePauseDetail($stime, $etime),
			'balance' => array(
				'VQ1' => $vq1Balance,
				'VQ2' => $vq2Balance,
				'VQ3' => $vq3Balance,
				'warehourse_cars' => $seeker->queryWareHourseCars('成品库',null, null),
			),
			'pass_car' => $seeker->queryWareHoursePassCars($stime, $etime),
			'drr' => $drrs,
		);

        $this->renderJsonBms(true, 'OK', $data);
	}

	public function actionShowBalanceDetail() {
		$node = $this->validateStringVal('node', 'PBS');//production,quality,balance
        $seeker = new MonitorSeeker();

		$data = $seeker->queryBalanceDetail($node);

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
			'title' => '总装A线',
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
		$data['checkin'] = $seeker->queryFinishCars($stime, $etime, 'CHECK_IN') . " / $planCars";
		$data['checkout'] = $seeker->queryFinishCars($stime, $etime, 'CHECK_OUT') . " / -";
		$data['lineURate'] = $seeker->queryLineURate($stime, $etime);

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
