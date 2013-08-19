<?php
Yii::import('application.models.AR.UserSmsAR');
Yii::import('application.models.CarSeeker');
Yii::import('application.models.ReportSeeker');
Yii::import('application.models.Sms.SmsService');

class SmsDailyCommand extends CConsoleCommand
{
	private static $SERIES = array(
		'F0' => 'F0',
		'M6' => 'M6',
		'6B' => '思锐',
	);

	private static $COUNT_POINT_DAILY = array(
		"assemblyCount" => "上线",
		"warehouseCount" => "入库",
		"distributeCount" => "出库",
		// "onlineBalance" => "在制",
		"recycleBalance" => "周转",
		// "warehouseBalance" => "库存",
	);

	public function actionProductionAfternoon () {
		$curDate = DateUtil::getCurDate();
		$stime = $curDate . " 08:00:00";
		$etime = $curDate . " 17:30:00";

		$countArray = $this->countProduction($stime, $etime);
		$content = $this->makeProdutionText($countArray, "afternoon");

		$phoneNumbers = $this->getPhoneNumbers("productionDaily");
		if(!empty($phoneNumbers)){
			$SmsService = new SmsService();
			$SmsService->send($content, $phoneNumbers);
		}
	}

	public function actionProductionMorning () {
		$curDate = DateUtil::getCurDate();
		$lastDate = DateUtil::getLastDate();
		$stime = $lastDate . " 08:00:00";
		$etime = $curDate . " 08:00:00";

		$countArray = $this->countProduction($stime, $etime);
		$content = $this->makeProdutionText($countArray, "morning");

		$phoneNumbers = $this->getPhoneNumbers("productionDaily");
		if(!empty($phoneNumbers)){
			$SmsService = new SmsService();
			$SmsService->send($content, $phoneNumbers);
		}
	}

	public function getPhoneNumbers ($type="productionDaily") {
		$sql = "SELECT cellphone FROM view_sms_cellphone WHERE type='$type'";
		$numberArray = Yii::app()->db->createCommand($sql)->queryColumn();
		$numberText = join(",", $numberArray);
		return $numberText;
	}

	public function countProduction ($stime, $etime) {
		$countArray = array();
		$reportSeeker = new ReportSeeker();
		$countArray["assemblyCount"] = $reportSeeker->countCarByPoint($stime, $etime, "assembly");
		// $countArray["finishCount"] = $reportSeeker->countCarByPoint($stime, $etime, "finish");
		$countArray["warehouseCount"] = $reportSeeker->countCarByPoint($stime, $etime, "warehouse");
		$countArray["distributeCount"] = $reportSeeker->countCarByPoint($stime, $etime, "distribute");
		// $countArray["onlineBalance"] = $reportSeeker->countOnline($stime, $etime);
		$countArray["recycleBalance"] = $reportSeeker->countCarByState("recycle");
		// $countArray["warehouseBalance"] = $reportSeeker->countCarByState("WH");

		return $countArray;
	}

	public function makeProdutionText ($countArray, $point="afternoon") {
		$contentArray = array();
		foreach($countArray as $point => $count){
			$detail = array();
			$total = 0;
			foreach(self::$SERIES as $series => $seriesName){
				$detail[$series] = $seriesName ."（". $count[$series] ."）";
				$total += $count[$series];
			}
			$detailText = join("、", $detail);
			$text = self::$COUNT_POINT_DAILY[$point] . "[" . $total ."]\r";
			$text .= $detailText;
			$contentArray[] = $text;
		}

		$body = join(";\r", $contentArray);
		$body .= "。\r";

		if($point == "afternoon"){
			$workDate = DateUtil::getCurDate();
		} else if($point == "morning") {
			$workDate = DateUtil::getLastDate();
		}
		
		$head = date("n月j日", strtotime($workDate));
		$head .= "十一部长沙总装产量通报(" . date("n-j H:i") . ")\r";
		$foot = "【十一部AMS】";
		
		$content = $head . $body . $foot;

		return $content;
	}
}