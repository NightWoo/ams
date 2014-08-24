<?php
Yii::import('application.models.Component');
class FaultComponentSeeker
{
	private $name;
	private $series;
	public function __construct($name, $series){
		$this->name = $name;
		$this->series = $series;
	}

	public function __get($attr) {
		return $this->{$attr};
	}

	public function getComponent() {
		$sql = "SELECT id as component_id,
                       display_name as component_name  
                  FROM component 
                 WHERE display_name='{$this->name}'
				   AND is_fault=1";
		if(!empty($this->series)) {
			$sql .= " AND car_series='$this->series'";
		}
		$component = Yii::app()->db->createCommand($sql)->queryRow();
		
		//falut mode
		if(!empty($component)) {
			$sql = "SELECT id,level,mode FROM fault_standard WHERE component_id={$component['component_id']} AND isenabled=1";
			$component['fault_mode'] = Yii::app()->db->createCommand($sql)->queryAll();
		}
		return $component;
	}

	public function getAll() {
		 $upperName = strtoupper($this->name);
		 $sql = "SELECT display_name  
                  FROM component 
                 WHERE upper(display_name) LIKE '%$upperName%'
                   AND is_fault=1";
		if(!empty($this->series)) {
            $sql .= " AND car_series='$this->series'";
        }
        $components = Yii::app()->db->createCommand($sql)->queryColumn();
		
		return $components;
	}	

}
