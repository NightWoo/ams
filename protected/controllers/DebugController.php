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
        // $transaction = Yii::app()->db->beginTransaction();
        try {
            $seriesNameList = Series::getNameList();
            foreach($seriesNameList as $series => $seriesName) {
                $this->updateOrderView($series);
            }
            // $transaction->commit();
            $this->renderJsonBms(true, 'OK', $ret);
        } catch(Exception $e) {
            $this->transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
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

    public function parseDate ($sDate, $eDate) {
        $stime = $sDate . ' 08:00:00';
        $etime = date("Y-m-d H:i:s", strtotime('+1 day', strtotime($eDate . ' 08:00:00')));

        $s = strtotime($stime);
        $e = strtotime($etime);

        $months = (date("Y", $e)-date("Y", $s))*12+(date("m", $e)-date("m",$s));
        $timespan = $months>=2 ? "yearly" : "monthly";
        $ret = array();
        switch($timespan) {
            case "monthly":
                $pointFormat = 'm-d';
                $format = 'Y-m-d H:i:s';
                $slice = 86400;
                break;
            case "yearly":
                $pointFormat = 'y-m';
                $format = 'Y-m-d H:i:s';
                break;
        }

        $t = $s;
        while($t<$e) {
            $point = date($pointFormat, $t);
            if($pointFormat === 'y-m') {
                $eNextM = strtotime('first day of next month', $t); //next month
                $ee = date('Y-m-d', $eNextM) . " 08:00:00"; //next month firstday
                $etmp = strtotime($ee); //next month firstday
            } else {
                $etmp = $t+$slice;
            }
            if($etmp>=$e){
                $etmp=$e;
            }

            $ret[] = array(
                'stime' => date($format, $t),
                'etime' => date($format, $etmp),
                'point' => $point,
            );
            $t = $etmp;
        }

        return $ret;
    }

    public function parseQueryTime ($stime, $etime, $timespan) {
        $s = strtotime($stime);
        $e = strtotime($etime);

        $ret = array();

        switch($timespan) {
            case "monthly":
                $pointFormat = 'd';
                $format = 'Y-m-d H:i:s';
                $slice = 86400;
                break;
            case "yearly":
                $pointFormat = 'm';
                $format = 'Y-m-d H:i:s';
                break;
            default:
                $pointFormat = 'd';
                $format = 'Y-m-d H:i:s';
                $slice = 86400;
        }

        $t = $s;
        while($t<$e) {
            $point = date($pointFormat, $t);
            if($pointFormat === 'm') {
                $eNextM = strtotime('first day of next month', $t); //next month
                $ee = date('Y-m-d', $eNextM) . " 08:00:00"; //next month firstday
                $etmp = strtotime($ee); //next month firstday
            } else {
                $etmp = $t+$slice;
            }
            if($etmp>=$e){
                $etmp=$e;
            }

            $ret[] = array(
                'stime' => date($format, $t),
                'etime' => date($format, $etmp),
                'point' => $point,
            );
            $t = $etmp;
        }

        return $ret;
    }

    public function updateOrderView ($series) {
        $seriesCodeList = Series::getCodeList();
        $sql = "SELECT order_id FROM sell_order_view WHERE (((audit_conclusion=0 OR not_arrived_count>0) AND audit_time>'0000-00-00 00:00:00') OR (audit_status=0 AND book_time>'2013-01-01 00:00:00'))  AND series='$series' ORDER BY book_time DESC LIMIT 3000";
        $ids = Yii::app()->db->createCommand($sql)->queryColumn();
        if(!empty($ids)) {
            $idCondition = "(" . join(",", $ids) .")";
            $mssql = "SELECT
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
                $ar = SellOrderViewAR::model()->find("order_id", array($data['order_id']));
                foreach($data as $key => $value) {
                    if($key == "order_id") {
                        continue;
                    } else if($key == "series_name") {
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

    public function actionCountMorning() {
        $lastDate = DateUtil::getLastDate();
        $curDate = DateUtil::getCurDate();
        // $seriesArray = self::$SERIES;
        $seriesArray = Series::getNameList();
        $monthStart = date("Y-m", strtotime($lastDate)) . "-01 08:00:00";

        $countDate = $curDate;
        $workDate = $lastDate;
        $log = 0;

        $stime = $lastDate . " 08:00:00";
        $etime = $curDate . " 08:00:00";

        $undistributed = $this->countUndistributed($etime);
        foreach($seriesArray as $series => $seriesName){
            $this->getSellTableDatas($series);

            $assembly = $this->countAssembly($stime, $etime, $series);
            $this->countRecord('上线',$assembly,$series,$countDate,$workDate,$log);

            $checkin = $this->countCheckin($stime, $etime, $series);
            $this->countRecord('入库',$checkin,$series,$countDate,$workDate,$log);
            $this->throwTextData('入库',$checkin,$seriesName,$countDate,$log);

            $monthCheckin = $this->countCheckin($monthStart, $etime, $series);
            $this->countRecord('已入',$monthCheckin,$series,$countDate,$workDate,$log);

            // $reviseMonthCheckin = $this->getReviseCount($series, '已入');
            // $monthCheckin += $reviseMonthCheckin;
            $this->throwTextData('已入',$monthCheckin,$seriesName,$countDate,$log);

            $checkout = $this->countCheckout($stime, $etime, $series);
            $this->countRecord('出库',$checkout,$series,$countDate,$workDate,$log);
            $this->throwTextData('出库',$checkout,$seriesName,$countDate,$log);

            $monthCheckout = $this->countCheckout($monthStart, $etime, $series);
            $this->countRecord('已发',$monthCheckout,$series,$countDate,$workDate,$log);

            // $reviseCheckout = $this->getReviseCount($series, '已发');
            // $monthCheckout += $reviseCheckout;
            $this->throwTextData('已发',$monthCheckout,$seriesName,$countDate,$log);

            $balance = $this->countBalance($series);
            $this->countRecord('库存',$balance,$series,$countDate,$workDate,$log);
            $this->throwTextData('库存',$balance,$seriesName,$countDate,$log);

            $this->countRecord('未发',$undistributed[$series],$series,$countDate,$workDate,$log);
            $this->throwTextData('未发',$undistributed[$series],$seriesName,$countDate,$log);

            // $sell->updateOrderView($series);
        }

        $sell = new SellTable();
        $sell->getStockDaily();
    }

    public function actionCountAfternoon() {
        $lastDate = DateUtil::getLastDate();
        $curDate = DateUtil::getCurDate();
        // $seriesArray = self::$SERIES;
        $seriesArray = Series::getNameList();
        $monthStart = date("Y-m", strtotime($curDate)) . "-01 08:00:00";

        $countDate = $curDate;
        $workDate = $curDate;
        $log = 1;

        $stime = $curDate . " 08:00:00";
        $etime = $curDate . " 17:30:00";

        $undistributed = $this->countUndistributed($etime);
        foreach($seriesArray as $series => $seriesName){
            $this->getSellTableDatas($series);

            $assembly = $this->countAssembly($stime, $etime, $series);
            $this->countRecord('上线',$assembly,$series,$countDate,$workDate,$log);

            $checkin = $this->countCheckin($stime, $etime, $series);
            $this->countRecord('入库',$checkin,$series,$countDate,$workDate,$log);
            $this->throwTextData('入库',$checkin,$seriesName,$countDate,$log);

            $monthCheckin = $this->countCheckin($monthStart, $etime, $series);
            $this->countRecord('已入',$monthCheckin,$series,$countDate,$workDate,$log);
            // $reviseMonthCheckin = $this->getReviseCount($series, '已入');
            // $monthCheckin += $reviseMonthCheckin;
            $this->throwTextData('已入',$monthCheckin,$seriesName,$countDate,$log);

            $checkout = $this->countCheckout($stime, $etime, $series);
            $this->countRecord('出库',$checkout,$series,$countDate,$workDate,$log);
            $this->throwTextData('出库',$checkout,$seriesName,$countDate,$log);

            $monthCheckout = $this->countCheckout($monthStart, $etime, $series);
            $this->countRecord('已发',$monthCheckout,$series,$countDate,$workDate,$log);
            // $reviseCheckout = $this->getReviseCount($series, '已发');
            // $monthCheckout += $reviseCheckout;
            $this->throwTextData('已发',$monthCheckout,$seriesName,$countDate,$log);

            $balance = $this->countBalance($series);
            $this->countRecord('库存',$balance,$series,$countDate,$workDate,$log);
            $this->throwTextData('库存',$balance,$seriesName,$countDate,$log);

            $this->countRecord('未发',$undistributed[$series],$series,$countDate,$workDate,$log);
            $this->throwTextData('未发',$undistributed[$series],$seriesName,$countDate,$log);

            // $sell->updateOrderView($series);
        }
    }

    private function getSellTableDatas ($series) {
        $sellTable = new SellTable();
        // $sellTable->updateOrderView($series);
        $sellTable->getOrderView($series);
        $sellTable->getSaleView($series);
        $sellTable->getShipView($series);
        // $sellTable->getStockView($series);
    }

    private function getReviseCount($series, $countType) {
        $sql = "SELECT count FROM warehouse_count_revise WHERE series='$series' AND count_type='$countType'";
        $count = Yii::app()->db->createCommand($sql)->queryScalar();
        return $count;
    }

    private function countAssembly($stime,$etime,$series) {
        $sql = "SELECT COUNT(id) FROM car WHERE series='$series' AND assembly_time>='$stime' AND assembly_time<'$etime'";
        $count = Yii::app()->db->createCommand($sql)->queryScalar();
        return $count;
    }

    private function countFinish($stime,$etime,$series) {
        $sql = "SELECT COUNT(id) FROM car WHERE series='$series' AND finish_time>='$stime' AND finish_time<'$etime'";
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
            $sql .= " AND special_property!=1";
        }
        $count = Yii::app()->db->createCommand($sql)->queryScalar();
        return $count;
    }

    private function countBalance($series, $all=false) {
        $sql = "SELECT COUNT(id) FROM car WHERE series='$series' AND (`status`='成品库' OR `status`='WDI')";
        if(!$all){
            $sql .= " AND warehouse_id < 3000 AND warehouse_id <> 1000 AND special_property < 9";
        }
        $count = Yii::app()->db->createCommand($sql)->queryScalar();
        return $count;
    }

    private function countUndistributed($etime) {
        // $seriesArray = array('F0', 'M6', '6B', 'G6');
        $seriesArray = Series::getArray();

        //初始时间2013-06-04 08:00前的未发值
        // $count = array(
     //     'F0' => 2829,
     //     'M6' => 603,
     //     '6B' => 382,
     //     'G6' => 0,
     //    );
        foreach($seriesArray as $series) {
            $count[$series] = $this->getReviseCount($series, '未发');
        }

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
        $stime = "2013-06-04 08:00:00";
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