<?php
class FaultBaseSeeker
{
	private static $kind_map = array(
		'assembly' => '1,2,3,4,5',
		'paint'    => '6,7,8,9',
		'welding'  => '10,11,12,13',
		'press'    => '14,15',
	);
	
	public function __construct() {
	}


	public function query($faultKind, $series, $component, $mode, $status, $levels, $curPage, $perPage) {
		$conditions = array();
		if(empty(self::$kind_map[$faultKind])) {
			$kinds = $faultKind;
		} else {
			$kinds = self::$kind_map[$faultKind];
		}
		$conditions[] = "kind_id IN ($kinds)";
        if(!empty($series)) {
            $conditions[] = "car_series='$series'";
        }
        if(!empty($component)) {
            $conditions[] = "component_name LIKE '%$component%'";
        }
        if(!empty($mode)) {
            $conditions[] = "mode LIKE '%$mode%'";
        }
		if($status === 'enable') {
			$conditions[] = "isenabled=1";
		} elseif($status === 'disable') {
			$conditions[] = "isenabled=0";
		}
		if(!empty($levels)) {
			//$levels = explode(',', $levels);
			$levelText = "'" . join("','", $levels) . "'";
			$conditions[] = "level IN ($levelText)";
		}
   
        $condition = join(' AND ', $conditions);

        $limit = $perPage;
        $offset = ($curPage - 1) * $perPage;


		$sql = "SELECT id, car_series, fault_code, component_name, mode, isenabled as status, level, kind_id,description FROM fault_standard WHERE $condition LIMIT $offset, $limit";

		$countSql = "SELECT count(*) FROM fault_standard WHERE $condition";

		$datas = Yii::app()->db->createCommand($sql)->queryAll(); 
		$total = Yii::app()->db->createCommand($countSql)->queryScalar();

		$sql = "SELECT id, name FROM fault_kind";
		$faultKinds = Yii::app()->db->createCommand($sql)->queryAll();
		$kindNameList = array();
		foreach($faultKinds as $faultKind) {
			$kindNameList[$faultKind['id']] = $faultKind['name'];
		} 

		foreach($datas as &$data) {
			$data['kind'] = $kindNameList[$data['kind_id']];
		}
	
		return array($total, $datas);
	}

	private function parseFaultKind($kind) {
	}

	public function generateFaultCode($series, $componentName) {
		$component = ComponentAR::model()->find('car_series = ? AND display_name=? AND is_fault=1', array($series,$componentName));	

		if(empty($component)) {
			throw new Exception("error @ $series, $componentName");
		}

		$code = trim($component->code);

		$ret = strtoupper($series);

		$codes = explode('-', $code);
		if(count($codes) >= 2) {
			$ret .= substr($codes[1], 0, 7);
		}

		
		$sql = "SELECT fault_code FROM fault_standard WHERE component_id = {$component->id} ORDER by fault_code DESC";

		$lastCode = Yii::app()->db->createCommand($sql)->queryScalar();
	
		$lastKey = intval(substr($lastCode, strlen($lastCode) - 3, 3));	
		
		$ret .= sprintf("%03d", (($lastKey + 1) % 1000));

		return $ret;
	}

}
