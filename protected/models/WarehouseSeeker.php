<?php
Yii::import('application.models.AR.WarehouseAR');
Yii::import('application.models.AR.OrderConfigAR');
Yii::import('application.models.Warehouse');

class WarehouseSeeker
{
	public function __construct(){
	}
	
	public function query($area, $row, $series, $orderConfig, $color) {
		
		$conditions = array();

		if(!empty($area)){
			$conditions[] = "area = '$area'";
		}

		$row = trim($row);
		if(!empty($row)){
			$conditions[] = "row LIKE '%$row'";
		}

		if(!empty($series)){
			$sql = "SELECT id FROM order_config WHERE car_series='$series'";
			$data = Yii::app()->db->createCommand($sql)->queryColumn();
			$orderConfigs = join(',',$data);
			$conditions[] = "(series = '$series' OR order_config_id IN ($orderConfigs))";
		}

		if(!empty($orderConfig)){
			$conditions[] = "order_config_id = $orderConfig";
		}

		if(!empty($color)){
			$conditions[] = "color = '$color'";
		}

		$con = join(' AND ', $conditions);

		$condition = 'WHERE id>1';
		if(!empty($con)){
			$condition .= " AND ". $con; 
		}

		$rowSql = "SELECT id AS warehouse_id, area, row, capacity, quantity, free_seat, series, order_config_id, cold_resistant, color FROM warehouse $condition";
		$rows = Yii::app()->db->createCommand($rowSql)->queryAll();

		$configs = $this->getAllOrderConfig();
		foreach($rows as &$row){
			$row['order_config_name'] = '';
			if(!empty($row['order_config_id'])){
				$row['order_config_name'] = $configs[$row['order_config_id']];
			}
			$row['cold'] = $row['cold_resistant']===1 ? '耐寒':'非耐寒';
		}

		return $rows;
	}

	public function getAllOrderConfig() {
		$configs = OrderConfigAR::model()->findAll();
		
		$datas = array();
		foreach($configs as $config) {
			$datas[$config['id']] = $config['name'];
		}
		return $datas;
	}
}
