<?php
Yii::import('application.models.AR.HR.OrgDepartmentAR');
class OrgStructureSeeker
{
  public function __construct () {

  }

  public function getOrgStructure ($parentId=0) {
    $parentCondition = empty($parentId) ? '' : " AND parent_id={$parentId}";
    $sql = "SELECT
              id,
              parent_id AS parent,
              name,
              display_name,
              short_name,
              level,
              sort_number
            FROM
              org_department
            WHERE
              removed = 0 $parentCondition
            ORDER BY
              level, sort_number ASC";
    $datas = Yii::app()->db->createCommand($sql)->queryAll();

    return $datas;
  }

  public function get3LevelList () {
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
}