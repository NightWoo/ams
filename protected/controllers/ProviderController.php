<?php
Yii::import('application.models.ProviderSeeker');
Yii::import('application.models.AR.ProviderAR');


class ProviderController extends BmsBaseController 
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
	public function actionGetNameList() {
		$name = $this->validateStringVal('providerName', '');
		try{
			if(empty($name)){
				throw new Exception('provider name cannot be null');
			}
			$seeker = new ProviderSeeker;
			$data = $seeker->getNameList($name);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());	
		}
	}
	
	//added by wujun
	public function actionGetCode() {
		$name = $this->validateStringVal('providerName', '');
		try{
			if(empty($name)){
				throw new Exception('provider name cannot be null');	
			}
			$seeker = new ProviderSeeker;
			$data = $seeker->getProviderCode($name);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}		
	}

	public function actionSearch() {
		$code = $this->validateStringVal('providerCode', '');
		$name = $this->validateStringVal('providerName', '');
		try{
			$seeker = new ProviderSeeker;
			$data = $seeker->queryProvider($code, $name);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionSave() {
		$id = $this->validateIntVal('id',0);
		$code = $this->validateStringVal('providerCode', '');
		$name = $this->validateStringVal('providerName', '');
		$displayName = $this->validateStringVal('displayName','');
		try{
			if(empty($name)){
				throw new Exception('供应商名称不能为空');
			}

			if(empty($code)){
				throw new Exception("供应商代码不能为空", 1);				
			}

			if(empty($id)){
				$nameExists = ProviderAR::model()->find('name=?', array($name));
				if(!empty($nameExists)){
					throw new Exception('供应商名称已经存在，请重新命名');
				}
				$codeExists = ProviderAR::model()->find('code=?', array($code));
				if(!empty($codeExists)){
					throw new Exception('供应商代码已经存在，请重新指定');					
				}
				$provider = new ProviderAR();
			} else {
				$provider = ProviderAR::model()->findByPk($id);
			}

			$provider->code = $code;
			$provider->name = $name;
			$provider->display_name = empty($displayName)? $name:$displayName;
			$provider->user_id = Yii::app()->user->id;
			$provider->modify_time = date("YmdHis");

			$provider->save();
			$this->renderJsonBms(true, 'provider save successfully', '');

		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
	
	public function actionDelete() {
		$id = $this->validateIntVal('id', 0);
		try{
			$provider = ProviderAR::model()->findByPk($id);
			if(!empty($provider)) {
				$provider->delete();
			}
			$this->renderJsonBms(true, 'provider delete successfully', '');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
}