<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>零部件清单</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
    <link href="/bms/css/generalInformation/basicData/componentMaintain.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/generalInformation/basicData/componentMaintain.js"></script>
</head>
<body>
<?php
	require_once(dirname(__FILE__)."/../../common/head.php");
?>
    <div id="bodymain" class="offhead">
       <?php
        require_once(dirname(__FILE__)."/../../common/left/general_database_left.php");
        ?>
     
        <div id="bodyright" class="offset2"><!-- 页体 -->
        	<!--<div style="position:absolute;top:50%;left:0;" id="chevron">
				<i class="fa fa-chevron-right" style="opacity:0.3"> </i>
			</div>-->
            <div><!-- breadcrumb -->
            	<ul class="breadcrumb">
            		<li><a href="#">综合信息</a><span class="divider">&gt;</span></li>
                	<li><a href="#">基础数据库</a><span class="divider">&gt;</span></li>
                	<li class="active">零部件清单</li>            
            	</ul>
            </div><!-- end of breadcrumb -->
            
   	   		<div><!-- 主体 -->
				<div>

					<div>
                    	<ul id="tabs" class="nav nav-pills">
                            <li class="active" id="liF0"><a href="#F0" data-toggle="tab">F0</a></li>
                            <li id="liM6"><a href="#M6" data-toggle="tab">M6</a></li>
                            <li id="li6B"><a href="#6B" data-toggle="tab">思锐</a></li>
                            <li id="liG6"><a href="#G6" data-toggle="tab">G6</a></li>
                        </ul>
                    </div>

           	  		<div id="formComponent" class="well form-search">

						<div class="tab-content">
							<div id="F0" class="tab-pane active">
								<form id="formF0">
									<table>
										<tr>
											<td><label>类别</label></td>
											<td><label>零部件名称</label></td>
											<td><label>编号</label></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>
												<select id="selectCategoryF0" class="input-small">
													<option value="0" selected>全部</option>
													<option value="1">车身</option>
													<option value="2">动力总成</option>
													<option value="3">底盘</option>
													<option value="4">电器</option>
													<option value="5">内外饰</option>
													<option value="6">附件</option>
													<option value="7">其他</option>
												</select>
											</td>
											<td><input id="inputNameF0" type="text" class="span3" placeholder="零部件名称"></td>
											<td><input id="inputCodeF0" type="text" class="span2" placeholder="零部件编号"></td>
											<td>
												<button id="btnQueryF0" type="reset" class="btn btn-primary">查询</button>                       	
												<button id="btnAddF0" type="reset" class="btn">新增</button>
											</td>
											<td>
												<label class="checkbox">
													<input type="checkbox" id="isFaultF0" value="1" checked>故障件
												</label>
											</td>
										</tr>
									</table>
                				</form>     									
							</div>

							<div id="M6" class="tab-pane">
								<form id="formM6">
										<table>
											<tr>
												<td><label>类别</label></td>
												<td><label>零部件名称</label></td>
												<td><label>编号</label></td>
												<td><label></label></td>
												<td></td>
												<td></td>
											</tr>
											<tr>
												<td>
													<select id="selectCategoryM6" class="input-small">
														<option value="0" selected>全部</option>
														<option value="1">车身</option>
														<option value="2">动力</option>
														<option value="3">底盘</option>
														<option value="4">电器</option>
														<option value="5">内外饰</option>
														<option value="6">附件</option>
														<option value="7">其他</option>
													</select>
												</td>
												<td><input id="inputNameM6" type="text" class="span3" placeholder="零部件名称"></td>
												<td><input id="inputCodeM6" type="text" class="span2" placeholder="零部件编号"></td>
												<td>
													<button id="btnQueryM6" type="reset" class="btn btn-primary">查询</button>                       	
													<button id="btnAddM6" type="reset" class="btn">新增</button>
												</td>
												<td>
													<label class="checkbox">
														<input type="checkbox" id="isFaultM6" value="1" checked>故障件
													</label>
												</td>
											</tr>
										</table>
                				</form>
							</div>

							<div id="6B" class="tab-pane">
								<form id="form6B">
										<table>
											<tr>
												<td><label>类别</label></td>
												<td><label>零部件名称</label></td>
												<td><label>编号</label></td>
												<td><label></label></td>
												<td></td>
												<td></td>
											</tr>
											<tr>
												<td>
													<select id="selectCategory6B" class="input-small">
														<option value="0" selected>全部</option>
														<option value="1">车身</option>
														<option value="2">动力</option>
														<option value="3">底盘</option>
														<option value="4">电器</option>
														<option value="5">内外饰</option>
														<option value="6">附件</option>
														<option value="7">其他</option>
													</select>
												</td>
												<td><input id="inputName6B" type="text" class="span3" placeholder="零部件名称"></td>
												<td><input id="inputCode6B" type="text" class="span2" placeholder="零部件编号"></td>
												<td>
													<button id="btnQuery6B" type="reset" class="btn btn-primary">查询</button>                       	
													<button id="btnAdd6B" type="reset" class="btn">新增</button>
												</td>
												<td>
													<label class="checkbox">
														<input type="checkbox" id="isFault6B" value="1" checked>故障件
													</label>
												</td>
											</tr>
										</table>
                				</form>
							</div>

							<div id="G6" class="tab-pane">
								<form id="formG6">
										<table>
											<tr>
												<td><label>类别</label></td>
												<td><label>零部件名称</label></td>
												<td><label>编号</label></td>
												<td><label></label></td>
												<td></td>
												<td></td>
											</tr>
											<tr>
												<td>
													<select id="selectCategoryG6" class="input-small">
														<option value="0" selected>全部</option>
														<option value="1">车身</option>
														<option value="2">动力</option>
														<option value="3">底盘</option>
														<option value="4">电器</option>
														<option value="5">内外饰</option>
														<option value="6">附件</option>
														<option value="7">其他</option>
													</select>
												</td>
												<td><input id="inputNameG6" type="text" class="span3" placeholder="零部件名称"></td>
												<td><input id="inputCodeG6" type="text" class="span2" placeholder="零部件编号"></td>
												<td>
													<button id="btnQueryG6" type="reset" class="btn btn-primary">查询</button>                       	
													<button id="btnAddG6" type="reset" class="btn">新增</button>
												</td>
												<td>
													<label class="checkbox">
														<input type="checkbox" id="isFaultG6" value="1" checked>故障件
													</label>
												</td>
											</tr>
										</table>
                				</form>
							</div>
						</div>  <!-- div form  end -->
           	  			               
						                 	
                    </div> 
					<div>
						<table id="tableComponent" class="table table-condensed">
							<thead>
								<tr>
									<th>车系</th>
									<th>类别</th>
									<th>零部件编号</th>
									<th>零部件名称</th>
									<th>简码</th>
									<th>故障件</th>
									<th>供应商</th>
									<th>单价</th>
									<th>备注</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
						<div class="pagination">
							<ul>
								<li><a href="#"><span>全部导出</span></a></li>
							</ul>
							<ul>
								<li class="prePage"><a href="#"><span>&laquo;</span></a></li>
								<li class="active curPage" page="1"><a href="#"><span id="curPage">1</span></a></li>
								<li class="nextPage"><a href="#"><span>&raquo;</span></a></li>
							</ul>
						</div>                         
					</div> <!--end of tableComponent-->                                          
                </div>
                <!--<div id="info" class="alert alert-success">LGXC16DGXC1234566已提交，请输入下一辆车VIN</div> -->
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
       	</div><!-- offhead -->
  	
