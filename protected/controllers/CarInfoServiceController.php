<?php
Yii::import('application.models.AR.CarAR');
class CarInfoServiceController extends CController
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
     * @param string the vin
     * @return array the car info
     * @soap
     */
    public function getCarInfo($vin)
    {   
        $car = CarAR::model()->find("vin=?", array($vin));
        if(!empty($car)){
            $carInfo = array(
                "vin" => $car->vin,
                "type" => $car->type,
            );
        } else {
            $carInfo = array("message"=>"vin not exists");
        }
        return $carInfo;
    }
}