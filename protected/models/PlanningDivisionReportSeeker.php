<?php
Yii::import('application.models.AR.NodeAR');
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.LaneAR');
Yii::import('application.models.AR.WarehouseAR');

class PlanningDivisionReportSeeker
{
    public function __construct () {}

    private static $COLD_RESISTANT = array('非耐寒','耐寒');

        public function queryPlanningDivisionReportDaily ($date) {
        $pdTypelist = $this->getPlanningDivisionTypeList();

        $pointArray = array(
            "assembly" => array(
                "daily" => array("domestic"=>0,"export"=>0, "sum"=>0),
                "monthly" => array("domestic"=>0,"export"=>0),
            ),
            "warehouse" => array(
                "daily" => array("domestic"=>0,"export"=>0, "sum"=>0),
                "monthly" => array("domestic"=>0,"export"=>0),
                "yearly" => array("domestic"=>0,"export"=>0),
            ),
            "distribute" => array(
                "daily" => array("domestic"=>0,"export"=>0),
                "monthly" => array("domestic"=>0,"export"=>0),
                "yearly" => array("domestic"=>0,"export"=>0),
            ),
        );
        $countData = array();
        foreach($pdTypelist as $series => $typeGroups) {
            foreach($typeGroups as $typeGroup) {
                $countData[$series][$typeGroup] = $pointArray;
                $countData[$series][$typeGroup]['warehouseBalance'] = array("daily" => array("domestic"=>0,"export"=>0));
                $countData[$series][$typeGroup]['stockBalance'] = array("daily" => array("all"=>0));
                $countData[$series][$typeGroup]['saleVolume'] = array(
                    "mothly" => array("all"=>0),
                    "yearly" => array("all"=>0)
                );
            }
            $countData[$series]['合计'] = $pointArray;
            $countData[$series]['合计']['warehouseBalance'] = array("daily" => array("domestic"=>0,"export"=>0));
            $countData[$series]['合计']['stockBalance'] = array("daily" => array("all"=>0));
            $countData[$series]['合计']['saleVolume'] = array(
                "monthly" => array("all"=>0),
                "yearly" => array("all"=>0)
            );
        }

        $countTotal = $pointArray;
        $countTotal['warehouseBalance'] = array("daily" => array("domestic"=>0,"export"=>0));
        $countTotal['stockBalance'] = array("daily" => array("all"=>0));
        $countTotal['saleVolume'] = array(
            "monthly" => array("all"=>0),
            "yearly" => array("all"=>0),
        );

        foreach($pointArray as $point => $timespans) {
            foreach($timespans as $timespan => $regions) {
                $datas = $this->countPlnningDivisionCars($date, $timespan, $point);
                foreach($datas as $data) {
                    if($data['special_property'] == '1') {
                        $countData[$data['series']][$data['pdType']][$point][$timespan]['export'] += intval($data['count']);
                        $countData[$data['series']]['合计'][$point][$timespan]['export'] += intval($data['count']);
                        $countTotal[$point][$timespan]['export'] += intval($data['count']);
                    } else {
                        $countData[$data['series']][$data['pdType']][$point][$timespan]['domestic'] += intval($data['count']);
                        $countData[$data['series']]['合计'][$point][$timespan]['domestic'] += intval($data['count']);
                        $countTotal[$point][$timespan]['domestic'] += intval($data['count']);
                    }
                    if(array_key_exists('sum', $countData[$data['series']][$data['pdType']][$point][$timespan])){
                        $countData[$data['series']][$data['pdType']][$point][$timespan]['sum'] += intval($data['count']);
                        $countData[$data['series']]['合计'][$point][$timespan]['sum'] += intval($data['count']);
                        $countTotal[$point][$timespan]['sum'] += intval($data['count']);
                    }
                }
            }
        }

        $warehouseBalance = $this->balanceCountGroupByPdType($date,'WH');
        foreach($warehouseBalance as $data) {
            if($data['special_property'] == '1') {
                $countData[$data['series']][$data['pdType']]['warehouseBalance']['daily']['export'] += intval($data['count']);
                $countData[$data['series']]['合计']['warehouseBalance']['daily']['export'] += intval($data['count']);
                $countTotal['warehouseBalance']['daily']['export'] += intval($data['count']);
            } else if ($data['special_property'] != '9') {
                $countData[$data['series']][$data['pdType']]['warehouseBalance']['daily']['domestic'] += intval($data['count']);
                $countData[$data['series']]['合计']['warehouseBalance']['daily']['domestic'] += intval($data['count']);
                $countTotal['warehouseBalance']['daily']['domestic'] += intval($data['count']);
            }
        }

        $stockBalance = $this->stockGroupByPdType($date);
        foreach($stockBalance as $data) {
            $countData[$data['series']][$data['pdType']]['stockBalance']['daily']['all'] += $data['count'];
            $countData[$data['series']]['合计']['stockBalance']['daily']['all'] += $data['count'];
            $countTotal['stockBalance']['daily']['all'] += intval($data['count']);
        }

        $saleSpan = array('monthly','yearly');
        foreach($saleSpan as $timespan) {
            $saleVolume = $this->saleVolumeGroupByPdType($date, $timespan);
            foreach($saleVolume as $data) {
                $countData[$data['series']][$data['pdType']]['saleVolume'][$timespan]['all'] += intval($data['count']);
                $countData[$data['series']]['合计']['saleVolume'][$timespan]['all'] += intval($data['count']);
                $countTotal['saleVolume'][$timespan]['all'] += intval($data['count']);
            }
        }

        return array("countData"=>$countData,"countTotal"=>$countTotal);
    }

