<?php
Yii::import('application.models.AR.WarehouseCountDailyAR');
Yii::import('application.models.OrderSeeker');
class WarehouseCountCommand extends CConsoleCommand
{	
	private static $SERIES = array(
		'F0' => 'F0',
		'M6' => 'M6',
		'6B' => '思锐',
	);

	public function actionCountMorning() {
		$lastDate = DateUtil::getLastDate();
		$curDate = DateUtil::getCurDate();
		$seriesArray = self::$SERIES;
		$monthStart = date("Y-m", strtotime($lastDate)) . "-01 08:00:00";

		$countDate = $curDate;
		$workDate = $lastDate;
		$log = 0;

		$stime = $lastDate . " 08:00:00";
		$etime = $curDate . " 08:00:00";
		$undistributed = $this->countUndistributed($etime);
		foreach($seriesArray as $series => $seriesName){
			$checkin = $this->countCheckin($stime, $etime, $series);
			$this->countRecord('入库',$checkin,$series,$countDate,$workDate,$log);
			$this->throwTextData('入库',$checkin,$seriesName,$countDate,$log);

			$monthCheckin = $this->countCheckin($monthStart, $etime, $series);
			$this->countRecord('已入',$monthCheckin,$series,$countDate,$workDate,$log);

			$reviseMonthCheckin = $this->getReviseCount($series, '已入');
			$monthCheckin += $reviseMonthCheckin;
			$this->throwTextData('已入',$monthCheckin,$seriesName,$countDate,$log);

			$checkout = $this->countCheckout($stime, $etime, $series);
			$this->countRecord('出库',$checkout,$series,$countDate,$workDate,$log);
			$this->throwTextData('出库',$checkout,$seriesName,$countDate,$log);

			$monthCheckout = $this->countCheckout($monthStart, $etime, $series);
			$this->countRecord('已发',$monthCheckout,$series,$countDate,$workDate,$log);

			$reviseCheckout = $this->getReviseCount($series, '已发');
			$monthCheckout += $reviseCheckout;
			$this->throwTextData('已发',$monthCheckout,$seriesName,$countDate,$log);
			
			$balance = $this->countBalance($series);
			$this->countRecord('库存',$balance,$series,$countDate,$workDate,$log);
			$this->throwTextData('库存',$balance,$seriesName,$countDate,$log);

			$this->countRecord('未发',$undistributed[$series],$series,$countDate,$workDate,$log);
			$this->throwTextData('未发',$undistributed[$series],$seriesName,$countDate,$log);			
		}
	}

	public function actionCountAfternoon() {
		$lastDate = DateUtil::getLastDate();
		$curDate = DateUtil::getCurDate();
		$seriesArray = self::$SERIES;
		$monthStart = date("Y-m", strtotime($curDate)) . "-01 08:00:00";

		$countDate = $curDate;
		$workDate = $curDate;
		$log = 1;

		$stime = $curDate . " 08:00:00";
		$etime = $curDate . " 17:30:00";

		$undistributed = $this->countUndistributed($etime);
		foreach($seriesArray as $series => $seriesName){
			$checkin = $this->countCheckin($stime, $etime, $series);
			$this->countRecord('入库',$checkin,$series,$countDate,$workDate,$log);
			$this->throwTextData('入库',$checkin,$seriesName,$countDate,$log);

			$monthCheckin = $this->countCheckin($monthStart, $etime, $series);
			$this->countRecord('已入',$monthCheckin,$series,$countDate,$workDate,$log);

			$reviseMonthCheckin = $this->getReviseCount($series, '已入');
			$monthCheckin += $reviseMonthCheckin;
			$this->throwTextData('已入',$monthCheckin,$seriesName,$countDate,$log);

			$checkout = $this->countCheckout($stime, $etime, $series);
			$this->countRecord('出库',$checkout,$series,$countDate,$workDate,$log); 
			$this->throwTextData('出库',$checkout,$seriesName,$countDate,$log);

			$monthCheckout = $this->countCheckout($monthStart, $etime, $series);
			$this->countRecord('已发',$monthCheckout,$series,$countDate,$workDate,$log);
			
			$reviseCheckout = $this->getReviseCount($series, '已发');
			$monthCheckout += $reviseCheckout;
			$this->throwTextData('已发',$monthCheckout,$seriesName,$countDate,$log);
			
			$balance = $this->countBalance($series);
			$this->countRecord('库存',$balance,$series,$countDate,$workDate,$log);
			$this->throwTextData('库存',$balance,$seriesName,$countDate,$log);

			$this->countRecord('未发',$undistributed[$series],$series,$countDate,$workDate,$log);
			$this->throwTextData('未发',$undistributed[$series],$seriesName,$countDate,$log);
		}
	}

