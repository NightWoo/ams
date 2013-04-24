<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>备车订单</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/execution/assembly/other/OrderMaintain.css" rel="stylesheet">	
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bms/js/common.js"></script>
	<script type="text/javascript" src="/bms/js/head.js"></script>
	<script type="text/javascript" src="/bms/js/execution/assembly/other/orderMaintain.js"></script>
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
            	<legend>发车计划
            		<span class="pull-right">
            			<!-- <a href="/bms/execution/orderMaintain"><i class="icon-link"></i>&nbsp;订单维护</a> -->
            		</span>
            	</legend>
            </div>
            
   	   		<div><!-- 主体 -->
			    <!-- <div class="accordion span4 pull-left" id="accordionLane">
			    	<div class="accordion-group" id="laneGroup1">
			    		<div class="accordion-heading">
			    			<div class="headBadge">
		    				 	<span class="label label-success" id="freeGroup1">2</span>
		    				 	<span class="label label-info" id="loadingGroup1">3</span>
			    				<span class="label label-warning" id="fullGroup1">5</span>
			    			</div>
			    		</div>
			    		<div id="collapse1" class="accordion-body collapse in">
			    			<div class="accordion-inner">
			    				<div class="block">
			    					<div>
				    					<div class="pull-left laneContainer">
					    					<a class="thumbnail" href="#">
												<p class="pull-left">#01</p>
												<div class="progress progress-warning">
													<div class="bar" style="width:100%">10/10</div>
												</div>
											</a>
										</div>
										<a class="btn btn-link distribution"><i class="icon-list"></i></a>
										<a class="btn btn-link resetLane"><i class="icon-undo"></i></a>
									</div>

									<div>
				    					<div class="pull-left laneContainer">
					    					<a class="thumbnail" href="#">
												<p class="pull-left">#02</p>
												<div class="progress progress-warning">
													<div class="bar" style="width:100%">10/10</div>
												</div>
											</a>
										</div>
										<a class="btn btn-link distribution"><i class="icon-list"></i></a>
										<a class="btn btn-link resetLane"><i class="icon-undo"></i></a>
									</div>
								</div>
			    			</div>
			    		</div>
			    	</div>
			    </div> -->

			    <div class="">
			    	<form id="form" class="well form-inline">
	                    <table>
	                        <tr>
	                            <td>备车日期&nbsp;<a href="#" id="refreshDate"><i class="icon-refresh"></i></a></td>
	                            <td>订单号</td>
	                            <td>经销商</td>
	                            <td>车系</td>
	                            <td></td>
	                        </tr>
	                        <tr>
	                            <td>
	                            	<div class="input-append">
								      	<input id="standbyDate"  type="text" class="input-small" placeholder="备车日期..."onClick="WdatePicker({el:'standbyDate',dateFmt:'yyyy-MM-dd'});"/>
							      		<a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
							    	</div>
	                            </td>
	                        	<td>
	                        		<div class="input-append">
		                        		<input id="orderNumber" type="text" class="input-medium" placeholder="订单号...">
							      		<a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
							    	</div>
	                        	</td>
	                           <td>
		                           	<div class="input-append">
		                           		<input id="distributor" type="text" class="input-medium" placeholder="经销商...">
							      		<a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
							    	</div>
	                           </td>
	                           <td>
	                           		<select name="" id="selectSeries" class="input-small">
		                                <option value="">全车系</option>
		                                <option value="F0">F0</option>
		                                <option value="M6">M6</option>
		                                <option value="6B">思锐</option>
		                            </select>
	                           </td>
	                            <td>
	                                <input type="button" class="btn btn-primary" id="btnQuery" value="查询" style="margin-left:2px;"></input>   
	                                <input id="btnAdd" type="button" class="btn btn-success" value="录入"></input>
	                                <label class="checkbox"><input type="checkbox" id="checkboxActive" value="1">激活</input></label>
		                            <label class="checkbox"><input type="checkbox" id="checkFreeze" value="0">冻结</input></label>
		                            <label class="checkbox"><input type="checkbox" id="checkClosed" value="2">完成</input></label>
	                            </td>
	                        </tr>
	                    </table>
	                </form>
	                
	                <table id="tableResult" class="table table-condensed table-hover" style="font-size:12px;">
	                    <thead>
	                        <tr>
	                            <!-- <th>#</th> -->
	                            <th id="thReorder">调整</th>
	                            <th id="thPriority">优先</th>
	                            <th id="thStatus">状态</th>
	                            <th id="thBoard">备板编号</th>
	                            <th id="thLane">车道</th>
	                            <th id="thOrderNumber">订单号</th>
	                            <th id="thDistributor">经销商</th>
	                            <th id="thSeries">车系</th>
	                            <th id="thCarType">车型/配置</th>
	                            <!-- <th id="thConfig">配置</th> -->
	                            <th id="thColdResistant">耐寒性</th>
	                            <th id="thColor">颜色</th>
	                            <th id="thAmount">数量</th>
	                            <th id="thHold">已备</th>
	                            <th id="thCount">出库</th>
	                            <!-- <th id="thOrderType">订单类型</th> -->
	                            <!-- <th id="thRemark">备注</th> -->
	                            <!-- <th id="thEdit"></th> -->
	                        </tr>
	                    </thead>
	                    <tbody>
	                        
	                    </tbody>
	                </table>
			    </div>
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
	</div><!-- offhead -->
