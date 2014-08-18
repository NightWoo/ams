<?php
Yii::import('application.models.AR.CityAR');
Yii::import('application.models.AR.ProvinceAR');
Yii::import('application.models.HR.OrgStructureSeeker');
class HrStaffSeeker
{
  public function __construct () {}

  public function provinceCityList() {
    $provinceSql = "SELECT * FROM province";
    $provinces = Yii::app()->db->createCommand($provinceSql)->queryAll();
    $citySql = "SELECT * FROM city";
    $cities = Yii::app()->db->createCommand($citySql)->queryAll();

    $data = array();
    foreach ($provinces as $province) {
      $data[$province['id']] = $province;
      $data[$province['id']]['cities'] = array();
    }

    foreach ($cities as $city) {
      array_push($data[$city['province_id']]['cities'],
        $city);
    }

    return array_values($data);
  }

  public function queryBasicInfo($employeeNum) {
    $sql = "SELECT
              *
            FROM
              view_hr_staff
            WHERE
              employee_number = '$employeeNum' AND
              staff_status = 0";
    $data =  Yii::app()->db->createCommand($sql)->queryRow();
    if (!empty($data)) {
      $data['dept_id'] = intval($data['dept_id']);

      $orgSeeker = new OrgStructureSeeker();
      $org = $orgSeeker->deptParents($data['dept_parent_id'], $data['dept_level']);
      $org[$data['dept_level']] = array(
        'display_name'=> $data['dept_display_name'],
        'short_name'=> $data['dept_short_name']
      );
      $data['org'] = $org;
    }
    return $data;
  }

  public function queryExp($staffId=0) {
    $sql = "SELECT * FROM hr_staff_experience WHERE staff_id = $staffId";
    $data = Yii::app()->db->createCommand($sql)->queryAll();
    return $data;
  }

  public function queryTransferApplyInfo($staffId) {
    $sql = "SELECT
              id,
              staff_id,
              process_type,
              apply_dept_id,
              apply_dept_parent_id,
              apply_dept_level,
              apply_dept_display_name,
              apply_dept_short_name,
              apply_position_display_name,
              apply_position_grade,
              apply_position_grade_name,
              reason,
              transfer_date,
              status
            FROM
              view_hr_transfer
            WHERE
              staff_id = $staffId AND
              status = 0
             ORDER BY
              create_time DESC";

    $data = Yii::app()->db->createCommand($sql)->queryRow();
    if(!empty($data)) {
      $orgSeeker = new OrgStructureSeeker();
      $org = $orgSeeker->deptParents($data['apply_dept_parent_id'], $data['apply_dept_level']);
      $org[$data['apply_dept_level']] = array(
        'display_name'=> $data['apply_dept_display_name'],
        'short_name'=> $data['apply_dept_short_name']
      );
      $data['apply_org'] = $org;
    }

    return $data;
  }

  public function queryApprovalInfo($transferId) {
    $sql = "SELECT
              id,
              transfer_id,
              conclusion,
              comment,
              approver_id,
              approval_status,
              process_type,
              procedure_type,
              procedure_name,
              procedure_descr,
              approver_display_name,
              approver_email,
              update_time
            FROM
              view_hr_approval
            WHERE
              transfer_id = $transferId AND
              approval_status > 0
            ORDER BY
              create_time, procedure_id DESC";
    $data = Yii::app()->db->createCommand($sql)->queryAll();
    if(!empty($data)) {
      foreach ($data as &$approval) {
        $approvalId = intval($approval['id']);
        $subSql = "SELECT
                    id,
                    conclusion,
                    point_description
                  FROM
                    view_hr_approval_sub
                  WHERE
                    approval_id = $approvalId
                  ORDER BY
                    procedure_point_id ASC";
        $approval['sub'] = Yii::app()->db->createCommand($subSql)->queryAll();
      }
    }

    return $data;
  }

  public function queryUserApproval() {
    $userId = Yii::app()->user->id;

    $sql = "SELECT
              transfer_id,
              staff_id,
              employee_number
            FROM
              view_hr_approval
            WHERE
              approver_id = $userId AND
              approval_status = 1";
    $approval = Yii::app()->db->createCommand($sql)->queryRow();

    $data = array(
        'basicInfo' => array(),
        'applyInfo' => array(),
        'approvalRecords' => array()
    );
    if (!empty($approval)) {
      $data['approvalRecords'] = $this->queryApprovalInfo($approval['transfer_id']);
      $data['basicInfo'] = $this->queryBasicInfo($approval['employee_number']);
      $data['applyInfo'] = $this->queryTransferApplyInfo($approval['staff_id']);
    }

    return $data;
  }

