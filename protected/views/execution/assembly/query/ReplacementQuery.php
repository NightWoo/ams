<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>换件查询</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
        <link href="/bms/css/flick/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
        <link href="/bms/css/jquery-ui-timepicker-addon.css" rel="stylesheet">
        <style type="text/css">
        .well {
            margin-bottom: 10px;
        }
        .checkbox{
            margin-left: 5px;
        }
        #tabs {
            margin-bottom: 10px;
        }
        #tableDetail {
            font-size: 12px;
        }
        </style>		
        <script data-main="/bms/rjs/replacementQuery.js" src="/bms/rjs/lib/require.js"></script>
	</head>


	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
        <div class="offhead">
        	<!-- Main体 -->		
            <div id="bodyright">
                <div>
                    <legend>换件查询
                        <span class="pull-right">
                            <!-- <a href="/bms/execution/orderMaintain"><i class="icon-link"></i>&nbsp;订单维护</a> -->
                        </span>
                    </legend>
                </div>
                <form class="well form-inline">
                        <div class="input-append input-prepend" style="clear: right">
                            <span class="add-on">时间</span>
                            <input type="text" class="span2" placeholder="请开始时间..." id="startTime"/>
                            <span class="add-on" style="padding:4px 0">-</span>
                            <input type="text" class="span2" placeholder="请结束时间..." id="endTime" style="border-radius: 0 4px 4px 0;"/>
                            <!-- <a class="btn clearinput appendBtn"><i class="icon-remove"></i></a> -->
                        </div>
                        <div class="input-prepend">
                            <span class="add-on">线别</span>
                            <select id="line" class="input-small">
                                <option value="" selected>全部</option>
                                <option value="I">I线</option>
                                <option value="II">II线</option>
                            </select>
                        </div>
                        <div class="input-prepend">
                            <span class="add-on">责任</span>
                            <select id="dutyDepartment" class="input-small">
                                <option value='0' selected>全部</option>
                            </select>
                        </div>
                        <label class="checkbox"><input type="checkbox" id="checkboxF0" value="F0">F0</input></label>
                        <label class="checkbox"><input type="checkbox" id="checkboxM6" value="M6">M6</input></label>
                        <label class="checkbox"><input type="checkbox" id="checkbox6B" value="6B">思锐</input></label>
                </form>
                <div>
                    <ul id="tabs" class="nav nav-pills">
                        <li><a href="#tabDetail" data-toggle="tab">明细报表</a></li>
                        <li><a href="#tabCostTrend" data-toggle="tab">成本趋势</a></li>
                        <li><a href="#tabCostDuty" data-toggle="tab">换件责任</a></li>
                        <div id="paginationDetail" class="pagination pagination-small pagination-right" style="display: none;">
                            <ul>
                                <li id="exportDetail"><a href="#"><span id="totalDetail"></span></a></li>
                            </ul>
                            <ul>
                                <li id="firstDetail"><a href="#">&laquo;</a></li>
                                <li id="preDetail" class="prePage"><a href="#">&lt;</a></li>
                                <li id="curDetail" class="active curPage" page="1"><a href="#">1</a></li>
                                <li id="nextDetail" class="nextPage"><a href="#">&gt;</a></li>
                                <li id="lastDetail"><a href="#">&raquo;</a></li>
                            </ul>
                        </div>
                    </ul>
                </div>
                <div id="tabContent" class="tab-content">
                    <div class="tab-pane" id="tabDetail">
                        <table id="tableDetail" class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>线别</th>
                                	<th>车系</th>
                                    <th>VIN</th>
                                    <th>零部件编号</th>
                                    <th>零部件名称</th>
                                    <th>供应商</th>
                                    <th>连带损</th>
                                    <th>责任部门</th>
                                    <th>换件故障</th>
                                    <th>区域</th>
                                    <th>换件时间</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <script id="tmplReplacementDetail" type="text/x-jsrander">
                                <tr>
                                    <td>{{:assembly_line}}</td>
                                    <td>{{:series}}</td>
                                    <td>{{:vin}}</td>
                                    <td>{{:component_code}}</td>
                                    <td>{{:component_name}}</td>
                                    <td>{{:provider_name}}</td>
                                    <td>
                                        {{if is_collateral==1}}是
                                        {{else}}否
                                        {{/if}}
                                    </td>
                                    <td>{{:duty_department_name}}</td>
                                    <td>{{:fault_component_name}}{{:fault_mode}}</td>
                                    <td>{{:duty_area}}</td>
                                    <td>{{:replace_time}}</td>
                                </tr>
                            </script>
                        </table>
                    </div>

                    <div class="tab-pane" id="tabCostTrend">
                        <div id="costTrendContainer" style="min-width: 800px; height: 400px; margin: 0 auto"></div>
                        <table id="tableCostTrend" class="table table-condensed table-bordered">
                            <thead />
                            <tbody />
                        </table>
                    </div>

                    <div class="tab-pane" id="tabCostDuty">
                        <div id="costDutyPlatoContainer" style="min-width: 400px; height: 400px; margin: 0 auto">
                        </div>
                        <table id="tablecostDutyPlato" class="table table-condensed table-bordered">
                            <thead />
                            <tbody />
                        </table>
                    </div>
                </div>
            </div><!-- bodyright -->
        </div><!-- offhead -->
    </body>
</html>