<!-- new record -->
<div class="modal" id="newModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>新增</h3>
  	</div>
  	<div class="modal-body">
  		<form class="form-horizontal">
  			<div class="control-group">
			    <label class="control-label" for="newSeries">车系</label>
			    <div class="controls">
			      	<!-- <input type="text" id="newSeries" placeholder="Old Password"> -->
			      	<select id="newSeries" class="span2" disabled>
						<option value="F0">F0</option>
						<option value="M6">M6</option>
						<option value="6B">思锐</option>
						<option value="G6">G6</option>
					</select>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="newCate">类别</label>
			    <div class="controls">
			      	<!-- <input type="text" id="newCate" placeholder="Old text"> -->
			      	<select id="newCate" class="span2">
						<option value="1">车身</option>
						<option value="2">动力总成</option>
						<option value="3">底盘</option>
						<option value="4">电器</option>
						<option value="5">内外饰</option>
						<option value="6">附件</option>
						<option value="7">其他</option>
						<option value="8">门系统</option>
					</select>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="newCode">零部件编号</label>
			    <div class="controls">
			      	<input type="text" id="newCode" placeholder="零部件编号">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="newCode">SAP编号</label>
			    <div class="controls">
			      	<input type="text" id="newSapCode" placeholder="零部件编号">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="newName">官方名称</label>
			    <div class="controls">
			      	<input type="text" id="newName" placeholder="零部件官方名称">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="newDisplayName">使用名称</label>
			    <div class="controls">
			      	<input type="text" id="newDisplayName" placeholder="零部件使用名称">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="newIsFault">故障件</label>
			    <div class="controls">
			      	<input type="checkbox" id="newIsFault">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="newSimpleCode">简码</label>
			    <div class="controls">
			      	<input type="text" id="newSimpleCode" placeholder="简码">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="newSimpleCode">单价</label>
			    <div class="controls">
			      	<input type="text" id="newUnitPrice" placeholder="单价">
			      	<span clses="help-inline">如：1.21</span>
			    </div>
			</div>	  
			<div class="control-group">
			    <label class="control-label" for="newComment">备注</label>
			    <div class="controls">
			      	<textarea class="input-xlarge" id="newComment" rows="2"></textarea>
			    </div>
			</div>	  	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnNewConfirm">确认新增</button>
  	</div>
