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
    <script type="text/javascript" src="/bms/js/common.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/vq2RoadTestFinished.js"></script>
	</head>
	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
		<div class="offhead">
			<?php
				// require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
			?>
		<div id="bodyright" class="offset2"><!-- Main体 -->        
            <legend><?php echo $nodeDisplayName;?>
                    <span class="pull-right">
                        <a href="/bms/execution/child?node=路试&view=VQ2RoadTestException"><i class="icon-link"></i>&nbsp;VQ2异常.路试</a>/
                        /
                        <a href="/bms/execution/faultDutyEdit"><i class="icon-link"></i>&nbsp;故障责任编辑</a>
                    </span>
            </legend>
				<div><!-- 内容主体 -->
					<div>
						<form id="formFailure" class="well form-search">
							<div>
								<div class="input-prepend">
									<span class="add-on" id="vinLabel">V</span>
									<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText">
								</div>
								<div class="input-prepend">
									<span class="add-on" id="cardLabel"><i class="icon-credit-card"></i></span>
									<input type="text" class="span3" placeholder="请贴厂牌或输入工号完成提交..." id="cardText" disabled="disabled">
								</div>
								<!-- <button id="btnSubmit" type="submit" class="btn btn-danger" disalbled='disabled'>提交故障记录</button> -->
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
							</form>
							<form id="formBarCode" class="well form-search">
								<div class="input-prepend">
									<span class="add-on" id="barcodeLabel"><i class="icon-barcode"></i></span>
									<input id="compCodeText" type="text" class="span3" placeholder="请扫描/输入条码...">
								</div>
									<table class="table table-striped table-condensed" id="componentTable">
										<tbody>
										  
										</tbody>
									</table>
								<div id="checkAlert"></div>
							</form>
							<form id="divDetail" class="well form-search">
								<div>
									<ul id="tabs" class="nav nav-pills">
										<li class="active"><a href="#general" data-toggle="tab">常见</a></li>
										<li><a href="#other" data-toggle="tab">其他</a></li>
										<li>
											<div style="margin:0 0 0px 5px;padding-top:2px">
												<!-- <label>温度</label> -->
												<div class="input-append">
													<input id="inputTemperature" type="text" class="span3" style="width:70px" placeholder="空调温度...">
													<span class="add-on">℃</span>
												</div>
											</div>
										</li>
									</ul>
								</div>
								<div id="tabContent" class="tab-content">

									<div class="tab-pane active" id="general">
										<table id="tableGeneral" class="table">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span2">故障零部件</td>
													<td class="span3">故障模式</td>
													<td class="">责任部门</td>
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
													<td class="span2">故障零部件</td>
													<td class="span3">故障模式</td>
													<td class="">责任部门</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
								</div>
							                   	
						</form>                                          
					</div>
					
				</div><!-- end of 内容主体 -->
			</div><!-- end main体 -->
		</div><!-- offhead -->	
	</body>
</html>
