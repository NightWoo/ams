<?php
class Test
{
    public function __construct () {
    }

    public function getOriginalOrders($orderNumber, $target="XF") {
        if(empty($orderNumber)){
            throw new Exception ('订单号不能为空');
        }
        $sql = "SELECT DATAK40_DGMXID AS order_detail_id, DATAK40_JXSMC AS distributor, DATAK40_DGDH AS order_number, DATAK40_CXMC AS series, DATAK40_CLDM AS car_type_code, DATAK40_CLXH AS sell_car_type, DATAK40_BZCX AS car_model, DATAK40_CXSM AS car_type_description, DATAK40_CLYS AS sell_color, DATAK40_VINMYS AS color, DATAK40_DGSL AS amount, DATAK40_XZPZ AS options, DATAK40_DDXZ AS order_nature, DATAK40_DDLX AS cold_resistant, DATAK40_NOTE AS remark, DATAK40_JZPZ AS additions, DATAK40_SSDW AS production_base, DATAK40_JXSDM AS distributor_code
                FROM DATAK40_CLDCKMX
                WHERE DATAK40_SSDW = '3' AND DATAK40_DGDH = '$orderNumber'";
        $prefix = explode("-", $orderNumber)[0];
        if($target == "XF") {
            $tdsSever = Yii::app()->params['tds_SELL'];
            $tdsDB = Yii::app()->params['tds_dbname_BYDDATABASE'];
            $tdsUser = Yii::app()->params['tds_SELL_username'];
            $tdsPwd = Yii::app()->params['tds_SELL_password'];

            $orders = $this->mssqlQuery($tdsSever, $tdsUser, $tdsPwd, $tdsDB, $sql);
        } else if($target == 'CRM') {
            $orders = Yii::app()->dbCRM->createCommand($sql)->queryAll();
        }

        foreach($orders as &$order){
            $order = array_change_key_case($order, CASE_LOWER);
            if($order['series'] == '思锐'){
                $order['series'] = '6B';
            }
            if($order['sell_color'] == '巧克力'){
                $order['sell_color'] = '巧克力棕';
            }
            if($order['color'] == '巧克力'){
                $order['color'] = '巧克力棕';
            }
            $order['car_type'] = $order['car_model']. "(" . $order['car_type_description'] . ")";
            $order['config_description'] = '';
            if(!empty($order['options'])){
                $order['config_description'] .= $order['options'];
                if(!empty($order['additions'])) $order['config_description'] .= $order['additions'];
            }else if(!empty($order['additions'])){
                $order['config_description'] .= $order['additions'];
            }
            $order['cold_resistant'] == '耐寒型' ? $order['cold_resistant'] = '1' : $order['cold_resistant'] = '0';

            $sql="SELECT SUM(amount) FROM `order` WHERE order_detail_id='{$order['order_detail_id']}'";
            $amountSum = Yii::app()->db->createCommand($sql)->queryScalar();
            $order['amount'] -= $amountSum;
            if($order['amount']<0) $order['amount'] = 0;
        }

        return $orders;
    }

    public function mssqlQuery($tdsSever, $tdsUser, $tdsPwd, $tdsDB, $sql){
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