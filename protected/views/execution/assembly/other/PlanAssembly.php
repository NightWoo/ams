<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>总装日执行计划</title>
        <!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/other/planAssembly.js"></script>
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
                        <li class="active">日执行计划</li>                
                </ul></div><!-- end 面包屑 -->
                <div><!-- 主体 -->
                <form id="form" class="well form-search">
                    <table>
                        <tr>
                            <td>日期</td>
                            <td>车系</td>
                            <td>线别</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="input-medium" placeholder="执行日期..." id="planDate" onClick="WdatePicker({el:'planDate',dateFmt:'yyyy-MM-dd'});"/>
                            </td>
                            <td>
                                <select name="" id="carSeries" class="input-small">
                                    <option value="F0" selected>F0</option>
                                    <option value="M6" disabled>M6</option>
                                </select>
                            </td>
                            <td>
                                <select name="" id="assemblyLine" class="input-small">
                                    <option value="A" selected>A线</option>
                                </select>
                            </td>
                            <td>
                                <input type="button" class="btn btn-primary" id="btnQuery" value="查询" style="margin-left:2px;"></input>   
                                <input id="btnAdd" type="button" class="btn btn-success" value="新增"></input>
                            </td>
                        </tr>
                    </table>
                </form>
                
                <table id="tablePlanAssembly" class="table table-condensed" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>优先序</th>
							<!-- <th>ID</th> -->
							<th>批次号</th>
                            <th>数量</th>
                            <th>完成</th>
                            <th>配置</th>
                            <th>车身</th>
                            <th>颜色</th>
                            <th>耐寒性</th>
                            <th>年份</th>
                            <th>订单类型</th>
                            <th>备注</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
        </div><!-- offhead -->
