<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>成品库报表</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/report/WarehouseReport.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
        <link href="/bms/css/datetimepicker.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.zh-CN.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/report/warehouseReport.js"></script>
        <script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
        <script type="text/javascript" src="/bms/js/highcharts.src.js"></script>
        <script type="text/javascript" src="/bms/js/exporting.src.js"></script>
	<!--[if IE 6]>    
            <link href="/bms/css/ie6.min.css" rel="stylesheet">
    <![endif]-->
    </head>


	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
        <div class="offhead">
            <?php
            require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
            ?>

        	<div id="bodyright" class="offset2">
                <div>
                    <legend>成品库报表
                        <span class="pull-right">
                            <!-- <a href="/bms/execution/orderMaintain"><i class="icon-link"></i>&nbsp;订单维护</a> -->
                        </span>
                    </legend>
                </div>
                <div>
                    <form id="form" class="well form-inline">
    					<table>
                            <tr>
                                <td>开始时间&nbsp;<a href="#" id="resetST"><i class="icon-undo"></i></a></td>
                                <td>结束时间&nbsp;<a href="#" id="refreshET"><i class="icon-refresh"></i></a></td>
                                <td></td>
                            </tr>
    						<tr>
                                <td>
                                    <!-- <input type="text" class="input-medium"  placeholder="起始时间..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:mm'});"/> -->
                                    <input type="text" class="input-medium"  placeholder="起始时间..." id="startTime"/>
                                </td>
                                <td>
                                    <!-- <input type="text" class="input-medium" placeholder="结束时间..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:mm'});"/> -->
                                    <input type="text" class="input-medium" placeholder="结束时间..." id="endTime"/>
                                </td>
                                <td>
                                    <select name="" id="selectSeries" class="input-small">
                                        <option value="">全车系</option>
                                        <option value="F0">F0</option>
                                        <option value="M6">M6</option>
                                        <option value="6B">思锐</option>
                                    </select>
                                </td>
                                <!-- <td colspan="2">
                                    <input type="button" class="btn btn-primary" id='btnQuery' value='查询'></input>   
                                    <input id="btnExport" class='btn btn-success' type="button" value="全部导出"></input>
                                </td> -->
                            </tr>
    					</table> 
                    </form>      
                </div>

                <div id="divDetail">
                    <div>
                        <ul id="tabs" class="nav nav-pills">
                            <li id="checkinDetail"><a href="#paneCheckin" data-toggle="tab">入库明细</a></li>
                            <li><a href="#paneCheckout" data-toggle="tab">出库明细</a></li>
                            <li><a href="#paneDailyReport" data-toggle="tab">日报表</a></li>
                            <div id="paginationCheckin" class="pagination pagination-small pagination-right" style="display: none;">
                                <ul>
                                    <li id="exportCheckin"><a href=""><span id="totalCheckin"></span></a></li>
                                </ul>
                                <ul>
                                    <li id="firstCheckin"><a href="#">&lt;&lt;</a></li>
                                    <li id="preCheckin" class="prePage"><a href="#">&lt;</a></li>
                                    <li id="curCheckin" class="active curPage" page="1"><a href="#">1</a></li>
                                    <li id="nextCheckin" class="nextPage"><a href="#">&gt;</a></li>
                                    <li id="lastCheckin"><a href="#">&gt;&gt;</a></li>
                                </ul>
                            </div>

                            <div id="paginationCheckout" class="pagination pagination-small pagination-right" style="display: none;">
                                <ul>
                                    <li id="exportCheckout"><a href=""><span id="totalCheckout"></span></a></li>
                                </ul>
                                <ul>
                                    <li id="firstCheckout"><a href="#">&lt;&lt;</a></li>
                                    <li id="preCheckout" class="prePage"><a href="#">&lt;</a></li>
                                    <li id="curCheckout" class="active curPage" page="1"><a href="#">1</a></li>
                                    <li id="nextCheckout" class="nextPage"><a href="#">&gt;</a></li>
                                    <li id="lastCheckout"><a href="#">&gt;&gt;</a></li>
                                </ul>
                            </div>
                        </ul>
                    </div>
                    <div id="tabContent" class="tab-content">
                        <div class="tab-pane  active" id="paneCheckin">
                            <table id="resultCheckin" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>库道</th>
                                        <th>VIN</th>
                                        <th>车系</th>
                                        <th>车型/配置</th>
                                        <th>耐寒性</th>
                                        <th>颜色</th>
                                        <th>发动机号</th>
                                        <th>入库时间</th>
                                        <th>线别</th>
                                        <th>下线时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="paneCheckout">
                             <table id="resultCheckout" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>订单号</th>
                                        <th>车道</th>
                                        <th>经销商</th>
                                        <th>VIN</th>
                                        <th>车系</th>
                                        <!-- <th>车型</th> -->
                                        <th>配置</th>
                                        <th>耐寒性</th>
                                        <th>颜色</th>
                                        <th>发动机号</th>
                                        <th>出库时间</th>
                                        <!-- <th>备注</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="paneDailyReport">

                        </div>
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>