    public function countPlnningDivisionCars ($date, $timespan, $point) {
        list($stime, $etime) = $this->reviseTime($date, $timespan);
        if($timespan == "monthly" || $timespan == "yearly") {
            $nextDay = strtotime("+1 day", strtotime($date));
            $etime = date("Y-m-d", $nextDay) . " 08:00:00";
        }
        $count = $this->countCarGroupByPdType($stime, $etime, $point);
        return $count;
    }

    public function balanceCountGroupByPdType ($date, $state) {
        $sql = "SELECT series,planning_division_type_name as pdType,special_property, count FROM balance_planning_division_daily WHERE state='$state' AND work_date='$date'";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        return $datas;
    }

    public function stockGroupByPdType($date) {
        $sql = "SELECT series, planning_division_type_name AS pdType, SUM(count) AS count FROM view_sell_stock_daily WHERE planning_division_statistics=1 AND DATE(create_time)='$date' GROUP BY series, pdType";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        return $datas;
    }

    public function saleVolumeGroupByPdType($date, $timespan) {
        list($stime, $etime) = $this->reviseTime($date, $timespan);
        $sql = "SELECT series, planning_division_type_name AS pdType, COUNT(DISTINCT vin) AS count FROM view_sale WHERE planning_division_statistics=1 AND register_time>='$stime' AND register_time<'$etime' GROUP BY series, pdType";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        return $datas;
    }

    public function countCarGroupByPdType ($stime,$etime,$point="assembly",$line="") {
        $point .= "_time";
        $sql = "SELECT series,planning_division_type_name as pdType,special_property, COUNT(car_id) as `count` FROM view_car_info_main WHERE $point>='$stime' AND $point<'$etime'";
        if(!empty($line)){
            $sql .= " AND assembly_line='$line'";
        }
        $sql .= " GROUP BY series,PDType,special_property";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();

        return $datas;
    }

    public function planningDivisionSms ($date) {
        $seriesList = Series::getNamelist();
        $sql = "SELECT series, count, count_type, log FROM warehouse_count_daily WHERE count_date='$date'";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        // $nextDay = date("Y-m-d" ,strtotime("+1 day", strtotime($date)));
        $log = array("0800","1730");
        $ret = array();
        foreach($datas as $data) {
            $ret[$log[$data['log']]][$seriesList[$data['series']]][$data['count_type']] = $data['count'];
        }
        return $ret;
    }