<!-- new record -->
<div class="modal" id="newModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>录入订单</h3>
  	</div>
  	<div class="modal-body">
  		<form id="" class="form-horizontal">
  			<div class="control-group">
			    <label class="control-label" for="">*&nbsp;备板编号</label>
			    <div class="controls">
			      	<input type="text" id="newBoardNumber" class="input-small" placeholder="备板编号...">
			      	<input type="text" id="newStandbyDate" class="input-small" placeholder="备车日期..."  onClick="WdatePicker({el:'newStandbyDate',dateFmt:'yyyy-MM-dd'});">
			    </div>
			</div> 
			<div class="control-group">
			    <label class="control-label" for="">*&nbsp;获取订单</label>
			    <div class="controls">
			    	<div class="input-append">
				      	<input type="text" id="newOrderNumber" class="input-medium" placeholder="订单号...">
			      		<a class="btn appendBtn" id="newGetOrder"><i class="icon-search"></i></a>
			    	</div>
			    	<span class="help-inline" id="hint">请输入订单号取得订单明细</span>
			      	<div class="help-inline" id="orderInfo" style="display:none">
						<!-- <span class="label label-info" rel="tooltip" title="经销商" id="newDistributor" code=""></span> -->
			      		<a class="btn btn-link" id="newClearOrder"><i class="icon-remove"></i></a>
					</div>
			    </div>
			</div> 	  
  			<!-- <div class="control-group">
                <label class="control-label" for="">&nbsp;承运</label>
                <div class="controls">
                    <input id="newCarrier" type="text" class="input-medium" disabled="disabled" placeholder="输入承运商..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">&nbsp;城市</label>
                <div class="controls">
                    <input id="newCity" type="text" class="input-medium" disabled="disabled" placeholder="输入城市..."/>
                </div>
            </div>  --> 
		</form>
		<legend></legend>
		<table id="tableNewOrder" class="table table-condensed table-hover" style="display:none">
			<thead>
				<tr>
					<th>选择</th>
					<th>经销商</th>
					<th>数量</th>
					<th>车系</th>
					<th>车型</th>
					<th>耐寒性</th>
					<th>颜色</th>
					<th>配置</th>
					<th>车道</th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-success" id="btnAddMore">继续录入</button>
	    <button class="btn btn-primary" id="btnAddConfirm">确认录入</button>
  	</div>
