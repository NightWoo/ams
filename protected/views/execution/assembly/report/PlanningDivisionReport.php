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
		                    <button type="button" class="btn help-inline" id="btnQuery"><i class="fa fa-search"></i></button>
	                	</div>
                    </span>
                    <span class="pull-right">
                        <a class="notPrintable" href="/bms/execution/warehouseCountRevise"><i class="fa fa-link"></i>&nbsp;未发值修正</a>
                    </span>
                </legend>
                <div>
					<ul id="tabs" class="nav nav-pills">
						<li  class="active"><a href="#reportPanel" data-toggle="tab">日报</a></li>
						<li><a href="#smsPanel" data-toggle="tab">短信</a></li>
					</ul>
				</div>
				<div id="tabContent" class="tab-content">
					<div class="tab-pane active" id="reportPanel">
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
					<div class="tab-pane" id="smsPanel">
						<textarea rows="12" class="textarea span6" id="0800"></textarea>
						<textarea rows="12" class="textarea span6" id="1730" style="margin-left:15px"></textarea>
						<table id="tableSms" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>时间</th>
									<th>车系</th>
									<th>上线</th>
									<th>入库</th>
									<th>发车</th>
									<th>已发</th>
									<th>已入</th>
									<th>库存</th>
									<th>未发</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</body>
	<script data-main="/bms/rjs/planningDivisionReport.js" src="/bms/rjs/lib/require.js"></script>
</html>