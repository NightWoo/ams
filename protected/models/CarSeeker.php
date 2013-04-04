<?php
Yii::import('application.models.AR.CarAR');
Yii::import('application.models.AR.OrderConfigAR');
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
		'WH' => array('成品库'),
		'assembly' => array('彩车身库','T1工段' ,'T2工段', 'T3工段', 'C1工段', 'C2工段', 'F1工段', 'F2工段', 'VQ1检验','VQ1异常','整车下线', '出生产车间', '检测线缓冲','VQ2路试', 'VQ2淋雨检验', 'VQ2异常.路试', 'VQ2异常.漏雨', 'VQ3检验' ,'VQ3合格', 'VQ3异常','成品库'),
	);

	public function __construct(){
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
		$sql = "SELECT series, vin, type, color, cold_resistant, status, config_id, modify_time,warehouse_id, assembly_line, finish_time, warehouse_time, distribute_time, remark FROM car $condition ORDER BY finish_time ASC $limit";
        $cars = Yii::app()->db->createCommand($sql)->queryAll();
        foreach($cars as &$car){
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
		$stateTotal =array();
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

	public function countStateCars($state,$series) {
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
		$condition = " WHERE status IN ($str)";
		if(!empty($series)){
			$condition .= " AND series='$series'";
		}

		$sql = "SELECT count(DISTINCT id) FROM car $condition";	
		$total = Yii::app()->db->createCommand($sql)->queryScalar();
		return $total;
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
			'assembly' => array('PBS','onLine','VQ1', 'VQ2', 'VQ3', 'WH'),
			'mergeRecyle' => array('PBS','onLine','recycle', 'WH'),
		);
		return $stateMap[$state];
	}

	private function stateName(){
		$stateName = array(
			'PBS' => 'PBS',
			'onLine' => 'I线',
			'VQ1' => 'VQ1(异常)',
			'VQ1-EXCEPTION' => 'VQ1(异常)',
			'VQ2' => 'VQ2',
			'VQ3' => 'VQ3',
			'recycle' => '周转车',
			'WH' => '成品库',
			'assembly' => '总装'
		);

		return $stateName;
	}

}
