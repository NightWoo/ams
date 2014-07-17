<?php
Yii::import('application.models.AR.CityAR');
Yii::import('application.models.AR.ProvinceAR');
class HrStaffSeeker
{
  public function __construct () {}

  function provinceCityList () {
    $provinceSql = "SELECT * FROM province";
    $provinces = Yii::app()->db->createCommand($provinceSql)->queryAll();
    $citySql = "SELECT * FROM city";
    $cities = Yii::app()->db->createCommand($citySql)->queryAll();

    $data = array();
    foreach ($provinces as $province) {
      $data[$province['id']] = $province;
      $data[$province['id']]['cities'] = array();
    }

    foreach ($cities as $city) {
      array_push($data[$city['province_id']]['cities'], $city);
    }

    return $data;
  }
}