<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $nodeDisplayName;?></title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/VQ2RoadTestFinished.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/vq2RoadTestFinished.js"></script>
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
					<li class="pull-right"><a href="/bms/execution/child?node=路试&view=VQ2RoadTestException">前往VQ2异常.路试</a></li>                
            </ul></div><!-- end 面包屑 -->
				<div><!-- 内容主体 -->
					<div>
						<form id="formFailure" class="well form-search">
							<div>
								<label>VIN</label>
								<input id="vinText" type="text" class="span3" placeholder="请扫描/输入VIN...">
								<button id="btnSubmit" type="submit" class="btn btn-danger" disalbled='disabled'>提交故障记录</button>
								<button id="reset" type="reset" class="btn">清空</button>
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
							</div>
							<div>
								<div id="messageAlert" class="alert"></div>    
							</div> <!-- end 提示信息 -->                   
							<div id="divDetail">
								<div>
									<ul id="tabs" class="nav nav-pills">
										<li class="active"><a href="#general" data-toggle="tab">常见</a></li>
										<li><a href="#other" data-toggle="tab">其他</a></li>
									</ul>
								</div>
								<div id="tabContent" class="tab-content">
									<div class="tab-pane active" id="general">
										<table id="tableGeneral" class="table">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span3">故障零部件</td>
													<td class="span12">故障模式</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
									
									<div class="tab-pane" id="other">
										<table id="tableOther" class="table">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span4">故障零部件</td>
													<td class="span7">故障模式</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
								</div>
							</div>                   	
						</form>                                          
					</div>
					<form id="formBag" class="well form-inline">
						<label>气囊：</label>
						<input id="inputBag" type="text" class="span3" placeholder="请扫描/输入主驾气囊条码...">
					</form>
				</div><!-- end of 内容主体 -->
			</div><!-- end main体 -->
		</div><!-- offhead -->	
	</body>
</html>
