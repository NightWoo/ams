<?php
Yii::import('application.models.AR.CarAR');
Yii::import('application.models.AR.OrderConfigAR');
Yii::import('application.models.AR.OrderAR');
Yii::import('application.models.AR.WarehouseAR');
Yii::import('application.models.AR.LaneAR');
Yii::import('application.models.Car');

class CarSeeker
{
	private static $NODE_BALANCE_STATE = array(
		'PBS' => array('彩车身库'),
		'onLine' => array('T1工段' ,'T2工段', 'T3工段', 'C1工段', 'C2工段', 'F1工段', 'F2工段', 'VQ1检验'),
		'VQ1' => array('VQ1异常'),
		'VQ1-EXCEPTION' => array('VQ1异常'),
		'VQ2' => array('整车下线', '出生产车间', '检测线缓冲', 'VQ2路试', 'VQ2淋雨检验', 'VQ2异常.路试', 'VQ2异常.漏雨'),
		'VQ3' => array('VQ3检验' ,'VQ3合格', 'VQ3异常'),
		'recycle' => array('VQ1异常','整车下线', '出生产车间', '检测线缓冲','VQ2路试', 'VQ2淋雨检验', 'VQ2异常.路试', 'VQ2异常.漏雨', 'VQ3检验' ,'VQ3合格', 'VQ3异常'),
		'WH' => array('成品库','WDI'),
		'WHin' => array('成品库'),
		'assembly' => array('T1工段' ,'T2工段', 'T3工段', 'C1工段', 'C2工段', 'F1工段', 'F2工段', 'VQ1检验','VQ1异常','整车下线', '出生产车间', '检测线缓冲','VQ2路试', 'VQ2淋雨检验', 'VQ2异常.路试', 'VQ2异常.漏雨', 'VQ3检验' ,'VQ3合格', 'VQ3异常','成品库'),
	);

	private static $COLD_RESISTANT = array('非耐寒','耐寒');

	public function __construct(){
	}


	public function queryCheckinDetail($startTime, $endTime, $series='', $curPage=0, $perPage=0){
		if(empty($startTime) || empty($endTime)){
			throw new Exception ('起止时间不可为空');
		}

		$configName = $this->configNameList();
		$condition = "warehouse_time>='$startTime' AND warehouse_time<='$endTime'";
		if(!empty($series)){
			$condition .= " AND series='$series'";
		}
		$limit = "";
		if(!empty($perPage)) {
			$offset = ($curPage - 1) * $perPage;
			$limit = "LIMIT $offset, $perPage";
		}

		$dataSql = "SELECT id as car_id,serial_number,warehouse_id,vin,series,type,config_id,cold_resistant,color,engine_code,finish_time,warehouse_time,remark,special_order,assembly_line
				      FROM car 
				     WHERE $condition 
			      ORDER BY distribute_time ASC $limit";

		$datas = Yii::app()->db->createCommand($dataSql)->queryAll();

		foreach($datas as &$data){
			if($data['series'] == '6B') $data['series'] = '思锐';
			$data['config_name'] = $configName[$data['config_id']];
			$data['cold'] = self::$COLD_RESISTANT[$data['cold_resistant']];

			$data['row'] = '-';
			if(!empty($data['warehouse_id'])){
				$data['row'] = WarehouseAR::model()->findByPk($data['warehouse_id'])->row;
			}
		}

		$countSql = "SELECT count(*) FROM car where $condition";	
		$total = Yii::app()->db->createCommand($countSql)->queryScalar();

		return array($total, $datas);
	}

