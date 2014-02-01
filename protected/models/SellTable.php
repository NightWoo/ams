<?php
Yii::import('application.models.AR.SellOrderViewAR');
Yii::import('application.models.AR.SellSaleViewAR');
Yii::import('application.models.AR.SellShipViewAR');
Yii::import('application.models.AR.SellStockViewAR');

class SellTable
{
    public function __construct () {}

    public function getOrderView ($series) {
        $seriesNameList = Series::getNameList();
        $seriesCodeList = Series::getCodeList();
        $seriesName = iconv('UTF-8', 'GB2312', $seriesNameList[$series]);
        $maxSql = "SELECT MAX(order_id) FROM sell_order_view WHERE series='$series'";
        $max = Yii::app()->db->createCommand($maxSql)->queryScalar();
        $max = empty($max) ? 0 : $max;
        $sql = "SELECT TOP 10000
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
            WHERE cxmc='$seriesName' AND ID>$max ORDER BY ID ASC";
        $datas = $this->sellMSSQL($sql);
        foreach($datas as $data){
            $ar = new SellOrderViewAR();
            foreach($data as $key => $value) {
                if($key == "series_name") {
                    $ar->series = $seriesCodeList[$value];
                } else {
                    $ar->$key = $value;
                }
            }
            $ar->save();
        }
    }

    public function getSaleView ($series) {
        $seriesNameList = Series::getNameList();
        $seriesCodeList = Series::getCodeList();
        $seriesName = iconv('UTF-8', 'GB2312', $seriesNameList[$series]);
        $maxSql = "SELECT MAX(register_time) FROM sell_sale_view WHERE series='$series'";
        $max = Yii::app()->db->createCommand($maxSql)->queryScalar();
        $max = (empty($max) || $max == '0000-00-00 00:00:00') ? '1800-01-01 00:00:00' : $max;
        $sql = "SELECT TOP 10000
        xswl AS distribution_network,
        yxbmc AS sales_department,
        ssdq AS sales_region,
        ssqy AS sales_area,
        sssf AS sales_province,
        sscs AS sales_city,
        cxmc AS series_name,
        convert(varchar(30),[gcrq],120) AS sales_date,
        jxsdm AS distributor_code,
        jxsmc AS distributor_name,
        xsdmc AS store_name,
        csys AS sell_color,
        xzpz AS options,
        cldm AS car_type_code,
        clxh AS sell_car_type,
        cx AS sell_config_name,
        VINxtcx AS car_type,
        VIN AS vin,
        fdjh AS engine_code,
        hgzh AS certificate_number,
        convert(varchar(30),[djrq],120) AS register_time
            FROM AMS_SALEVIEW
            WHERE cxmc='$seriesName' AND convert(varchar(30),[djrq],120)>'$max' ORDER BY djrq ASC";
        $datas = $this->sellMSSQL($sql);
        foreach($datas as $data){
            $ar = new SellSaleViewAR();
            foreach($data as $key => $value) {
                if($key == "series_name") {
                    $ar->series = $seriesCodeList[$value];
                } else {
                    $ar->$key = $value;
                }
            }
            $ar->save();
        }
    }

    public function getShipView ($series) {
        $seriesNameList = Series::getNameList();
        $seriesCodeList = Series::getCodeList();
        $seriesName = iconv('UTF-8', 'GB2312', $seriesNameList[$series]);
        $maxSql = "SELECT MAX(delivery_id) FROM sell_ship_view WHERE series='$series'";
        $max = Yii::app()->db->createCommand($maxSql)->queryScalar();
        $max = empty($max) ? 0 : $max;
        $sql = "SELECT TOP 10000
        ID AS delivery_id,
        xswl AS distribution_network,
        yxbmc AS sales_department,
        ssdq AS sales_region,
        ssqy AS sales_area,
        sssf AS sales_province,
        sscs AS sales_city,
        fhdh AS delivery_number,
        ckdh AS warehouse_out_number,
        convert(varchar(30),[ckrq],120) AS check_out_date,
        fhdw AS distribute_unit,
        convert(varchar(30),[djrq],120) AS register_time,
        dgdh AS order_number,
        cldm AS car_type_code,
        cxmc AS series_name,
        clxh AS sell_car_type,
        VINxtcx AS car_type,
        VIN AS vin,
        fdjh AS engine_code,
        clys AS sell_color,
        xzpz AS options,
        hgzh AS certificate_number,
        jxsdm AS distributor_code,
        jxsmc AS distributor_name,
        bz AS remark,
        SAPwlbm AS sap_material_code,
        SAPfhdh AS sap_delivery_number,
        sccs AS delivery_city,
        scdz AS delivery_address
            FROM AMS_SHIPVIEW
            WHERE cxmc='$seriesName' AND ID>$max AND fhdw='3' ORDER BY ID ASC";
        $datas = $this->sellMSSQL($sql);
        foreach($datas as $data){
            $ar = new SellShipViewAR();
            foreach($data as $key => $value) {
                if($key == "series_name") {
                    $ar->series = $seriesCodeList[$value];
                } else {
                    $ar->$key = $value;
                }
            }
            $ar->save();
        }
    }

    public function getStockView ($series) {
        $seriesNameList = Series::getNameList();
        $seriesCodeList = Series::getCodeList();
        $seriesName = iconv('UTF-8', 'GB2312', $seriesNameList[$series]);
        $maxSql = "SELECT MAX(store_time) FROM sell_stock_view";
        $max = Yii::app()->db->createCommand($maxSql)->queryScalar();
        $max = (empty($max) || $max == '0000-00-00 00:00:00') ? '1800-01-01 00:00:00' : $max;
        $sql = "SELECT TOP 10000
        yxbmc AS sales_department,
        ssdq AS sales_region,
        ssqy AS sales_area,
        sssf AS sales_province,
        sscs AS sales_city,
        jxsdm AS distributor_code,
        jxsmc AS distributor_name,
        cldm AS car_type_code,
        cxmc AS series_name,
        clxh AS sell_car_type,
        cx AS sell_config_name,
        VINxtcx AS car_type,
        VIN AS vin,
        dgdh AS order_number,
        fdjh AS engine_code,
        hgzh AS certificate_number,
        clys AS sell_color,
        xzpz AS options,
        convert(varchar(30),[rkrq],120) AS store_time
            FROM AMS_STOCKVIEW
            WHERE cxmc='$seriesName' AND convert(varchar(30),[rkrq],120) > '$max' ORDER BY rkrq ASC";
        $datas = $this->sellMSSQL($sql);
        foreach($datas as $data){
            $ar = new SellStockViewAR();
            foreach($data as $key => $value) {
                if($key == "series_name") {
                    $ar->series = $seriesCodeList[$value];
                } else {
                    $ar->$key = $value;
                }
            }
            $ar->save();
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

    public function stockDaily() {

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