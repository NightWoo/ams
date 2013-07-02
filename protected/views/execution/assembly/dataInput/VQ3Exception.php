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
    <script type="text/javascript" src="/bms/js/common.js"></script>
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
               
                <legend><?php echo $nodeDisplayName;?>
                    <span class="pull-right">
                        <a href="/bms/execution/child?node=VQ3&view=VQ3"><i class="icon-link"></i>&nbsp;VQ3外观检验</a>
                    </span>
                </legend>
                
        	   		<div><!-- 主体 -->
           	  		<form id="formConfirmation" class="well form-search">
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
