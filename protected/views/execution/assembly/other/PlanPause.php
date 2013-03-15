<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>计划停线</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/other/PlanPause.css" rel="stylesheet">	
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/other/planPause.js"></script>
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
            <div ><ul class="breadcrumb"><!-- 面包屑 -->
						<li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
						<li><a href="/bms/execution/home">总装</a><span class="divider">&gt;</span></li>
						<li><a href="child?node=NodeSelect">维护与帮助</a><span class="divider">&gt;</span></li>
						<li class="active">计划停线</li>
				</ul></div><!-- end 面包屑 -->
            
   	   		<div><!-- 主体 -->
				<form id="form" class="well form-search">
					<table>
						<tr>
							<td>开始时间</td>
							<td>结束时间</td>
							<td></td>
						</tr>
						<tr>
							<td>
								<input type="text" class="input-large" placeholder="开始时间..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
							</td>
							<td>
								<input type="text" class="input-large" placeholder="结束时间..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
							</td>
							<td>
								<input id="btnQuery" type="button" class="btn btn-primary" value="查询"></input>
								<input id="btnAdd" type="button" class="btn btn-success" value="新增"></input>  
							</td>
						</tr>
					</table>
				</form>
				
				<table id="tableResult" class="table table-condensed table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th id="thType">停线类型</th>
							<th id="thReason">原因描述</th>
							<th id="thHowlong" class="alignRight">时长</th>
							<th id="thPauseTime">停线时刻</th>
							<th id="thRecoverTime">恢复时刻</th>
							<th id="thEditor"></th>
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
					<!-- <ul>
						<li id="export"><a href=""><span id="totalText"></span></a></li>
					</ul> -->
				</div>
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
	</div><!-- offhead -->

<!-- new record -->
<div class="modal" id="newModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>新增</h3>
  	</div>
  	<div class="modal-body">
  		<form id="newForm" class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for="">开始时间</label>
				<div class="controls">
					<input type="text" class="input-large" placeholder="开始时间..." id="newStartTime" onClick="WdatePicker({el:'newStartTime',dateFmt:'yyyy-MM-dd HH:mm:00'});"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">结束时间</label>
				<div class="controls">
					<input type="text" class="input-large" placeholder="结束时间..." id="newEndTime" onClick="WdatePicker({el:'newEndTime',dateFmt:'yyyy-MM-dd HH:mm:00'});"/>
				</div>
			</div>
  			<div class="control-group">
				<label class="control-label" for="">原因描述</label>
				<div class="controls">
					<textarea class="input-large" id="newRemark" rows="3"></textarea>
				</div>
			</div>  	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-success" id="btnAddMore">继续新增</button>
	    <button class="btn btn-primary" id="btnAddConfirm">确认新增</button>
  	</div>
</div>
<!-- edit record -->
<div class="modal" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>编辑</h3>
  	</div>
  	<div class="modal-body">
  		<form id="editForm" class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for="">开始时间</label>
				<div class="controls">
					<input type="text" class="input-large" placeholder="开始时间..." id="editStartTime" onClick="WdatePicker({el:'editStartTime',dateFmt:'yyyy-MM-dd HH:mm:ss'});"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">结束时间</label>
				<div class="controls">
					<input type="text" class="input-large" placeholder="结束时间..." id="editEndTime" onClick="WdatePicker({el:'editEndTime',dateFmt:'yyyy-MM-dd HH:mm:ss'});"/>
				</div>
			</div>
  			<div class="control-group">
				<label class="control-label" for="">原因描述</label>
				<div class="controls">
					<textarea class="input-large" id="editRemark" rows="3"></textarea>
				</div>
			</div>  	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnEditConfirm">确认编辑</button>
  	</div>
</div>
  	
</body>
</html>