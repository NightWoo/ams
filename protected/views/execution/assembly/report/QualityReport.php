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
                        <li class="active"><a class="queryQualification VQ1" point="VQ1" href="#tabVQ1" data-toggle="tab"><span class="screenHide">1.&nbsp;</span>I线_VQ1</a></li>
                        <li class="notPrintable"><a class="queryQualification VQ1_2" point="VQ1_2" href="#tabVQ1_2" data-toggle="tab"><span class="screenHide">2.&nbsp;</span>II线_VQ1</a></li>
                        <li class="notPrintable"><a class="queryQualification VQ2_ROAD_TEST" point="VQ2_ROAD_TEST" href="#tabVQ2Road" data-toggle="tab"><span class="screenHide">3.&nbsp;</span>VQ2-路试</a></li>
                        <li class="notPrintable"><a class="queryQualification VQ2_LEAK_TEST" point="VQ2_LEAK_TEST" href="#tabVQ2Leak" data-toggle="tab"><span class="screenHide">4.&nbsp;</span>VQ2-淋雨</a></li>
                        <li class="notPrintable"><a class="queryQualification VQ3" point="VQ3" href="#tabVQ3" data-toggle="tab"><span class="screenHide">5.&nbsp;</span>VQ3</a></li>
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
                        <div class="tab-pane active tabQualification" point="VQ1" id="tabVQ1">
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
                                    <div class="divLoading" chart="column">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="faultsChart-VQ1" class="faultsChart" point="VQ1" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading" chart="donut">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="dutyChart-VQ1" class="dutyChart" point="VQ1" timespan="yearly"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabQualification" point="VQ1_2" id="tabVQ1_2">
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
                                    <div id="faultsChart-VQ1_2" class="faultsChart" point="VQ1_2" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="dutyChart-VQ1_2" class="dutyChart" point="VQ1_2" timespan="yearly"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabQualification" point="VQ2_ROAD_TEST" id="tabVQ2Road">
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading" timespan="monthly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div class="qualificationChart" point="VQ2_ROAD_TEST" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading" timespan="yearly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div class="qualificationChart" point="VQ2_ROAD_TEST" timespan="yearly"></div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="faultsChart-VQ2_ROAD_TEST" class="faultsChart" point="VQ2_ROAD_TEST" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="dutyChart-VQ2_ROAD_TEST" class="dutyChart" point="VQ2_ROAD_TEST" timespan="yearly"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabQualification" point="VQ2_LEAK_TEST" id="tabVQ2Leak">
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading" timespan="monthly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div class="qualificationChart" point="VQ2_LEAK_TEST" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading" timespan="yearly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div class="qualificationChart" point="VQ2_LEAK_TEST" timespan="yearly"></div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="faultsChart-VQ2_LEAK_TEST" class="faultsChart" point="VQ2_LEAK_TEST" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="dutyChart-VQ2_LEAK_TEST" class="dutyChart" point="VQ2_LEAK_TEST" timespan="yearly"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabQualification" point="VQ3" id="tabVQ3">
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading" timespan="monthly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div class="qualificationChart" point="VQ3" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading" timespan="yearly">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div class="qualificationChart" point="VQ3" timespan="yearly"></div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span8">
                                    <div class="divLoading">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="faultsChart-VQ3" class="faultsChart" point="VQ3" timespan="monthly"></div>
                                </div>
                                <div class="span4">
                                    <div class="divLoading">
                                        <span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                                    </div>
                                    <div id="dutyChart-VQ3" class="dutyChart" point="VQ3" timespan="yearly"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>
