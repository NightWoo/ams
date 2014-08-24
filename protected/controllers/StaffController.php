<?php
Yii::import('application.models.AR.HR.HrStaffAR');
Yii::import('application.models.AR.HR.HrPositionAR');
Yii::import('application.models.AR.HR.HrTransferAR');
Yii::import('application.models.HR.HrStaff');
Yii::import('application.models.HR.HrApproval');
Yii::import('application.models.HR.HrTransfer');
Yii::import('application.models.HR.HrStaffSeeker');

class StaffController extends BmsBaseController
{
  /**
   * Declares class-based actions.
   */
  public function actions() {
    return array();
  }

  public function actionIndex() {

  }

  public function actionSaveStaff() {
    $id = $this->validateIntVal('staffId',  0);
    $staffData = $this->validateStringVal('staffData', '{}');
    $expData = $this->validateStringVal('expData', '[]');

    $transaction = Yii::app()->db->beginTransaction();
    try {
      $staff = HrStaff::createById($id);
      $staff->save($staffData);
      $staff->saveExp($expData);
      $transaction->commit();
      $this->renderJsonApp(true, 'save success', '');
    } catch (Exception $e) {
      $transaction->rollback();
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionGetEditInfo() {
    $employeeNumber = $this->validateStringVal('employeeNumber', '');

    try {
      $seeker = new HrStaffSeeker();
      $basicInfo = $seeker->queryBasicInfo($employeeNumber);
      $exp = array();
      if (!empty($basicInfo)) {
        $exp = $seeker->queryExp($basicInfo['id']);
      }

      $data = array(
        "basicInfo" => $basicInfo,
        "exp" => $exp
      );

      $this->renderJsonApp(true, 'OK', $data);
    } catch (Exception $e) {
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionQueryBasicInfo() {
    $employeeNumber = $this->validateStringVal('employeeNumber', '');

    try {
      $seeker = new HrStaffSeeker();
      $basicInfo = $seeker->queryBasicInfo($employeeNumber);
      $this->renderJsonApp(true, 'OK', $basicInfo);
    } catch (Exception $e) {
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionQueryTansferInfo() {
    $employeeNumber = $this->validateStringVal('employeeNumber', '');

    try {
      $seeker = new HrStaffSeeker();
      $basicInfo = $seeker->queryBasicInfo($employeeNumber);
      $applyInfo = array();
      $approvalRecords = array();
      if (!empty($basicInfo)) {
        $applyInfo = $seeker->queryTransferApplyInfo($basicInfo['id']);
        if (!empty($applyInfo)) {
          $approvalRecords = $seeker->queryApprovalInfo($applyInfo['id']);
        }
      }

      $ret = array(
        'basicInfo' => $basicInfo,
        'applyInfo' => $applyInfo,
        'approvalRecords' => $approvalRecords
      );
      $this->renderJsonApp(true, 'OK', $ret);
    } catch (Exception $e) {
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionGetProvinceCityList()  {
    try {
      $seeker = new HrStaffSeeker();
      $data = $seeker->provinceCityList();
      $this->renderJsonApp(true, 'save success', $data);
    } catch (Exception $e) {
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionApplyTransfer() {
    $id = $this->validateIntVal('staffId', 0);
    $applyForm = $this->validateStringVal('applyForm', '{}');

    $transaction = Yii::app()->db->beginTransaction();
    try {
      $staff = HrStaff::createById($id);
      $data = $staff->applyTransfer($applyForm);
      $transaction->commit();

      $seeker = new HrStaffSeeker();
      $applyInfo = array();
      $approvalRecords = array();
      if (!empty($staff)) {
        $applyInfo = $seeker->queryTransferApplyInfo($id);
        if (!empty($applyInfo)) {
          $approvalRecords = $seeker->queryApprovalInfo($applyInfo['id']);
        }
      }
      $ret = array(
        'applyInfo' => $applyInfo,
        'approvalRecords' => $approvalRecords
      );
      $this->renderJsonApp(true, 'apply success', $ret);
    } catch (Exception $e) {
      $transaction->rollback();
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionGetApprovalInfo() {
    $employeeNumber = $this->validateStringVal('employeeNumber', '');

    try {
      $seeker = new HrStaffSeeker();
      $data = $seeker->queryApprovalInfo($employeeNumber);

      $this->randerJsonApp(true, 'ok', $data);
    } catch (Exception $e) {
      $this->randerJsonApp(false, $e->getMessage());
    }
  }

  public function actionGetMyApproval() {
    try {
      $seeker = new HrStaffSeeker();
      $data = $seeker->queryUserApproval();
      $this->renderJsonApp(true, 'ok', $data);
    } catch (Exception $e) {
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionSubmitApproval() {
    $approvalForm = $this->validateStringVal('approvalForm', '{}');
    $transferDate = $this->validateStringVal('transferDate', '');
    $transaction = Yii::app()->db->beginTransaction();
    try {
      $approvalForm = is_array($approvalForm) ? $approvalForm : CJSON::decode($approvalForm);
      $approval = HrApproval::createById($approvalForm['id']);
      $approval->approve($approvalForm);

      if (!empty($transferDate)) {

        $transfer = HrTransferAR::model()->findByPk($approvalForm['transfer_id']);
        $transfer->transfer_date = $transferDate;
        $transfer->save();
      }

      $transaction->commit();

      $approvalRecords = array();
      if (!empty($approvalForm)) {
        $seeker = new HrStaffSeeker();
        $approvalRecords = $seeker->queryApprovalInfo($approvalForm['transfer_id']);
      }

      $this->renderJsonApp(true, 'ok', $approvalRecords);
    } catch (Exception $e) {
      $transaction->rollback();
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionSubmitResign() {
    $staffId = $this->validateIntVal('staffId', 0);
    $resignForm = $this->validateStringVal('resignForm', '{}');

    $transaction = Yii::app()->db->beginTransaction();
    try {
      if (!empty($staffId)) {
        $staff = HrStaff::createById($staffId);
        $staff->resign($resignForm);
      } else {
        throw new Exception("staff id can not be empty");
      }
      $transaction->commit();
      $this->renderJsonApp(true, 'submit resign success', '');
    } catch (Exception $e) {
      $transaction->rollback();
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionQueryStaffInfo() {
    $employee = $this->validateStringVal('employee', '');
    try {
      $seeker = new HrStaffSeeker();
      $data = $seeker->queryStaffInfo($employee);
      $this->renderJsonApp(true, 'query success', $data);
    } catch (Exception $e) {
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionQueryStaffList() {
    $conditions = $this->validateStringVal('conditions', '{}');
    $employee = $this->validateStringVal('employee', '');
    $pager = $this->validateStringVal('pager', '{}');
    try {
      $seeker = new HrStaffSeeker();
      if (!empty($employee)) {
        $data = $seeker->queryStaffListByEmployee($employee, $pager);
      } else {
        $data = $seeker->queryStaffList($conditions, $pager);
      }
      $this->renderJsonApp(true, 'query success', $data);
    } catch (Exception $e) {
      $this->renderJsonApp(false, $e->getMessage());
    }
  }

  public function actionExportStaffList() {
    $conditions = $this->validateStringVal('conditions', '{}');
    $employee = $this->validateStringVal('employee', '');
    $isResigned = $this->validateIntVal('isResigned', 0);
    try{
      $pager = array("pageSize"=>0);
      $seeker = new HrStaffSeeker();

      if (!empty($employee)) {
        $datas = $seeker->queryStaffListByEmployee($employee, $pager);
      } else {
        $datas = $seeker->queryStaffList($conditions, $pager);
      }

      $title = "工号,姓名,性别,级别,岗位等级,科室,班,组,岗位,入厂日期,上岗日期,学历,考核关系,联系电话,身份证号,籍贯,学校,专业,底薪";
      if (empty($isResigned)) {
        $title .= "\n";
      } else {
        $title .= ",离职日期,离职类型,离职原因,离职原因说明\n";
      }
      $content = "";
      foreach($datas['result'] as $data) {
        $content .= "{$data['employee_number']},";
        $content .= "{$data['name']},";
        $gender = empty($data['gender']) ? "男" : "女";
        $content .= "{$gender},";
        $content .= "{$data['staff_grade']},";
        $content .= "{$data['grade_name']},";
        $level1 = empty($data['dept_parents']['1']) ? "--": $data['dept_parents']['1']['display_name'];
        $level2 = empty($data['dept_parents']['2']) ? "--": $data['dept_parents']['2']['display_name'];
        $level3 = empty($data['dept_parents']['3']) ? "--": $data['dept_parents']['3']['display_name'];
        $content .= "{$level1},";
        $content .= "{$level2},";
        $content .= "{$level3},";
        $content .= "{$data['position_display_name']},";
        $content .= "{$data['enter_date']},";
        $content .= "{$data['start_date']},";
        $content .= "{$data['education']},";
        $content .= "{$data['supervisor_name']},";
        $content .= "{$data['contact_phone']},";
        $content .= "{$data['id_number']},";
        $content .= "{$data['city_name']},";
        $content .= "{$data['school']},";
        $content .= "{$data['major']},";
        $basicSalary = "****";
        $userId = Yii::app()->user->id;
        if ( $userId==$data['manager_id'] || $userId==$data['parent_manager_id'] || $userId==$data['parent_parent_manager_id'] || $userId==$data['id']) {
          $basicSalary = $data['basic_salary'];
        }
        $content .= "{$basicSalary},";
        if (!empty($isResigned)) {
          $content .= "{$data['resign_date']},";
          $content .= "{$data['resign_type']},";
          $reasons = str_replace(',', '、', $data['resign_reason']);
          $content .= "{$reasons},";
          $content .= "{$data['resign_reason_desc']},";
        }
        $content .= "\n";
      }
      $export = new Export( '员工库查询_' . date('Ymd'), $title . $content);
      $export->toCSV();
    } catch(Exception $e) {
    }
  }

  public function actionHomeInfo() {
    try {
      $seeker = new HrStaffSeeker();
      $resignInfo = $seeker->curMonthResignRate();
      $taskCount = $seeker->countUserApproval();
      $data = array(
        'taskCount' => $taskCount,
        'resignInfo' => $resignInfo
      );
      $this->renderJsonApp(true, 'query success', $data);
    } catch (Exception $e) {
      $this->renderJsonApp(false, $e->getMessage());
    }
  }
}