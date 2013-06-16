<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>结存查询</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/query/BalanceQuery.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/query/balanceQuery.js"></script>
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
                    <legend>结存查询
                        <span class="pull-right">
                            <!-- <a href="/bms/execution/orderMaintain"><i class="icon-link"></i>&nbsp;订单维护</a> -->
                        </span>
                    </legend>
                </div>
                <div>
                    <form id="form" class="well form-inline">
    					<table>
    						<tr>
    							<!-- <td class="alignRight"><label>时间&nbsp;&nbsp;</label></td>
                                <td>
                                    <input type="text" class="input-medium" disabled placeholder="未开放此功能..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    <input type="text" class="input-medium" disabled placeholder="未开放此功能..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
                                </td> -->
    							<td>
    								<select name="" id="selectState" class="input-medium">
                                        <option value="assembly">总装(除PBS)</option>
                                        <option value="WH">成品库</option>
                                        <option value="WHin">成品库可备</option>
                                        <option value="recycle">周转车</option>
                                        <option value="VQ3">VQ3</option>
                                        <option value="VQ2">VQ2</option>
                                        <option value="VQ1">VQ1</option>
                                        <option value="onLine">I线</option>
                                        <option value="PBS">PBS</option>
    								</select>
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
                            <li id="carsDetail"><a href="#dataList" data-toggle="tab">结存明细</a></li>
                            <li><a href="#carsDistribute" data-toggle="tab">车辆分布</a></li>
                            <li><a href="#balanceTrendLine" data-toggle="tab">区域趋势</a></li>
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
                        <div class="tab-pane" id="dataList">
                            <table id="resultTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>流水号</th>
                                        <th>VIN</th>
                                        <th>车系</th>
                                        <th>车型/配置</th>
                                        <th>耐寒性</th>
                                        <th>颜色</th>
                                        <th>状态</th>
                                        <th>
                                            <select id="area" class="input-mini">
                                                <option value=''>库区</option>
                                            </select>
                                        </th>
                                        <th>下线时间</th>
                                        <th>入库时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="carsDistribute">
                            <div class="tableContainer span10 pull-left">
                                <table id="tableCarsDistribute" class="table table-condensed table-hover table-bordered">
                                    <thead>

                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <div id="divCheckbox">   
                                    <label class="checkbox">
                                      <input type="checkbox" name="optionsRadios" id="checkboxMerge" value="reycle_bar_data">
                                      将VQ1、VQ2、VQ3结存合并为周转车
                                    </label>
                                </div>                          
                            </div>
                            <div class="chartContainer offset10">
                                <!-- <div id="divRadio">   
                                    <label class="radio inline">
                                      <input type="radio" name="optionsRadios" id="optionsRadios1" value="car_type_pie_data" checked>
                                      车型
                                    </label>
                                    <label class="radio inline">
                                      <input type="radio" name="optionsRadios" id="optionsRadios2" value="color_pie_data">
                                      颜色
                                    </label>
                                </div> -->
                                <div id="columnContainer" style="min-width: 400px; height: 200px; margin: 0 auto"></div>
                            </div>
                            <div class="chartContainer offset9">
                                
                                <div id="barContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                            </div>
                        </div>

                        <div class="tab-pane" id="balanceTrend">

                            <div id="balanceTrendContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                            
                            <table id="tableBalanceTrendContainer" class="table table-condensed">
                                <thead>
                                    <tr></tr>
                                </thead>
                                <tbody>
                                    <tr></tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="carsModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4>-</h4>
            </div>
            <div class="modal-body">

                <table id="resultCars" class="table table-condensed table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>流水号</th>
                            <th>VIN</th>
                            <th>车系</th>
                            <th>车型/配置</th>
                            <th>耐寒性</th>
                            <th>颜色</th>
                            <th>状态</th>
                            <th>库区</th>
                            <th>下线时间</th>
                            <th>入库时间</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
            </div>
        </div>

	</body>
</html>
