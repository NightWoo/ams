<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $nodeDisplayName;?></title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/VQ2RoadTestFinished.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
    <script type="text/javascript" src="/bms/js/common.js"></script>

    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/wdi.js"></script>
	</head>
	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
		<div class="offhead">
			<?php
				require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
			?>
		<div id="bodyright" class="offset2"><!-- Main体 -->           
            <legend><?php echo $nodeDisplayName;?>
                   <!--  <span class="pull-right">
                        <a href=""><i class="icon-link"></i>&nbsp;WDI异常</a>
                    </span> -->
            </legend>
				<div><!-- 内容主体 -->
					<div>
						<form id="formFailure" class="well form-search">
							<div>
								<label>&nbsp;VIN</label>
								<input id="vinText" type="text" class="span3" placeholder="请扫描/输入VIN...">
								
								<button id="btnSubmit" type="submit" class="btn btn-danger" disalbled='disabled'>提交故障记录</button>
								<button id="reset" type="reset" class="btn">清空</button>
								<!-- <span class="help-inline" id="vinHint">请输入VIN后回车</span> -->
								<input type="text" class="input-medium" placeholder="检验日期..." id="checkTime" onClick="WdatePicker({el:'checkTime',dateFmt:'yyyy-MM-dd HH:00'});"/>
								<select name="" id="checker" class="input-small" disalbled='disabled'>
									<option value="" selected>初检员</option>
									<option value="195">郭卫东</option>
									<option value="196">冯涛</option>
									<option value="197">王建文</option>
									<option value="198">邓观佑</option>
									<option value="199">刘洋</option>
									<option value="200">全权</option>
									<option value="201">饶名义</option>
									<option value="202">苏韦</option>
									<option value="203">王傲</option>
									<option value="204">朱芳书</option>
									<option value="205">陈祥星</option>
									<option value="206">巴帅锋</option>
									<option value="207">徐文超</option>
									<option value="208">田士爱</option>
									<option value="209">彭辉成</option>
								</select>
								<select name="" id="subChecker" class="input-small" disalbled='disabled'>
									<option value="" selected>复检员</option>
									<option value="195">郭卫东</option>
									<option value="196">冯涛</option>
									<option value="197">王建文</option>
									<option value="198">邓观佑</option>
									<option value="199">刘洋</option>
									<option value="200">全权</option>
									<option value="201">饶名义</option>
									<option value="202">苏韦</option>
									<option value="203">王傲</option>
									<option value="204">朱芳书</option>
									<option value="205">陈祥星</option>
									<option value="206">巴帅锋</option>
									<option value="207">徐文超</option>
									<option value="208">田士爱</option>
									<option value="209">彭辉成</option>
								</select>
								<input type="hidden" id='currentNode' name='currentNode' value='<?php echo $node?>'></input>
								
								<div class="help-inline" id="carInfo">
									<span class="label label-info" rel="tooltip" title="流水号" id="serialNumber"></span>
									<span class="label label-info" rel="tooltip" title="车系" id="series"></span>
									<!--<span class="label label-info" rel="tooltip" title="Vin号" id="vin"></span>-->
									<span class="label label-info" rel="tooltip" title="车型" id="type"></span>
									<span class="label label-info" rel="tooltip" title="颜色" id="color"></span>
                            		<span class="label label-info" rel="tooltip" title="车辆区域" id="statusInfo"></span>

								</div>
							</div>
							<div>
								<div id="messageAlert" class="alert"></div>    
							</div> <!-- end 提示信息 --> 
							</form>                 
							
							<form id="divDetail" class="well form-search">
								<div>
									<ul id="tabs" class="nav nav-pills">
										<li><a href="#general" data-toggle="tab">常见</a></li>
										<li class="active"><a href="#other" data-toggle="tab">其他</a></li>
									</ul>
								</div>
								<div id="tabContent" class="tab-content">
									<div class="tab-pane" id="general">
										<table id="tableGeneral" class="table">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span3">故障零部件</td>
													<td class="span5">故障模式</td>
													<td class="span5">责任部门</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
									
									<div class="tab-pane active" id="other">
										<table id="tableOther" class="table">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span4">故障零部件</td>
													<td class="span5">故障模式</td>
													<td class="span5">责任部门</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
								</div>
							                   	
						</form>                                          
					</div>
					
				</div><!-- end of 内容主体 -->
			</div><!-- end main体 -->
		</div><!-- offhead -->	
	</body>
</html>
