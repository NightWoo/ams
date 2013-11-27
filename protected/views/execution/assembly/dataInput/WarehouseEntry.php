<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $nodeDisplayName;?></title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="/bms/css/common.css" rel="stylesheet" media="screen">
	<link href="/bms/css/execution/assembly/dataInput/WarehouseEntry.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" type="text/css" href="/bms/css/printable.css" media="print">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/common.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/warehouseEntry.js"></script>
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
					// require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
				?>
				<div id="bodyright" class="offset2"><!-- Main体 -->
					<div>
		            	<legend><?php echo $nodeDisplayName;?>
		            		<span class="pull-right">
		            			 <a href="/bms/execution/warehouseRelocate"><i class="fa fa-link"></i>&nbsp;重新分配库位</a>
		            			 /
		            			 <a href="/bms/execution/child?node=CHECK_IN&view=WarehouseEntryAreaT"><i class="fa fa-link"></i>&nbsp;入库临时区</a>
		            		</span>
		            	</legend>
		            </div>
					<div><!-- 内容主体 -->
						<div>
							<form id="form" class="well form-search">
								<table id="tableInput">
									<tr>
										<td>
											<div class="input-prepend">
												<span class="add-on" id="vinLabel">VIN</span>
												<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText">
											</div>
											<input type="button" class="btn" id ="reset" value ="清空">
											<input type="hidden" id='currentNode' name='currentNode' value='<?php echo $node?>'>
											<input type="hidden" id='areaT' name='areaT' value='0'>
										</td>
									</tr>
									<tr>
										<td>
											<div class="input-prepend">
												<span class="add-on" id="cardLabel"><i class="fa fa-credit-card"></i></span>
												<input type="text" class="span3" placeholder="请贴厂牌完成入库..." id="cardText" disabled="disabled">
											</div>
												<input type="button" class="btn btn-primary" disabled="disabled" id ="btnSubmit" value ="入库">
										</td>
									</tr>
									<tr>
										<td>
											<span class="help-inline" id="vinHint">请输入VIN后回车</span>
											<div class="help-inline" id="carInfo">
												<span class="label label-info" rel="tooltip" title="流水号" id="serialNumber"></span>
												<span class="label label-info" rel="tooltip" title="车系" id="series"></span>
												<span class="label label-info" rel="tooltip" title="车型" id="type"></span>
												<span class="label label-info" rel="tooltip" title="颜色" id="color"></span>
		                                    	<span class="label label-info" rel="tooltip" title="车辆区域" id="statusInfo"></span>
											</div>
										</td>
									</tr>
								</table>
							</form>
						</div>
						<div>
							<div id="messageAlert" class="alert"></div>
							<div id="messageRow" class="alert"></div>  
						</div> <!-- end 提示信息 --> 
					</div><!-- end 内容主体 -->
				</div><!-- end Main体 -->
			</div><!-- end offhead体 -->
		</div>
		<div class="printable toPrint" style="width:220pt;height:100pt; padding: 7pt 5pt; font-family: Microsoft YaHei;">
			<div style="text-align:center;font-size:14pt;text-align: center;">
				<p id="rowPrint" style="margin: 2pt 5pt;">A000</p>
			</div>
			<div style="text-align:center;font-size:12pt;margin: 0 5pt;">
				<p id="vinPrint" style="margin:2pt 5pt;">LC0C14AA0D0000000</p>
			</div>
			<div style="text-align:center;font-size:10pt;margin: 0 5pt;">
				<p id="distributorPrint" style="margin:2pt 5pt;"></p>
			</div>
			<div style="text-align:center;font-size:10pt;margin: 0 5pt;">
				<p id="orderNumberPrint" style="margin:2pt 5pt;"></p>
			</div>
			<div style="text-align:center;font-size:12pt;margin: 0 5pt;">
				<p id="lanePrint" style="margin:2pt 5pt;"></p>
			</div>
		</div>   	
	</body>
</html>
