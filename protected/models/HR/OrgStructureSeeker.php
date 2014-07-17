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
    $sql = "SELECT * FROM org_department WHERE level>0 AND removed=0";
    $depts = Yii::app()->db->createCommand($sql)->queryAll();

    $level1 = array();
    $level2 = array();
    $level3 = array();
    foreach ($depts as $dept) {
      if ($dept['level'] == 1) {
        array_push($level1, $dept);
      } else if ($dept['level'] == 2) {
        if (empty($level2[$dept['parent_id']])) {
          $level2[$dept['parent_id']] = array();
        }
        array_push($level2[$dept['parent_id']], $dept);
      } else if ($dept['level'] == 3) {
        if (empty($level3[$dept['parent_id']])) {
          $level3[$dept['parent_id']] = array();
        }
        array_push($level3[$dept['parent_id']], $dept);
      }
    }

    return array(
      'level1' => $level1,
      'level2' => $level2,
      'level3' => $level3
    );
  }
}