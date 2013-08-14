<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>随车附件清单</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/dataInput/AccessoryListPrint.css" rel="stylesheet" media="screen">	
	<link rel="stylesheet" type="text/css" href="/bms/css/execution/assembly/dataInput/AccessoryListPrint_print.css" media="print">
	<style type="text/css" media="screen">
		.printable{
			display: none;
		}
	</style>
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/common.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/dataInput/accessoryListPrint.js"></script>
	<script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
</head>
<body>
	<div  class="notPrintable">
	<?php
		require_once(dirname(__FILE__)."/../../../common/head.php");
	?>
	<div class="offhead">
	   <?php
		require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
		?>
     
        <div id="bodyright" class="offset2"><!-- 页体 -->
            <div id="headLegend" class="affix">
            	<legend>随车附件清单打印
            		<span class="pull-right">
            		</span>
            	</legend>
            </div>
            
   	   		<div id="mainBody"><!-- 主体 -->
			    <div class="accordion span4" id="accordionLane">
			    	<div class="accordion-group" id="laneGroup1">
			    		<div class="accordion-heading">
			    			<a href="#" id="refreshLane"><i class="icon-refresh pull-right"></i></a>
			    			<p class="text-success" id="totalOK">0</p>
			    		</div>
			    		<div id="collapse1" class="accordion-body collapse in" style="overflow-y: scroll; height:500px">
			    			<div class="accordion-inner">
			    				<div id="boardBar" class="block">
									<!-- <div>
				    					<div class="pull-left laneContainer">
					    					<a class="thumbnail" href="#">
												<p class="pull-left" laneid="51">#加</p>
												<p class="label pull-right laneOK" laneid="51">0</p>
												<div class="progress progress-info">
													<div class="bar" style="width:100%" laneid="51">10/10</div>
												</div>
											</a>
										</div>
									</div> -->
								</div>
			    			</div>
			    		</div>
			    	</div>
			    </div>

			    <div class="offset4">
			    	<div class="input-prepend input-append" style="margin-top:15px;margin-bottom: 14px;">
                        <span class="add-on">VIN</span>
                        <input type="text" class="input-medium" placeholder="VIN号..." id="vinInput"/>
                        <a class="btn queryVinBtn appendBtn"><i class="icon-search"></i></a>
                    </div>
			    	<!-- <div class="input-prepend input-append" style="margin-top:15px;margin-bottom: 14px;">
                        <span class="add-on">板号</span>
                        <input type="text" class="input-small" placeholder="备板号..." id="boardNumberInput"/>
                        <a class="btn queryBoardBtn appendBtn"><i class="icon-search"></i></a>
                    </div> -->
                    <div id="hintDiv" class="help-inline">通过VIN查询所在板车的订单及随附件清单</div>
                    <div id="printBtnDiv" class="help-inline" style="margin-top: 5px;"><span class="boardNumberText"></span><span>&nbsp;&nbsp;<i class="icon-arrow-right"></i>&nbsp;&nbsp;</span><a id="printList"><i class="icon-print"></i>打印附件清单</a></div>
                    <!-- <div class="boardNumberText"></div> -->
                    <div style="overflow-y: scroll; height:500px">
		                <table id="tableOrders" class="table table-condensed table-bordered" style="font-size:12px; display:none">
		                    <thead>
		                        <tr>
		                            <!-- <th style="width:50px">板号</th> -->
		                            <th style="min-width:30px">车道</th>
		                            <th style="min-width:100px">经销商</th>
		                            <th>订单号</th>
		                            <th style="min-width:30px">车系</th>
		                            <th style="min-width:180px">车型/配置</th>
		                            <th style="min-width:40px">耐寒性</th>
		                            <th style="min-width:50px">颜色</th>
		                            <th style="min-width:30px">数量</th>
		                            <th style="min-width:30px">已备</th>
		                            <th style="min-width:30px">出库</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    </tbody>
		                </table>
		                <div>
		                	<!-- <div class="boardNumberTextDiv"><span>&nbsp;&nbsp;随车附件清单</span></div> -->
			                <table class="accessoryListTable table table-condensed table-bordered" style="font-size:12px; display:none">
								<thead>
									<tr>
										<th style="width:60px">车系</th>
										<th style="min-width:180px">零部件编号</th>
										<th style="min-width:180px">零部件名称</th>
										<th style="width:60px">数量</th>
										<th style="width:60px">确认</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
		            </div>
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
  		<table class="table table-condensed table-hover" id="tableDetail">
    		<thead>
    			<tr>
	    			<!-- <th style="width:20px"></th> -->
	    			<th style="width:120px">VIN号</th>
	    			<th style="width:150px">出库时间</th>
	    			<th style="width:50px">证件</th>
	    			<th style="width:200px">经销商</th>
	    			<th style="width:30px">车系</th>
	    			<th style="width:200px">车型/配置</th>
	    			<th style="width:50px">耐寒性</th>
	    			<th style="width:50px">颜色</th>
	    			<th style="width:50px">发动机号</th>
    			</tr>
    		</thead>
    		<tbody>
    			<!-- <td><a class="btn btn-link" href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="打印"><i class="btnPrint icon-print"></i></a></td>
            	<td>LGXC34CG6D1014718</td>
    			<td></td>
            	<td>瑞亚银</td>
            	<td>QCJ6480MJ/2.4L尊贵/4G69</td>
            	<td>非耐寒</td>
            	<td>2013-04-18 09:00:00</td> -->
    		</tbody>
        </table>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="detailPrintAll" disabled><i class="btnPrint icon-print"></i>&nbsp;打印全部</button>
  	</div>
</div>

<div class="modal" id="spinModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;top:25%">
  	<div class="modal-body">
  		<div style="margin: 0 auto; width:40px;">
  		<i class="icon-spin icon-spinner" style="font-size:40px;line-height: 36px"></i>
	  	</div>
  	</div>
</div>
</div><!-- not printable -->
<div class="printable" style="width:570pt;margin: 0 auto;">
	<div class="logo">
		<img class="pull-right" src="/bms/img/byd-auto.jpg" alt="" >
	</div>
	<div style="margin: 0 auto; padding-right:10px">
		<div class="boardNumberTextDiv"><span class="boardNumberText"></span><span>&nbsp;&nbsp;随车附件清单</span></div>
		<div class="distributorsTextDiv"><span>经销商：</span><span class="distributorsText"></span></div>
		<table class="accessoryListTable table table-condensed table-bordered" style="font-size:12px;">
			<thead>
				<tr>
					<th colspan="5" style="text-align: left">
						交付人 / 日期：<?php for($i=0;$i<64;$i++) echo "&nbsp;" ?>
						签收人 / 日期：<?php for($i=0;$i<64;$i++) echo "&nbsp;" ?>
					</th>
				</tr>
				<tr>
					<th style="width:60px">车系</th>
					<th style="min-width:180px">零部件编号</th>
					<th style="min-width:180px">零部件名称</th>
					<th style="width:60px">数量</th>
					<th style="width:60px">确认</th>
				</tr>
			</thead>
			<tbody>
				<td>F0</td>
				<td>DDD</td>
				<td>AAAA</td>
				<td>DDDD</td>
				<td></td>
			</tbody>
		</table>
	</div>
</div>
</body>
</html>