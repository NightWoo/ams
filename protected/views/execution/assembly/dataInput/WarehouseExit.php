<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $nodeDisplayName;?></title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/WarehouseExit.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/common.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/warehouseExit.js"></script>
	</head>
	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
		<div class="offhead">
			<?php
				// require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
			?>
			<div id="bodyright" class="offset2"><!-- Main -->
				<div>
		            	<legend><?php echo $nodeDisplayName;?>
		            		<span class="pull-right">
		            			<a href="/bms/execution/outStandby"><i class="icon-link"></i>&nbsp;备车</a>
		            		</span>
		            	</legend>
		            </div>
				<div ><!-- 内容主体 -->
					<div >
						<form id="form" class="well form-search">
							<table id="tableInput">
								<tr>
									<td>
										<div class="input-prepend">
											<span id="vinLabel" class="add-on">VIN</span>
											<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText">
										</div>
										<input type="button" class="btn" id ="reset" value ="清空"></input>
										<input type="hidden" id='currentNode' name='currentNode' value='<?php echo $node?>'></input>
									</td>
								</tr>
								<tr>
									<td>
										<div class="input-prepend">
											<span class="add-on" id="cardLabel"><i class="icon-credit-card"></i></span>
											<input type="text" class="span3" placeholder="请贴厂牌完成出库..." id="cardText" disabled="disabled">
										</div>
											<input type="button" class="btn btn-primary" disabled="disabled" id ="btnSubmit" value ="出库">
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
						<div id="messageLane" class="alert"></div>     
					</div> <!-- end 提示信息 -->  
				</div ><!-- end 内容主体 -->
			</div><!-- end Main -->
		</div><!-- end offhead -->   
	</body>
</html>
