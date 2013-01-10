<?php
Yii::import('application.models.Order');
Yii::import('application.models.OrderSeeker');
Yii::import('application.models.AR.OrderAR');

class OrderController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionSearchByDate() {
		$standbyDate = $this->validateStringVal('standbyDate', '');
		$status = $this->validateIntVal('status', 0);
		//$curDate = DateUtil::getCurDate();
		try {
			$seeker = new OrderSeeker();
			$data = $seeker->searchBydate($standbyDate, $status);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionInc() {
		$id = $this->validateIntVal('id', 0);
		try {
			$order = OrderAR::model()->findByPk($id);
			if(!empty($order)) {
				$higher = OrderAR::model()->find('priority=? AND standby_date=?', array($order->priority - 1, $order->standby_date));
				if(!empty($higher)){
					$order->priority = $higher->priority;
					$higher->priority = $higher->priority + 1;
					
					$order->save();
					$higher->save();
				}
			}
			$this->renderJsonBms(true, 'OK', '');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionTop() {
		$id = $this->validateIntVal('id', 0);
		try {
			$order = OrderAR::model()->findByPk($id);
			if(!empty($order)) {
				$highers = OrderAR::model()->findAll('priority<? AND standby_date=?', array($order->priority, $order->standby_date));
				if(!empty($highers)) {
					$order->priority = 0;
					foreach($highers as $higher) {
						$higher->priority = $higher->priority + 1;
						$higher->save();
					}
					$order->save();
				}
			}
			$this->renderJsonBms(true, 'OK', '');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}	
	}

	public function actionSave() {
		$id = $this->validateIntVal('id', '');
		$standbyDate = $this->validateStringVal('standbyDate', date('Y-m-d'));
		$status = $this->validateIntVal('status', 0);
		$lane = $this->validateStringVal('lane', '');
		$carrier = $this->validateStringVal('carrier', '');
		$city = $this->validateStringVal('city', '');
		$distributorId = $this->validateStringVal('distributorId', '');
		$orderNumber = $this->validateStringVal('orderNumber', '');
		$amount = $this->validateStringVal('amount', '');
		$series = $this->validateStringVal('series', '');
		$carType = $this->validateStringVal('carType', '');
		$orderConfig = $this->validateIntVal('orderConfig', 0);
		$color =$this->validateStringVal('color', '');
		$coldResistant = $this->validateIntVal('coldResistant', 0);
		$carYear = $this->validateStringVal('carYear', '');
		$orderType = $this->validateStringVal('orderType', '');
		$remark = $this->validateStringVal('remark', '');
		try {
			if(empty($standbyDate)) {
				throw new Exception('备车日期不能为空');
			}
			if(empty($orderNumber)) {
				throw new Exception('订单号不能为空');
			}
			if(empty($series)) {
				throw new Exception('车系不能为空');
			}
			if(empty($carType)) {
				throw new Exception('车型不能为空');
			}
			if(empty($orderConfig)) {
				throw new Exception('配置不能为空');
			}
			if(empty($color)) {
				throw new Exception('颜色不能为空');
			}
			if(empty($distributorId)) {
				throw new Exception('经销商不能为空且需使用标准化名称');
			}

			$order = OrderAR::model()->findByPk($id);
			if(empty($order)) {
				$order = new OrderAR();
				$order->id = $id;
				$order->create_time = date('YmdHis');
				$max = OrderAR::model()->find('standby_date=? ORDER BY priority DESC', array($standbyDate));
				$order->priority = 0;
				if(!empty($max)) {
					$order->priority = $max->priority + 1;
				}
			}

			$samePriority = OrderAR::model()->find('standby_date=? AND priority=?', array($standbyDate, $order->priority));
			if(!empty($samePriority) && $samePriority->id != $id) {
				$max = OrderAR::model()->find('standby_date=? ORDER BY priority DESC', array($standbyDate));
				$order->priority = $max->priority + 1;
			}

			$order->standby_date = $standbyDate;
			$order->status = $status;
			$order->lane = $lane;
			$order->carrier = $carrier;
			$order->city = $city;
			$order->distributor_id = $distributorId;
			$order->order_number = $orderNumber;
			$order->amount = $amount;
			$order->series = $series;
			$order->car_type = $carType;
			$order->order_config_id = $orderConfig;
			$order->color = $color;
			$order->cold_resistant = $coldResistant;
			//$order->car_year = $carYear;
			$order->order_type = $orderType;
			$order->remark = $remark;

			$order->modify_time = date('YmdHis');
			$order->user_id = Yii::app()->user->id;

			$order->save();

			$this->renderJsonBms(true, 'OK', '');

		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionDelete() {
		$id = $this->validateIntVal('id', 0);
		try {
			$order = OrderAR::model()->findByPk($id);
			if(!empty($order)) {
				$lowers = OrderAR::model()->findAll('standby_date=? AND priority>?', array($order->standby_date, $order->priority));
				foreach($lowers as $lower) {
					$lower->priority -= 1;
					$lower->save();
				}
				$order->delete();
			}
			$this->renderJsonBms(true, 'OK', '');
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionGetDistributorList() {
		$name = $this->validateStringVal('distributorName', '');
		try {
			if(empty($name)){
				throw new Exception('distributor name cannot be null');
			}
			$seeker = new DistributorSeeker;
			$data = $seeker->getNameList($name);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionGetDistributorId() {
		$name = $this->validateStringVal('distributorName', '');
		try {
			if(empty($name)){
				throw new Exception('distributor name cannot be null');
			}
			$seeker = new DistributorSeeker;
			$data = $seeker->getDistributorId($name);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionGetCarStandby() {
		$transaction = Yii::app()->db->beginTransaction();
		try {
			//$standbyDate = $this->validateStringVal('standbyDate', '');
			//if(empty($standbyDate)) {
			//	throw new Exception('standby date cannot be null');
			//}
			$curDate = DateUtil::getCurDate();
			$order = new Order;
			$data = $order->getCarStandby($curDate);
			
			$transaction->commit();
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$transaction->rollback();
			$this->renderJsonBms(false, $e->getMessage());
		}
	}

	public function actionHoldRelease() {
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$vin = $this->validateStringVal('vin', '');
			$releaseOnly = $this->validateIntVal('releaseOnly', 0);

			//$car = CarAR::model()->find('vin=?', array($vin));
			$car = Car::create($vin);
			$order = OrderAR::model()->findByPk($car->car->order_id);
			$data = array();

			if(!empty($order)) {
				///释放占位订单
				$order->hold -= 1;
				//如果已出库，备车数量亦需减1
				if(strstr($car->car->status, '公司外') !== false) {
					$order->count -= 1;
				}
				$car->car->order_id = 0;
				$car->car->save();
				$order->save();
				$message = $vin . '已释放订单' . $order->order_number . '占位';

			} else {
				throw new Exception($vin . '释放订单失败，订单不存在或未匹配订单');
			}

			//退库
			if($releaseOnly !== 1) {
				$car->enterNode('CHECK_IN');
				$warehouse = new Warehouse;
				$data = $warehouse->checkin($vin);
				$message = $vin . '已成功退库，请开往' . $data['row'];

				$oldRow = WarehouseAR::model()->findByPk($car->car->warehouse_id);
				if(!empty($oldRow)){
					$oldRow->quantity -= 1;
					$oldRow->save();
			}
			}

			$transaction->commit();
			$this->renderJsonBms(true, $message, $data);
		} catch(Exception $e) {
			$transaction->rollback();
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
}
