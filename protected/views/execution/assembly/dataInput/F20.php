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
    	<div class="printable">
    		<table class="table-custom" style='border-collapse:collapse;width:841pt;heigh:595pt;'>
       <!--  <thead style="display:none;"> -->
        <thead height="0">
            <td width="50pt"></td>
            <td width="245pt"></td>
            <td width="41pt"></td>
            <td width="35pt"></td>
            <td width="49pt"></td>
            <td width="59pt"></td>
            <td width="65pt"></td>
            <td width="75pt"></td>
            <td width="45pt"></td>
            <td width="25pt"></td>
            <td width="44pt"></td>
            <td width="36pt"></td>
            <td width="55pt"></td>
            <td width="73pt"></td>
            <td width="61pt"></td>
            <td width="45pt"></td>
            <!-- <td width="43pt"></td> -->
            <td width="25pt"></td>
            <td width="19pt"></td>
            <td width="44pt"></td>
            <td width="38pt"></td>
            <td width="49pt"></td>
        </thead>
        <tbody>
            <tr height="17pt">
                <td colspan="5" rowspan="2" style="border:none;"><img src="/bms/img/byd-auto.jpg" alt="" style="height:25pt"></td>
                <td colspan="4" rowspan="2" style="text-align:right;border:none;font-size:18pt;font-weight:bold;">F0 整 车 检 验 单</td>
                <td colspan="7" style="text-align:center;border:none;"></td>
                
                <td colspan="5" style="text-align:center;border:none;">★一般★</td>
            </tr>
            <tr height="25pt">
                <td colspan="3">白班 □夜班 □</td>
                <td>整车编号</td>
                <td  id="printSerialNumber" style="text-align:center"></td>
                <td>日期</td>
                <td colspan="3" style="text-align:center" id="printDate"></td>
                <td>确认</td>
                <td colspan="2"></td>
            </tr>
            <tr height="21pt">
                <td rowspan="2" style="text-align:center">车架号</td>
                <td colspan="4" rowspan="2" style="text-align:center"><img id="vinBarcode"></td>
                <td style="text-align:center">发动机舱</td>
                <td style="text-align:center">性能检验</td>
                <td style="text-align:center">左侧门+后座椅+整车灯</td>
                <td colspan="2" style="text-align:center">右侧门+副座椅+行李舱 </td>
                <td colspan="2" style="text-align:center">四门两盖缝隙面差     </td>
                <td style="text-align:center">静态合格</td>
                <td style="text-align:center" >车型</td>
                <td colspan="8" style="text-align:center" id="printCarSeries"></td>
            </tr>
            <tr height="21pt">
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"> </td>
                <td colspan="2"> </td>
                <td></td>
                <td style="text-align:center" >颜色</td>
                <td id="printColor" style="text-align:center"></td>
                <td style="text-align:center">备注</td>
                <td id="printMemo" style="text-align:center" colspan="5"></td>
            </tr>
            <tr height="21pt">
                <td rowspan="2" style="text-align:center">发动机编号</td>
                <td colspan="4" rowspan="2" style="text-align:center"><img id="engineBarcode" alt=""></td>
                <td style="text-align:center">检测线</td>
                <td style="text-align:center">速度测试</td>
                <td style="text-align:center">底盘检查</td>
                <td colspan="2" style="text-align:center">解码合格     </td>
                <td colspan="2" style="text-align:center">淋雨       </td>
                <td style="text-align:center">路试</td>
                <td style="text-align:center">性能复检</td>
                <td style="text-align:center">动态合格</td>
                <!-- <td colspan="5" style="text-align:center">关联检验 | 外观合格  |  入库合格</td> -->
                  
                <td colspan="2" style="text-align:center">关联检验</td>
                <td colspan="2" style="text-align:center">外观合格</td>
                <td colspan="2" style="text-align:center">入库合格</td>
            </tr>
            <tr height="21pt">
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"> </td>
                <td colspan="2"> </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2" style="text-align:center"></td>
                <td colspan="2" style="text-align:center"></td>
                <td colspan="2" style="text-align:center"></td>
            </tr>
            <tr height="19pt">
                <td colspan="2" style="font-weight:bold;text-align:center">【下线静态性能】</td>
                <td style="text-align:center">返修人</td>
                <td style="text-align:center">确认</td>
                <td style="text-align:center">确认人</td>
                <td colspan="5" style="font-weight:bold;text-align:center">【下线静态性能】</td>
                <td style="text-align:center">返修人</td>
                <td style="text-align:center">确认</td>
                <td style="text-align:center">确认人</td>
                <td colspan="5" style="font-weight:bold;text-align:center">【下线静态性能】</td>
                <td style="text-align:center">返修人</td>
                <td style="text-align:center">确认</td>
                <td style="text-align:center">确认人</td>
            </tr>
            <tr height="19pt">
                <td colspan="2" style="font-weight:bold;text-align:center">发动机舱</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5" style="font-weight:bold;text-align:center">右侧门+副座椅+行李舱</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5" style="font-weight:bold;text-align:center">前束+侧滑+大灯+制动+速度+排放</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">VIN码拓印字迹不清晰/无拓印/破损/与实车不一致   </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">右前/后门框密封条装配不到位 </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">防冻液少/多、转向液少/多、洗涤液少/多、制动液少/多 </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">右前/后门护板未装配到位/缝大             </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">通风盖板装配不到位/干涉    </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">副座椅卡滞               </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">散热器支架未卡到位</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">右A柱上护板漏钣金/漏黑胶/漏标签               </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5" style="font-weight:bold;text-align:center">底盘检查            </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">散热器进水管卡箍未卡到位    </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">行李箱脏/杂胶/少卡扣             </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">底盘变形/掉漆/漏底          </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">近光/远光/转向灯不亮 </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">左后门玻璃与钣金干涉              </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">制动/燃油硬管与车身前端/后端钣金干涉         </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">左/右C柱上护板与顶篷缝隙               </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">背门密封条未装到位/松脱                </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">背门难关/  大顶饰条缝大/开胶                </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2" style="font-weight:bold;text-align:center">四门两盖    </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5" style="font-weight:bold;text-align:center">路试          </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">前机盖与左、右翼子板缝隙/面差</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">车辆向左/右跑偏     方向盘不正          </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">左、右翼子板与前保缝隙/面差</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">刹车软         电喇叭异响/不响            </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">左、右前门与翼子板缝隙/面差</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5" style="font-weight:bold;text-align:center">性能检验                </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">底盘/前座总成/机盖铰链处异响         </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">左、右后门与前门缝隙/面差</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">左、右遮阳板异响、底座缝大               </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">后排靠背总成/背门异响         </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">左、右后门与后围缝隙/面差</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">制动软/无刹车             </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">后保险杠与左、右后围缝隙/面差</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">安全带指示灯不亮/手刹指示灯常亮/ABS指示灯常亮               </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">左、右后组合灯与后围缝隙/面差</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">空调不制冷               </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">后背门与左、右后组合灯缝隙/面差</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">喷头失调/喇叭异响、不响                </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">雨刮与通风盖板干涉、刮幅大、刮幅小           </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5" style="font-weight:bold;text-align:center">【关联检验】-动态           </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">中控失灵                </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">CD机与下护板缝大/仪表前后护罩缝大              </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2" style="font-weight:bold;text-align:center">左侧门+后座椅+整车灯 </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">左、右前门玻璃升降器不工作/升降不到位             </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">左前/后门框密封条装配不到位  </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">左A柱上护板漏钣金/漏黑胶/漏标签               </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">左前/后门护板未装配到位/缝大</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5">主座椅卡滞               </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5" style="font-weight:bold;text-align:center">【关联检验】-漆面           </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">后靠背锁环未卡到位</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">左后门玻璃与钣金干涉</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2">左前/后门扶手装饰盖松动</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5" style="font-weight:bold;text-align:center">【静态性能复检】                </td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr height="19pt">
                <td colspan="21" style="font-weight:bold;">措施：不良…×；返修…R；更换…G；确认… <img src="/bms/img/confirm-x.png">;未录QMS系统…△</td>
            </tr>
            <tr height="19pt">
                <td colspan="8" style="border-right:none;">保存部门：总装工厂IPQC科   &nbsp;&nbsp;&nbsp;    保存期限：15年 </td>
                <td colspan="6" style="border-left:none;border-right:none;"></td>
                <td colspan="7" style="border-left:none;text-align:center;">FM-MSP-18-D11F01-004-01A</td>
            </tr>

        </table>
    	</div>

        
        
		

    	
	</body>
</html>
