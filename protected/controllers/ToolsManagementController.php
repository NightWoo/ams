<?php

//工具管理
Yii::import('application.models.ToolsHome');
Yii::import('application.models.Export');

class ToolsManagementController extends BmsBaseController {

    //---------------------------------------提示内容-----------------------------------------//
    private function makerData() {
        return ToolsHome::getmakerData();
    }

    private function distributorData() {
        return ToolsHome::getmakerData();
    }

    private function parameterData() {
        return ToolsHome::getmakerData();
    }

    private function toolsUserData() {
        return ToolsHome::getmakerData();
    }

    public function action() {
        $this->render();
    }

    //---------------------------------------各栏目初始页-----------------------------------------//
    //基础数据管理
    public function actionIndex() {
        try {
            Yii::app()->permitManager->check('HOME_MAKER');
            $this->render("homeMaker", array("promptData" => $this->makerData()));
        } catch (Exception $e) {
            if ($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionHomeDistributor() {
        try {
            Yii::app()->permitManager->check('HOME_DISTRIBUTOR');
            $this->render("homeDistributor", array("promptData" => $this->distributorData()));
        } catch (Exception $e) {
            if ($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionHomeParameter() {
        try {
            Yii::app()->permitManager->check('HOME_PARAMETER');
            $this->render("homeParameter", array("promptData" => $this->parameterData()));
        } catch (Exception $e) {
            if ($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    public function actionHomeToolsUser() {
        try {
            Yii::app()->permitManager->check('HOME_TOOLSUSER');
            $this->render("homeToolsUser", array("promptData" => $this->toolsUserData()));
        } catch (Exception $e) {
            if ($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    //工具库管理
    public function actionToolsManagement() {
        try {
            Yii::app()->permitManager->check('TOOLSMANAGEMENT');
            $this->render("toolsManagement", array());
        } catch (Exception $e) {
            if ($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    //工具调拨
    public function actionToolsAssign() {
        try {
            Yii::app()->permitManager->check('TOOLSASSIGN');
            $this->render("toolsAssign", array());
        } catch (Exception $e) {
            if ($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    //工具点检
    public function actionToolsCheck() {
        try {
            Yii::app()->permitManager->check('TOOLSCHECK');
            $this->render("toolsCheck", array());
        } catch (Exception $e) {
            if ($e->getMessage() == 'permission denied')
                $this->render('../site/permissionDenied');
        }
    }

    //---------------------------------------------查询添加更新删除操作------------------------------------------//
    //制造商
    public function actionSearchMaker() {
        $is_using = intval($this->validateStringVal('is_using', -1));
        $type = $this->validateStringVal('type');
        $id = $this->validateIntVal('id');
        $brand = $this->validateStringVal('brand');
        $name = $this->validateStringVal('maker');
        $display_name = $this->validateStringVal('displayName');
        $perPage = $this->validateIntVal('perPage', 20);
        $curPage = $this->validateIntVal('curPage', 1);
        try {
            $seeker = new ToolsHome();
            switch ($type) {
                case 'selects': //查询
                    list($total, $data) = $seeker->queryMaker($name, $is_using, $curPage, $perPage);
                    $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'listData' => $data,
                    );
                    $this->renderJsonBms(true, 'OK', $ret);
                    break;
                case 'adds':    //增加
                    if (empty($name) || empty($brand)) {
                        throw new Exception('抱歉，品牌或制造商为空');
                    }
                    $recordExists = $seeker->queryRowMaker($name, $brand);
                    if ($recordExists > 0) {
                        throw new Exception('抱歉，此记录已经存在');
                    }
                    $seeker->addMaker($name, $display_name, $brand);
                    $this->renderJsonBms(true, 'OK');
                    break;
                case 'updates': //更新
                    if (empty($name) || empty($brand)) {
                        throw new Exception('抱歉，品牌或制造商为空');
                    }
                    $recordExists = $seeker->queryRowMaker($name, $brand, $id);
                    if ($recordExists > 0) {
                        throw new Exception('抱歉，此记录已经存在');
                    }
                    $seeker->updateMaker($id, $name, $display_name, $brand, $is_using);
                    $this->renderJsonBms(true, 'OK');
                    break;
                case 'deletes': //删除
                    $seeker->deldteMaker($id);
                    $this->renderJsonBms(true, 'OK');
                    break;
                default:
                    break;
            }
        } catch (Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    //供应商
    public function actionSearchDistributor() {
        $is_using = intval($this->validateStringVal('is_using', -1));
        $type = $this->validateStringVal('type');
        $id = $this->validateIntVal('id');
        $contact = $this->validateStringVal('contact');
        $name = $this->validateStringVal('distributor');
        $display_name = $this->validateStringVal('displayName');
        $perPage = $this->validateIntVal('perPage', 20);
        $curPage = $this->validateIntVal('curPage', 1);
        try {
            $seeker = new ToolsHome();
            switch ($type) {
                case 'selects': //查询                    
                    list($total, $data) = $seeker->queryDistributor($name, $is_using, $curPage, $perPage);
                    $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'listData' => $data,
                    );
                    $this->renderJsonBms(true, 'OK', $ret);
                    break;
                case 'adds':    //增加
                    if (empty($name) || empty($contact)) {
                        throw new Exception('抱歉，联系方式或供应商为空');
                    }
                    $recordExists = $seeker->queryRowDistributor($name, $contact);
                    if ($recordExists > 0) {
                        throw new Exception('抱歉，此记录已经存在');
                    }
                    $seeker->addDistributor($name, $display_name, $contact);
                    $this->renderJsonBms(true, 'OK');
                    break;
                case 'updates': //更新
                    if (empty($name) || empty($contact)) {
                        throw new Exception('抱歉，联系方式或供应商为空');
                    }
                    $recordExists = $seeker->queryRowDistributor($name, $id);
                    if ($recordExists > 0) {
                        throw new Exception('抱歉，此记录已经存在');
                    }
                    $seeker->updateDistributor($id, $name, $display_name, $contact, $is_using);
                    $this->renderJsonBms(true, 'OK');
                    break;
                case 'deletes': //删除
                    $seeker->deldteDistributor($id);
                    $this->renderJsonBms(true, 'OK');
                    break;
                default:
                    break;
            }
        } catch (Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    //参数单位
    public function actionSearchParameter() {
        $is_using = intval($this->validateStringVal('is_using', -1));
        $type = $this->validateStringVal('type');
        $id = $this->validateIntVal('id');
        $name = $this->validateStringVal('parameter');
        $unit = $this->validateStringVal('unit');
        $perPage = $this->validateIntVal('perPage', 20);
        $curPage = $this->validateIntVal('curPage', 1);
        try {
            $seeker = new ToolsHome();
            switch ($type) {
                case 'selects': //查询
                    list($total, $data) = $seeker->queryParameter($name, $is_using, $curPage, $perPage);
                    $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'listData' => $data,
                    );
                    $this->renderJsonBms(true, 'OK', $ret);
                    break;
                case 'adds':    //增加
                    if (empty($name) || empty($unit)) {
                        throw new Exception('抱歉，参数名或单位为空');
                    }
                    $recordExists = $seeker->queryRowParameter($name, $unit);
                    if ($recordExists > 0) {
                        throw new Exception('抱歉，此记录已经存在');
                    }
                    $seeker->addParameter($name, $unit);
                    $this->renderJsonBms(true, 'OK');
                    break;
                case 'updates': //更新
                    if (empty($name) || empty($unit)) {
                        throw new Exception('抱歉，参数名或单位为空');
                    }
                    $recordExists = $seeker->queryRowParameter($name, $unit, $id);
                    if ($recordExists > 0) {
                        throw new Exception('抱歉，此记录已经存在');
                    }
                    $seeker->updateParameter($id, $name, $unit, $is_using);
                    $this->renderJsonBms(true, 'OK');
                    break;
                case 'deletes': //删除
                    $seeker->deldteParameter($id);
                    $this->renderJsonBms(true, 'OK');
                    break;
                default:
                    break;
            }
        } catch (Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    //领用单位
    public function actionSearchToolsUser() {
        //查询
        $is_using = intval($this->validateStringVal('is_using', -1));
        $is_seat = intval($this->validateStringVal('is_seat', -1));
        $name = $this->validateStringVal('assemblyPoint');

        $type = $this->validateStringVal('type');
        //级联下拉
        $parentId = $this->validateIntVal('parentId');
        //单独添加
        $lineName = $this->validateStringVal('lineName');
        $stageName = $this->validateStringVal('stageName');

        //修改，删除
        $id = $this->validateIntVal('id');
        //添加，修改
        $lineId = $this->validateIntVal('lineId');
        $StageId = $this->validateIntVal('stageId');
        //分页
        $perPage = $this->validateIntVal('perPage', 20);
        $curPage = $this->validateIntVal('curPage', 1);
        try {
            $seeker = new ToolsHome();
            switch ($type) {
                case 'selectMenu': //级联查询
                    $data = $seeker->queryToolsUserMenu($parentId);
                    $this->renderJsonBms(true, 'OK', $data);
                    break;
                case 'selects': //查询
                    list($total, $data) = $seeker->queryToolsUser($name, $StageId, $is_seat, $is_using, $curPage, $perPage);
                    $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'listData' => $data,
                    );
                    $this->renderJsonBms(true, 'OK', $ret);
                    break;
                case 'addLine':    //增加线别
                    if (empty($lineName)) {
                        throw new Exception('抱歉，线别名为空');
                    }
                    $recordExists = $seeker->queryRowToolsUser($lineName);
                    if ($recordExists > 0) {
                        throw new Exception('抱歉，此记录已经存在');
                    }
                    $seeker->addToolsUser($lineName, 0, -1, 0);
                    $this->renderJsonBms(true, 'OK');
                    break;
                case 'addStage':    //增加工段
                    if (empty($stageName) || $lineId == 0) {
                        throw new Exception('抱歉，工段名为空或未选择线别');
                    }
                    $recordExists = $seeker->queryRowToolsUser($stageName);
                    if ($recordExists > 0) {
                        throw new Exception('抱歉，此记录已经存在');
                    }
                    $seeker->addToolsUser($stageName, $lineId, -1, 0);
                    $this->renderJsonBms(true, 'OK');
                    break;
                case 'adds':    //增加工位
                    if (empty($name) || $StageId == 0) {
                        throw new Exception('抱歉，工位名为空或未选择工段');
                    }
                    $recordExists = $seeker->queryRowToolsUser($name);
                    if ($recordExists > 0) {
                        throw new Exception('抱歉，此记录已经存在');
                    }
                    $seeker->addToolsUser($name, $StageId);
                    $this->renderJsonBms(true, 'OK');
                    break;
                case 'updates': //更新
                    if (empty($name)) {
                        throw new Exception('抱歉，必填项为空');
                    }
                    $recordExists = $seeker->queryRowToolsUser($name, $id);
                    if ($recordExists > 0) {
                        throw new Exception('抱歉，此记录已经存在');
                    }
                    $seeker->updateToolsUser($id, $name, $StageId, $is_using);
                    $this->renderJsonBms(true, 'OK');
                    break;
                case 'deletes': //删除
                    $seeker->deldteToolsUser($id);
                    $this->renderJsonBms(true, 'OK');
                    break;
                default:
                    echo $lineName . $type;
                    exit;
                    break;
            }
        } catch (Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    //工具库管理
    public function actionSearchManagement() {

        $status = $this->validateStringVal('status');
        $name = $this->validateStringVal('assemblyPoint');

        $type = $this->validateStringVal('type');

        //新增
        $quantity = $this->validateIntVal('quantity');
        $distributor = $this->validateIntVal('distributor');
        $operater = $this->validateStringVal('operater');
        $cost = $this->validateIntVal('cost');
        $useCycles = $this->validateIntVal('useCycles');
        $spareCycles = $this->validateIntVal('spareCycles');
        $warnCycles = $this->validateIntVal('warnCycles');
        //查询
        $materialCode = $this->validateStringVal('materialCode');
        $toolsName = $this->validateStringVal('toolsName');
        $toolsType = $this->validateStringVal('toolsType');
        $brandMaker = $this->validateStringVal('brandMaker');   //品牌/制造商string
        $toolsModel = $this->validateStringVal('toolsModel');
        $selectSeat = $this->validateIntVal('selectSeat');

        $toolsCode = $this->validateStringVal('toolsCode');
        $tools_no = $this->validateStringVal('toolsNo');
        //编辑 
        $id = $this->validateIntVal('id');
        $toolsMenu = $this->validateIntVal('types');
        $makerId = $this->validateIntVal('makerId');    //品牌/制造商int
        $toolsApplication = $this->validateStringVal('toolsApplication');
        $imgsrc = $this->validateStringVal('imgsrc');
        $paramenter = $this->validateStringVal('paramenter');
        $indexMeasure = $this->validateStringVal('indexMeasure');

        //明细 -- 工具状态 -- 管理工具      
        $lineId = $this->validateIntVal('lineId');
        $stageId = $this->validateIntVal('stageId');
        $seatId = $this->validateIntVal('positionId');
        $recipient = $this->validateStringVal('recipient');
        $certificate = $this->validateStringVal('certificate');
        $entryTime = $this->validateIntVal('entryTime');
        $addTime = $this->validateIntVal('addTime');

        $toolsNo = $this->returnToolsNo($toolsCode, $quantity);
        //分页
        $perPage = $this->validateIntVal('perPage', 20);
        $curPage = $this->validateIntVal('curPage', 1);
        //导出明细D:\php\xampp\htdocs\bms\protected\controllers\OrderController.php 573
        try {
            $seeker = new ToolsHome();
            switch ($type) {
                case 'selectDistributor': //供应商下拉框
                    $data = $seeker->querySelectDistributor();
                    $this->renderJsonBms(true, 'OK', $data);
                    break;
                case 'selectMaker': //供应商下拉框
                    $data = $seeker->querySelectMaker();
                    $this->renderJsonBms(true, 'OK', $data);
                    break;
                case 'selectUnit': //供应商下拉框
                    $data = $seeker->querySelectUnit();
                    $this->renderJsonBms(true, 'OK', $data);
                    break;
                case 'checkCertificate': //频证检测
                    $data = $seeker->queryCheckCertificate($recipient); 
                    //$data = 'test ertificate';
                    $this->renderJsonBms(true, 'OK', $data);
                    break;
                case 'selects': //查询
                    if (empty($toolsCode) && empty($materialCode) && empty($toolsName) && empty($toolsType) && empty($brandMaker) && empty($toolsModel)) {
                        throw new Exception("有必填项为空");
                    }
                    list($total, $data) = $seeker->queryToolsManage($toolsCode, $materialCode, $toolsName, $toolsType, $brandMaker, $toolsModel, $selectSeat, $status, $curPage, $perPage);
                    $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'listData' => $data,
                    );
                    $this->renderJsonBms(true, 'OK', $ret);
                    break;
                case 'selectList': //查询流水
                    if (empty($toolsCode)) {
                        throw new Exception("有必填项为空");
                    }
                    list($total, $data) = $seeker->queryToolsManageList($toolsCode, $curPage, $perPage);
                    $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'listData' => $data,
                    );
                    $this->renderJsonBms(true, 'OK', $ret);
                    break;
                case 'selectToolsNoList': //查询编号流水
                    if (empty($tools_no)) {
                        throw new Exception("有必填项为空");
                    }
                    list($total, $data) = $seeker->queryToolsNoList($tools_no, $curPage, $perPage);
                    $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'listData' => $data,
                    );
                    $this->renderJsonBms(true, 'OK', $ret);
                    break;
                case 'adds':    //增加 工具种类及数量
                    if (empty($operater) || empty($toolsCode)) {
                        throw new Exception("有必填项为空");
                    }
                    $recordToolsCodeExists = $seeker->queryRowToolsCode($toolsCode);
                    $toolsNoMin = $toolsNo[0];
                    $recordToolsNoExists = $seeker->queryRowToolsNo($toolsNoMin);
                    if ($recordToolsNoExists > 0 && $recordToolsCodeExists > 0) {
                        throw new Exception('抱歉，此记录已经存在');
                    }
                    $seeker->addToolsManage($toolsNo, $operater, $cost, $useCycles, $spareCycles, $warnCycles, $toolsCode, $distributor, $paramenter);
                    $this->renderJsonBms(true, 'OK', $paramenter);
                    break;
                case 'addsQty':    //增加 仅工具数量
                    if (empty($operater) || empty($toolsCode)) {
                        throw new Exception("有必填项为空addsQty");
                    }
                    $existsToolsNoMax = $seeker->queryListRowToolsCode($toolsCode);
                    $toolsNo = $this->returnToolsNo($toolsCode, $quantity, $existsToolsNoMax + 1);
                    $toolsNoMax = $toolsNo[$quantity - 1];
                    $recordToolsNoExists = $seeker->queryRowToolsNo($toolsNoMax);
                    if ($recordToolsNoExists > 0) {
                        throw new Exception('抱歉，addsQty此记录已经存在');
                    }
                    $seeker->addToolsManage($toolsNo, $operater, $cost, $useCycles, $spareCycles, $warnCycles, $toolsCode, $distributor, $paramenter);
                    $this->renderJsonBms(true, 'OK', $paramenter);
                    break;
                case 'checkCode': //检查工艺代码
                    if (empty($toolsCode)) {
                        throw new Exception('抱歉，工艺代码为空');
                    }
                    $data = $seeker->queryToolsCode($toolsCode);
                    if (count($data) == 0) {
                        $data = false;
                    }
                    $this->renderJsonBms(true, 'OK', $data);
                    break;
                case 'updates': //更新
                    if (empty($toolsCode) || empty($toolsMenu) || empty($materialCode) || empty($makerId)) {
                        throw new Exception('抱歉，有必填项为空');
                    }
                    $recordExists = $seeker->queryRowToolsCode($toolsCode, $id);
                    if ($recordExists > 0) {
                        $seeker->updateToolsManage($id, $materialCode, $toolsMenu, $toolsType, $makerId, $toolsName, $toolsModel, $toolsApplication, $imgsrc, $paramenter);
                    } else {
                        throw new Exception('抱歉，此记录不存在');
                    }
                    $this->renderJsonBms(true, 'OK', $paramenter);   //,$seeker->queryMaker('', 0, 1, 1, $makerId)
                    break;

                //明细 -- 工具状态 -- 管理工具
                case 'ToolsNoAdd': //新增一把工具一条流水
                    if (empty($id) || empty($status) || empty($useCycles) || empty($spareCycles) || empty($warnCycles) || empty($lineId) || empty($stageId) || empty($seatId) || empty($certificate) || empty($entryTime)) {
                        throw new Exception('抱歉，有必填项为空');
                        //throw new Exception($tools_no."|".$toolsCode."|".$status."|".$distributor."|". $operater."|". $cost."|". $useCycles."|". $spareCycles."|". $warnCycles."|". $lineId."|".$stageId."|".$seatId."|".$recipient."|".$addTime);
                    }
                    $seeker->addToolsNoList($id, intval($status), $distributor, $operater, $cost, $useCycles, $spareCycles, $warnCycles, $lineId, $stageId, $seatId, $recipient,$certificate,$indexMeasure, $entryTime);
                    $this->renderJsonBms(true, 'OK');   //,$seeker->queryMaker('', 0, 1, 1, $makerId)
                    break;
                case 'ToolsNoAdd2': //新增一把工具一条流水 状态初始时间$addTime不变(time())
                    if (empty($id) || empty($status) || empty($useCycles) || empty($spareCycles) || empty($warnCycles) || empty($lineId) || empty($stageId) || empty($seatId) || empty($certificate) || empty($entryTime) || empty($addTime)) {
                        throw new Exception('抱歉，有必填项为空');
                    }
                    $seeker->addToolsNoList($id, intval($status), $distributor, $operater, $cost, $useCycles, $spareCycles, $warnCycles, $lineId, $stageId, $seatId, $recipient,$certificate,$indexMeasure, $entryTime, $addTime);
                    $this->renderJsonBms(true, 'OK');
                    break;
                case 'ToolsNoUpdate': //更新一把工具一条流水
                    if (empty($id) || empty($useCycles) || empty($spareCycles) || empty($warnCycles) || empty($recipient)) {
                        throw new Exception('抱歉，有必填项为空');
                    }
                    $seeker->updateToolsNoList($id, $distributor, $operater, $cost, $useCycles, $spareCycles, $warnCycles, $recipient,$certificate,$indexMeasure);
                    $this->renderJsonBms(true, 'OK');
                    break;
                /////////////////////////////////////////////////////////////////    
                case 'deletes': //删除
                    $seeker->deldteParameter($id);
                    $this->renderJsonBms(true, 'OK');
                    break;
                default:
                    break;
            }
        } catch (Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    public function actionToolsUpload() {
        $this->render("toolsUpload", array("promptData" => ""));
    }

    function actionUpload() {
        if (!empty($_FILES['img']['tmp_name'])) {
            //echo json_encode(array('file_infor' => dirname(__FILE__)));exit;
            //$dirs = dirname(__FILE__);            
            /* 设定上传目录 */
            $uploads_dir = getcwd() . '\img\uploads';
            $uploads_dir = str_replace("\\","/",$uploads_dir);

//            chdir($uploads_dir);//转换新地址为当前目录,测试完关闭，不然无法正常上传
//            getcwd()  //打印出新目录的绝对地址

            /* 检测上传目录是否存在 */
            if (!is_dir($uploads_dir) || !is_writeable($uploads_dir)) {
                if (!mkdir($uploads_dir, 0777)) {
                    echo json_encode(array('file_infor' => "mkdir error"));
                    exit;
                }
            }

            /* 设置允许上传文件的类型 */
            $allow_type = array("image/jpg", "image/jpeg", "image/png", "image/pjpeg", "image/gif", "image/bmp", "image/x-png");
            $get_img_type = $_FILES['img']['type'];
            if (!in_array($get_img_type, $allow_type)) {
                echo json_encode(array('file_infor' => "图片格式不对，请重新上传!"));
                exit;
            }
            /* 设置文件名为"日期_文件名" */
            date_default_timezone_set('PRC');
            $newName = date("YmdHis") . "_" . $_FILES['img']['name'];
            $path = $uploads_dir . '/' . $newName;

            /* 移动上传文件到指定文件夹 */
            $state = move_uploaded_file($_FILES['img']['tmp_name'], $path);
            $imgsrc = 'img/uploads/' . $newName;

            if ($state) {
                $message = "文件上传成功!";
                $message .= "文件名：" . $newName . "";
                $message .= "大小：" . ( round($_FILES['img']['size'] / 1024, 2) ) . " KB";
            } else {
                /* 处理错误信息 */
                switch ($_FILES['img']['error']) {
                    case 1 : $message = "上传文件大小超出 php.ini:upload_max_filesize 限制";
                    case 2 : $message = "上传文件大小超出 MAX_FILE_SIZE 限制";
                    case 3 : $message = "文件仅被部分上传";
                    case 4 : $message = "没有文件被上传";
                    case 5 : $message = "找不到临时文件夹";
                    case 6 : $message = "文件写入失败";
                }
            }
            echo json_encode(array('file_infor' => $message, 'imgsrc' => $imgsrc));
            exit;
        } else {
            echo json_encode(array('file_infor' => 'false'));
        }
    }

    public function actionToolsListExport() {
        //导出明细 参考：\bms\protected\controllers\OrderController.php 573
        try {
            $materialCode = $this->validateStringVal('materialCode');
            $toolsName = $this->validateStringVal('toolsName');
            $toolsType = $this->validateStringVal('toolsType');
            $brandMaker = $this->validateStringVal('brandMaker');   //品牌/制造商string
            $toolsModel = $this->validateStringVal('toolsModel');
            $status = $this->validateStringVal('status');
            $selectSeat = $this->validateIntVal('selectSeat');

            $toolsCode = $this->validateStringVal('toolsCode');

            $seeker = new ToolsHome();
            if (empty($toolsCode) && empty($materialCode) && empty($toolsName) && empty($toolsType) && empty($brandMaker) && empty($toolsModel)) {
                throw new Exception("有必填项为空");
            }
            $datas = $seeker->queryToolsManage($toolsCode, $materialCode, $toolsName, $toolsType, $brandMaker, $toolsModel, $selectSeat, $status, 1, 99999);

            $content = "序号,工艺代码,工具名称,制造商,型号,数量\n";
            if (isset($datas[0]) && $datas[0] > 0 && count($datas[1]) > 0) {
                foreach ($datas[1] as $key => $val) {
                    $content .= ($key + 1) . ",";
                    $content .= "{$val['tools_code']},";
                    $content .= "{$val['name']},";
                    $content .= "{$val['maker_name']},";
                    $content .= "{$val['model']},";
                    $content .= "{$val['numes']},";
                    $content .= "\n";
                }
            } else {
                $content .= "\n\n暂,";
                $content .= "无,";
                $content .= "明,";
                $content .= "细,";
                $content .= "记,";
                $content .= "录,";
                $content .= "\n";
            }
            $export = new Export('工具库汇总明细_' . date('YmdHi'), $content);
            $export->toCSV();
        } catch (Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    //调拨
    public function actionSearchAssign() {

        $type = $this->validateStringVal('type');

        //查询
        $selectSeat = $this->validateIntVal('selectSeat');
        $status = $this->validateStringVal('status');
        $toolsCode = $this->validateStringVal('toolsCode');

        $toolsName = $this->validateStringVal('toolsName');
        $toolsType = $this->validateStringVal('toolsType');
        $brandMaker = $this->validateStringVal('brandMaker');   //品牌/制造商string
        $toolsModel = $this->validateStringVal('toolsModel');


        //新增
        $noListId = $this->validateStringVal('noListId');       //被选的
        $recipient = $this->validateStringVal('recipient');
        $certificate = $this->validateStringVal('certificate');

        $id = $this->validateIntVal('id');
        $toolsId = $this->validateIntVal('toolsId');
        //分页
        $perPage = $this->validateIntVal('perPage', 20);
        $curPage = $this->validateIntVal('curPage', 1);

        try {
            $seeker = new ToolsHome();
            switch ($type) {
                case 'selects': //查询
                    list($total, $data) = $seeker->queryToolsCheck($selectSeat, $status, $curPage, $perPage);
                    $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'listData' => $data,
                    );
                    $this->renderJsonBms(true, 'OK', $ret);
                    break;
                case 'addSelects': //新增/加号/交换 查询
                    if (empty($toolsCode) && empty($toolsName) && empty($toolsType) && empty($brandMaker) && empty($toolsModel)) {
                        throw new Exception("有必填项为空");
                    }
                    list($total, $data) = $seeker->queryToolsAssign($toolsCode, '', $toolsName, $toolsType, $brandMaker, $toolsModel, '', $status, $curPage, $perPage);
                    $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'listData' => $data,
                    );
                    $this->renderJsonBms(true, 'OK', $ret);
                    break;
                case 'selectList': //查询流水
                    if (empty($toolsCode)) {
                        throw new Exception("有必填项为空");
                    }
                    list($total, $data) = $seeker->queryToolsManageList($toolsCode, $curPage, $perPage);
                    $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'listData' => $data,
                    );
                    $this->renderJsonBms(true, 'OK', $ret);
                    break;
                case 'adds': //新增流水
                    if (empty($noListId) || empty($recipient) || empty($certificate)) {
                        throw new Exception('抱歉，有必填项为空' . $noListId . "|" . $recipient . "|" . $certificate . "|");
                    }
                    $seeker->ToolsListAssign($noListId, $recipient, $certificate);
                    $this->renderJsonBms(true, 'OK');   //,$seeker->queryMaker('', 0, 1, 1, $makerId)
                    break;
                case 'addExchange': //交换 1>原工具新增退库流水 2>新增一把工具（被选的）一条调拨流水
                    if (empty($toolsId) || empty($noListId) || empty($recipient) || empty($certificate)) {
                        throw new Exception('抱歉，有必填项为空');
                    }
                    $seeker->ToolsListExchange($toolsId, $noListId, $recipient, $certificate);
                    $seeker->ToolsNoExitStocks($toolsId);
                    $this->renderJsonBms(true, 'OK');
                    break;
                default:
                    break;
            }
        } catch (Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    //点检
    public function actionSearchCheck() {

        $type = $this->validateStringVal('type');

        //查询
        $selectSeat = $this->validateIntVal('selectSeat');
        $status = $this->validateStringVal('status');

        $toolsCode = $this->validateStringVal('toolsCode');

        $id = $this->validateIntVal('id');
        //分页
        $perPage = $this->validateIntVal('perPage', 20);
        $curPage = $this->validateIntVal('curPage', 1);

        try {
            $seeker = new ToolsHome();
            switch ($type) {
                case 'selects': //查询
                    if (empty($selectSeat) && empty($status)) {
                        throw new Exception("有必填项为空");
                    }
                    list($total, $data) = $seeker->queryToolsCheck($selectSeat, $status, $curPage, $perPage);
                    $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'listData' => $data,
                    );
                    $this->renderJsonBms(true, 'OK', $ret);
                    break;
                case 'selectList': //查询流水
                    if (empty($toolsCode)) {
                        throw new Exception("有必填项为空");
                    }
                    list($total, $data) = $seeker->queryToolsManageList($toolsCode, $curPage, $perPage);
                    $ret = array(
                        'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total),
                        'listData' => $data,
                    );
                    $this->renderJsonBms(true, 'OK', $ret);
                    break;
                //明细 -- 工具状态 -- 管理工具

                case 'checks': //点检：新增一把工具 -- 点检库流水
                    if (empty($id)) {
                        throw new Exception('抱歉，有必填项为空');
                    }
                    $seeker->ToolsNoChecked($id);
                    $this->renderJsonBms(true, 'OK');   //,$seeker->queryMaker('', 0, 1, 1, $makerId)
                    break;
                case 'checksAll': //点检全部：新增N把工具 -- 点检库流水
                    if (empty($toolsCode)) {
                        throw new Exception('抱歉，有必填项为空');
                    }
                    $idArr = $seeker->returnToolsNoId($toolsCode);
                    foreach ($idArr as $val) {
                        $seeker->ToolsNoChecked($val["id"]);
                    }
                    $this->renderJsonBms(true, 'OK', $idArr);   //,$seeker->queryMaker('', 0, 1, 1, $makerId)
                    break;
                case 'exitStocks': //退库：新增一把工具一条退库流水
                    if (empty($id)) {
                        throw new Exception('抱歉，有必填项为空');
                    }
                    $seeker->ToolsNoExitStocks($id);
                    $this->renderJsonBms(true, 'OK');
                    break;
                /////////////////////////////////////////////////////////////////    
                case 'deletes': //删除
                    $seeker->deldteParameter($id);
                    $this->renderJsonBms(true, 'OK');
                    break;
                default:
                    break;
            }
        } catch (Exception $e) {
            $this->renderJsonBms(false, $e->getMessage());
        }
    }

    //工具编号
    public function returnToolsNo($toolsCode, $quantity, $startNo = 1) {
        $Arr = array();
        for ($i = $startNo; $i < $quantity + $startNo; $i++) {
            if ($i < 10) {
                $Arr[] = $toolsCode . '-' . '000' . $i;
            } elseif ($i < 100 && $i >= 10) {
                $Arr[] = $toolsCode . '-' . '00' . $i;
            } elseif ($i < 1000 && $i >= 100) {
                $Arr[] = $toolsCode . '-' . '0' . $i;
            } elseif ($i >= 1000) {
                $Arr[] = $toolsCode . '-' . $i;
            }
        }
        return $Arr;
    }

}

?>
