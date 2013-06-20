<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>总装录入节点</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/bms/css/execution/assembly/dataInput/NodeSelect.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
		<script type="text/javascript" src="/bms/js/head.js"></script>
    </head>
    <body>
        <?php
        	require_once(dirname(__FILE__)."/../../../common/head.php");
        ?>
		<div class="offhead">
			<?php
              require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
            ?>
			<div id="bodyright" class="offset2"><!-- Main -->				
				<div>
					<ul class="breadcrumb"><!-- 面包屑 -->
						<li>
							<a href="#">生产执行</a><span class="divider">&gt;</span>
						</li>
						<li>
							<a href="#">总装</a><span class="divider">&gt;</span>
						</li>
						<li class="active">
							数据录入
						</li>
					</ul>
				</div><!-- end 面包屑 -->				
				<div class="main"><!-- 内容主体 -->
					<div class="node-rgl node-PBS" 
						onclick="window.location.href='/bms/execution/child?view=PBS&node=PBS'"
						rel="tooltip"
						title="PBS节点">
						PBS
					</div>
					<div class="node-rgl node-T0" onclick="window.location.href='/bms/execution/child?view=T0&node=T0&line=I'">
						T0
					</div>
					<div class="node-rgl node-T11" onclick="window.location.href='/bms/execution/child?view=T11&node=T11'">
						T11
					</div>
					<div class="node-rgl node-T21" onclick="window.location.href='/bms/execution/child?view=T21&node=T21'">
						T21
					</div>
					<div class="node-rgl node-T32" onclick="window.location.href='/bms/execution/child?view=T32&node=T32'">
						T32
					</div>
					<div class="node-rgl node-C10" onclick="window.location.href='/bms/execution/child?view=C10&node=C10'">
						C10
					</div>
					<div class="node-rgl node-C21" onclick="window.location.href='/bms/execution/child?view=C21&node=C21'">
						C21
					</div>
					<div class="node-rgl node-F10" onclick="window.location.href='/bms/execution/child?view=F10&node=F10'">
						F10
					</div>
					<div class="node-rgl node-F20" onclick="window.location.href='/bms/execution/child?view=F20&node=F20'">
						F20
					</div>
					<div class="node-qg node-VQ1" onclick="window.location.href='/bms/execution/child?view=VQ1&node=VQ1'">
						VQ1
					</div>
					<div class="node-rgl node-CSCJ" onclick="window.location.href='/bms/execution/child?view=LoadExit&node=LEFT_WORK_SHOP'">
						出生<br>
						产间
					</div>
					<div class="node-rgl node-JJCJ" onclick="window.location.href='/bms/execution/child?node=ENTER_CHECK_SHOP&view=TestEntry'">
						进检<br>
						测间
					</div>
					<div class="node-rgl node-JCX" onclick="window.location.href='/bms/execution/child?node=CHECK_LINE&view=VQ2TestLineRegister'">
						检测<br>
						线
					</div>
					<div class="node-rgl node-LSKS" onclick="window.location.href='/bms/execution/child?node=ROAD_TEST_START&view=VQ2RoadTestEntry'">
						路试<br>
						开始
					</div>
					<div class="node-rgl node-LSWC" onclick="window.location.href='/bms/execution/child?node=ROAD_TEST_FINISH&view=VQ2RoadTestFinished'">
						路试<br>
						完成
					</div>
					<div class="node-qg node-VQ2" onclick="window.location.href='/bms/execution/child?node=VQ2&view=VQ2LeakTest'">
						VQ2
					</div>
					<div class="node-qg node-VQ3" onclick="window.location.href='/bms/execution/child?node=VQ3&view=VQ3'">
						VQ3
					</div>
					<div class="node-rgl node-RK" onclick="window.location.href='/bms/execution/child?node=CHECK_IN&view=WarehouseEntry'">
						入库
					</div>
					<div class="node-rgl node-CK" onclick="window.location.href='/bms/execution/child?node=CHECK_OUT&view=WarehouseExit'">
						出库
					</div>
					<div class="node-fz0 node-YBFZ" onclick="window.location.href='/bms/execution/child?node=仪表分装&view=sub&node=仪表分装&type=subInstrument'">仪表分装</div>
					<div class="node-fz0 node-HQFZ" onclick="window.location.href='/bms/execution/child?node=后桥分装&view=sub&node=后桥分装&type=subRearAxle'">后桥分装</div>
					<div class="node-fz0 node-QQFZ" onclick="window.location.href='/bms/execution/child?node=前桥分装&view=sub&node=前桥分装&type=subFrontAxle'">前桥分装</div>
					<div class="node-fz1 node-FDJFZ" onclick="window.location.href='/bms/execution/child?node=发动机分装&view=sub&node=发动机分装&type=subEngine'">发动机分装</div>
					<div class="node-VQ1YC" onclick="window.location.href='/bms/execution/child?node=VQ1异常&view=VQ1Exception'">VQ1异常</div>
					<!-- <div class="node-HJ">备件库9月20以后</div> -->
					<div class="node-vq3yc node-VQ3YC1" onclick="window.location.href='/bms/execution/child?node=VQ3异常&view=VQ3Exception'">
						<div class='yc-text'>VQ3</div>
						<div>异常</div>
					</div>
					<div class="node-vq3yc node-VQ3YC2" onclick="window.location.href='/bms/execution/child?node=VQ3异常&view=VQ3Exception'">
						<div class='yc-text'>VQ3</div>
						<div>异常</div>
					</div>
					<div class="node-vq2yc node-LYYC" onclick="window.location.href='/bms/execution/child?node=漏雨&view=VQ2LeakTestException'">
							漏雨
					</div>
					<div class="node-vq2yc node-LSYC" onclick="window.location.href='/bms/execution/child?node=路试&view=VQ2RoadTestException'">
							路试
					</div>
					<div class="node-qg node-WDI" onclick="window.location.href='/bms/execution/child?node=WDI&view=WDI'">
						WDI
					</div>
				</div><!-- end 内容主体 -->
			</div><!-- end Main -->
		</div><!-- end offhead -->
		<script type="text/javascript">
		$(document).ready(function () {
			//add head class
			$("#headAssemblyLi").addClass("active");
			$("#leftNodeSelectLi").addClass("active");
		});
		</script>    
    </body>
</html>
