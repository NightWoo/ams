<?php
Yii::import('application.models.AR.SellOrderViewAR');
Yii::import('application.models.AR.SellSaleViewAR');
Yii::import('application.models.AR.SellShipViewAR');
Yii::import('application.models.AR.SellStockViewAR');

class SellTable
{
    public function __construct () {}

    public function getOrderView () {
        $maxSql = "SELECT MAX(order_id) FROM sell_order_view";
        $max = Yii::app()->db->createCommand($maxSql)->queryScalar();
        $max = empty($max) ? 0 : $max;
        $sql = "SELECT TOP 1 xswl AS distribution_network, yxbmc AS sales_department, ssdq AS sales_region, ssqy AS sales_area, sssf AS sales_province, sscs AS sales_city, sccs AS deliver_city, dgdh AS order_number, dgxz AS order_nature, ddxz AS cold_resistant, fhdwmc AS delivery_unit, dgdwbh AS distributor_code, dgdw AS distributor_name, cldm AS car_type_code, cxmc AS series_name, clxh AS sell_car_type, cx AS sell_config_name, VINxtcx AS car_type, clys AS sell_color, xzpz AS options, dgsl AS amount, yfsl AS delivered_count, ydsl AS arrived_count, wdsl AS not_arrived_count, qxsl AS canceled_count, qxyy AS cancel_reason, dgrq AS book_time, jhzt AS order_plan_status, jhrq AS order_plan_date, cwshjg AS audit_status, cwshyj AS audit_comment, cwshrq AS audit_time, shbz AS audit_flag, shjg AS audit_conclusion, ID AS order_id, jzpz AS additions, mxbz AS remark
            FROM AMS_ORDERVIEW
            WHERE ID > $max";
        $datas = $this->sellMSSQL($sql);
        foreach($datas as $data){
            $ar = new SellOrderViewAR();
            foreach($data as $key => $value) {
                $ar->$key = $value;
            }
            $ar->save();
        }
    }

    public function getSaleView () {
        $maxSql = "SELECT MAX(register_time) FROM sell_sale_view";
        $max = Yii::app()->db->createCommand($maxSql)->queryScalar();
        $max = (empty($max) || $max == '0000-00-00 00:00:00') ? '1800-01-01 00:00:00' : $max;
        $sql = "SELECT TOP 1 xswl AS distribution_network, yxbmc AS sales_department, ssdq AS sales_region, ssqy AS sales_area, sssf AS sales_province, sscs AS sales_city, cxmc AS series_name, gcrq AS sales_date, jxsdm AS distributor_code, jxsmc AS distributor_name, xsdmc AS store_name, csys AS sell_color, xzpz AS options, cldm AS car_type_code, clxh AS sell_car_type, cx AS sell_config_name, VINxtcx AS car_type, VIN AS vin, fdjh AS engine_code, hgzh AS certificate_number, djrq AS register_time
            FROM AMS_SALEVIEW
            WHERE djrq >'$max'";
        $datas = $this->sellMSSQL($sql);
        foreach($datas as $data){
            $ar = new SellSaleViewAR();
            foreach($data as $key => $value) {
                $ar->$key = $value;
            }
            $ar->save();
        }
    }

    public function getShipView () {
        $maxSql = "SELECT MAX(delivery_id) FROM sell_ship_view";
        $max = Yii::app()->db->createCommand($maxSql)->queryScalar();
        $max = empty($max) ? 0 : $max;
        $sql = "SELECT TOP 1 ID AS delivery_id, xswl AS distribution_network, yxbmc AS sales_department, ssdq AS sales_region, ssqy AS sales_area, sssf AS sales_province, sscs AS sales_city, fhdh AS delivery_number, ckdh AS warehouse_out_number, ckrq AS check_out_date, fhdw AS distribute_unit, djrq AS register_time, dgdh AS order_number, cldm AS car_type_code, cxmc AS series_name, clxh AS sell_car_type, VINxtcx AS car_type, VIN AS vin, fdjh AS engine_code, clys AS sell_color, xzpz AS options, hgzh AS certificate_number, jxsdm AS distributor_code, jxsmc AS distributor_name, bz AS remark, SAPwlbm AS sap_material_code, SAPfhdh AS sap_delivery_number, sccs AS delivery_city, scdz AS delivery_address
            FROM AMS_SHIPVIEW
            WHERE ID > $max";
        $datas = $this->sellMSSQL($sql);
        foreach($datas as $data){
            $ar = new SellShipViewAR();
            foreach($data as $key => $value) {
                $ar->$key = $value;
            }
            $ar->save();
        }
    }

    public function getStockView () {
        $maxSql = "SELECT MAX(store_time) FROM sell_stock_view";
        $max = Yii::app()->db->createCommand($maxSql)->queryScalar();
        $max = (empty($max) || $max == '0000-00-00 00:00:00') ? '1800-01-01 00:00:00' : $max;
        $sql = "SELECT TOP 1 yxbmc AS sales_department, ssdq AS sales_region, ssqy AS sales_area, sssf AS sales_province, sscs AS sales_city, jxsdm AS distributor_code, jxsmc AS distributor_name, cldm AS car_type_code, cxmc AS series_name, clxh AS sell_car_type, cx AS sell_config_name, VINxtcx AS car_type, VIN AS vin, dgdh AS order_number, fdjh AS engine_code, hgzh AS certificate_number, clys AS sell_color, xzpz AS options, rkrq AS store_time
            FROM AMS_STOCKVIEW
            WHERE rkrq > '$max'";
        $datas = $this->sellMSSQL($sql);
        foreach($datas as $data){
            $ar = new SellStockViewAR();
            foreach($data as $key => $value) {
                $ar->$key = $value;
            }
            $ar->save();
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
                $data[$key] = iconv('GB2312','UTF-8', $value);
            }
        }

        return $datas;
    }
}