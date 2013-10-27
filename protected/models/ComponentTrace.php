<?php
Yii::import('application.models.Car');
Yii::import('application.models.Node');

class ComponentTrace
{
	protected function __construct(){
	}

	public static function createSeeker() {
		$c = __class__;
		$component = new $c();
		return $component;
	}
	
	public function query($vin, $barcode, $node, $stime, $etime, $provider, $component, $series, $curPage, $perPage) {
		$last = strtotime($etime) -strtotime($stime);
		if($last > 3600*24*31){
			throw new Exception('查询时间跨度不能超过1个月');
		}

		$conditions = array();

		// if(!empty($series)) {
  //           $series = explode(',', $series);
  //       } else {
  //           $series = array('f0', 'm6', '6b');
  //       }
        $series = Series::parseSeries($series);

		if(!empty($vin)) {
			$car = Car::create($vin);
			$vin = $car->car->vin;
			$series = array($car->car->series);
			$conditions[] = "vin = '$vin'";
		}
		
		if(!empty($barcode)) {
			$conditions[] = "bar_code LIKE '%$barcode'";
		}
		if(!empty($node)) {
			$node = Node::createByName($node);
			$conditions[] = "node_id = {$node->id}";
		} 

		if(!empty($stime)) {
            // $conditions[] = "modify_time >= '$stime'";
            $conditions[] = "create_time >= '$stime'";
        }
        if(!empty($etime)) {
            // $conditions[] = "modify_time <= '$etime'";
            $conditions[] = "create_time <= '$etime'";
        }
        if(!empty($provider)) {
            $conditions[] = "provider LIKE '%$provider%'";
        }
        if($component) {
            $conditions[] = "component_name LIKE '%$component%'";
        }

		$condition = '';
		if(!empty($conditions)) {
			$condition = join(' AND ', $conditions);
			$condition = 'WHERE ' .$condition;
		}

		$sqls = array();
		foreach($series as $serie) {
			$table = strtolower("component_trace_" .$serie);
			$sqls[] = "(SELECT * FROM $table $condition)";

			$countSqls[] = "SELECT count(*) FROM $table $condition";
		}
		$sql = join(' UNION ALL ', $sqls);

		$limit = "";
		if(!empty($perPage)) {
			$offset = ($curPage - 1) * $perPage;
			$limit = "LIMIT $offset, $perPage";
		}
		$sql .= $limit;
		

		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($datas as &$data) {
            $data['node_name'] = $this->getNodeName($data['node_id']);
			$data['user_name'] = $data['user_display_name'];

			if($data['car_series'] === '6B'){
				$data['car_series'] = '思锐';
			}
        } 

		$total = 0;

		foreach($countSqls as $sql) {
			$total += Yii::app()->db->createCommand($sql)->queryScalar();
		}

		return array($total, $datas);
	}

	public function rangeQuery($stime, $etime, $provider, $component, $series, $curPage, $perPage){
		$conditions = array();
		if(!empty($stime)) {
			$conditions[] = "modify_time >= '$stime'";
		}
		if(!empty($etime)) {
			$conditions[] = "modify_time <= '$etime'";
		}
		if(!empty($provider)) {
			$conditions[] = "provider LIKE '%$provider%'";
		} 
		if($component) {
			$conditions[] = "component_name LIKE '%$component%'";
		}
		if(!empty($series)) {
			$series = explode(',', $series);
		} else {
			$series = array('f0', 'm6', '6b');
		}

		$condition = "";
		if(!empty($conditions)) {
			$condition = join(' AND ', $conditions);
			$condition = " WHERE " .$condition;
		}
		
		$sqls = array();
		foreach($series as $serie) {
			$table = strtolower("component_trace_" .$serie);
			$sqls[] = "(SELECT * FROM $table $condition)";

			$countSqls[] = "SELECT count(*) FROM $table $condition";
		}
		$sql = join(' UNION ALL ', $sqls);

		$limit = $perPage;
		$offset = ($curPage-1) * $perPage;
		$sql .= " LIMIT $offset, $limit";
		

		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($datas as &$data) {
			$data['node_name'] = $this->getNodeName($data['node_id']);
			$data['user_name'] = $data['user_display_name'];

		}

		$total = 0;

		foreach($countSqls as $sql) {
			$total += Yii::app()->db->createCommand($sql)->queryScalar();
		}

		return array($total, $datas);
	}

	public function getCarAllTrace($carId, $series){
		if(empty($series)){
			$car = CarAR::model()->findByPk($carId);
			$series = $car->series;
		}
		$table = strtolower("component_trace_" .$series);

		$sql = "SELECT component_id FROM $table WHERE car_id=$carId";
		$datas = Yii::app()->db->createCommand($sql)->queryColumn();

		return $datas;
	}

	public function getNodeName($nodeId) {
		$node = Node::create($nodeId);
		$name = $node->name;
		switch($name) {
                case 'VQ1':
                    $name = 'VQ1静态检验';
                    break;
                case '路试开始':
                    $name = 'VQ2动态检验.路试开始';
                    break;
                case '路试完成':
                    $name = 'VQ2动态检验.路试完成';
                    break;
                case 'VQ2':
                    $name = 'VQ2动态检验.淋雨';
                    break;
                case 'VQ3':
                    $name = 'VQ3外观检验';
                    break;
                default:
                    ;
            }
		return $name;

	}	

}
