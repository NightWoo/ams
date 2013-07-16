<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>成品库标签</title>
    	<!-- Le styles -->
    	<link rel="stylesheet" type="text/css" href="/bms/css/bootstrap.css" media="screen">
    	<link rel="stylesheet" type="text/css" href="/bms/css/execution/assembly/dataInput/WarehouseLabel.css" media="screen">
    	<link rel="stylesheet" type="text/css" href="/bms/css/printable.css" media="print">
		<link href="/bms/css/common.css" rel="stylesheet">
    	<!-- <link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/dataInput/T0.css" rel="stylesheet"> -->
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>

		<script type="text/javascript" src="/bms/js/bootstrap-collapse.js"></script>
    	<script type="text/javascript" src="/bms/js/execution/assembly/dataInput/warehouseLabel.js"></script>
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
		            	<legend>成品库标签打印
		            		<span class="pull-right">
		            			<a href="/bms/execution/outStandby"><i class="icon-link"></i>&nbsp;成品库备车</a>
		            			/
		            			<a href="/bms/execution/outStandby35"><i class="icon-link"></i>&nbsp;35#厂房备车</a>
		            		</span>
		            	</legend>
		            </div>
					<div><!-- 内容主体 -->
						<form id="form" class="well form-search">
							<div class="input-prepend">
								<span class="add-on" id="vinLabel">VIN</span>
								<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText">
							</div>
							<button class="btn btn-primary" id='btnSubmit' value=''><i class="icon-print"></i>&nbsp;打印标签</button>
							<input type="button" class="btn" id ="reset" value ="清空">
							<input type="hidden" id='standbyArea' name='standbyArea' value="0"></input>
							<span class="help-inline" id="vinHint">请输入VIN后回车</span>
							<div class="help-inline" id="carInfo">
								<span class="label label-info" rel="tooltip" title="流水号" id="serialNumber"></span>
								<span class="label label-info" rel="tooltip" title="车系" id="series"></span>
								<span class="label label-info" rel="tooltip" title="车型" id="type"></span>
								<span class="label label-info" rel="tooltip" title="颜色" id="color"></span>
                            	<span class="label label-info" rel="tooltip" title="车辆区域" id="statusInfo"></span>
                            	<span class="label label-info" rel="tooltip" title="经销商" id="distributorInfo"></span>
							</div> 
						</form>             
					</div>
					<div>
						<div id="messageAlert" class="alert"></div>    
					</div>		
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
		</div>
	</body>
</html>
