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
        <script type="text/javascript" src="/bms/js/tools/toolsAssign.js"></script>

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
                        <li class="active">工具调拨</li>            
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
                                    <input name="button" type="button" class="btn btn-success" id="btnAdd" value="新增"></td>
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
                                    <th>增加</th>
                                </tr>
                            </thead>
                            <tbody>
<!--                                <tr>
                                    <td>1</td>
                                    <td><a id="codes" href="#" onMouseOver="">codes</a></td>
                                    <td>name</td>
                                    <td>mader</td>
                                    <td><a id="rightDetails" href="#">5</a></td>
                                    <td><a id="addDetails" href="#"><span style="font-size: 28px; font-weight:bold; color: #51A351;">+</span></a></td>
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
                                    <th>交换 / 退库</th>
                                </tr>
                            </thead>
                            <tbody>
<!--                                <tr>
                                    <td>1</td>
                                    <td><a id="toolscode" href="#" onMouseOver="">toolscode</a></td>
                                    <td>name</td>
                                    <td>mader</td>
                                    <td><a id="statusInfo" href="#" title="使用周期：2013-05-01 至 2013-08-30">正常使用</a></td>
                                    <td><a id="changeDetails" href="#"><img src="/bms/img/tools_up.gif" border="0"></a> &nbsp; <a id="delDetails" href="#"><img src="/bms/img/tools_del.gif" border="0"></a></td>
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
        <!-- 弹层 start  -->
        <!-- new record -->
        <div class="modal" id="newModal" tabindex="-1" role="dialog" aria-hidden="true" style="width:800px; margin-left: -500px;display:none;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>新增配备工具</h3>
            </div>
            <div class="modal-body">
                <form id="" class="form-horizontal">                    
                    <div class="control-group" style="text-align: left">
                        <table width="700" border="0" cellspacing="0" cellpadding="3">
                            <tr>
                                <td>工艺代码</td>
                                <td>工具名称</td>
                                <td>工具种类</td>
                                <td>制造商</td>
                                <td>型号</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><input type="text" class="input-small" id="toolsCode" style="height: 20px"></td>
                                <td><input type="text" class="input-small" id="toolsName" style="height: 20px"></td>
                                <td><input type="text" class="input-small" id="toolsType" style="height: 20px"></td>
                                <td><input type="text" class="input-small" id="brandMaker" style="height: 20px"></td>
                                <td><input type="text" class="input-small" id="toolsModel" style="height: 20px"></td>
                                <td><input name="button2" type="button" class="btn btn-primary" id="newModalbtnQuery" value="查询"></td>
                            </tr>
                        </table>
                    </div>          
                    <div class="control-group" style="text-align: center">     
                        <div style="width:740px; height: 2px; text-align:left; margin-bottom:25px; font-weight:bold; border-bottom: 1px solid #1F497D;"></div>                     
                        <table width="740" border="1" cellpadding="3" cellspacing="0" bordercolor="#C8BABC" id="addSelectsList">
                            <thead>
                                <tr>
                                    <th width="60">选择</th>
                                    <th>工具编码</th>
                                    <th>工具种类</th>
                                    <th>工具名称</th>
                                    <th>制造商</th>
                                    <th>工具型号</th>
                                    <th>领用人</th>
                                    <th>操作凭证</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
                <button class="btn btn-danger" id="btnAddCheck">检验凭证</button>
                <button class="btn btn-success" id="btnAddMore">继续新增</button>
                <button class="btn btn-primary" id="btnAddConfirm">确认新增</button>
            </div>
        </div>

        <!-- edit record 工位配备--加号+ 增加 -->
        <div class="modal" id="newModal2" tabindex="-1" role="dialog" aria-hidden="true" style="width:800px; margin-left: -500px;display:none;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>新增工具明细</h3>
            </div>
            <div class="modal-body">
                <form id="" class="form-horizontal">    
                    <div class="control-group" style="text-align: center">     
                        <div style="width:740px; height: 2px; text-align:left; margin-bottom:20px;"></div>                     
                        <table width="740" border="1" cellpadding="3" cellspacing="0" bordercolor="#C8BABC" id="addSelectsList2">
                            <thead>
                                <tr>
                                    <th width="60">选择</th>
                                    <th>工具编码</th>
                                    <th>工具种类</th>
                                    <th>工具名称</th>
                                    <th>制造商</th>
                                    <th>工具型号</th>
                                    <th>领用人</th>
                                    <th>操作凭证</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
                <button class="btn btn-danger" id="btnAddCheck2">检验凭证</button>
                <button class="btn btn-success" id="btnAddMore2">继续新增</button>
                <button class="btn btn-primary" id="btnAddConfirm2">确认新增</button>
            </div>
        </div>

        <!-- view record 配备明细--工具编号--管理流水 -->
        <div class="modal" id="toolsNoListModal" tabindex="-1" role="dialog" aria-hidden="true" style="width:1000px; margin-left: -500px;display:none;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>工具管理记录</h3>
            </div>
            <div class="modal-body"> 
                <div class="control-group" style=" margin-left:20px; margin-right:20px; text-align: center">
                    <table width="920" border="1" cellpadding="2" cellspacing="0" bordercolor="#B8BABC" id="tableToolsNoList">
                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th>工具编码</th>
                                <th>管理动作</th>
                                <th>操作人</th>
                                <th>时间</th>
                                <th>状态</th>
                                <th>线别</th>
                                <th>工位</th>
                                <th>领用人</th>
                                <th>操作凭证</th>
                            </tr>
                        </thead>
                        <tbody>
