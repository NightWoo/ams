<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>生产配置维护</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/other/ConfigMaintain.css" rel="stylesheet">
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/rjs/lib/jsrender.min.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/other/configMaintain.js"></script>
</head>
<body>
		
	<?php
		require_once(dirname(__FILE__)."/../../../common/head.php");
	?>
	<div class="offhead">
	   <?php
		require_once(dirname(__FILE__)."/../../../common/left/general_database_left.php");
		?>
     
        <div id="bodyright" class="offset2"><!-- 页体 -->
            <div>
            	<legend>生产配置维护
            		<span class="pull-right">
            			<a href="/bms/execution/configPaper"><i class="fa fa-link"></i>&nbsp;配置跟单</a>
            			/
            			<a href="/bms/execution/configList"><i class="fa fa-link"></i>&nbsp;配置明细</a>
            			/
            			<a href="/bms/execution/orderConfigMaintain"><i class="fa fa-link"></i>&nbsp;订单配置</a>
            			/
            			<a href="/bms/execution/accessoryListMaintain"><i class="fa fa-link"></i>&nbsp;随车附件</a>
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
								<select name="" id="carSeries" class="input-small carSeries">
									<option value="" selected>请选择</option>
									<!-- <option value="F0">F0</option>
									<option value="M6">M6</option>
                                    <option value="6B">思锐</option> -->
								</select>
								<script id="tmplSeriesSelect" type="text/x-jsrander">
                                    <option value='{{:series}}'>{{:name}}</option>
                                </script>
							</td>
							<td>
								<select name="" id="carType" class="input-xlarge">
								</select>
							</td>
							<td>
								<input type="text" id="configName" class="input-medium" placeholder="请输入配置">
							</td>
							<td>
								<input id="btnQuery" type="button" class="btn btn-primary" value="查询"></input>   
								<input id="btnAdd" type="button" class="btn btn-success" value="新增"></input>
							</td>
						</tr>
					</table>
				</form>
				
				<table id="tableConfig" class="table table-condensed">
					<thead>
						<tr>
							<th>ID</th>
							<th>车系</th>
							<th>车型</th>
							<th>配置名称</th>
							<th>更新时间</th>
							<th>更新人</th>
							<th>备注</th>
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
   	 	<h3>新增</h3>
  	</div>
  	<div class="modal-body">
  		<form id="" class="form-horizontal">
  			<div class="control-group">
                <label class="control-label" for="">停用</label>
                <div class="controls">
                    <input type="checkbox" id="newIsDisabled">
                </div>
            </div>
  			<div class="control-group">
			    <label class="control-label" for="">*&nbsp;车系</label>
			    <div class="controls">
			      	<select id="newCarSeries" class="input-small carSeries">
						<option value="" selected>请选择</option>
						<!-- <option value="F0" selected>F0</option>
						<option value="M6">M6</option>
                        <option value="6B">思锐</option> -->
					</select>
			    </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;车型</label>
				<div class="controls">
					<select name="" id="newCarType" class="input-xlarge">
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;配置</label>
				<div class="controls">
					<input type="text" id="newConfigName" class="input-xlarge" placeholder="请输入配置名称...">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;订单配置</label>
				<div class="controls">
					<select name="" id="newOrderConfig" class="input-large">
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;地区</label>
				<div class="controls">
					<select name="" id="newClime" class="input-small">
						<option selected value="国内">国内</option>
						<option value="出口">出口</option>
						<option value="阿拉伯">阿拉伯</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">出口国家</label>
				<div class="controls">
					<input type="text" id="newExportCountry" class="input-medium" placeholder="出口国家...">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;耐寒机油</label>
				<div class="controls">
					<select name="" id="newOilFillingCold" class="input-xlarge newOilFilling">
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;非耐寒机油</label>
				<div class="controls">
					<select name="" id="newOilFillingNormal" class="input-xlarge newOilFilling">
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;侧门玻璃</label>
				<div class="controls">
					<input type="text" id="newSideGlass" class="input-medium" placeholder="侧门玻璃...">
					<span class="help-inline">针对M6</span>
				</div>
			</div>
			<div class="control-group">
                <label class="control-label" for="">后空调</label>
                <div class="controls">
                    <input type="checkbox" id="newAircondition">
                </div>
            </div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;轮胎规格</label>
				<div class="controls">
					<input type="text" id="newTyre" class="input-medium" placeholder="请输入配置名称...">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;转向助力</label>
				<div class="controls">
					<select name="" id="newSteering" class="input-small">
						<option selected value="液压">液压</option>
						<option value="机械">机械</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;合格证备注</label>
				<div class="controls">
					<textarea class="input-xlarge" id="newCertificateNote" rows="6"></textarea>
				</div>
			</div>
  			<div class="control-group">
				<label class="control-label" for="">配置备注</label>
				<div class="controls">
					<textarea class="input-xlarge" id="newRemark" rows="2"></textarea>
				</div>
			</div>  	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
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
  		<form id="" class="form-horizontal">
  			<div class="control-group">
                <label class="control-label" for="">停用</label>
                <div class="controls">
                    <input type="checkbox" id="editIsDisabled">
                </div>
            </div>
  			<div class="control-group">
			    <label class="control-label" for="">*&nbsp;车系</label>
			    <div class="controls">
			      	<select id="editCarSeries" class="input-medium carSeries" dieabled>
						<option value="" selected>请选择</option>
						<!-- <option value="F0">F0</option>
						<option value="M6">M6</option>
                        <option value="6B">思锐</option> -->
					</select>
			    </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;车型</label>
				<div class="controls">
					<select name="" id="editCarType" class="input-xlarge">
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;配置</label>
				<div class="controls">
					<input type="text" id="editConfigName" class="input-xlarge" placeholder="请输入配置名称...">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;订单配置</label>
				<div class="controls">
					<select name="" id="editOrderConfig" class="input-xlarge">
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;地区</label>
				<div class="controls">
					<select name="" id="editClime" class="input-small">
						<option selected value="国内">国内</option>
						<option value="出口">出口</option>
						<option value="阿拉伯">阿拉伯</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">出口国家</label>
				<div class="controls">
					<input type="text" id="editExportCountry" class="input-medium" placeholder="出口国家...">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;耐寒机油</label>
				<div class="controls">
					<select name="" id="editOilFillingCold" class="input-xlarge editOilFilling">
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;非耐寒机油</label>
				<div class="controls">
					<select name="" id="editOilFillingNormal" class="input-xlarge editOilFilling">
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;侧门玻璃</label>
				<div class="controls">
					<input type="text" id="editSideGlass" class="input-medium" placeholder="侧门玻璃...">
					<span class="help-inline">针对M6</span>
				</div>
			</div>
			<div class="control-group">
                <label class="control-label" for="">后空调</label>
                <div class="controls">
                    <input type="checkbox" id="editAircondition">
                </div>
            </div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;轮胎规格</label>
				<div class="controls">
					<input type="text" id="editTyre" class="input-medium" placeholder="请输入配置名称...">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;转向助力</label>
				<div class="controls">
					<select name="" id="editSteering" class="input-small">
						<option selected value="液压">液压</option>
						<option value="机械">机械</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;合格证备注</label>
				<div class="controls">
					<textarea class="input-xlarge" id="editCertificateNote" rows="6"></textarea>
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

<!-- sap edit -->
<div class="modal" id="sapEditModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3></h3>
  	</div>
  	<div class="modal-body">
  		<table id="sapEditTable" class="table table-condensed table-hover" style="">
			<thead>
				<tr>
					<th>#</th>
					<th>颜色</th>
					<th>SAP物料编号</th>
					<th>SAP物料描述</th>
				</tr>
			</thead>
			<tbody>

			</tbody>
			<script id="tmplSapTr" type="text/x-jsrander">
				<tr sapId="{{:config_id}}">
					<td class="id">{{:id}}</td>
					<td class="color">{{:color}}</td>
					<td><input type='text' class='input-medium material_code' value='{{:material_code}}'></td>
					<td><input type='text' class='span5 description' value='{{:description}}'></td>
				</tr>
            </script>
		</table>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnSapConfirm">确认编辑</button>
  	</div>
</div>
  	
</body>
</html>