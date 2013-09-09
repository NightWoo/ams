<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>总装长沙AMS</title>
        <!--css styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet">
        <link href="/bms/css/generalInformation/basicData/faultmaintain.css" rel="stylesheet">
        <link href="/bms/css/common.css" rel="stylesheet">

        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>
        <script type="text/javascript" src="/bms/js/tools/toolsHomeDistributor.js"></script>        
        
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
                        <li><a href="#">基础数据库</a><span class="divider">&gt;</span></li>
                        <li class="active">供应商</li>            
                    </ul>
                </div>
                <div><!-- 主体 -->
                    <div id="divTabs">
                        <ul id="tabs" class="nav nav-pills">
                            <li class=""><a href="/bms/toolsManagement/index">制造商</a></li>
                            <li class="active"><a href="/bms/toolsManagement/homedistributor">供应商</a></li>
                            <li class=""><a href="/bms/toolsManagement/homeParameter">参数单位</a></li>
                            <li class=""><a href="/bms/toolsManagement/hometoolsuser">领用单位</a></li>
                        </ul>
                    </div>
                    <form id="form" class="well form-search">
                        <table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                                <td width="100"></td>
                                <td>&nbsp;<input type="checkbox" id="checkboxStopUse">&nbsp;包括停用</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td align="center">供应商名称</td>
                                <td>
                                    <input type="text" class="input-large" id="distributor" style="height: 20px" value="" style="height: 20px" data-provide="typeahead" data-items="4" data-source="<?php echo $promptData;?>">
                                </td>
                                <td>
                                    <input id="btnQuery" type="button" class="btn btn-primary" value="查询"></input>&nbsp;
                                    <input id="btnAdd" type="button" class="btn btn-success" value="新增"></input>  
                                </td>
                            </tr>
                        </table>
                    </form>

                    <table id="tableToolsList" class="table table-condensed" style="font-size:12px;margin-left: 80px;width:750px">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>供应商(全称)</th>
                                <th>制造商(简称)</th>
                                <th>联系方式</th>
                                <th>&nbsp;&nbsp;操作</th>
                            </tr>
                        </thead>
                        <tbody>

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
                    
                </div><!-- end of 主体 -->
            </div><!-- end of 页体 -->
        </div><!-- offhead -->
        <!-- new record -->
        <div class="modal" id="newModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>新增供应商</h3>
            </div>
            <div class="modal-body">
                <form id="" class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label" for="">供应商(全称)</label>
                        <div class="controls">
                            <input type="text" class="input-medium" id="textAddDistributor" style="height: 20px">
                        </div>
                    </div> 
                    <div class="control-group">
                        <label class="control-label" for="">供应商(简称)</label>
                        <div class="controls">
                            <input type="text" class="input-medium" id="textAddDisplayName" style="height: 20px">
                        </div>
                    </div> 
                    <div class="control-group">
                        <label class="control-label" for="">联系方式</label>
                        <div class="controls">
                            <input type="text" class="input-medium" id="textAddContact" style="height: 20px">
                        </div>
                    </div>         
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
                <button class="btn btn-success" id="btnAddMore">继续新增</button>
                <button class="btn btn-primary" id="btnAddConfirm">确认新增</button>
            </div>
        </div>

        <!-- edit record -->
        <div class="modal" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>编辑供应商</h3>
            </div>
            <div class="modal-body">
                <form id="" class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label" for="">供应商(全称)</label>
                        <div class="controls">
                            <input type="text" class="input-medium" id="textEditDistributor" style="height: 20px">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="">供应商(简称)</label>
                        <div class="controls">
                            <input type="text" class="input-medium" id="textEditDisplayName" style="height: 20px">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="">联系方式</label>
                        <div class="controls">
                            <input type="text" class="input-medium" id="textEditContact" style="height: 20px">
                        </div>
                    </div> 
                    <div class="control-group">
                        <label class="control-label" for="">启用</label>
                        <div class="controls">
                            <input type="checkbox" id="checkboxEditStartUsing" style="height: 20px">
                        </div>
                    </div>          
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
                <button class="btn btn-primary" id="btnEditConfirm">确认编辑</button>
            </div>
        </div><!-- offhead -->
    </body>
</html>