</div>
<!-- edit record -->
<div class="modal" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>编辑</h3>
    </div>
    <div class="modal-body">
        <form id="editForm" class="form-horizontal">
        	<div class="control-group">
                <label class="control-label" for="editBoardNumber">*&nbsp;备板编号</label>
                <div class="controls">
                    <input id="editBoardNumber"  type="text" class="input-small" placeholder="备板编号..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editStandbyDate">*&nbsp;备车日期</label>
                <div class="controls">
                    <input id="editStandbyDate"  type="text" class="input-small" placeholder="备车日期..."onClick="WdatePicker({el:'editStandbyDate',dateFmt:'yyyy-MM-dd'});"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editStatus">&nbsp;状态</label>
                <div class="controls">
                	<select id="editStatus" class="input-small">
                		<option value="0">冻结</option>
                		<option value="1">激活</option>
                		<option value="2">关闭</option>
                	</select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editLane">&nbsp;车道</label>
                <div class="controls">
                    <select id="editLane"  name=""class="input-small">
                        <option value="0" selected>未选择</option>
                        <?php 
                            for($i=1;$i<52;$i++){
                                $num = sprintf("%02d", $i);
                                if($i<51){
	                                $ret = "<option value=". $i .">$num</option>";
                                } else {
	                                $ret = "<option value=". $i .">加车道</option>";
                                }
                                echo $ret;
                            }

                            $j = 50;
                            for($i=126;$i<176;$i++){
                                $num = 'A' . sprintf("%02d", $j);
                                $j--;
	                            $ret = "<option value=". $i .">$num</option>";
                                echo $ret;
                            }
                        ?>
                    </select>
                </div>
            </div>
            <!-- <div class="control-group">
                <label class="control-label" for="editCarrier">*&nbsp;承运商</label>
                <div class="controls">
                    <input id="editCarrier" type="text" class="input-medium" placeholder="输入承运商..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="ditCity">*&nbsp;城市</label>
                <div class="controls">
                    <input id="editCity" type="text" class="input-medium" placeholder="输入城市..."/>
                </div>
            </div> -->
            <div class="control-group">
                <label class="control-label" for="">经销商</label>
                <div class="controls">
                    <input type="text" id="editDistributorName" class="input-large" placeholder="请输入经销商">
                </div>
            </div>
            <!-- <div class="control-group">
                <label class="control-label" for="editOrderNumber">*&nbsp;订单号</label>
                <div class="controls">
                    <input id="editOrderNumber" type="text" class="input-medium" placeholder="输入订单号..."/>
                </div>
            </div> -->
            <div class="control-group">
                <label class="control-label" for="editAmount">*&nbsp;数量</label>
                <div class="controls">
                    <input id="editAmount" type="text" class="input-small" placeholder="请输入数量..."/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editSeries">*&nbsp;车系</label>
                <div class="controls">
                    <select id="editSeries" class="input-small">
                        <option value="" selected>请选择</option>
                        <option value="F0">F0</option>
                        <option value="M6">M6</option>
                        <option value="6B">思锐</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editCarType">*&nbsp;车型</label>
                <div class="controls">
                    <select id="editCarType" name="" class="input-large">
                        <!-- <option value="">请选择</option> -->
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="editOrderConfig">*&nbsp;配置</label>
                <div class="controls">
                    <select id="editOrderConfig" name=""class="input-medium">
                        <!-- <option value="">请选择</option> -->
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="ditColor">*&nbsp;颜色</label>
                <div class="controls">
                    <select id="editColor" name=""class="input-small">
                        <!-- <option value="">请选择</option> -->
                    </select> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">耐寒型</label>
                <div class="controls">
                    <input id="editColdResistant" type="checkbox">
                </div>
            </div>
            <!-- <div class="control-group">
                <label class="control-label" for="editOrderType">*&nbsp;订单类型</label>
                <div class="controls">
                    <select id="editOrderNature" class="input-medium">
                        <option value="0">普通订单</option>
                        <option value="1">三方订单</option>
                    </select>
                </div>
            </div> -->
            <div class="control-group">
                <label class="control-label" for="editRemark">备注</label>
                <div class="controls">
                    <textarea id="editRemark" class="input-large"rows="2"></textarea>
                </div>
            </div>        
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <button class="btn btn-primary" id="btnEditConfirm">确认编辑</button>
    </div>
</div>

