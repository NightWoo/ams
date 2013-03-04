<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>配置跟单维护</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/other/ConfigPaper.css" rel="stylesheet">	
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/uploadify/jquery.uploadify-3.1.js"></script>
	<link rel="stylesheet" type="text/css" href="/bms/js/uploadify/uploadify.css">
	<script type="text/javascript" src="/bms/js/execution/assembly/other/configPaper.js"></script>
	<style type="text/css">
	label{margin-bottom: 0}
		.queue{display: inline-block;min-width: 350px;background-color: #FFF;height: 50px;padding: 5px 10px;
border-radius: 3px;
box-shadow: 0 1px 3px rgba(0,0,0,0.25);}
		.uploadify{display: inline-block;}
		.config-item label{width: 90px;text-align: right;}
		.config-item{margin-bottom: 10px;}
	</style>
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
            <div><!-- breadcrumb -->
            	<ul class="breadcrumb">
            		<li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
                	<li><a href="#">总装</a><span class="divider">&gt;</span></li>
					<li><a href="#">维护与帮助</a><span class="divider">&gt;</span></li>
					<li><a href="/bms/execution/configMaintain">配置维护</a><span class="divider">&gt;</span></li>
                	<li class="active">配置跟单</li>
					<li class="pull-right">
						<a href="/bms/execution/configList">配置明细</a>
					</li>             
            	</ul>
            </div><!-- end of breadcrumb -->
            
   	   		<div><!-- 主体 -->
				<form id="form" class="well form-search">
					<table>
						<tr>
							<td>车系</td>
							<td>车型</td>
							<td>配置</td>
							<td></td>
						</tr>
						<tr>
							<td>
								<select name="" id="series" class="input-small">
									<option value="" selected></option>
									<option value="F0" >F0</option>
									<option value="M6">M6</option>
                                    <option value="6B" disabled>思锐</option>
								</select>
							</td>
							<td>
								<select name="" id="carType" class="input-xlarge">
									<option value="" selected></option>
								</select>
							</td>
							<td>
								<select name="" id="config" class="input-large">
									<option value=""></option>
								</select>
							</td>
							<td> 
								<input type="button" class="btn btn-primary" id="" value="全部上传"></input>
								<i id="queryRefresh" class="icon-refresh"></i> 
							</td>
						</tr>
					</table>
				</form>
				<input type="hidden" id='sessionName' value='<?php echo session_name();?>'></input>
				<input type="hidden" id='sessionId' value='<?php echo session_id();?>'></input>
				<div class="well form-inline" id="configContainer">
					<div class="config-item">
						<form name="front" id="demoForm" method="post" enctype="multipart/form-data" 
						action="/bms/config/upload"
						 > 
						 <p>Upload File: <input type="file" name="file" /></p> 
						 <p><input type="submit" value="Submit" /></p> 
						 <button class="btn btn-primary" id="btnUpload">上传</button>
						</form> 
						<label style="">主配置单正面</label>
						<input type="file" name="file_upload" id="file_upload" class="file_upload"/>
						<input class="span7" id="testInput" type="text" disabled>
						
						<span class="help-inline btnDelect" id="del"><p class="text-error">删除本配置单</p></span>
						<span class="help-inline notyet"><p class="text-success">尚未上传配置单</p></span>
					</div>
					<div class="config-item">
						<label style="">主配置单反面</label>
						<input type="file" name="file_upload" id="file_upload2" class="file_upload"/>
						<input class="span7" id="testInput" type="text">
						<button class="btn btn-primary">上传</button>
						<span class="help-inline btnDelect"><p class="text-error">删除本配置单</p></span>
						<span class="help-inline notyet"><p class="text-success">尚未上传配置单</p></span>
					</div>
				</div>					
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
       	</div><!-- offhead -->
 	
</body>
</html>