<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
        <meta http-equiv="cache-control" content="no-cache, must-revalidate">
        <meta http-equiv="expires" content="0" />
		<title>BMS.Ver0.1</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet"  media="screen">
    <link href="/bms/css/common.css" rel="stylesheet"  media="screen">
    <link rel="stylesheet" type="text/css" href="/bms/css/subPrintable.css" media="print">
    <style type="text/css" media="screen">
        .printable{
            display: none;
        }
        #componentTable {
            margin-bottom: 0;
            margin-top: 10px;
        }

        #formVIN {
            margin-bottom: 10px;
        }

        #formBarCode {
            margin-bottom: 10px;
            display: none;
        }

        #checkAlert .alert {
            margin-top: 5px;
            margin-bottom: 0;
        }
    </style>
    <style type="text/css" media="print">
        td,th{border:none;}
    </style>
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/common.js"></script>
    <script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/sub.js"></script>
	</head>
	<body>
        <div class="notPrintable">
    		<?php
                    require_once(dirname(__FILE__)."/../../../common/head.php");
                ?>
            <div class="offhead">
                <div id="bodyright" class="offset2"><!-- 页体 -->
                    <div>
                        <legend><?php echo $nodeDisplayName;?>
                        </legend>
                    </div>
           	   		<div><!-- 主体 -->
        				<div>
                   	  		<form id="formVIN" class="well form-search">
                                <div>
                                    <input type="hidden" id='currentNode' name='currentNode' value='<?php echo $node?>'></input>
									<input type="hidden" id='subType' name='subType' value='<?php echo $type;?>'></input>

                       	  			<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VIN</label>
                                    <input id="vinText" type="text" placeholder="请扫描/输入VIN..." value="">
                                    <button id="btnSubmit" type="submit" class="btn btn-primary" style="margin-left: 10px;"><i class="fa fa-print"></i>&nbsp;打印</button>
                                    <button id="btnTopOut" type="submit" class="btn btn-info"><i class="fa fa-tag"></i>&nbsp;顶出</button>
                                     
                                    <button id="btnClear" type="reset" class="btn btn-danger"><i class="fa fa-repeat"></i>&nbsp;指定</button>
                              		<span class="help-inline" id="vinHint"></span>
                                    <div class="help-inline" id="carInfo">
                                        <span class="label label-info" rel="tooltip" title="车系" id="infoSeries">x</span>
                                        <span class="label label-info" rel="tooltip" title="车型" id="infoType">x</span>
                                        <span class="label label-info" rel="tooltip" title="颜色" id="infoColor">x</span>
                                        <!-- <span class="label label-info" rel="tooltip" title="耐寒性" id="infoColdResistant">x</span> -->
                                        <span class="label label-info" rel="tooltip" title="车辆区域" id="infoStatus">x</span>
                                    </div>
                                </div>
                                <div style="margin-top:10px;">
                                    <label>T0时间</label>
                                    <input type="text" class="span3" placeholder="请输入开始时间..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:mm'});"/>
                                    <span>-</span>
                                    <input type="text" class="span3" placeholder="请输入结束时间..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:mm'});"/>
                                    <button id="btnRefresh" type="button" class="btn btn-success"><i class="fa fa-refresh"></i>&nbsp;刷新</button>
                                    <span class="help-inline">如需打印指定车辆配置单，请清空</span>
                                </div>
                            </form>
                            <form id="formBarCode" class="well form-search">
                                <div class="input-prepend">
                                    <span class="add-on" id="barcodeLabel"><i class="icon-barcode"></i></span>
                                    <input id="compCodeText" type="text" class="span3" placeholder="请扫描/输入条码...">
                                </div>
                                    <table class="table table-striped table-condensed" id="componentTable">
                                        <tbody>
                                          
                                        </tbody>
                                    </table>
                                <div id="checkAlert"></div>
                            </form>                     
                        </div>
                        
                        <div id="messageAlert" class="alert alert-success">LGXC16DGXC1234666仪表分装配置单已打印，请输入下一辆车VIN</div>

                        <div class="accordion" id="accordionQueue">
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a id="queueViewToggle" class="accordion-toggle" 
                                    data-toggle="collapse" data-parent="#accordionQueue" href="#collapseQueue"><span id="today"></span>待上分装线[<span id="infoCount"></span><span>]</span></a>
                                </div>
                                <div id="collapseQueue" class="accordion-body collapse">
                                    <div class="accordion-inner" id="queueDiv">
                                        <table id="tableList" class="table table-condensed">
                                            <thead>
                                                <tr class="active">
                                                    <th class="">车系</th>
                                                    <th class="">整车编号</th>
                                                    <th class="">T0时间</th>
                                                    <th class="">VIN号</th>
                                                    <th class="">车型/配置</th>
                                                    <th class="">耐寒性</th>
                                                    <th class="">颜色</th>
                                                    <th class="">特殊单号</th>
                                                    <th class="">备注</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a id="queueDoneToggle" class="accordion-toggle" 
                                    data-toggle="collapse" data-parent="#accordionQueue" href="#collapseQueueDone">已上分装线，未装车</a>
                                </div>
                                <div id="collapseQueueDone" class="accordion-body collapse">
                                    <div class="accordion-inner" id="queueDoneDiv">
                                        <table id="tableDoneList" class="table table-condensed table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="">车系</th>
                                                    <th class="">整车编号</th>
                                                    <th class="">T0时间</th>
                                                    <th class="">VIN号</th>
                                                    <th class="">车型/配置</th>
                                                    <th class="">耐寒性</th>
                                                    <th class="">颜色</th>
                                                    <th class="">特殊单号</th>
                                                    <th class="">备注</th>
                                                    <th class="">发动机号</th>
                                                    <th class="">打印</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>                      
                        </div>
        				
        		  	</div><!-- end of 主体 -->
                </div><!-- end of 页体 -->
            </div>
       	</div><!-- offhead -->

	    <div class="printable" style="width:560pt;height:800pt; padding-top:10pt; font-size:18pt">
            <table class="" style="width:100%; margin-top:10pt;">
                <tr>
                    <td width="40%" style="padding-left:10pt"><img src="" class="printBarCode" width="80%"></td>
                    <td width="50%" class="printType" style="font-size:14pt"></td>
                    <td style="text-align:right; font-size:14pt; width=10%; padding-right:10pt" class="printSeries"></td>
                </tr>
                <tr>
                    <td class="printEngineCode" style="font-size:14pt; padding-left:1px;"></td>
                    <td class="printConfig" style="font-size:14pt"></td>
                    <td style="text-align:right; font-size:14pt; padding-right:10pt" class="printSerialNumber"></td>
                </tr>
            </table>
            <img src="" width="" height="" class="printFrontImage" style="display: block; margin:0 auto">
            <table style="width:100%;margin-top:10pt;">
                <tr>
                    <td class="printRemark" style="font-size:12pt; padding-left:10pt"></td>
                    <!-- <td align="right" style="text-align:right; font-size:18pt; padding-right:10pt">1/2</td> -->
                </tr>
            </table>
        </div>
	</body>
</html>
