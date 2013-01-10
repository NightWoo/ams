<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $nodeDisplayName;?></title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/VQ2LeakTest.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/vq2LeakTest.js"></script>
	</head>
	<body>
		 <?php
            require_once(dirname(__FILE__)."/../../../common/head.php");
        ?>
        <div class="offhead">
            <?php
              require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
            ?>
            <div id="bodyright" class="offset2"><!-- Main -->  
           
				<div ><ul class="breadcrumb"><!-- 面包屑 -->
						<li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
						<li><a href="#">总装</a><span class="divider">&gt;</span></li>
						<li><a href="child?node=NodeSelect">数据录入</a><span class="divider">&gt;</span></li>
						<li class="active"><?php echo $nodeDisplayName;?></li>
						<li class="pull-right"><a href="/bms/execution/child?node=漏雨&view=VQ2LeakTestException">前往VQ2异常.漏雨</a></li>                
				</ul></div><!-- end 面包屑 -->
            
				<div><!-- 内容主体 -->
					<div>
						<form id="formFailure" class="well form-search">
							<div>
								<label>VIN</label>
								<input id="vinText" type="text" class="span3" placeholder="请扫描/输入VIN...">
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
							<div>
								<div id="tabContent" class="tab-content">
									<div class="tab-pane active" id="general">
										<table id="tableGeneral" class="table">
											<thead>
												<tr>
													<td class="span1">序号</td>
													<td class="span1">漏水</td>
													<td class="span12">故障零部件</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
	
								</div>
							</div>
						</form>                                          
					</div>
				</div><!-- end内容主体 -->
			</div><!-- end Main -->
       	</div><!-- offhead -->
    	
	</body>
</html>
