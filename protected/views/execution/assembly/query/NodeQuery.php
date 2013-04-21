<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>质量查询</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/query/NodeQuery.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/query/nodeQuery.js"></script>
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
                <div ><ul class="breadcrumb"><!-- 面包屑 -->
                        <li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
                        <li><a href="/bms/execution/home">总装</a><span class="divider">&gt;</span></li>
                        <li><a href="#">数据查询</a><span class="divider">&gt;</span></li>
                        <li class="active">质量查询</li>                
                </ul></div><!-- end 面包屑 -->
                <div>
                <form id="form" class="well form-inline">
                    <!-- <legend>节点查询</legend> -->
					<table>
						<tr>
							<td class="alignRight"><label>起止时间&nbsp;&nbsp;</label></td>
                            <td>
                                <input type="text" class="span3" placeholder="开始时间..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
                            </td>
                            <td>
                                -
                            </td>
                            <td>
                                <input type="text" class="span3" placeholder="结束时间..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
                            </td>
							<td>
								<select name="" id="selectNode" class="input-medium">
                                    <option value="">所有节点</option>
									<!-- <option value="PBS">PBS</option>
									<option value="T0">T0</option> -->
									<option value="VQ1" selected="true">VQ1静态</option>
									<option value="CHECK_LINE">VQ2动态.检测线</option>
									<option value="ROAD_TEST_FINISH">VQ2动态.路试</option>
                                    <option value="VQ2">VQ2动态.淋雨</option>
									<option value="VQ2_ALL">VQ2动态检验</option>
                                    <option value="VQ3">VQ3外观</option>
									<option value="WDI">WDI</option>
									<!-- <option value="CHECK_IN">入成品库</option>
									<option value="CHECK_OUT">出成品库</option> -->
								</select>
							</td>
                            <!-- <td colspan="2">
                                <input type="button" class="btn btn-primary" id='btnQuery' value='查询'></input>   
                                <input id="btnExport" class='btn btn-success' type="button" value="全部导出"></input>
                            </td> -->
                        </tr>
                        <tr>
                            <td class="alignRight"><label>零部件-模式&nbsp;&nbsp;</label></td>
                            <td>
                                <input type="text" class="span3" placeholder="零部件..." id="componentText" />
                            </td>
                            <td>-</td>
                            <td>
                                <input type="text" class="span3" placeholder="故障模式..." id="faultModeText" />
                            </td>
                            <td>
                                <label class="checkbox"><input type="checkbox" id="checkboxF0" value="F0">F0</input></label>
                                <label class="checkbox"><input type="checkbox" id="checkboxM6" value="M6">M6</input></label>
                                <label class="checkbox"><input type="checkbox" id="checkbox6B" value="6B">思锐</input></label>
                            </td>
                        </tr>
					</table> 
                </form>      
                </div>
               <!-- <div style="display:none">
                    <h5 class='pull-left'>查询结果:<span id='totalText'></span></h5>               
                </div>-->
                <div id="divDetail">
                    <div>
                        <ul id="tabs" class="nav nav-pills">
                            <li id="carsDetail"><a href="#dataList" data-toggle="tab"> 详细报表 </a></li>
                            <li id="platoTab"><a href="#plato" data-toggle="tab">柏拉图</a></li>
                            <li><a href="#faultDistribute" data-toggle="tab">故障分布</a></li>
                            <li id="dpuTab"><a href="#dpu" data-toggle="tab">DPU趋势</a></li>
                            <li id="passRateTab"><a href="#passRate" data-toggle="tab">合格率趋势</a></li>
                            <!-- <li><a href="#statistic" data-toggle="tab">车辆统计</a></li> -->
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

                        <div class="tab-pane  active" id="dataList">
                            <table id="resultTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>车系</th>
                                        <th>VIN号</th>
                                        <th>故障零部件</th>
                                        <th>故障模式</th>
                                        <th>故障状态</th>
                                        <th>节点</th>
                                        <th>驾驶员</th>
                                        <th>录入人员</th>
                                        <th>录入时间</th>
                                        <th>确认时间</th>
                                        <th id="thChecker">初检人员</th>
                                        <th id="thSubChecker">复检人员</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="plato">
                            <div id="platoContainer" style="min-width: 800px; min-height: 600px; margin: 0 auto"></div>
                            <table id="tablePlato" class="table table-condensed">
                                <thead />
                                <tbody />
                            </table>
                        </div>

                        <div class="tab-pane" id="faultDistribute">
                            <div id="divRadio">   
                                <!--<span>分布条件:</span>-->
                                <label class="radio inline">
                                  <input type="radio" name="optionsRadios" id="optionsRadios1" value="component_chart_data" checked>
                                  零部件
                                </label>
                                <label class="radio inline">
                                  <input type="radio" name="optionsRadios" id="optionsRadios2" value="fault_mode_chart_data">
                                  故障模式
                                </label>
                                <label class="radio inline">
                                  <input type="radio" name="optionsRadios" id="optionsRadios3" value="series_chart_data">
                                  车系
                                </label>
                                <label class="radio inline">
                                  <input type="radio" name="optionsRadios" id="optionsRadios4" value="node_chart_data">
                                  节点
                                </label>
                            </div>
                            <div id="pieContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                            
                            <table id="tableFaultDistribute" class="table table-condensed">
                                <thead>
                                    <tr></tr>
                                </thead>
                                <tbody>
                                    <tr></tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="dpu">
                            <div id="lineContainer" style="min-width: 800px; height: 400px; margin: 0 auto"></div>
                            <table id="tableDpu" class="table table-condensed">
                                <thead>
                                    <tr></tr>
                                </thead>
                                <tbody>
                                    <tr></tr>
                                    <tr></tr>
                                    <tr></tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="passRate">
                            <div id="passRateContainer" style="min-width: 800px; height: 400px; margin: 0 auto"></div>
                            <table id="tablePassRate" class="table table-condensed">
                                <thead />
                                <tbody />
                            </table>
                        </div>

                        <div class="tab-pane" id="statistic">
                            <div id="statisticContainer" style="min-width: 800px; height: 400px; margin: 0 auto"></div>
                            <table id="tableStatistic" class="table table-condensed">
                                <thead />
                                <tbody />
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>
