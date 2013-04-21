<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>出库打印</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/WarehousePrint.css" rel="stylesheet">	
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/common.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/dataInput/warehousePrint.js"></script>
	<script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
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
            <div>
            	<legend>出库打印
            		<span class="pull-right">
            			<!-- <a href="/bms/execution/orderMaintain"><i class="icon-link"></i>&nbsp;订单维护</a> -->
            		</span>
            	</legend>
            </div>
            
   	   		<div><!-- 主体 -->
			    <div class="accordion span4" id="accordionLane">
			    	<div class="accordion-group" id="laneGroup1">
			    		<div class="accordion-heading">
			    			<!-- <div class="headBadge">
		    				 	<span class="label label-success" id="totalOK">2</span>
			    			</div> -->
			    			<a href="#" id="refreshLane"><i class="icon-refresh pull-right"></i></a>
			    			<p class="text-success" id="totalOK">12</p>
			    			<!-- <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionLane" href="#collapse1">
			    				01-10
			    			</a> -->
			    		</div>
			    		<div id="collapse1" class="accordion-body collapse in" style="overflow-y: scroll; height:460px">
			    			<div class="accordion-inner">
			    				<div class="block">
									<?php 
										for($i=1;$i<51;$i++){
											$num = sprintf("%02d", $i);
											echo "<div>";
											echo 	"<div class='pull-left laneContainer'>";
											echo 		"<a class='thumbnail lane' href='#' laneid='$i'>";
											echo 			"<p class='pull-left'>#$num</p>";
											echo 			"<p class='label pull-right laneOK' laneid='$i'>0</p>";
											echo 			"<div class='progress progress-info'>";
											echo 				"<div class='bar' style='width:100%' laneid='$i'>10/10</div>";
											echo 			"</div>";
											echo 		"</a>";
											echo 	"</div>";
											echo "</div>";
										}
									?>

									<div>
				    					<div class="pull-left laneContainer">
					    					<a class="thumbnail" href="#">
												<p class="pull-left" laneid="51">#加</p>
												<p class="label pull-right laneOK" laneid="51">0</p>
												<div class="progress progress-info">
													<div class="bar" style="width:100%" laneid="51">10/10</div>
												</div>
											</a>
										</div>
									</div>
								</div>
			    			</div>
			    		</div>
			    	</div>
			    </div>

			    <div class="offset4">
	                
	                <table id="tableResult" class="table table-condensed table-hover" style="font-size:12px;">
	                    <thead>
	                        <tr>
	                            <!-- <th>#</th> -->
	                            <th id="thLane">车道</th>
	                            <th id="thOrderNumber">订单号</th>
	                            <th id="thDistributor">经销商</th>
	                            <th id="thAmount">数量</th>
	                            <th id="thHold">已备</th>
	                            <th id="thCount">出库</th>
	                            <th id="thSeries">车系</th>
	                            <th id="thColor">颜色</th>
	                            <th id="thCarType">车型/配置</th>
	                            <th id="thColdResistant">耐寒性</th>
	                            <!-- <th id="thStandbyDate">备车日期</th> -->
	                            <!-- <th id="thOrderType">订单类型</th> -->
	                            <!-- <th id="thRemark">备注</th> -->
	                            <th id="thEdit"></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        <tr>
	                        	<td>34</td>
	                        	<td>ZCDG-20130327599959</td>
	                        	<td>南京苏舜亚通汽车销售服务有限公司</td>
	                        	<td>3</td>
	                        	<td>2</td>
	                        	<td>1</td>
	                        	<td>M6</td>
	                        	<td>瑞亚银</td>
	                        	<td>QCJ6480MJ/2.4L尊贵/4G69</td>
	                        	<td>非耐寒</td>
	                        	<!-- <td>2013-04-19</td> -->
	                        	<td>
	                        		<a class="btn btn-link" href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="车辆明细"><i class="btnDetail icon-list"></i></a>
		            				<a class="btn btn-link" href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="打印" disabled><i class="btnPrint icon-print"></i></a>
	                        	</td>
	                        </tr>
	                    </tbody>
	                </table>
			    </div>
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
	</div><!-- offhead -->
<!-- new record -->
<div class="modal" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
		    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		   	 	<h4>#01_南京苏舜亚通汽车销售服务有限公司_ZCDG-20130327599959</h4>
		  	</div>
		  	<div class="modal-body">
		  		<table class="table table-condensed table-hover" id="detailTable">
            		<thead>
            			<th style="width:20px"></th>
            			<th style="width:120px">VIN号</th>
            			<th style="width:30px">车系</th>
            			<th style="width:50px">颜色</th>
            			<th style="width:300px">车型/配置</th>
            			<th style="width:40px">耐寒性</th>
            			<th style="width:150px">出库时间</th>
            		</thead>
            		<tbody>
            			<td><a class="btn btn-link" href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="打印"><i class="btnPrint icon-print"></i></a></td>
                    	<td>LGXC34CG6D1014718</td>
            			<td>M6</td>
                    	<td>瑞亚银</td>
                    	<td>QCJ6480MJ/2.4L尊贵/4G69</td>
                    	<td>非耐寒</td>
                    	<td>2013-04-18 09:00:00</td>
            		</tbody>
	            </table>
		  	</div>
		  	<div class="modal-footer">
			    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
			    <button class="btn btn-primary" id="detailPrintAll"><i class="btnPrint icon-print"></i>&nbsp;打印全部</button>
		  	</div>
</div>

</body>
</html>