    public function queryOperationReport ($date) {
        list($stime, $etime) = $this->reviseYearlyTime($date);
        $seriesList = Series::getNameList();

        $monthPoint = array();
        $detail = array('monthPoint'=>array(), 'datas'=>array());
        $total = array();
        $timeArray = $this->parseQueryTime($stime, $etime, 'yearly');
        $pointArray = array('assembly' => '上线', 'warehouse'=>'入库', 'distribute'=>'出库');
        foreach($seriesList as $series => $seriesName) {
            $detail['datas'][$seriesName] = array();
            $total[$seriesName] = array();
            foreach($pointArray as $point=>$pointName) {
                $detail['datas'][$seriesName][$pointName] = array();
                $total[$seriesName][$pointName] = 0;
            }
        }

        foreach($timeArray as $queryTime) {
            foreach($pointArray as $point=>$pointName) {
                $count = $this->countCarByPoint ($queryTime['stime'], $queryTime['etime'], $point);
                foreach($seriesList as $series => $seriesName) {
                    $detail['datas'][$seriesName][$pointName][] = $count[$series];
                    $total[$seriesName][$pointName] += $count[$series];
                }
            }
            $detail['monthPoint'][] = $queryTime['point'];
        }

        return array('detail'=>$detail, 'total'=>$total);
    }

    public function queryDistributionNetworkReport ($date) {
        list($sMonth, $eMonth) = $this->reviseDailyMonth($date);
        list($sYear, $eYear) = $this->reviseDailyYear($date);

        $seriesList = Series::getNameList();
        $nets = ['net-blue'=>'蓝网', 'net-red'=>'红网'];
        $ret = array();
        foreach($seriesList as $series => $seriesName) {
            $saleDistributorCountMonth = array();
            $saleDistributorCountAll = array();

            $orderDistributorCountMonth = array();

            $orderDistributorCountYear = array();

            $orderDistributorCountAll = array();

            $stockDistributorCount = array();

            foreach($nets as $net=>$netName) {
                $sale[$net] = array();
                $saleCountMonth[$net] = 0;
                $orderMonth[$net] = array();
                $orderYear[$net] = array();
                $stock[$net] = array();
                $stockCount[$net] = 0;
            }
            $sale['total']['count'] = 0;
            $orderMonth['total']['count'] = 0;
            $orderYear['total']['count'] = 0;
            $stock['total']['count'] = 0;
            foreach($nets as $net => $netName) {
                $saleCountMonth[$net] = $this->querySaleCount($sMonth, $eMonth, $series, $netName);
                $sale['total']['count'] += $saleCountMonth[$net];
                $saleDistributorCountMonth[$net] = $this->querySaleDistributorCount($sMonth, $eMonth, $series, $netName);
                $saleDistributorCountAll[$net] = $this->querySaleDistributorCount('', '', $series, $netName);


                $orderCountMonth[$net] = $this->queryOrderCount($sMonth, $eMonth, $series, $netName);
                if(empty($orderCountMonth[$net])) {
                    $orderCountMonth[$net] = 0;
                }
                $orderMonth['total']['count'] += $orderCountMonth[$net];
                $orderDistributorCountMonth[$net] = $this->queryOrderDistributorCount($sMonth, $eMonth, $series, $netName);

                $orderCountYear[$net] = $this->queryOrderCount($sYear, $eYear, $series, $netName);
                if(empty($orderCountYear[$net])) {
                    $orderCountYear[$net] = 0;
                }
                $orderYear['total']['count'] += $orderCountYear[$net];
                $orderDistributorCountYear[$net] = $this->queryOrderDistributorCount($sYear, $eYear, $series, $netName);

                $orderDistributorCountAll[$net] = $this->queryOrderDistributorCount('', '', $series, $netName);

                $stockCount[$net] = $this->queryStockCount($date, $series, $netName);
                $stock['total']['count'] += $stockCount[$net];
                $stockDistributorCount[$net] = $this->queryStockDistributorCount($sMonth, $eMonth, $series, $netName);
                $stockDistributorCountAll[$net] = $this->queryStockDistributorCount('', '', $series, $netName);
            }

            foreach($nets as $net=>$netName) {

                $sale[$net]['count'] = $saleCountMonth[$net];
                $sale[$net]['rate'] = empty($sale['total']['count']) ? "-" : round($saleCountMonth[$net] / $sale['total']['count'], 3) * 100 . "%";
                $sale[$net]['distributor_count'] = $saleDistributorCountMonth[$net];
                $sale[$net]['distributor_capacity'] = empty($saleDistributorCountMonth[$net]) ? "-" : round($saleCountMonth[$net] / $saleDistributorCountMonth[$net]);
                $sale[$net]['distributor_total'] = $saleDistributorCountAll[$net];

                $orderMonth[$net]['count'] = $orderCountMonth[$net];
                $orderMonth[$net]['rate'] = empty($orderMonth['total']['count']) ? "-" : round($orderCountMonth[$net] / $orderMonth['total']['count'] , 3) * 100 . "%";
                $orderMonth[$net]['distributor_count'] = $orderDistributorCountMonth[$net];
                $orderMonth[$net]['distributor_capacity'] = empty($orderDistributorCountMonth[$net]) ? "-" : round($orderCountMonth[$net] / $orderDistributorCountMonth[$net]);
                $orderMonth[$net]['distributor_total'] = $orderDistributorCountAll[$net];


                $orderYear[$net]['count'] = $orderCountYear[$net];
                $orderYear[$net]['rate'] = empty($orderYear['total']['count']) ? "-" : round($orderCountYear[$net] / $orderYear['total']['count'] , 3) * 100 . "%";
                $orderYear[$net]['distributor_count'] = $orderDistributorCountYear[$net];
                $orderYear[$net]['distributor_capacity'] = empty($orderDistributorCountYear[$net]) ? "-" : round($orderCountYear[$net] / $orderDistributorCountYear[$net]);
                $orderYear[$net]['distributor_total'] = $orderDistributorCountAll[$net];

                $stock[$net]['count'] = $stockCount[$net];
                $stock[$net]['rate'] = empty($stock['total']['count']) ? "-" : round($stockCount[$net] / $stock['total']['count'], 3) * 100 . "%";
                $stock[$net]['distributor_count'] = $stockDistributorCount[$net];
                $stock[$net]['distributor_capacity'] = empty($stockDistributorCount[$net]) ? "-" : round($stockCount[$net] / $stockDistributorCount[$net]);
                $stock[$net]['distributor_total'] = $stockDistributorCountAll[$net];
            }

            $detail[$series]['月销量'] =  $sale;
            $detail[$series]['月新订单'] =  $orderMonth;
            $detail[$series]['年新订单'] =  $orderYear;
            $detail[$series]['渠道库存'] =  $stock;
        }

        return $detail;
    }

