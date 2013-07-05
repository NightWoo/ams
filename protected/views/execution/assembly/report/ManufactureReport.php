<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>生产报表</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/report/ManufactureReport.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
        <link href="/bms/css/datetimepicker.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.zh-CN.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/report/manufactureReport.js"></script>
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
                <legend>生产报表
                    <span class="">
                        <input type="text" class="input-small"  placeholder="日期..." id="startTime"/>
                    </span>
                </legend>
            </div>
                <div>
                    <ul class="nav nav-pills">
                        <li class="active"><a class="queryCompletion" href="#tabCompletion" data-toggle="tab">计划完成</a></li>
                        <li><a class="queryUse" href="#tabManufactureUse" data-toggle="tab">生产利用</a></li>
                        <li><a class="queryRecycle" href="#tabRecycle" data-toggle="tab">周转车</a></li>
                        <li class="disabled"><a href="#tabWarehouse" timespan='monthly'>成品库发车</a></li>
                        <!-- <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                导出
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="exportCars" point='assembly' timespan='monthly'>上线明细</a></li>
                                <li><a class="exportCars" point='finish' timespan='monthly'>下线明细</a></li>
                                <li><a class="exportCars" point='warehouse' timespan='monthly'>入库明细</a></li>
                                <li><a class="exportCars" point='distribute' timespan='monthly'>出库明细</a></li>
                            </ul>
                        </li> -->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabCompletion">
                            <div class="row-fluid">
                                <div class="span8">
                                    <div id="completionMonthlyChart" class="completionChart" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div id="completionYearlyChart" class="completionChart" timespan="yearly"></div>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <table id="manufactureDailyTable" class="table table-bordered table-condensed initHide">
                                        <thead></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabManufactureUse">
                            <div class="row-fluid">
                                <div class="span8">
                                    <div id="useMonthlyChart" class="useChart" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div id="useYearlyChart" class="useChart" timespan="yearly"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabRecycle">
                            <div class="row-fluid">
                                <div class="span8">
                                    <div id="recycleMonthlyChart" class="recycleChart" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div id="recycleYearlyChart" class="recycleChart" timespan="yearly"></div>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <table id="overtimeCarsTable" class="table table-bordered table-condensed initHide">
                                        <thead>
                                            <tr>
                                                <th style="min-width:40px">车系</th>
                                                <th>流水号</th>
                                                <th>VIN号</th>
                                                <th style="min-width:200px">配置</th>
                                                <th style="min-width:60px">颜色</th>
                                                <th style="min-width:80px">状态</th>
                                                <th>故障</th>
                                                <th style="min-width:60px">周转周期</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabWarehouse">
                            <p>成品库</p>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
	</body>
</html>
