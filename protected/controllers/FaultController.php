<?php
Yii::import('application.models.Car');
Yii::import('application.models.Fault');
Yii::import('application.models.AR.*');
class FaultController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionShowAll() {
		try{
            $fault = Fault::createSeeker();
            $data = $fault->getAllByCategory('all');

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}

	public function actionShow() {
		$category = $this->validateStringVal('category', '');
		$mode = $this->validateStringVal('mode', '');
		try{
			$fault = Fault::createSeeker();
			$data = $fault->getAllByCategory($category,$mode);
			
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false , $e->getMessage());
		}
	}

	public function actionShowLeak() {
        $category = $this->validateStringVal('category', '');
        try{
            $fault = Fault::createSeeker();
            $data = $fault->getAllByCategory($category,'leak');

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }


	public function actionSearch() {
		$name = $this->validateStringVal('component', '');
		try{
			if(empty($name)) {
				throw new Exception('component cannot be null');
			}	
            $fault = Fault::createSeekerByComponent($name);
            $data = $fault->getAll();

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

	}

	public function actionSearchGasBag() {
		$mainGasBag = 692;
		$vin = $this->validateStringVal('vin', '');
        try{
            $fault = Fault::create('VQ2_ROAD_TEST',$vin, '[]');
            $data = $fault->showGasBag($mainGasBag);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

	}

	public function actionView() {
        $name = $this->validateStringVal('component', '');
        try{
            if(empty($name)) {
                throw new Exception('component cannot be null');
            }
            $fault = Fault::createSeekerByComponent($name);
            $data = $fault->getComponent();

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

    }

	public function actionShowVQ1() {
        $vin = $this->validateStringVal('vin', '');
		try{
            $fault = Fault::create('VQ1_STATIC_TEST',$vin, '[]');
            $data = $fault->show();
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}

	
	public function actionSaveVQ1() {
		$vin = $this->validateStringVal('vin', '');
		$faults = $this->validateStringVal('fault', '[]'); 
        try{
			$fault = Fault::create('VQ1_STATIC_TEST',$vin, $faults);	
			$fault->save('离线');
            $this->renderJsonBms(true, 'OK');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

	}

	public function actionShowVQ2Road() {
		$vin = $this->validateStringVal('vin', '');
        try{
            $fault = Fault::create('VQ2_ROAD_TEST',$vin, '[]');
            $data = $fault->show();
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

    }


	public function actionSaveVQ2Road() {
        $vin = $this->validateStringVal('vin', '');
        $faults = $this->validateStringVal('fault', '[]');
        try{
            $fault = Fault::create('VQ2_ROAD_TEST',$vin, $faults);
            $fault->save('离线');
            $this->renderJsonBms(true, 'OK');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

    }

	public function actionShowVQ2Leak() {
        $vin = $this->validateStringVal('vin', '');
        try{
            $fault = Fault::create('VQ2_LEAK_TEST',$vin, '[]');
            $data = $fault->show();
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

    }



	public function actionSaveVQ2Leak() {
        $vin = $this->validateStringVal('vin', '');
        $faults = $this->validateStringVal('fault', '[]');
        try{
            $fault = Fault::create('VQ2_LEAK_TEST',$vin, $faults);
            $fault->save('离线');
            $this->renderJsonBms(true, 'OK');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

    }

	public function actionShowVQ3() {
        $vin = $this->validateStringVal('vin', '');
        try{
            $fault = Fault::create('VQ3_FACADE_TEST',$vin, '[]');
            $data = $fault->show();
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

    }


	
	public function actionSaveVQ3() {
        $vin = $this->validateStringVal('vin', '');
        $faults = $this->validateStringVal('fault', '[]');
        try{
            $fault = Fault::create('VQ3_FACADE_TEST',$vin, $faults);
            $fault->save('离线');
            $this->renderJsonBms(true, 'OK');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

    }



	public function actionQuery() {
		$component = $this->validateStringVal('component', '');
		$mode = $this->validateStringVal('mode', '');
		$series = $this->validateStringVal('series', '');
		$stime = $this->validateStringVal('stime', '');
		$etime = $this->validateStringVal('etime', '');
		$node = $this->validateStringVal('node', '');
		$perPage = $this->validateIntVal('perPage', 20);
		$curPage = $this->validateIntVal('curPage', 1);
		try{
            $fault = Fault::createSeeker();
            list($total, $data) = $fault->query($component, $mode, $series, $stime, $etime, $node,$curPage, $perPage);
			$ret = array(
				'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
				'data' => $data,
			);
            $this->renderJsonBms(true, 'OK', $ret);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

	}

	public function actionQueryDistribute() {
        $component = $this->validateStringVal('component', '');
        $mode = $this->validateStringVal('mode', '');
        $series = $this->validateStringVal('series', '');
        $stime = $this->validateStringVal('stime', '');
        $etime = $this->validateStringVal('etime', '');
        $node = $this->validateStringVal('node', '');
        try{
            $fault = Fault::createSeeker();
            $data = $fault->queryDistribute($component, $mode, $series, $stime, $etime, $node);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

    }

	public function actionQueryDPU() {
        $component = $this->validateStringVal('component', '');
        $mode = $this->validateStringVal('mode', '');
        $series = $this->validateStringVal('series', '');
        $stime = $this->validateStringVal('stime', '');
        $etime = $this->validateStringVal('etime', '');
        $node = $this->validateStringVal('node', '');
        try{
            $fault = Fault::createSeeker();
            $data = $fault->queryDPU($component, $mode, $series, $stime, $etime, $node);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

    }

	public function actionQueryPlaton() {
        $series = $this->validateStringVal('series', '');
        $stime = $this->validateStringVal('stime', '');
        $etime = $this->validateStringVal('etime', '');
        $node = $this->validateStringVal('node', '');
        try{
            $fault = Fault::createSeeker();
            $data = $fault->queryPlaton($series, $stime, $etime, $node);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }



	public function actionQueryQualified() {
        try{
            $series = $this->validateStringVal('series', '');
            $stime = $this->validateStringVal('stime', '');
            $etime = $this->validateStringVal('etime', '');
            $node = $this->validateStringVal('node', '');

			$fault = Fault::createSeeker();
            $data = $fault->queryQualified($series, $stime, $etime, $node);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }

    }

	public function actionQueryCars() {
        try{
            $series = $this->validateStringVal('series', '');
            $stime = $this->validateStringVal('stime', '');
            $etime = $this->validateStringVal('etime', '');
            $node = $this->validateStringVal('node', '');

            if(empty($node)){
                throw new Exception("车辆统计必须选择节点", 1);
            }

            $fault = Fault::createSeeker();
            $data = $fault->queryCars($series, $stime, $etime, $node);
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }

    }





	public function actionExport() {
		$component = $this->validateStringVal('component', '');
        $mode = $this->validateStringVal('mode', '');
        $series = $this->validateStringVal('series', '');
        $stime = $this->validateStringVal('stime', '');
        $etime = $this->validateStringVal('etime', '');
        $node = $this->validateStringVal('node', '');
        try{
            $fault = Fault::createSeeker();
            list($total, $datas) = $fault->query($component, $mode, $series, $stime, $etime, $node,0, 0);
			$content = "车系,VIN号,故障零部件,故障模式,故障状态,节点,录入人员,录入时间,确认时间\n";
			foreach($datas as $data) {
				$content .= "{$data['series']},";
				$content .= "{$data['vin']},";
				$content .= "{$data['component_name']},";
				$content .= "{$data['fault_mode']},";
				$content .= "{$data['fault_status']},";
				$content .= "{$data['node_name']},";
				$content .= "{$data['user_name']},";
				$content .= "{$data['create_time']},";
				$content .= "{$data['modify_time']}\n";
			}
			$export = new Export('故障_' .date('YmdHi'), $content);
			$export->toCSV();
        } catch(Exception $e) {
			echo $e->getMessage();
        }
	}



	//fault base
	public function actionQueryBase() {
		$faultKind = $this->validateStringVal('fault_kind', '');
        $series = $this->validateStringVal('series', '');
		$component = $this->validateStringVal('component', '');
        $mode = $this->validateStringVal('mode', '');
		$status = $this->validateStringVal('status', '');
		$level = empty($_REQUEST['level']) ? array() : $_REQUEST['level'];
		$perPage = $this->validateIntVal('perPage', 20);
        $curPage = $this->validateIntVal('curPage', 1);
		
		try{
            $fault = Fault::createBaseSeeker();
            list($total, $data) = $fault->query($faultKind, $series, $component, $mode, $status, $level, $curPage, $perPage);
			
			$ret = array(
                'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                'data' => $data,
            );
            $this->renderJsonBms(true, 'OK', $ret);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }


		
	}

	public function actionGenerateFaultCode() {
		$series = $this->validateStringVal('series', '');
        $component = $this->validateStringVal('component', '');
		try{
            $fault = Fault::createBaseSeeker();
            $data = $fault->generateFaultCode($series, $component);

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }

	}


	public function actionSaveFaultStandard() {
		try{
			$opUserId = Yii::app()->user->id;
            $user = User::model()->findByPk($opUserId);
            if(!$user->admin) {
                BmsLogger::warning($opUserId . " try to save component");
                throw new Exception ('不要做坏事，有记录的！！');
            }

			$id = $this->validateIntVal('id', 0);
			$series = $this->validateStringVal('series', '');
			$code = $this->validateStringVal('code', '');
			$mode = $this->validateStringVal('mode', '');
			$componentName = $this->validateStringVal('component_name', '');
			$level = $this->validateStringVal('level', '');
			$kind = $this->validateIntVal('fault_kind', 0);
			$status = $this->validateIntVal('status', 0);
			$description = $this->validateStringVal('description', '');
			if(empty($code) || empty($mode) || empty($componentName)) {
				throw new Exception('故障代码/零部件名称/故障模式不能为空');
			}
			$exist = FaultStandardAR::model()->find('fault_code=? && id!=?', array($code, $id));
			if(!empty($code) && !empty($exist)) {
				throw new Exception('故障代码已存在');
			}
			$component = ComponentAR::model()->find('display_name=?', array($componentName));
			if(empty($component)) {
				throw new Exception('请选择一个零部件后再保存');
			}

			$standard = FaultStandardAR::model()->findByPk($id);
            if(empty($standard)) {
				$standard = new FaultStandardAR();
				$standard->car_series = $series;
				$standard->component_id = $component->id;
	            $standard->component_name = $component->display_name;
            }
			$standard->kind_id = $kind;
			$standard->fault_code = $code;
			$standard->level = $level;
			$standard->mode = $mode;
			$standard->isenabled = $status;
			$standard->description = $description;
			$standard->save();
			
				
            $this->renderJsonBms(true, 'OK', $standard->id);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}

	public function actionRemoveFaultStandard() {
		try{
			$opUserId = Yii::app()->user->id;
            $user = User::model()->findByPk($opUserId);
			$id = $this->validateIntVal('id', 0);
            if(!$user->admin) {
                BmsLogger::warning($opUserId . " try to remove fault_standard @ " .$id);
                throw new Exception ('不要做坏事，有记录的！！');
            }
			BmsLogger::info("remove fault_standard @ " .$id);
			$standard = FaultStandardAR::model()->findByPk($id);
			if(!empty($standard)) {
				$standard->delete();
			}
            $this->renderJsonBms(true, 'OK', '');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

	}
}
