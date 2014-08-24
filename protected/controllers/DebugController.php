<?php
Yii::import('application.models.AR.*');
Yii::import('application.models.*');

class DebugController extends BmsBaseController
{
	public function actionIndex () {
		$this->render('test');
	}

    public function actionTest () {
        $vin = $this->validateStringVal('vin', '');
        $material = $this->validateStringVal('material', '');
        // $transaction = Yii::app()->db->beginTransaction();
        try {
            $plan =PlanAR::model()->findByPk(14856);
            $ar = new PlanAR();
            $ar->setAttributes($plan->getAttributes(), false);
            $ar->id = null;
            $ar->total = $plan->ready;
            $ar->ready = $plan->ready;
            // $ar->priority = $priLastDate ++;
            $ar->plan_date = date('YmdHis');
            $ar->user_id = 101;
            $ar->create_time = date("Y-m-d H:i:s");
            $ar->save();

            $plan->total -= $plan->ready;
            $plan->ready = 0;
            $plan->save();

            $this->resetCarPlanId($plan->id, $ar->id);

            $this->renderJsonBms(true, 'OK', $plan);

        } catch(Exception $e) {
            // $this->transaction->rollback();
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
    }

    private function resetCarPlanId ($oldId, $newId) {
        $cars = CarAR::model()->findAll("plan_id=?", array($oldId));
        foreach($cars as $car) {
            $car->plan_id = $newId;
            $car->save();
        }

    }
}