<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>主配置单</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="/bms/css/execution/assembly/other/ConfigPaperMain.css" rel="stylesheet" media="screen">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/bms/css/printable.css" media="print">
	<style type="text/css" media="screen">
		.printable{
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
    <script type="text/javascript" src="/bms/js/execution/assembly/other/configPaperMain.js"></script>
	</head>
	<body>
		<div class="notPrintable">
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
    		<div class="offhead">
    			<?php
    			require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
    			?>
    			<div id="bodyright" class="offset2"><!-- Main体 -->
                    <div>
                        <legend>主配置单检验打印
                        </legend>
                    </div>
                			           
    				<div><!-- 内容主体 -->
    					<form id="form" class="well form-search">
    						<label>VIN</label>
    						<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText">
    						<input type="button" class="btn btn-primary" id ="btnSubmit" 
    							disabled="disabled" value ="打印检验跟单"></input>
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

        <div class="configPaper printable" page="1" style="width:840pt;height:1100pt; padding-top:10pt; font-size:18pt">
            <table class="" style="width:100%; margin-top:10pt;">
                <tr>
                    <td rowspan="2" width="40%" style="padding-left:10pt"><img src="" class="printBarCode" width="80%"></td>
                    <td width="50%" class="printType" style="font-size:18pt"></td>
                    <td style="text-align:right; font-size:18pt; width=10%; padding-right:10pt" class="printSeries"></td>
                </tr>
                <tr>
                    <td class="printConfig" style="font-size:18pt"></td>
                    <td style="text-align:right; font-size:18pt; padding-right:10pt" class="printSerialNumber"></td>
                </tr>
            </table>
            <img src="" width="" height="" class="printFrontImage" style="display: block; margin:0 auto">
            <table style="width:100%;margin-top:10pt;">
                <tr>
                    <td class="printRemark" style="font-size:18pt; padding-left:10pt"></td>
                    <td align="right" style="text-align:right; font-size:18pt; padding-right:10pt">1/2</td>
                </tr>
            </table>
        </div>

        <div class="configPaper printable" page="2" style="width:840pt;height:1100pt; padding-top:10pt; font-size:18pt; page-break-before: always">
            <table class="" style="width:100%; margin-top:10pt;">
                <tr>
                    <td rowspan="2" width="40%" style="padding-left:10pt"><img src="" class="printBarCode" width="80%"></td>
                    <td width="50%" class="printType" style="font-size:18pt"></td>
                    <td style="text-align:right; font-size:18pt; width=10%; padding-right:10pt" class="printSeries"></td>
                </tr>
                <tr>
                    <td class="printConfig" style="font-size:18pt"></td>
                    <td style="text-align:right; font-size:18pt; padding-right:10pt" class="printSerialNumber"></td>
                </tr>
            </table>
            <img src="" width="" height="" class="printBackImage" style="display: block; margin:0 auto">
            <table style="width:100%;margin-top:10pt;">
                <tr>
                    <td class="printRemark" style="font-size:18pt; padding-left:10pt"></td>
                    <td align="right" style="text-align:right; font-size:18pt; padding-right:10pt">2/2</td>
                </tr>
            </table>
        </div>
	</body>
</html>
