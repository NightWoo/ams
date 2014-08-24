<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>车辆查询</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">		
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/rjs/lib/jsrender.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
    	<script type="text/javascript" src="/bms/js/execution/assembly/query/carQuery.js"></script>

        <style type="text/css">
            #sparesDetail {
                font-size: 12px;
            }
            .well {
                margin-bottom: 10px;    
            }
            #resultTable {
                font-size:12px; 
            }
            #sparesModal h4 {
                margin: 5px 0;
            }
        </style>
	</head>


	<body>
		<?php
			require_once(dirname(__FILE__)."/../../../common/head.php");
		?>
        <div class="offhead">
           <?php
            // require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
            ?>

        	<!-- Main体 -->	
    		
            <div id="bodyright" class="offset2">
                <div>
                    <legend>车辆查询
                    </legend>
                </div>
                <form class="well form-search">
                    <table>
                        <tr>
                            <td>线别</td>
                            <td>车系</td>
                            <td>整车编号</td>
                            <td>VIN</td>
                            <td>节点</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <select id="lineSelect" class="input-small lineSelect">
                                </select>
                                <script id="tmplLineSelect" type="text/x-jsrander">
                                    <option value='{{:line}}'>{{:line}}线</option>
                                </script>
                            </td>
                            <td>
                                <select name="" id="selectSeries" class="input-small">
                                    <option value="" selected>不限</option>
                                    <!-- <option value="F0">F0</option>
                                    <option value="M6">M6</option>
                                    <option value="6B">思锐</option> -->
                                </select>
                                <script id="tmplSeriesSelect" type="text/x-jsrander">
                                    <option value='{{:series}}'>{{:name}}</option>
                                </script>
                            </td>
                            <td>
                                <input type="text" class="input-small" placeholder="流水号" id="serialText" />
                            </td>
                            <td>
    							<input type="text" class="span3" placeholder="请扫描/输入VIN..." id="vinText" />
                            </td>
                            <td>
                                <select name="" id="selectNode" class="span3">
                                    <option value="">所有节点</option>
                                    <option value="VQ1">VQ1静态</option>
                                    <option value="CHECK_LINE">VQ2动态.检测线</option>
                                    <option value="ROAD_TEST_FINISH">VQ2动态.路试</option>
                                    <option value="VQ2">VQ2动态.淋雨</option>
                                    <option value="VQ3">VQ3外观</option>
                                </select>
                            </td>
                            <td>
        						<input type="button" class="btn btn-primary" id ="btnQuery" value ="查询"></input>
                            </td>
                        </tr>
                    </table>
                </form>
                <div id='carTag' class="help-inline" style="margin-bottom:10px;">
                    <span class="label label-info" rel="tooltip" title="车系" id="series"></span>
                    <span class="label label-info" rel="tooltip" title="流水号" id="serialNumber"></span>
                    <span class="label label-info" rel="tooltip" title="Vin号" id="vin"></span>
                    <span class="label label-info" rel="tooltip" title="车型" id="type"></span>
                    <span class="label label-info" rel="tooltip" title="配置" id="configName"></span>
                    <span class="label label-info" rel="tooltip" title="颜色" id="color"></span>
                    <span class="label label-info" rel="tooltip" title="车辆区域" id="statusInfo"></span>
                    <span class="label label-info" rel="tooltip" title="备注" id="remarkInfo"></span>
                </div>
                <table id="resultTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>节点</th>
                            <th>故障/节点信息</th>
                            <th>故障状态</th>
                            <th>录入人员</th>
                            <th>录入时间</th>
                            <th>确认时间</th>
                        </tr>
                    </thead>
                    <tbody>
                            
                    </tbody>
                </table>
                <div id="tabTestLine" class="tabbable tabs-left">
                    <ul class="nav nav-tabs">
                        <li><a href="#basic" data-toggle="tab">基本参数</a></li>
                        <li class="active"><a href="#toe" data-toggle="tab">前束</a></li>
                        <li><a href="#slide" data-toggle="tab">侧滑</a></li>
                        <li><a href="#turn" data-toggle="tab">转角</a></li>
                        <li><a href="#light" data-toggle="tab">前照灯</a></li>
                        <li><a href="#brake" data-toggle="tab">制动</a></li>
                        <li><a href="#speed" data-toggle="tab">速度表</a></li>
                        <li><a href="#gas" data-toggle="tab">尾气</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="basic" class="tab-pane">
                            <table id="tableBasic" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>燃料种类</td>
                                        <td class="Fuel">-</td>
                                    </tr>
                                    <tr>
                                        <td>发动机号 </td>
                                        <td><span class="engine_type">-</span><span class="engine_code">-</span></td>
                                    </tr>
                                    <tr>
                                        <td>外廓尺寸（长 × 宽 × 高）</td>
                                        <td><span class="Length">-</span>mm × <span class="Width">-</span>mm × <span class="Height">-</span>mm</td>
                                    </tr>
                                    <tr>
                                        <td>轴数</td>
                                        <td class="Axles_No">2</td>
                                    </tr>
                                    <tr>
                                        <td>轴距</td>
                                        <td><span class="WheelBase">-</span>mm</td>
                                    </tr>
                                    <tr>
                                        <td>轮胎数</td>
                                        <td class="Tires_No">-</td>
                                    </tr>
                                    <tr>
                                        <td>轮距（前 / 后）</td>
                                        <td><span class="Track_F">-</span>mm / <span class="Track_R">-</span>mm</td>
                                    </tr>
                                    <tr>
                                        <td>驱动形式</td>
                                        <td class="Drive_type">前置前驱</td>
                                    </tr>
                                    <tr>
                                        <td>最大设计质量</td>
                                        <td colspan="3"><span class="Mass_Max">-</span></td>
                                    </tr>
                                    <tr>
                                        <td>乘坐人数</td>
                                        <td class="PersonLimit">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="toe" class="tab-pane active">
                            <table id="tableToe" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>左</td>
                                        <td>右</td>
                                        <td>总</td>
                                        <td>评价</td>
                                    </tr>
                                    <tr>
                                        <td>前轮</td>
                                        <td class="ToeLeft_F">-</td>
                                        <td class="ToeRight_F">-</td>
                                        <td class="ToeTotal_F">-</td>
                                        <td class="ToeFlag_F">-</td>
                                    </tr>
                                    <tr>
                                        <td>后轮</td>
                                        <td class="ToeLeft_R">-</td>
                                        <td class="ToeRight_R">-</td>
                                        <td class="ToeTotal_R">-</td>
                                        <td class="ToeFlag_R">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="slide" class="tab-pane">
                            <table id="tableSlide" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>测值（m/km）</td>
                                        <td>评价</td>
                                    </tr>
                                    <tr>
                                        <td class="Slide">0.28</td>
                                        <td class="Slide_Flag">T</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="turn" class="tab-pane">
                            <table id="tableTurn" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>左转角</td>
                                        <td>右转角</td>
                                        <td>评价</td>
                                    </tr>
                                    <tr>
                                        <td>左轮</td>
                                        <td class="Left_Turn_Left">--</td>
                                        <td class="Right_Turn_Left">--</td>
                                        <td class="Angle_Flag" rowspan="2">T</td>
                                    </tr>
                                    <tr>
                                        <td>右轮</td>
                                        <td class="Left_Turn_Right">--</td>
                                        <td class="Right_Turn_Right">--</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="light" class="tab-pane">
                            <table id="tableLight" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td rowspan="2"></td>
                                        <td colspan="2">发光强度（cd）</td>
                                        <td colspan="2">上下偏角（cm/10m）</td>
                                        <td colspan="2">左右偏角（cm/10m）</td>
                                        <td colspan="2">照射高度（cm）</td>
                                        <td rowspan="2">评价</td>
                                    </tr>
                                    <tr>
                                        <td>远光</td>
                                        <td>近光</td>
                                        <td>远光</td>
                                        <td>近光</td>
                                        <td>远光</td>
                                        <td>近光</td>
                                        <td>远光</td>
                                        <td>近光</td>
                                    </tr>
                                    <tr>
                                        <td>左灯</td>
                                        <td class="LM_Inten">-</td>
                                        <td class="LL_Inten">-</td>
                                        <td class="LM_UDAngle">-</td>
                                        <td class="LL_UDAngle">-</td>
                                        <td class="LM_LRAngle">-</td>
                                        <td class="LL_LRAngle">-</td>
                                        <td class="LM_Height">-</td>
                                        <td class="LL_Height">-</td>
                                        <td class="Light_Flag_L">-</td>
                                    </tr>
                                    <tr>
                                        <td>左灯</td>
                                        <td class="RM_Inten">-</td>
                                        <td class="RL_Inten">-</td>
                                        <td class="RM_UDAngle">-</td>
                                        <td class="RL_UDAngle">-</td>
                                        <td class="RM_LRAngle">-</td>
                                        <td class="RL_LRAngle">-</td>
                                        <td class="RM_Height">-</td>
                                        <td class="RL_Height">-</td>
                                        <td class="Light_Flag_R">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="brake" class="tab-pane">
                            <table id="tableBrake" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>轴荷（×10N）</td>
                                        <td>左（×10N）</td>
                                        <td>右（×10N）</td>
                                        <td>和（%）</td>
                                        <td>差（%）</td>
                                        <td>左阻滞（%）</td>
                                        <td>右阻滞（%）</td>
                                        <td>评价</td>
                                    </tr>
                                    <tr>
                                        <td>前轴</td>
                                        <td class="AxleWeight_F">-</td>
                                        <td class="Brake_FL">-</td>
                                        <td class="Brake_FR">-</td>
                                        <td class="BrakeSumPer_F">-</td>
                                        <td class="BrakeDiffPer_F">-</td>
                                        <td class="BrakeResistance_FL">-</td>
                                        <td class="BrakeResistance_FR">-</td>
                                        <td class="BrakeFlag_F">-</td>
                                    </tr>
                                    <tr>
                                        <td>后轴</td>
                                        <td class="AxleWeight_R">-</td>
                                        <td class="Brake_RL">-</td>
                                        <td class="Brake_RR">-</td>
                                        <td class="BrakeSumPer_R">-</td>
                                        <td class="BrakeDiffPer_R">-</td>
                                        <td class="BrakeResistance_RL">-</td>
                                        <td class="BrakeResistance_RR">-</td>
                                        <td class="BrakeFlag_R">-</td>
                                    </tr>
                                    <tr>
                                        <td>整车</td>
                                        <td>制动力（×10N）</td>
                                        <td class="BrakeSum">-</td>
                                        <td>和（%）</td>
                                        <td class="BrakeSumPer" colspan="4">-</td>
                                        <td class="BrakeSum_Flag">-</td>
                                    </tr>
                                    <tr>
                                        <td>驻车</td>
                                        <td>制动力（×10N）</td>
                                        <td class="ParkSum">-</td>
                                        <td>和（%）</td>
                                        <td class="ParkSumPer" colspan="4">-</td>
                                        <td class="ParkSum_Flag">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="speed" class="tab-pane">
                            <table id="tableSpeed" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>标称值（km/h）</td>
                                        <td>实测值（km/h）</td>
                                        <td>误差（±）</td>
                                        <td>评价</td>
                                    </tr>
                                    <tr>
                                        <td class="">40</td>
                                        <td class="SpeedValue">-</td>
                                        <td class="SpeedInaccuracy">-</td>
                                        <td class="Speed_Flag">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="gas" class="tab-pane">
                            <table id="tableGas" class="table table-bordered">
                                <tbody>
                                     <tr>
                                        <td></td>
                                        <td>HC（ppm）</td>
                                        <td>CO（%）</td>
                                        <td>评价</td>
                                    </tr>
                                    <tr>
                                        <td>低怠速</td>
                                        <td class="GasHC_Low">-</td>
                                        <td class="GasCO_Low">-</td>
                                        <td class="GasLow_Flag">-</td>
                                    </tr>
                                    <tr>
                                        <td>高怠速</td>
                                        <td class="GasHC_High">-</td>
                                        <td class="GasCO_High">-</td>
                                        <td class="GasHigh_Flag">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- END MAIN -->
        </div>
<div class="modal" id="sparesModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;width:800px;margin-left:-400px;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4><span class="vinText"></span><span class="faultText"></span> </h4>
    </div>
    <div class="modal-body">
        <table class="table table-condensed table-hover" id="sparesDetail">
            <thead>
                <tr>
                    <th style="width:50px">连带损</th>
                    <th style="width:120px">零部件编号</th>
                    <th style="width:150px">零部件名称</th>
                    <th style="width:150px">零部件条码</th>
                    <th style="width:200px">供应商</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    </div>
</div>
	</body>
</html>
