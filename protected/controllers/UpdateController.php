<?php
class UpdateController extends CController
{
	public function actionUpdate20121028() {
		$sql = "SELECT id, name FROM component";

		$components = Yii::app()->db->createCommand($sql)->queryAll();
        foreach($components as $component) {
			$sql = "UPDATE fault_standard SET component_name='{$component['name']}' WHERE component_id={$component['id']}";

			Yii::app()->db->createCommand($sql)->execute();
        }


	}
	
	public function actionUpdate20121029() {
        $sql = "UPDATE component SET unique_barcode=0 WHERE name='371QA汽油机特征码'";
		Yii::app()->db->createCommand($sql)->execute();
    }

}
