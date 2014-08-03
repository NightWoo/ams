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

  public function queryTransferBasicInfo($employeeNum) {
    $sql = "SELECT
              id,
              employee_number,
              name,
              gender,
              dept_id,
              dept_parent_id,
              dept_level,
              dept_display_name,
              dept_short_name,
              grade_name,
              staff_grade,
              position_display_name,
              position_short_name,
              basic_salary,
              start_date
            FROM
              view_hr_staff_basic_info
            WHERE
              employee_number = '$employeeNum'";
    $data =  Yii::app()->db->createCommand($sql)->queryRow();
    $data['dept_id'] = intval($data['dept_id']);

    $orgSeeker = new OrgStructureSeeker();
    $org = $orgSeeker->deptParents($data['dept_parent_id'], $data['dept_level']);
    $org[$data['dept_level']] = array(
      'display_name'=> $data['dept_display_name'],
      'short_name'=> $data['dept_short_name']
    );
    $data['org'] = $org;
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
              approver_user_id,
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
}