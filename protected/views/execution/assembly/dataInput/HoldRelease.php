<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>释放订单占位</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/HoldRelease.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/holdRelease.js"></script>
	</head>
	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
		<div class="offhead">
			<?php
				require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
			?>
			<div id="bodyright" class="offset2"><!-- Main体 -->
				<div>
		            	<legend>释放订单占位
		            		<span class="pull-right">
		            			<a href="/bms/execution/outStandby"><i class="icon-link"></i>&nbsp;备车</a>
		            		</span>
		            	</legend>
		            </div>
					<div>
						<form id="form" class="well form-search">
							<table id="tableInput">
								<tr>
									<td>
										<div class="input-prepend">
											<span class="add-on">V</span>
											<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText">
										</div>
										    <div class="btn-group">
												<input type="button" class="btn btn-primary" disabled="disabled" id ="btnSubmit" value ="返回成品库异常区">
												<input type="button" class="btn btn-danger" disabled="disabled" id ="toVQ3" value ="返回VQ3">
											</div>
										<input type="button" class="btn" id ="reset" value ="清空">
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
					</div> <!-- end 提示信息 -->  
				</div><!-- end 内容主体 -->
			</div><!-- end Main体 -->
		</div><!-- end Main体 -->    	
	</body>
</html>
