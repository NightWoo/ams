<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>数据抛送</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/other/DataThrow.css" rel="stylesheet">	
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/other/dataThrow.js"></script>
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
	            	<legend>VINM下线
	            		<span class="pull-right">
	            		</span>
	            	</legend>
					<div id="formWarehouse" class="well form-inline">
						<div class="input-prepend  input-append">
							<span class="add-on">VIN</span>
							<input type="text" class="vinText span3" placeholder="请扫描/输入VIN..." id="vinAssembly" />
							<a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
						</div>
						<input type="button" class="btn btn-primary btn-commit" id="btnFinish" value="下线抛送"></input>
					</div>
	            </div>

	            <div>
	            	<legend>VINM入库/出库
	            		<span class="pull-right">
	            		</span>
	            	</legend>
					<div id="formWarehouse" class="well form-inline">
						<div class="input-prepend  input-append">
							<span class="add-on">VIN</span>
							<input type="text" class="vinText span3" placeholder="请扫描/输入VIN..." id="vinWarehouse" />
							<a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
						</div>
						<input type="button" class="btn btn-primary btn-commit" id="btnIn" value="入库抛送"></input>
						<input type="button" class="btn btn-primary btn-commit" id="btnOut" value="出库抛送"></input>
					</div>
	            </div>

	            <div>
	            	<legend>铭牌打印
	            		<span class="pull-right">
	            		</span>
	            	</legend>
					<div id="formMark" class="well form-inline">
						<div class="input-prepend input-append">
							<span class="add-on">VIN</span>
							<input type="text" class="vinText span3" placeholder="请扫描/输入VIN..." id="vinMark" />
							<a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
						</div>
						<input type="button" class="btn btn-primary btn-commit" id ="btnMark" value ="抛送"></input>
					</div>
				</div>

	            <div>
	            	<legend>合格证&厂检单打印
	            		<span class="pull-right">
	            		</span>
	            	</legend>
					<div id="formCertificate" class="well form-inline">
						<div class="input-prepend input-append">
							<span class="add-on">VIN</span>
							<input type="text" class="vinText span3" placeholder="请扫描/输入VIN..." id="vinCertificate" />
							<a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
						</div>
						<input type="button" class="btn btn-primary btn-commit" id ="btnCertificate" value ="抛送"></input>
					</div>
				</div>

				<div>
                    <div id="messageAlert" class="alert"></div>    
                </div> 	
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
	</div><!-- offhead -->
</body>
</html>