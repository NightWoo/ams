<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>成本报表</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
        <link href="/bms/css/execution/assembly/report/CostReport.css" rel="stylesheet" media="screen">
        <link href="/bms/css/execution/assembly/report/CostReportPrint.css" rel="stylesheet" media="print">
        <link href="/bms/css/datetimepicker.css" rel="stylesheet" media="screen">

        <style type="text/css" media="screen">
            .printable{
                display: none;
            }
        </style>
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.zh-CN.js"></script>
        <script type="text/javascript" src="/bms/rjs/lib/jsrender.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/report/costReport.js"></script>
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
            // require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
            ?>

        	<div id="bodyright" class="offset2">
            <div style="width:820pt;margin: 0px auto;">
                <legend>成本报表
                    <span class="">
                        <input type="text" class="input-small"  placeholder="日期..." id="startTime"/>
                        <!-- <span class="seriesRadio">
                            <label class="radio inline"><input type="radio" name="seriesRadios" id="optionsRadiosAll" value="all" checked>全部</label>
                        </span> -->
                        <script id="tmplSeriesRadio" type="text/x-jsrander">
                            <label class='radio inline'><input type='radio' name='seriesRadios' id='optionsRadios{{:series}}' value='{{:series}}'>{{:name}}</label>
                        </script>
                    </span>
                    <span class="pull-right printable">
                        <div class="logo"><img src="/bms/img/byd-auto.jpg" alt="" ></div>
                    </span>
                    <span class="pull-right notPrintable">
                        <a href="/bms/execution/report?type=ManufactureReport"><i class="fa fa-link"></i>&nbsp;生产报表</a>
                        /
                        <a href="/bms/execution/report?type=QualityReport"><i class="fa fa-link"></i>&nbsp;质量报表</a>
                    </span>
                </legend>
            </div >
                <div style="width:820pt;margin: 0px auto;">
                    <ul id="tabUl" class="nav nav-pills">
                        <li class="active"><a class="queryReplacementCost" href="#tabReplacementCost" data-toggle="tab"><!-- <span class="screenHide">1.&nbsp;</span> -->换件成本</a></li>
                        <li class="notPrintable"><a class="print" href="#"><i class="fa fa-print"></i></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active tabReplacementCost" id="tabReplacementCost">
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading" timespan="monthly">
                                        <span><i class="fa fa-spinner fa-spin fa-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div class="replacementCostChart" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading" timespan="yearly">
                                        <span><i class="fa fa-spinner fa-spin fa-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div class="replacementCostChart" timespan="yearly"></div>
                                </div>
                            </div>
                            <div class="row-fluid row-2">
                                <div class="span7">
                                    <div class="divLoading" chart="column">
                                        <span><i class="fa fa-spinner fa-spin fa-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="dutyChart" class="dutyChart"></div>
                                </div>
                                <div class="span5">
                                    <div class="divLoading" chart="donut">
                                        <span><i class="fa fa-spinner fa-spin fa-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="dutyAreaChart" class="dutyAreaChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>
