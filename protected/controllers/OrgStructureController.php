<?php
Yii::import('application.models.AR.HR.OrgDepartmentAR');
Yii::import('application.models.HR.OrgDepartment');
Yii::import('application.models.HR.OrgStructureSeeker');
Yii::import('application.models.HR.HrPositionSeeker');

class OrgStructureController extends BmsBaseController
{
    /**
     * Declares class-based actions.
     */
    public function actions ()
    {
        return array(
        );
    }

    public function actionIndex () {
        $this->actionGetStructure();
    }

    public function actionGetStructure () {
        try {
            $seeker = new OrgStructureSeeker();
            $datas = $seeker->getOrgStructure();
            $this->renderJsonBms(true, 'get orgStructure success', $datas);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    public function actionDepartmentSave () {
        $id = $this->validateIntVal('deptId', 0);
        $deptData = $this->validateStringVal('deptData', '{}');
        $managerNumber = $this->validateStringVal('managerNumber', '');
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $dept = OrgDepartment::createById($id);
            if(empty($id)) {
                $dept->generate($deptData);
            } else {
                $dept->modify($deptData);
            }
            $user = User::model()->find('card_number=? AND isdelete=0', array($managerNumber));
            if (!empty($user)) {
                $dept->_ar->manager_id = $user->id;
                $dept->_ar->save();
            }
            $transaction->commit();
            $this->renderJsonBms(true, 'save success', '');
        } catch(Exception $e) {
            $transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionDepartmentRemove () {
        $id = $this->validateIntVal('deptId', 0);
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $dept = OrgDepartment::createById($id);
            $dept->remove();
            $transaction->commit();
            $this->renderJsonBms(true, 'save success', '');
        } catch(Exception $e) {
            $transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionDepartmentSortUp () {
        $id = $this->validateIntVal('deptId', 0);
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $dept = OrgDepartment::createById($id);
            $dept->sortUp();
            $transaction->commit();
            $this->renderJsonBms(true, 'save success', '');
        } catch(Exception $e) {
            $transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionDepartmentSortDown () {
        $id = $this->validateIntVal('deptId', 0);
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $dept = OrgDepartment::createById($id);
            $dept->sortDown();
            $transaction->commit();
            $this->renderJsonBms(true, 'save success', '');
        } catch(Exception $e) {
            $transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionGetDeptList () {
        $id = $this->validateIntVal('deptId', 0);
        try {
            $dept = OrgDepartment::createById($id);
            $data = $dept->getDeptList();
            $this->renderJsonBms(true, 'get DeptList success', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionGetChildren () {
        $id = $this->validateIntVal('deptId', 0);
        try {
            $dept = OrgDepartment::createById($id);
            $data = $dept->getChildren();
            $this->renderJsonBms(true, 'get childrenDepts success', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionGet3LevelList () {
        try {
            $seeker = new OrgStructureSeeker();
            $data = $seeker->get3LevelList();
            $this->renderJsonBms(true, 'get 3 level list success', $data);
        } catch (Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }
}