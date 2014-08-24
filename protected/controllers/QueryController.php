<?php
Yii::import("application.models.query.*");
Yii::import("application.models.AR.*");
class QueryController extends BmsBaseController
{
	public function actionCarQuery () {
		$this->render('carQuery');
	}

	public function actionGetDutyDepartment() {
		try {
			$node = $this->validateStringVal('node', '');
			$query = new Query();
			$data = $query->getDutyDepartment($node);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}

	public function actionGetDutyGroupList () {
		$type = $this->validateStringVal('type', '');
		try {
			$seeker = new DepartmentSeeker();
			$nameList = $seeker->getDutyGroupList($type);
			$this->renderJsonBms(true, 'OK', $nameList);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
}
