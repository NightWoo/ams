<?php
class UpdateController extends BmsBaseController
{
    public function actionUpdate169() {
        try{
            $sql = "UPDATE node_trace SET user_id=427 WHERE user_id=169;
UPDATE node_trace SET driver_id=427 WHERE driver_id=169;
UPDATE `order` SET user_id=427 WHERE user_id=169;";
            Yii::app()->dbAdmin->createCommand($sql)->execute();
            $this->renderJsonBms(true, 'OK', 'OK');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }

}