    public function queryStockCount ($date, $series="", $distributionNetwork="", $pdType="") {
        $sql = "SELECT SUM(count) FROM view_sell_stock_daily WHERE DATE(create_time)>='$date'";
        if(!empty($series)) {
            $sql .= " AND series='$series'";
        }
        if(!empty($distributionNetwork)) {
            $sql .= " AND distribution_network='$distributionNetwork'";
        }
        if(!empty($pdType)) {
            $sql .= " AND planning_division_type_name='$pdType'";
        }
        $count = Yii::app()->db->createCommand($sql)->queryScalar();
        return $count;
    }

    public function queryStockDistributorCount ($stime="", $etime="", $series="", $distributionNetwork="") {
        $conditions = array();
        if(!empty($stime)) {
            $stime = substr($stime, 0, 10);
            $conditions[] = "DATE(create_time)>='$stime'";
        }
        if(!empty($etime)) {
            $etime = substr($etime, 0, 10);
            $conditions[] = "DATE(create_time)<='$etime'";
        }
        if(!empty($series)) {
            $conditions[] = "series='$series'";
        }
        if(!empty($distributionNetwork)) {
            $conditions[] = "distribution_network='$distributionNetwork'";
        }
        $condition = empty($conditions) ? "" : "WHERE " . join(" AND ", $conditions);

        $sql = "SELECT COUNT(DISTINCT distributor_name) FROM sell_stock_daily $condition";
        $count = Yii::app()->db->createCommand($sql)->queryScalar();
        return $count;
    }

