<?php
Yii::import('application.models.AR.LineAR');
Yii::import('application.models.AR.SeriesAR');
class CommonController extends BmsBaseController
{
	public function actionGetLineList () {
		try{
			$sql = "SELECT `line` FROM line";
			$lines = Yii::app()->db->createCommand($sql)->queryColumn();
			foreach($lines as $line) {
				$data[] = array("line"=>$line);
			}
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}

	public function actionGetSeriesList () {
		try{
			$seriesArray = SeriesAR::model()->findAll();
			$data = array();
			foreach($seriesArray as $one) {
				$temp = array(
					"series" => $one['series'],
					"name" => $one['name']
				);
				$data[] = $temp;
			}
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}
}
