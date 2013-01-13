<?php
Yii::import('application.models.AR.PoviderAR');

class ProviderSeeker
{
	public function __construct(){
	}
	
	public function getNameList($token) {
		$sql = "SELECT display_name FROM provider WHERE display_name LIKE '%$token%'";
		return Yii::app()->db->createCommand($sql)->queryColumn();
	}

	public function getProviderCode($providerName) {
		$sql = "SELECT id AS provider_id, code AS provider_code, display_name AS provider_name, name FROM provider WHERE display_name LIKE '%$providerName%'";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		return $datas;
	}

	public function queryProvider($code, $name) {
		$sql = "SELECT id, code, name, display_name FROM provider";

        $conditionsName = array();      
        if(!empty($name)){
            $conditions = array();
            $conditions[] = $conditionsName[][] = "name LIKE '%$name%'";
            $conditions[] = $conditionsName[][] = "display_name LIKE '%$name%'";
        }
        if(!empty($code)) {
            $conditions = array();
            if(!empty($conditionsName)){
                foreach($conditionsName as $condition){
                    $condition[] = "code LIKE '%$code%'";
                    $conditions[] = join(' AND ', $condition);
                }                        
            } else {
                $conditions[] = "code LIKE '%$code%'";
            }           
        }

        $sqls = array();
        if(!empty($conditions)){
            foreach($conditions as $condition){
                $sqls[] = $sql . ' WHERE ' . $condition;
            }
        } else {
            $sqls[] = $sql;
        }

        $sql = join(' UNION ', $sqls)  . ' ORDER BY id ASC';
        $data = Yii::app()->db->createCommand($sql)->queryAll();

        return $data;
	}
}
