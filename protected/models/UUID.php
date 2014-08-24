<?php
Yii::import('application.models.AR.UuidAR');
class UUID
{
	public static function generate() {
		$ar = new UuidAR();
		$ar->save();

		return $ar->id;
	}	
}