	public function queryCheckoutDetail($startTime, $endTime, $series='', $curPage=0, $perPage=0){
		if(empty($startTime) || empty($endTime)){
			throw new Exception ('起止时间不可为空');
		}

		$configName = $this->configNameList();
		$condition = "distribute_time>='$startTime' AND distribute_time<='$endTime'";
		if(!empty($series)){
			$condition .= " AND series='$series'";
		}
		$limit = "";
		if(!empty($perPage)) {
			$offset = ($curPage - 1) * $perPage;
			$limit = "LIMIT $offset, $perPage";
		}

		$dataSql = "SELECT id as car_id,vin,series,type,config_id,cold_resistant,color,engine_code,distributor_name,lane_id,distribute_time,order_id,remark,special_order 
				  FROM car 
				  WHERE $condition 
			  ORDER BY distribute_time ASC $limit";

		$datas = Yii::app()->db->createCommand($dataSql)->queryAll();

		foreach($datas as &$data){
			if($data['series'] == '6B') $data['series'] = '思锐';
			$data['config_name'] = $configName[$data['config_id']];
			$data['cold'] = self::$COLD_RESISTANT[$data['cold_resistant']];
			
			$data['order_number'] = '-';
			if(!empty($data['order_id'])){
				$data['order_number'] = OrderAR::model()->findByPk($data['order_id'])->order_number;
			}

			$data['lane'] = '-';
			if(!empty($data['lane_id'])){
				$data['lane'] = LaneAR::model()->findByPk($data['lane_id'])->name;
			}
		}

		$countSql = "SELECT count(*) FROM car where $condition";	
		$total = Yii::app()->db->createCommand($countSql)->queryScalar();

		return array($total, $datas);
	}

	public function queryBalanceDetail($state, $series='', $curPage=0, $perPage=0){
		if(!is_array($state)) {
			if(!empty(self::$NODE_BALANCE_STATE[$state])) {
				$states = self::$NODE_BALANCE_STATE[$state];
			} else {
				$states = array($state);
			}
		} else {
			$states = $state;
		}

		$sql = "SELECT id, name, order_config_id FROM car_config";
		$configs = Yii::app()->db->createCommand($sql)->queryAll();
		$configInfos = array();
		foreach($configs as $config){
			$configInfos[$config['id']]['configName'] = $config['name'];
			$order = OrderConfigAR::model()->findByPk($config['order_config_id']);
			if(!empty($order)){
				$configInfos[$config['id']]['orderConfigName'] = $order->name;
			} else {
				$configInfos[$config['id']]['orderConfigName'] = $config['name'];
			}
		}
		$sql = "SELECT car_type, car_model FROM car_type_map";
		$carModels = Yii::app()->db->createCommand($sql)->queryAll();
		$modelInfo = array();
		foreach($carModels as $carModel){
			$modelInfo[$carModel['car_type']]= $carModel['car_model'];
		}
		$sql = "SELECT id, row FROM warehouse";
		$rows = Yii::app()->db->createCommand($sql)->queryAll();
		$rowInfo = array();
		foreach($rows as $row){
			$rowInfo[$row['id']] = $row['row'];
		}

		$str = "'" . join("','", $states) . "'";
		$condition = " WHERE status IN ($str)";
		if(!empty($series)){
			$condition .= " AND series='$series'";
		}

		$limit = "";
		if(!empty($perPage)) {
			$offset = ($curPage - 1) * $perPage;
			$limit = "LIMIT $offset, $perPage";
		}
		$sql = "SELECT id as car_id, series,serial_number, vin, type, color, cold_resistant, status, config_id, modify_time,warehouse_id, assembly_line, finish_time, warehouse_time, distribute_time,special_order, remark FROM car $condition ORDER BY finish_time ASC $limit";
        $cars = Yii::app()->db->createCommand($sql)->queryAll();
        foreach($cars as &$car){
        	if($car['series'] == '6B') $car['series'] = '思锐';
        	if(!empty($car['type'])){
	        	$car['car_model'] = $modelInfo[$car['type']];
        	} else {
        		$car['car_model'] ='';
        	}
        	if(!empty($car['config_id'])){
        		$car['config_Name'] = $configInfos[$car['config_id']]['configName'];
	        	$car['order_config_name'] = $configInfos[$car['config_id']]['orderConfigName'];
	        	$car['type_info'] = $car['car_model'] . '/' . $car['order_config_name'];
        	}else {
        		$car['config_Name'] = '';
	        	$car['order_config_name'] = '';
	        	$car['type_info'] = $car['type'];
        	}
        	if(!empty($car['warehouse_id'])){
        		$car['row']=$rowInfo[$car['warehouse_id']];
        	}else{
        		$car['row']='-';
        	}
        	$car['cold'] = $car['cold_resistant'] == 1 ? '耐寒':'非耐寒';
        	if($car['finish_time'] == '0000-00-00 00:00:00')  $car['finish_time'] = '-';
        	if($car['warehouse_time'] == '0000-00-00 00:00:00')  $car['warehouse_time'] = '-';
        }

        $sql = "SELECT count(*) FROM car $condition";	
		$total = Yii::app()->db->createCommand($sql)->queryScalar();

        return  array($total, $cars);
	}

