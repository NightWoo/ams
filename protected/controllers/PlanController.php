<?php
Yii::import('application.models.PlanSeeker');
Yii::import('application.models.Plan');
Yii::import('application.models.AR.PlanAR');
Yii::import('application.models.AR.CarConfigAR');

class PlanController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions ()
	{
		return array(
		);
	}

	public function actionSearch () {
		$curDate = DateUtil::getCurDate();
        $date = $this->validateStringVal('plan_date', $curDate);
		$series = $this->validateStringVal('car_series', '');
		$line = $this->validateStringVal('assembly_line', '');
        try{
            $plan = new PlanSeeker();
            $data = $plan->search($date, $series, $line);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }

	// public function actionSavePlan () {
	// 	$id = $this->validateIntVal('id', 0);
	// 	$date = $this->validateStringVal('plan_date', date('Y-m-d'));
	// 	$series = $this->validateStringVal('car_series', 'F0');
	// 	$total = $this->validateIntVal('total', 0);
	// 	$carType = $this->validateStringVal('car_type', '');
	// 	// $configName = $this->validateStringVal('config', '');
	// 	$configId = $this->validateIntVal('config', '');
	// 	$carBody = $this->validateStringVal('car_body', '');
	// 	$color = $this->validateStringVal('color', '');
	// 	$carYear = $this->validateStringVal('car_year', '');
	// 	$orderType = $this->validateStringVal('order_type', '');
	// 	$specialOrder = $this->validateStringVal('special_order', '');
	// 	$assemblyLine = $this->validateStringVal('assembly_line', '');
	// 	$coldResistant = $this->validateIntVal('cold_resistant', 0);
	// 	$remark = $this->validateStringVal('remark', '');
	// 	$specialProperty = $this->validateStringVal('specialProperty', '');
	// 	$isFrozen = $this->validateIntVal('isFrozen', 0);
	// 	//$batchNumber = $this->validateStringVal('batch_number', '');
	// 	$transaction = Yii::app()->db->beginTransaction();
	// 	try{
	// 		$config = CarConfigAR::model()->find('id=?', array($configId));
	// 		if(empty($config)) {
	// 			throw new Exception("配置 $config 不存在");
	// 		}
	// 		// $planObj = new Plan();
	// 		if(empty($id)) {
	// 			// $planNumber = $planObj->createInSap($material, $quantity, $startDate, $endDate="", $type="", $prodVersion="", $plant="");
	// 			$plan = new PlanAR();
	// 			//get the max priority for the date
	// 			$max = PlanAR::model()->find('plan_date=? order by priority desc', array($date));
	// 			$plan->priority = 0;
	// 			if(!empty($max)) {
	// 				$plan->priority = $max->priority + 1;
	// 			}
	// 		} else {
	// 			$plan = PlanAR::model()->findByPk($id);
	// 		}
						
	// 		$samePriority = PlanAR::model()->find('plan_date=? AND priority=? order by priority desc', array($date,$plan->priority));
	// 		if(!empty($samePriority) && $samePriority->id != $id){
	// 			$max = PlanAR::model()->find('plan_date=? order by priority desc', array($date));
	// 			$plan->priority = $max->priority + 1;
	// 		}

	// 		if(empty($plan->batch_number)){
	// 			$seeker = new PlanSeeker();
	// 			$batchNumber = $seeker->generateBatchNumber($date);
	// 			$plan->batch_number = $batchNumber;
	// 		}
			
	// 		$plan->plan_date = $date;
	// 		$plan->car_series = $series;
	// 		$plan->total = $total;
	// 		$plan->car_type = $carType;
	// 		$plan->color = $color;
	// 		$plan->car_year = $carYear;
	// 		$plan->order_type = $orderType;
	// 		$plan->config_id = $configId;
	// 		$plan->car_body = $carBody;
	// 		$plan->special_order = $specialOrder;
	// 		$plan->assembly_line = $assemblyLine;
	// 		$plan->cold_resistant = $coldResistant;
	// 		$plan->remark = $remark;
	// 		$plan->special_property = $specialProperty;
	// 		$plan->is_frozen = $isFrozen;
	// 		$plan->user_id = Yii::app()->user->id;
	// 		$plan->modify_time = date("YmdHis");
	// 		$plan->save();

	// 		$transaction->commit();
 //            $this->renderJsonBms(true, 'OK', '');
 //        } catch(Exception $e) {
 //        	$transaction->rollback();
 //            $this->renderJsonBms(false , $e->getMessage());
 //        }
	// }

	public function actionSave () {
		$id = $this->validateIntVal('id', 0);
		$data['plan_date'] = $this->validateStringVal('plan_date', date('Y-m-d'));
		$data['car_series'] = $this->validateStringVal('car_series', 'F0');
		$data['total'] = $this->validateIntVal('total', 0);
		$data['car_type'] = $this->validateStringVal('car_type', '');
		$data['config_id'] = $this->validateIntVal('config', '');
		$data['car_body'] = $this->validateStringVal('car_body', '');
		$data['color'] = $this->validateStringVal('color', '');
		$data['car_year'] = $this->validateStringVal('car_year', '');
		$data['order_type'] = $this->validateStringVal('order_type', '');
		$data['special_order'] = $this->validateStringVal('special_order', '');
		$data['assembly_line'] = $this->validateStringVal('assembly_line', '');
		$data['cold_resistant'] = $this->validateIntVal('cold_resistant', 0);
		$data['remark'] = $this->validateStringVal('remark', '');
		$data['special_property'] = $this->validateStringVal('specialProperty', '');
		$data['is_frozen'] = $this->validateIntVal('isFrozen', 0);
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$plan = Plan::createById($id);
			if(empty($id)){
				$plan->generate($data);
			} else {
				$plan->modify($data);
			}
			$transaction->commit();
            $this->renderJsonBms(true, 'OK', '');
		} catch(Exception $e) {
			$transaction->rollback();
            $this->renderJsonBms(false , $e->getMessage());
		}
	}

	public function actionRemove () {
		$id = $this->validateIntVal('id', 0);
        try{
			$plan = PlanAR::model()->findByPk($id);
			if(!empty($plan)) {
				if($plan->ready>0) {
					throw new Exception("此计划已进行，无法删除！");
				}
				$lowers = PlanAR::model()->findAll('priority>? AND plan_date=?' ,array($plan->priority, $plan->plan_date));
                if(!empty($lowers)) {
                    foreach($lowers as $lower) {
                        $lower->priority = $lower->priority - 1;
                        $lower->save();
                    }
                }

				$plan->delete();
			}
            $this->renderJsonBms(true, 'OK', '');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

	}


	public function actionInc () {
		$id = $this->validateIntVal('id', 0);
        try{
            $plan = PlanAR::model()->findByPk($id);
            if(!empty($plan)) {
				//$higher = PlanAR::model()->find('priority=? AND plan_date=?' ,array($plan->priority - 1, $plan->plan_date));		
				$higher = PlanAR::model()->find('priority<? AND plan_date=? ORDER BY priority DESC', array($plan->priority, $plan->plan_date));		//modified by wujun
				if(!empty($higher)) {
					$plan->priority = $higher->priority;
					$higher->priority = $higher->priority + 1;
					
					$plan->save();
					$higher->save();
				}
            }
            $this->renderJsonBms(true, 'OK', '');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}
	
	public function actionTop () {
        $id = $this->validateIntVal('id', 0);
        try{
            $plan = PlanAR::model()->findByPk($id);
            if(!empty($plan)) {
                $highers = PlanAR::model()->findAll('priority<? AND plan_date=?' ,array($plan->priority, $plan->plan_date));
                if(!empty($highers)) {
                    $plan->priority = 0;
					foreach($highers as $higher) {
                    	$higher->priority = $higher->priority + 1;
						$higher->save();
                    }
                    $plan->save();
                }
            }
            $this->renderJsonBms(true, 'OK', '');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }

    //added by wujun
    public function actionReduce () {
		$id = $this->validateIntVal('id', 0);
        try{
            $plan = PlanAR::model()->findByPk($id);
            if(!empty($plan)) {
				//$higher = PlanAR::model()->find('priority=? AND plan_date=?' ,array($plan->priority - 1, $plan->plan_date));		
				$lower = PlanAR::model()->find('priority>? AND plan_date=? ORDER BY priority ASC', array($plan->priority, $plan->plan_date));		//modified by wujun
				if(!empty($lower)) {
					$plan->priority = $lower->priority;
					$lower->priority = $lower->priority - 1;
					
					$plan->save();
					$lower->save();
				}
            }
            $this->renderJsonBms(true, 'OK', '');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}
	
	//added by wujun
	public function actionGetYearCode ($year){
			$year = $this->validateStringVal('year', '');
			try{
				$yearCode=CarYear::getYearCode($year);
				$this->renderJsonBms(true, 'OK', $yearCode);
			}catch(Exception $e) {
				$this->renderJsonBms(false,$e->getMessage());
			}
	}
	
	//added by wujun
	public function actionGetBatchNumber () {
		$planDate = $this->validateStringVal('plan_date', '');
		
		try {
			$seeker = new PlanSeeker();
			$data = $seeker->generateBatchNumber($planDate);
			
			$this->renderJsonBms(true, 'OK', $data);
		}catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}	
	}

	//added by wujun
	public function actionQuery () {
		$stime = $this->validateStringVal('stime', '');
		$etime = $this->validateStringVal('etime', '');
		$series = $this->validateStringVal('series', '');
		$line = $this->validateStringVal('line', '');
		$perPage = $this->validateIntVal('perPage', 10);
		$curPage = $this->validateIntVal('curPage', 1);

		try {
			$seeker = new PlanSeeker();
			list($total, $data) = $seeker->query($stime, $etime, $series, $line, $curPage, $perPage);
			$ret = array(
				'pager' => array(
					'curPage' => $curPage,
					'perPage' => $perPage,
					'total' => $total,
				),
				'data' => $data,
			);

			$this->renderJsonBms(true, 'OK', $ret);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function actionExport () {
		$stime = $this->validateStringVal('stime', '');
		$etime = $this->validateStringVal('etime', '');
		$series = $this->validateStringVal('series', '');
		$line = $this->validateStringVal('line', '');

		try{
			$seeker = new PlanSeeker();
			list($total, $datas) = $seeker->query($stime, $etime, $series, $line, 0, 0);
			$content = "#,线别,批次号,计划日期,计划数量,完成数量,车系,车型,配置,耐寒性,颜色,年份,订单类型,特殊单号,备注\n";
			foreach($datas as $data) {
				$content .= "{$data['id']},";
				$content .= "{$data['assembly_line']},";
				$content .= "{$data['batch_number']},";
				$content .= "{$data['plan_date']},";
				$content .= "{$data['total']},";
				$content .= "{$data['ready']},";
				$content .= "{$data['car_series']},";
				$content .= "{$data['car_type_name']},";
				$data['config_name'] = str_replace(PHP_EOL, '', $data['config_name']);
				$data['config_name'] = str_replace(",", "，",$data['config_name']);
				$content .= "{$data['config_name']},";
				$content .= "{$data['cold']},";
				$content .= "{$data['color']},";
				$content .= "{$data['car_year']},";
				$content .= "{$data['order_type']},";
				$content .= "{$data['special_order']},";
				$data['remark'] = str_replace(PHP_EOL, '', $data['remark']);
				$data['remark'] = str_replace(",", "，",$data['remark']);
				$content .= "{$data['remark']}";
				$content .= "\n";
			}
			$export = new Export('计划明细_' . date('YmdHi'), $content);
			$export->toCSV();
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

	public function actionQueryCompletion () {
		$stime = $this->validateStringVal('stime', '');
		$etime = $this->validateStringVal('etime', '');
		$series = $this->validateStringVal('series', '');
		$line = $this->validateStringVal('line', 'I');
		try{
			$seeker = new PlanSeeker();
			$data = $seeker->queryCompletion($stime, $etime, $series, $line);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}
}
