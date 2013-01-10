<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>BMS.Ver0.1</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/vq1Exception.js"></script>
	</head>
	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head/execution_head.php");
			require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
		?>
     <!-- 页体 -->
     
		
        <div class="offset2"><!-- 页体 -->
            <div><!-- breadcrumb -->
            	<ul class="breadcrumb">
            		<li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
                	<li><a href="#">总装</a><span class="divider">&gt;</span></li>
                    <li><a href="#">数据录入</a><span class="divider">&gt;</span></li>
                	<li class="active">仪表分装</li>                
            	</ul>
            </div><!-- end of breadcrumb -->
            
   	   		<div><!-- 主体 -->
				<div>
           	  		<form id="formVIN" class="well form-search">
           	  			<label>VIN：</label>
                        <input id="inputVIN" type="text" placeholder="请扫描/输入VIN..." value="LGXC16DG4C1234564" readonly>
                        <button id="btnSubmit" type="submit" class="btn btn-primary"><i class="icon-print icon-white"></i>&nbsp;打印</button>
                        <button id="btnRefresh" type="button" class="btn btn-success"><i class="icon-refresh icon-white"></i>&nbsp;刷新</button> 
                        <button id="btnClear" type="reset" class="btn btn-danger"><i class="icon-repeat icon-white"></i>&nbsp;清空</button>
                  		<span class="help-inline">C123456 如需打印指定车辆配置单，请清空</span>
                    </form>                       
                </div>
                
                <div id="info" class="alert alert-success">LGXC16DGXC1234666仪表分装配置单已打印，请输入下一辆车VIN</div>

				<div class="accordion" id="accordionPlan">
                	<div class="accordion-group">
                		<div class="accordion-heading">
               				<a id="planViewToggle" class="accordion-toggle" data-toggle="collapse" data-parent="#accordionPlan" href="#collapsePlan">当前打印序列</a>
               			</div>
                		<div id="collapsePlan" class="accordion-body collapse">
                			<div class="accordion-inner">
                				<table id="tableList" class="table table-condensed">
                					<thead>
                    					<tr class="active">
                    						<th style="width: 80px;">整车编号</th>
                                            <th style="width: 50px;">车系</th>
                    						<th style="width: 180px;">VIN</th>
                    						<th style="width: 210px;">车型</th>
                    						<th style="width: 60px;">颜色</th>
                    						<th style="width: 60px;">年份</th>
                    						<th style="width: 100px;">配置</th>
                    						<th style="width: 80px;">订单类型</th>
                    						<th style="width: 100px;">特殊单号</th>
                    						<th>备注</th>
                  						</tr>
                    				</thead>
                    				<tbody>
 					                   	<tr id="row0">
        				           			<td>C12356</td>
                                            <td>F0</td>
                    						<td>LGXC16DG4C1234564</td>
                    						<td>QCJ7100L（1.0排量尊舒适型）</td>
                    						<td>德兰黑</td>
                    						<td>2012</td>
                    						<td>1.0舒适标准</td>
                  	  						<td>国内订单</td>
                    						<td>-</td>
                    						<td>北京，耐寒型，QA-5</td>                    				
              				     		</tr>
                                        <tr>
        				           			<td>C123457</td>
                                            <td>F0</td>
                    						<td>LGXC16DG7C1654321</td>
                    						<td>QCJ7100L（1.0排量尊舒适型）</td>
                    						<td>德兰黑</td>
                    						<td>2012</td>
                    						<td>1.0舒适标准</td>
                  	  						<td>国内订单</td>
                    						<td>-</td>
                    						<td>QA-5</td>                    				
              				     		</tr>                   				 			
         			         		</tbody>
             				  	</table>
                			</div>
                		</div>
                	</div>                		
                </div> 
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
       	</div><!-- offhead -->
	
	</body>
</html>