<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>订单车辆查询</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/query/OrderCarQuery.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">		
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
    	<script type="text/javascript" src="/bms/js/execution/assembly/query/orderCarQuery.js"></script>
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

        	<!-- Main体 -->	
    		
            <div id="bodyright" class="offset2">
                <div>
                    <legend>订单车辆查询
                        <span class="pull-right">
                            <!-- <a href="/bms/execution/orderMaintain"><i class="icon-link"></i>&nbsp;订单维护</a> -->
                        </span>
                    </legend>
                </div>
                <form class="well form-inline">
                    	<div class="input-prepend input-append">
							<span class="add-on">订单号</span>
							<input type="text" class="span3" placeholder="请输入订单号..." id="orderNumberText" />
                            <a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
                        </div>
                        <input type="button" class="btn btn-primary" id ="btnQuery" value ="查询"></input>
                        <div class="input-append">
                            <input id="standbyDate"  type="text" class="input-medium" placeholder="发车日期..."onClick="WdatePicker({el:'standbyDate',dateFmt:'yyyy-MM-dd'});"/>
                            <a class="btn resetDate appendBtn"><i class="icon-undo"></i></a>
                            <a class="btn clearinput appendBtn"><i class="icon-remove"></i></a>
                        </div>
                        <div id='divInfo' class="help-inline">
                            <span class="label label-info" rel="tooltip" title="经销商" id="distributorInfo"></span>
                        </div>
                </form>
                <table id="resultTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>订单号</th>
                            <th>车道</th>
                            <th>经销商</th>
                            <th>VIN</th>
                            <th>车系</th>
                            <th>车型</th>
                            <th>配置</th>
                            <th>耐寒性</th>
                            <th>颜色</th>
                            <th>发动机号</th>
                            <th>出库时间</th>
                            <th>备注</th>
                        </tr>
                    </thead>
                    <tbody>
                            
                    </tbody>
                    </table>
                </div>
            </div><!-- END MAIN -->
        </div>
	</body>
</html>
