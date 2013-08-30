<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>计划处日报</title>
		<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
		<link href="/bms/css/datetimepicker.css" rel="stylesheet" media="screen">
		<link href="/bms/css/execution/assembly/report/PlanningDivisionReport.css" rel="stylesheet" media="screen">
	</head>
	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
		<div class="offhead">
			<div class="container">
				<legend>计划处日报
                    <span class="">
                    	<div id="timeQueryInputDiv" class="input-append">
			                <input type="text" class="input-small"  placeholder="日期..." id="startTime"/>
		                    <button type="button" class="btn help-inline" id="btnQuery"><i class="icon-search"></i></button>
	                	</div>
                    </span>
                    <!-- <span class="pull-right printable">
                        <div class="logo"><img src="/bms/img/byd-auto.jpg" alt="" ></div>
                    </span> -->
                    <!-- <span class="pull-right">
                        <a class="notPrintable" href="/bms/execution/report?type=QualityReport"><i class="icon-link"></i>&nbsp;质量报表</a>
                    </span> -->
                </legend>
				<div>
					<table id="tableDaily" class="table table-bordered table-condensed table-striped">
						<thead>
							<tr>
								<th rowspan="2">车系</th>
								<th rowspan="2">款型</th>
								<th colspan="3">日上线</th>
								<th colspan="2">月上线</th>
								<th colspan="3">日入库</th>
								<th colspan="2">月入库</th>
								<th colspan="2">年入库</th>
								<th colspan="2">日出货</th>
								<th colspan="2">月出货</th>
								<th colspan="2">年出货</th>
								<th colspan="2">库存</th>
							</tr>
							<tr>
								<!-- 日上线 -->
								<th>国内</th>
								<th>出口</th>
								<th>总</th>
								<!-- 月上线 -->
								<th>国内</th>
								<th>出口</th>
								<!-- 日入库 -->
								<th>国内</th>
								<th>出口</th>
								<th>总</th>
								<!-- 月入库 -->
								<th>国内</th>
								<th>出口</th>
								<!-- 年入库 -->
								<th>国内</th>
								<th>出口</th>
								<!-- 日出货 -->
								<th>国内</th>
								<th>出口</th>
								<!-- 月出货 -->
								<th>国内</th>
								<th>出口</th>
								<!-- 年出货 -->
								<th>国内</th>
								<th>出口</th>
								<!-- 库存 -->
								<th>国内</th>
								<th>出口</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
	<script data-main="/bms/rjs/planningDivisionReport.js" src="/bms/rjs/lib/require.js"></script>
</html>