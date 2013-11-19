<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>备件库</title>
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/SparesStore.css" rel="stylesheet">
	
</head>
<body>
	<?php 
		require_once(dirname(__FILE__)."/../../../common/head.php");
	?>
	<div class="offhead">
		<?php
			// require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
		?>
		<div id="bodyright" class="offset2">
			<legend>
				备件库
			</legend>

			<div><!-- mainbody -->
				<div>
					<div>
                    	<ul id="tabs" class="nav nav-pills">
                            <li class="active" id="liWithVin"><a href="#withVin" data-toggle="tab">车辆换件</a></li>
                            <li id="liNoVin"><a href="#noVin" data-toggle="tab">产线换件</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                    	<div id="withVin" class="tab-pane active">
                    		<form id="formWithVin" class="well form-search">
								<div>
									<div class="input-prepend input-append">
										<span class="add-on">VIN</span>
										<input id="vinText" type="text" class="span3" placeholder="请扫描/输入VIN...">
										<a id="validateVinBtn" class="btn validateVinBtn appendBtn"><i class="icon-search"></i></a>
									</div>
									<button type="button" id="btnSubmit" class="btn btn-primary btnSubmit">提交</button>
									<button type="button" id="reset" class="btn">清空</button>
									<input type="hidden" id="currentNod" name="currentNode" value="<?php echo $node?>">
									<div class="help-inline" id="vinHint">请输入VIN后回车</div>
									<div class="help-inline" id="carInfo">
										<span class="label label-info" rel="tooltip" title="流水号" id="serialNumberInfo"></span>
										<span class="label label-info" rel="tooltip" title="车系" id="seriesInfo"></span>
										<span class="label label-info" rel="tooltip" title="车型" id="typeInfo"></span>
										<span class="label label-info" rel="tooltip" title="耐寒" id="coldInfo"></span>
										<span class="label label-info" rel="tooltip" title="颜色" id="colorInfo"></span>
		                        		<span class="label label-info" rel="tooltip" title="状态" id="statusInfo"></span>
		                        		<span class="label label-info" rel="tooltip" title="库区" id="rowInfo"></span>
									</div>
								</div>
							</form>
                    	</div>
                    	<div id="noVin" class="tab-pane">
                    		<form id="formWithVin" class="well form-search">
								<div>
									<div class="input-prepend">
										<span class="add-on">线别</span>
										<select id="lineSelect" class="input-small"></select>
									</div>
									<script id="tmplLineSelect" type="text/x-jsrander">
										<option value='{{:line}}'>{{:line}}线</option>
									</script>
									<div class="input-prepend">
										<span class="add-on">车系</span>
										<select id="seriesSelect" class="input-small"></select>
									</div>
									<script id="tmplSeriesSelect" type="text/x-jsrander">
										<option value='{{:series}}'>{{:name}}</option>
									</script>
									<button type="button" id="btnSubmitNoVin" class="btn btn-primary btnSubmit">提交</button>
								</div>
							</form>
                    	</div>
                    </div>
					<div id="messageAlert" class="alert"></div>
					<div id="checkAlert"></div>
					<div class="row-fluid">
						<div id="faultsDiv" class="span4">
							<table id="faultsTable" class="table">
								<thead>
									<tr>
										<th>区域</th>
										<th>故障</th>
										<th>责任</th>
										<th>选择</th>
									</tr>
									<tr id="newFaultTr" style="display:none">
										<td>
											<span id="newLine"></span>
										</td>
										<td>
											<div>
												<input id="newFaultComponent" type='text' style="width:90px">
												<select id="newFaultMode" disabled="disabled" class="help-inline" style="width:100px"><option value="">故障模式</option></select>
											</div>
										</td>
										<td>
											-
										</td>
										<td>
											<input id="newFaultRadio" type="radio" name="choseFault" disabled>
										</td>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
						<div id="componentsDiv" class="span8">
							<div class="input-prepend">
								<span class="add-on">责任</span>
								<select id="dutySelect" class=""></select>
							</div>
							<div class="input-prepend input-append">
								<span class="add-on">换件人</span>
								<select id="teamSelect" class="input-small">
									<option value="">班组</option>
								</select>
								<select id="handlerSelect" class="input-medium" style="border-radius: 0 4px 4px 0; border-left:none;" disabled="disabled">
									<option value=""></option>
								</select>
							</div>
							<script id="tmplTeamSelect" type="text/x-jsrander">
								<option value='{{:team}}'>{{:team}}</option>
							</script>
							<script id="tmplHandlerSelect" type="text/x-jsrander">
								<option value='{{:handler_name}}'>{{:handler_name}}</option>
							</script>
							<table id="componentsTable" class="table">
								<thead>
									<tr>
										<th><a id="addComponent" rel="tooltip" data-toggle="tooltip" title="添加换件"><i class="icon-plus"></i></a></th>
										<th>数量</th>
										<th>零部件名称</th>
										<th>零部件编码</th>
										<th>供应商</th>
										<th>零部件条码</th>
										<th>连损</th>
										<th>报废</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div><!-- end of mainbody -->
		</div><!-- end of bodyright -->
	</div>
</body>
<script data-main="/bms/rjs/sparesStore.js" src="/bms/rjs/lib/require.js"></script>
</html>