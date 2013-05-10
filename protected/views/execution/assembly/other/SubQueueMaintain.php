<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>分装队列维护</title>
        <!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet">
        <link href="/bms/css/common.css" rel="stylesheet">
        <link href="/bms/css/datetimepicker.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/other/subQueueMaintain.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.zh-CN.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/other/subQueueMaintain.js"></script>
        <script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
    </head>


    <body>
        <?php
            require_once(dirname(__FILE__)."/../../../common/head.php");
        ?>
        <div class="offhead">
           <?php
            require_once(dirname(__FILE__)."/../../../common/left/assembly_plan_left.php");
            ?>

            <!-- Main体 -->  
            
            <div id="bodyright" class="offset2">
            <div>
                <legend>分装列队维护
                </legend>
            </div>
                <div><!-- 主体 -->
                <form id="form" class="well form-search">
                    <table>
                        <tr>
                            <td class="alignRight">
                                <label>VIN&nbsp;&nbsp;</label>
                            </td>
                            <td>
                                <input id="vinText" type="text" placeholder="请扫描/输入VIN..." value="" class="input-medium">
                            </td>
                            <td>
                                -
                            </td>
                            <td>
                                <!-- <label>分装</label> -->
                                <select id="selectSub" class="select-medium">
                                    <option value="subInstrument">仪表分装</option>
                                    <option value="subEngine" selected>发动机分装</option>
                                    <option value="subFrontAxle">前桥分装</option>
                                    <option value="subRearAxle" disabled>后桥分装</option>
                                </select>
                            </td>
                            
                        </tr>
                        <tr>
                            <td>
                                <label>列队时间&nbsp;&nbsp;</label>
                            </td>
                            <td>
                                <!-- <input type="text" class="input-medium" placeholder="请输入开始时间..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:mm:ss'});"/> -->
                                <input type="text" class="input-medium datetimepicker" placeholder="请输入开始时间..." id="startTime"/>
                            </td>
                            <td>
                                -
                            </td>
                            <td>
                                <!-- <input type="text" class="input-medium" placeholder="请输入结束时间..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:mm:ss'});"/> -->
                                <input type="text" class="input-medium datetimepicker" placeholder="请输入结束时间..." id="endTime"/>
                            </td>
                            <td>
                                <input type="button" class="btn btn-primary" id="btnQuery" value="查询" style="margin-left:2px;"></input>   
                            </td>
                        </tr>
                    </table>
                </form>
                
               <table id="tableList" class="table table-condensed">
                    <thead>
                        <tr class="active">
                            <th class="" style="width:60px">整车编号</th>
                            <th class="" style="width:120px">列队时间</th>
                            <th class="" style="width:150px">VIN号</th>
                            <th class="" style="width:30px">车系</th>
                            <th class="" style="width:230px">车型/配置</th>
                            <th class="" style="width:50px">耐寒性</th>
                            <th class="" style="width:50px">颜色</th>
                            <!-- <th class="" style="width:50px">年份</th>
                            <th class="" style="width:100px">特殊单号</th> -->
                            <th class="" style="">备注</th>
                            <!-- <th> 操作</th> -->
                        </tr>
                    </thead>
                    <tbody>
               
                    </tbody>
                </table>
            </div><!-- end of 主体
        </div><!-- end of 页体 -->
        </div><!-- offhead -->

<!-- edit record -->
<div class="modal" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>编辑</h3>
    </div>
    <div class="modal-body">
        <form id="editForm" class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="queueTime">排队时间</label>
                <div class="controls">
                    <!-- <input id="queueTime" type="text" class="input-large" placeholder="请输入排队时间..." onClick="WdatePicker({el:'queueTime',dateFmt:'yyyy-MM-dd HH:mm:ss'});"/> -->
                    <input id="queueTime" type="text" class="input-large datetimepicker" placeholder="请输入排队时间..."/>
                </div>
            </div>
           
            <div class="control-group">
                <label class="control-label" for="editStatus">打印状态</label>
                <div class="controls">
                    <select id="editStatus" name="" class="input-large">
                        <option value="">请选择</option>
                        <option value="0">未打印</option>
                        <option value="1"> 已打印  </option>
                        <option value="2">不可打印</option>
                    </select> 
                </div>
            </div>
              
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <button class="btn btn-primary" id="btnEditConfirm">确认编辑</button>
    </div>
</div>
</body>
</html>
