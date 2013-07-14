<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>计划停线</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<!-- <link href="/bms/css/datetimepicker.css" rel="stylesheet"> -->
	<link href="/bms/css/flick/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
	<link href="/bms/css/jquery-ui-timepicker-addon.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/other/PlanPause.css" rel="stylesheet">
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="/bms/js/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="/bms/js/jquery-ui-timepicker-addon.zh-CN.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<!-- <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.min.js"></script> -->
    <!-- <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.zh-CN.js"></script> -->
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/common.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/other/planPause.js"></script>
	<script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
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
                <legend>班次/计划停线
                </legend>
            </div>
   	   		<div><!-- 主体 -->
   	   			<ul id="tabUl" class="nav nav-pills">
                    <li class="active"><a href="#tabPlanPause" data-toggle="tab">计划停线</a></li>
                    <li><a class="queryUse" href="#tabShiftMaintain" data-toggle="tab">班次维护</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tabPlanPause">
                    	<form id="form" class="well form-search">
							<table>
								<tr>
									<td>开始时间</td>
									<td>结束时间</td>
									<td></td>
								</tr>
								<tr>
									<td>
										<input type="text" class="input-medium datetimepicker" placeholder="开始时间..." id="startTime"/>
									</td>
									<td>
										<input type="text" class="input-medium datetimepicker" placeholder="结束时间..." id="endTime"/>
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
									<th class="thType">停线类型</th>
									<th class="thHowlong alignRight">时长</th>
									<th class="thTime">停线时刻</th>
									<th class="thTime">恢复时刻</th>
									<th class="thReason">原因描述</th>
									<th class="thEditor"></th>
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
						</div>
                    </div>

                    <div class="tab-pane" id="tabShiftMaintain">
                    	<form id="form" class="well form-search">
							<table>
								<tr>
									<td>日期</td>
									<td></td>
								</tr>
								<tr>
									<td>
										<input type="text" class="input-small" placeholder="开始时间..." id="shiftDate"/>
									</td>
									<td>
										<input id="btnShiftQuery" type="button" class="btn btn-primary" value="查询"></input>
										<!-- <input id="btnShiftAdd" type="button" class="btn btn-success" value="新增"></input> -->
									</td>
								</tr>
							</table>
						</form>

						<table id="tableShiftResult" class="table table-condensed table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th id="">日期</th>
									<th id="">班次</th>
									<th id="">线别</th>
									<th id="">线速</th>
									<th class="thTime">开始时间</th>
									<th class="thTime">结束时间</th>
									<th class="thEditor"></th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
                    </div>
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