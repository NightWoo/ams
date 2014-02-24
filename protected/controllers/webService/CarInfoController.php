<?php
Yii::import('application.models.Car');
Yii::import('application.models.AR.CarSeriesAR');
Yii::import('application.models.AR.CarTypeMapAR');
Yii::import('application.models.AR.CarColorMapAR');
class CarInfoController extends CController
{
    public function actions()
    {
        return array(
            'quote'=>array(
                'class'=>'CWebServiceAction',
            ),
        );
    }

     /**
     * @param string vin
     * @return string result
     * @soap
     */
    public function getCarInfo($vin)
    {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $car = Car::create($vin);
            $result = "0";
            if(!empty($car->car)){
                $carInfo = array();
                $carInfo['matched'] = 1;
                $carInfo['id'] = $car->car->id;
                $car->enterNode('PBS', 2);
                $car->detectStatus('PBS');
                if(!empty($car->car->plan_id)) {
                    $carInfo['matched'] = 2;
                } else {
                    $curDate = DateUtil::workDate(date("Y-m-d H:i:s"));
                    $data = $car->matchPlan($curDate, "I");
                    if($data['adapt_plan']) {
                        $car->addToPlan ($curDate, $data['plan_id']);
                        $carInfo['matched'] = 2;
                    }
                }

                $carSeries = CarSeriesAR::model()->find('series=?', array($car->car->series));
                $carInfo['series_id'] = empty($carSeries) ? 0 : $carSeries->id;

                $carType = CarTypeMapAR::model()->find('series=? AND car_type=?', array($car->car->series, $car->car->type));
                $carInfo['type_id'] = empty($carType) ? 0 : $carType->id;

                $carColor = CarColorMapAR::model()->find('series=? AND color=?', array($car->car->series, $car->car->color));
                $carInfo['color_id'] = empty($carColor) ? 0 : $carColor->id;

                $carInfo['special'] = empty($car->car->special_order) ? "x" : $car->car->special_order;

                $result = join('~', $carInfo);
            } else {
                $result = "0";
            }
            $transaction->commit();
            return $result;
        } catch(Excption $e) {
            $result = "vin not exist";
            $transaction->rollback();
            return $result;
        }
    }
}