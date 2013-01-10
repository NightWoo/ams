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
	
}