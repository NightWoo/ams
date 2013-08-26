<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>成品库异常</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/other/WarehouseReturn.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/common.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/other/warehouseReturn.js"></script>
	</head>
	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
		<div class="offhead">
			<?php
				// require_once(dirname(__FILE__)."/../../../common/left/assembly_plan_left.php");
			?>
			<div id="bodyright" class="offset2"><!-- Main体 -->
				<div>
		            	<legend>成品库异常
		            		<!-- <span class="pull-right">
		            			<a href="/bms/execution/outStandby"><i class="icon-link"></i>&nbsp;备车</a>
		            		</span> -->
		            	</legend>
		            </div>
					<div>
						<form id="form" class="well form-search">
							<table id="tableInput">
								<tr>
									<td style="text-align: right;">VIN&nbsp;&nbsp;</td>
									<td>
										<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText">
										<div class="btn-group" style="vertical-align:top;">
											<input type="button" class="btn btn-danger btnSubmit" disabled="disabled" id ="toVQ3" value ="VQ3">
											<input type="button" class="btn btn-danger btnSubmit" disabled="disabled" id ="toVQ2" value ="VQ2">
											<input type="button" class="btn btn-danger btnSubmit" disabled="disabled" id ="toVQ1" value ="VQ1">
											<input type="button" class="btn btn-primary btnSubmit" disabled="disabled" id ="toWarehouse" value ="成品库">
										</div>
										<input type="button" class="btn" id ="reset" value ="清空">
										<span class="help-inline" id="vinHint">请输入VIN后回车</span>
										<div class="help-inline" id="carInfo">
											<span class="label label-info" rel="tooltip" title="流水号" id="serialNumber"></span>
											<span class="label label-info" rel="tooltip" title="车系" id="series"></span>
											<span class="label label-info" rel="tooltip" title="车型" id="type"></span>
											<span class="label label-info" rel="tooltip" title="颜色" id="color"></span>
                                        	<span class="label label-info" rel="tooltip" title="车辆区域" id="statusInfo"></span>
                                        	<span class="label label-info" rel="tooltip" title="经销商" id="distributorInfo"></span>
										</div>
									</td>
								</tr>
								<tr>
									<td style="text-align: right; vertical-align: top; padding-top: 5px;">退回原因&nbsp;&nbsp;</td>
									<td style="padding-top: 5px;">
										<textarea rows="1" id="remark" style="width:495px;"></textarea>
									</td>
								</tr>
							</table>
						</form>
					</div>
					<div>
						<div id="messageAlert" class="alert"></div>    
					</div> <!-- end 提示信息 -->  
				</div><!-- end 内容主体 -->
			</div><!-- end Main体 -->
		</div><!-- end Main体 -->    	
	</body>
</html>
