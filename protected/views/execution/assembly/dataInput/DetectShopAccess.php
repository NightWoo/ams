<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>检测间车辆门禁</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/DetectShopAccess.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/common.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/detectShopAccess.js"></script>
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
            <legend>检测间车辆门禁
                    <!-- <span class="pull-right">
                        <a href="/bms/execution/child?node=路试&view=VQ2RoadTestException"><i class="icon-link"></i>&nbsp;VQ2异常.路试</a>
                    </span> -->
            </legend>
				<div><!-- 内容主体 -->
					<div>
						<form id="formFailure" class="well form-search">
							<table id="tableInput">
								<tr>
									<td style="text-align: right;">VIN&nbsp;&nbsp;</td>
									<td>
										<input id="vinText" type="text" class="span3" placeholder="请扫描/输入VIN...">
										<select name="" id="driver" class="input-small" disalbled='disabled'>
											<option value="" selected>保密员</option>
											<option value="238">吕美珍</option>
											<option value="239">吴赛群</option>
											<option value="240">赵阳芳</option>
											<option value="241">张梅芳</option>
											<option value="242">张梅芳</option>
											<option value="243">叶文娟</option>
											<option value="244">肖迪玲</option>
											<option value="245">蒋百幕</option>
											<option value="246">谢维芬</option>
											<option value="247">任瑜</option>
											<option value="248">贺蝶</option>
											<option value="249">范宣</option>
											<option value="250">杨灿威</option>
											<option value="252">张万姣</option>
											<option value="253">柴枚</option>
											<option value="254">肖婷</option>
											<option value="255">贺方</option>
											<option value="256">王元华</option>
										</select>
										<button id="btnLeave" type="submit" class="btn btn-danger btnSubmit" disabled='disabled' name='DETECT_SHOP_LEAVE'>离开</button>
										<button id="btnReturn" type="submit" class="btn btn-primary btnSubmit" disabled='disabled' name='DETECT_SHOP_RETURN'>归还</button>
										<button id="reset" type="reset" class="btn">清空</button>
										<span class="help-inline" id="vinHint">请输入VIN后回车</span>
										<div class="help-inline" id="carInfo">
											<span class="label label-info" rel="tooltip" title="流水号" id="serialNumber"></span>
											<span class="label label-info" rel="tooltip" title="车系" id="series"></span>
											<!--<span class="label label-info" rel="tooltip" title="Vin号" id="vin"></span>-->
											<span class="label label-info" rel="tooltip" title="车型" id="type"></span>
											<span class="label label-info" rel="tooltip" title="颜色" id="color"></span>
		                            		<span class="label label-info" rel="tooltip" title="车辆状态" id="statusInfo"></span>
										</div>
									</td>
								</tr>
								<tr>
									<td style="text-align: right; vertical-align: top; padding-top: 5px;">备注&nbsp;&nbsp;</td>
									<td style="padding-top: 5px;">
										<textarea rows="1" id="remark" style="width:474px; padding-top: 5px;"></textarea>
									</td>
								</tr>
							</table>
						</form>                 
						<div>
							<div id="messageAlert" class="alert"></div>    
						</div> <!-- end 提示信息 --> 
					</div>
					
				</div><!-- end of 内容主体 -->
			</div><!-- end main体 -->
		</div><!-- offhead -->	
	</body>
</html>
