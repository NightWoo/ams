<?php
Yii::import('application.models.AR.*');
Yii::import('application.models.*');

class DebugController extends BmsBaseController
{
	public function actionIndex () {
		$this->render('test');
	}

	public function actionTest () {
		$vin = $this->validateStringVal('vin', '');
		$material = $this->validateStringVal('material', '');
		try {
			$ret = $this->updateOrderView();
			$this->renderJsonBms(true, 'OK', $ret);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

    public function updateOrderView () {
        $sql = "SELECT order_id FROM sell_order_view WHERE audit_conclusion=0";
        $ids = Yii::app()->db->createCommand($sql)->queryColumn();
        if(!empty($ids)) {
            $idCondition = "(" . join(",", $ids) .")";
            $mssql = "SELECT TOP 10000
            xswl AS distribution_network,
            yxbmc AS sales_department,
            ssdq AS sales_region,
            ssqy AS sales_area,
            sssf AS sales_province,
            sscs AS sales_city,
            sccs AS deliver_city,
            dgdh AS order_number,
            dgxz AS order_nature,
            ddxz AS cold_resistant,
            fhdwmc AS delivery_unit,
            dgdwbh AS distributor_code,
            dgdw AS distributor_name,
            cldm AS car_type_code,
            cxmc AS series_name,
            clxh AS sell_car_type,
            cx AS sell_config_name,
            VINxtcx AS car_type,
            clys AS sell_color,
            xzpz AS options,
            dgsl AS amount,
            yfsl AS delivered_count,
            ydsl AS arrived_count,
            wdsl AS not_arrived_count,
            qxsl AS canceled_count,
            qxyy AS cancel_reason,
            convert(varchar(30),[dgrq],120) AS book_time,
            jhzt AS order_plan_status,
            convert(varchar(30),[jhrq],120) AS order_plan_date,
            cwshjg AS audit_status,
            cwshyj AS audit_comment,
            convert(varchar(30),[cwshrq],120) AS audit_time,
            shbz AS audit_flag,
            shjg AS audit_conclusion,
            ID AS order_id,
            jzpz AS additions,
            mxbz AS remark
            FROM AMS_ORDERVIEW
            WHERE ID IN $idCondition";
            $datas = $this->sellMSSQL($mssql);
            foreach($datas as $data) {
                $ar = SellStockViewAR::model()->find("order_id", array($data['order_id']));
                foreach($data as $key => $value) {
                    if($key == "order_id") {
                        continue;
                    }
                    if($key == "series_name") {
                        $ar->series = $seriesCodeList[$value];
                    } else {
                        $ar->$key = $value;
                    }
                }
                $ar->save();
            }
        }
    }

	public function actionGetOrderView () {
		$series = $this->validateStringVal('series', '');
		try {
			$seriesNameList = Series::getNameList();
			foreach($seriesNameList as $series => $seriesName) {
				$sellTable = new SellTable();
				$sellTable->getOrderView($series);
			}

			$this->renderJsonBms(true, 'OK', 'got it');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function actionGetSaleView () {
		$series = $this->validateStringVal('series', '');
		try {
			$seriesNameList = Series::getNameList();
			foreach($seriesNameList as $series => $seriesName) {
				$sellTable = new SellTable();
				$sellTable->getSaleView($series);
			}

			$this->renderJsonBms(true, 'OK', 'got it');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function actionGetShipView () {
		$series = $this->validateStringVal('series', '');
		try {
			$seriesNameList = Series::getNameList();
			foreach($seriesNameList as $series => $seriesName) {
				$sellTable = new SellTable();
				$sellTable->getShipView($series);
			}

			$this->renderJsonBms(true, 'OK', 'got it');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function actionStockView () {
		$series = $this->validateStringVal('series', '');
		try {
			$seriesNameList = Series::getNameList();
			foreach($seriesNameList as $series => $seriesName) {
				$sellTable = new SellTable();
				$sellTable->getStockView($series);
			}

			$this->renderJsonBms(true, 'OK', 'got it');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	private function getSellTableDatas ($series) {
		$sellTable = new SellTable();
		$datas = array();
		$datas[] = $sellTable->getOrderView($series);
		$datas[] = $sellTable->getSaleView($series);
		$datas[] = $sellTable->getShipView($series);
		$datas[] = $sellTable->getStockView($series);
		return $datas;
	}

	public function createInSap ($material, $quantity, $startDate, $endDate="", $prodVersion="", $plant="C113", $type="QC01") {
		try {
			$client = @new SoapClient(Yii::app()->params['saprfc']);
			$params = array(
				"material"=>$material,
				"quantity"=>$quantity,
				"startDate"=>$startDate,
				"endDate"=>$endDate,
				"prodVersion"=>$prodVersion,
				"plant"=>$plant,
				"orderType"=>$type
			);
			$result = $client->createPlan($params);
			$ret = (array)$result->createPlanResult;
			return $ret['string'];
		} catch(Exception $e) {
			$ret = array("fail", "E", "createInSap fail", "");
		}
	}

	public function actionTestCRM () {
		$orderNumber = $this->validateStringVal('orderNumber', '');
		try {
			$test = new Test();
			$ret['xf'] = $test->getOriginalOrders($orderNumber, "XF");
			$ret['crm'] = $test->getOriginalOrders($orderNumber, "CRM");
			$this->renderJsonBms(true, 'Ok', $ret);
		} catch(Exception $e) {
			$this->renderJsonBms(true, $e->getMessage(), null);
		}
	}

	public function actionCheckAndSend () {
		$sql = "SELECT * FROM view_pause_sms WHERE closed=0";
		$unclosed = Yii::app()->db->createCommand($sql)->queryAll();
		if(!empty($unclosed)){
			foreach($unclosed as $pause) {
				if(0 == $pause['status']) {
					$this->sendClosed($pause);
				} else if (1 == $pause['status']) {
					$this->sendLevel($pause);
				}
			}
		}
	}

	public function sendClosed ($pause) {
		$level = $this->checkLevel($pause);
		$phoneNumbers = $this->getPhoneNumbers($pause, $level, true);
		$content = $this->makeContent($pause);
		if(!empty($phoneNumbers)){
			$SmsService = new SmsService();
			$SmsService->send($content, $phoneNumbers);
		}
		$pauseSms = PauseSmsAR::model()->findByPk($pause['id']);
		$pauseSms->closed = 1;
		$pauseSms->update(array("closed"));
	}

	public function sendLevel ($pause) {
		$level = $this->checkLevel($pause);
		for($i=0;$i<=$level;$i++) {
			$underLevel = "level_" . $i;
			if($pause[$underLevel] == 0) {
				$phoneNumbers = $this->getPhoneNumbers($pause, $i, false);
				$content = $this->makeContent($pause);
				if(!empty($phoneNumbers)){
					$SmsService = new SmsService();
					$SmsService->send($content, $phoneNumbers);
				}
				$pauseSms = PauseSmsAR::model()->findByPk($pause['id']);
				$pauseSms->$underLevel = 1;
				$pauseSms->update(array($underLevel));
			}
		}
	}

	public function checkLevel ($pause) {
		$end = $pause['recover_time'] == "0000-00-00 00:00:00" ? date("Y-m-d H:i:s") : $pause['recover_time'];
		$minutes = (strtotime($end) - strtotime($pause['pause_time']))/60;
		$level = 0;
		if(!empty($pause['duty_department'])) {
			switch ($minutes) {
				case ($minutes<10) :
					$level = 1;
					break;
				case ($minutes<30) :
					$level = 2;
					break;
				case ($minutes<60) :
					$level = 3;
					break;
				default :
					$level = 4;
			}
		}
		return $level;
	}

	public function getPhoneNumbers($pause, $level, $underLevel = false) {
		$levelCon = $underLevel ? "`level`<=$level" : "`level`=$level";
		$sql = "SELECT DISTINCT(cellphone) FROM view_pause_receiver WHERE `section`='{$pause['section']}' AND $levelCon";
		if(!empty($level)) $sql .= " AND `duty_group`='{$pause['duty_group']}'";
		$numberArray = Yii::app()->db->createCommand($sql)->queryColumn();
		$numberText = join(",", $numberArray);
		return $numberText;
	}

	public function makeContent ($pause) {
		$end = $pause['recover_time'] == "0000-00-00 00:00:00" ? date("Y-m-d H:i:s") : $pause['recover_time'];
		$minutes = floor((strtotime($end) - strtotime($pause['pause_time']))/60);

		$textHead = "【总装长沙停线通知】\n";
		$textStart = "开始: " . substr($pause['pause_time'], 5, 11) . "\n";
		$textEnd = $pause['status'] == 1 ? "停线中" : substr($pause['recover_time'], 5, 11) . "\n";
		$textEnd = "结束: " . $textStatus;
		$textMinutes = "时长: " . $minutes . "分钟\n";
		$textSection = "工位: " . $pause['node_display_name'] . "\n";
		$textDuty = "责任: " . $pause['duty_department'] . "\n";
		$textCause = "原因: " . $pause['remark'] . "\n";
		$textFoot = "【十一部AMS】";

		$content = "(" . $textHead . $textStart . $textEnd . $textMinutes . $textSection . $textDuty . $textCause . $textFoot .")";

		return $content;
	}
}