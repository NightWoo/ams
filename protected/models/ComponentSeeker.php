<?php
Yii::import('application.models.Car');
Yii::import('application.models.AR.ProviderAR');

class ComponentSeeker
{
	public function __construct(){
	}

	public function getAll($token, $series) {
		$sql = "SELECT display_name FROM component WHERE display_name LIKE '%$token%' AND car_series='$series'";
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

		$sql = "SELECT id,car_series, category_id, code, display_name, name, is_fault, simple_code,unit_price, remark, provider_1,provider_2,provider_3  FROM component WHERE $condition ORDER BY code ASC LIMIT $offset,$limit";
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
			for($i=1;$i<=3;$i++){
				$providerDisplayName = "provider_display_name_" . $i;
				$providerName = "provider_name_" . $i;
				$providerCode = "provider_code_" . $i;
				$providerNum = "provider_". $i;
				$provider = ProviderAR::model()->findByPk($data[$providerNum]);
				$data[$providerDisplayName] = empty($provider) ? "" : $provider->display_name;
				$data[$providerName] = empty($provider) ? "" : $provider->name;
				$data[$providerCode] = empty($provider) ? "" : $provider->code;
			}
		}

		return array($total, $datas);
	}
	
	//added by wujun
	public function getComponentCode ($componentName, $series) {
		$sql = "SELECT id AS component_id, code AS component_code, display_name AS component_name, name, simple_code FROM component WHERE display_name = '$componentName' AND car_series='$series'";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		return $datas;
	}

	public function getComponentInfo ($componentId) {
		$sql = "SELECT id AS component_id, code, name, display_name, unit_price,simple_code, provider_1, provider_2, provider_3 FROM component WHERE id=$componentId";
		$component = Yii::app()->db->createCommand($sql)->queryRow();
		$component['provider'] = array();
		for($i=1;$i<=3;$i++){
			$providerNum = "provider_". $i;
			if(empty($component[$providerNum])) continue;
			$providerDisplayName = "provider_display_name_" . $i;
			$providerName = "provider_name_" . $i;
			$providerCode = "provider_code_" . $i;
			$provider = ProviderAR::model()->findByPk($component[$providerNum]);
			$component['provider'][$component[$providerNum]]["display_name"] = empty($provider) ? "" : $provider->display_name;
			$component['provider'][$component[$providerNum]]["name"] = empty($provider) ? "" : $provider->name;
			$component['provider'][$component[$providerNum]]["code"] = empty($provider) ? "" : $provider->code;
		}
		return $component;
	}	
}
