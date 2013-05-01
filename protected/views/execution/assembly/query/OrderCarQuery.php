<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>订单查询</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/query/OrderCarQuery.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">		
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
    	<script type="text/javascript" src="/bms/js/execution/assembly/query/orderCarQuery.js"></script>
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
                <div>
                    <legend>发车查询
                        <span class="pull-right">
                            <!-- <a href="/bms/execution/orderMaintain"><i class="icon-link"></i>&nbsp;订单维护</a> -->
                        </span>
                    </legend>
                </div>
                <form class="well form-inline">
                    	<div class="input-prepend input-append">
							<span class="add-on">订单号</span>
							<input type="text" class="input-medium" placeholder="请输入订单号..." id="orderNumberText" />
                            <a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
                        </div>
                        <div class="input-prepend input-append">
                            <span class="add-on">经销商</span>
                            <input type="text" class="input-medium" placeholder="请输入经销商..." id="distributorText" />
                            <a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
                        </div>
                        <!-- <input type="button" class="btn btn-primary" id ="btnQuery" value ="查询"></input> -->
                        <div class="input-append input-prepend" style="clear: right">
                            <span class="add-on">日期</span>
                            <input id="standbyDate"  type="text" class="input-small" placeholder="发车日期..."onClick="WdatePicker({el:'standbyDate',dateFmt:'yyyy-MM-dd'});"/>
                            <span class="add-on" style="padding:4px 0">-</span>
                            <input id="standbyDateEnd"  type="text" class="input-small" placeholder="发车日期..."onClick="WdatePicker({el:'standbyDateEnd',dateFmt:'yyyy-MM-dd'});"/>
                            <!-- <a class="btn resetDate appendBtn"><i class="icon-undo"></i></a> -->
                            <a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
                        </div>
                        <div class="help-inline"  style="padding-left:0;">
                            <select name="" id="selectSeries" class="input-small">
                                <option value="">全车系</option>
                                <option value="F0">F0</option>
                                <option value="M6">M6</option>
                                <option value="6B">思锐</option>
                            </select>
                            <label class="checkbox"><input type="checkbox" id="checkboxActive" value="1">激活</input></label>
                            <label class="checkbox"><input type="checkbox" id="checkFreeze" value="0">冻结</input></label>
                            <label class="checkbox"><input type="checkbox" id="checkClosed" value="2">完成</input></label>
                        </div>
                        <!-- <div id='divInfo' class="help-inline">
                            <span class="label label-info" rel="tooltip" title="经销商" id="distributorInfo"></span>
                        </div> -->
                </form>
                    <div>
                        <ul id="tabs" class="nav nav-pills">
                            <li><a href="#tabOrderCars" data-toggle="tab">车辆明细</a></li>
                            <li><a href="#tabOrderDetail" data-toggle="tab">订单明细</a></li>
                            <div id="paginationCars" class="pagination pagination-small pagination-right" style="display: none;">
                                <ul>
                                    <li id="exportCars"><a href=""><span id="totalCars"></span></a></li>
                                </ul>
                                <ul>
                                    <li id="firstCars"><a href="#">&lt;&lt;</a></li>
                                    <li id="preCars" class="prePage"><a href="#">&lt;</a></li>
                                    <li id="curCars" class="active curPage" page="1"><a href="#">1</a></li>
                                    <li id="nextCars" class="nextPage"><a href="#">&gt;</a></li>
                                    <li id="lastCars"><a href="#">&gt;&gt;</a></li>
                                </ul>
                            </div>
                        </ul>
                    </div>
                    <div id="tabContent" class="tab-content">
                        <div class="tab-pane" id="tabOrderCars">
                            <table id="tableOrderCars" class="table table-bordered">
                                <thead>
                                    <tr>
                                    	<th>车道</th>
                                        <th>订单号</th>
                                        <th>经销商</th>
                                        <th>流水号</th>
                                        <th>VIN</th>
                                        <th>车系</th>
                                        <th>配置</th>
                                        <th>耐寒性</th>
                                        <th>颜色</th>
                                        <th>发动机号</th>
                                        <th>出库时间</th>
                                        <th>原库道</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="tabOrderDetail">
                            <table id="tableOrderDetail" class="table table-condensed table-bordered">
                                <thead>
                                    <th>备板编号</th>
                                	<th style="width:30px">车道</th>
                                    <th>订单号</th>
                                    <th style="width:200px">经销商</th>
                                    <th style="width:30px">车系</th>
                                    <th style="width:200px">车型/配置/耐寒性</th>
                                    <!-- <th style="width:40px">耐寒性</th> -->
                                    <th style="width:40px">颜色</th>
                                    <th colspan="2" style="text-align: center">数量</th>
                                    <th colspan="2" style="text-align: center">已备</th>
                                    <th colspan="2" style="text-align: center">出库</th>
                                    <th style="width:70px">指令时间</th>
                                    <th style="width:70px">备齐时间</th>
                                    <th style="width:70px">完成时间</th>
                                    <th></th>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- END MAIN -->
        </div>
    </body>
</html>
