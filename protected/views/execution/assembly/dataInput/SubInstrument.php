<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>BMS.Ver0.1</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/vq1Exception.js"></script>
	</head>
	<body>
        <div>
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
                        	<li><a href="#">总装</a><span class="divider">&gt;</span></li>
                            <li><a href="#">数据录入</a><span class="divider">&gt;</span></li>
                        	<li class="active">仪表分装</li>                
                    	</ul>
                    </div><!-- end of breadcrumb -->
                    
           	   		<div><!-- 主体 -->
        				<div>
                   	  		<form id="formVIN" class="well form-search">
                                <div>
                       	  			<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VIN</label>
                                    <input id="inputVIN" type="text" placeholder="请扫描/输入VIN..." value="LGXC16DG4C1234564" readonly>
                                    <button id="btnSubmit" type="submit" class="btn btn-primary" style="margin-left: 10px;"><i class="icon-print icon-white"></i>&nbsp;打印</button>
                                     
                                    <button id="btnClear" type="reset" class="btn btn-danger"><i class="icon-repeat icon-white"></i>&nbsp;清空</button>
                              		<span class="help-inline">C123456 如需打印指定车辆配置单，请清空</span>
                                    <div class="help-inline" id="carInfo">
                                        <span class="label label-info" rel="tooltip" title="车系" id="infoSeries">x</span>
                                        <span class="label label-info" rel="tooltip" title="车型" id="infoType">x</span>
                                        <span class="label label-info" rel="tooltip" title="颜色" id="infoColor">x</span>
                                        <span class="label label-info" rel="tooltip" title="耐寒性" id="infoColdResistant">x</span>
                                        <span class="label label-info" rel="tooltip" title="车辆区域" id="infoStatus">x</span>
                                    </div>
                                </div>
                                <div style="margin-top:10px;">
                                    <label>列队时间</label>
                                    <input type="text" class="span3" placeholder="请输入开始时间..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
                                    <span>-</span>
                                    <input type="text" class="span3" placeholder="请输入结束时间..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
                                    <button id="btnRefresh" type="button" class="btn btn-success"><i class="icon-refresh icon-white"></i>&nbsp;刷新</button>
                                </div>
                            </form>                       
                        </div>
                        
                        <!-- <div id="info" class="alert alert-success">LGXC16DGXC1234666仪表分装配置单已打印，请输入下一辆车VIN</div> -->

        				<table id="tableList" class="table table-condensed">
                            <thead>
                                <tr class="active">
                                    <th class="">序号</th>
                                    <th class="">待上线</th>
                                    <th class="">车系</th>
                                    <th class="">车型</th>
                                    <th class="">配置</th>
                                    <th class="">耐寒性</th>
                                    <th class="">颜色</th>
                                    <th class="">年份</th>
                                    <th class="">特殊单号</th>
                                    <th class="">备注</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row0" class="info">
                                    <td>C12356</td>
                                    <td>F0</td>
                                    <td>LGXC16DG4C1234564</td>
                                    <td>QCJ7100L（1.0排量尊舒适型）</td>
                                    <td>德兰黑</td>
                                    <td>2012</td>
                                    <td>1.0舒适标准</td>
                                    <td>国内订单</td>
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
                                    <td>国内订单</td>
                                    <td>-</td>
                                    <td>QA-5</td>                                   
                                </tr>                                               
                            </tbody>
                        </table>
        		  	</div><!-- end of 主体 -->
                </div><!-- end of 页体 -->
            </div>
       	</div><!-- offhead -->
	
	</body>
</html>