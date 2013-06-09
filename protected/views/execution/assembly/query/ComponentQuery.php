<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>零部件查询</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/query/ComponentQuery.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">
		<!-- <link href="/bms/css/datetimepicker.css" rel="stylesheet"> -->
		<link href="/bms/css/flick/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
		<link href="/bms/css/jquery-ui-timepicker-addon.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript" src="/bms/js/jquery-ui-timepicker-addon.zh-CN.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.js"></script>
        <!-- <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.min.js"></script> -->
        <!-- <script type="text/javascript" src="/bms/js/bootstrap-datetimepicker.zh-CN.js"></script> -->
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/query/componentQuery.js"></script>
        <!-- <script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script> -->
	</head>


	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
        <div class="offhead">
            <?php
            require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
            ?>

        	<!-- Main体 -->	
    		
            <div id="bodyright" class="offset2">
                <div>
                    <legend>零部件查询
                    </legend>
                </div>
                <form id="form" class="well form-search">
					<table>
						<tr>
							<td class="alignRight"><label>VIN&nbsp;&nbsp;</label></td>
							<td><input type="text" class="span3" placeholder="请输入至少9位VIN..." id="vinText" /></td>
							<td class="alignRight"><label>&nbsp;&nbsp;&nbsp;&nbsp;物料条码&nbsp;&nbsp;</label></td>
							<td><input type="text" class="span3" placeholder="请输入..." id="barText" /></td>
							<td>
								<input type="button" class="btn btn-primary" id='btnQuery' value='查询'></input>   
								<!-- <input id="btnExport" class='btn btn-success' type="button" value="全部导出"></input> -->
								<select name="" id="series" class="input-small">
									<option value="f0">F0</option>
									<option value="m6">M6</option>
									<option value="6b">思锐</option>
								</select>
							</td>
							
						</tr>
						<tr>
							<td class="alignRight"><label>零部件&nbsp;&nbsp;</label></td>
							<td><input type="text" class="span3" placeholder="请输入..." id="componentText" /></td>
							<td class="alignRight"><label>供应商&nbsp;&nbsp;</label></td>
							<td><input type="text" class="span3" placeholder="请输入..." id="providerText" /></td>
							<td>
								<!-- <label id="labelF0" class="checkbox">
									<input type="checkbox" id="checkboxF0" value="F0">F0</input>
								</label>
								<label class="checkbox">
									<input type="checkbox" id="checkboxM6" value="M6">M6</input>
								</label>
								<label class="checkbox">
									<input type="checkbox" id="checkbox6B" value="6B">思锐</input>
								</label> -->
								
							</td>
						</tr>
						<tr>
							<td class="alignRight"><label>开始时间&nbsp;&nbsp;</label></td>
							<!-- <td><input type="text" class="span3" placeholder="请输入开始时间..." id="startTime" onClick="WdatePicker({el:'startTime',dateFmt:'yyyy-MM-dd HH:00'});"/></td> -->
							<td><input type="text" class="span3" placeholder="请输入开始时间..." id="startTime"/></td>
							<td class="alignRight"><label>结束时间&nbsp;&nbsp;</label></td>
							<!-- <td><input type="text" class="span3" placeholder="请输入结束时间..." id="endTime" onClick="WdatePicker({el:'endTime',dateFmt:'yyyy-MM-dd HH:00'});"/></td> -->
							<td><input type="text" class="span3" placeholder="请输入结束时间..." id="endTime"/></td>
							<td>
								<select name="" id="selectNode" class="input-medium">
									<option value="">所有节点</option>
									<option value="T11">T11</option>
									<option value="T21">T21</option>
									<option value="T32">T32</option>
									<option value="C10">C10</option>
									<option value="C21">C21</option>
									<option value="F10">F10</option>
									<option value="ROAD_TEST_FINISH">VQ2动态.路试结束</option>
								</select>
							</td>
						</tr>
					</table>  
                </form>
                <div class="divLoading">
                	<span><i class="icon-spinner icon-spin icon-4x" style="height:1em;"></i></span>
                </div>   
                <!--<div style="display:none">
                    <h5 class='pull-left'>查询结果:<span id='totalText'></span></h5>  
				 </div>-->
				 <div id="pagination" class="pagination" style="display: none;">
                        <ul>
                            <li id="export"><a href=""><span id="total"></span></a></li>
                        </ul>
                        <ul>
                            <li id="firstPage"><a href="#"><span>&lt;&lt;</span></a></li>
                            <li id="prePage" class="prePage"><a href="#"><span>&lt;</span></a></li>
                            <li id="curPage" class="active curPage" page="1"><a href="#"><span>1</span></a></li>
                            <li id="nextPage" class="nextPage"><a href="#"><span>&gt;</span></a></li>
                            <li id="lastPage"><a href="#"><span>&gt;&gt;</span></a></li>
                        </ul>
                    </div>
                    <table id="resultTable" class="table table-bordered" style="display: none;">
                        <thead>
                            <tr>
                                <th>车系</th>
                                <th>车型</th>
                                <th>VIN号</th>
                                <th>零部件名称</th>
                                <th>零部件条码</th>
                                <th>供应商</th>
                                <th>节点</th>
                                <th>录入人员</th>
                                <th>录入时间</th>
                                <th>修改时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    
            </div>
        </div>
	</body>
</html>
