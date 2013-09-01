<?php
Yii::import('application.models.Component');
Yii::import('application.models.AR.ComponentAR');
Yii::import('application.models.AR.NodeTraceAR');

class SparesSeeker 
{
	public function __construct(){
	}

	public function querySparesTrace ($traceId) {
		if(empty($traceId)) {
			throw new Exception ('node_trace_id can not be null');
		}

		$sql = "SELECT node_trace_id,vin, component_id, component_name,component_code, provider_name, bar_code, is_collateral
				FROM view_spare_replacement WHERE node_trace_id = $traceId";
		$data = Yii::app()->db->createCommand($sql)->queryAll();

		return $data;
	}

	public function queryReplacementDetail ($stime, $etime, $series, $line, $dutyId, $curPage=0, $perPage=0) {
		if(empty($stime) || empty($etime)) {
			throw new Exception("查询起止均时间不可为空");
		}

		$conditions = array("replace_time>='$stime'","replace_time<'$etime'");
		if(!empty($series)) {
			$arraySeries = $this->parseSeries($series);
			foreach($arraySeries as $series){
	        	$cTmp[] = "series='$series'";
	        }
	        $conditions[] = "(" . join(' OR ', $cTmp) . ")";
		}
		if(!empty($line)) {
			$conditions[] = "assembly_line='$line'";
		}
		if(!empty($dutyId)) {
			$conditions[] = "duty_department_id=$dutyId";
		}
		$condition = join(" AND ", $conditions);

		$limit = "";
        if(!empty($perPage)) {
            $offset = ($curPage - 1) * $perPage;
            $limit = "LIMIT $offset, $perPage";
        }

        $dataSql = "SELECT assembly_line, series, vin, component_code, component_name, provider_name,provider_code,factory_code, bar_code, is_collateral, unit_price, duty_department_name, fault_component_name, fault_mode, replace_time
        			FROM view_spare_replacement
        			WHERE $condition
        			ORDER BY replace_time ASC
        			$limit";
        $datas = Yii::app()->db->createCommand($dataSql)->queryAll();

        $countSql = "SELECT COUNT(*) FROM view_spare_replacement WHERE $condition";
        $total = Yii::app()->db->createCommand($countSql)->queryScalar();

        return array($total, $datas);
	}

	private function parseSeries ($series) {
		if(empty($series) || $series === 'all') {
            $series = array('F0', 'M6', '6B');
        } else {
            $series = explode(',', $series);
        }
		return $series;
	}
}