	public function queryAssemblyBalance($state){ 
		$seriesArray = $this->parseSeries('all');
		$seriesName = $this->seriesName();
		$stateArray = $this->stateArray($state);
		$stateName = $this->stateName();

		$detail = array();
		$dataSeriesX = array();
		$dataSeriesY = array();
		$seriesTotal = array();
		$stateTotal = array();
		foreach($seriesArray as $series){
			$seriesTotal[$seriesName[$series]] = 0;
		}
		foreach($stateArray as $state){
			$stateTotal[$stateName[$state]] = 0;
		}

		foreach($stateArray as $state){
			$temp = array();
			foreach($seriesArray as $series){
				$count = $this->countStateCars($state,$series);
				$temp[$seriesName[$series]] = $count;
				$dataSeriesY[$seriesName[$series]][] = intval($count);
				$seriesTotal[$seriesName[$series]] += intval($count);
				$stateTotal[$stateName[$state]] += intval($count);
			}
			$detail[] = array_merge(array('state' => $stateName[$state]), $temp);
			$dataSeriesX[] = $stateName[$state];
		}

		$carSeries = array();
		foreach($seriesArray as $key => $series){
			$carSeries[] = $seriesName[$series];
		}

		return array(
			'carSeries' => $carSeries, 
			'detail' => $detail,
			'seriesTotal'=> $seriesTotal,
			'stateTotal'=> $stateTotal,
			'series' => array(
				'x'=> $dataSeriesX,
				'y'=> $dataSeriesY,
			)
		);	
	}

	public function balanceDistribute($state, $series){
		
		$stateName = $this->stateName();

		$colorArray = $this->colorCategories($series);
		$configColdArray = $this->configColdArray($state, $series);

		$detail = array();
		$colorTotal = array();
		foreach($colorArray as $color){
			$colorTotal[$color] = 0;
		}

		$configNameArray = array();
		$configTotal = array();
		foreach($configColdArray as $configCold){
			$configNameArray[] = $configCold['name']; 
			$configTotal[$configCold['name']] = 0;
		}
		$dataPie = array();

		foreach($colorArray as $index => $color){
			$data = array();
			$temp = array();
			$colorCount = $this->countStateCars($state, $series, $color);
			$data['y'] = $colorCount;
			$colorTotal[$color] += $colorCount;

			//$configColdArray = $this->configColdArray($state, $series, $color);
			$drilldownCategories = array();
			$drilldownData = array();
			foreach($configColdArray as $configCold){
				$configCount = $this->countStateCars($state,$series, $color, $configCold['order_config_id'], $configCold['cold_resistant']);
				if(!empty($configCount)){					
					$drilldownCategories[] = $configCold['name'];
					$drilldownData[] = $configCount;					
					$configTotal[$configCold['name']] += $configCount;
				}
				$temp[$configCold['name']] = $configCount;

			}
			$data['drilldown'] = array(
				'name' => $color,
				'categories' => $drilldownCategories,
				'data' => $drilldownData,
			);
			$dataPie[] = $data;
			$detail[] = array_merge(array('color' => $color), $temp);
		}

		return array(
			'colorArray' => $colorArray,
			'configNameArray' =>$configNameArray,
			'detail' => $detail,
			'colorTotal'=> $colorTotal,
			'configTotal'=> $configTotal,
			'dataPie' => $dataPie,
		);

	}

