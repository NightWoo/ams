<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $nodeDisplayName;?></title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/VQ2RoadTestEntry.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/vq2RoadTestEntry.js"></script>
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
				<div ><ul class="breadcrumb"><!-- 面包屑 -->
						<li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
						<li><a href="#">总装</a><span class="divider">&gt;</span></li>
						<li><a href="child?node=NodeSelect">数据录入</a><span class="divider">&gt;</span></li>
						<li class="active"><?php echo $nodeDisplayName;?></li>                
				</ul></div><!-- end 面包屑 -->
				
				
				<div><!-- 内容主体 -->
					<form id="formVIN" class="well form-search">
						<table id="tableInput">
							<tr>
								<!--<th>
									<label>VIN：</label>
								</th>-->
								<td>
									<div class="input-prepend">
										<span class="add-on">V</span>
										<input id="vinText" type="text" placeholder="请扫描/输入VIN...">
									</div>
									<input type="button" class="btn" id ="reset" value ="清空"></input>
									<input type="hidden" id='currentNode' name='currentNode' value='<?php echo $node?>'></input>
									<span class="help-inline" id="vinHint">请输入VIN后回车</span>
									<div class="help-inline" id="carInfo">
										<span class="label label-info" rel="tooltip" title="流水号" id="serialNumber"></span>
										<span class="label label-info" rel="tooltip" title="车系" id="series"></span>
										<!--<span class="label label-info" rel="tooltip" title="Vin号" id="vin"></span>-->
										<span class="label label-info" rel="tooltip" title="车型" id="type"></span>
										<span class="label label-info" rel="tooltip" title="颜色" id="color"></span>
                            			<span class="label label-info" rel="tooltip" title="车辆区域" id="statusInfo"></span>
									</div>
								</td>
							</tr>
							<tr>
								<!--<th>
									<label>路试员：</label>
								</th>-->
								<td>
									<div class="input-prepend">
										<span class="add-on"><i class="icon-user"></i></span>
										<input id="inputDriver" type="text" placeholder="请输入工号..." disabled="disabled">
									</div>
									<button id="btnSubmit" type="submit" class="btn btn-primary" disabled="disabled">路试</button>
									<span class="help-inline" id="cardHint">请输入员工卡号后回车</span>
									<div class="help-inline" id="driverInfo">
										<!--<span>路试员:</span>-->
										<span class="label label-info" rel="tooltip" title="姓名" id="driverName"></span>
										<span id="driverId" style="display:none"></span>
									</div>
								</td>
							</tr>
						</table>
					</form>
					<div>
						<div id="messageAlert" class="alert"></div>    
					</div> <!-- end 提示信息 -->  
				</div><!-- end 内容主体 -->
			</div><!-- end main体 -->
		</div><!-- end offhead -->
        
	</body>
</html>