    public function querySaleCount ($stime, $etime, $series="", $distributionNetwork="", $pdType="") {
        $sql = "SELECT COUNT(*) FROM view_sale WHERE register_time>='$stime' AND register_time<'$etime'";
        if(!empty($series)) {
            $sql .= " AND series='$series'";
        }
        if(!empty($distributionNetwork)) {
            $sql .= " AND distribution_network='$distributionNetwork'";
        }
        if(!empty($pdType)) {
            $sql .= " AND planning_division_type_name='$pdType'";
        }

        $count = Yii::app()->db->createCommand($sql)->queryScalar();

        return $count;
    }

    public function queryOrderCount ($stime, $etime, $series="", $distributionNetwork="" ) {
        $sql = "SELECT SUM(amount) FROM sell_order_view WHERE audit_time>='$stime' AND audit_time<'$etime' AND audit_status=1";
        if(!empty($series)) {
            $sql .= " AND series='$series'";
        }
        if(!empty($distributionNetwork)) {
            $sql .= " AND distribution_network='$distributionNetwork'";
        }

        $count = Yii::app()->db->createCommand($sql)->queryScalar();

        return $count;
    }

    public function querySaleDistributorCount ($stime="", $etime="", $series="", $distributionNetwork="") {
        $conditions = array();
        if(!empty($stime)) {
            $conditions[] = "register_time>='$stime'";
        }
        if(!empty($etime)) {
            $conditions[] = "register_time<'$etime'";
        }
        if(!empty($series)) {
            $conditions[] = "series='$series'";
        }
        if(!empty($distributionNetwork)) {
            $conditions[] = "distribution_network='$distributionNetwork'";
        }
        $condition = empty($conditions) ? "" : "WHERE " . join(" AND ", $conditions);

        $sql = "SELECT COUNT(DISTINCT distributor_code) FROM sell_sale_view $condition";
        $count = Yii::app()->db->createCommand($sql)->queryScalar();

        return $count;
    }

    public function queryOrderDistributorCount ($stime="", $etime="", $series="", $distributionNetwork="") {
        $conditions = array();
        if(!empty($stime)) {
            $conditions[] = "audit_time>='$stime'";
        }
        if(!empty($etime)) {
            $conditions[] = "audit_time<'$etime'";
        }
        if(!empty($series)) {
            $conditions[] = "series='$series'";
        }
        if(!empty($distributionNetwork)) {
            $conditions[] = "distribution_network='$distributionNetwork'";
        }
        $conditions[] = "audit_status=1";
        $condition = empty($conditions) ? "" : "WHERE " . join(" AND ", $conditions);

        $sql = "SELECT COUNT(DISTINCT distributor_code) FROM sell_order_view $condition";
        $count = Yii::app()->db->createCommand($sql)->queryScalar();

        return $count;
    }

    public function countCarByPoint ($stime,$etime,$point="assembly",$line="") {
        $point .= "_time";
        $sql = "SELECT series, COUNT(id) as `count` FROM car WHERE $point>='$stime' AND $point<'$etime'";
        if(!empty($line)){
            $sql .= " AND assembly_line='$line'";
        }
        $sql .= " GROUP BY series";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();

        $seriesArray = Series::getArray();
        $count = array();
        foreach($seriesArray as $series){
            $count[$series] = 0;
        }
        foreach($datas as $data){
            $count[$data['series']] = intval($data['count']);
        }

        return $count;
    }

