<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
        <meta http-equiv="cache-control" content="no-cache, must-revalidate">
		<title>条码补录</title>
		<!-- Le styles -->
		<link href="/bms/css/bootstrap.css" rel="stylesheet"  media="screen">
		<link href="/bms/css/common.css" rel="stylesheet"  media="screen">
		<link rel="stylesheet" type="text/css" href="/bms/css/printable.css" media="print">
		<style type="text/css" media="screen">
			#tableInput tr {
				height: 40px;	
			}

			.lingbujianmingchen {
				width: 250px;
			}

			#componentTable {
				margin-top: 15px;
				margin-bottom: 0;
			}

			#sub {
				margin-left: 5px;
			}

			.alert {
				margin-bottom: 0;
				margin-top: 10px;
			}

			.well {
				margin-bottom: 0px;
			}
		</style>
		<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	    <script type="text/javascript" src="/bms/js/service.js"></script>
		<script type="text/javascript" src="/bms/js/head.js"></script>
		<script type="text/javascript" src="/bms/js/common.js"></script>
		<script type="text/javascript" src="/bms/js/execution/assembly/other/completeBarcodeRecord.js"></script>
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
		                    <legend>条码补录
		                    	<span class="pull-right">
			                    	<a href="/bms/execution/carLabelAssembly" id="linkCarLabel" style="display:none"><i class="icon-link"></i>&nbsp;车辆标签</a>
			                    </span>
		                    </legend>
		                </div>
						<div ><!-- 内容主体 -->
							<form class="well form-search">
								<table id="tableInput">
									<tr>
										<td>
											<div class="input-prepend">
												<span class="add-on">V</span>
												<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText" />
											</div>
											<input type="button" class="btn btn-primary" id='btnSubmit' value='提交'></input> 
											<button class="btn" id="reset">清空</button>
											<span class="help-inline" id="vinHint">请输入VIN后回车</span>
											<div class="help-inline" id="carInfo">
												<span class="label label-info" rel="tooltip" title="流水号" id="serialNumber"></span>
												<span class="label label-info" rel="tooltip" title="车系" id="series"></span>
												<span class="label label-info" rel="tooltip" title="车型" id="type"></span>
												<span class="label label-info" rel="tooltip" title="配置" id="config"></span>
												<span class="label label-info" rel="tooltip" title="耐寒性" id="cold"></span>
												<span class="label label-info" rel="tooltip" title="颜色" id="color"></span>
												<span class="label label-info" rel="tooltip" title="车辆区域" id="statusInfo"></span>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="input-prepend">						
												<span class="add-on"><i class="fa fa-barcode"></i></span>
												<input type="text" class="input" id="compCodeText" placeholder="扫描零部件条码">				
											</div>
											<select id="nodeSelect" class="span2">
												<!-- <option value="" selected>所有节点</option> -->
												<option value="T11">I线_T11</option>
												<option value="T21">I线_T21</option>
												<option value="T32">I线_T32</option>
												<option value="C10">I线_C10</option>
												<option value="C21">I线_C21</option>
												<option value="F10">I线_F10</option>
												<option value="ROAD_TEST_FINISH">路试完成</option>
												<option value="T11_2">II线_T11</option>
												<option value="T21_2">II线_T21</option>
												<option value="T32_2">II线_T32</option>
												<option value="C10_2">II线_C10</option>
												<option value="C21_2">II线_C21</option>
												<option value="F10_2">II线_F10</option>
											</select>
										</td>
									</tr>
								</table>
								<div>
									<div id="messageAlert" class="alert"></div>
								</div> <!-- end 提示信息 -->							
							</form>
							<div id="checkAlert">
								
							</div>
							<div id="ComponentInfo">	
								<table class="table table-striped table-condensed" id="componentTable">
									<thead>
									  <tr>
										<th class="lingbujianmingchen">零部件名称</th>
										<th class="lingbujiantiaoma">零部件条码</th>
									  </tr>
									</thead>
									<tbody>
									  
									</tbody>
								</table>
							</div>
							
						</div>
										
					<!-- <div>现已生产：<span id="生产数">辆</span></div> -->
				</div><!-- end 内容主体 -->
			</div><!-- end offhead -->	
    	</div>
	</body>
</html>
