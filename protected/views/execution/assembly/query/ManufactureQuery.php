<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>生产查询</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/query/ManufactureQuery.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/query/manufactureQuery.js"></script>
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
                        <li class="active">生产查询</li>                
                </ul></div><!-- end 面包屑 -->
                <div>
                <form id="form" class="well form-inline">
                    <!-- <legend>节点查询</legend> -->
					<table>
						<tr>
							<td class="alignRight"><label>节点&nbsp;&nbsp;</label></td>
							<td>
								<select name="" id="selectNode" class="span3">
									<option value="PBS">PBS</option>
									<option value="T0">T0</option>
									<option value="CHECK_IN">入成品库</option>
									<option value="CHECK_OUT">出成品库</option>
								</select>
                                
							</td>
                            <td colspan="2">
                                <input type="button" class="btn btn-primary" id='btnQuery' value='查询'></input>   
                                <input id="btnExport" class='btn btn-success' type="button" value="全部导出"></input>
                            </td>
						</tr>
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
                                <label class="checkbox"><input type="checkbox" checked="checked" id="checkboxF0" value="F0">F0</input></label>
                                <label class="checkbox"><input type="checkbox" id="checkboxM6" value="M6" disabled>M6</input></label>
                                <label class="checkbox"><input type="checkbox" id="checkboxSiRui" value="思锐" disabled>思锐</input></label>
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
                            <li  class="active"><a href="#dataList" data-toggle="tab">车辆明细</a></li>
                            <li id="platoTab"><a href="#plato" data-toggle="tab">柏拉图</a></li>
                            <li id="dpuTab"><a href="#dpu" data-toggle="tab">DPU趋势</a></li>
                            <li id="passRateTab"><a href="#passRate" data-toggle="tab">合格率趋势</a></li>
                            <li><a href="#statistic" data-toggle="tab">车辆统计</a></li>
                            <li><a href="#" data-toggle="tab">停线流水</a></li>
                            <li><a href="#" data-toggle="tab">停线分布</a></li>
                            <li><a href="#" data-toggle="tab">生产利用率</a></li>
                            <li><a href="#" data-toggle="tab">计划流水</a></li>
                            <li><a href="#" data-toggle="tab">计划完成率</a></li>
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
                                        <th>录入人员</th>
                                        <th>录入时间</th>
                                        <th>确认时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                            <ul class="pager">
                                <li class="prePage"><a>上一页</a></li>
                                <li class="curPage" page="1"> 第1页</li>
                                <li class="nextPage"><a>下一页</a></li>
                            </ul>
                        </div>

                        <div class="tab-pane" id="plato">
                            <div id="platoContainer" style="min-width: 800px; min-height: 600px; margin: 0 auto"></div>
                            <table id="tablePlato" class="table table-condensed">
                                <thead />
                                <tbody />
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
