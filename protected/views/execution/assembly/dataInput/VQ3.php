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
    <script type="text/javascript" src="/bms/js/common.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/vq3.js"></script>
	</head>
	<body>
	 <?php
            require_once(dirname(__FILE__)."/../../../common/head.php");
        ?>
        <div class="offhead">
            <?php
              // require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
            ?>
            <div id="bodyright" class="offset2"><!-- Main -->  
            
            <div>
                <legend><?php echo $nodeDisplayName;?>
                    <span class="pull-right">
                        <a href="/bms/execution/child?node=VQ3异常&view=VQ3Exception"><i class="icon-link"></i>&nbsp;VQ3异常</a>
                        /
                        <a href="/bms/execution/faultDutyEdit"><i class="icon-link"></i>&nbsp;故障责任编辑</a>
                    </span>
                </legend>
            </div>
            
   	   		<div><!-- 主体 -->
				<div>
           	  		<form id="formFailure" class="well form-search">
           	  			<div>
                        	<label>VIN</label>
           	  				<input id="vinText" type="text" class="span3" placeholder="请扫描/输入VIN...">
                            <select name="" id="driver" class="input-small" disalbled='disabled'>
                                    <option value="" selected>检验员</option>
                                    <option value="312">孙国富</option>
                                    <option value="313">周凯</option>
                                    <option value="314">何文丽</option>
                                    <option value="315">张勇</option>
                                    <option value="316">方焯宁</option>
                                    <option value="317">宋令泽</option>
                                    <option value="318">林文铸</option>
                                    <option value="319">张赟飞</option>
                                    <option value="320">向华</option>
                                    <option value="321">舒波</option>
                                    <option value="322">黄志敏</option>
                                    <option value="323">刘雨</option>
                                    <option value="324">王昆</option>
                                    <option value="325">熊峰</option>
                                    <option value="326">黄敏</option>
                                    <option value="327">彭斌</option>
                                    <option value="328">任驰</option>
                                    <option value="329">游志平</option>
                                    <option value="330">李亚娟</option>
                                    <option value="331">谢鹏</option>
                                    <option value="332">游波</option>
                                    <option value="333">周迎春</option>
                                    <option value="334">张潇</option>
                                    <option value="335">欧阳成龙</option>
                                    <option value="336">张毅</option>
                                    <option value="337">邹友财</option>
                                    <option value="338">陈建陆</option>
                                    <option value="339">彭宏光</option>
                                    <option value="340">彭攀</option>
                            </select>
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
                            		<li><a href="#assembly" data-toggle="tab">常见</a></li>
                                	<!-- <li><a href="#paint" data-toggle="tab">涂装</a></li>
                                	<li><a href="#body" data-toggle="tab">焊装</a></li> -->
                                	<li class="active"><a href="#mix" data-toggle="tab">综合</a></li>
                            	</ul>
                            </div>
                            <div id="tabContent" class="tab-content">
                            	<div class="tab-pane" id="assembly">
                            		<table id="tableAssembly" class="table table-hover table-condensed">
                            			<thead>
                            				<tr>
                            					<td class="span1">序号</td>
                            					<td class="span2">故障零部件</td>
                            					<td class="span3">故障模式</td>
                            					<!-- <td class="span2">在线修复</td> -->
                                                <td>责任部门</td>
                            				</tr>
                            			</thead>
                            			<tbody>
                            				
                            			</tbody>
                            		</table>
                            	</div>
                                <!-- <div class="tab-pane" id="paint">
                                	<table id="tablePaint" class="table table-hover table-condensed">
                            			<thead>
                            				<tr>
                            					<td class="span1">序号</td>
                            					<td class="span2">故障零部件</td>
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
                                	<table id="tableBody" class="table table-hover table-condensed">
                            			<thead>
                            				<tr>
                            					<td class="span1">序号</td>
                            					<td class="span2">故障零部件</td>
                            					<td class="span3">故障模式</td>
                            					<td class="span2">在线修复</td>
                                                <td></td>
                            				</tr>
                            			</thead>
                            			<tbody>
                            				
                            			</tbody>
                            		</table>
                                </div> -->
                                
                                <div class="tab-pane active" id="mix">
                                	<table id="tableMix" class="table table-hover table-condensed">
                            			<thead>
                            				<tr>
                            					<td class="span1">序号</td>
                            					<td class="span3">故障零部件</td>
                            					<td class="span3">故障模式</td>
                            					<!-- <td class="span2">在线修复</td> -->
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
