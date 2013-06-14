<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>质量查询</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/query/NodeQuery.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
        <!-- <link href="/bms/css/datetimepicker.css" rel="stylesheet"> -->
        <link href="/bms/css/flick/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
        <link href="/bms/css/jquery-ui-timepicker-addon.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-timepicker-addon.zh-CN.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <!-- <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.min.js"></script> -->
        <!-- <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.zh-CN.js"></script> -->
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/query/nodeQuery.js"></script>
        <!-- <script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script> -->
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
                    <legend>质量查询
                    </legend>
                </div>
                <form id="form" class="well form-inline">
					<table>
						<tr>
							<td class="alignRight"><label>起止时间&nbsp;&nbsp;</label></td>
                            <td>
                                <!-- <input type="text" class="span3" placeholder="开始时间..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:mm'});"/> -->
                                <input type="text" class="span3" placeholder="开始时间..." id="startTime"/>
                            </td>
                            <td>
                                -
                            </td>
                            <td>
                                <!-- <input type="text" class="span3" placeholder="结束时间..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:mm'});"/> -->
                                <input type="text" class="span3" placeholder="结束时间..." id="endTime"/>
                            </td>
							<td>
								<select name="" id="selectNode" class="input-medium">
                                    <option value="">所有节点</option>
									<!-- <option value="PBS">PBS</option>
									<option value="T0">T0</option> -->
									<option value="VQ1">VQ1静态</option>
									<option value="VQ2_ALL">VQ2动态检验</option>
                                    <option value="CHECK_LINE">--VQ2动态.检测线</option>
                                    <option value="ROAD_TEST_FINISH">--VQ2动态.路试</option>
                                    <option value="VQ2">--VQ2动态.淋雨</option>
                                    <option value="VQ3">VQ3外观</option>
									<option value="WDI">WDI</option>
									<!-- <option value="CHECK_IN">入成品库</option>
									<option value="CHECK_OUT">出成品库</option> -->
								</select>

                                <select name="" id="selectItem" class="input-small">
                                    <option value="" selected="true">项目</option>
                                    <option value="NCA">四轮定位</option>
                                    <option value="Light">前照灯</option>
                                    <!-- <option value="Angle">转角</option> -->
                                    <option value="Slide">侧滑</option>
                                    <option value="Brake">制动</option>
                                    <option value="Speed">速度表</option>
                                    <option value="Gas">废气</option>
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
               <!-- <div style="display:none">
                    <h5 class='pull-left'>查询结果:<span id='totalText'></span></h5>               
                </div>-->
                <div id="divDetail">
                    <div>
                        <ul id="tabs" class="nav nav-pills">
                            <li id="carsDetail"><a href="#dataList" data-toggle="tab"> 详细报表 </a></li>
                            <li id="platoTab"><a href="#plato" data-toggle="tab">柏拉图</a></li>
                            <li id="dutyDistributionTab"><a href="#dutyDistribution" data-toggle="tab">责任分布</a></li>
                            <li id="dpuTab"><a href="#dpu" data-toggle="tab">DPU趋势</a></li>
                            <li id="passRateTab"><a href="#passRate" data-toggle="tab">合格率趋势</a></li>
                            <!-- <li><a href="#statistic" data-toggle="tab">车辆统计</a></li> -->
                            <div id="paginationCars" class="pagination pagination-small pagination-right" style="display: none;">
                                <ul>
                                    <li id="exportCars"><a href=""  rel="tooltip" data-toggle="tooltip" data-placement="top" title="精确到分钟删除重复项"><span id="totalCars"></span></a></li>
                                </ul>
                                <ul>
                                    <li id="firstCars"><a href="#">&lt;&lt;</a></li>
                                    <li id="preCars" class="prePage"><a href="#">&lt;</a></li>
                                    <li id="curCars" class="active curPage" page="1"><a href="#">1</a></li>
                                    <li id="nextCars" class="nextPage"><a href="#">&gt;</a></li>
                                    <li id="lastCars"><a href="#">&gt;&gt;</a></li>
                                </ul>
                            </div>

                            <div id="paginationTestline" class="pagination pagination-small pagination-right" style="display: none;">
                                <ul>
                                    <li id="exportTestline"><a href=""><span id="totalTestline"></span></a></li>
                                </ul>
                                <ul>
                                    <li id="firstTestline"><a href="#">&lt;&lt;</a></li>
                                    <li id="preTestline" class="prePage"><a href="#">&lt;</a></li>
                                    <li id="curTestline" class="active curPage" page="1"><a href="#">1</a></li>
                                    <li id="nextTestline" class="nextPage"><a href="#">&gt;</a></li>
                                    <li id="lastTestline"><a href="#">&gt;&gt;</a></li>
                                </ul>
                            </div>
                        </ul>
                    </div>
                    <div id="tabContent" class="tab-content">

                        <div class="tab-pane  active" id="dataList">
                            <table id="resultTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>节点</th>
                                        <th>车系</th>
                                        <th>VIN号</th>
                                        <th>故障零部件</th>
                                        <th>故障模式</th>
                                        <th>故障状态</th>
                                        <th>录入人员</th>
                                        <!-- <th>录入人员</th> -->
                                        <th>录入时间</th>
                                        <th>确认时间</th>
                                        <th id="thChecker">初检人员</th>
                                        <th id="thSubChecker">复检人员</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>

                            <table id="tableNCA" class="table table-bordered table-condensed tableTestline">
                                <thead>
                                    <tr>
                                        <th rowspan="2">车系</th>
                                        <th rowspan="2">VIN</th>
                                        <th colspan="4">前轮前束</th>
                                        <th colspan="4">后轮前束</th>
                                        <th rowspan="2">总评价</th>
                                    </tr>
                                    <tr>
                                        <th>前左轮</th>
                                        <th>前右轮</th>
                                        <th>前总前束</th>
                                        <th>前轮评价</th>
                                        <th>后左轮</th>
                                        <th>后右轮</th>
                                        <th>后总前束</th>
                                        <th>后轮评价</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>

                            <table id="tableLight" class="table table-bordered table-condensed tableTestline">
                                <thead>
                                    <tr>
                                        <td colspan="29" >
                                            单位：发光强度(cd)、上下/左右偏角(cm/10m)、照射高度(cm)
                                        </td>
                                    </tr>
                                    <tr>
                                        <th rowspan="3">车系</th>
                                        <th rowspan="3">VIN</th>
                                        <th colspan="10">左灯</th>
                                        <th colspan="10">右灯</th>
                                        <th rowspan="3">总评</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5">远光</th>
                                        <th colspan="5">近光</th>
                                        <th colspan="5">远光</th>
                                        <th colspan="5">近光</th>
                                    </tr>
                                    <tr>
                                        <th>光强(</th>
                                        <th>上下</th>
                                        <th>左右</th>
                                        <th>高度</th>
                                        <th>评价</th>
                                        <th>光强(</th>
                                        <th>上下</th>
                                        <th>左右</th>
                                        <th>高度</th>
                                        <th>评价</th>
                                        <th>光强(</th>
                                        <th>上下</th>
                                        <th>左右</th>
                                        <th>高度</th>
                                        <th>评价</th>
                                        <th>光强(</th>
                                        <th>上下</th>
                                        <th>左右</th>
                                        <th>高度</th>
                                        <th>评价</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>

                            <table id="tableSlide" class="table table-bordered table-condensed tableTestline">
                                <thead>
                                    <tr>
                                        <th>车系</th>
                                        <th>VIN</th>
                                        <th>侧滑(m/km)</th>
                                        <th>评价</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>

                            <table id="tableBrake" class="table table-bordered table-condensed tableTestline">
                                <thead>
                                    <tr>
                                        <th rowspan="2">车系</th>
                                        <th rowspan="2">VIN</th>
                                        <th colspan="8">前轴</th>
                                        <th colspan="8">后轴</th>
                                        <th colspan="3">整车</th>
                                        <th colspan="3">驻车</th>
                                        <th rowspan="2">总评</th>
                                    </tr>
                                    <tr>
                                        <th>轴荷(×10N)</th>
                                        <th>左(×10N)</th>
                                        <th>右(×10N)</th>
                                        <th>和(%)</th>
                                        <th>差(%)</th>
                                        <th>左阻滞(%)</th>
                                        <th>右阻滞(%)</th>
                                        <th>评价</th>
                                        <th>轴荷(×10N)</th>
                                        <th>左(×10N)</th>
                                        <th>右(×10N)</th>
                                        <th>和(%)</th>
                                        <th>差(%)</th>
                                        <th>左阻滞(%)</th>
                                        <th>右阻滞(%)</th>
                                        <th>评价</th>
                                        <th>总制动力(×10N)</th>
                                        <th>和(%)</th>
                                        <th>评价</th>
                                        <th>总制动力(×10N)</th>
                                        <th>和(%)</th>
                                        <th>评价</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>

                            <table id="tableSpeed" class="table table-bordered table-condensed tableTestline">
                                <thead>
                                    <tr>
                                        <th>车系</th>
                                        <th>VIN</th>
                                        <th>标称值(km/h)</th>
                                        <th>实测值(km/h)</th>
                                        <th>误差(±)</th>
                                        <th>评价</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>

                            <table id="tableGas" class="table table-bordered table-condensed tableTestline">
                                <thead>
                                    <tr>
                                        <th rowspan="2">车系</th>
                                        <th rowspan="2">VIN</th>
                                        <th colspan="3">低怠速</th>
                                        <th colspan="3">高怠速</th>
                                        <th rowspan="2">总评</th>
                                    </tr>
                                    <tr>
                                        <th>HC(ppm)</th>
                                        <th>CO(%)</th>
                                        <th>评价</th>
                                        <th>HC(ppm)</th>
                                        <th>CO(%)</th>
                                        <th>评价</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="plato">
                            <div id="platoContainer" style="min-width: 800px; min-height: 600px; margin: 0 auto"></div>
                            <table id="tablePlato" class="table table-condensed  table-bordered">
                                <thead />
                                <tbody />
                            </table>
                        </div>

                        <div class="tab-pane" id="dutyDistribution">
                            <div id="divRadio">   
                                <!-- <label class="radio inline">
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
                                </label> -->
                            </div>
                            <div id="dutyDistibutionContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                            
                            <table id="tableFaultDistribution" class="table table-condensed  table-bordered">
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
                            <div><p class="text-info">注：由于车辆可能在不同的时间多次进入经过VQ节点，所以结果中，合计值≠各时间段数据的和值或求和后相乘/除的结果</p></div>
                            <table id="tableDpu" class="table table-condensed  table-bordered">
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
                            <div><p class="text-info">注：由于车辆可能在不同的时间多次进入经过VQ节点，所以结果中，合计值≠各时间段数据的和值或求和后相乘/除的结果</p></div>
                            <table id="tablePassRate" class="table table-condensed table-bordered">
                                <thead />
                                <tbody />
                            </table>
                        </div>

                        <div class="tab-pane" id="statistic">
                            <div id="statisticContainer" style="min-width: 800px; height: 400px; margin: 0 auto"></div>
                            <table id="tableStatistic" class="table table-condensed  table-bordered">
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
