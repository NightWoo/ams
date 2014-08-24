<?php
Yii::import('application.models.AR.HR.HrGradeAR');
Yii::import('application.models.AR.HR.HrPositionAR');
Yii::import('application.models.HR.HrPosition');
Yii::import('application.models.HR.HrPositionSeeker');

class PositionSystemController extends BmsBaseController
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

    }

    public function actionGetPositionDetail () {
        $positionId = $this->validateIntVal('positionId', 0);
        try {
            $seeker = new HrPositionSeeker();
            $data = $seeker->getPositionDetail($positionId);
            $this->renderJsonBms(true, 'get position detail success', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionSavePosition () {
        $id = $this->validateIntVal('positionId', 0);
        $detail = $this->validateStringVal('positionDetail', '{}');

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $position = HrPosition::createById($id);
            $position->save($detail);
            $transaction->commit();
            $this->renderJsonBms(true, 'save success', '');
        } catch(Exception $e) {
            $transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionRemovePosition () {
        $id = $this->validateIntVal('positionId', 0);

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $position = HrPosition::createById($id);
            $position->remove();
            $transaction->commit();
            $this->renderJsonBms(true, 'save success', '');
        } catch(Exception $e) {
            $transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionGetPositionList () {
        $channel = $this->validateStringVal('channel', '');
        $level = $this->validateIntVal('level', 0);
        try {
            $data = HrPosition::getPositionList($channel, $level);
            $this->renderJsonBms(true, 'get PositionList success', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionGetGradeList () {
        try {
            $data = HrPosition::getGradeList();
            $this->renderJsonBms(true, 'get GradeList success', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }
}