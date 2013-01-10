<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>BMS.Ver0.1</title>
		<!-- Le styles -->
		<link href="/bms/css/bootstrap.min.css" rel="stylesheet">
		<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	    <script type="text/javascript" src="/bms/js/service.js"></script>
		<script type="text/javascript" src="/bms/js/execution/assembly/dataInput/t11.js"></script>
	</head>


	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head/execution_head.php");
			require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
		?>

    	<!-- Main体 -->		
        <div class="offset2">
            <!-- 面包屑 -->
             <div class="row">
            	<ul class="breadcrumb">
            		<li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
                	<li><a href="#">总装</a><span class="divider">&gt;</span></li>
					<li><a href="dataInputNodeSelect.html">数据录入</a><span class="divider">&gt;</span></li>
                	<li class="active"><a href="#">Ⅰ线.T11</a></li>                
            	</ul>
            </div>
			<!-- end 面包屑 -->
            
			 <div class="row"><!-- 主体 -->
				<form class="well form-search">
					<label>VIN：</label>
                    <input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText" />
                    <input type="button" class="btn btn-primary disabled" id='sub' value='提交'></input> 
					<button class="btn">清空</button>
				</form>
			</div>
			
			<div class='row' id="carInfo">
            	<table id="table" class="table table-bordered">
            		<thead>
            			<tr class="T_row_height">
            				<th class="T_chexi_width">车系</th>
            				<th class="T_vin_width">VIN</th>
            				<th class="T_chexing_width">车型</th>
            				<th class="T_yanse_width">颜色</th>
            			</tr>
            		</thead>
            		<tbody>
            			<tr>
            				<td id="series"></td>
            				<td id="vin"></td>
            				<td id="type"></td>
            				<td id="color"></td>
            			</tr>
            		</tbody>
            	</table>
            </div>    
			
		    <div class='row' id="ComponentInfo">	
			    <table class="table table-striped table-bordered" id="componentTalbe">
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
			  
			 <div>
				
			    <span>零部件条码输入：</span>
				<input type="text" class="input-medium search-query" id="compCodeText">
				
			</div>
			
			<!-- <div>现已生产：<span id="生产数">辆</span></div> -->
        </div>
    	
	</body>
</html>