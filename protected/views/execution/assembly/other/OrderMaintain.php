<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>订单维护</title>
        <!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet">
        <link href="/bms/css/common.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/other/OrderMaintain.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/other/orderMaintain.js"></script>
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
                        <li class="active">订单维护</li>                
                </ul></div><!-- end 面包屑 -->
                <div><!-- 主体 -->
                <form id="form" class="well form-search">
                    <table>
                        <!-- <tr>
                            <td>日期</td>
                            <td>车系</td>
                            <<td>线别</td>
                            <td></td>
                        </tr> -->
                        <tr>
                            <td>
                                <label>备车日期&nbsp;</label><input id="standbyDate"  type="text" class="input-medium" placeholder="执行日期..."onClick="WdatePicker({el:'standbyDate',dateFmt:'yyyy-MM-dd'});"/>
                            </td>
                            <!-- <td>
                                <select name="" id="carSeries" class="input-small">
                                    <option value="F0">F0</option>
                                    <option value="M6" disabled>M6</option>
                                </select>
                            </td> -->
                            <!-- <td>
                                <select name="" id="assemblyLine" class="input-small">
                                    <option value="A">A线</option>
                                </select>
                            </td> -->
                            <td>
                                <input type="button" class="btn btn-primary" id="btnQuery" value="查询" style="margin-left:2px;"></input>   
                                <input id="btnAdd" type="button" class="btn btn-success" value="新增"></input>
                            </td>
                        </tr>
                    </table>
                </form>
                
                <table id="tableResult" class="table table-condensed" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th id="thReorder">调整</th>
                            <th id="thPriority">优先</th>
                            <th id="thStatus">状态</th>
                            <th id="thLane">车道</th>
                            <th id="thCarrier">承运商 </th>
                            <th id="thOrderNumber">订单号</th>
                            <th id="thDistributor">经销商</th>
                            <th id="thAmount">数量</th>
                            <th id="thSeries">车系</th>
                            <th id="thColor">颜色</th>
                            <th id="thConfig">配置</th>
                            <th id="thColdResistant">耐寒性</th>
                            <th id="thCarType">车型</th>
                            <!-- <th id="thCarYear">年份</th> -->
                            <th id="thOrderType">订单类型</th>
                            <th id="thCity">城市</th>
                            <th id="thRemark">备注</th>
                            <th id="thEdit"></th>
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
        <form id="newForm" class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;备车日期</label>
                <div class="controls">
                    <input id="newStandbyDate"  type="text" class="input-medium" placeholder="选择备车日期..."onClick="WdatePicker({el:'newStandbyDate',dateFmt:'yyyy-MM-dd'});"/>
                </div>
            </div>
			<div class="control-group">
                <label class="control-label" for="">*&nbsp;激活</label>
                <div class="controls">
                    <input id="newStatus" type="checkbox">
                </div>
            </div>
			<div class="control-group">
                <label class="control-label" for="">*&nbsp;车道</label>
                <div class="controls">
                    <select id="newLane"  name=""class="input-small">
                        <option value="" selected>未选择</option>
                        <?php 
                            for($i=1;$i<51;$i++){
                                $num = sprintf("%02d", $i);
                                $ret = "<option value=". $num .">$num</option>";
                                echo $ret;
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;承运商</label>
                <div class="controls">
                    <input id="newCarrier" type="text" class="input-medium" placeholder="输入承运商..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;城市</label>
                <div class="controls">
                    <input id="newCity" type="text" class="input-medium" placeholder="输入城市..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">经销商</label>
                <div class="controls">
                    <input type="text" id="newDistributorName" class="input-medium" placeholder="请输入供应商">
                    <span id="newDistributorCode" class="help-inline"></span>
                    <input type="hidden" id="newDistributorId" value="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;订单号</label>
                <div class="controls">
                    <input id="newOrderNumber" type="text" class="input-medium" placeholder="输入订单号..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;数量</label>
                <div class="controls">
                    <input type="text" class="input-small" placeholder="请输入数量..." id="newAmount"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;车系</label>
                <div class="controls">
                    <select id="newSeries" class="input-small">
                        <option value="" selected>请选择</option>
                        <option value="F0">F0</option>
                        <option value="M6">M6</option>
                        <option value="6B">思锐</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;车型</label>
                <div class="controls">
                    <!--<input type="text" class="input-medium"  id="newCarType"/>-->
                    <select id="newCarType" name="" class="input-large">
                        <option value="">请选择</option>
                        <!-- <option value="QCJ7100L(1.0排量实用型)">QCJ7100L(1.0排量实用型)</option>
                        <option value="QCJ7100L(1.0排量舒适型)">QCJ7100L(1.0排量舒适型)</option>
                        <option value="QCJ7100L(1.0排量尊贵型)">QCJ7100L(1.0排量尊贵型)</option>
                        <option value="QCJ7100L5(1.0排量实用型北京)">QCJ7100L5(1.0排量实用型北京)</option>
                        <option value="QCJ7100L5(1.0排量舒适型北京)">QCJ7100L5(1.0排量舒适型北京)</option>
						<option value="BYD7100L3(1.0排量实用型)">BYD7100L3(1.0排量实用型)</option>
                        <option value="BYD7100L3(1.0排量舒适型)">BYD7100L3(1.0排量舒适型)</option>
						<option value="QCJ7100L(1.0排量实用型（出口）)">QCJ7100L(1.0排量实用型（出口）)</option>
                        <option value="QCJ7100L(1.0排量舒适型（出口）)">QCJ7100L(1.0排量舒适型（出口）)</option>
                        <option value="QCJ7100L(1.0排量尊贵型（出口）)">QCJ7100L(1.0排量尊贵型（出口）)</option> -->
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;配置</label>
                <div class="controls">
                    <!--<input type="text" class="input-medium"  id="newConfig"/>-->
                    <select id="newOrderConfig" name=""class="input-medium">
                        <option value="">请选择</option>
                        <!-- <option value="1">F0实用</option>
                        <option value="2">F0舒适</option>
                        <option value="3">F0尊贵</option>
                        <option value="4">F0实用</option>
                        <option value="5">F0舒适</option>
                        <option value="6">F0实用助力</option> -->
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;颜色</label>
                <div class="controls">
                    <!--<input type="text" class="input-medium"  id="newColor"/>-->
                    <select id="newColor" name=""class="input-small">
                        <option value="">请选择</option>
                        <!-- <option value="冰岛蓝">冰岛蓝</option>
                        <option value="德兰黑">德兰黑</option>
						<option value="天山白">天山白</option>
                        <option value="法兰红">法兰红</option>
                        <option value="伊甸紫">巴西黄</option>
                        <option value="麦加金">麦加金</option>
                        <option value="雅典银">雅典银</option>   -->
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">耐寒型</label>
                <div class="controls">
                    <input id="newColdResistant" type="checkbox">
                </div>
            </div>
            <!-- <div class="control-group">
                <label class="control-label" for="">*&nbsp;年份</label>
                <div class="controls">
                    <select id="newCarYear" name=""class="input-small">
                        <option value="2012">2012</option>
                        <option value="2013">2013</option>
                        <option value="2014">2014</option>
                    </select>
                </div>
            </div> -->
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;订单类型</label>
                <div class="controls">
                    <select name="" id="newOrderType" class="input-medium">
                        <option value="普通订单">普通订单</option>
                        <option value="三方订单">三方订单</option>
                        <option value="其他">其他</option>
                    </select>
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
        <form id="editForm" class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="editStandbyDate">*&nbsp;备车日期</label>
                <div class="controls">
                    <input id="editStandbyDate"  type="text" class="input-medium" placeholder="选择备车日期..."onClick="WdatePicker({el:'editStandbyDate',dateFmt:'yyyy-MM-dd'});"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editStatus">*&nbsp;激活</label>
                <div class="controls">
                    <input id="editStatus" type="checkbox">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editLane">*&nbsp;车道</label>
                <div class="controls">
                    <select id="editLane"  name=""class="input-small">
                        <option value="" selected>未选择</option>
                        <?php 
                            for($i=1;$i<51;$i++){
                                $num = sprintf("%02d", $i);
                                $ret = "<option value=". $num .">$num</option>";
                                echo $ret;
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editCarrier">*&nbsp;承运商</label>
                <div class="controls">
                    <input id="editCarrier" type="text" class="input-medium" placeholder="输入承运商..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="ditCity">*&nbsp;城市</label>
                <div class="controls">
                    <input id="editCity" type="text" class="input-medium" placeholder="输入城市..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">经销商</label>
                <div class="controls">
                    <input type="text" id="editDistributorName" class="input-medium" placeholder="请输入供应商">
                    <span id="editDistributorCode" class="help-inline"></span>
                    <input type="hidden" id="editDistributorId" value="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editOrderNumber">*&nbsp;订单号</label>
                <div class="controls">
                    <input id="editOrderNumber" type="text" class="input-medium" placeholder="输入订单号..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editAmount">*&nbsp;数量</label>
                <div class="controls">
                    <input id="editAmount" type="text" class="input-small" placeholder="请输入数量..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editSeries">*&nbsp;车系</label>
                <div class="controls">
                    <select id="editSeries" class="input-small">
                        <option value="" selected>请选择</option>
                        <option value="F0">F0</option>
                        <option value="M6">M6</option>
                        <option value="6B">思锐</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editCarType">*&nbsp;车型</label>
                <div class="controls">
                    <select id="editCarType" name="" class="input-large">
                        <option value="">请选择</option>
                        <!-- <option value="QCJ7100L(1.0排量实用型)">QCJ7100L(1.0排量实用型)</option>
                        <option value="QCJ7100L(1.0排量舒适型)">QCJ7100L(1.0排量舒适型)</option>
                        <option value="QCJ7100L(1.0排量尊贵型)">QCJ7100L(1.0排量尊贵型)</option>
                        <option value="QCJ7100L5(1.0排量实用型北京)">QCJ7100L5(1.0排量实用型北京)</option>
                        <option value="QCJ7100L5(1.0排量舒适型北京)">QCJ7100L5(1.0排量舒适型北京)</option>
                        <option value="BYD7100L3(1.0排量实用型)">BYD7100L3(1.0排量实用型)</option>
                        <option value="BYD7100L3(1.0排量舒适型)">BYD7100L3(1.0排量舒适型)</option>
                        <option value="QCJ7100L(1.0排量实用型（出口）)">QCJ7100L(1.0排量实用型（出口）)</option>
                        <option value="QCJ7100L(1.0排量舒适型（出口）)">QCJ7100L(1.0排量舒适型（出口）)</option>
                        <option value="QCJ7100L(1.0排量尊贵型（出口）)">QCJ7100L(1.0排量尊贵型（出口）)</option> -->
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editConfig">*&nbsp;配置</label>
                <div class="controls">
                    <select id="editOrderConfig" name=""class="input-medium">
                        <option value="">请选择</option>
                        <!-- <option value="1">F0实用</option>
                        <option value="2">F0舒适</option>
                        <option value="3">F0尊贵</option>
                        <option value="4">F0实用北京</option>
                        <option value="5">F0舒适北京</option>
                        <option value="6">F0实用助力</option> -->
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="ditColor">*&nbsp;颜色</label>
                <div class="controls">
                    <select id="editColor" name=""class="input-small">
                        <option value="">请选择</option>
                        <!-- <option value="冰岛蓝">冰岛蓝</option>
                        <option value="德兰黑">德兰黑</option>
                        <option value="天山白">天山白</option>
                        <option value="法兰红">法兰红</option>
                        <option value="伊甸紫">巴西黄</option>
                        <option value="麦加金">麦加金</option>
                        <option value="雅典银">雅典银</option> -->  
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">耐寒型</label>
                <div class="controls">
                    <input id="editColdResistant" type="checkbox">
                </div>
            </div>
            <!-- <div class="control-group">
                <label class="control-label" for="editCarYear">*&nbsp;年份</label>
                <div class="controls">
                    <select id="editCarYear" name=""class="input-small">
                        <option value="2012">2012</option>
                        <option value="2013">2013</option>
                        <option value="2014">2014</option>
                    </select>
                </div>
            </div> -->
            <div class="control-group">
                <label class="control-label" for="editOrderType">*&nbsp;订单类型</label>
                <div class="controls">
                    <select id="editOrderType" class="input-medium">
                        <option value="普通订单">普通订单</option>
                        <option value="三方订单">三方订单</option>
                        <option value="其他">其他</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editRemark">备注</label>
                <div class="controls">
                    <textarea id="editRemark" class="input-xlarge"rows="2"></textarea>
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
