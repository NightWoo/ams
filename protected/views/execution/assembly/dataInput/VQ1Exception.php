<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $nodeDisplayName;?></title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/VQ1Exception.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/vq1Exception.js"></script>
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
				<legend><?php echo $nodeDisplayName;?>
                    <span class="pull-right">
                        <a href="/bms/execution/child?view=VQ1&node=VQ1"><i class="icon-link"></i>&nbsp;VQ1静态检验</a>
                    </span>
                </legend>
				
				<div><!-- 内容主体 -->
					<div>
						<form id="formConfirmation" class="well form-search">
							<div>
								<label>VIN</label>
								<input id="vinText" type="text" class="span3" placeholder="请扫描/输入VIN...">                          
								<input id="reset" type="reset" class="btn" value="清空"> </input>
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
							<div id="divDetail">
								<table id="tableConfirmation" class="table">
									<thead>
										<tr>
											<th class="span2">序号</th>
											<th>修复</th>
											<th>故障现象</th>
											<th>录入人员</th>
											<th>录入时间</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
								<div>
									<button id="btnSubmit" type="submit" class="btn btn-danger">确认提交</button>&nbsp;&nbsp;
									<div class="btn-group">                                 
										<button id="btnPickAll"  class="btn" type="button">全选</button>
										<button id="btnPickNone" class="btn" type="button">清选</button>
									</div>
								</div> 
							</div>                      
						</form>                                          
					</div>
				</div><!-- 内容主体 -->
			</div><!-- end of 页体 -->
		
		</div>
		
		
    	
	</body>
</html>
