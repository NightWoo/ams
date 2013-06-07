<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>车辆查询.总装</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/query/CarQuery.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">		
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
    	<script type="text/javascript" src="/bms/js/execution/assembly/query/carQuery.js"></script>
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
                    <legend>车辆查询
                    </legend>
                </div>
                <form class="well form-search">
                    <table>
                        <tr>
                            <td>车系</td>
                            <td>整车编号</td>
                            <td>VIN</td>
                            <td>节点</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <select name="" id="selectSeries" class="input-small">
                                    <option value="" selected>不限</option>
                                    <option value="F0">F0</option>
                                    <option value="M6">M6</option>
                                    <option value="6B">思锐</option>
                                </select>
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
                                    <option value="ROAD_TEST_FINISH">VQ2动态.路试结束</option>
                                    <option value="VQ2">VQ2动态.淋雨</option>
                                    <option value="VQ3">VQ3外观</option>
                                </select>
                            </td>
                            <td>
        						<input type="button" class="btn btn-primary" id ="btnQuery" value ="查询"></input>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div id='carTag' class="help-inline">
                                    <span class="label label-info" rel="tooltip" title="车系" id="series"></span>
                                    <span class="label label-info" rel="tooltip" title="流水号" id="serialNumber"></span>
                                    <span class="label label-info" rel="tooltip" title="Vin号" id="vin"></span>
                                    <span class="label label-info" rel="tooltip" title="车型" id="type"></span>
                                    <span class="label label-info" rel="tooltip" title="配置" id="configName"></span>
                                    <span class="label label-info" rel="tooltip" title="颜色" id="color"></span>
                                    <span class="label label-info" rel="tooltip" title="车辆区域" id="statusInfo"></span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
                <table id="resultTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>节点</th>
                            <th>故障/退库记录</th>
                            <th>故障状态</th>
                            <th>录入人员</th>
                            <th>录入时间</th>
                            <th>确认时间</th>
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
