<?php
class UpdateFaultStandardController extends CController
{
	public function actionUpdate() {
		$sql = "SELECT id, name FROM component";

		$components = Yii::app()->db->createCommand($sql)->queryAll();
        foreach($components as $component) {
			$sql = "UPDATE fault_standard SET component_name='{$component['name']}' WHERE component_id={$component['id']}";

			Yii::app()->db->createCommand($sql)->execute();
        }


	}
}
