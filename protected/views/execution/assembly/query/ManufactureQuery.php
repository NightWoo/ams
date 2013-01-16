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
                            <td>节点 / 工段</td>
                            <td>开始时间</td>
                            <td>结束时间</td>
                            <td class="withSection">停线类型</td>
                            <td class="withSection">责任部门</td>
                            <td class="withSection">原因</td>
                            <td class="withNode"></td>
                        </tr>
						<tr>
							<td>
								<select name="" id="selectNode" class="input-small">
                                    <option value="PBS">&lt;PBS&gt;</option>
                                    <option value="T0">&lt;T0&gt;</option>
                                    <option value="CHECK_IN">&lt;入库&gt;</option>
                                    <option value="CHECK_OUT">&lt;出库&gt;</option>
                                    <option value="" selected>全部工段</option>
                                    <option value="T1">T1</option>
                                    <option value="T2">T2</option>
                                    <option value="T3">T3</option>
                                    <option value="C1">C1</option>
                                    <option value="C2">C2</option>
                                    <option value="F1">F1</option>
                                    <option value="F2">F2</option>
								</select>
                                
							</td>
                            <td>
                                <input type="text" class="span2" placeholder="开始时间..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
                            </td>
                            <td>
                                <input type="text" class="span2" placeholder="结束时间..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
                            </td>
                            <td  class="withSection">
                                <select name="" id="pauseType" class="input-small">
                                    <option value="" selected>全部</option>
                                    <option value="工位求助">工位求助</option>
                                    <option value="紧急停止">紧急停止</option>
                                    <option value="设备故障">设备故障</option>
                                    <option value="计划停线">计划停线</option>
                                </select>
                            </td>
                            <td class="withSection">
                                <input type="text" class="span2" placeholder="责任部门..." id="dutyDepartment"/>
                            </td>
                            <td class="withSection">
                                <input type="text" class="input-medium" placeholder="停线原因..." id="pauseReason"/>
                            </td>
                           <td>
                                <input type="button" class="btn btn-primary" id='btnQuery' value='查询'></input>   
                                <input id="btnExport" class='btn btn-success' type="button" value="导出"></input>
                            </td>
                            <td class="withNode">
                                <label class="checkbox"><input type="checkbox" checked="checked" id="checkboxF0" value="F0">F0</input></label>
                                <label class="checkbox"><input type="checkbox" id="checkboxM6" value="M6" disabled>M6</input></label>
                                <label class="checkbox"><input type="checkbox" id="checkboxSiRui" value="思锐" disabled>思锐</input></label>
                            </td>
                        </tr>    
					</table> 
                </form>      
                </div>
                <div id="divDetail">
                    <div>
                        <ul id="tabs" class="nav nav-pills">
                            <li id="carDetail"><a href="#dataList" data-toggle="tab">车辆明细</a></li>
                            <li id="statistics"><a href="#statistic" data-toggle="tab">车辆统计</a></li>
                            <!-- <li><span class="divider">&nbsp;|&nbsp;</span></li> -->
                            <li id="pauseDetail"  class="active"><a href="#dataPauseDetail" data-toggle="tab">停线明细</a></li>
                            <li id="pauseDistribution"><a href="#" data-toggle="tab">停线分布</a></li>
                            <li id="useRate"><a href="#" data-toggle="tab">生产利用率</a></li>
                            <li class="dividerLi"><span class="divider">&nbsp;|&nbsp;</span></li>
                            <li id="planDetail"><a href="#" data-toggle="tab">计划明细</a></li>
                            <li id="completion"><a href="#" data-toggle="tab">计划完成率</a></li>
                        </ul>
                    </div>
                    <div id="tabContent" class="tab-content">
                        <div class="tab-pane" id="dataList">
                            <table id="tableCars" class="table table-bordered">
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
                            <div id="paginationCars" class="pagination" style="display: none;">
                                <ul>
                                    <li id="preCars" class="prePage"><a href="#"><span>&lt;</span></a></li>
                                    <li id="curCars" class="active curPage" page="1"><a href="#"><span>1</span></a></li>
                                    <li id="nextCars" class="nextPage"><a href="#"><span>&gt;</span></a></li>
                                </ul>
                                <ul>
                                    <li id="exportCars"><a href=""><span id="totalCars"></span></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-pane" id="statistic">
                            <div id="statisticContainer" style="min-width: 800px; height: 400px; margin: 0 auto"></div>
                            <table id="tableStatistic" class="table table-condensed">
                                <thead />
                                <tbody />
                            </table>
                        </div>

                        <div class="tab-pane active" id="dataPauseDetail">
                            <table id="tablePause" class="table table-condensed table-hover" style="display: none;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th id="thType">停线类型</th>
                                        <th id="thSeat">工位</th>
                                        <th id="thDuty">责任部门</th>
                                        <th id="thReason">原因</th>
                                        <th id="thHowlong" class="alignRight">时长</th>
                                        <th id="thPauseTime">停线时刻</th>
                                        <th id="thRecoverTime">恢复时刻</th>
                                        <th id="thEditor">编辑人</th>
                                    </tr>
                                </thead>
                                <tbody>
                        
                                </tbody>
                            </table>
                            <div id="paginationPause" class="pagination" style="display: none;">
                                <ul>
                                    <li id="prePause" class="prePage"><a href="#"><span>&lt;</span></a></li>
                                    <li id="curPause" class="active curPage" page="1"><a href="#"><span>1</span></a></li>
                                    <li id="nextPause" class="nextPage"><a href="#"><span>&gt;</span></a></li>
                                </ul>
                                <ul>
                                    <li id="exportPause"><a href=""><span id="totalText"></span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>