	private function getReviseCount($series, $countType){
		$sql = "SELECT count FROM warehouse_count_revise WHERE series='$series' AND count_type='$countType'";
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		return $count;
	}

	private function countCheckin($stime,$etime,$series) {
		$sql = "SELECT COUNT(id) FROM car WHERE series='$series' AND warehouse_time>='$stime' AND warehouse_time<'$etime'";
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		return $count;
	}

	private function countCheckout($stime,$etime,$series,$noExport=false) {
		$sql = "SELECT COUNT(id) FROM car WHERE series='$series' AND distribute_time>='$stime' AND distribute_time<'$etime'";
		if($noExport){
			$sql .= " AND special_property=0";
		}
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		return $count;
	}

	private function countBalance($series, $all=false) {
		$sql = "SELECT COUNT(id) FROM car WHERE series='$series' AND (`status`='成品库' OR `status`='WDI')";
		if(!$all){
			$sql .= " AND warehouse_id <= 2000 AND warehouse_id <> 1000 AND special_property < 9";
		}
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		return $count;
	}

	private function countUndistributed($etime) {
		$seriesArray = array('F0', 'M6', '6B');

		$stime = "2013-06-04 08:00:00";
		//初始时间2013-06-04 08:00前的未发值
		$count = array(
	    	'F0' => 2829,
	    	'M6' => 603,
	    	'6B' => 382,
	    );

		////初始时间2013-06-04 08:00时的最大DATAK40_DGMXID为1746208
		$sql = "SELECT SUM(DATAK40_DGSL) as sum,
						DATAK40_CXMC as series 
				FROM DATAK40_CLDCKMX 
				WHERE DATAK40_DGMXID>1746208 AND DATAK40_SSDW=3 
				GROUP BY DATAK40_CXMC";
		$tdsSever = Yii::app()->params['tds_SELL'];
        $tdsDB = Yii::app()->params['tds_dbname_BYDDATABASE'];
        $tdsUser = Yii::app()->params['tds_SELL_username'];
        $tdsPwd = Yii::app()->params['tds_SELL_password'];

        $datas = $this->mssqlQuery($tdsSever, $tdsUser, $tdsPwd, $tdsDB, $sql);
        foreach($datas as &$data){
        	if($data['series'] == '思锐'){
        		$data['series'] = '6B';
	        }
	        $count[$data['series']] += $data['sum'];
	    }

	    // $sql = "SELECT SUM(count) as sum, series FROM `order` WHERE order_detail_id>1746208 GROUP BY series";
	    // $rets = Yii::app()->db->createCommand($sql)->queryAll();

	    // foreach($rets as $ret){
		   //  $count[$ret['series']] -= $ret['sum'];
	    // }
	    
	    //计算从初始时间2013-06-04 08:00开始到目前的出库量，并从未发值中减去
	    $noExport=true;
	    foreach($seriesArray as $series){
	    	$checkout = $this->countCheckout($stime, $etime, $series, $noExport);
	    	$count[$series] -= $checkout;
	    }

	    return $count;
	}

	private function throwTextData($countType,$count,$series,$date,$log) {
		$client = new SoapClient(Yii::app()->params['ams2vin_note']);
		$params = array(
			'Date'=>$date, 
			'AutoType'=>$series, 
			'Sum'=>$count,
			'StatType'=>$countType,
			'NoteLog'=>$log,
		);
		if(!empty($time)){
			$params['Date'] = $time;
		}
		$result = (array)$client -> NoteStat($params);

		return $result;
	}

	private function countRecord($countType,$count,$series,$countDate,$workDate,$log){
		$ar = new WarehouseCountDailyAR();
		$ar->series = $series;
		$ar->count = $count;
		$ar->count_type = $countType;
		$ar->count_date = $countDate;
		$ar->work_date = $workDate;
		$ar->log = $log;
		$ar->save();
	}

	private function mssqlQuery($tdsSever, $tdsUser, $tdsPwd, $tdsDB, $sql){
		//php 5.4 linux use pdo cannot connet to ms sqlsrv db 
        //use mssql_XXX instead

		$mssql=mssql_connect($tdsSever, $tdsUser, $tdsPwd);
        if(empty($mssql)) {
            throw new Exception("cannot connet to sqlserver $tdsSever, $tdsUser ");
        }
        mssql_select_db($tdsDB ,$mssql);
        
        //query
        $result = mssql_query($sql);
        $datas = array(); 
        while($ret = mssql_fetch_assoc($result)){
        	$datas[] = $ret;
        }
        //disconnect
        mssql_close($mssql);

        //convert to UTF-8
        foreach($datas as &$data){
            foreach($data as $key => $value){
                $data[$key] = iconv('GB2312','UTF-8', $value);
            }
        }

        return $datas;
	}
}
