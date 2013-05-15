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
		require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
		?>
     
        <div id="bodyright" class="offset2"><!-- 页体 -->
   	   		<div><!-- 主体 -->
	            <div>
	            	<legend>出口车厂检单、合格证打印数据传输
	            		<span class="pull-right">
           					<a href="/bms/execution/warehousePrint"><i class="icon-link"></i>&nbsp;出库打印</a>
           					<!-- /
	            			<a href="/bms/execution/warehousePrintOrderInBoard"><i class="icon-link"></i>&nbsp;板内订单方式传输</a> -->
	            		</span>
	            	</legend>
					<div id="form" class="well form-inline">
						<div class="input-prepend  input-append">
							<span class="add-on">特殊订单号</span>
							<input type="text"  id="specialOrder" class="specialOrderText span3" placeholder="请输入特殊订单号..." />
							<!-- <a class="btn clearinput appendBtn"><i class="icon-remove"></i></a> -->
							<a class="btn goSearch appendBtn" id="search-remove" style="width: 11px;"><i class="icon-search"></i></a>
						</div>
						<!-- <button class="btn btn-primary" id="check"><i class="icon-search"></i></button> -->
						<button class="btn btn-primary btnPrint" id="printAll" disabled><i class="icon-print"></i>&nbsp;打印</button>
						<label class="checkbox"><input type="checkbox" id="checkboxForce" value="1">将已传输过车辆一同传输</input></label>
						<span class="help-inline" id="orderHint">请输入特殊订单号后回车进行校验</span>
						<div class="help-inline" id="orderInfo">
							<span class="label label-info" rel="tooltip" title="共计" id="total"></span>
							<span class="label label-success" rel="tooltip" title="数据完整" id="isGood"></span>
						</div>
					</div>
	            </div>
	            <div>
                    <div id="messageAlert" class="alert"></div>    
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
		            			<th>单</th>
		            			<th>证</th>
		            		</tr>
	            		</thead>
	            		<tbody>

	            		</tbody>
	            	</table>
	            </div>


					
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
	</div><!-- offhead -->
<div class="modal" id="spinModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;top:25%">
  	<div class="modal-body">
  		<div style="margin: 0 auto; width:40px;">
  		<i class="icon-spin icon-spinner" style="font-size:40px;line-height: 36px"></i>
	  	</div>
  	</div>
</div>
</body>
</html>