<!-- new record -->
<div class="modal" id="laneModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>车道分配</h3>
  	</div>
  	<div class="modal-body">
  		<form id="" class="form-horizontal">
  			<div class="control-group">
			    <label class="control-label" for="">订单号/经销商</label>
			    <div class="controls">
				      	<input type="text" id="laneOrderNumber" class="input-medium" placeholder="订单号..."> / 
				      	<input type="text" id="laneDistributor" class="input-medium" placeholder="经销商...">
			      		<button class="btn" id="laneOrderQuery"><i class="icon-search"></i></button>
			    </div>
			</div> 	  
		</form>
		<legend>未分配订单</legend>
		<table id="tableDistribution" class="table table-condensed table-hover">
			<thead>
				<tr>
					<!-- <th style="width:80px">#</th> -->
					<th style="width:30px">释放</th>
					<th style="width:30px">数量</th>
					<th style="width:140px">订单号</th>
					<th style="width:200px">经销商</th>
					<th style="width:30px">车系</th>
					<th style="width:200px">车型/配置</th>
					<th style="width:40px">耐寒性</th>
					<th style="width:40px">颜色</th>
					<!-- <th>配置</th> -->
					<!-- <th>备车日期</th> -->
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<!-- <td>1187177</td> -->
					<td>
						<a href="#" title="加配此单"><i class="icon-plus-sign icon-large"></i></a>
					</td>
					<td>20</td>
					<td>ZCDG-20130220992705</td>
					<td>广元市万仕达汽车销售服务有限公司</td>
					<td>F0</td>
					<td>QCJ7100L(1.0排量舒适型)</td>
					<td>耐寒</td>
					<td>冰岛蓝</td>
					<!-- <td>F0实用助力</td> -->
					<td>
						<input type="text" class="input-mini" placeholder="0"/><a class="btn btn-link">分拆</a>
					</td>
				</tr>
				<tr>
					<!-- <td>1187177</td> -->
					<td>
						<a href="#" title="加配此单"><i class="icon-plus-sign icon-large"></i></a>
					</td>
					<td>20</td>
					<td>ZCDG-20130220992705</td>
					<td>广元市万仕达汽车销售服务有限公司</td>
					<td>F0</td>
					<td>QCJ7100L(1.0排量舒适型)</td>
					<td>耐寒</td>
					<td>冰岛蓝</td>
					<!-- <td>F0实用助力</td> -->
					<td>
						<input type="text" class="input-mini" placeholder="0"/><a class="btn btn-link">分拆</a>
					</td>
				</tr>
			</tbody>
		</table>

		<legend>车道01调整</legend>
		<table id="tableAdjust" class="table table-condensed table-hover">
			<thead>
				<tr>
					<!-- <th style="width:80px">#</th> -->
					<th style="width:30px">释放</th>
					<th style="width:30px">数量</th>
					<th style="width:140px">订单号</th>
					<th style="width:200px">经销商</th>
					<th style="width:30px">车系</th>
					<th style="width:200px">车型/配置</th>
					<th style="width:40px">耐寒性</th>
					<th style="width:40px">颜色</th>
					<!-- <th>配置</th> -->
					<!-- <th>备车日期</th> -->
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<!-- <td>1187177</td> -->
					<td>
						<input id="checkboxDistribution1" type="checkbox">
					</td>
					<td>20</td>
					<td>ZCDG-20130220992705</td>
					<td>广元市万仕达汽车销售服务有限公司</td>
					<td>F0</td>
					<td>QCJ7100L(1.0排量舒适型)</td>
					<td>耐寒</td>
					<td>冰岛蓝</td>
					<!-- <td>F0实用助力</td> -->
				</tr>
				<tr>
					<!-- <td>1187177</td> -->
					<td>
						<input id="checkboxDistribution2" type="checkbox">
					</td>
					<td>20</td>
					<td>ZCDG-20130220992705</td>
					<td>广元市万仕达汽车销售服务有限公司</td>
					<td>F0</td>
					<td>QCJ7100L(1.0排量舒适型)</td>
					<td>耐寒</td>
					<td>冰岛蓝</td>
					<!-- <td>F0实用助力</td> -->
				</tr>
			</tbody>
		</table>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
  	</div>
</div>

<!-- edit record -->
<div class="modal" id="splitModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>分拆</h3>
    </div>
    <div class="modal-body">
        <form id="splitForm" class="form-horizontal">
            <!-- <div class="control-group">
                <label class="control-label" for="splitStandbyDate">*&nbsp;备车日期</label>
                <div class="controls">
                    <input id="splitStandbyDate"  type="text" class="input-small" placeholder="备车日期..."onClick="WdatePicker({el:'editStandbyDate',dateFmt:'yyyy-MM-dd'});"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="splitStatus">&nbsp;状态</label>
                <div class="controls">
                	<select id="splitStatus" class="input-small">
                		<option value="0">冻结</option>
                		<option value="1">激活</option>
                		<option value="2">关闭</option>
                	</select>
                </div>
            </div> -->
            <div class="control-group">
                <label class="control-label" for="splitLane">&nbsp;车道</label>
                <div class="controls">
                    <select id="splitLane"  name=""class="input-small">
                        <option value="0" selected>未选择</option>
                        <?php 
                            for($i=1;$i<52;$i++){
                                $num = sprintf("%02d", $i);
                                if($i<51){
	                                $ret = "<option value=". $i .">$num</option>";
                                } else {
	                                $ret = "<option value=". $i .">加车道</option>";
                                }
                                echo $ret;
                            }

                            $j = 50;
                            for($i=126;$i<176;$i++){
                                $num = 'A' . sprintf("%02d", $j);
                                $j--;
	                            $ret = "<option value=". $i .">$num</option>";
                                echo $ret;
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="splitAmount">*&nbsp;分拆数量</label>
                <div class="controls">
                    <input id="splitAmount" type="text" class="input-small" placeholder="请输入数量..."/>
                </div>
            </div>      
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <button class="btn btn-primary" id="btnSplitConfirm">确认编辑</button>
    </div>
</div>
  	
</body>
</html>