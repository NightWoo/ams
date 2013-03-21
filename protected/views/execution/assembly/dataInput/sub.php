<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>BMS.Ver0.1</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet"  media="screen">
    <link href="/bms/css/common.css" rel="stylesheet"  media="screen">
    <link rel="stylesheet" type="text/css" href="/bms/css/subPrintable.css" media="print">
    <style type="text/css" media="screen">
        .printable{
            display: none;
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
                <?php
                    require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
                ?>
     
		
                <div id="bodyright" class="offset2"><!-- 页体 -->
                    <div><!-- breadcrumb -->
                    	<ul class="breadcrumb">
                    		<li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
                            <li><a href="/bms/execution/home">总装</a><span class="divider">&gt;</span></li>
                            <li><a href="child?node=NodeSelect">数据录入</a><span class="divider">&gt;</span></li>
                        	<li class="active"><?php echo $nodeDisplayName;?></li>                
                    	</ul>
                    </div><!-- end of breadcrumb -->
                    
           	   		<div><!-- 主体 -->
        				<div>
                   	  		<form id="formVIN" class="well form-search">
                                <div>
                                    <input type="hidden" id='currentNode' name='currentNode' value='<?php echo $node?>'></input>
									<input type="hidden" id='subType' name='subType' value='<?php echo $type;?>'></input>

                       	  			<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VIN</label>
                                    <input id="vinText" type="text" placeholder="请扫描/输入VIN..." value="">
                                    <button id="btnSubmit" type="submit" class="btn btn-primary" style="margin-left: 10px;"><i class="icon-print icon-white"></i>&nbsp;打印</button>
                                    <button id="btnTopOut" type="submit" class="btn btn-info"><i class="icon-tag"></i>&nbsp;顶出</button>
                                     
                                    <button id="btnClear" type="reset" class="btn btn-danger"><i class="icon-repeat icon-white"></i>&nbsp;清空</button>
                              		<span class="help-inline" id="vinHint">如需打印指定车辆配置单，请清空</span>
                                    <div class="help-inline" id="carInfo">
                                        <span class="label label-info" rel="tooltip" title="车系" id="infoSeries">x</span>
                                        <span class="label label-info" rel="tooltip" title="车型" id="infoType">x</span>
                                        <span class="label label-info" rel="tooltip" title="颜色" id="infoColor">x</span>
                                        <!-- <span class="label label-info" rel="tooltip" title="耐寒性" id="infoColdResistant">x</span> -->
                                        <span class="label label-info" rel="tooltip" title="车辆区域" id="infoStatus">x</span>
                                    </div>
                                </div>
                                <div style="margin-top:10px;">
                                    <label>列队时间</label>
                                    <input type="text" class="span3" placeholder="请输入开始时间..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:mm'});"/>
                                    <span>-</span>
                                    <input type="text" class="span3" placeholder="请输入结束时间..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:mm'});"/>
                                    <button id="btnRefresh" type="button" class="btn btn-success"><i class="icon-refresh icon-white"></i>&nbsp;刷新</button>
                                </div>
                            </form>                       
                        </div>
                        
                        <div id="messageAlert" class="alert alert-success">LGXC16DGXC1234666仪表分装配置单已打印，请输入下一辆车VIN</div>

        				<table id="tableList" class="table table-condensed">
                            <thead>
                                <tr class="active">
                                    <th class="">整车编号</th>
                                    <th class="">列队时间</th>
                                    <th class="">VIN号</th>
                                    <th class="">车系</th>
                                    <th class="">车型/配置</th>
                                    <th class="">耐寒性</th>
                                    <th class="">颜色</th>
                                    <!-- <th class="">年份</th> -->
                                    <th class="">特殊单号</th>
                                    <th class="">备注</th>
                                </tr>
                            </thead>
                            <tbody>
                               <!--  <tr id="row0" class="info">
                                    <td>C12356</td>
                                    <td>F0</td>
                                    <td>LGXC16DG4C1234564</td>
                                    <td>QCJ7100L（1.0排量尊舒适型）</td>
                                    <td>德兰黑</td>
                                    <td>2012</td>
                                    <td>1.0舒适标准</td>
                                    <td>-</td>
                                    <td>北京，耐寒型，QA-5</td>                                    
                                </tr>
                                <tr>
                                    <td>C123457</td>
                                    <td>F0</td>
                                    <td>LGXC16DG7C1654321</td>
                                    <td>QCJ7100L（1.0排量尊舒适型）</td>
                                    <td>德兰黑</td>
                                    <td>2012</td>
                                    <td>1.0舒适标准</td>
                                    <td>-</td>
                                    <td>QA-5</td>                                   
                                </tr>          -->                                      
                            </tbody>
                        </table>
        		  	</div><!-- end of 主体 -->
                </div><!-- end of 页体 -->
            </div>
       	</div><!-- offhead -->

	    <div class="printable" style="width:840pt;height:570pt; padding-top:10pt; font-size:18pt">
            <table class="" style="width:100%; margin-top:10pt;">
                <tr>
                    <td rowspan="2" width="40%" style="padding-left:10pt"><img src="" class="printBarCode" width="80%"></td>
                    <td width="50%" class="printType" style="font-size:18pt"></td>
                    <td style="text-align:right; font-size:18pt; width=10%; padding-right:10pt" class="printSeries"></td>
                </tr>
                <tr>
                    <td class="printConfig" style="font-size:18pt"></td>
                    <td style="text-align:right; font-size:18pt; padding-right:10pt" class="printSerialNumber"></td>
                </tr>
            </table>
            <img src="" width="" height="" class="printFrontImage" style="display: block; margin:0 auto">
            <table style="width:100%;margin-top:10pt;">
                <tr>
                    <td class="printRemark" style="font-size:18pt; padding-left:10pt"></td>
                    <!-- <td align="right" style="text-align:right; font-size:18pt; padding-right:10pt">1/2</td> -->
                </tr>
            </table>
        </div>
	</body>
</html>
