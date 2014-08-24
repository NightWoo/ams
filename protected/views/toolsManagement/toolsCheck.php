<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>总装长沙AMS</title>
        <!--css styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet">
        <link href="/bms/css/common.css" rel="stylesheet">

        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap-tooltip.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap-popover-check.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>
        <script type="text/javascript" src="/bms/js/tools/toolsCheck.js"></script>
    </head>
    <body>
        <?php
        require_once(dirname(__FILE__) . "/../common/head.php");
        ?>
        <div class="offhead">
            <?php
            require_once(dirname(__FILE__) . "/../common/left/tools_management_left.php");
            ?>

            <div id="bodyright" class="offset2"><!-- 页体 -->

                <div>
                    <ul class="breadcrumb">
                        <li><a href="#">工具管理</a><span class="divider">&gt;</span></li>
                        <li class="active">工具点检</li>            
                    </ul>
                </div>
                <div><!-- 主体 -->

                    <form id="form" class="well form-search">
                        <table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                                <td>线别</td>
                                <td>工段</td>
                                <td>工位</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><select name="select" id="selectLine" class="input-small">
                                    </select>
                                </td>
                                <td><select name="select" id="selectStage" class="input-small">
                                    </select>
                                </td>
                                <td><select name="select" id="selectSeat" class="input-small">
                                    </select>
                                </td>
                                <td colspan="5" align="center">
                                    &nbsp;&nbsp;<input name="button2" type="button" class="btn btn-primary" id="btnQuery" value="查询">&nbsp;&nbsp;
                                    <!--1闲置（正常|点检提醒｜点检警告） 2使用（正常|点检提醒｜超期使用） 3维修  4退库 5报废-->
                                    &nbsp;<input type="checkbox" name="checkboxStatus" value="1.2" checked="checked">&nbsp;点检提醒[闲置]
                                    &nbsp;<input type="checkbox" name="checkboxStatus" value="1.3" checked="checked">&nbsp;点检警告[闲置]
                                    &nbsp;<input type="checkbox" name="checkboxStatus" value="2.2" checked="checked">&nbsp;点检提醒[使用]
                                    &nbsp;<input type="checkbox" name="checkboxStatus" value="2.3" checked="checked">&nbsp;超期[使用]
                                </td>
                            </tr>
                        </table>
                    </form>
                </div><!-- end of 主体 -->
                <div class="table table-condensed">     
                    <div style="width:500px;float: left;">
                        <div style="clear:both; height:30px;"><span style="float:left;width:80px; font-size:14px">工位配备</span><!--<a href="#" style="float:right">导出所有明细</a>--></div>
                        <table border="0" cellpadding="0" cellspacing="0" id="tableToolsManage" style="font-size:12px;width:500px;border:1px solid #4BACC6;">
                            <thead>
                                <tr bgcolor="#4BACC6" style="color:#FFFFFF">
                                    <th>#</th>
                                    <th>工艺代码</th>
                                    <th>工具名称</th>
                                    <th>工艺用途</th>
                                    <th>数量</th>
                                </tr>
                            </thead>
                            <tbody>
<!--                                <tr>
                                    <td>1</td>
                                    <td><a id="codes" href="#" onMouseOver="">codes</a></td>
                                    <td>name</td>
                                    <td>mader</td>
                                    <td><a id="rightDetails" href="#">5</a></td>
                                </tr>-->
                            </tbody>
                      </table>
                        <!--             分页             -->
                        <div class="pagination">
                            <ul>
                                <li><a href="#"><span>分页</span></a></li>
                            </ul>
                            <ul>
                                <li class="firstPage"><a href="#"><span>首页</span></a></li>
                                <li class="prePage"><a href="#"><span>&laquo;</span></a></li>
                                <li class="active curPage" page="1"><a href="#"><span id="curPage">1</span></a></li>
                                <li class="nextPage"><a href="#"><span>&raquo;</span></a></li>
                                <li class="lastPage" page="0"><a href="#"><span>尾页</span></a></li>
                            </ul>
                        </div>
                    </div>  
                    <div style="width:520px;margin-left: 20px; min-height:400px; border-left:1px solid #4A7EBB;float: left;">   
                        <div style="clear:both; height:30px;"><span style="float:left;width:80px; margin-left:20px; font-size:14px">配备明细</span></div>
                        <table border="0" cellpadding="0" cellspacing="0" id="tableToolsList" style="font-size:12px;margin-left: 20px;width:500px; border:1px solid #F79646;">
                            <thead>
                                <tr bgcolor="#F79646" style="color:#FFFFFF">
                                    <th>#</th>
                                    <th>工具编号</th>
                                    <th>操作凭证</th>
                                    <th>领用人</th>
                                    <th>工具状态</th>
                                    <th><a href="#" id="isCheckedAll"><span style="padding:3px; background-color:#FF0000; color:#FFFFFF">点检全部</span></a></th>
                                </tr>
                            </thead>
                            <tbody>
<!--                                <tr>
                                    <td>1</td>
                                    <td>toolscode</td>
                                    <td>name</td>
                                    <td>mader</td>
                                    <td>正常使用</td>
                                    <td><a id="isChecked" href="#">点检</a> &nbsp; <a id="delDetails" href="#"><img src="/bms/img/tools_del.gif" border="0"></a></td>
                                </tr>-->
                            </tbody>
                      </table>
                            <!--             分页             -->
                            <div class="pagination" id="page2" style="margin-left: 20px;">
                                <ul>
                                    <li><a href="#" id="page_attr" pagecode=""><span>分页</span></a></li>
                                </ul>
                                <ul>
                                    <li class="firstPage2"><a href="#"><span>首页</span></a></li>
                                    <li class="prePage2"><a href="#"><span>&laquo;</span></a></li>
                                    <li class="active curPage2" page="1"><a href="#"><span id="curPage2">1</span></a></li>
                                    <li class="nextPage2"><a href="#"><span>&raquo;</span></a></li>
                                    <li class="lastPage2" page="0"><a href="#"><span>尾页</span></a></li>
                                </ul>
                            </div>
                    </div>
                </div>
            </div><!-- end of 页体 -->
        </div><!-- offhead -->   
    </body>
</html>