	public function queryBalanceCars($state, $series){
		if(!is_array($state)) {
			if(!empty(self::$NODE_BALANCE_STATE[$state])) {
				$states = self::$NODE_BALANCE_STATE[$state];
			} else {
				$states = array($state);
			}
		} else {
			$states = $state;
		}

		$str = "'" . join("','", $states) . "'";
		$condition = "status IN ($str)";
		if(!empty($series)){
			$condition .= " AND series='$series'";
		}

		$sql = "SELECT car_id,, `status`, vin, series, color, type, car_model, config_id, config_name, order_config_name,
				FROM view_car_info_main
				WHERE $condition";
		$cars = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($cars as &$car){
			$car['type_info'] = $car['car_model'] . "/" . $car['order_config_name'];
			if($car['cold_resistant'] == 1){
				$car['type_info'] .= "/耐寒型";
			} else {
				$car['type_info'] .= "/非耐寒";
			}
		}
		return $cars;
	}

	public function countStateCars($state,$series, $color='', $orderConfigId=0, $coldResistant=2) {
		if(!is_array($state)) {
			if(!empty(self::$NODE_BALANCE_STATE[$state])) {
				$states = self::$NODE_BALANCE_STATE[$state];
			} else {
				$states = array($state);
			}
		} else {
			$states = $state;
		}

		$sql = "SELECT id FROM car_config WHERE order_config_id = $orderConfigId";
        $configId = Yii::app()->db->createCommand($sql)->queryColumn();
        $configIds = join(',', $configId);

		$str = "'" . join("','", $states) . "'";
		$condition = " WHERE status IN ($str)";
		if(!empty($series)){
			$condition .= " AND series='$series'";
		}
		if(!empty($color)){
			$condition .= " AND color='$color'";
		}
		if(!empty($orderConfigId)){
			$condition .= " AND config_id IN ($configIds)";
		}
		if($coldResistant != 2){
			$condition .= " AND cold_resistant=$coldResistant";
		}

		$sql = "SELECT count(*) FROM car $condition";	
		$total = Yii::app()->db->createCommand($sql)->queryScalar();
		return $total;
	}

	public function queryOrderCar($orderNumber, $standbyDate=''){
		$configNames = $this->configNameList();
		$distributorName = '';
		$sql = "SELECT id as order_id, order_number, standby_date, distributor_name FROM `order` WHERE order_number LIKE '%$orderNumber' AND amount = count";
		if(!empty($standbyDate)){
			$sql  .= " AND standby_date='$standbyDate'";
		}
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$orderNumberArray = array();
		$sqls = array();
		foreach($orders as $order){
			$orderNumberArray[$order['order_id']] = $order['order_number'];
			$orderNumber = $order['order_number'];
			$distributorName = $order['distributor_name'];
			$orderId= $order['order_id'];
			$sqls[] = "(SELECT vin,series,type,config_id,cold_resistant,color,engine_code,distributor_name,lane_id,distribute_time,order_id,remark
							FROM car 
							WHERE order_id = $orderId)";
		}
		$dataSql = join(' UNION ALL ', $sqls);
		$dataSql .= "ORDER BY order_id, distribute_time ASC";
		if(!empty($sqls)){
			$datas = Yii::app()->db->createCommand($dataSql)->queryAll();
		}
		if(empty($datas)){
			throw new Exception("订单" .$orderNumber. "在该日期条件下无车辆明细数据");
		}
		foreach($datas as &$data){
			$data['config_name'] = $configNames[$data['config_id']];
			$data['cold'] = self::$COLD_RESISTANT[$data['cold_resistant']];
			// $car = $car = Car::create($data['vin']);
			// $engineTrace = $car->checkTraceGasolineEngine();
			// $data['engine_code'] = $engineTrace->bar_code;
			$data['order_number'] = $orderNumberArray[$data['order_id']];
			if(!empty($data['lane_id'])){
				$data['lane'] = LaneAR::model()->findByPk($data['lane_id'])->name;
			}
		}

