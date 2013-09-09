<?php

class ToolsHome {

    private $User;

    public function __construct() {
        date_default_timezone_set('PRC');
        $this->User = Yii::app()->user->display_name;
    }

    //搜索匹配
    public static function getmakerData($table = 'tools_maker', $title = 'name') {
        $sql = "SELECT name FROM " . $table . " WHERE " . $title . "<>'' ORDER BY id ASC limit 99999";

        $data = Yii::app()->db->createCommand($sql)->queryAll();
        $str = '[';
        foreach ($data as $key => $value) {
            if ($key == 0) {
                $str .= '&quot;' . $value[$title] . '&quot;';
            } else {
                $str .= ',&quot;' . $value[$title] . '&quot;';
            }
        }
        $str .= ']';
        return $str;
    }

    /*     * *****************************************  制造商  ********************************************************** */

    //查询
    public function queryMaker($name, $is_using = -1, $curPage = 1, $perPage = 20, $id = 0) {

        try {
            $limit = $perPage;
            $offset = ($curPage - 1) * $perPage;
            $sql = "SELECT id, brand, name, display_name, is_using FROM tools_maker where 1=1 ";
            if ($id == 0) {
                if (!empty($name)) {
                    $sql .= "and (name LIKE '%" . $name . "%' or display_name LIKE '%" . $name . "%') ";
                }
                $sql .= "and is_using <= " . $is_using . " ";
            } else {
                $sql .= "and id = " . $id . " ";
            }
            $sql .= "ORDER BY id DESC";
            $data = Yii::app()->db->createCommand($sql . " LIMIT " . $offset . ", " . $limit . "")->queryAll();
            $total = count(Yii::app()->db->createCommand($sql)->queryAll());

            return array($total, $data);
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //查询：其他工具栏目制造商下拉框值
    public function querySelectMaker($is_using = -1, $limit = 99999) {

        try {
            $sql = "SELECT id, brand, name, display_name, is_using FROM tools_maker where 1=1 ";
            $sql .= "and is_using <= " . $is_using . " ";
            $sql .= "ORDER BY id DESC LIMIT " . $limit . " ";

            $data = Yii::app()->db->createCommand($sql)->queryAll();

            return $data;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //查询 品牌与制造厂家联合唯一是否存在
    public function queryRowMaker($name, $brand, $id = 0) {

        try {
            $sql = "SELECT id, brand, name, display_name, is_using FROM tools_maker where name = '" . $name . "' and brand = '" . $brand . "' ";
            if ($id > 0) {
                $sql .= " and id <> " . $id . " ";
            }
            $Row = Yii::app()->db->createCommand($sql)->queryRow();
            return $Row;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //增加
    public function addMaker($name, $display_name, $brand) {

        try {
            $sql = "INSERT INTO tools_maker (brand,name,display_name,is_using,add_time) VALUE ( '" . $brand . "', '" . $name . "', '" . $display_name . "', -1, " . time() . " )";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //更新
    public function updateMaker($id, $name, $display_name, $brand, $is_using) {

        try {
            $sql = "UPDATE tools_maker SET name='" . $name . "',display_name='" . $display_name . "',brand='" . $brand . "',is_using='" . $is_using . "' where id=" . $id . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //删除
    public function deldteMaker($id) {

        try {
            $sql = "DELETE FROM tools_maker where id=" . $id . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    /*     * *****************************************  供应商  ********************************************************** */

    //查询
    public function queryDistributor($name, $is_using = -1, $curPage = 1, $perPage = 20) {

        try {
            $limit = $perPage;
            $offset = ($curPage - 1) * $perPage;
            $sql = "SELECT id, contact, name, display_name, is_using FROM tools_distributor where 1=1 ";

            if (!empty($name)) {
                $sql .= "and (name LIKE '%" . $name . "%' or display_name LIKE '%" . $name . "%') ";
            }
            $sql .= "and is_using <= " . $is_using . " ";
            $sql .= "ORDER BY id DESC";

            $data = Yii::app()->db->createCommand($sql . " LIMIT " . $offset . ", " . $limit . "")->queryAll();
            $total = count(Yii::app()->db->createCommand($sql)->queryAll());

            return array($total, $data);
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //查询：其他工具栏目供应商下拉框值
    public function querySelectDistributor($is_using = -1, $limit = 99999) {

        try {

            $sql = "SELECT id, name, display_name FROM tools_distributor where 1=1 ";
            $sql .= "and is_using <= " . $is_using . " ";
            $sql .= "ORDER BY id DESC LIMIT " . $limit . " ";

            $data = Yii::app()->db->createCommand($sql)->queryAll();

            return $data;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //查询 供应商唯一是否存在
    public function queryRowDistributor($name, $id = 0) {

        try {
            $sql = "SELECT id, contact, name, display_name, is_using FROM tools_distributor where name = '" . $name . "' ";
            if ($id > 0) {
                $sql .= " and id <> " . $id . " ";
            }
            $Row = Yii::app()->db->createCommand($sql)->queryRow();
            return $Row;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //增加
    public function addDistributor($name, $display_name, $contact) {

        try {
            $sql = "INSERT INTO tools_distributor (contact,name,display_name,is_using,add_time) VALUE ( '" . $contact . "', '" . $name . "', '" . $display_name . "', -1, " . time() . " )";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //更新
    public function updateDistributor($id, $name, $display_name, $contact, $is_using) {

        try {
            $sql = "UPDATE tools_distributor SET name='" . $name . "',display_name='" . $display_name . "',contact='" . $contact . "',is_using='" . $is_using . "' where id=" . $id . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //删除
    public function deldteDistributor($id) {

        try {
            $sql = "DELETE FROM tools_distributor where id=" . $id . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    /*     * *****************************************  参数单位  ********************************************************** */

    //查询
    public function queryParameter($name, $is_using = -1, $curPage = 1, $perPage = 20) {

        try {
            $limit = $perPage;
            $offset = ($curPage - 1) * $perPage;
            $sql = "SELECT id, unit, name, is_using FROM tools_parameter where 1=1 ";

            if (!empty($name)) {
                $sql .= "and name LIKE '%" . $name . "%'  ";
            }
            $sql .= "and is_using <= " . $is_using . " ";
            $sql .= "ORDER BY id DESC";

            $data = Yii::app()->db->createCommand($sql . " LIMIT " . $offset . ", " . $limit . "")->queryAll();
            $total = count(Yii::app()->db->createCommand($sql)->queryAll());

            return array($total, $data);
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //查询：其他工具栏目参数单位下拉框值
    public function querySelectUnit($is_using = -1, $limit = 99999) {

        try {

            $sql = "SELECT id, name, unit FROM tools_parameter where is_using <= " . $is_using . " ORDER BY id DESC LIMIT " . $limit . " ";

            $data = Yii::app()->db->createCommand($sql)->queryAll();

            return $data;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //查询：其他工具栏目参数单位:id查单位名称
    public function paramenterIdToName($paramenterStr) {

        $arr = array();
        $arr = explode("|", $paramenterStr);
        foreach ($arr as $key => $value) {
            $data = array();
            $data = explode(";", $value);
            if (isset($data[2]) && intval($data[2]) > 0) {

                try {
                    $sql = "SELECT unit FROM tools_parameter where id = " . intval($data[2]) . " ORDER BY id DESC LIMIT 1 ";
                    $RS = Yii::app()->db->createCommand($sql)->queryAll();
                    if (isset($RS[0]["unit"])) {
                        $arr[$key] = $data[0] . ';' . $data[1] . ';' . $RS[0]["unit"];
                    }
                } catch (Exception $e) {    //不做处理,可记log
                }
            }
        }
        return implode("|", $arr);
    }

    //查询 参数唯一是否存在
    public function queryRowParameter($name, $unit, $id = 0) {

        try {
            $sql = "SELECT id, unit, name, is_using FROM tools_parameter where name = '" . $name . "' and unit = '" . $unit . "' ";
            if ($id > 0) {
                $sql .= " and id <> " . $id . " ";
            }
            $Row = Yii::app()->db->createCommand($sql)->queryRow();
            return $Row;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //增加
    public function addParameter($name, $unit) {

        try {
            $sql = "INSERT INTO tools_parameter(unit,name,is_using,add_time) VALUE ( '" . $unit . "', '" . $name . "', -1, " . time() . " )";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //更新
    public function updateParameter($id, $name, $unit, $is_using) {

        try {
            $sql = "UPDATE tools_parameter SET name='" . $name . "',unit='" . $unit . "',is_using='" . $is_using . "' where id=" . $id . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //删除
    public function deldteParameter($id) {

        try {
            $sql = "DELETE FROM tools_parameter where id=" . $id . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    /*     * *****************************************  领用单位  ********************************************************** */

    //查询级联下拉
    public function queryToolsUserMenu($parentId) {

        try {
            $sql = "SELECT id, name FROM tools_tools_user where parent_id = " . $parentId . " and is_using <>0 ORDER BY id DESC";

            $data = Yii::app()->db->createCommand($sql)->queryAll();
            return $data;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //查询
    public function queryToolsUser($name, $StageId, $is_seat = -1, $is_using = -1, $curPage = 1, $perPage = 20) {

        try {
            $limit = $perPage;
            $offset = ($curPage - 1) * $perPage;
            $sql = "SELECT id, name,parent_id,is_using,is_seat FROM tools_tools_user where 1=1 ";

            if (!empty($name)) {
                $sql .= "and name LIKE '%" . $name . "%' ";
            }
            if ($StageId != 0) {
                $sql .= "and parent_id = " . $StageId . " ";
            }
            $sql .= "and is_using <= " . $is_using . " and is_seat <= " . $is_seat . " ";
            $sql .= "ORDER BY id DESC";

            $data = Yii::app()->db->createCommand($sql . " LIMIT " . $offset . ", " . $limit . "")->queryAll();
            $arr = array();
            foreach ($data as $key => $value) {
                if ($value["parent_id"] == 0) { //线别
                    $arr[$key]["id"] = $value["id"];
                    $arr[$key]["name"] = '';
                    $arr[$key]["stageId"] = 0;
                    $arr[$key]["stageName"] = '';
                    $arr[$key]["lineId"] = $value["id"];
                    $arr[$key]["lineName"] = $value["name"];
                    $arr[$key]["is_using"] = $value["is_using"];
                    $arr[$key]["is_seat"] = 0;
                } else {
                    $MenuArr = $this->returnMenuInfo($value["parent_id"]);
                    if (count($MenuArr) > 0) {
                        if ($value["is_seat"] == -1 && isset($MenuArr["parent"])) { //工位
                            $arr[$key]["id"] = $value["id"];
                            $arr[$key]["name"] = $value["name"];
                            $arr[$key]["stageId"] = isset($MenuArr["Id"]) ? $MenuArr["Id"] : '';
                            $arr[$key]["stageName"] = isset($MenuArr["Name"]) ? $MenuArr["Name"] : '';
                            $arr[$key]["lineId"] = isset($MenuArr["parent"]["Id"]) ? $MenuArr["parent"]["Id"] : '';
                            $arr[$key]["lineName"] = isset($MenuArr["parent"]["Name"]) ? $MenuArr["parent"]["Name"] : '';
                            $arr[$key]["is_seat"] = -1;
                            $arr[$key]["is_using"] = $value["is_using"];
                        } else if ($value["is_seat"] != -1 && !isset($MenuArr["parent"])) { //工段
                            $arr[$key]["id"] = $value["id"];
                            $arr[$key]["name"] = '';
                            $arr[$key]["stageId"] = $value["id"];
                            $arr[$key]["stageName"] = $value["name"];
                            $arr[$key]["lineId"] = isset($MenuArr["Id"]) ? $MenuArr["Id"] : '';
                            $arr[$key]["lineName"] = isset($MenuArr["Name"]) ? $MenuArr["Name"] : '';
                            $arr[$key]["is_seat"] = 0;
                            $arr[$key]["is_using"] = $value["is_using"];
                        }
                    }
                    unset($MenuArr);
                }
            }

            $total = count(Yii::app()->db->createCommand($sql)->queryAll());

            return array($total, $arr);
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    public function returnMenuInfo($Id) {  //$Id为工位的parent_id
        $sql = "SELECT id, name, parent_id FROM tools_tools_user where id = " . $Id . " ";
        $rs = Yii::app()->db->createCommand($sql)->queryAll();
        $arr = array();
        if (is_array($rs) && isset($rs[0]) && count($rs[0]) > 0) {
            if ($rs[0]["parent_id"] > 0) {
                $arr["Id"] = $rs[0]["id"];
                $arr["Name"] = $rs[0]["name"];
                $MenuInfo["parent"] = $this->returnMenuInfo($rs[0]["parent_id"]);
                $arr = array_merge($arr, $MenuInfo);
            } else if ($rs[0]["parent_id"] == 0) {
                $arr["Id"] = $rs[0]["id"];
                $arr["Name"] = $rs[0]["name"];
            }
        } else {
            return array();
        }
        return $arr;
    }

    //根据id查name
    public function returnMenuNameById($Id, $is_seat = 0) {    //is_seat = -1特指工位
        $sql = "SELECT name FROM tools_tools_user where id = " . $Id . "  ";
        if (!empty($is_seat)) {
            $sql .= " and is_seat = " . $is_seat . " ";
        }
        $rs = Yii::app()->db->createCommand($sql)->queryAll();
        $data = '';
        if (isset($rs[0])) {
            $data = $rs[0]["name"];
        }
        return $data;
    }

    //查询 记录是否存在
    public function queryRowToolsUser($name, $id = 0) {

        try {
            $sql = "SELECT id, name FROM tools_tools_user where name = '" . $name . "' ";
            if ($id > 0) {
                $sql .= " and id <> " . $id . " ";
            }
            $Row = Yii::app()->db->createCommand($sql)->queryRow();
            return $Row;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //增加线别/工段/工位    $is_using=-1 启用;$is_seat=-1工位
    public function addToolsUser($name, $parent_id = 0, $is_using = -1, $is_seat = -1) {

        try {
            $sql = "INSERT INTO tools_tools_user(name,parent_id,is_using,is_seat,add_time) VALUE ( '" . $name . "', " . $parent_id . ", " . $is_using . ", " . $is_seat . ", " . time() . " )";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //更新
    public function updateToolsUser($id, $name, $StageId, $is_using) {

        try {
            $sql = "UPDATE tools_tools_user SET name='" . $name . "',parent_id=" . $StageId . ",is_using=" . $is_using . " where id=" . $id . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //删除
    public function deldteToolsUser($id) {

        try {
            $sql = "DELETE FROM tools_tools_user where id=" . $id . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    /*     * *****************************************  工具库管理  ********************************************************** */

    //查询
    public function queryToolsManage($toolsCode, $materialCode, $toolsName, $toolsType, $brandMaker, $toolsModel, $selectSeat, $status, $curPage = 1, $perPage = 20) {

        try {
            $limit = $perPage;
            $offset = ($curPage - 1) * $perPage;
            $sql = "SELECT A.tools_code tools_code,A.*,B.tools_no tools_no,B.id listId FROM tools_management A,tools_management_list B where A.tools_code = B.tools_code";
            if (!empty($toolsCode)) {
                $sql .= " and A.tools_code LIKE '%" . $toolsCode . "%'";
            }
            if (!empty($materialCode)) {
                $sql .= " and A.material_code LIKE '%" . $materialCode . "%'";
            }
            if (!empty($toolsName)) {
                $sql .= " and A.name LIKE '%" . $toolsName . "%'";
            }
            if (!empty($toolsType)) {
                $sql .= " and A.tools_type LIKE '%" . $toolsType . "%'";
            }
            if (!empty($brandMaker)) {
                $sql .= " and A.maker_name LIKE '%" . $brandMaker . "%'";
            }
            if (!empty($toolsModel)) {
                $sql .= " and A.model LIKE '%" . $toolsModel . "%'";
            }

            if (!empty($selectSeat)) {
                $sql .= " and B.position_id = " . $selectSeat . "";
            }

            $sql .= " and B.id in(select MAX(id) from tools_management_list where 1 ";

            $sql .= " group by tools_no) ";
            //状态：1闲置（正常|1.2点检提醒｜1.3点检警告） 2使用（正常|2.2点检提醒｜2.3超期使用） 3维修  4退库 5报废
            if (!empty($status)) {
                $sql .= " and ( 1=2 ";
                $statusArr = explode(',', $status);
                foreach ($statusArr as $key => $value) {
                    $sql .= "  or (B.status = " . intval($value) . " ";
                    switch ($value * 10) {
                        case 10:
                            $sql .= " and B.entry_time >= " . time() . "-B.spare_cycles*86400";
                            break;
                        case 12:
                            $sql .= " and B.entry_time < " . time() . "-B.spare_cycles*86400";
                            break;
                        case 13:
                            $sql .= " and B.entry_time < " . time() . "-B.spare_cycles*86400-B.warn_cycles*86400";
                            break;
                        case 20:
                            $sql .= " and B.add_time >= " . time() . "-B.use_cycles*86400";
                            break;
                        case 22:
                            $sql .= " and B.add_time < " . time() . "-B.use_cycles*86400";
                            break;
                        case 23:
                            $sql .= " and B.add_time < " . time() . "-B.use_cycles*86400-B.warn_cycles*86400";
                            break;
                        default:
                            break;
                    }
                    $sql .= ")";
                }
                $sql .= ")";
            }//

            $sql .= " group by A.tools_code ORDER BY A.id DESC";

            $data = Yii::app()->db->createCommand($sql . " LIMIT " . $offset . ", " . $limit . "")->queryAll();
            $total = count(Yii::app()->db->createCommand($sql)->queryAll());
            foreach ($data as $k => $val) {
                //查询工具汇总数量
                $arr = array();
                $toolsMaxNo = $this->getMaxToolsNo($val["tools_code"]);
                if (!empty($toolsMaxNo)) {
                    $arr = explode('-', $toolsMaxNo);
                }
                if (isset($arr[1])) {
                    $data[$k]["numes"] = intval($arr[1]);
                } else {
                    $data[$k]["numes"] = 0;
                }
                $data[$k]["paramenter_new"] = $this->paramenterIdToName($val["paramenter"]);
            }
            //return $sql;
            return array($total, $data);
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    public function getMaxToolsNo($toolsCode) {
        $sql = "SELECT max( tools_no ) tools_no FROM tools_management_list WHERE tools_code = '" . $toolsCode . "' ";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        $str = null;
        if (isset($data[0]["tools_no"])) {
            $str = $data[0]["tools_no"];
        }
        return $str;
    }

    //查询工具类某一属性值
    public function queryToolsManageField($toolsCode, $ReturnField) {
        $sql = "SELECT '" . $ReturnField . "' FROM tools_managementt where tools_code = '" . $toolsCode . "' ORDER BY add_time desc,id desc limit 1";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        $ReturnValue = null;
        if (isset($data[0])) {
            $ReturnValue = $data[0][$ReturnField];
        }
        return $ReturnValue;
    }

    //查询流水
    public function queryToolsManageList($toolsCode, $curPage = 1, $perPage = 20) {

        try {
            $limit = $perPage;
            $offset = ($curPage - 1) * $perPage;
            $sql = "SELECT * FROM tools_management_list where tools_code = '" . $toolsCode . "' ORDER BY add_time desc,id desc";

            $data = Yii::app()->db->createCommand($sql . " LIMIT " . $offset . ", " . $limit . "")->queryAll();
            foreach ($data as $k => $val) {
                if (!empty($val["position_id"])) {    //工位
                    $data[$k]["seat"] = $this->returnMenuNameById($val["position_id"], $is_seat = -1);
                }
                if (!empty($val["line_id"])) {        //线别
                    $data[$k]["lineName"] = $this->returnMenuNameById($val["line_id"], $is_seat = 0);
                }
//                if(!empty($val["stage_id"])){       //工段
//                    $data[$k]["stageName"] = $this->returnMenuNameById($val["stage_id"],$is_seat=0);
//                }
                //状态:1闲置（正常|1.2点检提醒｜1.3点检警告） 2使用（正常|2.2点检提醒｜2.3超期使用） 3维修  4退库 5报废
                if ($val["status"] == 1 && $val["entry_time"] >= time() - $val["spare_cycles"] * 86400) {
                    $data[$k]["statusName"] = "正常[闲置]";
                } elseif ($val["status"] == 1 && $val["entry_time"] < time() - $val["spare_cycles"] * 86400) {
                    $data[$k]["statusName"] = "点检提醒[闲置]";
                } elseif ($val["status"] == 1 && $val["entry_time"] < time() - $val["spare_cycles"] * 86400 - $val["warn_cycles"] * 86400) {
                    $data[$k]["statusName"] = "点检警告[闲置]";
                } elseif ($val["status"] == 2 && $val["add_time"] >= time() - $val["use_cycles"] * 86400) {
                    $data[$k]["statusName"] = "正常[使用]";
                } elseif ($val["status"] == 2 && $val["add_time"] < time() - $val["use_cycles"] * 86400) {
                    $data[$k]["statusName"] = "点检提醒[使用]";
                } elseif ($val["status"] == 2 && $val["add_time"] < time() - $val["use_cycles"] * 86400 - $val["warn_cycles"] * 86400) {
                    $data[$k]["statusName"] = "点检警告[使用]";
                } elseif ($val["status"] == 3) {
                    $data[$k]["statusName"] = "维修";
                } elseif ($val["status"] == 4) {
                    $data[$k]["statusName"] = "退库";
                } elseif ($val["status"] == 5) {
                    $data[$k]["statusName"] = "报废";
                } else {
                    $data[$k]["statusName"] = "未知状态";
                }
                //闲置或使用周期
                $data[$k]["date_cycles"] = '';
                if ($val["status"] == 1 && isset($val["add_time"])) {
                    $data[$k]["date_cycles"] = '周期：' . date("Y-m-d", $val["add_time"]) . " 至 " . date("Y-m-d H:i:s", $val["add_time"] + ($val["spare_cycles"] + $val["warn_cycles"]) * 86400);
                } elseif ($val["status"] == 2 && isset($val["add_time"])) {
                    $data[$k]["date_cycles"] = '周期：' . date("Y-m-d", $val["add_time"]) . " 至 " . date("Y-m-d H:i:s", $val["add_time"] + ($val["use_cycles"] + $val["warn_cycles"]) * 86400);
                }
            }
            $total = count(Yii::app()->db->createCommand($sql)->queryAll());

            return array($total, $data);
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //查询工具编号流水
    public function queryToolsNoList($tools_no, $curPage = 1, $perPage = 20) {

        try {
            $limit = $perPage;
            $offset = ($curPage - 1) * $perPage;
            $sql = "SELECT * FROM tools_management_list where tools_no = '" . $tools_no . "' ORDER BY add_time desc,id desc";

            $data = Yii::app()->db->createCommand($sql . " LIMIT " . $offset . ", " . $limit . "")->queryAll();
            foreach ($data as $k => $val) {
                if (!empty($val["position_id"])) {    //工位
                    $data[$k]["seat"] = $this->returnMenuNameById($val["position_id"], $is_seat = -1);
                }
                if (!empty($val["line_id"])) {        //线别
                    $data[$k]["lineName"] = $this->returnMenuNameById($val["line_id"], $is_seat = 0);
                }
//                if(!empty($val["stage_id"])){       //工段
//                    $data[$k]["stageName"] = $this->returnMenuNameById($val["stage_id"],$is_seat=0);
//                }
                if (!empty($val["add_time"])) {        //线别
                    $data[$k]["add_time"] = date("Y-m-d H:i:s", $val["add_time"]);
                }
                //管理动作 0入库 1调拨 2点检 3报修 4退库 5报废
                if ($val["operate"] == 0) {
                    $data[$k]["operateName"] = "入库";
                } elseif ($val["operate"] == 1) {
                    $data[$k]["operateName"] = "调拨";
                } elseif ($val["operate"] == 2) {
                    $data[$k]["operateName"] = "点检";
                } elseif ($val["operate"] == 3) {
                    $data[$k]["operateName"] = "报修";
                } elseif ($val["operate"] == 4) {
                    $data[$k]["operateName"] = "退库";
                } elseif ($val["operate"] == 5) {
                    $data[$k]["operateName"] = "报废";
                } else {
                    $data[$k]["operateName"] = "未知状态";
                }
                //状态:1闲置（正常|1.2点检提醒｜1.3点检警告） 2使用（正常|2.2点检提醒｜2.3超期使用） 3维修  4退库 5报废
                if ($val["status"] == 1 && $val["entry_time"] >= time() - $val["spare_cycles"] * 86400) {
                    $data[$k]["statusName"] = "正常[闲置]";
                } elseif ($val["status"] == 1 && $val["entry_time"] < time() - $val["spare_cycles"] * 86400) {
                    $data[$k]["statusName"] = "点检提醒[闲置]";
                    $data[$k]["status"] = 1.2;
                } elseif ($val["status"] == 1 && $val["entry_time"] < time() - $val["spare_cycles"] * 86400 - $val["warn_cycles"] * 86400) {
                    $data[$k]["statusName"] = "点检警告[闲置]";
                    $data[$k]["status"] = 1.3;
                } elseif ($val["status"] == 2 && $val["add_time"] >= time() - $val["use_cycles"] * 86400) {
                    $data[$k]["statusName"] = "正常[使用]";
                } elseif ($val["status"] == 2 && $val["add_time"] < time() - $val["use_cycles"] * 86400) {
                    $data[$k]["statusName"] = "点检提醒[使用]";
                    $data[$k]["status"] = 2.2;
                } elseif ($val["status"] == 2 && $val["add_time"] < time() - $val["use_cycles"] * 86400 - $val["warn_cycles"] * 86400) {
                    $data[$k]["statusName"] = "点检警告[使用]";
                    $data[$k]["status"] = 2.3;
                } elseif ($val["status"] == 3) {
                    $data[$k]["statusName"] = "维修";
                } elseif ($val["status"] == 4) {
                    $data[$k]["statusName"] = "退库";
                } elseif ($val["status"] == 5) {
                    $data[$k]["statusName"] = "报废";
                } else {
                    $data[$k]["statusName"] = "未知状态";
                }
            }
            $total = count(Yii::app()->db->createCommand($sql)->queryAll());

            return array($total, $data);
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //查询 工具类是否存在
    public function queryRowToolsCode($toolsCode, $id = 0) {

        try {
            $sql = "SELECT id, tools_code FROM tools_management where tools_code = '" . $toolsCode . "' ";
            if ($id > 0) {
                $sql .= " and id = " . $id . " ";
            }
            $Row = Yii::app()->db->createCommand($sql)->queryRow();
            return $Row;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //查询 已经存在某类工具的把数
    public function queryListRowToolsCode($toolsCode) {
        try {
            $sql = "SELECT * FROM tools_management_list where tools_code = '" . $toolsCode . "' group by tools_no";
            $RS = Yii::app()->db->createCommand($sql)->queryAll();
            if (count($RS) > 0) {
                $RScount = count($RS);
            } else {
                $RScount = 0;
            }
            return $RScount;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //返回已存在工艺代码的相关参数值
    public function queryToolsCode($toolsCode, $id = 0) {

        try {
            $sql = "SELECT paramenter FROM tools_management where tools_code = '" . $toolsCode . "' ";
            if ($id > 0) {
                $sql .= " and id <> " . $id . " ";
            }
            $data = Yii::app()->db->createCommand($sql)->queryAll();
            $datas = $Arr = $valArr = array();
            if (isset($data[0]["paramenter"]))
                $datas = explode("|", $data[0]["paramenter"]);
            foreach ($datas as $key => $value) {
                $valArr = explode(";", $value);
                $Arr[$key]["names"] = isset($valArr[0]) ? $valArr[0] : '';
                $Arr[$key]["vals"] = isset($valArr[1]) ? $valArr[1] : '';
                $Arr[$key]["unit"] = isset($valArr[2]) ? $valArr[2] : '';
            }
            return $Arr;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //查询 工具流水是否存在
    public function queryRowToolsNo($toolsNo, $id = 0) {

        try {
            $sql = "SELECT id, tools_no FROM tools_management_list where tools_no = '" . $toolsNo . "' ";
            if ($id > 0) {
                $sql .= " and id <> " . $id . " ";
            }
            $Row = Yii::app()->db->createCommand($sql)->queryRow();
            return $Row;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //增加
    public function addToolsManage($toolsNo, $operater, $cost, $useCycles, $spareCycles, $warnCycles, $toolsCode, $distributor, $paramenter) {

        try {
            $sql = "INSERT INTO tools_management_list (tools_no,tools_code,operate,operater,status,distributor,cost,use_cycles,spare_cycles,warn_cycles,position_id,entry_time,add_time) VALUE ";
            foreach ($toolsNo as $key => $value) {
                if ($key == 0) {
                    $sql .= "( '" . $value . "','" . $toolsCode . "', 0, '" . $operater . "',  1," . $distributor . ", " . $cost . ", " . $useCycles . ", " . $spareCycles . ", " . $warnCycles . ",0, " . time() . ", " . time() . ")";
                } else {
                    $sql .= ",( '" . $value . "','" . $toolsCode . "', 0, '" . $operater . "', 1," . $distributor . ", " . $cost . ", " . $useCycles . ", " . $spareCycles . ", " . $warnCycles . ",0, " . time() . ", " . time() . ")";
                }
            }
            Yii::app()->db->createCommand($sql)->execute();
            //检查工具类是否入库
            $recordToolsCodeExists = $this->queryRowToolsCode($toolsCode);
            if ($recordToolsCodeExists > 0) {
                //已存在，不用添加
            } else {
                $sql = "INSERT INTO tools_management (tools_code,paramenter,add_time) VALUE ('" . $toolsCode . "','" . $paramenter . "'," . time() . ")";
                Yii::app()->db->createCommand($sql)->execute();
            }
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //新增一把工具一条流水
    public function addToolsNoList($id, $status, $distributor, $operater, $cost, $useCycles, $spareCycles, $warnCycles, $lineId, $stageId, $seatId, $recipient,$certificate,$indexMeasure, $entryTime, $addTime = 0) {

        try {
            if ($status > 0 && $status < 3) { //0入库 1调拨 2点检 3报修 4退库 5报费   
                $operate = 1;
            } else {
                $operate = $status;
            }
            if ($addTime == 0) {
                $addTime = time();
            }         
            $sql = "insert into tools_management_list (tools_no,tools_code,operate,operater,status,distributor,cost,use_cycles,spare_cycles,warn_cycles,line_id,stage_id,position_id,recipient,certificate,index_measure,entry_time,add_time) select tools_no,tools_code," . $operate . " ";
            if (!empty($operater)) {
                $sql .= ",'" . $operater . "' ";
            } else {
                $sql .= ",operater ";
            }
            $sql .= "," . $status . " ";
            if (!empty($distributor)) {
                $sql .= "," . $distributor . " ";
            } else {
                $sql .= ",distributor";
            }
            if (!empty($cost)) {
                $sql .= "," . $cost . " ";
            } else {
                $sql .= ",cost";
            }
            $sql .= "," . $useCycles . "," . $spareCycles . "," . $warnCycles . "," . $lineId . "," . $stageId . "," . $seatId . ",'" . $recipient . "','" . $certificate . "','".$indexMeasure."'," . $entryTime . ", " . $addTime . " from `tools_management_list` where id=" . $id . " ";

            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试" . $sql);
        }
    }

    //更新一把工具的一条流水
    public function updateToolsNoList($id, $distributor, $operater, $cost, $useCycles, $spareCycles, $warnCycles, $recipient,$certificate,$indexMeasure) {
        try {
            $sql = "UPDATE tools_management_list SET use_cycles=" . $useCycles . ",spare_cycles=" . $spareCycles . ",warn_cycles=" . $warnCycles . " ";
            if (!empty($distributor)) {
                $sql .= ",distributor=" . $distributor . " ";
            }
            if (!empty($operater)) {
                $sql .= ",operater='" . $operater . "' ";
            }
            if (!empty($cost)) {
                $sql .= ",cost=" . $cost . " ";
            }
            if (!empty($recipient)) {
                $sql .= ",recipient='" . $recipient . "' ";
            }
            if (!empty($certificate)) {
                $sql .= ",certificate='" . $certificate . "' ";
            }
            if (!empty($indexMeasure)) {
                $sql .= ",index_measure='" . $indexMeasure . "' ";
            }
            $sql .= " where id=" . $id . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //更新
    public function updateToolsManage($id, $materialCode, $toolsMenu, $toolsType, $makerId, $toolsName, $toolsModel, $toolsApplication, $imgsrc, $paramenter) {
        try {
            $makerNames = '';
            $makerName = $this->queryMaker('', 0, 1, 20, $makerId);
            if (isset($makerName[1][0]["name"])) {
                $makerNames = $makerName[1][0]["name"];
            }
            $sql = "UPDATE tools_management SET material_code='" . $materialCode . "',type=" . $toolsMenu . ",tools_type='" . $toolsType . "',brand_maker=" . $makerId . ",maker_name='" . $makerNames . "',name='" . $toolsName . "',model='" . $toolsModel . "',applications='" . $toolsApplication . "',imgsrc='" . $imgsrc . "',paramenter='" . $paramenter . "',add_time=" . time() . " where id=" . $id . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }
    //频证查询
    public function queryCheckCertificate($recipient){
        try {
            $sql = "SELECT certificate FROM user where username = '" . $recipient . "' ";
            $data = Yii::app()->db->createCommand($sql)->queryAll();
            if(isset($data[0]["certificate"]) && strlen($data[0]["certificate"])>0){
                return $data[0]["certificate"];
            }else{
                return '';
            }
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }        
    }
    /*     * *****************************************  工具调拨  ********************************************************** */

    public function queryToolsAssign($toolsCode, $materialCode, $toolsName, $toolsType, $brandMaker, $toolsModel, $selectSeat, $status, $curPage = 1, $perPage = 20) {

        try {
            $limit = $perPage;
            $offset = ($curPage - 1) * $perPage;
            $sql = "SELECT A.tools_code tools_code,A.*,B.tools_no tools_no,B.id listId,B.recipient,B.certificate FROM tools_management A,tools_management_list B where A.tools_code = B.tools_code";
            if (!empty($toolsCode)) {
                $sql .= " and A.tools_code LIKE '%" . $toolsCode . "%'";
            }
            if (!empty($materialCode)) {
                $sql .= " and A.material_code LIKE '%" . $materialCode . "%'";
            }
            if (!empty($toolsName)) {
                $sql .= " and A.name LIKE '%" . $toolsName . "%'";
            }
            if (!empty($toolsType)) {
                $sql .= " and A.tools_type LIKE '%" . $toolsType . "%'";
            }
            if (!empty($brandMaker)) {
                $sql .= " and A.maker_name LIKE '%" . $brandMaker . "%'";
            }
            if (!empty($toolsModel)) {
                $sql .= " and A.model LIKE '%" . $toolsModel . "%'";
            }

            if (!empty($selectSeat)) {
                $sql .= " and B.position_id = " . $selectSeat . "";
            }

            $sql .= " and B.id in(select MAX(id) from tools_management_list where 1 ";
            $sql .= " group by tools_no) ";

            //状态：1闲置（正常|1.2点检提醒｜1.3点检警告） 2使用（正常|2.2点检提醒｜2.3超期使用） 3维修  4退库 5报废
            if (!empty($status)) {
                $sql .= " and ( 1=2 ";
                $statusArr = explode(',', $status);
                foreach ($statusArr as $key => $value) {
                    $sql .= "  or status = " . intval($value) . " ";
                }
                $sql .= ")";
            }//

            $sql .= " ORDER BY B.id DESC";

            $data = Yii::app()->db->createCommand($sql . " LIMIT " . $offset . ", " . $limit . "")->queryAll();
            $total = count(Yii::app()->db->createCommand($sql)->queryAll());
            foreach ($data as $k => $val) {
                //查询工具汇总数量
                $arr = array();
                $toolsMaxNo = $this->getMaxToolsNo($val["tools_code"]);
                if (!empty($toolsMaxNo)) {
                    $arr = explode('-', $toolsMaxNo);
                }
                if (isset($arr[1])) {
                    $data[$k]["numes"] = intval($arr[1]);
                } else {
                    $data[$k]["numes"] = 0;
                }
                $data[$k]["paramenter_new"] = $this->paramenterIdToName($val["paramenter"]);
            }
            return array($total, $data);
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //新增（1）将工具状态切换为“正常使用”（2）将“开始时间点”更新为当前 (3)动作为“调拨”的流水

    public function ToolsListAssign($noListId, $recipient, $certificate) {
        try {
            $user = $this->User;
            $idArr = explode(",", $noListId);
            $recipientArr = explode(",", $recipient);
            $certificateArr = explode(",", $certificate);
            foreach ($idArr as $key => $value) {
                $id = $value;
                $recipient = $recipientArr[$key];
                $certificate = $certificateArr[$key];
                if (empty($id) || empty($recipient) || empty($certificate)) {
                    continue;
                }
                //需加判断$certificate的有效性，如果无效抛出异常------------------------------------------------
                $sql = "insert into tools_management_list (tools_no,tools_code,operate,operater,status,distributor,cost,use_cycles,spare_cycles,warn_cycles,line_id,stage_id,position_id,recipient,certificate,entry_time,add_time) select tools_no,tools_code,1,'" . $user . "',2,distributor,cost,use_cycles,spare_cycles,warn_cycles,line_id,stage_id,position_id,'" . $recipient . "','" . $certificate . "'," . time() . "," . time() . " from `tools_management_list` where id=" . $id . " ";
                Yii::app()->db->createCommand($sql)->execute();
            }
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //交换  新增一条正常状态的流水（工位 段位 线别值取交换前；状态为2 时间为当前时间，其他值取被选工具记录值）
    public function ToolsListExchange($toolsId, $noListId, $recipient, $certificate) {
        try {
            //查询 line_id,stage_id,position_id
            $seat_sql = "select line_id,stage_id,position_id from tools_management_list where id=" . $toolsId;
            $data = Yii::app()->db->createCommand($seat_sql)->queryAll();
            if (isset($data[0]["line_id"]) && isset($data[0]["stage_id"]) && isset($data[0]["position_id"])) {
                $line_id = $data[0]["line_id"];
                $stage_id = $data[0]["stage_id"];
                $position_id = $data[0]["position_id"];
            } else {
                $line_id = 1;
                $stage_id = 0;
                $position_id = 0;
            }
            $user = $this->User;
            //需加判断$certificate的有效性，如果无效抛出异常------------------------------------------------
            $sql = "insert into tools_management_list (tools_no,tools_code,operate,operater,status,distributor,cost,use_cycles,spare_cycles,warn_cycles,line_id,stage_id,position_id,recipient,certificate,entry_time,add_time) select tools_no,tools_code,1,'" . $user . "',2,distributor,cost,use_cycles,spare_cycles,warn_cycles," . $line_id . "," . $stage_id . "," . $position_id . ",'" . $recipient . "','" . $certificate . "'," . time() . "," . time() . " from `tools_management_list` where id=" . $noListId . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    /*     * *****************************************  工具点检  ********************************************************** */

    //查询
    public function queryToolsCheck($selectSeat, $status, $curPage = 1, $perPage = 20) {

        try {
            $limit = $perPage;
            $offset = ($curPage - 1) * $perPage;
            $sql = "SELECT A.tools_code tools_code,A.*,B.tools_no tools_no FROM tools_management A,tools_management_list B where A.tools_code = B.tools_code";

            if (!empty($selectSeat)) {
                $sql .= " and B.position_id = " . $selectSeat . "";
            }

            $sql .= " and B.id in(select MAX(id) from tools_management_list where 1 ";
            $sql .= " group by tools_no) ";

            //状态：1闲置（正常|1.2点检提醒｜1.3点检警告） 2使用（正常|2.2点检提醒｜2.3超期使用） 3维修  4退库 5报废
            if (!empty($status)) {
                $sql .= " and ( 1=2 ";
                $statusArr = explode(',', $status);
                foreach ($statusArr as $key => $value) {
                    $sql .= "  or (B.status = " . intval($value) . " ";
                    switch ($value * 10) {
                        case 10:
                            $sql .= " and B.entry_time >= " . time() . "-B.spare_cycles*86400";
                            break;
                        case 12:
                            $sql .= " and B.entry_time < " . time() . "-B.spare_cycles*86400";
                            break;
                        case 13:
                            $sql .= " and B.entry_time < " . time() . "-B.spare_cycles*86400-B.warn_cycles*86400";
                            break;
                        case 20:
                            $sql .= " and B.add_time >= " . time() . "-B.use_cycles*86400";
                            break;
                        case 22:
                            $sql .= " and B.add_time < " . time() . "-B.use_cycles*86400";
                            break;
                        case 23:
                            $sql .= " and B.add_time < " . time() . "-B.use_cycles*86400-B.warn_cycles*86400";
                            break;
                        default:
                            break;
                    }
                    $sql .= ")";
                }
                $sql .= ")";
            }//

            $sql .= " group by A.tools_code ORDER BY A.id DESC";

            $data = Yii::app()->db->createCommand($sql . " LIMIT " . $offset . ", " . $limit . "")->queryAll();
            $total = count(Yii::app()->db->createCommand($sql)->queryAll());
            foreach ($data as $k => $val) {
                //查询工具汇总数量
                $arr = array();
                $toolsMaxNo = $this->getMaxToolsNo($val["tools_code"]);
                if (!empty($toolsMaxNo)) {
                    $arr = explode('-', $toolsMaxNo);
                }
                if (isset($arr[1])) {
                    $data[$k]["numes"] = intval($arr[1]);
                } else {
                    $data[$k]["numes"] = 0;
                }
                $data[$k]["paramenter_new"] = $this->paramenterIdToName($val["paramenter"]);
            }
            return array($total, $data);
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //点检（1）将工具状态切换为“正常使用”（2）将“开始时间点”更新为当前
    public function ToolsNoChecked($id) {
        try {
            $user = $this->User;
            $sql = "insert into tools_management_list (tools_no,tools_code,operate,operater,status,distributor,cost,use_cycles,spare_cycles,warn_cycles,line_id,stage_id,position_id,recipient,certificate,entry_time,add_time) select tools_no,tools_code,2,'" . $user . "',2,distributor,cost,use_cycles,spare_cycles,warn_cycles,1,0,0,recipient,certificate," . time() . "," . time() . " from `tools_management_list` where id=" . $id . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //退库  -- 线别为1，工位为0
    public function ToolsNoExitStocks($id) {
        try {
            $user = $this->User;
            $sql = "insert into tools_management_list (tools_no,tools_code,operate,operater,status,distributor,cost,use_cycles,spare_cycles,warn_cycles,line_id,stage_id,position_id,recipient,certificate,entry_time,add_time) select tools_no,tools_code,4,'" . $user . "',1,distributor,cost,use_cycles,spare_cycles,warn_cycles,1,0,0,recipient,certificate," . time() . "," . time() . " from `tools_management_list` where id=" . $id . " ";
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

    //根据toolsCode查询id
    public function returnToolsNoId($toolsCode) {
        try {
            $data = array();
            $sql = "select MAX(id) id from tools_management_list where tools_code='" . $toolsCode . "' group by tools_no ";
            $data = Yii::app()->db->createCommand($sql)->queryAll();
            return $data;
        } catch (Exception $e) {
            throw new Exception("抱歉，数据库繁忙，请稍后再试");
        }
    }

}
