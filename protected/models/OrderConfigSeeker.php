<?php
Yii::import('application.models.Node');
Yii::import('application.models.User');
Yii::import('application.models.AR.ComponentAR');
Yii::import('application.models.AR.CarConfigListAR');
Yii::import('application.models.AR.ProviderAR');
Yii::import('application.models.AR.NodeAR');
Yii::import('application.models.AR.OrderConfigAR');

class OrderConfigSeeker
{
	public function __construct(){
	}

	public function getName($configId) {
		$sql = "SELECT name FROM car_config WHERE id=$configId";
        $configName = Yii::app()->db->createCommand($sql)->queryScalar();
		return $configName;
	}

	public function getDetail($nodeName) {
		$node = Node::createByName($nodeName);
		if(!$node->exist()) {
			throw new Exception('node ' . $nodeName . ' is not exit');
		}
		$config = Config::create();

		$sql = "SELECT name FROM car_config WHERE id=$configId";
	}

	//added by wujun
	public function search($series, $type, $configName, $column = '') {
		$condition = "car_series=?";
		$values = array($series);
		if(!empty($type)) {
			$condition .= " AND car_type=?";
			$values[] = $type;
		}
		if(!empty($configName)) {
			$condition .= " AND name=?";
			$values[] = $configName;
		}
				
		$configs = OrderConfigAR::model()->findAll($condition . ' ORDER BY id ASC', $values);
		$datas = array();
		foreach($configs as $config){
			if($column === 'car_type') {
				$data = array(
						'id' => $config->{$column},
						'name' => $config->{$column},
					);
			} elseif($column === 'name') {
				$data = array(
                        'id' => $config->id,
                        'name' => $config->{$column},
                    );
			} else {
				$data = $config->getAttributes();
				$user = User::model()->findByPk($config->user_id);
				$user_name = empty($user) ? '' : $user->display_name;
				$data['user_name']= $user_name;
			}
			if(!in_array($data, $datas)) {
				$datas[]=$data;	
			}
		}
		
		return $datas;
	}
	
	//added by wujun
	public function getNameList ($carSeries, $carType) {
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
	
}
