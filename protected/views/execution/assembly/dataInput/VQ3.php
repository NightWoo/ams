<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $nodeDisplayName;?></title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
    <link href="/bms/css/execution/assembly/dataInput/VQ3.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/vq3.js"></script>
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
                	<li class="pull-right"><a href="/bms/execution/child?node=VQ3异常&view=VQ3Exception">前往VQ3异常</a></li>                
            	</ul>
            </div><!-- end of breadcrumb -->
            
   	   		<div><!-- 主体 -->
				<div>
           	  		<form id="formFailure" class="well form-search">
           	  			<div>
                        	<label>VIN</label>
           	  				<input id="vinText" type="text" class="span3" placeholder="请扫描/输入VIN...">
                        	<button id="btnSubmit" type="submit" class="btn btn-danger">提交故障记录</button>
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
                    		<div>
                    			<ul id="tabs" class="nav nav-pills">
                            		<li><a href="#assembly" data-toggle="tab">总装</a></li>
                                	<li><a href="#paint" data-toggle="tab">涂装</a></li>
                                	<li><a href="#body" data-toggle="tab">焊装</a></li>
                                	<li class="active"><a href="#mix" data-toggle="tab">综合</a></li>
                            	</ul>
                            </div>
                            <div id="tabContent" class="tab-content">
                            	<div class="tab-pane" id="assembly">
                            		<table id="tableAssembly" class="table">
                            			<thead>
                            				<tr>
                            					<td class="span1">序号</td>
                            					<td class="span3">故障零部件</td>
                            					<td class="span3">故障模式</td>
                            					<td class="span2">在线修复</td>
                                                <td></td>
                            				</tr>
                            			</thead>
                            			<tbody>
                            				
                            			</tbody>
                            		</table>
                            	</div>
                                <div class="tab-pane" id="paint">
                                	<table id="tablePaint" class="table">
                            			<thead>
                            				<tr>
                            					<td class="span1">序号</td>
                            					<td class="span3">故障零部件</td>
                            					<td class="span3">故障模式</td>
                            					<td class="span2">在线修复</td>
                                                <td></td>
                            				</tr>
                            			</thead>
                            			<tbody>
                            				
                            			</tbody>
                            		</table>
                                </div>
                                <div class="tab-pane" id="body">
                                	<table id="tableBody" class="table">
                            			<thead>
                            				<tr>
                            					<td class="span1">序号</td>
                            					<td class="span3">故障零部件</td>
                            					<td class="span3">故障模式</td>
                            					<td class="span2">在线修复</td>
                                                <td></td>
                            				</tr>
                            			</thead>
                            			<tbody>
                            				
                            			</tbody>
                            		</table>
                                </div>
                                
                                <div class="tab-pane active" id="mix">
                                	<table id="tableMix" class="table">
                            			<thead>
                            				<tr>
                            					<td class="span1">序号</td>
                            					<td class="span3">故障零部件</td>
                            					<td class="span3">故障模式</td>
                            					<td class="span2">在线修复</td>
                                                <td>责任部门</td>
                            				</tr>
                            			</thead>
                            			<tbody class="other">
                            				
                            			</tbody>
                            		</table>
                                </div>
                        	</div>
                    	</div>                   	
                    </form>                                          
                </div>
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
       	</div><!-- offhead -->
        
    	
	</body>
</html>