    public function queryEndSale ($sDate, $eDate, $series) {
        list($stime, $etime) = $this->reviseDate2Date($sDate, $eDate);
        $timeArray = $this->parseDate($stime, $etime);
        $pdTypeList = $this->pdTypeList($series);
        $saleSeries = $this->querySaleCount($stime, $etime, $series, "");
        $stockSeries = $this-> queryStockCount ($eDate, $series, "");

        $all = array();
        $sub = array('time'=>array(), 'data'=>array());
        foreach ($pdTypeList as $pdType) {
            $sub['data'][$pdType] = array();
        }
        $sub['data']['total'] = array();
        foreach ($pdTypeList as $pdType) {

            $saleTotal = $this->querySaleCount($stime, $etime, $series, "", $pdType);
            $stockTotal = $this->queryStockCount($eDate, $series, "", $pdType);

            $saleRate = empty($saleSeries) ? "-" : round($saleTotal/$saleSeries, 2) * 100 . "%";
            $stockRate = empty($stockSeries) ? "-" : round($stockTotal/$stockSeries, 2) * 100 . "%";

            $all[$pdType]['stock'] = $stockTotal . " / " . $stockRate;
            $all[$pdType]['sale'] = $saleTotal . " / " . $saleRate;

            foreach ($timeArray as $time) {
                $sub['data'][$pdType][] = $this->querySaleCount($time['stime'], $time['etime'], $series, "", $pdType);
            }
        }
        foreach ($timeArray as $time) {
            $sub['time'][] = $time['point'];
            $sub['data']['total'][] = $this->querySaleCount($time['stime'], $time['etime'], $series);
        }
        $all['合计']['stock'] = $stockSeries;
        $all['合计']['sale'] = $saleSeries;

        return array('all'=>$all, 'sub'=>$sub ,'timeArray'=>$timeArray);
    }


    public function pdTypeList ($series) {
        $sql = "SELECT DISTINCT planning_division_type_name FROM car_type_map WHERE series='$series' AND planning_division_statistics=1 ORDER BY planning_division_type_name ASC";
        $list = Yii::app()->db->createCommand($sql)->queryColumn();
        return $list;
    }

    public function getPlanningDivisionTypeList () {
        $sql = "SELECT series, planning_division_type_name as pd_type_name FROM car_type_map GROUP BY series,planning_division_type_name ORDER BY series, planning_division_type_name ASC";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $list = array();
        foreach($datas as $data) {
            $list[$data['series']][] = $data['pd_type_name'];
        }

        return $list;
    }

    public function reviseDailyMonth ($date) {
        $d = strtotime($date);
        $sMonth = date("Y-m-01 08:00:00", $d);
        $nextDay = strtotime('+1 day', $d);
        $eDate = date("Y-m-d 08:00:00", $nextDay);

        return array($sMonth, $eDate);
    }

    public function reviseDailyYear ($date) {
        $d = strtotime($date);
        $sYear = date("Y-01-01 08:00:00", $d);
        $nextDay = strtotime('+1 day', $d);
        $eDate = date("Y-m-d 08:00:00", $nextDay);

        return array($sYear, $eDate);
    }

    public function reviseTime ($date, $timespan) {
        switch($timespan) {
            case "daily":
                list($stime, $etime) = $this->reviseDailyTime($date);
                break;
            case "monthly":
                list($stime, $etime) = $this->reviseMonthlyTime($date);
                break;
            case "yearly":
                list($stime, $etime) = $this->reviseYearlyTime($date);
                break;
            default:
                list($stime, $etime) = $this->reviseDailyTime($date);
        }
        return array($stime, $etime);
    }

    public function reviseDailyTime($date) {
        $d = strtotime($date);
        $nextDay = strtotime('+1 day', $d);
        $stime = date("Y-m-d 08:00:00", $d);
        $etime = date("Y-m-d 08:00:00", $nextDay);

        return array($stime, $etime);
    }

    public function reviseMonthlyTime($date) {
        $d = strtotime($date);
        $nextM = strtotime('first day of next month', $d);
        $stime = date("Y-m-01 08:00:00", $d);
        $etime = date("Y-m-01 08:00:00", $nextM);

        return array($stime, $etime);
    }

    private function reviseYearlyTime ($date) {
        $d = strtotime($date);
        $nextY = strtotime('+1 year', $d);
        $stime = date("Y-01-01 08:00:00", $d);
        $etime = date("Y-01-01 08:00:00", $nextY);

        return array($stime, $etime);
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

    public function reviseDate2Date ($sDate, $eDate) {
        $stime = $sDate . ' 08:00:00';
        $etime = date("Y-m-d H:i:s", strtotime('+1 day', strtotime($eDate . ' 08:00:00')));
        return array($stime, $etime);
    }

    public function parseDate ($stime, $etime) {
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
}