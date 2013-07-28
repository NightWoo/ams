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
	<script type="text/javascript" src="/bms/js/jquery.form.js"></script>
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
		require_once(dirname(__FILE__)."/../../../common/left/assembly_plan_left.php");
		?>
     
        <div id="bodyright" class="offset2"><!-- 页体 -->
            <div>
            	<legend>生产配置跟单维护
            		<span class="pull-right">
            			<a href="/bms/execution/configMaintain"><i class="icon-link"></i>&nbsp;生产配置</a>
            			/
            			<a href="/bms/execution/configList"><i class="icon-link"></i>&nbsp;配置明细 </a>
            		</span>
            	</legend>
            </div>
            
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
                                    <option value="6B">思锐</option>
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
								<!-- <input type="button" class="btn btn-primary" id="" value="全部上传"></input> -->
								<button type="button" class="btn btn-primary" id="queryRefresh" value=""><i class="icon-refresh"></i>&nbsp;刷新</button>
								 
							</td>
						</tr>
					</table>
				</form>
				<input type="hidden" id='sessionName' value='<?php echo session_name();?>'></input>
				<input type="hidden" id='sessionId' value='<?php echo session_id();?>'></input>
				<div class="well form-inline" id="configContainer">
					<div class="config-item">
						<form name="front" id="frontForm" method="post" enctype="multipart/form-data" action="/bms/config/upload"> 
							主 1/4
							<input type="file" name="front" />
							<input type="submit" value="上传" class="btn btn-primary"/>
							<span class="help-inline btnDelect" id="del"><a class="text-error"><i class="icon-trash"></i>删除</a></span>
							<span class="help-inline viewImage"><a class="text-success"><i class="icon-eye-open"></i>预览</a></span>
							<span class="help-inline notyet"><p class="text-muted">尚未上传配置单</p></span>
						</form>
					</div>

					<div class="config-item">
						<form name="front" id="backForm" method="post" enctype="multipart/form-data" action="/bms/config/upload"> 
							主 2/4 
							<input type="file" name="back" />
							<input type="submit" value="上传" class="btn btn-primary"/>
							<span class="help-inline btnDelect" id="del"><a class="text-error"><i class="icon-trash"></i>删除</a></span>
							<span class="help-inline viewImage"><a class="text-success"><i class="icon-eye-open"></i>预览</a></span>
							<span class="help-inline notyet"><p class="text-muted">尚未上传配置单</p></span>
						</form> 
					</div>

					<div class="config-item">
						<form name="front" id="front2Form" method="post" enctype="multipart/form-data" action="/bms/config/upload"> 
							主 3/4
							<input type="file" name="front2" />
							<input type="submit" value="上传" class="btn btn-primary"/>
							<span class="help-inline btnDelect" id="del"><a class="text-error"><i class="icon-trash"></i>删除</a></span>
							<span class="help-inline viewImage"><a class="text-success"><i class="icon-eye-open"></i>预览</a></span>
							<span class="help-inline notyet"><p class="text-muted">尚未上传配置单</p></span>
						</form>
					</div>

					<div class="config-item">
						<form name="front" id="back2Form" method="post" enctype="multipart/form-data" action="/bms/config/upload"> 
							主 4/4
							<input type="file" name="back2" />
							<input type="submit" value="上传" class="btn btn-primary"/>
							<span class="help-inline btnDelect" id="del"><a class="text-error"><i class="icon-trash"></i>删除</a></span>
							<span class="help-inline viewImage"><a class="text-success"><i class="icon-eye-open"></i>预览</a></span>
							<span class="help-inline notyet"><p class="text-muted">尚未上传配置单</p></span>
						</form> 
					</div>

					
					<div class="config-item">
						<form name="front" id="subInstrumentForm" method="post" enctype="multipart/form-data" action="/bms/config/upload"> 
							仪表台
							<input type="file" name="subInstrument" />
							<input type="submit" value="上传" class="btn btn-primary"/>
							<span class="help-inline btnDelect" id="del"><a class="text-error"><i class="icon-trash"></i>删除</a></span>
							<span class="help-inline viewImage"><a class="text-success"><i class="icon-eye-open"></i>预览</a></span>
							<span class="help-inline notyet"><p class="text-muted">尚未上传配置单</p></span>
						</form> 
					</div>

					<div class="config-item">
						<form name="front" id="subEngineForm" method="post" enctype="multipart/form-data" action="/bms/config/upload"> 
							发动机
							<input type="file" name="subEngine" />
							<input type="submit" value="上传" class="btn btn-primary"/>
							<span class="help-inline btnDelect" id="del"><a class="text-error"><i class="icon-trash"></i>删除</a></span>
							<span class="help-inline viewImage"><a class="text-success"><i class="icon-eye-open"></i>预览</a></span>
							<span class="help-inline notyet"><p class="text-muted">尚未上传配置单</p></span>
						</form> 
					</div>
				</div>					
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
       	</div><!-- offhead -->
 	
</body>
</html>