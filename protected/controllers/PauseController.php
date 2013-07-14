<?php
Yii::import('application.models.PauseSeeker');
Yii::import('application.models.DepartmentSeeker');
Yii::import('application.models.Shift');
Yii::import('application.models.AR.monitor.LinePauseAR');
Yii::import('application.models.AR.DutyDepartmentAR');
Yii::import('application.models.AR.PauseAR');


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
		$causeType = $this->validateStringVal('causeType', '');
		$pauseType = $this->validateStringVal('pauseType', '');
		$dutyDepartment = $this->validateStringVal('dutyDepartment', '');
		$pauseReason = $this->validateStringVal('pauseReason', '');
		$perPage = $this->validateIntVal('perPage', 10);
		$curPage = $this->validateIntVal('curPage', 1);
		$orderBy = $this->validateStringVal('orderBy', '');
		try{
			$orderBy = empty($orderBy) ? 'ORDER BY pause_time ASC' : $orderBy;
			$seeker = new PauseSeeker();
			list($total, $data) = $seeker->query($startTime, $endTime, $section,$pauseType, $causeType, $dutyDepartment, $pauseReason, $curPage, $perPage, $orderBy);
			$ret = array(
				'pager' => array(
					'curPage' => $curPage,
					'perPage' => $perPage,
					'total' => $total,
				),
				'data' => $data,
			);

			if(empty($data)){
				throw new Exception("无查询结果", 1);
			}

			$this->renderJsonBms(true, 'OK', $ret);

		}catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionQueryDistribute() {
		$stime = $this->validateStringVal('startTime', '');
		$etime = $this->validateStringVal('endTime', '');
		$section = $this->validateStringVal('section', '');
		$pauseType = $this->validateStringVal('pauseType', '');
		$causeType = $this->validateStringVal('causeType', '');
		$dutyDepartment = $this->validateStringVal('dutyDepartment', '');
		$pauseReason = $this->validateStringVal('pauseReason', '');
		try{
			$seeker = new PauseSeeker();
			$data = $seeker->queryDistribute($stime, $etime, $section,$pauseType, $causeType, $dutyDepartment, $pauseReason);
			$this->renderJsonBms(true, 'OK', $data);
		}catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionEditSave() {
		$id = $this->validateIntVal('id', 0);
		$causeType = $this->validateStringVal('causeType', '');
		$dutyDepartment = $this->validateStringVal('dutyDepartment', '');
		$remark = $this->validateStringVal('remark', '');
		try{
			$department = DutyDepartmentAR::model()->find("type=? AND display_name=?", array('停线',$dutyDepartment));
			if(empty($causeType)){
				throw new Exception("停线类型不可为空");
			}
			if(empty($department)){
				throw new Exception("责任部门需使用标准化名称", 1);
			}
			if(!empty($id)){
				$pause = LinePauseAR::model()->findByPk($id);
				$pause->cause_type = $causeType;
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
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionQueryUseRate() {
		$stime = $this->validateStringVal('startTime', '');
		$etime = $this->validateStringVal('endTime', '');
		$line = $this->validateStringVal('line', 'I');
		try{
			$seeker = new PauseSeeker();
			$data = $seeker->queryUseRate($stime, $etime, $line);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function actionExportRecord() {
		$startTime = $this->validateStringVal('startTime', '');
		$endTime = $this->validateStringVal('endTime', '');
		$section = $this->validateStringVal('section', '');
		$causeType = $this->validateStringVal('causeType', '');
		$dutyDepartment = $this->validateStringVal('dutyDepartment', '');
		$pauseReason = $this->validateStringVal('pauseReason', '');
		$orderBy = $this->validateStringVal('orderBy', '');

		try{
			$orderBy = empty($orderBy) ? 'ORDER BY pause_time ASC' : $orderBy;
			$seeker = new PauseSeeker();
			list($total, $datas) = $seeker->query($startTime, $endTime, $section, '', $causeType, $dutyDepartment, $pauseReason, 0, 0, $orderBy);
			$content = "recordID,停线类型,工位,责任部门,原因,时长,停线时刻,恢复时刻,编辑人\n";
			foreach($datas as $data) {
				$content .= "{$data['id']},";
				$content .= "{$data['cause_type']},";
				$content .= "{$data['node_name']},";
				$content .= "{$data['duty_department']},";
				$data['remark'] = str_replace(PHP_EOL, '', $data['remark']);
				$data['remark'] = str_replace(",", "，",$data['remark']);
				$content .= "{$data['remark']},";
				$content .= "{$data['howlong']},";
				$content .= "{$data['pause_time']},";
				$content .= "{$data['recover_time']},";
				$content .= "{$data['editor_name']}\n";
			}
			$export = new Export('停线明细_' . date('YmdHi'), $content);
			$export->toCSV();
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

	public function actionPlanPauseSave() {
		$id = $this->validateIntVal('id', 0);
		$startTime = $this->validateStringVal('startTime', '');
		$endTime = $this->validateStringVal('endTime', '');
		$remark = $this->validateStringVal('remark', '');
		$line = $this->validateStringVal('line', 'I');
		try{
			if(empty($startTime) || empty($endTime)){
				throw new Exception ('起止时间均不可为空');
			}

			// if(strtotime($endTime) <=strtotime($startTime)){
			// 	throw new Exception("结束时间不能大于起始时间", 1);

			// }

			$pause = PauseAR::model()->findByPk($id);
			if(empty($pause)){
				$pause = new PauseAR();
				$pause->pause_type = "计划停线";
				$pause->status = 0;
			}
			$pause->line = $line;
			$pause->pause_time = $startTime;
			$pause->recover_time = $endTime;
			$pause->remark = $remark;
			$pause->editor = Yii::app()->user->id;
			$pause->edit_time = date('YmdHis');
			$pause->save();

			$workDate = DateUtil::workDate($pause->pause_time);
			$shift = new Shift();
			$shift->updateCapacityDaily($workDate);

			$this->renderJsonBms(true, 'OK', '');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionDelete() {
		$id = $this->validateIntVal('id', 0);
        try{
        	$opUserId = Yii::app()->user->id;
            $user = User::model()->findByPk($opUserId);
            if(!$user->admin) {
                BmsLogger::warning($opUserId . " try to delete plan pause record @ " .$id);
                throw new Exception ('不要做坏事，有记录的！！');
            }
			$pause = PauseAR::model()->findByPk($id);
			if(!empty($pause)){
				$pause->delete();
			}
            $this->renderJsonBms(true, 'OK', '');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

	}
}