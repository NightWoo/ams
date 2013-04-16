<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
        <meta http-equiv="cache-control" content="no-cache, must-revalidate">
		<title><?php echo $nodeDisplayName;?></title>
		<!-- Le styles -->
		<link href="/bms/css/bootstrap.css" rel="stylesheet"  media="screen">
		<link href="/bms/css/execution/assembly/dataInput/T11-F10.css" rel="stylesheet"  media="screen">
		<link href="/bms/css/execution/assembly/dataInput/T11-F10_Print.css" rel="stylesheet"  media="print">
		<link href="/bms/css/common.css" rel="stylesheet"  media="screen">
		<link rel="stylesheet" type="text/css" href="/bms/css/printable.css" media="print">
		<style type="text/css" media="screen">
		.toPrintable{
			display: none;
		}
	</style>
		<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	    <script type="text/javascript" src="/bms/js/service.js"></script>
		<script type="text/javascript" src="/bms/js/head.js"></script>
		<script type="text/javascript" src="/bms/js/execution/assembly/dataInput/t11-f10.js"></script>
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
						<div><!-- 面包屑 -->
							<ul class="breadcrumb">
								<li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
								<li><a href="#">总装</a><span class="divider">&gt;</span></li>
								<li><a href="child?node=NodeSelect">数据录入</a><span class="divider">&gt;</span></li>
								<li class="active" id ="myNode"><?php echo $nodeDisplayName;?></li>                
							</ul>
						</div><!-- end 面包屑 -->
						<div ><!-- 内容主体 -->
							<form class="well form-search">
								<table id="tableInput">
									<tr>
										<td>
											<div class="input-prepend">
												<span class="add-on">V</span>
												<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText" />
											</div>
											<span class="help-inline" id="vinHint">请输入VIN后回车</span>
											<div class="help-inline" id="carInfo">
												<span class="label label-info" rel="tooltip" title="流水号" id="serialNumber"></span>
												<span class="label label-info" rel="tooltip" title="车系" id="series"></span>
												<!--<span class="label label-info" rel="tooltip" title="Vin号" id="vin"></span>-->
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
												<span class="add-on"><i class="icon-barcode"></i></span>
												<input type="text" class="input" id="compCodeText" placeholder="扫描零部件条码">				
											</div>
											<input type="button" class="btn btn-primary" id='btnSubmit' value='提交'></input> 
											<input type="hidden" id='currentNode' name='currentNode' value='<?php echo $node?>'></input>		
											<button class="btn" id="reset">清空</button>
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

    	<div class="toPrintable" style="padding: 5pt; width: 220pt;">
    		<div id="printBarCode">
    			<div>
    				<p id="carSeriesInfo">F0</p>
    				<p id="carTypeShort">QCJ7100L(1.0排量舒适型)</p>
    			</div>
    			<div style="">
    				<img class="vinBarcode" alt="" src="/bms/img/barcodeTest.jpeg" style="width:247px; height:60px;">
    			</div>
    		</div>

    		<div id="printInfo">
    			<div>
    				<p class="printDate">-</p>
    			</div>
    			<div>
    				<p class="printSerialNumber">-</p>
    			</div>
    			<div>
    				<p class="printModel">-</p>
    			</div>
    			<div>
    				<p class="printConfig">-</p>
    			</div>
    			<div>
    				<p class="printRemark">-</p>
    			</div>
    		</div>
    	</div>	
	</body>
</html>