</div>
<!-- edit record -->
<div class="modal" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>编辑</h3>
  	</div>
  	<div class="modal-body">
  		<form class="form-horizontal">
  			<div class="control-group">
			    <label class="control-label" for="inputSeries">车系</label>
			    <div class="controls">
			      	<!-- <input type="text" id="inputSeries" placeholder="Old Password"> -->
			      	<select id="inputSeries" class="span2" disabled>
						<option value="F0">F0</option>
						<option value="M6">M6</option>
						<option value="6B">思锐</option>
						<option value="G6">G6</option>
					</select>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="inputCate">类别</label>
			    <div class="controls">
			      	<!-- <input type="text" id="inputCate" placeholder="Old text"> -->
			      	<select id="inputCate" class="span2">
						<option value="1">车身</option>
						<option value="2">动力总成</option>
						<option value="3">底盘</option>
						<option value="4">电器</option>
						<option value="5">内外饰</option>
						<option value="6">附件</option>
						<option value="7">其他</option>
						<option value="8">门系统</option>
					</select>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="inputCode">零部件编号</label>
			    <div class="controls">
			      	<input type="text" id="inputCode" placeholder="零部件编号">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="newCode">SAP编号</label>
			    <div class="controls">
			      	<input type="text" id="inputSapCode" placeholder="零部件编号">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="inputName">官方名称</label>
			    <div class="controls">
			      	<input type="text" id="inputName" placeholder="零部件官方名称">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="inputDisplayName">使用名称</label>
			    <div class="controls">
			      	<input type="text" id="inputDisplayName" placeholder="零部件使用名称">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="checkboxIsFault">故障件</label>
			    <div class="controls">
			      	<input type="checkbox" id="checkboxIsFault">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="inputSimpleCode">简码</label>
			    <div class="controls">
			      	<input type="text" id="inputSimpleCode" placeholder="简码">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="editSimpleCode">单价</label>
			    <div class="controls">
			      	<input type="text" id="editUnitPrice" placeholder="单价">
			      	<span clses="help-inline">如：1.21</span>
			    </div>
			</div>
			  
			<div class="control-group">
			    <label class="control-label" for="inputComment">备注</label>
			    <div class="controls">
			    	<textarea class="input-xlarge" id="inputComment" rows="2"></textarea>
			    </div>
			</div>	  	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnEditConfirm">确认修改</button>
  	</div>
</div>

<div class="modal" id="editProviderModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;width:760px;margin-left:-390px;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h4>编辑供应商</h4>
  	</div>
  	<div class="modal-body">
  		<form class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for="">第一供应商</label>
				<div class="controls">
					<input type="text" id="editProviderName1" class="input-xlarge inputProviderName" placeholder="请输入供应商">
					<span id="editProviderCode1"class="help-inline inputProviderCode"></span>
					<input type="hidden" id="editProviderId1" class="inputProviderId" value="">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">第二供应商</label>
				<div class="controls">
					<input type="text" id="editProviderName2" class="input-xlarge inputProviderName" placeholder="请输入供应商">
					<span id="editProviderCode2"class="help-inline inputProviderCode"></span>
					<input type="hidden" id="editProviderId2" class="inputProviderId" value="">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">第三供应商</label>
				<div class="controls">
					<input type="text" id="editProviderName3" class="input-xlarge inputProviderName" placeholder="请输入供应商">
					<span id="editProviderCode3"class="help-inline inputProviderCode"></span>
					<input type="hidden" id="editProviderId3" class="inputProviderId" value="">
				</div>
			</div>
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnEditProviderConfirm">确认修改</button>
  	</div>
</div>

</body>
</html>