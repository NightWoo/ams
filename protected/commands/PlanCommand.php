<?php
Yii::import('application.models.AR.PlanAR');
Yii::import('application.models.AR.CarAR');
class PlanCommand extends CConsoleCommand
{
	public function run($args) {
		$sysUid = $this->getSystemUserId();
		list($unfinishedPlans, $count) = $this->getUnfinishedPlans();
		$plansCurDate = $this->getCurdatePlans();
		$curDate = DateUtil::getCurDate();
		$lastDate = DateUtil::getLastDate();

		//将“未完成昨日计划”生成“当日计划”，并置顶
		if($count > 0 && !empty($plansCurDate)) {
			foreach($plansCurDate as $plan) {
				$plan->priority += $count;
				$plan->save();
			}
		}
		$pri = 0;
		if(!empty($unfinishedPlans)){
			foreach($unfinishedPlans as $plan) {
				$ar = new PlanAR();
				$ar->setAttributes($plan->getAttributes(),false);
				$ar->id = null;
				$ar->total = $plan->total - $plan->ready;
				$ar->priority = $pri ++;
				$ar->ready = 0;
				$ar->plan_date = $curDate;
				$ar->user_id = $sysUid;
				$ar->create_time = date("Y-m-d H:i:s");
				$ar->original_id = $plan->id;
				$ar->save();
			}
		}

		//将存在提前上线的“当日计划”从原当天计划中分离出来，生成为“昨日计划”,并调整原“当天计划”
		//1、“昨日计划”的计划数量total与完成数量ready均为提前上线量，即原“当天计划”在08:00工作日转换时的完成数量ready
		//2、原“当日计划”的计划数量total减去已提前上线量，完成数量ready归零
		//$plansCurDate = $this->getCurdatePlans();
		//$lastDate = DateUtil::getLastDate();

		$maxPriorityPlanLastDate = PlanAR::model()->find("plan_date=? ORDER BY priority DESC", array($lastDate));
		$priLastDate = 0;
		if(!empty($maxPriorityPlanLastDate)) {
			$priLastDate = $maxPriorityPlanLastDate->priority + 1;
		}
		foreach($plansCurDate as $plan) {
			if($plan->ready > 0) {
				$ar = new PlanAR();
				$ar->setAttributes($plan->getAttributes(), false);
				$ar->id = null;
				$ar->total = $plan->ready;
				$ar->ready = $plan->ready;
				$ar->priority = $priLastDate ++;
				$ar->plan_date = $lastDate;
				$ar->user_id = $sysUid;
				$ar->create_time = date("Y-m-d H:i:s");
				$ar->original_id = $plan->id;
				$ar->save();

				$plan->total -= $plan->ready;
				$plan->ready = 0;
				$plan->save();

				$this->resetCarPlanId ($plan->id, $ar->id);
				if($plan->total == 0){
					// $plan->delete();
					$plan->removed = 1;
					$plan->save();
				}
			}
		}
	}

	private function resetCarPlanId ($oldId, $newId) {
		$cars = CarAR::model()->findAll("plan_id=?", array($oldId));
		foreach($cars as $car) {
			$car->plan_id = $newId;
			$car->save();
		}

	}

	private function getLastDatePlans() {
		$lastDate = DateUtil::getLastDate();
		$plans = PlanAR::model()->findAll("plan_date=? ORDER BY priority ASC", array($lastDate));
		return $plans;
	}

	private function getSystemUserId() {
		$sql = "SELECT id FROM user WHERE username='system'";
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}

	private function getCurDatePlans() {
		$curDate = DateUtil::getCurDate();
		$plans = PlanAR::model()->findAll("plan_date=? ORDER BY priority ASC", array($curDate));
		return $plans;
	}

	private function getUnfinishedPlans() {
		$lastDate = DateUtil::getLastDate();
		$plans = PlanAR::model()->findAll("plan_date=? AND total-ready >0 ORDER BY priority ASC", array($lastDate));
		$count = PlanAR::model()->count("plan_date=? AND total-ready >0 ORDER BY priority ASC", array($lastDate));
		return array($plans, $count);
	}
}
