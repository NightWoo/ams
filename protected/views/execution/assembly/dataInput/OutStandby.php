<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>出库备车</title>
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
					<div><!-- 面包屑 -->
						<ul class="breadcrumb">
							<li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
							<li><a href="#">总装</a><span class="divider">&gt;</span></li>
							<li><a href="child?node=NodeSelect">数据录入</a><span class="divider">&gt;</span></li>
							<li class="active">出库备车</li>
							<li class="pull-right"><a href="/bms/execution/child?node=CHECK_OUT&view=WarehouseExit">出成品库</a></li>                
						</ul>
					</div><!-- end 面包屑 -->
					<div><!-- 内容主体 -->
						<form id="form" class="well form-search">
							<input type="button" class="btn btn-primary" id='btnSubmit' value='备车'></input>
							<span><a href="" id="refresh"><i class="icon-refresh" style="margin-top:6px"></i></a></span>
							<span class="help-inline" id="hint">未获得任何可备车辆</span>
							<div class="help-inline" id="carInfo">
								<span class="label label-info" rel="tooltip" title="车列" id="rowInfo"></span>
								<span class="label label-info" rel="tooltip" title="Vin号" id="vinInfo"></span>
								<span class="label" rel="tooltip" title="车系" id="seriesInfo"></span>
								<span class="label" rel="tooltip" title="车型" id="typeInfo"></span>
								<span class="label" rel="tooltip" title="颜色" id="colorInfo"></span>
								<span class="label" rel="tooltip" title="订单号" id="orderNumberInfo"></span>
							</div> 
						</form>             
					</div>
					<div>
						<div id="messageAlert" class="alert"></div>    
					</div> <!-- end 提示信息 -->
		
					<!-- <div >
						<div id="messageAlert" class="alert"></div>    
					</div >  -->
					<div class="accordion" id="accordionOrder">
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
                            					<!-- <th id="thCarYear">年份</th> -->
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
					</div>
				</div><!-- end Main体 -->
			</div><!-- end offhead --> 
		</div>
		<div class="printable toPrint" style="width:168pt;height:70pt; margin:8pt 0; font-family: Microsoft YaHei;">
			<div style="text-align:center;font-size:24pt;text-align: center;">
				<p id="rowPrint" style="margin: 0 5pt 0 0;">A000</p>
			</div>
			<div style="text-align:center;font-size:14pt;margin: 0 5pt;">
				<p id="vinPrint" style="margin:0 5pt 5pt 0;">LC0C14AA0d0000000</p>
			</div>
			<div style="font-size:10pt;">
				<span id="driver" style="margin-left:10pt">
					张三三
				</span>
				<span class="nowTime" style="float:right; margin-right:15pt">
				</span>
			</div>
		</div>
	</body>
</html>
