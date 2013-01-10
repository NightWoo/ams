<?php
class BmsLogger
{
	public static function debug($message, $category = 'bms') {
		self::log($message, 'trace' ,$category);
	}

	public static function fatal($message, $category = 'bms') {
		self::log($message, 'fatal' ,$category);
    }


	public static function warning($message, $category = 'bms') {
		self::log($message, 'warning' ,$category);
    }


	public static function info($message, $category = 'bms') {
		self::log($message, 'info' ,$category);
    }

	public static function log($message, $level ,$category = 'bms') {
		Yii::log("[".Yii::app()->user->name."]" . $message, $level ,$category);
	}


}
