<?php
Yii::import('application.models.AR.SeriesAR');
class SeriesName extends CApplicationComponent
{
	public static function getName ($series) {
        $seriesArray = self::getSeries();
        return $seriesArray[$series];
	}

	public static function getSeries () {
		$seriesName = array();
		$seriesArray = SeriesAR::model()->findAll();
    	foreach($seriesArray as $series){
    		$seriesName[$series['series']] = $series['name'];
    	}
		return  $seriesName;
	}
}
