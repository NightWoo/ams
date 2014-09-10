<?php
Yii::import('application.models.AR.HR.HrProcedureAR');
Yii::import('application.models.AR.HR.HrProcedurePointAR');
Yii::import('application.models.AR.HR.OrgDepartmentAR');
Yii::import('application.models.HR.OrgStructureSeeker');

class HrApprovalProcess {
  private static $TYPES = array (
    'sameLevel2' => array(1,2,3,5),
    'differentLevel2' => array(1,2,3,5),
    'differentLevel1' => array(1,2,3,5)
  );
  public function __construct() {}

  public static function create($type) {}

  public static function typeProcess($curDeptId, $applyDeptId) {
    $curDept = OrgDepartmentAR::model()->findByPk($curDeptId);
    $applyDept = OrgDepartmentAR::model()->findByPk($applyDeptId);
    $orgSeeker = new OrgStructureSeeker();
    $curParents = $orgSeeker->deptParents($curDept->parent_id, $curDept->level);
    $applyParents = $orgSeeker->deptParents($applyDept->parent_id, $applyDept->level);

    $type = 'differentLevel1';
    $processDepts = array(
      '1' => isset($curParents[1]['id']) ? $curParents[1]['id'] : $curDept->id,
      '2' => isset($applyParents[1]['id']) ? $applyParents[1]['id'] : $applyDept->id,
      '3' => OrgStructureSeeker::getTrId(),
      '4' => 0,
      '5' => -1,
    );

    if (isset($curParents[1]['id']) && isset($applyParents[1]['id'])) {
      if ($curParents[1]['id'] === $applyParents[1]['id']) {
        // if (empty($curParents[2]) || empty($applyParents[2]) || ($curParents[2]['id'] !== $applyParents[2]['id'])) {
          $type = 'differentLevel2';
          $processDepts['1'] = empty($curParents[2]) ? $curDept['id'] : $curParents[2]['id'];
          $processDepts['2'] = empty($applyParents[2]) ? $applyDept['id'] : $applyParents[2]['id'];
        // } else {
        //   $type = 'sameLevel2';
        //   $processDepts['1'] = empty($curParents[2]) ? $curDept['id'] : $curParents[2]['id'];
        //   $processDepts['2'] = empty($applyParents[2]) ? $applyDept['id'] : $applyParents[2]['id'];
        // }
      } else {
        $processDepts['4'] = OrgStructureSeeker::getFactoryId();
      }
    }

    return array($type, $processDepts);
  }
}