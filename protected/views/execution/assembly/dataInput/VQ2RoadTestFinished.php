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
    <script type="text/javascript" src="/bms/js/common.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/vq2RoadTestFinished.js"></script>
	</head>
	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
		<div class="offhead">
			<?php
				// require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
			?>
		<div id="bodyright" class="offset2"><!-- Main体 -->        
            <legend><?php echo $nodeDisplayName;?>
                    <span class="pull-right">
                        <a href="/bms/execution/child?node=路试&view=VQ2RoadTestException"><i class="icon-link"></i>&nbsp;VQ2异常.路试</a>/
                        /
                        <a href="/bms/execution/faultDutyEdit"><i class="icon-link"></i>&nbsp;故障责任编辑</a>
                    </span>
            </legend>
				<div><!-- 内容主体 -->
					<div>
						<form id="formFailure" class="well form-search">
							<div>
								<label>&nbsp;VIN</label>
								<input id="vinText" type="text" class="span3" placeholder="请扫描/输入VIN...">
								<select name="" id="driver" class="input-small" disalbled='disabled'>
									<option value="" selected>驾驶员</option>
									<option value="149">陈亚军</option>
									<option value="112">樊后来</option>
									<option value="152">凡鹏飞</option>
									<option value="142">方祥</option>
									<option value="135">郭胜军</option>
									<option value="145">郭振华</option>
									<option value="146">何征兵</option>
									<option value="148">胡亚南</option>
									<option value="140">黄彬</option>
									<option value="156">黄水江</option>
									<option value="143">刘泮胜</option>
									<option value="137">毛保林</option>
									<option value="141">糜芳</option>
									<option value="153">石仕全</option>
									<option value="151">孙昌伍</option>
									<option value="136">滕向军</option>
									<option value="138">田佳岸</option>
									<option value="167">王泽鹏</option>
									<option value="139">肖志虎</option>
									<option value="147">杨思强</option>
									<option value="144">杨自西</option>
									<option value="150">曾海龙</option>
									<option value="154">赵松</option>
                                    <option value="227">顾善军</option>
                                    <option value="234">单炯</option>
                                    <option value="276">刘琪</option>
									<option value="277">苏新胜</option>
									<option value="278">简成伟</option>
									<option value="279">周超</option>
									<option value="280">谢文强</option>
									<option value="281">王安</option>
									<option value="282">黄兴</option>
									<option value="283">刘斌</option>
									<option value="284">艾梅彬</option>
									<option value="215">朱志鹏</option>
									<option value="285">黎江</option>
									<option value="286">毛光玉</option>
									<option value="169">汪辉</option>
									<option value="287">彭鹭云</option>
									<option value="288">尹彦德</option>
									<option value="289">贺锦</option>
									<option value="290">刘山东</option>
									<option value="291">熊闯</option>
									<option value="292">周思来</option>
									<option value="293">刘嘉伟</option>
									<option value="294">谌勇</option>
									<option value="295">周颂雄</option>
									<option value="296">赵鑫鹏</option>
									<option value="297">田双全</option>
									<option value="298">王文明</option>
									<option value="299">黄双喜</option>
									<option value="300">蒋其名</option>
									<option value="301">王定钊</option>
									<option value="302">鄢佳斌</option>
								</select>
								<button id="btnSubmit" type="submit" class="btn btn-danger" disalbled='disabled'>提交故障记录</button>
								<button id="reset" type="reset" class="btn">清空</button>
								<input type="hidden" id='currentNode' name='currentNode' value='<?php echo $node?>'></input>
								<span class="help-inline" id="vinHint">请输入VIN后回车</span>
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
							<form id="formBag" class="well form-search">
								<label>气囊</label>
								<input id="inputBag" type="text" class="span3" placeholder="请扫描/输入主驾气囊条码...">
							</form>
							<form id="divDetail" class="well form-search">
								<div>
									<ul id="tabs" class="nav nav-pills">
										<li class="active"><a href="#general" data-toggle="tab">常见</a></li>
										<li><a href="#other" data-toggle="tab">其他</a></li>
										<li>
											<div style="margin:0 0 0px 5px;padding-top:2px">
												<!-- <label>温度</label> -->
												<div class="input-append">
													<input id="inputTemperature" type="text" class="span3" style="width:70px" placeholder="空调温度...">
													<span class="add-on">℃</span>
												</div>
											</div>
										</li>
									</ul>
								</div>
								<div id="tabContent" class="tab-content">

									<div class="tab-pane active" id="general">
										<table id="tableGeneral" class="table">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span2">故障零部件</td>
													<td class="span3">故障模式</td>
													<td class="">责任部门</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
									
									<div class="tab-pane" id="other">
										<table id="tableOther" class="table">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span2">故障零部件</td>
													<td class="span3">故障模式</td>
													<td class="">责任部门</td>
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
