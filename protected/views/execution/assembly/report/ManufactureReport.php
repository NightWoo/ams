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
                <!-- <div>
                    <legend>生产报表
                        <span class="">
                        </span>
                    </legend>
                </div> -->
                <div class="tabbable"> <!-- Only required for left/right tabs -->
                    <ul class="nav nav-tabs">
                        <li id="headText">生产报表</li>
                        <li class="active"><a href="#daily" data-toggle="tab">日</a></li>
                        <li><a href="#monthly" data-toggle="tab">月</a></li>
                        <li><a href="#yearly" data-toggle="tab">年</a></li>
                        <li>
                            <input type="text" class="input-small"  placeholder="日期..." id="startTime"/>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="daily">
                            <ul class="nav nav-pills">
                                <li id="queryManufactureDaily"><a href="#manufactureDaily" data-toggle="tab">生产日报</a></li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                        导出
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="exportCars" point='assembly' timespan='daily'>上线明细</a></li>
                                        <li><a class="exportCars" point='finish' timespan='daily'>下线明细</a></li>
                                        <li><a class="exportCars" point='warehouse' timespan='daily'>入库明细</a></li>
                                        <li><a class="exportCars" point='distribute' timespan='daily'>出库明细</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane container" id="manufactureDaily">
                                    <div class="row-fluid">
                                        <div id="manufacureDailyColumnContainer" class="manufactureDailyChart span6"></div>
                                        <div id="recycleDonutContainer" class="manufactureDailyChart span6"></div>
                                    </div>
                                    <div>
                                        <table id="manufactureDailyTable" class="table table-bordered initHide">
                                            <thead></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="monthly">
                            <ul class="nav nav-pills">
                                <li><a class="queryCompletion" href="#completionMonthly" data-toggle="tab" timespan='monthly'>计划完成</a></li>
                                <li class="disabled"><a href="#manufactureUseMonthly" timespan='monthly'>生产利用</a></li>
                                <li class="disabled"><a href="#warehouseInMonthly" timespan='monthly'>入库</a></li>
                                <li class="disabled"><a href="#warehouseOutMonthly" timespan='monthly'>出库</a></li>
                                <li class="disabled"><a href="#distributePeriodMonthly" timespan='monthly'>发车周期</a></li>
                                <li class="dropdown">
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
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="completionMonthly">
                                    <div class="">
                                        <div id="completionMonthlyChart" class="completionChart" timespan="monthly"></div>
                                    </div>
                                    <div>
                                        <table id="completionMonthlyTable" class="table table-bordered table-condensed initHide completionTable" timespan="monthly">
                                            <thead></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="manufactureUseMonthly">
                                    <p>月生产利用</p>
                                </div>
                                <div class="tab-pane" id="warehouseInMonthly">
                                    <p>月入库</p>
                                </div>
                                <div class="tab-pane" id="warehouseOutMonthly">
                                    <p>月出库</p>
                                </div>
                                <div class="tab-pane" id="distributePeriodMonthly">
                                    <p>月发车周期</p>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="yearly">
                            <ul class="nav nav-pills">
                                <li><a class="queryCompletion" href="#completionYearly" data-toggle="tab" timespan='yearly'>计划完成</a></li>
                                <li class="disabled"><a href="#manufactureUseYearly" timespan='yearly'>生产利用</a></li>
                                <li class="disabled"><a href="#warehouseInYearly" timespan='yearly'>入库</a></li>
                                <li class="disabled"><a href="#warehouseOutYearly" timespan='yearly'>出库</a></li>
                                <li class="disabled"><a href="#distributePeriodYearly" timespan='yearly'>发车周期</a></li>
                                <!-- <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                        导出
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="exportCars" point='assembly' timespan='yearly'>上线明细</a></li>
                                        <li><a class="exportCars" point='finish' timespan='yearly'>下线明细</a></li>
                                        <li><a class="exportCars" point='warehouse' timespan='yearly'>入库明细</a></li>
                                        <li><a class="exportCars" point='distribute' timespan='yearly'>出库明细</a></li>
                                    </ul>
                                </li> -->
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane" id="completionYearly">
                                    <div class="">
                                        <div id="completionYearlyChart" class="completionChart" timespan="yearly"></div>
                                    </div>
                                    <div>
                                        <table id="completionYearlyTable" class="table table-bordered table-condensed initHide completionTable" timespan="yearly">
                                            <thead></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="manufactureUseYearly">
                                    <p>年生产利用</p>
                                </div>
                                <div class="tab-pane" id="warehouseInYearly">
                                    <p>年入库</p>
                                </div>
                                <div class="tab-pane" id="warehouseOutYearly">
                                    <p>年出库</p>
                                </div>
                                <div class="tab-pane" id="distributePeriodYearly">
                                    <p>年发车周期</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>
