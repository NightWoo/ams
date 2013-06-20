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
				<legend>节点选择
            	</legend>				
				<div class="main"><!-- 内容主体 -->
					<div class="node-rgl node-PBS" onclick="window.location.href='/bms/execution/child?view=PBS&node=PBS'"	rel="tooltip" title="PBS节点">
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
					<div class="node-rgl node-VQ1" onclick="window.location.href='/bms/execution/child?view=VQ1&node=VQ1'">
						VQ1
					</div>
					<div class="node-rgl node-JCX" onclick="window.location.href='/bms/execution/child?node=CHECK_LINE&view=VQ2TestLineRegister'">
						VQ2<br>
						检
					</div>
					<div class="node-rgl node-LSWC" onclick="window.location.href='/bms/execution/child?node=ROAD_TEST_FINISH&view=VQ2RoadTestFinished'">
						VQ2<br>
						路
					</div>
					<div class="node-rgl node-VQ2" onclick="window.location.href='/bms/execution/child?node=VQ2&view=VQ2LeakTest'">
						VQ2<br>
						雨
					</div>
					<div class="node-rgl node-VQ3" onclick="window.location.href='/bms/execution/child?node=VQ3&view=VQ3'">
						VQ3
					</div>
					<div class="node-rgl node-RK" onclick="window.location.href='/bms/execution/child?node=CHECK_IN&view=WarehouseEntry'">
						入库
					</div>
					<div class="node-rgl node-standby" onclick="window.location.href='/bms/execution/outStandby'">
						备车
					</div>
					<div class="node-rgl node-WDI" onclick="window.location.href='/bms/execution/child?node=WDI&view=WDI'">
						WDI
					</div>
					<div class="node-rgl node-CK" onclick="window.location.href='/bms/execution/child?node=CHECK_OUT&view=WarehouseExit'">
						出库
					</div>
					<div class="node-rect node-FCD" onclick="window.location.href='/bms/execution/laneManage'">发车道</div>
					<div class="node-rect node-YBFZ" onclick="window.location.href='/bms/execution/child?node=仪表分装&view=sub&node=仪表分装&type=subInstrument'">仪表</div>
					<!-- <div class="node-fz0 node-HQFZ" onclick="window.location.href='/bms/execution/child?node=后桥分装&view=sub&node=后桥分装&type=subRearAxle'">后桥分装</div> -->
					<div class="node-rect node-QQFZ" onclick="window.location.href='/bms/execution/child?node=前桥分装&view=sub&node=前桥分装&type=subFrontAxle'">前桥</div>
					<div class="node-rect node-FDJFZ" onclick="window.location.href='/bms/execution/child?node=发动机分装&view=sub&node=发动机分装&type=subEngine'">发动机</div>

					<!-- II线 -->
					<div class="node-rgl node-T0_2" onclick="window.location.href='/bms/execution/child?view=T0&node=T0_2&line=II'">
						T0
					</div>
					<div class="node-rgl node-T11_2" onclick="window.location.href='/bms/execution/child?view=T11_2&node=T11_2'">
						T11
					</div>
					<div class="node-rgl node-T21_2" onclick="window.location.href='/bms/execution/child?view=T21_2&node=T21_2'">
						T21
					</div>
					<div class="node-rgl node-T32_2" onclick="window.location.href='/bms/execution/child?view=T32_2&node=T32_2'">
						T32
					</div>
					<div class="node-rgl node-C10_2" onclick="window.location.href='/bms/execution/child?view=C10_2&node=C10_2'">
						C10
					</div>
					<div class="node-rgl node-C21_2" onclick="window.location.href='/bms/execution/child?view=C21_2&node=C21_2'">
						C21
					</div>
					<div class="node-rgl node-F10_2" onclick="window.location.href='/bms/execution/child?view=F10_2&node=F10_2'">
						F10
					</div>
					<div class="node-rgl node-F20_2" onclick="window.location.href='/bms/execution/child?view=F20&node=F20_2'">
						F20
					</div>
					<div class="node-rgl node-VQ1_2" onclick="window.location.href='/bms/execution/child?view=VQ1&node=VQ1_2'">
						VQ1
					</div>
				</div><!-- end main -->
			</div><!-- end offset-->
		</div><!-- end offhead -->
    </body>
		<script type="text/javascript">
		$(document).ready(function () {
			//add head class
			$("#headAssemblyLi").addClass("active");
			$("#leftNodeSelectLi").addClass("active");
		});
		</script>    
</html>
