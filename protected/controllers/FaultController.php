<?php
Yii::import('application.models.Car');
Yii::import('application.models.Fault');
Yii::import('application.models.AR.*');
Yii::import('application.models.FileUpload.*');

class FaultController extends BmsBaseController
{
	const IMAGE_PATH = "/home/work/bms/web/bms/faultImage/";
	const IMAGE_HTTP = "/bms/faultImage/";
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
		$series = $this->validateStringVal('series', 'F0');
		try{
			$fault = Fault::createSeeker();
			$data = $fault->getAllByCategory($category,$mode, $series);
			
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false , $e->getMessage());
		}
	}

	public function actionShowLeak() {
        $category = $this->validateStringVal('category', '');
		$series = $this->validateStringVal('series', 'F0');
        try{
            $fault = Fault::createSeeker();
            $data = $fault->getAllByCategory($category,'leak', $series);

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }


	public function actionSearch() {
		$name = $this->validateStringVal('component', '');
		$series = $this->validateStringVal('series', 'F0');
		try{
			if(empty($name)) {
				throw new Exception('component cannot be null');
			}	
            $fault = Fault::createSeekerByComponent($name, $series);
            $data = $fault->getAll();

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

	}

	public function actionSearchGasBag() {
		//$mainGasBag = 692;
		$vin = $this->validateStringVal('vin', '');
        try{
            $fault = Fault::create('VQ2_ROAD_TEST',$vin, '[]');
            // $data = $fault->showGasBag($mainGasBag);
            $data = $fault->showGasBag();
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

	}

	public function actionView() {
        $name = $this->validateStringVal('component', '');
		$series = $this->validateStringVal('series', '');
        try{
            if(empty($name)) {
                throw new Exception('component cannot be null');
            }
            $fault = Fault::createSeekerByComponent($name, $series);
            $data = $fault->getComponent();

            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }

    }

	public function actionShowVQ1() {
        $vin = $this->validateStringVal('vin', '');
		try{
            $car = Car::create($vin);
            $tablePrefix = "VQ1_STATIC_TEST";
            if($car->car->assembly_line == "II") $tablePrefix = "VQ1_STATIC_TEST_2";
            $fault = Fault::create($tablePrefix, $vin, '[]');
            $data = $fault->show();
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}

	
	public function actionSaveVQ1() {
        $vin = $this->validateStringVal('vin', '');
        $faults = $this->validateStringVal('fault', '[]'); 
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $car = Car::create($vin);
            if($car->car->series == "6B"  && $car->car->type != "QCJ7152ET1(1.5TI豪华型)" && $car->car->type != "QCJ7152ET2(1.5TID豪华型)"){
                $IRemote = $car->getIRemoteTestResult();
                if(!($IRemote->Result) || $IRemote->TestState != "2"){
                    throw new Exception($car->car->vin . '未通过云系统测试，不可录入下线合格，请先完成云系统测试');
                }
            }

            $checkTrace = $car->checkTraceComponentByConfig();
            if($checkTrace['notGood']) throw new Exception("此车追溯零部件记录不完整，不可录入下线合格，请联系相关责任人补录数据");
            
            $vinValidate = $car->validateVin();
            if(!$vinValidate['success']) throw new Exception("此车" . $vinValidate['message']);

            $tablePrefix = "VQ1_STATIC_TEST";
            $nodeName = "VQ1";
            if($car->car->assembly_line == "II"){
                $tablePrefix = "VQ1_STATIC_TEST_2";
                $nodeName = "VQ1_2";
            }  
			$fault = Fault::create($tablePrefix, $vin, $faults);	
			$fault->save('离线');

            $node = Node::createByName($nodeName);
            $car->detectStatus($node);

            $this->renderJsonBms(true, 'OK');
            $transaction->commit();
        } catch(Exception $e) {
            $transaction->rollback();
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
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $fault = Fault::create('VQ2_ROAD_TEST',$vin, $faults);
            $fault->save('离线');

            $car = Car::create($vin);
            $node = Node::createByName("ROAD_TEST_FINISH");
            $car->detectStatus($node);

            $transaction->commit();
            $this->renderJsonBms(true, 'OK');
        } catch(Exception $e) {
            $transaction->rollback();
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
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $fault = Fault::create('VQ2_LEAK_TEST',$vin, $faults);
            $fault->save('离线');

            $car = Car::create($vin);
            $node = Node::createByName("VQ2");
            $car->detectStatus($node);

            $transaction->commit();
            $this->renderJsonBms(true, 'OK');
        } catch(Exception $e) {
            $transaction->rollback();
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
        $driverId = $this->validateStringVal('driver', 0);
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $others = array(
                'checker' => $driverId,
            );

            $fault = Fault::create('VQ3_FACADE_TEST',$vin, $faults, $others);
            $fault->save('离线');
			
			$car = Car::create($vin);
            $node = Node::createByName("VQ3");
            $car->detectStatus($node);

            $transaction->commit();
            $this->renderJsonBms(true, 'OK');
			$vinMessage = $car->throwVinAssembly($car->vin, '面漆修正');
        } catch(Exception $e) {
            $transaction->rollback();
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

    public function actionQueryDutyDistribution() {
        $component = $this->validateStringVal('component', '');
        $mode = $this->validateStringVal('mode', '');
        $series = $this->validateStringVal('series', '');
        $stime = $this->validateStringVal('stime', '');
        $etime = $this->validateStringVal('etime', '');
        $node = $this->validateStringVal('node', '');
        try{
            $fault = Fault::createSeeker();
            $data = $fault->queryDutyDistribution($component, $mode, $series, $stime, $etime, $node);
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
        $component = $this->validateStringVal('component', '');
        $mode = $this->validateStringVal('mode', '');
        $node = $this->validateStringVal('node', '');
        try{
            $fault = Fault::createSeeker();
            $data = $fault->queryPlaton($series, $stime, $etime, $node, $component, $mode);
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
            $returnNode = 0;
            if($node === 'VQ3_WAREHOUSE_RETURN'){
                $returnNode = 17;
            }
            $fault = Fault::createSeeker();
            $data = $fault->queryCars($series, $stime, $etime, $node, $returnNode);
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
			$content = "车系,VIN号,故障零部件,故障模式,故障状态,责任部门,节点,驾驶员,录入人员,录入时间,确认时间,初检人员,复检人员\n";
			foreach($datas as $data) {
				$content .= "{$data['series']},";
				$content .= "{$data['vin']},";
				$content .= "{$data['component_name']},";
				$content .= "{$data['fault_mode']},";
                $content .= "{$data['fault_status']},";
				$content .= "{$data['duty_department']},";
				$content .= "{$data['node_name']},";
                $content .= "{$data['driver_name']},";
				$content .= "{$data['user_name']},";
				$content .= "{$data['create_time']},";
				$content .= "{$data['modify_time']},";
                $content .= "{$data['checker']},";
                $content .= "{$data['sub_checker']}\n";
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
			$component = ComponentAR::model()->find('is_fault=1 AND display_name=? AND car_series=?', array($componentName,$series));
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

	public function actionUploadImage() {
		try{
            $opUserId = Yii::app()->user->id;
            $user = User::model()->findByPk($opUserId);
            $id = $this->validateIntVal('id', 0);
            if(!$user->admin) {
                BmsLogger::warning($opUserId . " try to upload fault_standard @ " .$id);
                throw new Exception ('不要做坏事，有记录的！！');
            }
            BmsLogger::info("update image fault_standard @ " .$id);
            $standard = FaultStandardAR::model()->findByPk($id);
			$data = array();
            if(!empty($standard)) {
				$newPaths = FileUpload::uploadImages('image', self::IMAGE_PATH, $standard->id, true);
				foreach($newPaths as $path) {
					$imageAR = new FaultStandardImageAR();
					$imageAR->fault_id = $standard->id;
					$imageAR->path = $path;
					$imageAR->user_id = $opUserId;
					$imageAR->save();
					$data[] = array('id' => $imageAR->id, 'image' => self::IMAGE_HTTP . $path);
				}
            }
			
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}

	public function actionShowImage() {
        try{
            $id = $this->validateIntVal('id', 0);
            $standard = FaultStandardAR::model()->findByPk($id);
			$data = array();
            if(!empty($standard)) {
				$images = FaultStandardImageAR::model()->findAll('fault_id=?',array($standard->id));
				foreach($images as $image) {
					$data[] = array(
						'id' => $image->id,
						'image' => self::IMAGE_HTTP . $image->path,
					);
				}
            }
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
	}

	public function actionDeleteImage() {
        try{
            $id = $this->validateIntVal('id', 0);
			$opUserId = Yii::app()->user->id;
            $user = User::model()->findByPk($opUserId);
            if(!$user->admin) {
                BmsLogger::warning($opUserId . " try to remove fault_standard_image @ " .$id);
                throw new Exception ('不要做坏事，有记录的！！');
            }
            BmsLogger::info("delete image fault_standard_image @ " .$id);

			$image = FaultStandardImageAR::model()->findByPk($id);
			if(!empty($image)) {
				@unlink(self::IMAGE_PATH . $image->path);
				$image->delete();
			}
            $this->renderJsonBms(true, 'OK', '');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }

    public function actionSaveDutyDepartment() {
        try{
            $id = $this->validateIntVal('id', 0);
            $faultClass = $this->validateStringVal('faultClass', 0);
            $dutyDepartment = $this->validateIntVal('duty', 0);
            $fault = $faultClass::model()->findByPk($id);
            $data = $fault->duty_department = $dutyDepartment;
            $fault->updator = Yii::app()->user->id;
            $fault->save();
            $this->renderJsonBms(true, 'OK', $data);
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }
}
