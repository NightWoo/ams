<?php
Yii::import('application.models.Config');
Yii::import('application.models.ConfigSeeker');
Yii::import('application.models.AR.CarConfigAR');
Yii::import('application.models.AR.CarConfigListAR');
Yii::import('application.models.AR.CarTypeMapAR');

class ConfigController extends BmsBaseController 
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}
	
	//added by wujun
	public function actionSearch() {
		$configName = $this->validateStringVal('config_name','');
		$series = $this->validateStringVal('car_series','');
		$type = $this->validateStringVal('car_type','');
		try{
			$config = new ConfigSeeker();
			$data = $config->search($series, $type, $configName);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this-> renderJsonBms(false , $e->getMessage());
		}
	}
	
	//added by wujun
	public function actionSave() {
		$id = $this->validateIntVal('id',0);
		$series = $this->validateStringVal('car_series','');
		$type = $this->validateStringVal('car_type','');
		$configName = $this->validateStringVal('config_name','');
		$remark = $this->validateStringVal('remark','');
		
		try {
			if(empty($series)) {
				throw new Exception("车系不能为空");
			}
			if(empty($type)) {
				throw new Exception("车型不能为空");
			}
			if(empty($configName)) {
				throw new Exception("配置名称不能为空");
			} else if(empty($id)) {
				$exist = CarConfigAR::model()->find('name=?', array($configName));
				if(!empty($exist)){
					throw new Exception("配置名称 $configName 已经存在，请重新命名");
				}
			}
			if(empty($id)) {
				$config = new CarConfigAR();
				$config->create_time = date("YmdHis");
			} else {
				$config = CarConfigAR::model()->findByPk($id);
			}
			
			$config->car_series = $series;
			$config->car_type = $type;
			$config->name = $configName;
			$config->remark = $remark;
			$config->user_id = Yii::app()->user->id;
			$config->modify_time = date("YmdHis");
			
			$config->save();
			$this->renderJsonBms(true, 'OK', '');
		}catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
	
	public function actionDelete() {
		$id = $this->validateIntval('id', 0);
		try{
			$config = CarConfigAR::model()->findByPk($id);
			if(!empty($config)) {
				$config->delete();
			}
			$this->renderJsonBms(true, 'OK', '');
		}catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
	
	//added by wujun
	public function actionGetConfig() {
		$carSeries = $this->validateStringVal('carSeries', '');
		$carType = $this->validateStringVal('carType', '');
		try {
			$config = new ConfigSeeker();
			$data = $config->getNameList($carSeries, $carType);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());	
		}		
	}
	
	public function actionGetCarType() {
		$carSeries = $this->validateStringVal('carSeries','');
		try {
			$condition = "series=?";
			$params = array($carSeries);
			$carType = CarTypeMapAR::model()->findAll($condition, $params);
			$data = $carType;
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());	
		}	
	}
	
	public function actionSearchConfigList () {
		$configId = $this->validateIntVal('configId', 0);
		$nodeId = $this->validateIntVal('nodeId', 0);
		$perPage = $this->validateIntVal('perPage', 10);
		$curPage = $this->validateIntVal('curPage', 1);
		try {
			$seeker = new ConfigSeeker;
			list($total, $data) = $seeker->getList($configId, $nodeId, $curPage, $perPage);
			$ret = array(
				'pager' => array(
					'curPage' => $curPage,
					'perPage' => $perPage,
					'total' => $total,
				),
				'list' => $data,
			);
			$this->renderJsonBms(true, 'OK', $ret);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
	
	//added by wujun
	public function actionSaveDetail(){
		$id = $this->validateIntVal('id', 0);
		$configId = $this->validateIntVal('configId', 0);
		$istrace = $this->validateIntVal('istrace', 0);
		$componentId = $this->validateIntVal('componentId',0);
		$nodeId = $this->validateIntVal('nodeId',0);
		$providerId = $this->validateIntVal('providerId',0);
		$remark = $this->validateStringVal('remark','');
		
		try{
			if(empty($configId)){
				throw new Exception('需先选择配置，请返回配置明细页面');
			}
			if(empty($componentId)){
				throw new Exception('零部件不能为空');
			}
			if(empty($id)){
				$exist = CarConfigListAR::model()->find('config_id=? AND component_id=?', array($configId, $componentId));
				if(!empty($exist)){
					throw new Exception('此零部件已经存在于本配置明细中');
				} else {
					$configDetail = new CarConfigListAR;
					$configDetail->create_time = date("YmdHis");
				}
			} else {
				$configDetail = CarConfigListAR::model()->findByPk($id);
			}
			
			$configDetail->config_id = $configId;
			$configDetail->istrace = $istrace;
			$configDetail->node_id = $nodeId;
			$configDetail->component_id = $componentId;
			$configDetail->provider_id = $providerId;
			$configDetail->remark = $remark;
			$configDetail->user_id = Yii::app()->user->id;
			$configDetail->modify_time = date("YmdHis");
			
			$configDetail->save();
			$this->renderJsonBms(true, 'saved');
		} catch (Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());	
		}
	}
	
	//added by wujun
	public function actionDeleteDetail() {
		$id = $this->validateIntVal('id', 0);
		try{
			$configDetail = CarConfigListAR::model()->findByPk($id);
			if(!empty($configDetail)) {
				$configDetail->delete();
			}
			$this->renderJsonBms(true, 'deleted', '');
		}catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}	
	}

	//added by wujun
	public function actionCopyList() {
		$originalId = $this->validateIntVal('originalId', 0);
		$clonedId = $this->validateIntval('clonedId', 0);
		$transaction = Yii::app()->db->beginTransaction();
		try {
			if(!empty($originalId) && !empty($clonedId)) {
				$sql = "DELETE FROM car_config_list WHERE config_id = '$clonedId'";
				Yii::app()->db->createCommand($sql)->execute();
				Config::copyConfigList($originalId, $clonedId);
			}
			$transaction->commit();
			$this->renderJsonBms(true, 'copy success', '');
		} catch(Exception $e) {
			$transaction->rollback();
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
}