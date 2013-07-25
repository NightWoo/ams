<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>随车附件明细维护</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/other/AccessoryListMaintain.css" rel="stylesheet">	
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/other/accessoryListMaintain.js"></script>
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
            <div>
            	<legend>随车附件明细维护
            		<span class="pull-right">
            			<a href="/bms/execution/orderConfigMaintain"><i class="icon-link"></i>&nbsp;订单配置</a>
            			/
            			<a href="/bms/execution/configMaintain"><i class="icon-link"></i>&nbsp;生产配置</a>
            		</span>
            	</legend>
            </div>
   	   		<div><!-- 主体 -->
				<form id="form" class="well form-search">
					<table>
						<tr>
							<td>车系</td>
							<td>车型</td>
							<td>配置名称</td>
							<td></td>
						</tr>
						<tr>
							<td>
								<select name="" id="carSeries" class="input-small">
									<option value="" selected></option>
									<option value="F0">F0</option>
									<option value="M6">M6</option>
									<option value="6B">思锐</option>
								</select>
							</td>
							<td>
								<select name="" id="carType" class="input-xlarge">
									<option value="">请先选择车系</option>
								</select>
							</td>
							<td>
								<select name="" id="orderConfig" class="input-medium">
									<option value="">请先选择车系</option>
								</select>
							</td>
							<td>
								<input type="button" class="btn btn-primary" id="btnQuery" value="查询"></input>   
								<input id="btnAdd" type="button" class="btn btn-success" value="新增"></input>
								<input type="button" class="btn btn-danger" id="btnCopy" value="复制"></input> 
							</td>
						</tr>
					</table>
				</form>
				
				<table id="tableAccessoryList" class="table table-condensed">
					<thead>
						<tr>
							<th>ID</th>
							<th>零部件编号</th>
							<th>零部件名称</th>
							<th>备注</th>
							<th>更新时间</th>
							<th>更新人</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
					
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
       	</div><!-- offhead -->
<!-- new record -->
<div class="modal" id="newModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>[配置名称]</h3>
  	</div>
  	<div class="modal-body">
  		<form id="" class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;零部件</label>
				<div class="controls">
					<input type="text" id="newComponentName" class="input-medium" placeholder="请输入零部件名称">
					<select id="newComponentCode" class="input-xlarge">
						<option value="" selected></option>
					</select>
				</div>
			</div>
  			<div class="control-group">
				<label class="control-label" for="">备注</label>
				<div class="controls">
					<textarea class="input-xlarge" id="newRemark" rows="2"></textarea>
				</div>
			</div>  	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true" id="btnAddClose">关闭</button>
		<button class="btn btn-success" id="btnAddMore">继续新增</button>
	    <button class="btn btn-primary" id="btnNewConfirm">确认新增</button>
  	</div>
</div>

<!-- edit record -->
<div class="modal" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>[配置名称]</h3>
  	</div>
  	<div class="modal-body">
  		<form id="" class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;零部件</label>
				<div class="controls">
					<input type="text" id="editComponentName" class="input-medium" placeholder="请输入零部件名称">
					<select id="editComponentCode" class="input-xlarge">
						<option value="" selected></option>
					</select>
				</div>
			</div>
  			<div class="control-group">
				<label class="control-label" for="">备注</label>
				<div class="controls">
					<textarea class="input-xlarge" id="editRemark" rows="2"></textarea>
				</div>
			</div>  	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnEditConfirm">确认编辑</button>
  	</div>
</div>

<!-- copy configlist -->
<div class="modal" id="copyModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>复制[配置名称]</h3>
  	</div>
  	<div class="modal-body">
  		<form id="" class="form-horizontal">
  			<div class="control-group">
			    <label id="cloneLabel" class="control-label" for="">*&nbsp;将随车附件明细完整复制给：</label>
			    <div class="controls">
			      	<select name="" id="clonedConfig" class="input-large">
						<option value=""></option>
					</select>
					<span class="help-inline"><p class="text-error">此操作将覆盖已存在的明细，且不可恢复，请谨慎！</p></span>
			    </div>
			</div>
			
				  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnCopyConfirm">确认复制</button>
  	</div>
</div>
  	
</body>
</html>