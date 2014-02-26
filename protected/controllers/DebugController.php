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
            // $client =new SoapClient('http://192.168.1.38/bms/webService/carInfo/quote');
            // $ret = $client->getCarInfo('LC0C14AA8D0061858');
            $vin = 797;
            if(strlen($vin) != 17 && substr(strtoupper($vin), 0, 1) != 'L') {
                $sql = "SELECT vin FROM car WHERE id = $vin";
                $vin = Yii::app()->db->createCommand($sql)->queryScalar();
            }
            $car = Car::create($vin);
            $ret = $car->car->vin;
            $this->renderJsonBms(true, 'OK', $ret);

        } catch(Exception $e) {
            // $this->transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function queryOrderCount ($stime, $etime, $series="", $netName="" ) {
        $sql = "SELECT SUM(dgsl) AS count
                FROM AMS_ORDERVIEW
                WHERE convert(varchar(30),[cwshrq],120)>='$stime'
                    AND convert(varchar(30),[cwshrq],120)<'$etime'
                    AND cwshjg='1'";
        if(!empty($series)) {
            $seriesName = iconv('UTF-8', 'GBK', Series::getName($series));
            $sql .= " AND cxmc='$seriesName'";
        }
        if(!empty($netName)) {
            $netName = iconv('UTF-8', 'GBK', $netName);
            $sql .= " AND xswl='$netName'";
        }
        $count = $this->sellMSSQL($sql);

        return intval($count);
    }

    public function queryOrderDistributorCount ($stime="", $etime="", $series="", $netName="") {
        $conditions = array();
        if(!empty($stime)) {
            $conditions[] = "convert(varchar(30),[cwshrq],120)>='$stime'";
        }
        if(!empty($etime)) {
            $conditions[] = "convert(varchar(30),[cwshrq],120)<'$etime'";
        }
        if(!empty($series)) {
            $seriesName = iconv('UTF-8', 'GBK', Series::getName($series));
            $conditions[] = "cxmc='$seriesName'";
        }
        if(!empty($netName)) {
            $netName = iconv('UTF-8', 'GBK', $netName);
            $conditions[] = "xswl='$netName'";
        }
        $conditions[] = "cwshjg='1'";
        $condition = empty($conditions) ? "" : "WHERE " . join(" AND ", $conditions);

        $sql = "SELECT COUNT(DISTINCT dgdw) AS count
                FROM AMS_ORDERVIEW $condition";
        $count = $this->sellMSSQL($sql);

        return intval($count);
    }

    public function actionTestCRMempty () {
        try {
            $sql = "SELECT DATAK40_DGMXID AS order_detail_id, DATAK40_JXSMC AS distributor, DATAK40_DGDH AS order_number, DATAK40_CXMC AS series, DATAK40_CLDM AS car_type_code, DATAK40_CLXH AS sell_car_type, DATAK40_BZCX AS car_model, DATAK40_CXSM AS car_type_description, DATAK40_CLYS AS sell_color, DATAK40_VINMYS AS color, DATAK40_DGSL AS amount, DATAK40_XZPZ AS options, DATAK40_DDXZ AS order_nature, DATAK40_DDLX AS cold_resistant, DATAK40_NOTE AS remark, DATAK40_JZPZ AS additions, DATAK40_SSDW AS production_base, DATAK40_JXSDM AS distributor_code
                FROM DATAK40_CLDCKMX
                WHERE DATAK40_SSDW = '3' AND DATAK40_DGDH = '1111'";

            $orders = Yii::app()->dbCRM->createCommand($sql)->queryAll();

            $ret = empty($orders) ? 'is empty' : $orders;

            $this->renderJsonBms(true, 'OK', $ret);
        } catch(Exception $e) {
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

    public function sellMSSQL($sql){
        //php 5.4 linux use pdo cannot connet to ms sqlsrv db
        //use mssql_XXX instead

        $tdsSever = Yii::app()->params['tds_SELL'];
        $tdsDB = Yii::app()->params['tds_dbname_BYDDATABASE'];
        $tdsUser = Yii::app()->params['tds_SELL_username'];
        $tdsPwd = Yii::app()->params['tds_SELL_password'];

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
                $data[$key] = iconv('GBK','UTF-8', $value);
            }
        }

        return $datas;
    }
}