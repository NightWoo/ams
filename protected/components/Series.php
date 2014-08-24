<?php
Yii::import('application.models.AR.SeriesAR');
class Series
{
	public static function getName ($series) {
        $seriesName = SeriesAR::model()->find('series=?', array($series))->name;
        return $seriesName;
	}

	public static function getNameList () {
		$nameList = array();
		$seriesArray = SeriesAR::model()->findAll();
    	foreach($seriesArray as $series){
    		$nameList[$series['series']] = $series['name'];
    	}
		return  $nameList;
	}

    public static function getCodeList () {
        $codeList = array();
        $seriesArray = SeriesAR::model()->findAll();
        foreach($seriesArray as $series){
            $codeList[$series['name']] = $series['series'];
        }
        return $codeList;
    }

	public static function getArray () {
		$sql = "SELECT series FROM series";
		$arr = Yii::app()->db->createCommand($sql)->queryColumn();
		return $arr;
	}

	public static function parseSeries ($series="") {
		if(empty($series) || $series === 'all') {
			$sql = "SELECT series FROM series";
			$series = Yii::app()->db->createCommand($sql)->queryColumn();
        } else {
            $series = explode(',', $series);
        }
		return $series;
	}

	public static function parseSeriesName ($series="") {
		$nameList = self::getNameList();
		if(empty($series) || $series === 'all') {
            $seriesArray = $nameList;
        } else {
            $series = explode(',', $series);
            foreach($series as $one){
            	$seriesArray[$one] = $nameList[$one];
            }
        }
		return $seriesArray;
	}
}
