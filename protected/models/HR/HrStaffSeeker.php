<?php
Yii::import('application.models.AR.CityAR');
Yii::import('application.models.AR.ProvinceAR');
Yii::import('application.models.HR.OrgStructureSeeker');
class HrStaffSeeker
{
  public function __construct () {}

  public static $STAFF_GRADE = array("I1", "I2", "I3", "H1", "H2", "H3", "G1", "G2", "G3", "F1", "F2", "F3", "E1", "E2", "E3", "D1", "D2", "D3");

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
    $resignCount = $this->countResign($startDate, $endDate);
    $staffCount = $this->countCurrent();
    $rate = round($resignCount / ($staffCount + $resignCount), 2);
    return array(
      'resignCount' => $resignCount,
      'staffCount' => $staffCount,
      'resignRate' => $rate
    );
  }

  public function queryStaffList($conditions, $pager=array('pageSize'=>0)) {
    $conditions =  is_array($conditions) ? $conditions : CJSON::decode($conditions);
    $pager = is_array($pager) ? $pager : CJSON::decode($pager);
    $conArr = $this->commonConditionArr($conditions);
    if (!empty($conditions['isResigned']) && $conditions['isResigned']) {
      $conArr[] = "staff_status = 1";
      if (!empty($conditions['startDate'])) {
        $conArr[] = "resign_date >= '{$conditions['startDate']}'";
      }
      if (!empty($conditions['endDate'])) {
        $conArr[] = "resign_date <= '{$conditions['endDate']}'";
      }
    } else {
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

  public function commonConditionArr($conditions) {
    $conArr = array();
    if (!empty($conditions['gradeId'])) {
      $conArr[] = "grade_id = {$conditions['gradeId']}";
    }
    if (!empty($conditions['position'])) {
      $conArr[] = "(UPPER(position_short_name) LIKE '%{$conditions['position']}%' OR position_display_name LIKE '%{$conditions['position']}%' OR UPPER(position_name) LIKE '%{$conditions['position']}%')";
    } else if (!empty($conditions['staffGrade'])) {
      $conArr[] = "staff_grade = '{$conditions['staffGrade']}'";
    }

    if (!empty($conditions['deptId'])) {
      $conArr[] = "(dept_id = {$conditions['deptId']} OR dept_parent_id = {$conditions['deptId']} OR parent_parent_id = {$conditions['deptId']})";
    }
    if (isset($conditions['gender']) && $conditions['gender'] > -1) {
      $conArr[] = "gender = {$conditions['gender']}";
    }
    if (!empty($conditions['provinceId'])) {
      $conArr[] = "province_id = {$conditions['provinceId']}";
    }
    if (!empty($conditions['cityId'])) {
      $conArr[] = "native_city_id = {$conditions['cityId']}";
    }
    if (!empty($conditions['education'])) {
      $conArr[] = "education = '{$conditions['education']}'";
    }
    if (!empty($conditions['major'])) {
      $conArr[] = "major LIKE '%{$conditions['major']}%'";
    }

    return $conArr;
  }

  public function queryStaffListByEmployee($employee, $pager) {
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

  public function queryStaffInfo($employee) {
    $sql = "SELECT * FROM view_hr_staff WHERE employee_number = '$employee' OR name = '$employee'";
    $staff = Yii::app()->db->createCommand($sql)->queryRow();
    if (!empty($staff)) {
      $staff = $this->resovleStaffOrg($staff);
    }
    return $staff;
  }

  public function resovleStaffListOrg($data) {
    if (!empty($data)) {
      $orgSeeker = new OrgStructureSeeker();
      $parents = array();
      foreach ($data as &$staff) {
        $staff = $this->resovleStaffOrg($staff);
      }
    }
    return $data;
  }

  public function resovleStaffOrg($staff) {
    $orgSeeker = new OrgStructureSeeker();
    $parents = array();
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
    return $staff;
  }

  public function queryTransferRecord($staffId) {
    $sql = "SELECT * FROM view_hr_transfer WHERE staff_id = $staffId";
    $data = Yii::app()->db->createCommand($sql)->queryAll();
    $orgSeeker = new OrgStructureSeeker();
    foreach ($data as &$record) {
      $parents = array();
      if (intval($record['apply_dept_level']) > 1) {
        $parents = $orgSeeker->deptParents($record['apply_dept_parent_id'], $record['apply_dept_level']);
      }
      $parents[$record['apply_dept_level']] = array(
        "id" => $record['apply_dept_id'],
        "display_name" => $record['apply_dept_display_name'],
        "name" => $record['apply_dept_name'],
        "parent_id" => $record['apply_dept_parent_id'],
        "short_name" => $record['apply_dept_short_name'],
        "level" => $record['apply_dept_level']
      );
      $record['dept_parents'] = $parents;
    }
    return $data;
  }

  public function queryAnalysisIn($conditions) {
    $conditions =  is_array($conditions) ? $conditions : CJSON::decode($conditions);
    $staffData = $this->queryStaffList($conditions);
    $deptId = empty($conditions['deptId']) ? 1 : $conditions['deptId'];
    $level = $conditions['countLevel'];
    $analysis = $this->resolveAnalysisIn($staffData['result'], $deptId, $level);
    return $analysis;
  }

  public function resolveAnalysisIn($staffData, $deptId, $level) {
    $analysis = array();
    $orgData = $this->initAnalysisOrg($deptId);
    $gradeData = $this->initAnalysisGrade();
    $staffGradeData = $this->initAnalysisStaffGrade();
    $nativeData = array(
      'province' => array(),
      'city' => array()
    );
    $eduData = array();
    $genderData = array(
      array('name'=>'男', 'y'=>0),
      array('name'=>'女', 'y'=>0)
    );
    foreach ($staffData as $staff) {
      if (empty($staff['dept_parents']) || empty($staff['dept_parents'][$level])) {
        continue;
      } else {
        $org = $staff['dept_parents'][$level];
        if (!empty($orgData[$org['id']])) {
          $orgData[$org['id']]['y']++;
        }
      }

      if (empty($eduData[$staff['education']])) {
        $eduData[$staff['education']] = array(
          'name' => $staff['education'],
          'y' => 0
        );
      }

      if (empty($nativeData['province'][$staff['province_id']])) {
        $nativeData['province'][$staff['province_id']] = array(
          'name' => $staff['province_name'],
          'y' => 0
        );
      }
      if (empty($nativeData['city'][$staff['native_city_id']])) {
        $nativeData['city'][$staff['native_city_id']] = array(
          'name' => $staff['city_name'],
          'y' => 0
        );
      }

      $gradeData[$staff['grade_id']]['y']++;
      $staffGradeData[$staff['staff_grade']]['y']++;
      $nativeData['province'][$staff['province_id']]['y']++;
      $nativeData['city'][$staff['native_city_id']]['y']++;
      $genderData[$staff['gender']]['y']++;
      $eduData[$staff['education']]['y']++;
    }


    $analysis['org'] = array_values($orgData);
    $analysis['grade'] = array_values($gradeData);
    $analysis['staffGrade'] = array_values($staffGradeData);
    $analysis['province'] = array_values($nativeData['province']);
    $analysis['city'] = array_values($nativeData['city']);
    $analysis['gender'] = array_values($genderData);
    $analysis['edu'] = array_values($eduData);
    return $analysis;
  }

  public function initAnalysisOrg($deptId) {
    $analysisData = array();
    $orgList = OrgStructureSeeker::getChildren($deptId);
    foreach ($orgList as $org) {
      $analysisData[$org['id']] = array(
        'name' => $org['display_name'],
        'y' => 0,
        'id' => $org['id'],
        'shortName' => $org['short_name'],
        'managerName' => $org['manager_name']
      );
    }
    return $analysisData;
  }

  public function initAnalysisGrade() {
    $sql = "SELECT * FROM hr_grade ORDER BY channel, level DESC";
    $gradeList = Yii::app()->db->createCommand($sql)->queryAll();

    $color = array(
      '管理' => '#428bca',
      '技术专家' => '#5cb85c',
      '技能' => '#f0ad4e'
    );
    $analysisData = array();
    foreach ($gradeList as $grade) {
      $analysisData[$grade['id']] = array(
        'name' => $grade['grade_name'] . '(' . $grade['grade'] . ')',
        'grade' => $grade['grade'],
        'channel' => $grade['channel'],
        'y' => 0
      );
      if (!empty($color[$grade['channel']])) {
        $analysisData[$grade['id']]['color'] = $color[$grade['channel']];
      }
    }
    return $analysisData;
  }

  public function initAnalysisStaffGrade() {
    $gradeList = self::$STAFF_GRADE;

    $analysisData = array();
    foreach ($gradeList as $grade) {
      $analysisData[$grade] = array(
        'name' => $grade,
        'y' => 0
      );
    }
    return $analysisData;
  }


  public function queryAnalysisOutOrg($conditions) {
    $deptId = empty($conditions['deptId']) ? 1 : $conditions['deptId'];
    $analysis = $this->initAnalysisOrg($deptId);
    $orgCon = $conditions;
    $curDate = date('Y-m-d');
    $startDate = !empty($conditions['startDate']) ? $conditions['startDate'] : '';
    $endDate = !empty($conditions['endDate']) ? $conditions['endDate'] : $curDate;
    foreach ($analysis as &$org) {
      $orgCon['deptId'] = $org['id'];
      $conArr = $this->commonConditionArr($orgCon);
      $org['y'] = $this->calculateResignRate($startDate, $endDate, $conArr);
    }
    $analysis = $this->multi_array_sort(array_values($analysis), 'y', SORT_DESC);
    return $analysis;
  }

  public function queryAnalysisOutTrend($conditions) {
    $analysis = array(array(), array());
    $datePeriods = $this->parsePeriod($conditions['startDate'], $conditions['endDate']);
    $conArr = $this->commonConditionArr($conditions);
    foreach ($datePeriods as $period) {
      $analysis[0][] = array(
        'name' => $period['point'],
        'y' => $this->countResign($period['sDate'], $period['eDate'], $conArr, true)
      );
      $analysis[1][] = array(
        'name' => $period['point'],
        'y' => $this->calculateResignRate($period['sDate'], $period['eDate'], $conArr)
      );

    }
    return $analysis;
  }

  public function queryAnalysisOutReason($conditions) {
    $analysis = array();
    $staffData = $this->queryStaffList($conditions)['result'];
    foreach ($staffData as $staff) {
      $reasons = explode(",", $staff['resign_reason']);
      foreach ($reasons as $reason) {
        if (empty($analysis[$reason])) {
          $analysis[$reason] = array(
            'name' => $reason,
            'y' => 0,
          );
        }
        $analysis[$reason]['y']++;
      }
    }

    return array_values($analysis);
  }

  public function calculateResignRate($startDate, $endDate, $conArr) {
    $countCurrent = $this->countCurrent($conArr);

    $curDate = date('Y-m-d');
    $countAddFromEnd = $this->countAdd($endDate, $curDate, $conArr);
    $countResignFromEnd = $this->countResign($endDate, $curDate, $conArr);
    $countStaffIn = $countCurrent - $countAddFromEnd + $countResignFromEnd;

    $countResign = $this->countResign($startDate, $endDate, $conArr, true);

    $countStaffBasic = $countStaffIn + $countResign;

    $rate = empty($countStaffBasic) ? null : ($countResign / $countStaffBasic);

    return round($rate, 2);
  }

  public function countCurrent($conArr=array()) {
    $conArr[] = "staff_status = 0";
    $conditionText = "WHERE " . join(' AND ', $conArr);
    $sql = "SELECT COUNT(*) FROM view_hr_staff $conditionText";
    $count = Yii::app()->db->createCommand($sql)->queryScalar();
    return intval($count);
  }

  public function countAdd($fromDate, $toDate, $conArr, $includeFrom = false) {
    $conArr[] = $includeFrom ? "resign_date>='$fromDate'" : "resign_date>'$fromDate'";
    $conArr[] = "enter_date<='$toDate'";
    $conditionText = "WHERE " . join(' AND ', $conArr);
    $sql = "SELECT COUNT(*) FROM view_hr_staff $conditionText";
    $count = Yii::app()->db->createCommand($sql)->queryScalar();
    return intval($count);
  }

  public function countResign($fromDate, $toDate, $conArr=array(), $includeFrom = false) {
    $conArr[] = $includeFrom ? "resign_date>='$fromDate'" : "resign_date>'$fromDate'";
    $conArr[] = "resign_date<='$toDate'";
    $conditionText = "WHERE " . join(' AND ', $conArr);
    $sql = "SELECT COUNT(*) FROM view_hr_staff $conditionText";

    $count = Yii::app()->db->createCommand($sql)->queryScalar();
    return intval($count);
  }

  public function multi_array_sort ($multi_array,$sort_key,$sort=SORT_ASC) {
    if(is_array($multi_array)){
        foreach ($multi_array as $row_array){
            if(is_array($row_array)){
                $key_array[] = $row_array[$sort_key];
            }else{
                return -1;
            }
        }
    }else{
        return -1;
    }
    array_multisort($key_array,$sort,$multi_array);
    return $multi_array;
  }

  public function parsePeriod($from, $to) {
    $s = strtotime($from);
    $e = strtotime($to);
    $ret = array();

    $format = 'Y-m-d';
    $timespan = $e - $s;
    if ($timespan < 86400 * 365) {
      $pointFormat = 'Y-m';
    } else {

      $pointFormat = 'Y';
    }

    $t = $s;
    while($t<$e) {
      $point = date($pointFormat, $t);
      if ($pointFormat === 'Y-m') {
        $eNextM = strtotime('first day of next month', $t);     //next month
        $ee = date('Y-m-d', $eNextM);                           //next month firstday
        $etmp = strtotime($ee);                               //next month firstday
        $eDate = date('Y-m-t', $t);
      } else {
        $eNextY = strtotime('first day of next year', $t);     //next month
        $ee = date('Y-1-1', $eNextY);                           //next month firstday
        $etmp = strtotime($ee);                                //next month firstday
        $eDate = date('Y-12-31', $t);
      }

      $ret[] = array(
        'sDate' => date($format, $t),
        'eDate' => $eDate,
        'point' => $point,
      );
      $t = $etmp;
    }

    return $ret;
  }

}