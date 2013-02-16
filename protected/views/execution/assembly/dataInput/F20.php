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
				<div><ul class="breadcrumb"><!-- 面包屑 -->
					  <li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
						<li><a href="#">总装</a><span class="divider">&gt;</span></li>
						<li><a href="child?node=NodeSelect">数据录入</a><span class="divider">&gt;</span></li>
						<li class="active"><?php echo $nodeDisplayName;?></li>                
				</ul></div><!-- END 面包屑 -->
            			           
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
    	<div id="printF0" class="printable" style="height:595pt;width:841pt;">
            <div id="logo"><img src="/bms/img/byd-auto.jpg" alt="" ></div>
        <table id="tableHead">
            <thead>
                <tr style="height:14pt;">
                    <td colspan="14" class="docCode" style="text-align:right; border:none;">M-MSP-18-D11F01-004-01A&nbsp;&nbsp;&nbsp;&nbsp;★一般★</td>
                </tr>
                <tr style="height:28pt">
                    <th></th>
                    <th id="title" style="width:209pt; vertical-align:top;">F0整车检验单</th>
                    <td id="shift" style="width:42pt">白班&nbsp;&nbsp;□<br>夜班&nbsp;&nbsp;□</td>
                    <td style="width:42pt">整车编号</td>
                    <td id="printSerialNumber" colspan="2" style="width:84pt"></td>
                    <td id="" style="width:42pt">日期</td>
                    <td id="printDate" colspan="3" style="width:126pt"></td>
                    <td id="" style="width:42pt">确认</td>
                    <td id="" colspan="2" style="width:84pt"></td>
                </tr>
            </thead>     
            <tbody>
                <tr class="trInfo">
                    <td rowspan="2">VIN</td>
                    <td rowspan="2" class="barcode"><img id="vinBarcode" alt=""></td>
                    <td>发动机舱</td>
                    <td>性能检验</td>
                    <td style="font-size:7pt">左门+后座+整车灯</td>
                    <td style="font-size:7pt">右门+副驾+行李舱</td>
                    <td>四门两盖间隙面差</td>
                    <td><b>静态合格</b></td>
                    <td>车型</td>
                    <td colspan="4" width="126pt" id="printCarType">QCJ7100L（1.0排量舒适型)</td>
                </tr heitght="20pt">
                <tr class="trInfo">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>颜色</td>
                    <td id="printColor"></td>
                    <td>备注</td>
                    <td colspan="2" id="printMemo"></td>
                </tr>
                <tr class="trInfo">
                    <td rowspan="2">发动机<br>编号</td>
                    <td rowspan="2" class="barcode"><img id="engineBarcode" alt=""></td>
                    <td>前束+灯光+侧滑</td>
                    <td>制动+速度+排放</td>
                    <td>底盘检查</td>
                    <td>解码合格</td>
                    <td>淋雨</td>
                    <td>路试</td>
                    <td>性能修复</td>
                    <td><b>动态合格</b></td>
                    <td><b>关联检验</b></td>
                    <td><b>外观合格</b></td>
                    <td>入库合格</td>
                </tr>
                <tr class="trInfo">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <table>
            <tr class="trList">
                <td class="tdList alignCenter" style="width:215pt;">【下线静态性能】</td>
                <td class="tdCheck alignCenter">确认</td>
                <td class="tdCheck alignCenter">返修</td>
                <td class="tdCheck alignCenter">措施</td>
                <td class="tdList alignCenter">【下线静态性能】</td>
                <td class="tdCheck alignCenter">确认</td>
                <td class="tdCheck alignCenter">返修</td>
                <td class="tdCheck alignCenter">措施</td>
                <td class="tdList alignCenter">【检测线动态性能】</td>
                <td class="tdCheck alignCenter">确认</td>
                <td class="tdCheck alignCenter">返修</td>
                <td class="tdCheck alignCenter">措施</td>
            <tr>
            <tr class="trList">
                <td class="tdList alignCenter">发动机舱</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">右侧门+副座椅+行李舱</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">前束+灯光+侧滑</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">VIN码拓印字迹不清晰/无拓印/破损与实车不一致</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">左前/后门框密封条未装配到位</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">防冻液少/多、转向液少/多、洗涤液少/多、制动液少/多</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">左前/后门护板未装配到位、间隙</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">通风盖板装配不到位/干涉</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">副驾驶座椅卡滞</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">制动+速度+排放</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">散热器支架未卡到位</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">右A柱上护板钣金外露/黑胶外露/标签外露</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">前轴/后轴/阻滞力/总制动/驻车制动</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">散热器进水管卡箍未卡到位</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">行李箱脏/杂胶/少卡扣</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">方向盘力矩</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">近光/远光/转向灯不亮</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">右后门玻璃与钣金干涉</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">左/右C柱上护板与顶棚间隙</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">底盘检查</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList alignCenter">性能检验</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">背门密封条未装配到位/松脱</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">底盘变形/掉漆/漏底</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">钥匙未匹配、打不着火</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">背门难关/大顶饰条间隙/开胶</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">制动/燃油硬管与车身前端/后端钣金干涉</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">智能钥匙遥控功能失效、机械钥匙不工作、微动开关不工作</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">左/右遮阳板异响、底座间隙</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">四门两盖</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">路试</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">制动软/无刹车</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">前机盖与左/右翼子板间隙/面差</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">车辆向左/右跑偏、方向盘不正</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">安全带指示灯不亮/手刹指示灯常亮/ABS指示灯常亮</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">左/右翼子板与前保间隙/面差</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">刹车软、电喇叭异响/不响</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">空调不制冷、喷头失调、喇叭异响/不响</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">左/右前门与翼子板间隙/面差</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">底盘/前座总成/机盖机盖铰链处异响</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">雨刮与通风盖板干涉/刮幅大/刮幅小</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">左/右后门与前门间隙/面差</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">后排靠背总成/背门异响</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">收音机与下护板间隙、仪表前后护罩间隙</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">左/右后门与后围间隙/面差</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">左/右前门玻璃升降器不工作/升降不到位</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">后保险杠与左/右后围间隙/面差</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">左A柱上护板钣金外露/黑胶外露/标签外露</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">左/右后组合灯与后围间隙/面差</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">【关联检验】-动态</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">主座椅卡滞</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList">背门与左/右后组合灯间隙/面差</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList alignCenter">左侧门+后座椅+整车灯</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">【性能修复】</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">左前/后门框密封条未装配到位</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">左前/后门护板未装配到位、间隙</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">【关联检验】-修正</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">后靠背锁环未装配到位</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">左后门玻璃与钣金干涉</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList">左前/后门扶手装饰盖松动</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>

            <tr>
                <td colspan="6" class="docCode" style="border:none; vertical-align:bottom; padding-top:3pt;">措施：不良···X；返修···R；更换···G；确认···<img src="/bms/img/confirm-x.png" style="position:relative; top:3pt;">； 未录AMS系统···△</td>
                <td colspan="6" class="docCode" style="text-align:right; border:none;">保存部门：总装长沙工厂  保存期限：15年</td>
            <tr>

        </table>
    </div>

    <div id="printM6" class="printable" style="height:595pt;width:841pt;">
            <div id="logo"><img src="/bms/img/byd-auto.jpg" alt="" ></div>
        <table id="tableHead">
            <thead>
                <tr style="height:14pt;">
                    <td colspan="14" class="docCode" style="text-align:right; border:none;">M-MSP-18-D11F01-004-01A&nbsp;&nbsp;&nbsp;&nbsp;★一般★</td>
                </tr>
                <tr style="height:28pt">
                    <th></th>
                    <th id="title" style="width:209pt; vertical-align:top;">M6整车检验单</th>
                    <td id="shift" style="width:42pt">白班&nbsp;&nbsp;□<br>夜班&nbsp;&nbsp;□</td>
                    <td style="width:42pt">整车编号</td>
                    <td class="printSerialNumber" colspan="2" style="width:84pt"></td>
                    <td id="" style="width:42pt">日期</td>
                    <td class="printDate" colspan="3" style="width:126pt"></td>
                    <td id="" style="width:42pt">确认</td>
                    <td id="" colspan="2" style="width:84pt"></td>
                </tr>
            </thead>     
            <tbody>
                <tr class="trInfo">
                    <td rowspan="2">VIN</td>
                    <td rowspan="2" class="barcode"><img class="vinBarcode" alt=""></td>
                    <td>发动机舱</td>
                    <td>性能检验</td>
                    <td style="font-size:7pt">左门+后座+整车灯</td>
                    <td style="font-size:7pt">右门+副驾+行李舱</td>
                    <td>四门两盖间隙面差</td>
                    <td><b>静态合格</b></td>
                    <td>车型</td>
                    <td colspan="4" width="126pt" class="printCarType"></td>
                </tr heitght="20pt">
                <tr class="trInfo">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>颜色</td>
                    <td class="printColor"></td>
                    <td>备注</td>
                    <td colspan="2" class="printMemo"></td>
                </tr>
                <tr class="trInfo">
                    <td rowspan="2">发动机<br>编号</td>
                    <td rowspan="2" class="barcode"><img class="engineBarcode" alt=""></td>
                    <td>前束+灯光+侧滑</td>
                    <td>制动+速度+排放</td>
                    <td>底盘检查</td>
                    <td>解码合格</td>
                    <td>淋雨</td>
                    <td>路试</td>
                    <td>性能修复</td>
                    <td><b>动态合格</b></td>
                    <td><b>关联检验</b></td>
                    <td><b>外观合格</b></td>
                    <td>入库合格</td>
                </tr>
                <tr class="trInfo">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <table>
            <tr class="trList">
                <td class="tdList alignCenter" style="width:215pt;">【下线静态性能】</td>
                <td class="tdCheck alignCenter">确认</td>
                <td class="tdCheck alignCenter">返修</td>
                <td class="tdCheck alignCenter">措施</td>
                <td class="tdList alignCenter">【下线静态性能】</td>
                <td class="tdCheck alignCenter">确认</td>
                <td class="tdCheck alignCenter">返修</td>
                <td class="tdCheck alignCenter">措施</td>
                <td class="tdList alignCenter">【检测线动态性能】</td>
                <td class="tdCheck alignCenter">确认</td>
                <td class="tdCheck alignCenter">返修</td>
                <td class="tdCheck alignCenter">措施</td>
            <tr>
            <tr class="trList">
                <td class="tdList alignCenter">发动机舱</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">右侧门+副座椅+行李舱</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">前束+灯光+侧滑</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList alignCenter"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">【关联检验】-动态</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList alignCenter"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">【性能修复】</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList alignCenter">【关联检验】-修正</td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>
            <tr class="trList">
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdList"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
                <td class="tdCheck"></td>
            <tr>

            <tr>
                <td colspan="6" class="docCode" style="border:none; vertical-align:bottom; padding-top:3pt;">措施：不良···X；返修···R；更换···G；确认···<img src="/bms/img/confirm-x.png" style="position:relative; top:3pt;">； 未录AMS系统···△</td>
                <td colspan="6" class="docCode" style="text-align:right; border:none;">保存部门：总装长沙工厂  保存期限：15年</td>
            <tr>

        </table>
    </div>
	</body>
</html>
