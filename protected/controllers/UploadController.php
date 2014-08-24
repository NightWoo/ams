<?php
Yii::import('application.models.FileUpload.FileUpload');
class UploadController extends BmsBaseController
{
	public function actionUpload() {
		var_dump(FileUpload::uploadImages('image','/home/work/bms/web/bms/tmp/fileupload'));
	}

	public function actionIndex() {
		$this->render('index');
	}
}
