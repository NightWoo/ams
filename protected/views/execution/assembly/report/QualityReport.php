<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>质量报表</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
        <link href="/bms/css/execution/assembly/report/QualityReport.css" rel="stylesheet" media="screen">
        <link href="/bms/css/execution/assembly/report/QualityReportPrint.css" rel="stylesheet" media="print">
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
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/report/qualityReport.js"></script>
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
            <div style="width:820pt;margin: 0px auto;">
                <legend>质量报表
                    <span class="">
                        <input type="text" class="input-small"  placeholder="日期..." id="startTime"/>
                    </span>
                    <span class="pull-right">
                        <div class="logo"><img src="/bms/img/byd-auto.jpg" alt="" ></div>
                    </span>
                </legend>
            </div >
                <div style="width:820pt;margin: 0px auto;">
                    <ul id="tabUl" class="nav nav-pills">
                        <li class="active"><a class="VQ1" href="#tabVQ1" data-toggle="tab"><span class="screenHide">1.&nbsp;</span>I线_VQ1</a></li>
                        <li class="notPrintable"><a class="VQ1_2" href="#tabVQ1_2" data-toggle="tab"><span class="screenHide">2.&nbsp;</span>I线_VQ1</a></li>
                        <li class="notPrintable"><a class="VQ2_ROAD_TEST" href="#tabVQ2Road" data-toggle="tab"><span class="screenHide">3.&nbsp;</span>VQ2-路试</a></li>
                        <li class="notPrintable"><a class="VQ2_LEAK_TEST" href="#tabVQ2Leak" data-toggle="tab"><span class="screenHide">4.&nbsp;</span>VQ2-淋雨</a></li>
                        <li class="notPrintable"><a class="VQ3" href="#tabVQ3" data-toggle="tab"><span class="screenHide">5.&nbsp;</span>VQ3</a></li>
                        <li class="notPrintable"><a class="print" href="#"><i class="icon-print"></i></a></li>
                        <!-- <li class="dropdown pull-right notPrintable">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                月明细
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="exportCars" point='assembly' timespan='monthly'>上线</a></li>
                                <li><a class="exportCars" point='finish' timespan='monthly'>下线</a></li>
                                <li><a class="exportCars" point='warehouse' timespan='monthly'>入库</a></li>
                                <li><a class="exportCars" point='distribute' timespan='monthly'>出库</a></li>
                            </ul>
                        </li>
                        <li class="dropdown pull-right notPrintable">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                日明细
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="exportCars" point='assembly' timespan='daily'>上线</a></li>
                                <li><a class="exportCars" point='finish' timespan='daily'>下线</a></li>
                                <li><a class="exportCars" point='warehouse' timespan='daily'>入库</a></li>
                                <li><a class="exportCars" point='distribute' timespan='daily'>出库</a></li>
                            </ul>
                        </li> -->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabVQ1">
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading" timespan="monthly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div class="qualificationChart" point="VQ1" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading" timespan="yearly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div class="qualificationChart" point="VQ1" timespan="yearly"></div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="faultsChart-VQ1" class="faultsChart" point="VQ1" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="dutyChart-VQ1" class="dutyChart" point="VQ1" timespan="yearly"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane active" id="tabVQ1_2">
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading" timespan="monthly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div class="qualificationChart" point="VQ1_2" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading" timespan="yearly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div class="qualificationChart" point="VQ1_2" timespan="yearly"></div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="faultsChart-VQ1" class="faultsChart" point="VQ1_2" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="dutyChart-VQ1" class="dutyChart" point="VQ1_2" timespan="yearly"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane printable" id="tabVQ2Road">
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading" timespan="monthly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="useMonthlyChart" class="useChart" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading" timespan="yearly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="useYearlyChart" class="useChart" timespan="yearly"></div>
                                </div>
                            </div>
                            <div>
                                <table id="tablePause" class="table table-condensed table-bordered tablePause" style="display: none;">
                                    <thead>
                                        <tr>
                                            <!-- <th>ID</th> -->
                                            <th class="thType">停线类型</th>
                                            <th class="thDuty">责任部门</th>
                                            <th class="thReason">原因</th>
                                            <th class="thHowlong alignRight">总时长</th>
                                            <th class="thHowlong alignRight">时长</th>
                                            <th class="thPauseTime">停线时刻</th>
                                            <th class="thRecoverTime">恢复时刻</th>
                                            <th class="thSeat">工位</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabVQ2Leak">
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading" timespan="monthly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="recycleMonthlyChart" class="recycleChart" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading" timespan="yearly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="recycleYearlyChart" class="recycleChart" timespan="yearly"></div>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <table id="overtimeCarsTable" class="table table-bordered table-condensed initHide overtimeCarsTable">
                                        <thead>
                                            <tr>
                                                <th style="min-width:60px">生产周期</th>
                                                <th style="min-width:40px">车系</th>
                                                <th>流水号</th>
                                                <th>VIN号</th>
                                                <th style="min-width:160px">配置</th>
                                                <th style="min-width:60px">颜色</th>
                                                <th style="min-width:60px">状态</th>
                                                <th>备注</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabVQ3">
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading" timespan="monthly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="warehouseMonthlyChart" class="warehouseChart" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading" timespan="yearly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="warehouseYearlyChart" class="warehouseChart" timespan="yearly"></div>
                                </div>
                            </div>
                            <div>
                                <table id="overtimeOrdersTable" class="table table-condensed overtimeOrdersTable" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>备板编号</th>
                                            <th style="min-width:40px">车道</th>
                                            <th>订单号</th>
                                            <!-- <th style="min-width:160px">经销商</th> -->
                                            <th style="min-width:40px">车系</th>
                                            <th style="min-width:160px">车型/配置/耐寒性</th>
                                            <th style="min-width:60px">颜色</th>
                                            <th style="min-width:30px">数量</th>
                                            <th style="min-width:30px">已备</th>
                                            <th style="min-width:30px">出库</th>
                                            <th style="min-width:120px">指令激活</th>
                                            <th><i class="icon-time"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>
