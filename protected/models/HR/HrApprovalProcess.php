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
    $processManagers = array(
      '1' => OrgStructureSeeker::getDeptManager($curDept['id']),
      '2' => OrgStructureSeeker::getDeptManager($applyParents[1]['id']),
      '3' => OrgStructureSeeker::getTrManager(),
      '4' => 0,
      '5' => 2, //system
    );
    if ($curParents[1]['id'] === $applyParents[1]['id']) {
      if ($curParents[2]['id'] !== $applyParents[2]['id']) {
        $type = 'differentLevel2';
        $processManagers['2'] = OrgStructureSeeker::getDeptManager($applyParents[2]['id']);
      } else {
        $type = 'sameLevel2';
        $processManagers['2'] = OrgStructureSeeker::getDeptManager($curDept['id']);
      }
    } else {
      $processManagers['4'] = OrgStructureSeeker::getFactoryManager();
    }

    return array($type, $processManagers);
  }
}