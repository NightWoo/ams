<?php
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.OrderConfigAR');
Yii::import('application.models.AR.DistributorAR');
Yii::import('application.models.AR.CarTypeMapAR');
Yii::import('application.models.AR.LaneAR');
Yii::import('application.models.AR.CarAR');
Yii::import('application.models.AR.WarehouseAR');
Yii::import('application.models.Order');

class OrderSeeker
{
	public function __construct(){
	}

	private static $COLD_RESISTANT = array('非耐寒','耐寒');

	public function getOriginalOrders($orderNumber) {
        if(empty($orderNumber)){
        	throw new Exception ('订单号不能为空');
        }
        $sql = "SELECT DATAK40_DGMXID AS order_detail_id, DATAK40_JXSMC AS distributor, DATAK40_DGDH AS order_number, DATAK40_CXMC AS series, DATAK40_CLDM AS car_type_code, DATAK40_CLXH AS sell_car_type, DATAK40_BZCX AS car_model, DATAK40_CXSM AS car_type_description, DATAK40_CLYS AS sell_color, DATAK40_VINMYS AS color, DATAK40_DGSL AS amount, DATAK40_XZPZ AS options, DATAK40_DDXZ AS order_nature, DATAK40_DDLX AS cold_resistant, DATAK40_NOTE AS remark, DATAK40_JZPZ AS additions, DATAK40_SSDW AS production_base, DATAK40_JXSDM AS distributor_code
                FROM DATAK40_CLDCKMX
                WHERE DATAK40_SSDW = '3' AND DATAK40_DGDH = '$orderNumber'";
		
		$tdsSever = Yii::app()->params['tds_SELL'];
        $tdsDB = Yii::app()->params['tds_dbname_BYDDATABASE'];
        $tdsUser = Yii::app()->params['tds_SELL_username'];
        $tdsPwd = Yii::app()->params['tds_SELL_password'];
       
        $orders = $this->mssqlQuery($tdsSever, $tdsUser, $tdsPwd, $tdsDB, $sql);

        foreach($orders as &$order){
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

	public function getSellOrderDetail($orderDetailId) {
		if(empty($orderDetailId)){
			return null;
		}
		$sql = "SELECT DATAK40_DGMXID AS order_detail_id, DATAK40_JXSMC AS distributor, DATAK40_DGDH AS order_number, DATAK40_CXMC AS series, DATAK40_CLDM AS car_type_code, DATAK40_CLXH AS sell_car_type, DATAK40_BZCX AS car_model, DATAK40_CXSM AS car_type_description, DATAK40_CLYS AS sell_color, DATAK40_VINMYS AS color, DATAK40_DGSL AS amount, DATAK40_XZPZ AS options, DATAK40_DDXZ AS order_nature, DATAK40_DDLX AS cold_resistant, DATAK40_NOTE AS remark, DATAK40_JZPZ AS additions, DATAK40_SSDW AS production_base, DATAK40_JXSDM AS distributor_code
                FROM DATAK40_CLDCKMX
                WHERE DATAK40_SSDW = '3' AND DATAK40_DGMXID = $$orderDetailId";
		
		$tdsSever = Yii::app()->params['tds_SELL'];
        $tdsDB = Yii::app()->params['tds_dbname_BYDDATABASE'];
        $tdsUser = Yii::app()->params['tds_SELL_username'];
        $tdsPwd = Yii::app()->params['tds_SELL_password'];
       
        $datas = $this->mssqlQuery($tdsSever, $tdsUser, $tdsPwd, $tdsDB, $sql);

        $order = $datas[0];

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

        return $order;
	}

	public function getSpecialOrders($specialNumber){
		if(empty($specialNumber)){
        	throw new Exception ('订单号不能为空');
        }
        $specialNumber = trim($specialNumber);
		$specialNumber = strtoupper($specialNumber);

		$sql = "SELECT COUNT(id) AS amount, series, type AS car_type, order_config_id, order_config_name,cold_resistant, color, special_order,remark,export_country,mark_clime
				FROM view_car_info_order_config 
				WHERE UPPER(special_order) = '$specialNumber' OR UPPER(remark) LIKE '%$specialNumber%' 
				GROUP BY series, type,order_config_id, order_config_name,cold_resistant, color";
		$orders = Yii::app()->db->createCommand($sql)->queryAll();

		foreach($orders as &$order){
			$sumSql = "SELECT SUM(amount)
						FROM `order` 
						WHERE UPPER(order_number)='$specialNumber' AND series='{$order['series']}' AND car_type='{$order['car_type']}' AND order_config_id='{$order['order_config_id']}' AND cold_resistant='{$order['cold_resistant']}' AND color='{$order['color']}'";
			$amountSum = Yii::app()->db->createCommand($sumSql)->queryScalar();
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

	public function query($standbyDate, $orderNumber, $distributor, $status='all', $series='', $orderBy='lane_id,priority,`status`', $standbyDateEnd='', $boardNumber='') {

		$statusArray = $this->parseStatus($status);
		$condition = "`status` IN(" . join(",", $statusArray) . ")";
		
		if(!empty($standbyDate)){
			if(empty($standbyDateEnd)){
				$standbyDateEnd = $standbyDate;
			}
			$condition .= " AND (standby_date>='$standbyDate' AND standby_date<='$standbyDateEnd')";
		}

		if(!empty($orderNumber)){
			$condition .= " AND order_number LIKE '%$orderNumber'";
		}

		if(!empty($distributor)){
			$condition .= " AND distributor_name LIKE '%$distributor%'";
		}

		if(!empty($series)){
			$condition .= " AND series='$series'";
		}

		if(!empty($boardNumber)){
			$condition .= " AND board_number LIKE '%$boardNumber'";
		}
		
		$sql = "SELECT id, order_number, board_number, priority, standby_date, amount, hold, count, series, car_type, color, cold_resistant, order_config_id, distributor_name, lane_id, remark, status, create_time, activate_time, standby_finish_time, out_finish_time, is_printed, lane_release_time, to_count, carrier FROM bms.order WHERE $condition ORDER BY $orderBy ASC";
		$orderList = Yii::app()->db->createCommand($sql)->queryAll();
		if(empty($orderList)){
			throw new Exception("查无订单");
		}

		foreach($orderList as &$detail) {
			if(!empty($detail['order_config_id'])){
				$detail['order_config_name'] = OrderConfigAR::model()->findByPk($detail['order_config_id'])->name;
			}
			$detail['car_model'] = CarTypeMapAR::model()->find("car_type=?", array($detail['car_type']))->car_model;
			
			$detail['lane_name'] = '';
			$lane = LaneAR::model()->findByPk($detail['lane_id']);
			if(!empty($lane)) $detail['lane_name'] = $lane->name;
			if(!empty($detail['order_config_name'])){
				$detail['car_type_config'] = $detail['car_model']. "/" . $detail['order_config_name'];
			}else {
				$detail['car_type_config'] = $detail['car_model'];
			}
			if($detail['cold_resistant'] == 1){
				$detail['cold'] = '耐寒';
			} else {
				$detail['cold'] = '非耐寒';
			}

			$detail['remain'] =  $detail['amount']; - $detail['hold'];
			
			$detail['standby_last'] = 0;
			$detail['out_last'] = 0;
			$detail['lane_last'] = 0;
			if($detail['activate_time'] > '0000-00-00 00:00:00'){
				if($detail['standby_finish_time'] === '0000-00-00 00:00:00'){
					$detail['standby_last'] = (strtotime(date('Y-m-d H:i:s')) - strtotime($detail['activate_time'])) / 3600;

				}else{
					$detail['standby_last'] = (strtotime($detail['standby_finish_time']) - strtotime($detail['activate_time'])) / 3600;
					if($detail['out_finish_time'] === '0000-00-00 00:00:00'){
						// $detail['out_last'] = (strtotime(date('Y-m-d H:i:s')) - strtotime($detail['standby_finish_time'])) / 3600;
						$detail['out_last'] = (strtotime(date('Y-m-d H:i:s')) - strtotime($detail['activate_time'])) / 3600;
					}else{
						// $detail['out_last'] = (strtotime($detail['out_finish_time']) - strtotime($detail['standby_finish_time'])) / 3600;
						$detail['out_last'] = (strtotime($detail['out_finish_time']) - strtotime($detail['activate_time'])) / 3600;
						if($detail['lane_release_time'] === '0000-00-00 00:00:00'){
							$detail['lane_last'] = (strtotime(date('Y-m-d H:i:s')) - strtotime($detail['out_finish_time'])) / 3600;
						} else {
							$detail['lane_last'] = (strtotime($detail['lane_release_time']) - strtotime($detail['out_finish_time'])) / 3600;
						}
					}
				}
			}
			$detail['standby_last'] = round($detail['standby_last'],1);
			$detail['out_last'] = round($detail['out_last'],1);
			$detail['lane_last'] = round($detail['lane_last'],1);
		}

		return $orderList;
	}

	public function queryBoardOrders($standbyDate, $orderNumber, $distributor, $status='all', $series='', $orderBy='lane_id,priority,`status`', $standbyDateEnd='', $boardNumber=''){
		$orders = $this->query($standbyDate, $orderNumber, $distributor, $status, $series, $orderBy, $standbyDateEnd, $boardNumber);
		$boards = array();

		foreach($orders as &$order){
			$need = $order['amount'] - $order['hold'];
			$order['short'] = 0;
			if($need > 0){
				$sql = "SELECT id FROM car_config WHERE order_config_id = {$order['order_config_id']}";
	        	$configId = Yii::app()->db->createCommand($sql)->queryColumn();
	        	$configId = "(" . join(',', $configId) . ")";

				$matchCondition = "warehouse_id>1 AND warehouse_id< 1000 AND series=? AND color=? AND cold_resistant=? AND config_id IN $configId AND warehouse_time >'0000-00-00 00:00:00'";
				$values = array($order['series'], $order['color'], $order['cold_resistant']);
				$matchCount = CarAR::model()->count($matchCondition, $values);
				
				$alreadyNeed = 0;
				if($order['status'] = 1){
					$sameSql = "SELECT SUM(amount) AS amount_sum, SUM(hold) AS hold_sum FROM `order` WHERE standby_date='{$order['standby_date']}' AND `status` =1 AND to_count=1 AND (priority<{$order['priority']} OR (priority={$order['priority']} AND id<{$order['id']})) AND series='{$order['series']}' AND color='{$order['color']}' AND cold_resistant={$order['cold_resistant']} AND order_config_id={$order['order_config_id']} AND id<>{$order['id']}";
				} else {
					$sameSql = "SELECT SUM(amount) AS amount_sum, SUM(hold) AS hold_sum FROM `order` WHERE standby_date='{$order['standby_date']}' AND `status` =1 AND to_count=1 AND series='{$order['series']}' AND color='{$order['color']}' AND cold_resistant={$order['cold_resistant']} AND order_config_id={$order['order_config_id']}";
				}
				$same = Yii::app()->db->createCommand($sameSql)->queryRow();
				$alreadyNeed = $same['amount_sum'] - $same['hold_sum'];

				$order['short'] = $matchCount - $alreadyNeed - $need;
				if($order['short']<(-$need)){
					$order['short'] = -$need;
				}
			}

			if(empty($boards[$order['board_number']])){
				$boards[$order['board_number']] = array(
					'boardNumber' => $order['board_number'],
					'boardAmount' => 0,
					'boardHold' => 0,
					'boardCount' => 0,
					'orders' => array(),
				);
			}
			$boards[$order['board_number']]['boardAmount'] += $order['amount'];
			$boards[$order['board_number']]['boardHold'] += $order['hold'];
			$boards[$order['board_number']]['boardCount'] += $order['count'];
			$boards[$order['board_number']]['orders'][] = $order;
		}
		return $boards;
	}

	public function queryLaneOrders(){
		$condition = "lane_status=1 AND lane_id>0";
		$orderBy='board_number,lane_id';
		$sql = "SELECT id, order_number, board_number, priority, standby_date, amount, hold, count, series, car_type, color, cold_resistant, order_config_id, distributor_name, lane_id, lane_status, status, create_time, activate_time, standby_finish_time, out_finish_time, is_printed FROM bms.order WHERE $condition ORDER BY $orderBy ASC";
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		
		$countSql = "SELECT COUNT(DISTINCT lane_id) FROM `order` WHERE $condition";
		$laneCount = Yii::app()->db->createCommand($countSql)->queryScalar();

		$boards = array();
		$lanes = array();

		foreach ($orders as $order) {
			if(empty($boards[$order['board_number']])){
				$boards[$order['board_number']] = array(
					'boardNumber' => $order['board_number'],
					'boardAmount' => 0,
					'boardHold' => 0,
					'boardCount' => 0,
					'boardActivateTime' => '0000-00-00 00:00:00',
					'boardStandbyFinishTime' => '0000-00-00 00:00:00',
					'boardOutFinishTime' => '0000-00-00 00:00:00',
					'lane' => array(),
					'laneNum' => 0,
					// 'orders' => array(),
				);
			}

			if(empty($boards[$order['board_number']]['lane'][$order['lane_id']])){
				$laneName = '';
				$lane = LaneAR::model()->findByPk($order['lane_id']);
				if(!empty($lane)){
					$laneName = $lane->name;
				}
				$boards[$order['board_number']]['lane'][$order['lane_id']] = array(
					'laneName' => $laneName,
					'laneAmount' => 0,
					'laneHold' => 0,
					'laneCount' => 0,
					'orders' => array(),
				);
			}

			$boards[$order['board_number']]['boardAmount'] += $order['amount'];
			$boards[$order['board_number']]['boardHold'] += $order['hold'];
			$boards[$order['board_number']]['boardCount'] += $order['count'];
			$boards[$order['board_number']]['lane'][$order['lane_id']]['laneAmount'] += $order['amount'];
			$boards[$order['board_number']]['lane'][$order['lane_id']]['laneHold'] += $order['hold'];
			$boards[$order['board_number']]['lane'][$order['lane_id']]['laneCount'] += $order['count'];
			$boards[$order['board_number']]['lane'][$order['lane_id']]['orders'][] = $order;

			$laneNum = sizeof($boards[$order['board_number']]['lane']);
			$boards[$order['board_number']]['laneNum'] = $laneNum;
			
			if($order['activate_time'] >'0000-00-00 00:00:00'){
				if($boards[$order['board_number']]['boardActivateTime'] === '0000-00-00 00:00:00' || $order['activate_time'] < $boards[$order['board_number']]['boardActivateTime']){
					$boards[$order['board_number']]['boardActivateTime'] = $order['activate_time'];
				}
			}
			if($order['standby_finish_time'] > $boards[$order['board_number']]['boardStandbyFinishTime']){
				$boards[$order['board_number']]['boardStandbyFinishTime'] = $order['standby_finish_time'];
			}
			if($order['out_finish_time'] > $boards[$order['board_number']]['boardOutFinishTime']){
				$boards[$order['board_number']]['boardOutFinishTime'] = $order['out_finish_time'];
			}
		}

		foreach($boards as &$board){
			foreach($board['lane'] as $lane){
				foreach($lane['orders'] as $order){
					if($order['standby_finish_time'] === '0000-00-00 00:00:00'){
						$boards[$order['board_number']]['boardStandbyFinishTime'] = '0000-00-00 00:00:00';
						break;
					}
				}
				foreach($lane['orders'] as $order){
					if($order['out_finish_time'] === '0000-00-00 00:00:00'){
						$boards[$order['board_number']]['boardOutFinishTime'] = '0000-00-00 00:00:00';
						break 2;
					}
				}
			}
			if($board['boardStandbyFinishTime'] === '0000-00-00 00:00:00'){
				$board['boardStandbyLast'] =(strtotime(date('Y-m-d H:i:s')) - strtotime($board['boardActivateTime'])) / 3600;
				$board['boardOutLast'] =0;
			} else {
				$board['boardStandbyLast'] =(strtotime($board['boardStandbyFinishTime']) - strtotime($board['boardActivateTime'])) / 3600;
				if($board['boardOutFinishTime'] === '0000-00-00 00:00:00'){
					// $board['boardOutLast'] =(strtotime(date('Y-m-d H:i:s')) - strtotime($board['boardStandbyFinishTime'])) / 3600;
					$board['boardOutLast'] =(strtotime(date('Y-m-d H:i:s')) - strtotime($board['boardActivateTime'])) / 3600;
				} else {
					// $board['boardOutLast'] =(strtotime($board['boardOutFinishTime']) - strtotime($board['boardStandbyFinishTime'])) / 3600;
					$board['boardOutLast'] =(strtotime($board['boardOutFinishTime']) - strtotime($board['boardActivateTime'])) / 3600;
				}
			}
			$board['boardStandbyLast'] = round($board['boardStandbyLast'] ,1);
			$board['boardOutLast'] = round($board['boardOutLast'] ,1);

		}

		return array($boards, $laneCount);
	}

	public function queryPeriod($startDate, $endDate, $status = 'all'){
		if(empty($startDate)){
			throw new Exception("起始时间不能为空");
		} else {
			if(empty($endDate)){
				$endDate = $startDate;
			}
		}
		$statusArray = $this->parseStatus($status);
		$statusCondition = "`status` IN(" . join(",", $statusArray) . ")";

		$queryTimes = $this->parseQueryTime($startDate, $endDate, $status);
		$detail = array();
		$dataSeriesX = array();
		$dataSeriesY = array();
		$retTotal = array(
			'warehousePeriodAvg' => 0,
			'transportPeriodAvg' => 0,
			'totalPeriodAvg' => 0,
			'boardCountSum' => 0,
		);
		$boardCountSum = 0;
		$warehousePeriodSum = 0;
		$transportPeriodSum =  0;
		$totalPeriodSum = 0;

		foreach($queryTimes as $queryTime){
			$ss = $queryTime['stime'];
			$ee = $queryTime['etime'];
			$cc = array();
			$cc[] = $statusCondition;
			if(!empty($ss)) {
				$cc[] = "activate_time>='$ss'";
			}
			if(!empty($ee)) {
				$cc[] = "activate_time<'$ee'";
			}
			$condition = join(' AND ', $cc);
			if(!empty($condition)) {
				$condition = 'WHERE ' . $condition;
			}

			$sql = "SELECT 	board_number, 
							MIN(activate_time) AS min_activate, 
							MAX(activate_time) AS max_activate, 
							MIN(out_finish_time) AS min_out, 
							MAX(out_finish_time) AS max_out,
							MIN(lane_release_time) AS min_release,
							MAX(lane_release_time) AS max_relaese 
					FROM 	`order` $condition
					GROUP BY board_number";
			$countSql = "SELECT COUNT(DISTINCT board_number) FROM `order` $condition"; 

			$datas = Yii::app()->db->createCommand($sql)->queryAll();
			$boardCount = Yii::app()->db->createCommand($countSql)->queryScalar();
			$boardCountSum += $boardCount;
			$warehousePeriod = 0;
			$transportPeriod = 0;
			$temp = array();
			foreach($datas as &$data){
				//获得每板的激活、完成、释放这三个周期时间点
				$boardActivate = $data['min_activate'];
				if($data['min_out'] === '0000-00-00 00:00:00'){
					$boardOutFinish = date('Y-m-d H:i:s');
				} else {
					$boardOutFinish = $data['max_out'];
				}
				if($data['min_release'] === '0000-00-00 00:00:00'){
					$boardRelease = date('Y-m-d H:i:s');
				} else {
					$boardRelease = $data['max_relaese'];
				}

				//计算成品库周期，出库完成时间-激活时间
				$data['warehousePeriod'] = strtotime($boardOutFinish) - strtotime($boardActivate);
				$warehousePeriod += $data['warehousePeriod'] ;
				//计算储运周期，车道释放时间-完成时间
				$data['transportPeriod'] = strtotime($boardRelease) - strtotime($boardOutFinish);
				$transportPeriod += $data['transportPeriod'];
			}
			$totalPeriod = $warehousePeriod + $transportPeriod;
			if($boardCount == 0){
				$totalPeriodAvg = null;
				$warehousePeriodAvg = null;
				$transportPeriodAvg = null;
			} else {
				$totalPeriodAvg = round((($warehousePeriod + $transportPeriod) / $boardCount / 3600), 1);
				$warehousePeriodAvg = round(($warehousePeriod / $boardCount / 3600), 1);
				$transportPeriodAvg = round(($transportPeriod / $boardCount / 3600), 1);
			}

			$dataSeriesX[] = $queryTime['point'];
			$dataSeriesY['成品库周期'][] = $warehousePeriodAvg;
			$dataSeriesY['储运周期'][] = $transportPeriodAvg;
			$dataSeriesY['totalPeriod'][] = $totalPeriodAvg;

			$temp['成品库周期'] = $warehousePeriodAvg;
			$temp['储运周期'] = $transportPeriodAvg;
			$temp['totalPeriod'] = $totalPeriodAvg;
			$detail[] = array_merge(array('time' => $queryTime['point']), $temp);

			$warehousePeriodSum +=  $warehousePeriod;
			$transportPeriodSum +=  $transportPeriod;
			$totalPeriodSum +=  $totalPeriod;
			
		}
			$retTotal['boardCountSum'] = $boardCountSum;
			if($boardCountSum == 0){
				$retTotal['warehousePeriodAvg'] = null;
				$retTotal['transportPeriodAvg'] = null;
				$retTotal['totalPeriodAvg'] =  null;
			}else{
				$retTotal['warehousePeriodAvg'] = round(($warehousePeriodSum / $boardCountSum / 3600), 1);
				$retTotal['transportPeriodAvg'] = round(($transportPeriodSum / $boardCountSum / 3600), 1);
				$retTotal['totalPeriodAvg'] =  round((($warehousePeriodSum + $transportPeriodSum) / $boardCountSum / 3600), 1);
			}

		return array(
			'periodSeries' => array('储运周期','成品库周期'),
			'detail' => $detail,
			'total' => $retTotal,
			'series' => array(
							'x' => $dataSeriesX,
							'y' => $dataSeriesY,
						),
		);
	}

	public function matchQuery($series, $carType, $orderConfigId, $color, $coldResistant, $date) {

		if(empty($date)){
			$date = date('Y-m-d');
		}

		$conditions = array();
		$conditions['order'] = "status=1 AND standby_date='$date' AND order_config_id='$orderConfigId' AND hold<amount AND count<amount";
		$conditions['car'] = "series='$series' AND color='$color' AND cold_resistant='$coldResistant'";

		$condition = join(' AND ', $conditions);

		$sql = "SELECT *
				  FROM bms.order
				 WHERE $condition
			  ORDER BY priority ASC";

		$order = OrderAR::model()->findBySql($sql);

		return $order;
	}

	public function getLaneInfo(){
		$laneSql = "SELECT id,name FROM lane";
		$lanes = Yii::app()->db->createCommand($laneSql)->queryAll();
		$laneArray = array();
		$laneInfo = array();
		$totalToPrint = 0;
		foreach($lanes as $lane){
			$laneArray[$lane['id']] = $lane['name'];
			$countSum = 0;
			$amountSum = 0;
			$toPrint = 0;
			$sql = "SELECT id,amount,hold,count,lane_id,`status`,is_printed 
					FROM `order` 
					WHERE lane_id='{$lane['id']}' AND (`status`=1 OR `status`=2) AND is_printed=0";
			$orders = Yii::app()->db->createCommand($sql)->queryAll();
			foreach($orders as $order){
				$countSum += $order['count'];
				$amountSum += $order['amount'];
				if($order['count'] == $order['amount']){
					++$toPrint;
					++$totalToPrint;
				}
			}
			$laneInfo[$lane['id']] = array(
					'name' => $lane['name'],
					'toPrint' => $toPrint,
					'countSum' => $countSum,
					'amountSum' => $amountSum,
			);
		}

		return array('totalToPrint'=>$totalToPrint, 'laneInfo'=>$laneInfo);
	}

	public function queryByBoard($boardNumber){
		$sql = "SELECT board_number,id as order_id,lane_id, order_number, distributor_name, amount, hold, count, series, car_type, color, cold_resistant, order_config_id
				FROM `order`
				WHERE board_number='$boardNumber' AND (`status`=1 OR `status`=2) AND is_printed=0";
		$orders = Yii::app()->db->createCommand($sql)->queryAll();

		$countSum = 0;
		$amountSum = 0;
		foreach($orders as &$order) {
			if(!empty($order['order_config_id'])){
				$order['order_config_name'] = OrderConfigAR::model()->findByPk($order['order_config_id'])->name;
			}
			$order['car_model'] = CarTypeMapAR::model()->find("car_type=?", array($order['car_type']))->car_model;
			
			$order['lane_name'] = '';
			$lane = LaneAR::model()->findByPk($order['lane_id']);
			if(!empty($lane)) $order['lane_name'] = $lane->name;
			if(!empty($order['order_config_name'])){
				$order['car_type_config'] = $order['car_model']. "/" . $order['order_config_name'];
			}else {
				$order['car_type_config'] = $order['car_model'];
			}
			if($order['cold_resistant'] == 1){
				$order['cold'] = '耐寒';
			} else {
				$order['cold'] = '非耐寒';
			}

			$order['remain'] = $order['amount']; - $order['hold'];

			$countSum += $order['count'];
			$amountSum += $order['amount'];
		}

		$remainTotal = $amountSum - $countSum;

		return array($orders, $remainTotal);
	}

	public function queryBoardInfo(){
		$boardArray = array();
		$boardInfo = array();
		$totalToPrint = 0;

		$sql = "SELECT board_number, id,amount,hold,count,lane_id,`status`,is_printed
				 FROM `order`
				 WHERE is_printed=0 AND (`status`=1 OR `status`=2)
				 ORDER BY board_number ASC";
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($orders as $order){
			if(!in_array($order['board_number'], $boardArray)){
				$boardArray[] = $order['board_number'];
				$boardInfo[$order['board_number']] = array(
					'toPrint' => 0,
					'countSum' => 0,
					'amountSum' => 0,
				);
			}
			$boardInfo[$order['board_number']]['countSum'] += $order['count'];
			$boardInfo[$order['board_number']]['amountSum'] += $order['amount'];
			if($order['count'] == $order['amount']){
				++$boardInfo[$order['board_number']]['toPrint'];
				++$totalToPrint;
			}
		}

		return array('boardArray'=>$boardArray, 'totalToPrint'=>$totalToPrint, 'boardInfo'=>$boardInfo);
	}

	public function queryOrderInBoardInfo(){
		$orderInBoardArray = array();
		$orderInBoardInfo = array();
		$totalToPrint = 0;

		$sql = "SELECT board_number,order_number, id,amount,hold,count,lane_id,`status`,is_printed
				 FROM `order`
				 WHERE is_printed=0 AND (`status`=1 OR `status`=2)
				 ORDER BY board_number ASC";
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($orders as &$order){
			$name = $order['order_number'] . '@' . $order['board_number'];
			if(!in_array($name, $orderInBoardArray)){
				$orderInBoardArray[] = $name;
				$orderInBoardInfo[$name] = array(
					'orderIdArray' => array(),
					'countSum'=> 0,
					'amountSum' => 0,
					'toPrint' => false,
				);
			}
			$orderInBoardInfo[$name]['orderIdArray'][] = $order['id'];
			$orderInBoardInfo[$name]['countSum'] += $order['count'];
			$orderInBoardInfo[$name]['amountSum'] += $order['amount'];
		}

		foreach($orderInBoardInfo as &$orderInboard){
			if($orderInboard['amountSum'] > 0 && $orderInboard['countSum'] === $orderInboard['amountSum']){
				++$totalToPrint;
				$orderInboard['toPrint'] = true;
			}
		}

		return array('orderInBoardArray' => $orderInBoardArray, 'totalToPrint'=>$totalToPrint, 'orderInBoardInfo'=>$orderInBoardInfo);
	}

	public function queryCarsById($orderId){
		$sql = "SELECT id as car_id,vin, order_id, series, type, config_id, cold_resistant,color, `status`, distribute_time, distributor_name, engine_code, old_wh_id 
				FROM car 
				WHERE order_id=$orderId ORDER BY distribute_time ASC";
		$cars = Yii::app()->db->createCommand($sql)->queryAll();
		$configName = $this->configNameList();
		foreach($cars as &$car){
			$car['type_config'] = $configName[$car['config_id']];
			$car['cold'] = self::$COLD_RESISTANT[$car['cold_resistant']];
			$car['old_row'] = '-';
			$oldRow = WarehouseAR::model()->findByPk($car['old_wh_id']);
			if(!empty($oldRow)){
				$car['old_row'] = $oldRow->row;
			}
			$car['standby_time'] = '-';
			$traceSql = "SELECT pass_time FROM node_trace WHERE node_id=96 AND car_id = {$car['car_id']} ORDER BY pass_time DESC";
			$car['standby_time'] = Yii::app()->db->createCommand($traceSql)->queryScalar();

			$car['standby_last'] = 0;
			if($car['distribute_time'] === '0000-00-00 00:00:00'){
				$car['standby_last'] = (strtotime(date('Y-m-d H:i:s')) - strtotime($car['standby_time'])) / 3600;
			} else {
				$car['standby_last'] = (strtotime($car['distribute_time']) - strtotime($car['standby_time'])) / 3600;
			}
			$car['standby_last'] = round($car['standby_last'],1);
		}
		return $cars;
	}

	public function queryCarsByIds($orderIds){
		$orderIds = "(" . join(',', $orderIds) . ")";
		$sql = "SELECT id as car_id,vin, order_id, series, type, config_id, cold_resistant,color, `status`, distribute_time, distributor_name, engine_code,lane_id
				FROM car 
				WHERE order_id IN $orderIds ORDER BY distribute_time ASC";
		$cars = Yii::app()->db->createCommand($sql)->queryAll();
		$configName = $this->configNameList();
		foreach($cars as &$car){
			$car['type_config'] = $configName[$car['config_id']];
			$car['cold'] = self::$COLD_RESISTANT[$car['cold_resistant']];
			$car['lane_name'] = '';
			if(!empty($car['lane_id'])){
				$car['lane_name'] = LaneAR::model()->findByPk($car['lane_id'])->name;
			}
		}
		return $cars;
	}

	public function queryCarsBySpecialOrder($specialOrder){
		$specialOrder = trim($specialOrder);
		$specialOrder = strtoupper($specialOrder);
		if(empty($specialOrder)){
			throw new Exception('特殊订单号不可为空');
		}
		$specialOrder = strtoupper($specialOrder);
		$condition = "(UPPER(special_order)='$specialOrder' OR UPPER(remark) LIKE '%$specialOrder%') AND special_property";

		$sql = "SELECT special_order, id, vin, serial_number, series, type, config_id, cold_resistant, color, `status`, engine_code,finish_time, warehouse_time, remark 
				FROM car
				WHERE $condition 
				ORDER BY serial_number ASC";
		$cars = Yii::app()->db->createCommand($sql)->queryAll();

		$configName = $this->configNameList();
		$total = 0;
		$isGood = 0;
		foreach($cars as &$car){
			$car['type_config'] = $configName[$car['config_id']];
			$car['cold'] = self::$COLD_RESISTANT[$car['cold_resistant']];
			$testlinePassed = $this->checkTestLinePassed($car['vin']);
			
			if($testlinePassed){
				$car['inspectionSheet'] = 'OK';
			} else {
				$car['inspectionSheet'] = 'NG';
			}

			if(!empty($car['engine_code'])){
				$car['certificatePaper'] = 'OK';
				if($testlinePassed){
					++$isGood;
				}
			} else {
				$car['certificatePaper'] = 'NG';
			}

			$car['cPrinted'] = false;
			$existInHGZ = $this->existInHGZ($car['vin']);
			if($existInHGZ){
				$car['cPrinted'] = true;
			}
			$car['iPrinted'] = false;
			$isReportPrinted = $this->isReportPrinted($car['vin']);
			if($isReportPrinted){
				$car['iPrinted'] = true;
			}
			
			++$total;
		}

		return array($cars, $total, $isGood);
	}

	public function getConfigList ($carSeries, $carType) {
		$condition = "car_series=?";
		$values = array($carSeries);
		if(!empty($carType)) {
			$condition .= " AND car_type=?";
			$values[] = $carType;
		}
		$configs = OrderConfigAR::model()->findAll($condition . ' ORDER BY id ASC', $values);
		
		$datas = array();
		foreach($configs as $config) {
			$data['config_id'] = $config->id;
			$data['config_name']= $config->name;
			$datas[]=$data;
		}
		return $datas;
	}

	public function getLaneList() {
		$sql = "SELECT id, name FROM lane";
		$lanes = Yii::app()->db->createCommand($sql)->queryAll();
		$datas = array();
		foreach($lanes as $lane){
			$data['lane_id'] = $lane['id'];
			$data['lane_name'] = $lane['name'];
			$datas[]=$data;
		}
		return $datas;
	}

	public function generateBoardNumber() {
		$date = strtotime(DateUtil::getCurDate());
		$year = date("Y", $date);
		$yearCode = CarYear::getYearCode($year);
		$monthDay = date("md", $date);
		$ret = $yearCode . $monthDay;

		$sql = "SELECT board_number FROM `order` WHERE board_number LIKE '$ret%' ORDER BY board_number DESC";
		$lastSerial = Yii::app()->db->createCommand($sql)->queryScalar();
		$lastKey = intval(substr($lastSerial, 5 , 3));
		
		$ret .= sprintf("%03d", (($lastKey + 1) % 1000));
		
		return $ret;
	}

	private function parseStatus($status) {
		if($status === 'all') {
            $status = array(0, 1, 2);
        } else {
            $status = explode(',', $status);
        }
		return $status;
	}

	private function configNameList(){
		$configName = array();
		$sql = "SELECT car_config_id, order_config_id , name , car_model FROM view_config_name";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($datas as $data){
			$configName[$data['car_config_id']] = $data['car_model'] . '/' . $data['name'];
		}
		return $configName;
	}

	public function checkTestLinePassed($vin) {
		$flag = false;
		$sql = "SELECT ToeFlag_F, LM_Flag, RM_Flag, RL_Flag, LL_Flag, Light_Flag, Slide_Flag, BrakeResistanceFlag_F, BrakeFlag_F, BrakeResistanceFlag_R, BrakeFlag_R, BrakeSum_Flag, ParkSum_Flag, Brake_Flag, Speed_Flag, GasHigh_Flag, GasLow_Flag, Final_Flag 
		FROM Summary WHERE vin='$vin'";
			
		$ret=Yii::app()->dbTest->createCommand($sql)->queryRow();
		if($ret['Final_Flag'] === 'T') 
			$flag = true;
		
		return $flag;
	}

	private function orderCarType($series = ""){
		$orderCarType = array();
		$condition = "";
		if(!empty($series)){
			$condition = "WHERE series ='$series'";
		}
		$sql = "SELECT car_type, order_type_name FROM car_type_map $condition";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($datas as $data){
			$orderCarType[$data['car_type']] = $data['order_type_name'];
		}
		return $orderCarType;
	}

	private function parseQueryTime($stime,$etime) {

		$format = 'Y-m-d';
		$stime = date($format, strtotime($stime));
		$etime = date($format, strtotime($etime));

		$stime = $stime . " 08:00:00";
		$etime = $etime . " 08:00:00";

		$s = strtotime($stime);
		$e = strtotime($etime);

		$lastDay = (strtotime($etime) - strtotime($stime)) / 86400;//days

		$ret = array();
		if($lastDay <= 31) {
			$pointFormat = 'm-d';
		} else {	
			$format = 'Y-m';
			$stime = date($format, $s);
			$etime = date($format, $e);
			$pointFormat = 'Y-m';
		}
		
		$t = $s;
		while($t <= $e) {
			
			$point = date($pointFormat, $t);

			if($pointFormat === 'm-d'){
				$nextD = strtotime('+1 day', $t);
				$ret[] = array(
					'stime' => date($format, $t),
					'etime' => date($format, $nextD),
					'point' => $point,
				);
				$t = $nextD;	
			} else {
				$nextM = strtotime('+1 month', $t);
				$ret[] = array(
					'stime' => date($format, $t),
					'etime' => date($format, $nextM),
					'point' => $point,
				);
				$t = $nextM;
			}		
		}

		return $ret;
	}

	public function existInHGZ($vin){
		$exist = false;
		$tdsSever = Yii::app()->params['tds_HGZ'];
        $tdsDB = Yii::app()->params['tds_dbname_HGZ_DATABASE'];
        $tdsUser = Yii::app()->params['tds_HGZ_username'];
        $tdsPwd = Yii::app()->params['tds_HGZ_password']; 

		$sql = "SELECT VIN,DGDH,DGMXID FROM Print_Table WHERE VIN='{$vin}'";
		//connect
        $mssql=mssql_connect($tdsSever, $tdsUser, $tdsPwd);
        if(empty($mssql)) {
            throw new Exception("cannot connet to sqlserver $tdsSever, $tdsUser ");
        }
        mssql_select_db($tdsDB ,$mssql);
        
        //execute insert
        $ret=mssql_query($sql);
        
        //disconnect
        mssql_close($mssql);

        if(mssql_num_rows($ret) > 0){
        	$exist = true;
        }

        return $exist;
	}

	public function isReportPrinted($vin) {
		$isPrinted = false;
		$existsql = "SELECT vin,Order_ID,ReportPrinted FROM ShopPrint WHERE vin='{$vin}'";				
		$exist=Yii::app()->dbTest->createCommand($existsql)->queryRow();
		if(!empty($exist) && $exist['ReportPrinted'] == '已打印'){
			$isPrinted = true;
		}
		return $isPrinted;
	}

}
