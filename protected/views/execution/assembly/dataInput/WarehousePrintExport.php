<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>出口车出库打印</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/WarehousePrintExport.css" rel="stylesheet">	
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/dataInput/warehousePrintExport.js"></script>
</head>
<body>
		
	<?php
		require_once(dirname(__FILE__)."/../../../common/head.php");
	?>
	<div class="offhead">
	   <?php
		require_once(dirname(__FILE__)."/../../../common/left/assembly_plan_left.php");
		?>
     
        <div id="bodyright" class="offset2"><!-- 页体 -->
   	   		<div><!-- 主体 -->
	            <div>
	            	<legend>出口车合格证、厂检单数据传输
	            		<span class="pull-right">
	            			<a href="/bms/execution/warehousePrint"><i class="icon-link"></i>&nbsp;出库打印</a>
	            		</span>
	            	</legend>
					<div id="form" class="well form-inline">
						<div class="input-prepend  input-append">
							<span class="add-on">特殊订单号</span>
							<input type="text" class="SpecialOrder span3" placeholder="请输入特殊订单号..." id="vinWarehouse" />
							<a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
						</div>
						<button class="btn btn-primary" id="printAll" disabled><i class="btnPrint icon-print"></i>&nbsp;打印全部</button>
						<span class="help-inline" id="orderHint">请输入特殊订单号后回车进行校验</span>
						<div class="help-inline" id="orderInfo">
							<span class="label label-info" rel="tooltip" title="共计" id="total"></span>
							<span class="label label-info" rel="tooltip" title="厂检单数据完整" id="inspectionOK"></span>
							<span class="label label-info" rel="tooltip" title="合格证数据完整" id="certificateOK"></span>
						</div>
					</div>
	            </div>
	            <div>
	            	<table id="tableResult" class="table table-condensed table-hover table-bordered">
	            		<thead>
	            			<tr>
		            			<th>特殊订单号</th>
		            			<th>VIN</th>
		            			<th>整车编号</th>
		            			<th>车系</th>
		            			<th>车型/配置/耐寒性</th>
		            			<th>颜色</th>
		            			<th>发动机号</th>
		            			<th>状态</th>
		            			<th>下线时间</th>
		            			<th>备注</th>
		            		</tr>
	            		</thead>
	            		<tbody>

	            		</tbody>
	            	</table>
	            </div>


				<div>
                    <div id="messageAlert" class="alert"></div>    
                </div> 	
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
	</div><!-- offhead -->
</body>
</html>