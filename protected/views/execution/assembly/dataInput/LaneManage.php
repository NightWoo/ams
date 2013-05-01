<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>发车道管理</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/LaneManage.css" rel="stylesheet">	
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/common.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/dataInput/laneManage.js"></script>
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
            	<legend>发车道管理
			    	<a href="#" id="refresh"><i class="icon-refresh"></i></a>
            		<span class="pull-right">
            			<!-- <a href="/bms/execution/orderMaintain"><i class="icon-link"></i>&nbsp;订单维护</a> -->
            		</span>
            	</legend>
            </div>
            
   	   		<div><!-- 主体 -->
			    <div>
	                <table id="tableBoard" class="table table-condensed table-bordered" style="font-size:14px;">
	                    <thead>
	                        <tr>
	                            <th style="width:80px;text-align:center;">备板编号</th>
	                            <th style="width:40px;text-align:center;">车道</th>
	                            <th style="width:40px;text-align:center;" id="laneCount">-</th>
                                <th style="text-align:center;">进度</th>
	                            <th style="width:150px">激活时间</th>
	                            <th style="width:150px">备齐时间</th>
	                            <th style="width:150px">完成时间</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    </tbody>
	                </table>
			    </div>
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
	</div><!-- offhead -->
</body>
</html>