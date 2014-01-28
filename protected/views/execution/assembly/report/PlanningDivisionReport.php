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
		                    <button type="button" class="btn btn-primary help-inline" id="btnQuery"><i class="fa fa-search"></i></button>
	                	</div>
                    </span>
                    <span class="pull-right">
                        <a class="notPrintable" href="/bms/execution/report?type=PlanningDivisionFourTech"><i class="fa fa-link"></i>&nbsp;四大工艺完成</a>
                        /
                        <a class="notPrintable" href="/bms/execution/report?type=PlanningDivisionNewOrder"><i class="fa fa-link"></i>&nbsp;新增订单</a>
                        /
                        <a class="notPrintable" href="/bms/execution/report?type=PlanningDivisionSalesVolume"><i class="fa fa-link"></i>&nbsp;终端销量</a>
                        /
                        <a class="notPrintable" href="/bms/execution/warehouseCountRevise"><i class="fa fa-link"></i>&nbsp;未发值修正</a>
                    </span>
                </legend>
                <div>
					<ul id="tabs" class="nav nav-pills">
						<li class="active"><a href="#reportPanel" data-toggle="tab">生产出货完成</a></li>
						<li><a href="#smsPanel" data-toggle="tab">短信</a></li>
						<!-- <li><a href="#inventoryDeficiencyPanel" data-toggle="tab">库存与欠单</a></li> -->
						<li><a href="#operationPanel" data-toggle="tab">运营</a></li>
						<!-- <li><a href="#four" data-toggle="tab">四大工艺</a></li> -->
						<!-- <li><a href="#newOrder" data-toggle="tab">新增订单</a></li> -->
						<!-- <li><a href="#" data-toggle="tab">终端销量</a></li> -->
						<li><a href="#distributionNetworkPanel" data-toggle="tab">红蓝网</a></li>
						<!-- <li><a href="#undeliveredPanel" data-toggle="tab">未发车</a></li> -->
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

					<div class="tab-pane" id="inventoryDeficiencyPanel">
						<div class="tabbale tabs-left">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#inventoryDeficiencyTabF0" data-toggle="tab">F0</a></li>
								<li><a href="#inventoryDeficiencyTabM6" data-toggle="tab">M6</a></li>
								<li><a href="#inventoryDeficiencyTab6B" data-toggle="tab">思锐</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="inventoryDeficiencyTabF0">
									<table class="table table-bordered inventory-deficiency-F0">
										<thead>
											<tr>
												<th colspan="2">车型</th>
												<th style="width:80px">颜色</th>
												<th style="width:80px">欠单</th>
												<th style="width:80px">库存</th>
												<th style="width:80px">车间结存</th>
												<th style="width:80px">未计划</th>
											</tr>
										</thead>
										<tbody>
											<tr class="thickBorder">
												<td rowspan="20">F0实用型</td>
												<td rowspan="4">1.0L实用/非耐寒</td>
												<td>德兰黑</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>冰岛蓝</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>法兰红</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td rowspan="4">1.0L实用/助力/非耐寒</td>
												<td>德兰黑</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>冰岛蓝</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>法兰红</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td rowspan="4">1.0L实用/北京欧五</td>
												<td>德兰黑</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>冰岛蓝</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>法兰红</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td rowspan="4">1.0L实用/耐寒</td>
												<td>德兰黑</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>冰岛蓝</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>法兰红</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td rowspan="4">1.0L实用/助力/非耐寒</td>
												<td>德兰黑</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>冰岛蓝</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>法兰红</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr class="info">
												<td colspan="3" class="alignRight">合计</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr class="thickBorder">
												<td rowspan="12">F0舒适型</td>
												<td rowspan="4">1.0L舒适/非耐寒</td>
												<td>德兰黑</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>冰岛蓝</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>法兰红</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td rowspan="4">1.0L舒适/北京欧五</td>
												<td>德兰黑</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>冰岛蓝</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>法兰红</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td rowspan="4">1.0L舒适/耐寒</td>
												<td>德兰黑</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>冰岛蓝</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>法兰红</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr class="info">
												<td colspan="3" class="alignRight">合计</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr class="thickBorder">
												<td rowspan="4">F0尊贵型</td>
												<td rowspan="4">1.0L尊贵</td>
												<td>德兰黑</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>冰岛蓝</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>法兰红</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr class="info">
												<td colspan="3" class="alignRight">合计</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr class="thickBorder">
												<td rowspan="8">F0-AMT</td>
												<td rowspan="4">1.0L实用/助力/AMT</td>
												<td>德兰黑</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>冰岛蓝</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>法兰红</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td rowspan="4">1.0L舒适/AMT</td>
												<td>德兰黑</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>冰岛蓝</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>法兰红</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr class="info">
												<td colspan="3" class="alignRight">合计</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr class="thickBorder">
												<td colspan="2">特殊订单</td>
												<td class="alignRight">合计</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr class="thickBorder warning">
												<td colspan="3">总计</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="inventoryDeficiencyTabM6">
									<table class="table table-bordered inventory-deficiency-M6">
										<thead>
											<tr>
												<td>车型</td>
												<td style="width:80px">颜色</td>
												<td style="width:80px">欠单</td>
												<td style="width:80px">库存</td>
												<td style="width:80px">车间结存</td>
												<td style="width:80px">未计划</td>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td rowspan="4">2.0L舒适/483QB/有后空调</td>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>瑞亚银</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>皇家紫</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>麦加金</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr class="info">
												<td colspan="2" class="alignRight">合计</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div  class="tab-pane" id="inventoryDeficiencyTab6B">
									<table class="table table-bordered inventory-deficiency-6B">
										<thead>
											<tr>
												<td>车型</td>
												<td style="width:80px">颜色</td>
												<td style="width:80px">欠单</td>
												<td style="width:80px">库存</td>
												<td style="width:80px">车间结存</td>
												<td style="width:80px">未计划</td>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td rowspan="5">1.5TI豪华</td>
												<td>德兰黑</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>天山白</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>瑞亚银</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>巧克力棕</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr>
												<td>宝石红</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
											<tr class="info">
												<td colspan="2" class="alignRight">合计</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="operationPanel">
						<table class="table table-bordered" id="operationTable">
							<thead>
								<tr>
									<th style="width:50px">车系</th>
									<th style="width:50px">项目</th>
									<th style="width:50px">累计</th>
									<th style="width:50px">1月</th>
									<th style="width:50px">2月</th>
									<th style="width:50px">3月</th>
									<th style="width:50px">4月</th>
									<th style="width:50px">5月</th>
									<th style="width:50px">6月</th>
									<th style="width:50px">7月</th>
									<th style="width:50px">8月</th>
									<th style="width:50px">9月</th>
									<th style="width:50px">10月</th>
									<th style="width:50px">11月</th>
									<th style="width:50px">12月</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<!-- <div class="tab-pane" id="four"></div> -->
					<!-- <div class="tab-pane" id="newOrder"></div> -->
					<!-- <div class="tab-pane" id=""></div> -->
					<div class="tab-pane" id="distributionNetworkPanel">
						<div class="tabbale tabs-left">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#distributionNetworkTabF0" data-toggle="tab">F0</a></li>
								<li><a href="#distributionNetworkTabM6" data-toggle="tab">M6</a></li>
								<li><a href="#distributionNetworkTab6B" data-toggle="tab">思锐</a></li>
								<li><a href="#distributionNetworkTabG6" data-toggle="tab">G6</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="distributionNetworkTabF0">
									<table class="table table-bordered distribution-network-F0">
										<thead>
											<tr>
												<th rowspan="2">项目</th>
												<th colspan="5" class="net-blue">蓝网</th>
												<th colspan="5" class="net-red">红网</th>
												<th rowspan="2">合计</th>
											</tr>
											<tr>
												<th class="net-blue">数量</th>
												<th class="net-blue">比例</th>
												<th class="net-blue"><span clsss="month">7</span>月经销商数量</th>
												<th class="net-blue">每家经销商能力</th>
												<th class="net-blue">累计经销商数量</th>
												<th class="net-red">数量</th>
												<th class="net-red">比例</th>
												<th class="net-red"><span clsss="month">7</span>月经销商数量</th>
												<th class="net-red">每家经销商能力</th>
												<th class="net-red">累计经销商数量</th>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="distributionNetworkTabM6">
									<table class="table table-bordered distribution-network-M6">
										<thead>
											<tr>
												<th rowspan="2">项目</th>
												<th colspan="5" class="net-blue">篮网</th>
												<th colspan="5" class="net-red">红网</th>
												<th rowspan="2">合计</th>
											</tr>
											<tr>
												<th class="net-blue">数量</th>
												<th class="net-blue">比例</th>
												<th class="net-blue"><span clsss="month">7</span>月经销商数量</th>
												<th class="net-blue">每家经销商能力</th>
												<th class="net-blue">累计经销商数量</th>
												<th class="net-red">数量</th>
												<th class="net-red">比例</th>
												<th class="net-red"><span clsss="month">7</span>月经销商数量</th>
												<th class="net-red">每家经销商能力</th>
												<th class="net-red">累计经销商数量</th>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="distributionNetworkTab6B">
									<table class="table table-bordered distribution-network-6B">
										<thead>
											<tr>
												<th rowspan="2">项目</th>
												<th colspan="5" class="net-blue">篮网</th>
												<th colspan="5" class="net-red">红网</th>
												<th rowspan="2">合计</th>
											</tr>
											<tr>
												<th class="net-blue">数量</th>
												<th class="net-blue">比例</th>
												<th class="net-blue"><span clsss="month">7</span>月经销商数量</th>
												<th class="net-blue">每家经销商能力</th>
												<th class="net-blue">累计经销商数量</th>
												<th class="net-red">数量</th>
												<th class="net-red">比例</th>
												<th class="net-red"><span clsss="month">7</span>月经销商数量</th>
												<th class="net-red">每家经销商能力</th>
												<th class="net-red">累计经销商数量</th>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="distributionNetworkTabG6">
									<table class="table table-bordered distribution-network-G6">
										<thead>
											<tr>
												<th rowspan="2">项目</th>
												<th colspan="5" class="net-blue">篮网</th>
												<th colspan="5" class="net-red">红网</th>
												<th rowspan="2">合计</th>
											</tr>
											<tr>
												<th class="net-blue">数量</th>
												<th class="net-blue">比例</th>
												<th class="net-blue"><span clsss="month">7</span>月经销商数量</th>
												<th class="net-blue">每家经销商能力</th>
												<th class="net-blue">累计经销商数量</th>
												<th class="net-red">数量</th>
												<th class="net-red">比例</th>
												<th class="net-red"><span clsss="month">7</span>月经销商数量</th>
												<th class="net-red">每家经销商能力</th>
												<th class="net-red">累计经销商数量</th>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="undeliveredPanel">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th rowspan="2">车系</th>
									<th rowspan="2">未发</th>
									<th colspan="3">汽车销售公司-发运</th>
									<th colspan="5">汽车销售公司-库存</th>
								</tr>
								<tr>
									<th style="width:80px">总订单</th>
									<th style="width:80px">限制发运</th>
									<th style="width:80px">总计</th>
									<th style="width:80px">可发欠单</th>
									<th style="width:80px">昨日已发</th>
									<th style="width:80px">库存</th>
									<th style="width:80px">库存不足</th>
									<th style="width:80px">昨日补库</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>F0</td>
									<td>0</td>
									<td>0</td>
									<td><input type="text" class="input-mini limit" value="0"></td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
								</tr>
								<tr>
									<td>M6</td>
									<td>0</td>
									<td>0</td>
									<td><input type="text" class="input-mini limit" value="0"></td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
								</tr>
								<tr>
									<td>思锐</td>
									<td>0</td>
									<td>0</td>
									<td><input type="text" class="input-mini limit" value="0"></td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
								</tr>
								<tr>
									<td>合计</td>
									<td>0</td>
									<td>0</td>
									<td><input type="text" class="input-mini limit" value="0"></td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</body>
	<script data-main="/bms/rjs/planningDivisionReport.js" src="/bms/rjs/lib/require.js"></script>
</html>