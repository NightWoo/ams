<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>终端销量</title>
		<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
		<link href="/bms/css/datetimepicker.css" rel="stylesheet" media="screen">
        <style type="text/css" media="screen">
            #btnQuery {
                margin-bottom: 10px;
            }
            .nav {
                margin-bottom: 10px;
            }
            .tabs-left > .nav-tabs > li > a {
                min-width: 30px
            }
        </style>
	</head>
	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
		<div class="offhead">
			<div class="container">
				<legend>计划处报表-终端销量
                    <span class="pull-right">
                        <a class="notPrintable" href="/bms/execution/report?type=PlanningDivisionReport"><i class="fa fa-link"></i>&nbsp;日报</a>
                        /
                        <a class="notPrintable" href="/bms/execution/report?type=PlanningDivisionFourTech"><i class="fa fa-link"></i>&nbsp;四大工艺完成</a>
                        /
                        <a class="notPrintable" href="/bms/execution/report?type=PlanningDivisionNewOrder"><i class="fa fa-link"></i>&nbsp;新增订单</a>
                    </span>
                </legend>
            	<div id="timeQueryInputDiv" class="input-append input-prepend">
                    <span class="add-on">日期</span>
                    <input type="text" class="input-small"  placeholder="起始日期..." id="startTime"/>
                    <span class="add-on" style="padding:4px 0">-</span>
                    <input type="text" class="input-small"  placeholder="结束日期..." id="endTime" style="border-radius: 0 4px 4px 0;"/>
                </div>
                <div class="input-prepend">
                    <span class="add-on">车系</span>
                    <select type="text" class="input-small" id="seriesSelect">
                        <option value="F0">F0</option>
                        <option value="M6">M6</option>
                        <option value="6B">思锐</option>
                    </select>
                </div>
                <input type="button" class="btn btn-primary" id="btnQuery" value="查询"></input>
                <div>
                    <ul id="tabs" class="nav nav-pills">
                        <li class="active"><a href="#retTable" data-toggle="tab"><i class="fa fa-table"></i></a></li>
                        <li><a href="#retChart" data-toggle="tab"><i class="fa fa-bar-chart-o"></i></a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="retTable">
                        <div class="row">
                            <div class="span5">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="min-width:180px">配置</th>
                                            <th style="width:70px">渠道库存</th>
                                            <th style="width:70px">销量</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>1.0L实用/非耐寒</th>
                                            <td>0 / 0%</td>
                                            <td>0 / 0%</td>
                                        </tr>
                                        <tr>
                                            <th>1.0L实用/北京欧五</th>
                                            <td>0 / 0%</td>
                                            <td>0 / 0%</td>
                                        </tr>
                                        <tr>
                                            <th>1.0L实用/耐寒</th>
                                            <td>0 / 0%</td>
                                            <td>0 / 0%</td>
                                        </tr>
                                        <tr>
                                            <th>1.0L实用/助力/耐寒</th>
                                            <td>0 / 0%</td>
                                            <td>0 / 0%</td>
                                        </tr>
                                        <tr>
                                            <th>1.0L舒适/非耐寒</th>
                                            <td>0 / 0%</td>
                                            <td>0 / 0%</td>
                                        </tr>
                                        <tr>
                                            <th>1.0L舒适/北京欧五</th>
                                            <td>0 / 0%</td>
                                            <td>0 / 0%</td>
                                        </tr>
                                        <tr>
                                            <th>1.0L舒适/耐寒</th>
                                            <td>0 / 0%</td>
                                            <td>0 / 0%</td>
                                        </tr>
                                        <tr>
                                            <th>1.0L尊贵</th>
                                            <td>0 / 0%</td>
                                            <td>0 / 0%</td>
                                        </tr>
                                        <tr>
                                            <th>1.0L实用/助力/AMT</th>
                                            <td>0 / 0%</td>
                                            <td>0 / 0%</td>
                                        </tr>
                                        <tr>
                                            <th>1.0L舒适/AMT</th>
                                            <td>0 / 0%</td>
                                            <td>0 / 0%</td>
                                        </tr>
                                        <tr>
                                            <th>合计</th>
                                            <td>0 / 0%</td>
                                            <td>0 / 0%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="span7" style="overflow-x: scroll; margin-left:-1px">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="min-width:60px">01-01</th>
                                            <th style="min-width:60px">01-02</th>
                                            <th style="min-width:60px">01-03</th>
                                            <th style="min-width:60px">01-04</th>
                                            <th style="min-width:60px">01-05</th>
                                            <th style="min-width:60px">01-06</th>
                                            <th style="min-width:60px">01-07</th>
                                            <th style="min-width:60px">01-08</th>
                                            <th style="min-width:60px">01-09</th>
                                            <th style="min-width:60px">01-10</th>
                                            <th style="min-width:60px">01-11</th>
                                            <th style="min-width:60px">01-12</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <tr>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="retChart">
                        <div class="row">
                            <div class="span6">
                                <div class="input-prepend">
                                    <span class="add-on">配置</span>
                                    <select type="text" class="input-small configSelect"></select>
                                </div>
                                <div>
                                    <h3>这是一个按颜色分布的饼图</h3>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="input-prepend">
                                    <span class="add-on">颜色</span>
                                    <select type="text" class="input-small configSelect"></select>
                                </div>
                                <div>
                                    <h3>这是一个按颜色分布的饼图</h3>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
			</div>
		</div>
	</body>
	<script data-main="/bms/rjs/planningDivisionSalesVolume.js" src="/bms/rjs/lib/require.js"></script>
</html>