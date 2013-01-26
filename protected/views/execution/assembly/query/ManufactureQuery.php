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
                            <td>工段 / 节点</td>
                            <td>开始时间</td>
                            <td>结束时间</td>
                            <td></td>
                            <td class="withSection">停线类型</td>
                            <td class="withSection">责任部门</td>
                            <td class="withSection">原因</td>
                            <td></td>
                            <td class="withNode"></td>
                        </tr>
						<tr>
							<td>
								<select name="" id="selectNode" class="input-small">
                                    <option value="" selected>全部工段</option>
                                    <option value="PBS">PBS/入库</option>
                                    <option value="T0">PBS/上线</option>
                                    <option value="T1">T1/T11</option>
                                    <option value="T2">T2/T21</option>
                                    <option value="T3">T3/T32</option>
                                    <option value="C1">C1/C10</option>
                                    <option value="C2">C2/C21</option>
                                    <option value="F1">F1/F10</option>
                                    <option value="F2">F2/F20</option>
                                    <option value="VQ1">VQ1/下线</option>
                                    <option value="CHECK_IN">WH/入库</option>
                                    <option value="CHECK_OUT">WH/出库</option>
								</select>
                                
							</td>
                            <td>
                                <input type="text" class="span2" placeholder="开始时间..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
                            </td>
                            <td>
                                <input type="text" class="span2" placeholder="结束时间..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
                            </td>
                            <td>&nbsp;&nbsp;</td>
                            <td  class="withSection">
                                <select name="" id="causeType" class="input-small">
                                    <option value="" selected>全部</option>
                                    <option value="生产组织">生产组织</option>
                                    <option value="品质异常">品质异常</option>
                                    <option value="设备故障">设备故障</option>
                                    <option value="物料供给">物料供给</option>
                                </select>
                            </td>
                            <td class="withSection">
                                <input type="text" class="span2" placeholder="责任部门..." id="dutyDepartment"/>
                            </td>
                            <td class="withSection">
                                <input type="text" class="span2" placeholder="停线原因..." id="pauseReason"/>
                            </td>
                            <td>&nbsp;&nbsp;</td>
                            <td class="withNode">
                                <label class="checkbox"><input type="checkbox" checked="checked" id="checkboxF0" value="F0">F0</input></label>
                                <label class="checkbox"><input type="checkbox" id="checkboxM6" value="M6">M6</input></label>
                                <label class="checkbox"><input type="checkbox" id="checkboxSiRui" value="思锐" disabled>思锐</input></label>
                            </td>
                            <!-- <td>
                                <input type="button" class="btn btn-primary" id='btnQuery' value='查询'></input>   
                                <input id="btnExport" class='btn btn-success' type="button" value="导出"></input>
                                <label>with&nbsp;&nbsp;</label>
                            </td> -->
                        </tr>    
					</table> 
                </form>      
                </div>
                <div id="divDetail">
                    <div>
                        <ul id="tabs" class="nav nav-pills">
                            <li id="carDetail"><a href="#dataList" data-toggle="tab">车辆明细</a></li>
                            <li id="statistics"><a href="#statistic" data-toggle="tab">车辆统计</a></li>
                            <li><p class="divider">&nbsp;|&nbsp;</p></li>
                            <li id="pauseDetail"><a href="#dataPauseDetail" data-toggle="tab">停线明细</a></li>
                            <li id="pauseDistribution"><a href="#pauseDistribute" data-toggle="tab">停线分析</a></li>
                            <li id="useRate"><a href="#paneUseRate" data-toggle="tab">生产利用率</a></li>
                            <li class="dividerLi"><p class="divider">&nbsp;|&nbsp;</p></li>
                            <li id="planDetail"><a href="#dataPlanDetail" data-toggle="tab">计划明细</a></li>
                            <li id="completion"><a href="#planCompletionRate" data-toggle="tab">计划完成率</a></li>
                            <div id="paginationCars" class="pagination pagination-small pagination-right" style="display: none;">
                                <ul>
                                    <li id="exportCars"><a href=""><span id="totalCars"></span></a></li>
                                </ul>
                                <ul>
                                    <li id="firstCars"><a href="#">&laquo;</a></li>
                                    <li id="preCars" class="prePage"><a href="#">&lt;</a></li>
                                    <li id="curCars" class="active curPage" page="1"><a href="#">1</a></li>
                                    <li id="nextCars" class="nextPage"><a href="#">&gt;</a></li>
                                    <li id="lastCars"><a href="#">&raquo;</a></li>
                                </ul>
                            </div>
                        </ul>
                    </div>
                    <div id="tabContent" class="tab-content">
                        <div class="tab-pane" id="dataList">
                            <table id="tableCars" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>VIN号</th>
                                        <th>车系</th>
                                        <th>流水号</th>
                                        <th>车型</th>
                                        <th>配置</th>
                                        <th>颜色</th>
                                        <th>耐寒性</th>
                                        <th>状态</th>
                                        <th>备注</th>
                                        <th>时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="statistic">
                            <div id="statisticContainer" style="min-width: 800px; height: 400px; margin: 0 auto"></div>
                            <table id="tableStatistic" class="table table-condensed">
                                <thead />
                                <tbody />
                            </table>
                        </div>

                        <div class="tab-pane" id="dataPauseDetail">
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
                                    <li id="exportPause"><a href=""><span id="totalPause"></span></a></li>
                                </ul>
                                <ul>
                                    <li id="firstPause"><a href="#"><span>&lt;&lt;</span></a></li>
                                    <li id="prePause" class="prePage"><a href="#"><span>&lt;</span></a></li>
                                    <li id="curPause" class="active curPage" page="1"><a href="#"><span>1</span></a></li>
                                    <li id="nextPause" class="nextPage"><a href="#"><span>&gt;</span></a></li>
                                    <li id="lastPause"><a href="#"><span>&gt;&gt;</span></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-pane" id="pauseDistribute">
                            <div id="radioPauseDistribute">   
                                <!--<span>分布条件:</span>-->
                                <label class="radio inline">
                                  <input type="radio" name="optionsRadios" id="optionsRadios1" value="cause_type_chart_data" checked="checked">
                                  停线类型
                                </label>
                                <label class="radio inline">
                                  <input type="radio" name="optionsRadios" id="optionsRadios2" value="duty_department_chart_data">
                                  责任部门
                                </label>
                            </div>
                            <div id="pieContainerPauseDistribute" style="min-width: 400px; height: 400px; margin: 0 auto">
                            </div>
                            <table id="tablePauseDistribute" class="table table-condensed">
                                <thead />
                                <tbody />
                            </table>
                        </div>

                        <div class="tab-pane" id="paneUseRate">
                            <div id="useRateContainer" style="min-width: 800px; height: 400px; margin: 0 auto"></div>
                            <table id="tableUseRate" class="table table-condensed">
                                <thead />
                                <tbody />
                            </table>
                        </div>

                        <div class="tab-pane" id="dataPlanDetail">
                            <table id="tablePlan" class="table table-condensed table-hover" style="display: none;">
                                <thead>
                                    <th>批次号</th>
                                    <th>车系</th>
                                    <th>计划日期</th>
                                    <th>数量</th>
                                    <th>完成</th>
                                    <th>配置</th>
                                    <th>车型</th>
                                    <th>颜色</th>
                                    <th>耐寒性</th>
                                    <th>年份</th>
                                    <th>订单类型</th>
                                    <th>备注</th>
                                </thead>
                                <tbody>
                        
                                </tbody>
                            </table>
                            <div id="paginationPlan" class="pagination" style="display: none;">
                                <ul>
                                    <li id="exportPlan"><a href=""><span id="totalPlan"></span></a></li>
                                </ul>
                                <ul>
                                    <li id="firstPlan"><a href="#"><span>&lt;&lt;</span></a></li>
                                    <li id="prePlan" class="prePage"><a href="#"><span>&lt;</span></a></li>
                                    <li id="curPlan" class="active curPage" page="1"><a href="#"><span>1</span></a></li>
                                    <li id="nextPlan" class="nextPage"><a href="#"><span>&gt;</span></a></li>
                                    <li id="lastPlan"><a href="#"><span>&gt;&gt;</span></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-pane" id="planCompletionRate">
                            <div id="completionRateContainer" style="min-width: 800px; height: 400px; margin: 0 auto"></div>
                            <table id="tablecompletionRate" class="table table-condensed">
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
