<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $nodeDisplayName;?></title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
    <link href="/bms/css/execution/assembly/dataInput/VQ3_exception.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/vq3Exception.js"></script>
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
                <div><!-- breadcrumb -->
                	<ul class="breadcrumb">
                		<li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
                    	<li><a href="#">总装</a><span class="divider">&gt;</span></li>
                        <li><a href="child?node=NodeSelect">数据录入</a><span class="divider">&gt;</span></li>
                    	<li class="active"><?php echo $nodeDisplayName;?></li>
                    	<li class="pull-right"><a href="/bms/execution/child?node=VQ3&view=VQ3">前往VQ3外观检验</a></li>                
                	</ul>
                </div><!-- end of breadcrumb -->
                
        	   		<div><!-- 主体 -->
           	  		<form id="formConfirmation" class="well form-search">
           	  			<div>
                        	<label>VIN</label>
           	  				<input id="vinText" type="text" class="span3" placeholder="请扫描/输入VIN...">                        	
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
                        <div id="divDetail">
                    		<table id="tableConfirmation" class="table">
                            	<thead>
                                	<tr>
                                    	<th class="span2">序号</th>
                                        <th>修复</th>
                                        <th class="span3">故障现象</th>
                                        <th>责任部门</th>
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
                            		<button id="btnPickAll" class="btn" type="button">全选</button>
                            		<button id="btnPickNone" class="btn" type="button">清选</button>
                            	</div>
                            </div>                           
                    	</div>                   	
                    </form>                                          
        	  	</div><!-- end of 主体 -->
            </div><!-- end of 页体 -->
       	</div><!-- offhead -->
      
   	
	</body>
</html>
