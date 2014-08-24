<?php
class SeriesSeeker
{
    public function __construct() {
    }

    public static function findAllCode() {
		$sql = "SELECT series_code FROM series";
		return Yii::app()->db->createCommand($sql)->queryColumn();
	}
}
