<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $nodeDisplayName;?></title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="/bms/css/execution/assembly/dataInput/F20.css" rel="stylesheet" media="screen">
	<link href="/bms/css/common.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/bms/css/printable.css" media="print">
	<style type="text/css" media="screen">
		.printable{
			display: none;
		}
	</style>
    <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
	<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/common.js"></script>
    <script type="text/javascript" src="/bms/js/execution/assembly/dataInput/f20.js"></script>
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
                    <legend><?php echo $nodeDisplayName;?>
                        <span class="pull-right">
                            <a href="/bms/execution/checkPaper"><i class="icon-link"></i>&nbsp;检验跟单</a>
                        </span>
                    </legend>
                </div>
            			           
				<div><!-- 内容主体 -->
					<form id="form" class="well form-search">
						<label>VIN</label>
						<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText">
						<input type="button" class="btn btn-primary" id ="btnSubmit" 
							disabled="disabled" value ="打印检验跟单"></input>
						<input type="button" class="btn" id ="reset" value ="清空"></input>
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
					</form>
					<div>
						<div id="messageAlert" class="alert"></div>    
					</div> <!-- end 提示信息 -->
				</div><!-- end 内容主体 -->		
			</div><!-- end main体 -->
		</div><!-- end offhead -->
    	</div>
        <div id="printF0" class="printable">
        	<div style="height:560pt;width:820pt;margin: 0px auto;">
                <div class="logo"><img src="/bms/img/byd-auto.jpg" alt="" ></div>
            <table class="tableHead">
                <thead>
                    <tr style="height:20pt;">
                        <td colspan="13" class="docCode" style="text-align:right; border:none;">FM-MSP-18-D11F01-004-01A&nbsp;&nbsp;&nbsp;&nbsp;★一般★</td>
                    </tr>
                    <tr style="height:20pt">
                        <th></th>
                        <th style="width:209pt; vertical-align:top;">整车检验单</th>
                        <td style="width:42pt">车系</td>
                        <td class="printSeries" style="width:42pt"></td>
                        <td style="width:42pt">整车编号</td>
                        <td class="printSerialNumber" colspan="3" style="width:84pt"></td>
                        <td style="width:42pt">日期</td>
                        <td class="printDate" style="width:66pt"></td>
                        <td style="width:42pt">确认</td>
                        <td style="width:126pt"></td>
                    </tr>
                </thead>     
                <tbody>
                    <tr class="trInfo" style="height:20pt">
                        <td rowspan="2">VIN</td>
                        <td rowspan="2" class="barcode"><img class="vinBarcode" alt=""></td>
                        <td rowspan="2"><h2>VQ1</h2></td>
                        <td rowspan="2" colspan="5"></td>
                        <td>车型 / 配置</td>
                        <td colspan="3" class="printCarType"></td>
                    </tr>
                    <tr class="trInfo" style="height:20pt">
                        <td>颜色</td>
                        <td class="printColor"></td>
                        <td style="width:42pt">备注</td>
                        <td class="printRemark"></td>
                    </tr>
                    <tr class="trInfo" style="height:40pt">
                        <td>发动机<br>编号</td>
                        <td class="barcode"><img class="engineBarcode" alt=""></td>
                        <td><h2>VQ2</h2></td>
                        <td colspan="5"></td>
                        <td><h2>VQ3</h2></td>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tr class="trList">
                    <td colspan="6" class="tdList alignCenter">【VQ1_静态性能】</td>
                    <td colspan="3" class="tdList alignCenter">【VQ2_动态性能】</td>
                </tr>
                <tr class="trList">
                    <td class="tdList alignRight">发动机舱〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck">确认</td>
                    <td class="tdCheck">责任</td>
                    <td class="tdList alignRight">右侧门+副座椅+行李舱〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck">确认</td>
                    <td class="tdCheck">责任</td>
                    <td class="tdList alignRight">检测线〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck">确认</td>
                    <td class="tdCheck">责任</td>
                </tr>
                <tr class="trList">
                    <td class="tdList">VIN码拓印字迹不清晰/无拓印/破损与实车不一致</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右前/后门框密封条未装配到位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">P档检测：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">防冻液少/多、转向液少/多、洗涤液少/多、制动液少/多</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右前/后门护板未装配到位、间隙</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">四轮定位：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">通风盖板装配不到位/干涉</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">主/副驾驶座椅卡滞</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">ESP/EPB/SRS：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">散热器支架未卡到位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右A柱上护板钣金外露/黑胶外露/标签外露</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">大灯：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">散热器进水管卡箍未卡到位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">行李箱脏/杂胶/少卡扣</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">侧滑：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">近光/远光/转向灯不亮</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右后门玻璃与钣金干涉</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">制动：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">燃油硬管松脱/卡夹漏装</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右C柱上护板与顶棚间隙</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">速度、声响：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">真空助力泵软管漏装/漆笔未画</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">背门密封条未装配到位/松脱</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">方向盘力矩复检：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">背门难关/大顶饰条间隙/开胶</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">空调温度：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">排放：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList alignRight">性能检验〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignRight">四门两盖〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">底盘：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">钥匙未匹配、打不着火</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">前机盖与左/右翼子板间隙/面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">智能钥匙遥控功能、机械钥匙/微动开关不工作</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右翼子板与前保间隙/面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignRight">路试〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左/右遮阳板异响、底座间隙</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右前门与翼子板间隙/面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">车辆向左/右跑偏、方向盘不正</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">制动软/无刹车</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右后门与前门间隙/面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">刹车软、电喇叭异响/不响</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">安全带指示灯不亮、手刹指示灯常亮、ABS指示灯常亮</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右后门与后围间隙/面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">底盘、前座总成、机盖铰链处异响</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">空调不制冷</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">后保险杠与左/右后围间隙/面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">后排靠背总成、背门异响</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">油门回位不良</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右后组合灯与后围间隙/面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">防冻液少/多、转向液少/多、制动液少/多</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">背门与左/右后组合灯间隙/面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">时钟弹簧不对中、方向盘力矩未打</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">加油口盖异响/面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">安全气囊未解码</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList alignRight">左侧门+后座椅+整车灯〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左前/后门框密封条未装配到位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左前/后门护板未装配到位、间隙</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignCenter">VQ1关联检验</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignCenter">VQ2关联检验</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">后靠背锁环未装配到位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">AMT变速箱自学习</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左后门玻璃与钣金干涉</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左前/后门扶手装饰盖松动</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>

                <tr>
                    <td colspan="6" class="docCode" style="border:none; vertical-align:bottom; padding-top:3pt;">措施：不良···X；确认···<img src="/bms/img/confirm-x.png" style="position:relative; top:3pt;"></td>
                    <td colspan="6" class="docCode" style="text-align:right; border:none;">保存部门：总装长沙工厂生产部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;保存期限：15年</td>
                </tr>

            </table>
        </div>
        <div style="height:560pt;width:820pt;margin: 0px auto; page-break-before: always">
            <img src="/bms/checkPaperImage/checkPaperBack.jpg" width="" height="" style="display: block; margin:0 auto">
        </div>
    </div><!-- end of printF0 -->

    <div id="printM6" class="printable">
        <div style="height:560pt;width:820pt;margin: 0px auto;">
                <div class="logo"><img src="/bms/img/byd-auto.jpg" alt="" ></div>
            <table class="tableHead">
                <thead>
                    <tr style="height:20pt;">
                        <td colspan="13" class="docCode" style="text-align:right; border:none;">FM-MSP-18-D11F01-004-01A&nbsp;&nbsp;&nbsp;&nbsp;★一般★</td>
                    </tr>
                    <tr style="height:20pt">
                        <th></th>
                        <th style="width:209pt; vertical-align:top;">整车检验单</th>
                        <td style="width:42pt">车系</td>
                        <td class="printSeries" style="width:42pt"></td>
                        <td style="width:42pt">整车编号</td>
                        <td class="printSerialNumber" colspan="3" style="width:84pt"></td>
                        <td style="width:42pt">日期</td>
                        <td class="printDate" style="width:66pt"></td>
                        <td style="width:42pt">确认</td>
                        <td style="width:126pt"></td>
                    </tr>
                </thead>     
                <tbody>
                    <tr class="trInfo" style="height:20pt">
                        <td rowspan="2">VIN</td>
                        <td rowspan="2" class="barcode"><img class="vinBarcode" alt=""></td>
                        <td rowspan="2"><h2>VQ1</h2></td>
                        <td rowspan="2" colspan="5"></td>
                        <td>车型 / 配置</td>
                        <td colspan="3" class="printCarType"></td>
                    </tr>
                    <tr class="trInfo" style="height:20pt">
                        <td>颜色</td>
                        <td class="printColor"></td>
                        <td style="width:42pt">备注</td>
                        <td class="printRemark"></td>
                    </tr>
                    <tr class="trInfo" style="height:40pt">
                        <td>发动机<br>编号</td>
                        <td class="barcode"><img class="engineBarcode" alt=""></td>
                        <td><h2>VQ2</h2></td>
                        <td colspan="5"></td>
                        <td><h2>VQ3</h2></td>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tr class="trList">
                    <td colspan="6" class="tdList alignCenter">【VQ1_静态性能】</td>
                    <td colspan="3" class="tdList alignCenter">【VQ2_动态性能】</td>
                </tr>
                <tr class="trList">
                    <td class="tdList alignRight">发动机舱〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck">确认</td>
                    <td class="tdCheck">责任</td>
                    <td class="tdList alignRight">右侧门〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck">确认</td>
                    <td class="tdCheck">责任</td>
                    <td class="tdList alignRight">检测线〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck">确认</td>
                    <td class="tdCheck">责任</td>
                </tr>
                <tr class="trList">
                    <td class="tdList">VIN码不清晰/无拓印/与实车不一致</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右前/后门框密封条装配不到位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">P档检测：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">防冻液少、多&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;转向液少、多</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右滑动门玻璃下降滑动门不限位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">四轮定位：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">洗涤液少、多&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;制动液少、多</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">_____________________黑膜气泡/褶皱</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">ESP/EPB/SRS：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">机盖与左/右翼子板间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右前门与翼子板/右滑动门间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">大灯：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">前格栅与左/右大灯间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右滑动门与侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">侧滑：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">前保险杠划伤、掉漆</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右滑动门与侧围玻璃干涉异响</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">制动：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">通风盖板未装配到位、划伤</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">后安全带装反、座椅线束外露</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">速度、声响：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">近光/远光/转向灯不亮</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">方向盘力矩复检：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">空调温度：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">排放：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList alignRight">性能检验〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignRight">后背门〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">底盘：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">钥匙未匹配、打不着火</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">后背门玻璃与左/右侧围玻璃间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">智能/机械钥匙/微动开关不动作</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右后组合灯与倒车灯间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignRight">路试〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">_______________门玻璃/天窗升降异常、不工作、卡滞</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">后背门上护板/左护板/右护板/下护板间隙</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">车辆向左/右跑偏、方向盘不正</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">手刹与手刹护板、换挡操纵机构与换挡盖板干涉</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">后背门左/右外板与侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">刹车软、硬</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">组合仪表____________________故障灯异常</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">后排座椅卡滞</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">______________________________异响</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">门型护板与多媒体间隙、面差、未装配到位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右组合灯与侧围间隙、未装配到位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">组合仪表____________________故障灯异常</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左A柱上护板露钣金/露黑胶/未装配到位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">防冻液少/多、 转向液少/多</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左遮阳板/多功能显示屏/组合仪表错装</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">洗涤液少/多、 制动液少/多</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList alignRight">左侧门〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左滑动门与侧围玻璃干涉异响</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左前/后门框密封条未装配到位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignCenter">VQ1关联检验</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignCenter">VQ2关联检验</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">______________________________黑膜气泡/褶皱</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左滑动门玻璃下降、加油口盖开启、滑动门不限位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左前门与翼子板/滑动门间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">滑动门与侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">加油口盖与侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>

                <tr>
                    <td colspan="6" class="docCode" style="border:none; vertical-align:bottom; padding-top:3pt;">措施：不良···X；确认···<img src="/bms/img/confirm-x.png" style="position:relative; top:3pt;"></td>
                    <td colspan="6" class="docCode" style="text-align:right; border:none;">保存部门：总装长沙工厂生产部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;保存期限：15年</td>
                </tr>

            </table>
        </div>

        <div style="height:560pt;width:820pt;margin: 0px auto; page-break-before: always">
            <img src="/bms/checkPaperImage/checkPaperBack.jpg" width="" height="" style="display: block; margin:0 auto">
        </div>
    </div><!-- end of printM6-->

    <div id="print6B" class="printable">
        <div style="height:560pt;width:820pt;margin: 0px auto;">
                <div class="logo"><img src="/bms/img/byd-auto.jpg" alt="" ></div>
            <table class="tableHead">
                <thead>
                    <tr style="height:20pt;">
                        <td colspan="13" class="docCode" style="text-align:right; border:none;">FM-MSP-18-D11F01-004-01A&nbsp;&nbsp;&nbsp;&nbsp;★一般★</td>
                    </tr>
                    <tr style="height:20pt">
                        <th></th>
                        <th style="width:209pt; vertical-align:top;">整车检验单</th>
                        <td style="width:42pt">车系</td>
                        <td class="printSeries" style="width:42pt"></td>
                        <td style="width:42pt">整车编号</td>
                        <td class="printSerialNumber" colspan="3" style="width:84pt"></td>
                        <td style="width:42pt">日期</td>
                        <td class="printDate" style="width:66pt"></td>
                        <td style="width:42pt">确认</td>
                        <td style="width:126pt"></td>
                    </tr>
                </thead>     
                <tbody>
                    <tr class="trInfo" style="height:20pt">
                        <td rowspan="2">VIN</td>
                        <td rowspan="2" class="barcode"><img class="vinBarcode" alt=""></td>
                        <td rowspan="2"><h2>VQ1</h2></td>
                        <td rowspan="2" colspan="5"></td>
                        <td>车型 / 配置</td>
                        <td colspan="3" class="printCarType"></td>
                    </tr>
                    <tr class="trInfo" style="height:20pt">
                        <td>颜色</td>
                        <td class="printColor"></td>
                        <td style="width:42pt">备注</td>
                        <td class="printRemark"></td>
                    </tr>
                    <tr class="trInfo" style="height:40pt">
                        <td>发动机<br>编号</td>
                        <td class="barcode"><img class="engineBarcode" alt=""></td>
                        <td><h2>VQ2</h2></td>
                        <td colspan="5"></td>
                        <td><h2>VQ3</h2></td>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tr class="trList">
                    <td colspan="6" class="tdList alignCenter">【VQ1_静态性能】</td>
                    <td colspan="3" class="tdList alignCenter">【VQ2_动态性能】</td>
                </tr>
                <tr class="trList">
                    <td class="tdList alignRight">发动机舱〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck">确认</td>
                    <td class="tdCheck">责任</td>
                    <td class="tdList alignRight">右侧门〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck">确认</td>
                    <td class="tdCheck">责任</td>
                    <td class="tdList alignRight">检测线〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck">确认</td>
                    <td class="tdCheck">责任</td>
                </tr>
                <tr class="trList">
                    <td class="tdList">VIN码不清晰/无拓印/与实车不一致</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右前/后门框密封条未装配到位、亮饰条高点</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">P档检测：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">防冻液少/多、转向液少/多、洗涤液少/多、制动液少/多</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右A柱上护板未装配到位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">四轮定位：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">机盖与左/右翼子板间隙</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右翼子板与前门间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">ESP/EPB/SRS：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">前格栅与左/右大灯间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右前门与后门间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">大灯：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">通风盖板未装配到位、划伤</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右后门与侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">侧滑：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">机盖弹力小</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">制动：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">速度、声响：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">方向盘力矩复检：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">空调温度：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">排放：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList alignRight">性能检验〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignRight">行李箱盖〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">底盘：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">钥匙未匹配、打不着火</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">行李箱盖与左/右侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">智能/机械钥匙、微动开关不工作</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右尾灯与组合后灯间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignRight">路试〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">_________________门玻璃/天窗异响、卡滞、不工作</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">后保险杠与左/右侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">车辆向左/右跑偏、方向盘不正</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">____________________________________故障灯异常</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右组合后灯与侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">刹车软、电喇叭异响/不响</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">多媒体不工作、划伤</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">行李箱踏板与左/右毡垫间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">底盘、前座总成、机盖铰链处异响</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">仪表下护板与副仪表间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右尾灯与行李箱盖间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">防冻液少/多、转向液少/多、制动液少/多</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左/中/右风口面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">后档风玻璃与左/右侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">时钟弹簧不对中、方向盘力矩未打</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">安全气囊未解码</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">倒车影像/全景影像</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList alignRight">左侧门〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignCenter">VQ1关联检验</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">指南针/TV</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左前、后门框密封条未装配到位、亮饰条高点</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">云服务空调开启失败、提交失败</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左翼子板与前门间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">DCT变速箱自学习</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignCenter">VQ2关联检验</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左前门与后门间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左后门与侧围间隙面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>

                <tr>
                    <td colspan="6" class="docCode" style="border:none; vertical-align:bottom; padding-top:3pt;">措施：不良···X；确认···<img src="/bms/img/confirm-x.png" style="position:relative; top:3pt;"></td>
                    <td colspan="6" class="docCode" style="text-align:right; border:none;">保存部门：总装长沙工厂生产部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;保存期限：15年</td>
                </tr>

            </table>
        </div>

        <div style="height:560pt;width:820pt;margin: 0px auto; page-break-before: always">
            <img src="/bms/checkPaperImage/checkPaperBack.jpg" width="" height="" style="display: block; margin:0 auto">
        </div>
    </div>

    <div id="printG6" class="printable">
        <div style="height:560pt;width:820pt;margin: 0px auto;">
                <div class="logo"><img src="/bms/img/byd-auto.jpg" alt="" ></div>
            <table class="tableHead">
                <thead>
                    <tr style="height:20pt;">
                        <td colspan="13" class="docCode" style="text-align:right; border:none;">FM-MSP-18-D11F01-004-01A&nbsp;&nbsp;&nbsp;&nbsp;★一般★</td>
                    </tr>
                    <tr style="height:20pt">
                        <th></th>
                        <th style="width:209pt; vertical-align:top;">整车检验单</th>
                        <td style="width:42pt">车系</td>
                        <td class="printSeries" style="width:42pt"></td>
                        <td style="width:42pt">整车编号</td>
                        <td class="printSerialNumber" colspan="3" style="width:84pt"></td>
                        <td style="width:42pt">日期</td>
                        <td class="printDate" style="width:66pt"></td>
                        <td style="width:42pt">确认</td>
                        <td style="width:126pt"></td>
                    </tr>
                </thead>     
                <tbody>
                    <tr class="trInfo" style="height:20pt">
                        <td rowspan="2">VIN</td>
                        <td rowspan="2" class="barcode"><img class="vinBarcode" alt=""></td>
                        <td rowspan="2"><h2>VQ1</h2></td>
                        <td rowspan="2" colspan="5"></td>
                        <td>车型 / 配置</td>
                        <td colspan="3" class="printCarType"></td>
                    </tr>
                    <tr class="trInfo" style="height:20pt">
                        <td>颜色</td>
                        <td class="printColor"></td>
                        <td style="width:42pt">备注</td>
                        <td class="printRemark"></td>
                    </tr>
                    <tr class="trInfo" style="height:40pt">
                        <td>发动机<br>编号</td>
                        <td class="barcode"><img class="engineBarcode" alt=""></td>
                        <td><h2>VQ2</h2></td>
                        <td colspan="5"></td>
                        <td><h2>VQ3</h2></td>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tr class="trList">
                    <td colspan="6" class="tdList alignCenter">【VQ1_静态性能】</td>
                    <td colspan="3" class="tdList alignCenter">【VQ2_动态性能】</td>
                </tr>
                <tr class="trList">
                    <td class="tdList alignRight">发动机舱〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck">确认</td>
                    <td class="tdCheck">责任</td>
                    <td class="tdList alignRight">右侧门〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck">确认</td>
                    <td class="tdCheck">责任</td>
                    <td class="tdList alignRight">检测线〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck">确认</td>
                    <td class="tdCheck">责任</td>
                </tr>
                <tr class="trList">
                    <td class="tdList">VIN码不清晰/无拓印/与实车不一致</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右前/后门框密封条未装配到位、亮饰条高点</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">P档检测：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">防冻液少/多、转向液少/多、洗涤液少/多、制动液少/多</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右A柱上护板未装配到位</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">四轮定位：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">机盖与左/右翼子板间隙</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右翼子板与前门间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">ESP/EPB/SRS：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">前格栅与左/右大灯间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右前门与后门间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">大灯：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">通风盖板未装配到位、划伤</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">右后门与侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">侧滑：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">机盖弹力小</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">制动：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">速度、声响：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">方向盘力矩复检：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">空调温度：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">排放：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList alignRight">性能检验〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignRight">行李箱盖〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">底盘：</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">钥匙未匹配、打不着火</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">行李箱盖与左/右侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">智能/机械钥匙、微动开关不工作</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右尾灯与组合后灯间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignRight">路试〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">_________________门玻璃/天窗异响、卡滞、不工作</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">后保险杠与左/右侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">车辆向左/右跑偏、方向盘不正</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">____________________________________故障灯异常</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右组合后灯与侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">刹车软、电喇叭异响/不响</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">多媒体不工作、划伤</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">行李箱踏板与左/右毡垫间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">底盘、前座总成、机盖铰链处异响</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">仪表下护板与副仪表间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">左/右尾灯与行李箱盖间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">防冻液少/多、转向液少/多、制动液少/多</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左/中/右风口面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">后档风玻璃与左/右侧围间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">时钟弹簧不对中、方向盘力矩未打</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">安全气囊未解码</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">倒车影像/全景影像</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList alignRight">左侧门〖<?php for($i=0;$i<16;$i++){echo '&nbsp;';} ?>〗</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignCenter">VQ1关联检验</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">指南针/TV</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左前、后门框密封条未装配到位、亮饰条高点</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">云服务空调开启失败、提交失败</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左翼子板与前门间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList">DCT变速箱自学习</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList alignCenter">VQ2关联检验</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左前门与后门间隙、面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList">左后门与侧围间隙面差</td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>
                <tr class="trList">
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                    <td class="tdList"></td>
                    <td class="tdCheck"></td>
                    <td class="tdCheck"></td>
                </tr>

                <tr>
                    <td colspan="6" class="docCode" style="border:none; vertical-align:bottom; padding-top:3pt;">措施：不良···X；确认···<img src="/bms/img/confirm-x.png" style="position:relative; top:3pt;"></td>
                    <td colspan="6" class="docCode" style="text-align:right; border:none;">保存部门：总装长沙工厂生产部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;保存期限：15年</td>
                </tr>

            </table>
        </div>

        <div style="height:560pt;width:820pt;margin: 0px auto; page-break-before: always">
            <img src="/bms/checkPaperImage/checkPaperBack.jpg" width="" height="" style="display: block; margin:0 auto">
        </div>
    </div>
	</body>
</html>
