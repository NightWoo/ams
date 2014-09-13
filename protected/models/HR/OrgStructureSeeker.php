<?php
Yii::import('application.models.AR.HR.OrgDepartmentAR');
class OrgStructureSeeker
{
  public function __construct() {

  }

  public function getOrgStructure($parentId=0) {
    $parentCondition = empty($parentId) ? '' : " AND parent_id={$parentId}";
    $sql = "SELECT
              id,
              parent_id AS parent,
              name,
              display_name,
              short_name,
              level,
              sort_number,
              manager_name
            FROM
              view_org_department
            WHERE
              removed = 0 $parentCondition
            ORDER BY
              level, sort_number ASC";
    $datas = Yii::app()->db->createCommand($sql)->queryAll();

    return $datas;
  }

  public function get3LevelList() {
    $sql = "SELECT * FROM org_department WHERE level>0 AND removed=0 ORDER BY level DESC";
    $depts = Yii::app()->db->createCommand($sql)->queryAll();

    $org = array();
    foreach ($depts as $dept) {
      $id = $dept['id'];
      $parentId = $dept['parent_id'];
      $level = $dept['level'];
      if (empty($org[$level])) {
        $org[$level] = array();
      }
      if (!empty($level)) {
        if (!empty($org[$level + 1]) && !empty($org[$level + 1][$id])) {
          $dept['children'] = $org[$level + 1][$id];
        }
        if (empty($org[$level][$parentId])) {
          $org[$level][$parentId] = array();
        }
        array_push($org[$level][$parentId], $dept);
      }
    }

    return array_values($org['1'])[0];
  }

  public function deptParents($parentId, $level) {
    $highArr = array();
    if ($level>1) {
      $sql = "SELECT * FROM org_department WHERE level<$level AND level>0";
      $highs = Yii::app()->db->createCommand($sql)->queryAll();
      foreach ($highs as $high) {
        $highArr[$high['id']] = $high;
      }
    }

    $org = array();
    $org[$level--] = array();
    for (; $level > 0; $level--) {
      $org[$level] = $highArr[$parentId];
      $parentId = $highArr[$parentId]['parent_id'];
    }

    return $org;
  }

  public function getManagerList() {
    $sql = "SELECT
              manager_id,
              manager_name,
              level,
              parent_dept_display_name
            FROM
              view_org_department
            WHERE
              manager_id > 0 AND
              removed = 0
            GROUP BY
              manager_id
            ORDER BY
              level ASC";
    $data = Yii::app()->db->createCommand($sql)->queryAll();
    return $data;
  }

  public static function getDeptManager($deptId) {
    $sql = "SELECT manager_id FROM org_department WHERE id=$deptId";
    $managerId  = Yii::app()->db->createCommand($sql)->queryScalar();
    return $managerId;
  }

  public static function getTrId() {
    $sql = "SELECT id From org_department WHERE UPPER(short_name)='TR'";
    $trId = Yii::app()->db->createCommand($sql)->queryScalar();
    return $trId;
  }

  public static function getFactoryManager() {
    $sql = "SELECT manager_id FROM org_department WHERE level=0";
    $managerId = Yii::app()->db->createCommand($sql)->queryScalar();
    return $managerId;
  }

  public static function getFactoryId() {
    $sql = "SELECT id FROM org_department WHERE level=0";
    $factoryId = Yii::app()->db->createCommand($sql)->queryScalar();
    return $factoryId;
  }

  public static function getChildren($parentId=1) {
    if (empty($parentId)) {
      $parentId = 1;
    }
    $sql = "SELECT * FROM view_org_department WHERE parent_id=$parentId AND removed=0 ORDER BY sort_number ASC";
    $list = Yii::app()->db->createCommand($sql)->queryAll();
    return $list;
  }
}