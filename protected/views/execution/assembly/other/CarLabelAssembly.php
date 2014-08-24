<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>车辆标签</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="/bms/css/execution/assembly/other/CarLabelAssembly.css" rel="stylesheet" media="screen">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/bms/css/printable.css" media="print">
    <link href="/bms/css/execution/assembly/dataInput/T11-F10_Print.css" rel="stylesheet"  media="print">
	<style type="text/css" media="screen">
		.toPrintable{
			display: none;
		}
	</style>
    <style type="text/css" media="print">
            td,th{border:none;}
    </style>
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/common.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/other/carLabelAssembly.js"></script>
	</head>
	<body>
		<div class="notPrintable">
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
    		<div class="offhead">
    			<?php
    			// require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
    			?>
    			<div id="bodyright" class="offset2"><!-- Main体 -->
                    <div>
                        <legend>车辆标签打印
                            <span class="pull-right">
                                <a href="/bms/execution/child?view=F10&node=C21"><i class="fa fa-link"></i>&nbsp;I线_C10</a>
                                /
                                <a href="/bms/execution/child?view=F10_2&node=C21_2"><i class="fa fa-link"></i>&nbsp;II线_C10</a>
                            </span>
                        </legend>
                    </div>
                			           
    				<div><!-- 内容主体 -->
    					<form id="form" class="well form-search">
    						<label>VIN</label>
    						<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText">
    						<button type="button" class="btn btn-primary" id ="btnSubmit" 
    							disabled="disabled"><i class="fa fa-print"></i>&nbsp;车辆标签</button>
    						<input type="button" class="btn" id ="reset" value ="清空"></input>
    						<span class="help-inline" id="vinHint">请输入VIN后回车</span>
    						<div class="help-inline" id="carInfo">
    							<span class="label label-info" rel="tooltip" title="流水号" id="serialNumber"></span>
    							<span class="label label-info" rel="tooltip" title="车系" id="series"></span>
    							<!--<span class="label label-info" rel="tooltip" title="Vin号" id="vin"></span>-->
    							<span class="label label-info" rel="tooltip" title="车型" id="type"></span>
    							<span class="label label-info" rel="tooltip" title="颜色" id="color"></span>
                                <span class="label label-info" rel="tooltip" title="车辆区域" id="statusInfo"></span>
    						</div>
    					</form>
    					<div>
    						<div id="messageAlert" class="alert"></div>    
    					</div> <!-- end 提示信息 -->
    				</div><!-- end 内容主体 -->		
    			</div><!-- end main体 -->
    		</div><!-- end offhead -->
    	</div><!-- end notPrintable -->

        <div class="toPrintable" style="padding: 5pt; width: 220pt;">
            <div id="printBarCode">
                <div>
                    <p id="carSeriesInfo">F0</p>
                    <p id="carTypeShort">QCJ7100L(1.0排量舒适型)</p>
                </div>
                <div style="">
                    <img class="vinBarcode" alt="" src="/bms/img/barcodeTest.jpeg" style="width:247px; height:60px;">
                </div>
            </div>

            <div id="printInfo" style="page-break-before: always">
                <div>
                    <p class="printDate">-</p>
                </div>
                <div>
                    <p class="printSerialNumber">-</p>
                </div>
                <div>
                    <p class="printModel">-</p>
                </div>
                <div>
                    <p class="printConfig">-</p>
                </div>
                <div>
                    <p class="printRemark">-</p>
                </div>
            </div>
        </div>
	</body>
</html>
