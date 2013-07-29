<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="cache-control" content="no-cache, must-revalidate">
		<title><?php echo $nodeDisplayName;?></title>
    	<!-- Le styles -->
    	<link href="/bms/css/common.css" rel="stylesheet">
    	<link rel="stylesheet" type="text/css" href="/bms/css/bootstrap.css" media="screen">
    	<link rel="stylesheet" type="text/css" href="/bms/css/execution/assembly/dataInput/T0.css" media="screen">
    	<link rel="stylesheet" type="text/css" href="/bms/css/printable.css" media="print">
		<style type="text/css" media="screen">
			.printable{
				display: none;
			}
		</style>
		<style type="text/css" media="print">
			td,th{border:none;}
		</style>

    	<!-- <link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/dataInput/T0.css" rel="stylesheet"> -->
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>

		<script type="text/javascript" src="/bms/js/bootstrap-collapse.js"></script>
    	<script type="text/javascript" src="/bms/js/execution/assembly/dataInput/t0.js"></script>
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
		            	<legend><?php echo $nodeDisplayName;?>
		            		<span class="pull-right">
		            			<a href="/bms/execution/pauseEdit"><i class="icon-link"></i>&nbsp;停线编辑</a>
		            		</span>
		            	</legend>
		            </div>
					<div><!-- 内容主体 -->
						<form id="form" class="well form-search">
							<label>VIN</label>
							<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText" />
							<input type="button" class="btn btn-primary" id='btnSubmit' value='上线并打印配置单'></input>   
							<input type="button" class="btn" id ="reset" value ="清空"></input>
							<input type="hidden" id='currentNode' name='currentNode' value='<?php echo $node ?>'></input>
							<input type="hidden" id='line' name='currentNode' value='<?php echo $line ?>'></input>
							<span class="help-inline" id="vinHint">未输入VIN</span>
							<div class="help-inline" id="carInfo">
								<span class="label label-info" rel="tooltip" title="车系" id="infoSeries"></span>
								<span class="label label-info" rel="tooltip" title="车型" id="infoType"></span>
								<span class="label label-info" rel="tooltip" title="颜色" id="infoColor"></span>
								<span class="label label-info" rel="tooltip" title="耐寒性" id="infoColdResistant"></span>
								<span class="label label-info" rel="tooltip" title="车辆区域" id="infoStatus"></span>
							</div>
						</form>             
					</div>
		
					<div >
						<div id="messageAlert" class="alert"></div>    
					</div > 

					<div class="accordion" id="accordionPlan">
	                	<div class="accordion-group">
	                		<div class="accordion-heading">
	               				<a id="planViewToggle" class="accordion-toggle" 
								data-toggle="collapse" data-parent="#accordionPlan" href="#collapsePlan"><span id="today"></span>_计划预览, 待上线 : <span id="infoCount"></span></a>
	               			</div>
	                		<div id="collapsePlan" class="accordion-body collapse">
	                			<div class="accordion-inner" id="planDiv">
	                				<table id="planTable" class="table table-condensed">
	                					<thead>
	                    					<tr>
	                    						<th class="">序号</th>
												<th class="">待上线</th>
												<th class="">车系</th>
												<th class="">车型</th>
												<th class="">配置</th>
												<th class="">耐寒性</th>
												<th class="">颜色</th>
												<th class="">年份</th>
												<!-- <th class="T_dingdanleixing_width">订单类型</th> -->
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
	               				<a id="planViewToggleTomorrow" class="accordion-toggle" 
								data-toggle="collapse" data-parent="#accordionPlan" href="#collapsePlanTomorrow"><span id="tomorrow"></span>_计划预览</a>
	               			</div>
	                		<div id="collapsePlanTomorrow" class="accordion-body collapse">
	                			<div class="accordion-inner" id="planTomorrowDiv">
	                				<table id="planTomorrow" class="table table-condensed">
	                					<thead>
	                    					<tr>
	                    						<th class="">序号</th>
												<th class="">待上线</th>
												<th class="">车系</th>
												<th class="">车型</th>
												<th class="">配置</th>
												<th class="">耐寒性</th>
												<th class="">颜色</th>
												<th class="">年份</th>
												<!-- <th class="T_dingdanleixing_width">订单类型</th> -->
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
					</div>
				</div><!-- end Main体 -->
			</div><!-- end offhead --> 
		</div>

		<div id="M6Glass" class="printable" style="width:220pt;height:100pt; padding: 7pt 5pt; font-family: Microsoft YaHei;">
			<div>
				<p class="printVin" style="font-size:16px; margin:5px 0">-</p>
			</div>
			<div>
				<p class="printModel" style="font-size:16px; margin:5px 0">-</p>
			</div>
			<div>
				<p class="printConfig" style="font-size:16px; margin:5px 0">-</p>
			</div>
			<div>
				<p class="printGlass" style="font-size:50px; margin:5px auto;text-align: center">-</p>
			</div>
		</div>

		<div class="configPaper printable" style="width:840pt;height:1100pt; padding-top:10pt; font-size:18pt">
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
					<td align="right" style="text-align:right; font-size:18pt; padding-right:10pt">1/2</td>
				</tr>
			</table>
		</div>

		<div class="configPaper printable" style="width:840pt;height:1100pt; padding-top:10pt; font-size:18pt; page-break-before: always">
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
			<img src="" width="" height="" class="printBackImage" style="display: block; margin:0 auto">
			<table style="width:100%;margin-top:10pt;">
				<tr>
					<td class="printRemark" style="font-size:18pt; padding-left:10pt"></td>
					<td align="right" style="text-align:right; font-size:18pt; padding-right:10pt">2/2</td>
				</tr>
			</table>
		</div>
	</body>
</html>
