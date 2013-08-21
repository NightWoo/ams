<?php
Yii::import('application.models.AR.*');
Yii::import('application.models.*');

class DebugController extends BmsBaseController
{
	private static $SERIES = array(
		'F0' => 'F0',
		'M6' => 'M6',
		'6B' => '思锐',
	);

	private static $LC0_TYPE = "('BYD7100L3(1.0排量实用型)','BYD7100L3(1.0排量舒适型)')";
	private static $LC0_TYPE_ARRAY = array('BYD7100L3(1.0排量实用型)','BYD7100L3(1.0排量舒适型)');

	private static $COUNT_POINT_DAILY = array(
		"assemblyCount" => "上线",
		"warehouseCount" => "入库",
		"distributeCount" => "出库",
		// "onlineBalance" => "在制",
		// "recycleBalance" => "周转",
		// "warehouseBalance" => "库存",
	);

	public function actionTest () {
		$vin = $this->validateStringVal("vin", "");
		try{
			$reportSeeker = new ReportSeeker();
			$ret = $this->makeContent('2013-08-19 08:00:00', '2013-08-19 20:00:00', "afternoon");
			$this->renderJsonBms(true, 'OK', $ret);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionProductionAfternoon () {
		$curDate = DateUtil::getCurDate();
		$stime = $curDate . " 08:00:00";
		$etime = $curDate . " 20:00:00";

		$content = $this->makeContent($stime, $etime, "afternoon");
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

		$content = $this->makeContent($stime, $etime, "morning");
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
		$countArray["warehouseCount"] = $reportSeeker->countCarByPoint($stime, $etime, "warehouse");
		$countArray["distributeCount"] = $reportSeeker->countCarByPoint($stime, $etime, "distribute");

		return $countArray;
	}

	public function countRecycle () {
		$seeker = new ReportSeeker();
		$count = $seeker->queryRecycleBalanceNow();
		return $count;
	}

	public function makeContent ($stime, $etime, $type="afternoon") {
		if($type == "afternoon"){
			$workDate = DateUtil::getCurDate();
		} else if($type == "morning") {
			$workDate = DateUtil::getLastDate();
		}
		$head = date("n月j日", strtotime($workDate));
		$head .= "长沙总装产量通报(" . date("n-j,H:i") . ")\r";
		$foot = "【十一部AMS】";

		$productionText = $this->makeProdutionText($stime, $etime);
		$recycleText = $this->makeRecycleText();
		$useText = $this->makeUseText($stime, $etime);

		$body = $productionText . $recycleText . $useText;

		$content = $head.$body.$foot;

		return $content;
	}

	public function makeProdutionText ($stime, $etime) {
		$countArray = $this->countProduction($stime, $etime);
		$textArray = array();
		foreach($countArray as $point => $count){
			$total = 0;
			foreach(self::$SERIES as $series => $seriesName){
				$total += $count[$series];
			}
			$text = self::$COUNT_POINT_DAILY[$point] . $total ."[" . join("/", $count) . "]";
			$textArray[] = $text;
		}
		$productionText = "(1)生产情况(F0/思锐/M6)\r";
		$productionText.= "-" . join(";\r-", $textArray) . ".\r";

		return $productionText;
	}

	public function makeRecycleText () {
		$countArray = $this->countRecycle();
		$total = 0;
		foreach($countArray as $count) {
			$total += intval($count);
		}
		$recycleText = "(2)周转车(VQ1/VQ2/VQ3)\r";
		$recycleText.= "-" . $total . "[" . join("/", $countArray) ."].\r";

		return $recycleText;
	}

	public function makeUseText ($stime, $etime) {
		$pauseDetail = $this->queryPauseDetail($stime, $etime);
		$pauseTime = $this->queryPausetime($stime, $etime);
		$useRate = $this->queryUseRate($stime, $etime);

		$pauseTextArray = array();
		foreach($pauseDetail as $pause) {
			$pauseTextArray[] = $pause['duty_department'] . "/" . $pause['pause_reason'] . "/" . $pause['howlongMin'] . "min";
		}
		$useText = "(3)停线责任\r";
		$useText.= "-" . join(";\r-", $pauseTextArray) . ".\r";
		$useText.= "利用率/停线时长:" . $useRate . "/" . $pauseTime . "min.\r";

		return $useText;
	}

	public function queryUseRate ($stime, $etime) {
		$reportSeeker = new ReportSeeker();
		$data = $reportSeeker->queryUseRateBase($stime, $etime);
		$rate = empty($data['useRate'])? "-" : ($data['useRate'] * 100) . "%";

		return $rate;
	}

	public function queryPausetime ($stime, $etime) {
		$sql = "SELECT SUM(TIMESTAMPDIFF(minute,pause_time,recover_time)) AS howlong FROM pause WHERE pause_type!='计划停线' AND pause_time>='$stime' AND pause_time<'$etime' AND recover_time>'0000-00-00 00:00:00'";
		$data = Yii::app()->db->createCommand($sql)->queryScalar();
		return $data;
	}

	public function queryPauseDetail($stime, $etime) {
		$conditions = array();
		$conditions[] = "(pause_type = '紧急停止' OR pause_type = '设备故障' OR pause_type = '质量关卡' OR pause_type = '工位呼叫')";
		$conditions[] = "pause_time>='$stime' AND pause_time<'$etime'";
		$condition = join(" AND ", $conditions);
		$sql = "SELECT remark AS pause_reason,cause_type,node_id,duty_department, SUM(TIMESTAMPDIFF(second,pause_time,recover_time)) AS howlong 
				FROM pause 
				WHERE $condition 
				GROUP BY pause_reason,cause_type,duty_department
				ORDER BY howlong DESC 
				LIMIT 0,3";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($datas as &$data) {
			$data['howlongMin'] = round($data['howlong'] / 60, 0); 
		}

		return $datas;
	}
}