<!--                            <tr>
                                <td>1</td>
                                <td>RLAG-0017</td>
                                <td>点检</td>
                                <td>吴龙山</td>
                                <td>2013-07-10 10:00</td>
                                <td>正常使用</td>
                                <td>I</td>
                                <td>T2</td>
                                <td>张三星</td>
                                <td>LY000779</td>
                            </tr>-->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
                <button class="btn btn-primary" id="btnEditConfirm">确认</button>
            </div>
        </div>

        <!-- view record 明细--工具编号(一把)--新增/编辑流水-->
        <div class="modal" id="toolsNoListEditModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>管理工具</h3>
            </div>
            <div class="modal-body">
                <form id="" class="form-horizontal">
                    <div class="control-group">
                    <!-- 状态:1闲置（正常|1.2点检提醒｜1.3点检警告） 2使用（正常|2.2点检提醒｜2.3超期使用） 3维修  4退库 5报废 -->
                        <label class="control-label" for="">状态：</label>
                        <div class="controls">
                            <select name="" id="toolsNoListEditModalStatus" class="input-medium">
                                <option value="1" selected>正常[闲置]</option>
                                <option value="1.2">点检提醒[闲置]</option>
                                <option value="1.3">点检警告[闲置]</option>
                                <option value="2">正常[使用]</option>
                                <option value="2.2">点检提醒[使用]</option>
                                <option value="2.3">超期[使用]</option>
                                <option value="3">维修</option>
                            </select><input type="hidden" class="input-medium" id="toolsNoListEditModalType" value="ToolsNoUpdate" style="height: 20px">
                        </div>
                    </div>
