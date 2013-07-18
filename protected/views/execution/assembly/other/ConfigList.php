<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>配置明细维护</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/other/ConfigList.css" rel="stylesheet">	
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/other/configList.js"></script>
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
            	<legend>配置明细
            		<span class="pull-right">
            			<a href="/bms/execution/configMaintain"><i class="icon-link"></i>&nbsp;配置维护</a>
            			/
            			<a href="/bms/execution/configPaper"><i class="icon-link"></i>&nbsp;配置跟单</a>
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
							<td>录入节点</td>
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
								<select name="" id="config" class="input-medium">
									<option value="">请先选择车系</option>
								</select>
							</td>
							<td>
								<select id="node" class="input-medium">
									<option value="" selected>所有节点</option>
									<option value="3">I线_T11</option>
									<option value="4">I线_T21</option>
									<option value="5">I线_T32</option>
									<option value="6">I线_C10</option>
									<option value="7">I线_C21</option>
									<option value="8">I线_F10</option>
									<option value="15">路试完成</option>
									<option value="202">II线_T11</option>
									<option value="203">II线_T21</option>
									<option value="204">II线_T32</option>
									<option value="205">II线_C10</option>
									<option value="206">II线_C21</option>
									<option value="207">II线_F10</option>
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
				
				<table id="tableConfigList" class="table table-condensed">
					<thead>
						<tr>
							<th>ID</th>
							<th>零部件名称</th>
							<th>追溯类型</th>
							<th>工位</th>
							<th>供应商</th>
							<th>备注</th>
							<th>更新时间</th>
							<th>更新人</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
				<div class="pagination">
					<ul>
						<li class="prePage"><a href="#"><span>&lt;</span></a></li>
						<li class="active curPage" page="1"><a href="#"><span>1</span></a></li>
						<li class="nextPage"><a href="#"><span>&gt;</span></a></li>
					</ul>
					<ul>
						<li id="export"><a href=""><span id="totalText"></span></a></li>
					</ul>
				</div>
					
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
			    <label class="control-label" for="">*&nbsp;追溯类型</label>
			    <div class="controls">
			      	<select id="newIsTrace" class="input-medium">
						<option value="0">不追溯</option>
						<option value="1" selected>单件追溯</option>
						<option value="2">批次追溯</option>
					</select>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="">*&nbsp;录入工位</label>
			    <div class="controls">
			      	<select id="newNode" class="input-medium">
						<option value="" selected>请选择</option>
						<option value="3">I线_T11</option>
						<option value="4">I线_T21</option>
						<option value="5">I线_T32</option>
						<option value="6">I线_C10</option>
						<option value="7">I线_C21</option>
						<option value="8">I线_F10</option>
						<option value="15">路试完成</option>
						<option value="202">II线_T11</option>
						<option value="203">II线_T21</option>
						<option value="204">II线_T32</option>
						<option value="205">II线_C10</option>
						<option value="206">II线_C21</option>
						<option value="207">II线_F10</option>
					</select>
			    </div>
			</div>
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
				<label class="control-label" for="">供应商</label>
				<div class="controls">
					<input type="text" id="newProviderName" class="input-medium" placeholder="请输入供应商">
					<span id="newProviderCode" class="help-inline"></span>
					<input type="hidden" id="newProviderId" value="">
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
			    <label class="control-label" for="">*&nbsp;追溯类型</label>
			    <div class="controls">
			      	<select id="editIsTrace" class="input-medium">
						<option value="0">不追溯</option>
						<option value="1">单件追溯</option>
						<option value="2">批次追溯</option>
					</select>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="">*&nbsp;录入工位</label>
			    <div class="controls">
			      	<select id="editNode" class="input-medium">
						<option value="" selected>请选择</option>
						<option value="3">I线_T11</option>
						<option value="4">I线_T21</option>
						<option value="5">I线_T32</option>
						<option value="6">I线_C10</option>
						<option value="7">I线_C21</option>
						<option value="8">I线_F10</option>
						<option value="15">路试完成</option>
						<option value="202">II线_T11</option>
						<option value="203">II线_T21</option>
						<option value="204">II线_T32</option>
						<option value="205">II线_C10</option>
						<option value="206">II线_C21</option>
						<option value="207">II线_F10</option>
					</select>
			    </div>
			</div>
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
				<label class="control-label" for="">供应商</label>
				<div class="controls">
					<input type="text" id="editProviderName" class="input-medium" placeholder="请输入供应商">
					<span id="editProviderCode"class="help-inline"></span>
					<input type="hidden" id="editProviderId" value="">
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
			    <label class="control-label" for="">*&nbsp;将明细完整复制给：</label>
			    <div class="controls">
			      	<select name="" id="clonedConfig" class="input-medium">
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