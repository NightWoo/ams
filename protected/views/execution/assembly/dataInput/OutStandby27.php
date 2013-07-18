<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>35#厂房备车</title>
    	<!-- Le styles -->
    	<link rel="stylesheet" type="text/css" href="/bms/css/bootstrap.css" media="screen">
    	<link rel="stylesheet" type="text/css" href="/bms/css/execution/assembly/dataInput/OutStandby.css" media="screen">
    	<link rel="stylesheet" type="text/css" href="/bms/css/printable.css" media="print">
		<link href="/bms/css/common.css" rel="stylesheet">
    	<!-- <link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/dataInput/T0.css" rel="stylesheet"> -->
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>

		<script type="text/javascript" src="/bms/js/bootstrap-collapse.js"></script>
    	<script type="text/javascript" src="/bms/js/execution/assembly/dataInput/outStandby.js"></script>
    	<style type="text/css" media="screen">
			.printable{
				display: none;
			}
		</style>
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
				<div id="bodyright" class="offset2"><!-- Main体 -->
					<div>
		            	<legend>27#厂房备车
		            		<span class="pull-right">
				            	<a href="/bms/execution/warehouseLabel"><i class="icon-link"></i>&nbsp;成品库标签</a>
			            		/
			            		<a href="/bms/execution/outStandby"><i class="icon-link"></i>&nbsp;成品库备车</a>
		            		</span>
		            	</legend>
		            </div>
					<div><!-- 内容主体 -->
						<form id="form" class="well form-search">
							<div id="seriesDiv">
		            			<label class="checkbox"><input type="checkbox" id="checkboxF0" value="F0">F0</input></label>
	                            <label class="checkbox"><input type="checkbox" id="checkboxM6" value="M6">M6</input></label>
	                            <label class="checkbox"><input type="checkbox" id="checkbox6B" value="6B">思锐</input></label>
		            		</div>
							<div class="input-prepend">
								<span class="add-on" id="cardLabel"><i class="icon-credit-card"></i></span>
								<input type="text" id="cardNumber" class="span3" placeholder="请贴厂牌/输入工号...">
							</div>
							<input type="button" class="btn btn-success" id='btnSubmit' value='27#厂房备车'>
							<input type="button" class="btn" id ="reset" value ="清空">
							<input type="hidden" id='standbyArea' name='standbyArea' value="27"></input>
							<span class="help-inline" id="hint">未获得任何可备车辆</span>
							<div class="help-inline" id="carInfo">
								<span class="label label-info" rel="tooltip" title="车列" id="rowInfo"></span>
								<span class="label label-info" rel="tooltip" title="Vin号" id="vinInfo"></span>
								<span class="label label-info" rel="tooltip" title="车系" id="seriesInfo"></span>
								<span class="label label-info" rel="tooltip" title="车型配置" id="typeInfo"></span>
								<span class="label label-info" rel="tooltip" title="耐寒性" id="coldInfo"></span>
								<span class="label label-info" rel="tooltip" title="颜色" id="colorInfo"></span>
								<span class="label label-info" rel="tooltip" title="订单号" id="orderNumberInfo"></span>
								<span class="label label-info" rel="tooltip" title="经销商" id="distributorInfo"></span>
                                <span class="label label-info" rel="tooltip" title="车道号" id="laneInfo"></span>
							</div> 
						</form>             
					</div>
					<div>
						<div id="messageAlert" class="alert"></div>    
					</div>
		
					<!-- <div class="accordion" id="accordionOrder">
	                	<div class="accordion-group">
	                		<div class="accordion-heading">
	               				<a id="viewToggleOrder" class="accordion-toggle" data-toggle="collapse" data-parent="#accordionOder" href="#collapseOrder">
	               					<span class="today"></span>_计划预览
	               				</a>
	               			</div>
	                		<div id="collapseOrder" class="accordion-body collapse">
	                			<div class="accordion-inner" id="divOrder">
	                				<table id="tableOrder" class="table table-condensed">
	                					<thead>
	                    					<tr>
	                    						<th id="thPriority">#</th>
                            					<th id="thRemain">待备</th>
                            					<th id="thOrderNumber">订单号</th>
                            					<th id="thAmount">数量</th>
                            					<th id="thCount">上道</th>
                            					<th id="thSeries">车系</th>
                            					<th id="thColor">颜色</th>
                            					<th id="thConfig">配置</th>
                            					<th id="thCarType">车型</th>
                            					<th id="thColdResistant">耐寒性</th>
	                    						<th id="thLane">车道</th>
                            					<th id="thCarrier">承运商 </th>
                            					<th id="thDistributor">经销商</th>
                            					<th id="thCity">城市</th>
                            					<th id="thRemark">备注</th>
	                  						</tr>
	                    				</thead>
	                    				<tbody>
	 					                   		
	         			         		</tbody>
	             				  	</table>
	                			</div>
	                		</div>
	                	</div>                  	
					</div> -->
				</div><!-- end Main体 -->
			</div><!-- end offhead --> 
		</div>
		<div class="printable toPrint" style="width:220pt;height:100pt; padding: 7pt 5pt; font-family: Microsoft YaHei;">
			<div style="text-align:center;font-size:14pt;text-align: center;">
				<p id="rowPrint" style="margin: 2pt 5pt;">A000</p>
			</div>
			<div style="text-align:center;font-size:12pt;margin: 0 5pt;">
				<p id="vinPrint" style="margin:2pt 5pt;">LC0C14AA0D0000000-冰岛蓝</p>
			</div>
			<div style="text-align:center;font-size:10pt;margin: 0 5pt;">
				<p id="distributorPrint" style="margin:2pt 5pt;">-------------------</p>
			</div>
			<div style="text-align:center;font-size:10pt;margin: 0 5pt;">
				<p id="orderNumberPrint" style="margin:2pt 5pt;">ZCDG-00000000000000</p>
			</div>
			<div style="text-align:center;font-size:12pt;margin: 0 5pt;">
				<p id="lanePrint" style="margin:2pt 5pt;">00道</p>
			</div>
			<!-- <div style="font-size:10pt;">
				<span id="driver" style="margin-left:10pt">
					司机
				</span>
				<span class="nowTime" style="float:right; margin-right:14pt">
				</span>
			</div> -->

		</div>
	</body>
</html>
