<?php
Yii::import("application.models.query.*");
class QueryController extends BmsBaseController
{
	public function actionQueryDutyDepartment() {
		try {
			$node = $this->validateStringVal('node', '');
			$query = new Query();
			$data = $query->queryDutyDepartment($node);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

	}
}