<!--                    <div class="control-group">
                        <label class="control-label" for="">供应商：</label>
                        <div class="controls">
                            <select name="" id="toolsNoListEditModalDistributor" class="input-medium">
                            </select>
                        </div>
                    </div>    
                    <div class="control-group">
                        <label class="control-label" for="">验收人：</label>
                        <div class="controls">                            
                            <input type="text" class="input-medium" id="toolsNoListEditModalOperater" style="height: 20px">
                        </div>
                    </div>    
                    <div class="control-group">
                        <label class="control-label" for="">成本：</label>
                        <div class="controls">
                            <input type="text" class="input-medium" id="toolsNoListEditModalCost" style="height: 20px">
                        </div>
                    </div>  -->
                    <div class="control-group">
                        <label class="control-label" for="">闲置点检周期(天)：</label>
                        <div class="controls">
                            <input type="text" class="input-medium" id="toolsNoListEditModalSpareCycles" style="height: 20px">
                        </div>
                    </div>  
                    <div class="control-group">
                        <label class="control-label" for="">使用点检周期(天)：</label>
                        <div class="controls">
                            <input type="text" class="input-medium" id="toolsNoListEditModalUseCycles" style="height: 20px">
                        </div>
                    </div>  
                    <div class="control-group">
                        <label class="control-label" for="">警告周期(天)：</label>
                        <div class="controls">
                            <input type="text" class="input-medium" id="toolsNoListEditModalWarnCycles" style="height: 20px">
                        </div>
                    </div>  
                    <div class="control-group">
                        <label class="control-label" for="">线别：</label>
                        <div class="controls">
                            <select name="" id="toolsNoListEditModalLine" class="input-medium">
                            </select>
                        </div>
                    </div>  
                    <div class="control-group">
                        <label class="control-label" for="">工段：</label>
                        <div class="controls">
                            <select name="" id="toolsNoListEditModalStage" class="input-medium">

                            </select>
                        </div>
                    </div>  
                    <div class="control-group">
                        <label class="control-label" for="">工位：</label>
                        <div class="controls">
                            <select name="" id="toolsNoListEditModalSeat" class="input-medium">
                            </select>
                        </div>
                    </div>  
                    <div class="control-group">
                        <label class="control-label" for="">领用人：</label>
                        <div class="controls">
                            <input type="text" class="input-small" id="toolsNoListEditModalRecipient" style="height: 20px"> &nbsp; <span id="toolsNoListEditModalCertificateSpan"></span><input type="hidden" id="toolsNoListEditModalCertificate" value="">
                        </div>
                    </div>  
                    <div class="control-group">
                        <div style="height: 30px; text-align:left; margin-left:20px; margin-right:20px; font-weight:bold; border-bottom: 1px solid #DDDDDD;">关键指标</div>
                    </div>  
                    <div class="control-group" style=" margin-left:20px; margin-right:20px; text-align: center">
                        <table width="500" border="0" cellpadding="2" cellspacing="0" id="toolsNoListEditModalIndexMeasure">
                            <thead>
                                <tr>
                                    <th width="60">#</th>
                                    <th>指标名</th>
                                    <th>指标值</th>
                                    <th>单位</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="control-group">
                        <div id="editModalAddOneIndexMeasure" style="height: 30px; text-align:left; margin-left:40px;width:40px; font-weight:bold; font-size:40px; color: #51A351;cursor: pointer;">+</div>
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
                <button class="btn btn-primary" id="btnToolsNoEditConfirm">确认编辑</button>
            </div>
        </div>
        <!-- edit record 明细 -- 交/替换 -- 列表 -->
        <div class="modal" id="changeDetailModal" tabindex="-1" role="dialog" aria-hidden="true" style="width:800px; margin-left: -500px;display:none;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>替换工具</h3>
            </div>
            <div class="modal-body">
                <form id="" class="form-horizontal">             
                    <div class="control-group" style="text-align: left">
                        <table width="700" border="0" cellspacing="0" cellpadding="3">
                            <tr>
                                <td>工艺代码</td>
                                <td>工具名称</td>
                                <td>工具种类</td>
                                <td>制造商</td>
                                <td>型号</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><input type="text" class="input-small" id="exchangeToolsCode" style="height: 20px"></td>
                                <td><input type="text" class="input-small" id="exchangeToolsName" style="height: 20px"></td>
                                <td><input type="text" class="input-small" id="exchangeToolsType" style="height: 20px"></td>
                                <td><input type="text" class="input-small" id="exchangeBrandMaker" style="height: 20px"></td>
                                <td><input type="text" class="input-small" id="exchangeToolsModel" style="height: 20px"></td>
                                <td><input name="button2" type="button" class="btn btn-primary" id="changeDetailModalBtnQuery" value="查询"></td>
                            </tr>
                        </table>
                    </div> 
                    <div class="control-group" style="text-align: center">       
                        <div style="width:740px; height: 2px; text-align:left; margin-bottom:25px; font-weight:bold; border-bottom: 1px solid #1F497D;"></div>                   
                        <table width="740" border="1" cellpadding="3" cellspacing="0" bordercolor="#C8BABC" id="tableExchangeList">
                            <thead>
                                <tr>
                                    <th width="60">选择</th>
                                    <th>工具编码</th>
                                    <th>工具种类</th>
                                    <th>工具名称</th>
                                    <th>制造商</th>
                                    <th>工具型号</th>
                                    <th>领用人</th>
                                    <th>操作凭证</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>                
                <button class="btn btn-danger" id="btnExchangeCheck">检验凭证</button>
                <button class="btn btn-primary" id="btnExchangeConfirm">确认交换</button>
            </div>
        </div>
        
        <!-- 弹层 end  -->
    </body>
</html>
