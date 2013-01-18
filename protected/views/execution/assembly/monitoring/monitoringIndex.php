<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>生产监控</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="/bms/css/common.css" rel="stylesheet">
		<link href="/bms/css/jquery.qtip.css" rel="stylesheet">
        <link href="/bms/css/execution/assembly/monitoring/monitoringIndex.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src="/bms/js/head.js"></script>
		<script type="text/javascript" src="/bms/js/jquery.qtip.js"></script>
		<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/bms/js/service.js"></script>
		<script type="text/javascript" src="/bms/js/execution/assembly/monitoring/monitoringIndex.js"></script>
		
		
		<style type="text/css">
			#tableInfo td{text-align: center;}
			
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
            <div id="bodyright" class="offset2">
            	<div>
					<ul class="breadcrumb"><!-- 面包屑 -->
						<li>
							<a href="#">生产执行</a><span class="divider">&gt;</span>
						</li>
						<li>
							<a href="#">总装</a><span class="divider">&gt;</span>
						</li>
						<li class="active">
							生产监控
						</li>
						<li class="pull-right">
							welcome&nbsp;
						<a id="welcomeShop" href="#">车间板</a>
						<span class="divider">&frasl;</span>
						<a id="welcomeSection" href="#">工段板</a>
					</li>
					</ul>
				</div><!-- end 面包屑 -->

				
	          	<div id="divTabs">
			    	<ul id="tabs" class="nav nav-pills">
			            <li class="active"   id="liAssembly"><a href="#index" data-toggle="tab">总装车间</a></li>
			            <li class="" id="liDetecthouse"><a href="#detecthouse" data-toggle="tab">检测车间/成品库</a></li>
						<!-- <li class="" id="liWarehouse"><a href="#warehouse" data-toggle="tab">成品库</a></li> -->
			        </ul>
			    </div>
			    <div class="tab-content">
				    
		            <div id="index" class="tab-pane active main">
		            	<div class="stop_mark" id="stopMark"></div>
		            	<div class="t1 range" id="rangeT1"></div>
		            	<div class="t2 range" id="rangeT2"></div>
		            	<div class="t3 range" id="rangeT3"></div>
		            	<div class="c1 range" id="rangeC1"></div>
		            	<div class="c2 range" id="rangeC2"></div>
		            	<div class="f1 range" id="rangeF1"></div>
		            	<div class="f2 range" id="rangeF2"></div>
		            	<div class="optNode ef1">EF1</div>
		            	<div class="optNode ef2">EF2</div>
		            	<div class="optNode ef3">EF3</div>
		            	<div class="optNode main_chain">主链</div>
		            	<div class="pbs" id="pbsBalance"></div>
		            	<div class="vq1" id="vq1Balance"></div>
		            	<div class="vq1_exception" id="vq1ExceptionBalance"></div>
		            	<div class="andon_bord" id="andon_board"></div>
		            	<div class="node_pbs"></div>
		            	<div class="node_t0"></div>
		            	<div class="node_vq1"></div>
		            	<div class="pauseTimeArea" id="pauseTimeArea" style="color:red;">
		            		<div>
		            			device: <span id="pauseTimeDevice"></span>
		            		</div>
		            		<div>
		            			<span id="pauseTimeT1" style='padding-left:50px'></span>
		            			<span id="pauseTimeT2" style='padding-left:50px'></span>
		            			<span id="pauseTimeT3" style='padding-left:70px'></span>
		            			<span id="pauseTimeC1" style='padding-left:70px'></span>
		            			<span id="pauseTimeC2" style='padding-left:40px'></span>
		            			<span id="pauseTimeF1" style='padding-left:47px'></span>
		            			<span id="pauseTimeF2" style='padding-left:50px'></span>
		            		</div>
		            			
		            	</div>
		            </div>
		            <div id="detecthouse" class="tab-pane ">		            	
		            	<div id="background" class="pull-left">
				    		<div class="A01 stockyard">A01</div>
				  		  	<div class="A02 stockyard">A02</div>
				    		<div class="A03 stockyard">A03</div>
				    		<div class="A04 stockyard">A04</div>
				    		<div class="A05 stockyard">A05</div>
				    		<div class="A06 stockyard">A06</div>
				    		<div class="A07 stockyard">A07</div>
				    		<div class="A08 stockyard">A08</div>
				    		<div class="A09 stockyard">A09</div>
				    		<div class="A10 stockyard">A10</div>
				    		<div class="A11 stockyard">A11</div>
				    		<div class="A12 stockyard">A12</div>
				    		<div class="A13 stockyard">A13</div>
				    		<div class="A14 stockyard">A14</div>
				    		<div class="A15 stockyard">A15</div>
				    		<div class="A16 stockyard">A16</div>
				    		<div class="B01">B01</div>
				    		<div class="B02">B02</div>
				    		<div class="outware node"></div>
				    		<div class="inware node"></div>
				    		<div class="vq3 node"></div>
				    		<div class="leak node"></div>
				    		<div class="road node"></div>
				    		<div class="check node"></div>
				    		<div class="vq3-balance">36</div>
				    		<div class="vq2-road">84</div>
				    		<div class="vq2-leak"></div>
				    		<div class="vq2-check"></div>
				    		<div class="stock-amount">36</div>
				    	</div>
				    	<div class="span4" style="margin-top:12px;" id="block">
                			<a class="thumbnail" href="">
                  				<p class="pull-left">A111</p>
                  				<div class="progress">
                    				<div class="bar" style="width: 60%;">6 / 10</div>
                  				</div>
                			</a>
                			<a class="thumbnail" href="" style="margin:10px 0;">
                  				<p class="pull-left" style="margin:0 10px;">A112</p>
                    			<div class="progress" style="margin-bottom:0;">
                      				<div class="bar" style="width: 30%;">3 / 10</div>
                    			</div>
                			</a>
                			<a class="thumbnail" href="" style="margin:10px 0;">
                  				<p class="pull-left" style="margin:0 10px;">A113</p>
                    			<div class="progress" style="margin-bottom:0;">
                      				<div class="bar" style="width: 70%;">7 / 10</div>
                    			</div>
                			</a>
                			<a class="thumbnail" href="" style="margin:10px 0;">
                  				<p class="pull-left" style="margin:0 10px;">A114</p>
                  				<div class="progress progress-success" style="margin-bottom:0;">
                    					<div class="bar" style="width: 100%;">10 / 10</div>
                  				</div>
                			</a>
                			<a class="thumbnail" href="" style="margin:10px 0;">
                  				<p class="pull-left" style="margin:0 10px;">A115</p>
                    			<div class="progress" style="margin-bottom:0;">
                      				<div class="bar" style="width: 40%;">4 / 10</div>
                    			</div>
                			</a>
                			<a class="thumbnail" href="" style="margin:10px 0;">
                  				<p class="pull-left" style="margin:0 10px;">A116</p>
                    			<div class="progress" style="margin-bottom:0;">
                      				<div class="bar" style="width: 60%;">6 / 10</div>
                    			</div>
                			</a>
              			</div>
		         </div>
		         <form class="bs-docs-example form-search" id="generalInfo">
		            <!-- <i class="icon-hand-right" style="margin: 8px 10px 0 32px;"></i>
		            <div style="display:inline-block;" id="radioInfo">
			            <label class="radio inline">
			              <input type="radio" name="monitoringType" id="monitoringType1" value="productInfo" >
			              生产
			            </label>
			            <label class="radio inline">
			              <input type="radio" name="monitoringType" id="monitoringType2" value="qualityInfo">
			              质量
			            </label>
			            <label class="radio inline">
			              <input type="radio" name="monitoringType" id="monitoringType3" value="storeInfo">
			              结存
			            </label>
			            <label class="radio inline">
			              <input type="radio" name="monitoringType" id="monitoringType4" value="hideInfo" checked="checked">
			              隐藏
			            </label>
		            </div> -->
		            <div>
		            <label class="checkbox" style="display:inline-block;padding-top:7px;">
				      <input type="checkbox" id="togglePauseTime">停线时间
				    </label> 
		           		<label style="padding-top:7px; margin-bottom: 0">，合计：&nbsp;&nbsp;<span id="totalPauseTime"></span></label>
						<label style="padding-top:7px; margin-bottom: 0">。&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;生产利用率：&nbsp;&nbsp;<span id="line_urate"></span></label>
						<label style="padding-top:7px; margin-bottom: 0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;线速：&nbsp;&nbsp;<span id="line_speed"></span></label>
						<label style="padding-top:7px; margin-bottom: 0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;周转车：&nbsp;&nbsp;<span id="recycleCar"></span></label>
					</div>
	          	</form>	
	            <div style="width:756px;margin-left:44px;display:none;">
	            	<table class="table table-bordered" id="tableInfo">
	            		
	            		<tbody>
	            			<thead>
	            				<tr>
	            					<th style="width:150px;text-align:center;">车系</th>
	            					<th style="width:150px;text-align:center;">DPVe / DRR</th>
	            					<th style="width:150px;text-align:center;">VQ1</th>
	            					<th style="width:150px;text-align:center;">VQ2</th>
	            					<th style="width:150px;text-align:center;">VQ3</th>
	            				</tr>
	            			</thead>
	            			<tbody>
	            				<tr>
	            					<td>F0</td>
	            					<td id="DPVeDRR"></td>
	            					<td id="VQ1DPVeDRR"></td>
	            					<td id="VQ2DPVeDRR"></td>
	            					<td id="VQ3DPVeDRR"></td>
	            				</tr>
	            			</tbody>
	            			<!-- <tr>
	            				<td style="border-top:none;border-left:none;">&nbsp;</td>
	            				<td style="border-top:none;border-left:none;">&nbsp;</td>
	            				<td style="border-top:1px solid #ddd;">VQ1</td>
	            				<td style="border-top:1px solid #ddd;">VQ2</td>
	            				<td style="border-top:1px solid #ddd;">VQ3</td>
	            			</tr>
	            			<tr>
	            				<td id="totalDPU"></td>
	            				<td>DPVe</td>
	            				<td id="vq1DPU"></td>
	            				<td id="vq2DPU"></td>
	            				<td id="vq3DPU"></td>
	            
	            			</tr>
	            			<tr>
	            				<td id="totalDRR"></td>
	            				<td>DRR</td>
	            				<td id="vq1DRR"></td>
	            				<td id="vq2DRR"></td>
	            				<td id="vq3DRR"></td>
	            
	            			</tr> -->
	            			
	            		</tbody>
	            	</table>
	            </div>
	        </div>

		</div>

		<!-- pbs record -->
		<div class="modal" id="pbsBalanceModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;width:800px;margin-left:-400px;">
		  	<div class="modal-header">
		    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		   	 	<h3>PBS结存明细</h3>
		  	</div>
		  	<div class="modal-body">
		  		<table class="table table-bordered" id="pbsBalanceTable">
            		<thead><th>车系</th>
            			<th>VIN号</th>
            			<th>车型/车身</th>
            			<th>颜色</th>
            			<th>进入时间</th></thead>
            		<tbody></tbody>
	            </table>
		  	</div>
		  	<div class="modal-footer">
			    <button class="btn" data-dismiss="modal" aria-hidden="true">确认</button>
		  	</div>
		</div>

		<!-- vq1 record -->
		<div class="modal" id="vq1BalanceModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;width:800px;margin-left:-400px;">
		  	<div class="modal-header">
		    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		   	 	<h3>VQ1结存明细</h3>
		  	</div>
		  	<div class="modal-body">
		  		<table class="table table-bordered" id="vq1BalanceTable">
            		<thead>
            			<th>车系</th>
            			<th>VIN号</th>
            			<th>车型/车身</th>
            			<th>颜色</th>
            			<th>进入时间</th>
            		</thead>
            		<tbody></tbody>
	            </table>
		  	</div>
		  	<div class="modal-footer">
			    <button class="btn" data-dismiss="modal" aria-hidden="true">确认</button>
		  	</div>
		</div>

		<!-- vq1Exception record -->
		<div class="modal" id="vq1ExceptionBalanceModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;width:800px;margin-left:-400px;">
		  	<div class="modal-header">
		    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		   	 	<h3>VQ1异常结存明细</h3>
		  	</div>
		  	<div class="modal-body">
		  		<table class="table table-bordered" id="vq1ExceptionBalanceTable">
            		<thead>
            			<th>车系</th>
            			<th>VIN号</th>
            			<th>车型/车身</th>
            			<th>颜色</th>
            			<th>进入时间</th>

            		</thead>
            		<tbody></tbody>
	            </table>
		  	</div>
		  	<div class="modal-footer">
			    <button class="btn" data-dismiss="modal" aria-hidden="true">确认</button>
		  	</div>
		</div>

		

		<!-- stockyard record -->
		<div class="modal" id="stockyardModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;width:800px;margin-left:-400px;">
		  	<div class="modal-header">
		    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		   	 	<h3 id="stockyardTitle">VQ1异常结存明细</h3>
		  	</div>
		  	<div class="modal-body">
		  		<table class="table table-bordered" id="stockyardTable">
            		<thead>
            			<th>车系</th>
            			<th>VIN号</th>
            			<th>车型/车身</th>
            			<th>颜色</th>
            			<th>进入时间</th>

            		</thead>
            		<tbody></tbody>
	            </table>
		  	</div>
		  	<div class="modal-footer">
			    <button class="btn" data-dismiss="modal" aria-hidden="true">确认</button>
		  	</div>
		</div>

		<!-- modal -->
		<div class="modal" id="modal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;width:800px;margin-left:-400px;">
		  	<div class="modal-header">
		    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		   	 	<h3 id="modalTitle"></h3>
		  	</div>
		  	<div class="modal-body">
		  		<table class="table table-bordered" id="modalTable">
            		<thead>
            			<th>车系</th>
            			<th>VIN号</th>
            			<th>车型/车身</th>
            			<th>颜色</th>
            			<th>进入时间</th>

            		</thead>
            		<tbody></tbody>
	            </table>
		  	</div>
		  	<div class="modal-footer">
			    <button class="btn" data-dismiss="modal" aria-hidden="true">确认</button>
		  	</div>
		</div>
    </body>
</html>
