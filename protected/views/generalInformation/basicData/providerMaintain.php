<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>供应商名录</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
    <link href="/bms/css/generalInformation/basicData/providerMaintain.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/generalInformation/basicData/providerMaintain.js"></script>
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
            <div><!-- breadcrumb -->
            	<ul class="breadcrumb">
            		<li><a href="#">综合信息</a><span class="divider">&gt;</span></li>
                	<li><a href="#">基础数据库</a><span class="divider">&gt;</span></li>
                	<li class="active">供应商名录</li>            
            	</ul>
            </div><!-- end of breadcrumb -->
            
   	   		<div><!-- 主体 -->
				<div>
           	  		<div class="well form-search">
           	  			<form id="formProvider">
							<table>
								<tr>
									<td><label>供应商代码</label></td>
									<td><label>供应商名称</label></td>
									<td></td>
								</tr>
								<tr>
									<td><input id="providerCode" type="text" class="span3" placeholder="供应商代码"></td>
									<td><input id="providerName" type="text" class="span3" placeholder="供应商名称"></td>
									<td>
										<button id="btnQuery" type="reset" class="btn btn-primary">查询</button>                       	
                       					<button id="btnAdd" type="reset" class="btn">新增</button>
									</td>
								</tr>
							</table>
                    	</form>                    
                    	<div>
                    		<table id="tableProvider" class="table table-condensed">
                            	<thead>
                                	<tr>
                                		<th class="span1">#</th>
                                    	<th class="span2">供应商代码</th>
                                        <th class="span4">供应商名称</th>
                                        <th class="span3">使用名称</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
							<!-- <div class="pagination">
								<ul>
									<li class="disable"><a href="#"><span>&laquo;</span></a></li>
									<li class="active"><a href="#"><span>1</span></a></li>
									<li><a href="#"><span>&raquo;</span></a></li>
								</ul>
							</div>  -->                        
                    	</div>                   	
                    </div>                                          
				</div>
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
				<label class="control-label" for="">*&nbsp;供应商代码</label>
				<div class="controls">
					<input type="text" id="newProviderCode" class="input-medium" placeholder="请输入供应商代码...">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;供应商名称</label>
				<div class="controls">
					<input type="text" id="newProviderName" class="input-medium" placeholder="请输入供应商名称...">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;使用名称</label>
				<div class="controls">
					<input type="text" id="newDisplayName" class="input-medium" placeholder="请输入使用名称...">
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
				<label class="control-label" for="">*&nbsp;供应商代码</label>
				<div class="controls">
					<input type="text" id="editProviderCode" class="input-medium" placeholder="请输入供应商代码...">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;供应商名称</label>
				<div class="controls">
					<input type="text" id="editProviderName" class="input-medium" placeholder="请输入供应商名称...">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="">*&nbsp;使用名称</label>
				<div class="controls">
					<input type="text" id="editDisplayName" class="input-medium" placeholder="请输入使用名称...">
				</div>
			</div>	  	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnEditConfirm">确认编辑</button>
  	</div>
</div>	
</body>
</html>