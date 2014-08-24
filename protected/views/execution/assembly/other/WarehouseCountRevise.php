<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>未发值修正</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/common.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/other/warehouseCountRevise.js"></script>
</head>
<body>

	<?php
		require_once(dirname(__FILE__)."/../../../common/head.php");
	?>
	<div class="offhead">
	   <?php
		// require_once(dirname(__FILE__)."/../../../common/left/general_database_left.php");
		?>

        <div id="bodyright" class="offset2"><!-- 页体 -->
        	<div class="container">

	            <div>
	                <legend>未发值修正
	                	<span class="pull-right">
	                        <a class="notPrintable" href="/bms/execution/report?type=PlanningDivisionReport"><i class="fa fa-link"></i>&nbsp;计划处日报</a>
	                    </span>
	                </legend>
	            </div>
	   	   		<div><!-- 主体 -->
	            	<form id="form" class="well form-search">
						<table>
							<tr>
								<td>F0</td>
								<td>M6</td>
								<td>思锐</td>
								<td>G6</td>
							</tr>
							<tr>
								<td>
									<input type="text" class="input-small revise" placeholder="未发初始值..." id="undistribuedF0" series="F0" counttype="未发"/>
								</td>
								<td>
									<input type="text" class="input-small revise" placeholder="未发初始值..." id="undistribuedM6" series="M6" counttype="未发"/>
								</td>
								<td>
									<input type="text" class="input-small revise" placeholder="未发初始值..." id="undistribued6B" series="6B" counttype="未发"/>
								</td>
								<td>
									<input type="text" class="input-small revise" placeholder="未发初始值..." id="undistribuedG6" series="G6" counttype="未发"/>
								</td>
								<td>
									<!-- <input id="btnShiftQuery" type="button" class="btn btn-primary" value="查询"></input> -->
									<input id="btnUndistributedConfirm" type="button" class="btn btn-danger" value="修改"></input>
								</td>
							</tr>
						</table>
					</form>
			  	</div><!-- end of 主体 -->
	        </div>
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
					<input type="text" class="input-large" placeholder="开始时间..." id="newStartTime" onClick="WdatePicker({el:'newStartTime',dateFmt:'yyyy-MM-dd HH:mm:ss'});"/>
					<!-- <input type="text" class="input-large datetimepicker" placeholder="开始时间..." id="newStartTime"/> -->
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">结束时间</label>
				<div class="controls">
					<input type="text" class="input-large" placeholder="结束时间..." id="newEndTime" onClick="WdatePicker({el:'newEndTime',dateFmt:'yyyy-MM-dd HH:mm:ss'});"/>
					<!-- <input type="text" class="input-large datetimepicker" placeholder="结束时间..." id="newEndTime"/> -->
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
					<!-- <input type="text" class="input-large datetimepicker" placeholder="开始时间..." id="editStartTime"/> -->
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">结束时间</label>
				<div class="controls">
					<input type="text" class="input-large" placeholder="结束时间..." id="editEndTime" onClick="WdatePicker({el:'editEndTime',dateFmt:'yyyy-MM-dd HH:mm:ss'});"/>
					<!-- <input type="text" class="input-large datetimepicker" placeholder="结束时间..." id="editEndTime"/> -->
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

<div class="modal" id="editShiftModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>编辑</h3>
  	</div>
  	<div class="modal-body">
  		<form id="editShiftForm" class="form-horizontal">
  			<div class="control-group">
				<label class="control-label" for="">线速</label>
				<div class="controls">
					<input type="text" class="input-small" placeholder="线束..." id="editLineSpeed"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">开始时间</label>
				<div class="controls">
					<input type="text" class="input-large" placeholder="开始时间..." id="editShiftStartTime" onClick="WdatePicker({el:'editShiftStartTime',dateFmt:'yyyy-MM-dd HH:mm:ss'});"/>
					<!-- <input type="text" class="input-large datetimepicker" placeholder="开始时间..." id="editShiftStartTime"/> -->
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">结束时间</label>
				<div class="controls">
					<input type="text" class="input-large" placeholder="开始时间..." id="editShiftEndTime" onClick="WdatePicker({el:'editShiftEndTime',dateFmt:'yyyy-MM-dd HH:mm:ss'});"/>
					<!-- <input type="text" class="input-large datetimepicker" placeholder="结束时间..." id="editShiftEndTime"/> -->
				</div>
			</div>
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnEditShiftConfirm">确认编辑</button>
  	</div>
</div>

</body>
</html>