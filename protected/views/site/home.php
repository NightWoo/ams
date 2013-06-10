<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>总装长沙AMS</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <link href="/bms/css/home.css" rel="stylesheet">
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/home.js"></script>
	
</head>
<body>
	<?php
		//require_once(dirname(__FILE__)."/../common/head.php");
	?>
	<div>
		<div id="welcome">你好，<a href="/bms/generalInformation/accountMaintain"><?php echo Yii::app()->user->display_name; ?></a></div>
		<div class="pull-right btnField">
			<div id="logout" class="btnIcon pull-right"></div>
		</div>
	</div>
	<div id="tileContainer" class="floatClear">
		<div id="quality" class="tile rectangle">
			<div class="title">
				<h3>质量</h3>
			</div>
			<div class="mainData pull-left">
				<h1 id="DRR">-</h1>
			</div>
			<div class="subData pull-right">
				<p id="vq1"></p>
				<p id="vq2"></p>
				<p id="vq3"></p>
			</div>
		</div>
		
		<div id="efficiency" class="tile rectangle">
			<div class="title">
				<h3>效率</h3>
				<div class="mainData pull-left">
				<h1 id="workingTimePercentage">-</h1>
			</div>
			<div class="subData pull-right">
				<p id="onLine"></p>
				<p id="checkin"></p>
				<p id="checkout"></p>
			</div>
			</div>
		</div>
			
		<div id="dataInput" class="tile square shortcut">
			<div class="title">
				<h3>数据录入</h3>
			</div>
		</div>
		
		<div id="maintain" class="tile square">
			<div class="title">
				<h3>总装维护</h3>
			</div>
		</div>
		
		<div id="carAccessControl" class="tile square shortcut">
			<div class="title">
				<h3>车辆门禁</h3>
			</div>
		</div>
		
		<div id="" class="tile square">
			<div class="title">
				<h3></h3>
			</div>
		</div>
				
		<div id="cost" class="tile rectangle floatClear">
			<div class="title">
				<h3>成本</h3>
				<div class="mainData pull-left">
				<h2 id="">￥3.45</h2>
			</div>
			<div class="subData pull-right">
				<p id=""></p>
			</div>
			</div>
		</div>
		
		<div id="safety" class="tile rectangle">
			<div class="title">
				<!-- <h3>安全</h3> -->
				<div class="mainData pull-left">
				<h3 id="">本月0事故</h3>
			</div>
			<div class="subData pull-right">
				<p id=""></p>
			</div>
			</div>
		</div>
		
		<div id="query" class="tile square shortcut">
			<div class="title">
				<h3>数据查询</h3>
			</div>
		</div>
		
		<div id="warehouseMaintain" class="tile square">
			<div class="title">
				<h3>成品库<br>维护</h3>
			</div>
		</div>
		
		<div id="" class="tile square shortcut">
			<div class="title">
				<h3></h3>
			</div>
		</div>
		
		<div id="" class="tile square">
			<div class="title">
				<h3></h3>
			</div>
		</div>
		
		<div id="manpower" class="tile rectangle floatClear">
			<div class="title">
				<h3>人力资源</h3>
				<div class="mainData pull-left">
				<h2 id="">9.8%</h2>
			</div>
			<div class="subData pull-right">
				<p id=""></p>
			</div>
			</div>
		</div>
		
		<div id="managementSystem" class="tile square">
			<div class="title">
				<h3>管理体系</h3>
			</div>
		</div>
		
		<div id="" class="tile square">
			<div class="title">
				<h3></h3>
			</div>
		</div>
				
		<div id="basicDatabase" class="tile square shortcut">
			<div class="title">
				<h3>基础<br>数据库</h3>
			</div>
		</div>

		<div id="orderMaintain" class="tile square">
			<div class="title">
				<h3>发车计划</h3>
			</div>
		</div>
		
		<div id="" class="tile square shortcut">
			<div class="title">
				<h3></h3>
			</div>
		</div>
		
		<div id="summary" class="tile square">
			<div class="title">
				<h3></h3>
			</div>
		</div>
		
	</div>
</body>
</html>
