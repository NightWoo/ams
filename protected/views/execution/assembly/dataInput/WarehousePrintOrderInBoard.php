<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>出库打印</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/WarehousePrintOrderInBoard.css" rel="stylesheet">	
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/common.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/dataInput/warehousePrintOrderInBoard.js"></script>
	<script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
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
            <div>
            	<legend>出库打印_板内方式订单
            		<span class="pull-right">
            			<a href="/bms/execution/warehousePrint"><i class="icon-link"></i>&nbsp;出库打印</a>
            			/
            			<a href="/bms/execution/warehousePrintExport"><i class="icon-link"></i>&nbsp;出口车批量传输</a>
            		</span>
            	</legend>
            </div>
            
   	   		<div><!-- 主体 -->
			    <div class="accordion span4" id="accordionOrderInBoard">
			    	<div class="accordion-group" id="group1">
			    		<div class="accordion-heading">
			    			<a href="#" id="refresh"><i class="icon-refresh pull-right"></i></a>
			    			<p class="text-success" id="totalOK">0</p>
			    		</div>
			    		<div id="collapse1" class="accordion-body collapse in" style="overflow-y: scroll; height:460px">
			    			<div class="accordion-inner">
			    				<div id="orderInBoard" class="">
			    					<div class="ordersContainer">
				    					<!-- <a class="btn btn-link" href="#">
											ZCDG-20130220992705@D0504001
										</a>
										<span class="label">NY</span> -->
									</div>
								</div>
			    			</div>
			    		</div>
			    	</div>
			    </div>

			    <div class="offset4">
			    	<legend id="legendDetail"><span id="orderBoardName">-</span><a class="btn btn-link" id="printAll" href="#"><i class="icon-print"></i>传输打印</a></legend>
	                <table class="table table-condensed table-hover" id="tableDetail" style="display: none">
			    		<thead>
			    			<tr>
				    			<th style="width:30px">车道</th>
				    			<th style="width:120px">VIN号</th>
				    			<th style="width:120px">出库时间</th>
				    			<th style="width:50px">证件</th>
				    			<th style="width:200px">经销商</th>
				    			<th style="width:40px">车系</th>
				    			<th style="width:150px">车型/配置</th>
				    			<th style="width:50px">耐寒性</th>
				    			<th style="width:50px">颜色</th>
				    			<th style="">发动机号</th>
			    			</tr>
			    		</thead>
			    		<tbody>
			            	<!-- <td>LGXC34CG6D1014718</td>
			            	<td>2013-04-18 09:00:00</td>
			    			<td>新证</td>
			    			<td>XXXXXXXXXXXXXX</td>
			            	<td>F0</td>
			            	<td>QCJ6480MJ/2.4L尊贵/4G69</td>
			            	<td>非耐寒</td>
			            	<td>瑞亚银</td>
			            	<td>4G69S4M1234556</td> -->
			    		</tbody>
			        </table>
			        <div >
						<div id="messageAlert" class="alert"></div>    
					</div > 
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