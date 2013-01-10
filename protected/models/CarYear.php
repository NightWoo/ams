<?php
class CarYear
{
	private static $carYearMap = array(
		'1' => '2001',
		'2' => '2002',
		'3' => '2003',
		'4' => '2004',
		'5' => '2005',
		'6' => '2006',
		'7' => '2007',
		'8' => '2008',
		'9' => '2009',
	    'a' => '2010',
	    'b' => '2011',
		'c' => '2012',
		'd' => '2013',
		'e' => '2014',
		'f' => '2015',
		'g' => '2016',
		'h' => '2017',
		'i' => '2018',
		'j' => '2019',
		'k' => '2020',
		'l'	=> '2021',
		'm' => '2022',
		'n'	=> '2023',
		'o' => '2024',
		'p' => '2025',
	);
	public static function getCarYear($vin) {
		$key = strtolower($vin[9]);
		return self::$carYearMap[$key];
	}
	//added by wujun
	public static function getYearCode($year){
		$yearCode = array_search($year,self::$carYearMap);
		return strtoupper($yearCode);	
	}
}
