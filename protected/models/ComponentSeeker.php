<?php
Yii::import('application.models.Car');
Yii::import('application.models.ComponentSeeker');

class ComponentSeeker
{
	public function __construct(){
	}

	public function getAll($token) {
		$sql = "SELECT display_name FROM component WHERE display_name LIKE '%$token%'";
		return Yii::app()->db->createCommand($sql)->queryColumn();
	}	
	
	public function query($series, $category, $component, $code, $isfault, $curPage, $perPage) {
		$conditions = array();
		if(!empty($series)) {
			$conditions[] = "car_series='$series'";
		}
		if(!empty($category)) {
			$conditions[] = "category_id='$category'";
		}
		if(!empty($component)) {
			$conditions[] = "display_name LIKE '%$component%'";
		}
		if(!empty($code)) {
			$conditions[] = "code LIKE '%$code%'";
		}
		if(!empty($isfault)) {
			$conditions[] = "is_fault=$isfault";
		}
	
		$condition = join(' AND ', $conditions);
		
		$limit = $perPage;
		$offset = ($curPage - 1) * $perPage;

		$sql = "SELECT id,car_series, category_id, code, display_name, name, is_fault, simple_code, remark  FROM component WHERE $condition ORDER BY code ASC LIMIT $offset,$limit";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();		


		$countSql = "SELECT count(*) FROM component WHERE $condition";
		
		$total = Yii::app()->db->createCommand($countSql)->queryScalar();

		$sql = "SELECT id,name FROM component_category";
		$temps = Yii::app()->db->createCommand($sql)->queryAll();
		
		$categorys = array();
		foreach($temps as $category) {
			$categorys[$category['id']] = $category['name'];
		}
		$categorys[0] = '其他';

		foreach($datas as &$data) {
			$data['category'] = $categorys[$data['category_id']];
		}

		return array($total, $datas);
	}
	
	//added by wujun
	public function getComponentCode($componentName) {
		$sql = "SELECT id AS component_id, code AS component_code, display_name AS component_name, name, simple_code FROM component WHERE display_name = '$componentName'";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		return $datas;
	}
}
