<?php
Yii::import('application.models.Car');
Yii::import('application.models.ComponentSeeker');

class Component
{
	public function __construct(){
	}

	public static function createSeeker() {
		return new ComponentSeeker();
	}
	
}
