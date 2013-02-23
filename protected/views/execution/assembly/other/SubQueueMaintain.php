<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>分装队列维护</title>
        <!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet">
        <link href="/bms/css/common.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/other/OrderMaintain.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
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
            require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
            ?>

            <!-- Main体 -->  
            
            <div id="bodyright" class="offset2">
                <div ><ul class="breadcrumb"><!-- 面包屑 -->
                        <li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
                        <li><a href="/bms/execution/home">总装</a><span class="divider">&gt;</span></li>
                        <li><a href="#">维护与帮助</a><span class="divider">&gt;</span></li>
                        <li class="active">分装队列维护</li>                
                </ul></div><!-- end 面包屑 -->
                <div><!-- 主体 -->
                <form id="form" class="well form-search">
                    <table>
                        <tr>
                            <td>
                                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VIN</label><input id="vinText" type="text" placeholder="请扫描/输入VIN..." value="">
                            </td>
                            <td>
                                <label>&nbsp;&nbsp;&nbsp;&nbsp;分装</label>
                                <select id="selectSub" class="input">
                                    <option value="subInstrument">仪表分装</option>
                                    <option value="subEngine">发动机分装</option>
                                    <option value="subFrontAxle">前桥分装</option>
                                    <option value="subEndAxle">后桥分装</option>
                                </select>
                            </td>
                            
                        </tr>
                        <tr>
                            <td>
                                 <label>列队时间</label>
                                <input type="text" class="span3" placeholder="请输入开始时间..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:00:00'});"/>
                            </td>
                            <td>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</span>
                                <input type="text" class="span3" placeholder="请输入结束时间..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:00:00'});"/>
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
                            <th class="">整车编号</th>
                            <th class="">VIN号</th>
                            <th class="">车系</th>
                            <th class="">车型/配置</th>
                            <th class="">耐寒性</th>
                            <th class="">颜色</th>
                            <th class="">年份</th>
                            <th class="">特殊单号</th>
                            <th class="">备注</th>
                            <!-- <th> 操作</th> -->
                        </tr>
                    </thead>
                    <tbody>
                         <!-- <tr>
                                    <td>C12356</td>
                                    <td>F0</td>
                                    <td>LGXC16DG4C1234564</td>
                                    <td>QCJ7100L（1.0排量尊舒适型）</td>
                                    <td>德兰黑</td>
                                    <td>2012</td>
                                    <td>1.0舒适标准</td>
                                    <td>-</td>
                                    <td>北京，耐寒型，QA-5</td>  
                                    <td><button class="btn-link">编辑</button></td>                                  
                                </tr>      -->                     
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
                    <input id="queueTime" type="text" class="input-large" placeholder="请输入排队时间..." onClick="WdatePicker({el:'queueTime',dateFmt:'yyyy-MM-dd HH:00:00'});"/>
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