  public function countUserApproval() {
    $userId = Yii::app()->user->id;

    $sql = "SELECT
              COUNT(*)
            FROM
              view_hr_approval
            WHERE
              approver_id = $userId AND
              approval_status = 1";
    $count = Yii::app()->db->createCommand($sql)->queryScalar();
    return intval($count);
  }

  public function curMonthResignRate() {
    $startDate = date("Y-m-01");
    $endDate = date("Y-m-d");
    $resignCount = intval($this->countResign($startDate, $endDate));
    $staffCount = intval($this->countStaff());
    $rate = round($resignCount / ($staffCount + $resignCount), 2);
    return array(
      'resignCount' => $resignCount,
      'staffCount' => $staffCount,
      'resignRate' => $rate
    );
  }

  public function countStaff() {
    $sql = "SELECT COUNT(*) FROM hr_staff WHERE status = 0";
    $count = Yii::app()->db->createCommand($sql)->queryScalar();
    return intval($count);
  }

  public function countResign($startDate, $endDate='') {
    if (empty($endDate)) {
      $endDate = date("Y-m-d");
    }
    $sql = "SELECT COUNT(*) FROM hr_resign WHERE `date`>='$startDate' AND `date`<='$endDate'";
    $count = Yii::app()->db->createCommand($sql)->queryScalar();
    return intval($count);
  }

  public function queryStaffList($conditions, $pager) {
    $conditions =  is_array($conditions) ? $conditions : CJSON::decode($conditions);
    $pager = is_array($pager) ? $pager : CJSON::decode($pager);
    $conArr = array();
    if (!empty($conditions['gradeId'])) {
      $conArr[] = "grade_id = {$conditions['gradeId']}";
    }
    if (!empty($conditions['staffGrade'])) {
      $conArr[] = "staff_grade = '{$conditions['staffGrade']}'";
    }
    if (!empty($conditions['deptId'])) {
      $conArr[] = "(dept_id = {$conditions['deptId']} OR dept_parent_id = {$conditions['deptId']} OR parent_parent_id = {$conditions['deptId']})";
    }
    if (!$conditions['includeResigned']) {
      $conArr[] = "staff_status = 0";
    }
    $conditionText = join(" AND ", $conArr);
    if (!empty($conditionText)) {
      $conditionText = 'WHERE ' . $conditionText;
    } else {
      $conditionText = "";
    }
    $limit = "";
    if (!empty($pager['pageSize'])) {
      $offset = ($pager['pageNumber'] - 1) * $pager['pageSize'];
      $limit = "LIMIT $offset, {$pager['pageSize']}";
    }
    $sql = "SELECT *
            FROM view_hr_staff
              $conditionText
            ORDER BY enter_date ASC
              $limit";
    $data = Yii::app()->db->createCommand($sql)->queryAll();
    $data = $this->resovleStaffListOrg($data);

    $countSql = "SELECT COUNT(*) FROM view_hr_staff $conditionText";
    $count = Yii::app()->db->createCommand($countSql)->queryScalar();

    return array(
      "result"=>$data,
      "total"=>$count
    );
  }

  public function queryStaffInfo($employee, $pager) {
    $pager = is_array($pager) ? $pager : CJSON::decode($pager);
    $limit = "";
    if (!empty($pager['pageSize'])) {
      $offset = ($pager['pageNumber'] - 1) * $pager['pageSize'];
      $limit = "LIMIT $offset, {$pager['pageSize']}";
    }
    $sql = "SELECT * FROM view_hr_staff WHERE employee_number LIKE '%$employee' OR name LIKE '%$employee%' ORDER BY enter_date ASC
              $limit";
    $data = Yii::app()->db->createCommand($sql)->queryAll();
    $data = $this->resovleStaffListOrg($data);

    $countSql = "SELECT COUNT(*) FROM view_hr_staff WHERE employee_number LIKE '%$employee' OR name LIKE '%$employee%'";
    $count = Yii::app()->db->createCommand($countSql)->queryScalar();

    return array(
      "result"=>$data,
      "total"=>$count
    );
  }

  public function resovleStaffListOrg($data) {
    if (!empty($data)) {
      $orgSeeker = new OrgStructureSeeker();
      $parents = array();
      foreach ($data as &$staff) {
        if (intval($staff['dept_level']) > 1) {
          $parents = $orgSeeker->deptParents($staff['dept_parent_id'], $staff['dept_level']);
        }
        $parents[$staff['dept_level']] = array(
          "id" => $staff['dept_id'],
          "display_name" => $staff['dept_display_name'],
          "name" => $staff['dept_name'],
          "parent_id" => $staff['dept_parent_id'],
          "short_name" => $staff['dept_short_name'],
          "level" => $staff['dept_level']
        );
        $staff['dept_parents'] = $parents;
      }
    }
    return $data;
  }

}