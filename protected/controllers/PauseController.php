<?php
Yii::import('application.models.PauseSeeker');
Yii::import('application.models.DepartmentSeeker');
Yii::import('application.models.AR.monitor.LinePauseAR');


class PauseController extends BmsBaseController 
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
	public function actionQuery() {
		$startTime = $this->validateStringVal('startTime', '');
		$endTime = $this->validateStringVal('endTime', '');
		$section = $this->validateStringVal('section', '');
		$pauseType = $this->validateStringVal('pauseType', '');
		$dutyDepartment = $this->validateStringVal('dutyDepartment', '');
		$perPage = $this->validateIntVal('perPage', 10);
		$curPage = $this->validateIntVal('curPage', 1);
		$orderBy = $this->validateStringVal('orderBy', '');
		try{
			$orderBy = empty($orderBy) ? 'ASC' : 'DESC';
			$seeker = new PauseSeeker();
			list($total, $data) = $seeker->query($startTime, $endTime, $section, $pauseType, $dutyDepartment, $curPage, $perPage, $orderBy);
			$ret = array(
				'pager' => array(
					'curPage' => $curPage,
					'perPage' => $perPage,
					'total' => $total,
				),
				'data' => $data,
			);
			$this->renderJsonBms(true, 'OK', $ret);
			
		}catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());	
		}	
	}
	
	public function actionEditSave() {
		$id = $this->validateIntVal('id', 0);
		$dutyDepartment = $this->validateStringVal('dutyDepartment', '');
		$remark = $this->validateStringVal('remark', '');
		try{
			if(!empty($id)){
				$pause = LinePauseAR::model()->findByPk($id);
				$pause->duty_department = $dutyDepartment;
				$pause->remark = $remark;
				$pause->editor = Yii::app()->user->id;
				$pause->edit_time = date('YmdHis');
				
				$pause->save();
				
				$this->renderJsonBms(true, 'OK', '');	
			}else {
				throw new Exception('the pause record is not exist');
			}
		}catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionGetDutyDepartment() {
		$name = $this->validateStringVal('departmentName', '');
		try {
			if(!empty($name)){
				$seeker = new DepartmentSeeker();
				$type = '停线';
				$nameList = $seeker->getNameList($name, $type);
			}
			$this->renderJsonBms(true, 'OK', $nameList);
		} catch(Exception $e) {
			$thi->renderJsonBms(false, $e->getMessage());
		}
	}
}