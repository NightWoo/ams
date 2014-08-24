<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>四大工艺完成</title>
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
        </style>
	</head>
	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
		<div class="offhead">
			<div class="container">
				<legend>计划处报表-四大工艺完成情况
                    <span class="pull-right">
                        <a class="notPrintable" href="/bms/execution/report?type=PlanningDivisionReport"><i class="fa fa-link"></i>&nbsp;日报</a>
                        /
                        <a class="notPrintable" href="/bms/execution/report?type=PlanningDivisionNewOrder"><i class="fa fa-link"></i>&nbsp;新增订单</a>
                        /
                        <a class="notPrintable" href="/bms/execution/report?type=PlanningDivisionSalesVolume"><i class="fa fa-link"></i>&nbsp;终端销量</a>
                    </span>
                </legend>
            	<div id="timeQueryInputDiv" class="input-append input-prepend">
                    <span class="add-on">日期</span>
                    <input type="text" class="input-small"  placeholder="起始日期..." id="startTime"/>
                    <span class="add-on" style="padding:4px 0">-</span>
                    <input type="text" class="input-small"  placeholder="结束日期..." id="endTime" style="border-radius: 0 4px 4px 0;"/>
                </div>
                <!-- <div class="input-prepend">
                    <span class="add-on">车系</span>
                    <select type="text" class="input-small" id="seriesSelect"/></select>
                </div> -->
                <div class="input-prepend">
                    <span class="add-on">工序</span>
                    <select type="text" id="nodeSelect" style="width:120px">
                        <option value="" selected>全部</option>}
                        <option value="" disabled>焊装上线</option>}
                        <option value="" disabled>前处理</option>}
                        <option value="" disabled>面漆</option>}
                        <option value="pbs">彩车身库</option>}
                        <option value="assembly">总装上线</option>}
                        <option value="warehouse">入库</option>}
                        <option value="distribute">出库</option>}
                    </select>
                </div>
                <input type="button" class="btn btn-primary" id="btnQuery" value="查询"></input>
                <div>
                    <ul id="tabs" class="nav nav-pills">
                        <li class="active"><a href="#retTable" data-toggle="tab"><i class="fa fa-table"></i></a></li>
                        <li><a href="#retChart" data-toggle="tab"><i class="fa fa-bar-chart-o"></i></a></li>
                    </ul>
                </div>
                <div  id="tabContent" class="tab-content">
                    <div class="tab-pane active" id="retTable">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:40px">车系</th>
                                    <th style="width:80px">工序</th>
                                    <th style="width:60px">01-01</th>
                                    <th style="width:60px">01-02</th>
                                    <th style="width:60px">01-03</th>
                                    <th style="width:60px">01-04</th>
                                    <th style="width:60px">01-05</th>
                                    <th style="width:60px">01-06</th>
                                    <th style="width:60px">01-07</th>
                                    <th style="width:60px">01-08</th>
                                    <th style="width:60px">01-09</th>
                                    <th style="width:60px">01-10</th>
                                    <th style="width:60px">01-11</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th rowspan="7">F0</th>
                                    <th>焊装上线</th>
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
                                    <th>前处理</th>
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
                                    <th>面漆</th>
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
                                    <th>彩车身库</th>
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
                                    <th>上线</th>
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
                                    <th>入库</th>
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
                                    <th>发车</th>
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
                                    <th rowspan="7">M6</th>
                                    <th>焊装上线</th>
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
                                    <th>前处理</th>
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
                                    <th>面漆</th>
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
                                    <th>彩车身库</th>
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
                                    <th>上线</th>
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
                                    <th>入库</th>
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
                                    <th>发车</th>
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
                                    <th rowspan="7">思锐</th>
                                    <th>焊装上线</th>
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
                                    <th>前处理</th>
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
                                    <th>面漆</th>
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
                                    <th>彩车身库</th>
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
                                    <th>上线</th>
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
                                    <th>入库</th>
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
                                    <th>发车</th>
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
                    <div class="tab-pane" id="retChart">
                        <h3>这是趋势图</h3>
                    </div>
                </div>
			</div>
		</div>
	</body>
	<script data-main="/bms/rjs/planningDivisionFourTech.js" src="/bms/rjs/lib/require.js"></script>
</html>