		return array('distributor_name' => $distributorName,
					'cars' => $datas);
	}

	public function configColdArray($state, $series){
		if(!is_array($state)) {
			if(!empty(self::$NODE_BALANCE_STATE[$state])) {
				$states = self::$NODE_BALANCE_STATE[$state];
			} else {
				$states = array($state);
			}
		} else {
			$states = $state;
		}

		$str = "'" . join("','", $states) . "'";
		$condition = " WHERE status IN ($str) AND series='$series'";

		$sql = "SELECT DISTINCT order_config_id, cold_resistant, order_config_name FROM view_car_info_main $condition";
		$configColdArray = Yii::app()->db->createCommand($sql)->queryAll();

		foreach($configColdArray as &$configCold){
			$configCold['name'] = '';
			if(!empty($configCold['order_config_id'])){
				$configFullName = $configCold['order_config_name'] . '/' . self::$COLD_RESISTANT[$configCold['cold_resistant']];
				$configCold['name'] = $configFullName;
			}
		}
		return $configColdArray;
	}

	private function parseSeries($series) {
		if(empty($series) || $series === 'all') {
            $series = array('F0', 'M6', '6B');
        } else {
            $series = explode(',', $series);
        }
		return $series;
	}

	private function seriesName(){
		$seriesName = array(
			'F0' => 'F0',
			'M6' => 'M6',
			'6B' => '思锐'
		);

		return $seriesName;
	}

	private function stateArray($state){
		$stateMap=array(
			'PBS' => array('PBS'),
			'onLine' => array('PBS'),
			'VQ1' => array('VQ1'),
			'VQ2' => array('VQ1'),
			'VQ3' => array('VQ3'),
			'recycle' => array('VQ1', 'VQ2', 'VQ3'),
			'WH' => array('WH'),
			'WHin' => array('WHin'),
			'assembly' => array('onLine','VQ1', 'VQ2', 'VQ3', 'WH'),
			'mergeRecyle' => array('onLine','recycle', 'WH'),
		);
		return $stateMap[$state];
	}

	private function stateName(){
		$stateName = array(
			'PBS' => 'PBS',
			'onLine' => 'I线',
			'VQ1' => 'VQ1',
			'VQ1-EXCEPTION' => 'VQ1(异常)',
			'VQ2' => 'VQ2',
			'VQ3' => 'VQ3',
			'recycle' => '周转车',
			'WH' => '成品库',
			'WHin' => '成品库(除WDI)',
			'assembly' => '总装'
		);

		return $stateName;
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

	private function configIdList(){
		$configIds = array();
		$sql = "SELECT car_config_id, order_config_id , name FROM view_config_name";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($data as $data){
			if(empty($configId[$data['name']])){
				$configIds[$data['name']] = array();
			}
			$configIds[$data['name']][] = $data['car_config_id'];
		}
		return $configIds;
	}

	private function colorCategories($series){
		$colors = array();
		$sql = "SELECT color from car_color_map WHERE series='$series'";
		$datas = Yii::app()->db->createCommand($sql)->queryColumn();
		// foreach($datas as $data){
		// 	$colors[] = $data['color'];
		// }
		return $datas;
	}

	private function configCategories($series){
		$configs = array();
		$sql = "SELECT id from car_config WHERE series='$series'";
		$datas = Yii::app()->db->createCommand($sql)->queryColumn();
		foreach($datas as $data){
			$configs[] = $data['id'];
		}
		return $configs;
	}

}
