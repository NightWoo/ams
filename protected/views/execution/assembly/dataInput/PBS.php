<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>PBS</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/PBS.css" rel="stylesheet">
    <link href="/bms/css/animate.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/common.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/pbs.js"></script>
	</head>
	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
		<div class="offhead"><!-- offhead -->
			<?php
			// require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
			?>
						
        	<div id="bodyright" class="offset2"><!-- main -->          
				<div>
	            	<legend><?php echo $nodeDisplayName;?>
	            	</legend>
	            </div>
				<div ><!-- 内容主体 -->
					<form id="form" class="well form-search">
						<label>VIN</label>
						<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText">
						<input type="button" class="btn btn-primary" disabled="disabled" 
							id ="btnSubmit" value ="入彩车身库"></input>
						<input type="button" class="btn" 
							id ="reset" value ="清空"></input>
						<input type="hidden" id='currentNode' name='currentNode' value='<?php echo $node?>'></input>
						<input type="hidden" id='line' name='currentNode' value='I'></input>
						<span class="help-inline" id="vinHint">请输入VIN后回车</span>
						<div class="help-inline" id="carInfo">
							<span class="label label-info" rel="tooltip" title="车系" id="infoSeries"></span>
							<span class="label label-info" rel="tooltip" title="车型" id="infoType"></span>
							<span class="label label-info" rel="tooltip" title="颜色" id="infoColor"></span>
							<span class="label label-info" rel="tooltip" title="耐寒性" id="infoColdResistant"></span>
							<span class="label label-info" rel="tooltip" title="车辆区域" id="infoStatus"></span>
						</div>
					</form>
				</div><!-- end 主体 -->

				<div>
					<div id="messageAlert" class="alert"></div>    
				</div> <!-- end 提示信息 -->

				<div class="accordion" id="accordionPlan">
	                	<div class="accordion-group">
	                		<div class="accordion-heading">
	               				<a id="planViewToggle" class="accordion-toggle" 
								data-toggle="collapse" data-parent="#accordionPlan" href="#collapsePlan"><span id="today"></span>_计划预览, 待上线 : <span id="infoCount"></span></a>
	               			</div>
	                		<div id="collapsePlan" class="accordion-body collapse">
	                			<div class="accordion-inner" id="planDiv">
	                				<table id="planTable" class="table table-condensed">
	                					<thead>
	                    					<tr>
	                    						<th class="">序号</th>
												<th class="">待上线</th>
												<th class="">车系</th>
												<th class="">车型</th>
												<th class="">配置</th>
												<th class="">耐寒性</th>
												<th class="">颜色</th>
												<th class="">年份</th>
												<!-- <th class="T_dingdanleixing_width">订单类型</th> -->
												<th class="">特殊单号</th>
												<th class="">备注</th>
	                  						</tr>
	                    				</thead>
	                    				<tbody>
	 					                   		
	         			         		</tbody>
	             				  	</table>
	                			</div>
	                		</div>
	                	</div>
	                	<div class="accordion-group">
	                		<div class="accordion-heading">
	               				<a id="planViewToggleTomorrow" class="accordion-toggle" 
								data-toggle="collapse" data-parent="#accordionPlan" href="#collapsePlanTomorrow"><span id="tomorrow"></span>_计划预览</a>
	               			</div>
	                		<div id="collapsePlanTomorrow" class="accordion-body collapse">
	                			<div class="accordion-inner" id="planTomorrowDiv">
	                				<table id="planTomorrow" class="table table-condensed">
	                					<thead>
	                    					<tr>
	                    						<th class="">序号</th>
												<th class="">待上线</th>
												<th class="">车系</th>
												<th class="">车型</th>
												<th class="">配置</th>
												<th class="">耐寒性</th>
												<th class="">颜色</th>
												<th class="">年份</th>
												<!-- <th class="T_dingdanleixing_width">订单类型</th> -->
												<th class="">特殊单号</th>
												<th class="">备注</th>
	                  						</tr>
	                    				</thead>
	                    				<tbody>
	 					                   		
	         			         		</tbody>
	             				  	</table>
	                			</div>
	                		</div>
	                	</div>                  	
					</div>

			</div><!-- end main -->
		</div><!-- end offhead -->
	</body>
</html>