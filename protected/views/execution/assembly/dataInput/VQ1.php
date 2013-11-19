<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $nodeDisplayName;?></title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/VQ1.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/common.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/vq1.js"></script>
	</head>
	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
		<div class="offhead">
			<?php
				// require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
			?>
			<div id="bodyright" class="offset2"><!-- 页体 -->
				<legend><?php echo $nodeDisplayName;?>静态检验
                    <span class="pull-right">
                        <a href="/bms/execution/child?node=VQ1异常&view=VQ1Exception"><i class="icon-link"></i>&nbsp;VQ1异常</a>
                        /
                        <a href="/bms/execution/faultDutyEdit"><i class="icon-link"></i>&nbsp;故障责任编辑</a>
                        /
                        <a href="/bms/execution/completeBarcodeRecord" id="linkCompleteBarcodeRecord"><i class="icon-link"></i>&nbsp;条码补录</a>
                    </span>
                </legend>
				
				<div><!-- 内容主体 -->
					<div>
						<form id="formFailure" class="well form-search">
							<div>
								<label>VIN</label>
								<input id="vinText" type="text" class="span3" placeholder="请扫描/输入VIN...">
								<input type="button" class="btn btn-danger"  disabled="disabled" id ="btnSubmit" value ="提交故障记录"></input>
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
								<!-- <span class="help-inline">xcdshcj</span> -->
							</div>
							<div id="messageAlert" class="alert"></div>    
							<div id="checkAlert"></div>
							<div id="divDetail">
								<div>
									<ul id="tabs" class="nav nav-pills">
										<li  class="active"><a href="#engienRoom" data-toggle="tab">发动机舱</a></li>
										<li><a href="#performance" data-toggle="tab">性能检验</a></li>
										<li><a href="#left" data-toggle="tab">左侧门</a></li>
										<li><a href="#right" data-toggle="tab">右侧门</a></li>
										<li><a href="#baggageRoom" data-toggle="tab">行李舱</a></li>
										<li><a href="#other" data-toggle="tab">其他</a></li>
									</ul>
								</div>
								<div id="tabContent" class="tab-content">
									<div class="tab-pane  active" id="engienRoom">
										<table id="tableEngine" class="table table-condensed tableFault">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span3">故障零部件</td>
													<td class="span4">故障模式</td>
													<td>在线修复</td>
													<td>责任部门</td>
												</tr>
											</thead>
											<tbody>
											
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="performance">
										<table id="tablePerformance" class="table table-condensed tableFault">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span3">故障零部件</td>
													<td class="span4">故障模式</td>
													<td>在线修复</td>
													<td>责任部门</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="left">
										<table id="tableLeft" class="table table-condensed tableFault">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span3">故障零部件</td>
													<td class="span4">故障模式</td>
													<td>在线修复</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="right">
										<table id="tableRight" class="table table-condensed tableFault">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span3">故障零部件</td>
													<td class="span4">故障模式</td>
													<td>在线修复</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="baggageRoom">
										<table id="tableBaggage" class="table table-condensed tableFault">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span3">故障零部件</td>
													<td class="span4">故障模式</td>
													<td>在线修复</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="other">
										<table id="otherTable" class="table table-condensed tableFault">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span3">故障零部件</td>
													<td class="span4">故障模式</td>
													<td>在线修复</td>
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
				</div><!-- end 内容主体 -->
        	</div><!-- end of 页体 -->
		</div>  	
	</body>
</html>