<!-- new record -->
<div class="modal" id="newModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>新增</h3>
    </div>
    <div class="modal-body">
        <form id="  " class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;执行日期</label>
                <div class="controls">
                    <input type="text" class="input-medium" placeholder="选择执行日期..." id="newPlanDate" onClick="WdatePicker({el:'newPlanDate',dateFmt:'yyyy-MM-dd'});"/>
                </div>
            </div>
			<!-- <div class="control-group">
                <label class="control-label" for="">*&nbsp;计划ID</label>
                <div class="controls">
                    <input type="text" class="input-small" placeholder="请输入计划ID..." id="newPlanId"/>
                </div>
            </div>
			<div class="control-group">
                <label class="control-label" for="">*&nbsp;批次号</label>
                <div class="controls">
                    <input type="text" class="input-small" placeholder="请输入批次号..." id="newBatchNumber"/>
                </div>
            </div> -->
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;数量</label>
                <div class="controls">
                    <input type="text" class="input-small" placeholder="请输入数量..." id="newPlanAmount"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;线别</label>
                <div class="controls">
                    <select name="" id="newLine" class="input-small">
                        <option value="A" selected>A线</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="newCarSeries">*&nbsp;车系</label>
                <div class="controls">
                    <select id="newSeries" class="input-small">
                        <option value="F0" selected>F0</option>
                        <option value="M6" disabled>M6</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;车型</label>
                <div class="controls">
                    <!--<input type="text" class="input-medium"  id="newCarType"/>-->
                    <select name="" id="newCarType" class="input-large">
                        <option value="" selected>请选择</option>
                        <option value="QCJ7100L(1.0排量实用型)">QCJ7100L(1.0排量实用型)</option>
                        <option value="QCJ7100L(1.0排量舒适型)">QCJ7100L(1.0排量舒适型)</option>
                        <option value="QCJ7100L(1.0排量尊贵型)">QCJ7100L(1.0排量尊贵型)</option>
                        <option value="QCJ7100L5(1.0排量实用型北京)">QCJ7100L5(1.0排量实用型北京)</option>
                        <option value="QCJ7100L5(1.0排量舒适型北京)">QCJ7100L5(1.0排量舒适型北京)</option>
						<option value="BYD7100L3(1.0排量实用型)">BYD7100L3(1.0排量实用型)</option>
                        <option value="BYD7100L3(1.0排量舒适型)">BYD7100L3(1.0排量舒适型)</option>
						<option value="QCJ7100L(1.0排量实用型（出口）)">QCJ7100L(1.0排量实用型（出口）)</option>
                        <option value="QCJ7100L(1.0排量舒适型（出口）)">QCJ7100L(1.0排量舒适型（出口）)</option>
                        <option value="QCJ7100L(1.0排量尊贵型（出口）)">QCJ7100L(1.0排量尊贵型（出口）)</option>
                    </select> 
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="">*&nbsp;配置</label>
                <div class="controls">
                    <!--<input type="text" class="input-medium"  id="newConfig"/>-->
                    <select name="" id="newConfig" class="input-large">
                        <option value="" selected>请选择配置</option>
                        <option value="1.0L实用/QA-4">1.0L实用/QA-4</option>
                        <option value="1.0L实用/QA-4/北京">1.0L实用/QA-4/北京</option>
                        <option value="1.0L实用/QA-4/助力">1.0L实用/QA-4/助力</option>
                        <option value="1.0L实用/QA-5">1.0L实用/QA-5</option>
                        <option value="1.0L实用/QA-5/北京">1.0L实用/QA-5/北京</option>
                        <option value="1.0L实用/QA-5/助力">1.0L实用/QA-5/助力</option>
                        <option value="1.0L舒适/QA-4">1.0L舒适/QA-4</option>
                        <option value="1.0L舒适/QA-4/北京">1.0L舒适/QA-4/北京</option>
                        <option value="1.0L舒适/QA-5">1.0L舒适/QA-5</option>
                        <option value="1.0L舒适/QA-5/北京">1.0L舒适/QA-5/北京</option>
                        <option value="1.0L尊贵/QA-4">1.0L尊贵/QA-4</option>
                        <option value="1.0L尊贵/QA-5">1.0L尊贵/QA-5</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;车身</label>
                <div class="controls">
                    <!--<input type="text" class="input-medium"  id="newCarType"/>-->
                    <select name="" id="newCarBody" class="input-large">
                        <option value="" selected>请选择</option>
                        <option value="QCJ7100L(1.0排量实用型)">QCJ7100L(1.0排量实用型)</option>
                        <option value="QCJ7100L(1.0排量舒适型)">QCJ7100L(1.0排量舒适型)</option>
                        <option value="QCJ7100L(1.0排量尊贵型)">QCJ7100L(1.0排量尊贵型)</option>
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;颜色</label>
                <div class="controls">
                    <!--<input type="text" class="input-medium"  id="newColor"/>-->
                    <select name="" id="newColor" class="input-small">
                        <option value="" selected>请选择</option>
                        <option value="冰岛蓝">冰岛蓝</option>
                        <option value="德兰黑">德兰黑</option>
						<option value="天山白">天山白</option>
                        <option value="法兰红">法兰红</option>
                        <option value="巴西黄">巴西黄</option>
                        <option value="麦加金">麦加金</option>
                        <option value="雅典银">雅典银</option>  
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">耐寒型</label>
                <div class="controls">
                    <input type="checkbox" id="checkboxNewColdResistant">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;年份</label>
                <div class="controls">
                    <select name="" id="newCarYear" class="input-small">
                        <option value="2012" selected>2012</option>
                        <option value="2013">2013</option>
                        <option value="2014">2014</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;订单类型</label>
                <div class="controls">
                    <select name="" id="newOrderType" class="input-medium">
                        <option value="国内订单" selected>国内订单</option>
                        <option value="出口订单">出口订单</option>
                        <option value="其他">其他</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">特殊单号</label>
                <div class="controls">
                    <input type="text" class="input-medium" placeholder="请输入特殊单号..." id="newSpecialOrder"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">备注</label>
                <div class="controls">
                    <textarea class="input-xlarge" id="newRemark" rows="2"></textarea>
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
        <h3>编辑</h3>
    </div>
    <div class="modal-body">
        <form id="  " class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;执行日期</label>
                <div class="controls">
                    <input type="text" class="input-medium" placeholder="选择执行日期..." id="editPlanDate" onClick="WdatePicker({el:'editPlanDate',dateFmt:'yyyy-MM-dd'});"/>
                </div>
            </div>
			<!-- <div class="control-group">
                <label class="control-label" for="">*&nbsp;计划ID</label>
                <div class="controls">
                    <input type="text" class="input-small" placeholder="请输入计划ID..." id="editPlanId"/>
                </div>
            </div>
			<div class="control-group">
                <label class="control-label" for="">*&nbsp;批次号</label>
                <div class="controls">
                    <input type="text" class="input-small" placeholder="请输入批次号..." id="editBatchNumber"/>
                </div>
            </div> -->
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;数量</label>
                <div class="controls">
                    <input type="text" class="input-small" placeholder="请输入数量..." id="editPlanAmount"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;线别</label>
                <div class="controls">
                    <select name="" id="editLine" class="input-small">
                        <option value="A" selected>A线</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editSeries">*&nbsp;车系</label>
                <div class="controls">
                    <select id="editSeries" class="input-small">
                        <option value="F0" selected>F0</option>
                        <option value="M6" disabled>M6</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;车型</label>
                <div class="controls">
                    <!--<input type="text" class="input-medium" id="editCarType"/>-->
                    <select name="" id="editCarType" class="input-large">
                        <option value="" selected>请选择</option>
                        <option value="QCJ7100L(1.0排量实用型)">QCJ7100L(1.0排量实用型)</option>
                        <option value="QCJ7100L(1.0排量舒适型)">QCJ7100L(1.0排量舒适型)</option>
                        <option value="QCJ7100L(1.0排量尊贵型)">QCJ7100L(1.0排量尊贵型)</option>
                        <option value="QCJ7100L5(1.0排量实用型北京)">QCJ7100L5(1.0排量实用型北京)</option>
                        <option value="QCJ7100L5(1.0排量舒适型北京)">QCJ7100L5(1.0排量舒适型北京)</option>
						<option value="BYD7100L3(1.0排量实用型)">BYD7100L3(1.0排量实用型)</option>
                        <option value="BYD7100L3(1.0排量舒适型)">BYD7100L3(1.0排量舒适型)</option>
						<option value="QCJ7100L(1.0排量实用型（出口）)">QCJ7100L(1.0排量实用型（出口）)</option>
                        <option value="QCJ7100L(1.0排量舒适型（出口）)">QCJ7100L(1.0排量舒适型（出口）)</option>
                        <option value="QCJ7100L(1.0排量尊贵型（出口）)">QCJ7100L(1.0排量尊贵型（出口）)</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;配置</label>
                <div class="controls">
                    <!--<input type="text" class="input-medium" id="editConfig"/>-->
                    <select name="" id="editConfig" class="input-large">
                        <option value="" selected>请选择配置</option>
                        <option value="1.0L实用/QA-4">1.0L实用/QA-4</option>
                        <option value="1.0L实用/QA-4/北京">1.0L实用/QA-4/北京</option>
                        <option value="1.0L实用/QA-4/助力">1.0L实用/QA-4/助力</option>
                        <option value="1.0L实用/QA-5">1.0L实用/QA-5</option>
                        <option value="1.0L实用/QA-5/北京">1.0L实用/QA-5/北京</option>
                        <option value="1.0L实用/QA-5/助力">1.0L实用/QA-5/助力</option>
                        <option value="1.0L舒适/QA-4">1.0L舒适/QA-4</option>
                        <option value="1.0L舒适/QA-4/北京">1.0L舒适/QA-4/北京</option>
                        <option value="1.0L舒适/QA-5">1.0L舒适/QA-5</option>
                        <option value="1.0L舒适/QA-5/北京">1.0L舒适/QA-5/北京</option>
                        <option value="1.0L尊贵/QA-4">1.0L尊贵/QA-4</option>
                        <option value="1.0L尊贵/QA-5">1.0L尊贵/QA-5</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;车身</label>
                <div class="controls">
                    <!--<input type="text" class="input-medium"  id="newCarType"/>-->
                    <select name="" id="editCarBody" class="input-large">
                        <option value="" selected>请选择</option>
                        <option value="QCJ7100L(1.0排量实用型)">QCJ7100L(1.0排量实用型)</option>
                        <option value="QCJ7100L(1.0排量舒适型)">QCJ7100L(1.0排量舒适型)</option>
                        <option value="QCJ7100L(1.0排量尊贵型)">QCJ7100L(1.0排量尊贵型)</option>
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;颜色</label>
                <div class="controls">
                    <!-- <input type="text" class="input-medium" id="editColor"/> -->
                    <select name="" id="editColor" class="input-small">
                        <option value="" selected>请选择</option>
                        <option value="冰岛蓝">冰岛蓝</option>
                        <option value="德兰黑">德兰黑</option>
						<option value="天山白">天山白</option>
                        <option value="法兰红">法兰红</option>
                        <option value="巴西黄">巴西黄</option>
                        <option value="麦加金">麦加金</option>
                        <option value="雅典银">雅典银</option> 
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">耐寒型</label>
                <div class="controls">
                    <input type="checkbox" id="checkboxEditColdResistant">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;年份</label>
                <div class="controls">
                    <select name="" id="editCarYear" class="input-small">
                        <option value="2012" selected>2012</option>
                        <option value="2013">2013</option>
                        <option value="2014">2014</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;订单类型</label>
                <div class="controls">
                    <select name="" id="editOrderType" class="input-medium">
                        <option value="国内订单" selected>国内订单</option>
                        <option value="出口订单">出口订单</option>
                        <option value="其他">其他</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">特殊单号</label>
                <div class="controls">
                    <input type="text" class="input-medium" placeholder="请输入特殊单号..." id="editSpecialOrder"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">备注</label>
                <div class="controls">
                    <textarea class="input-xlarge" rows="2" id="editRemark"></textarea>
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
