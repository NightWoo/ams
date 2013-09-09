<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>故障责任编辑</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/other/FaultDutyEdit.css" rel="stylesheet">
		<link href="/bms/css/common.css" rel="stylesheet">		
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/common.js"></script>
    	<script type="text/javascript" src="/bms/js/execution/assembly/other/faultDutyEdit.js"></script>
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
                    <legend>故障责任编辑
                        <span class="pull-right">
                        <a href="/bms/execution/child?view=VQ1&node=VQ1"><i class="icon-link"></i>&nbsp;I线VQ1</a>
                        /
                        <a href="/bms/execution/child?view=VQ1&node=VQ1_2"><i class="icon-link"></i>&nbsp;II线VQ1</a>
                        /
                        <a href="/bms/execution/child?node=ROAD_TEST_FINISH&view=VQ2RoadTestFinished"><i class="icon-link"></i>&nbsp;VQ2路试</a>
                        /
                        <a href="/bms/execution/child?node=VQ2&view=VQ2LeakTest"></i>&nbsp;VQ2淋雨</a>
                        /
                        <a href="/bms/execution/child?node=VQ3&view=VQ3"><i class="icon-link"></i>&nbsp;VQ3</a>
                    </span>
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
                                    <option value="VQ1_ALL">VQ1静态</option>
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
                    <span class="label label-info" rel="tooltip" title="配置" id="cold"></span>
                    <span class="label label-info" rel="tooltip" title="颜色" id="color"></span>
                    <span class="label label-info" rel="tooltip" title="车辆区域" id="statusInfo"></span>
                </div>
                <table id="resultTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>节点</th>
                            <th>故障</th>
                            <th>故障状态</th>
                            <th>责任部门</th>
                            <th>录入时间</th>
                            <th>更新人员</th>
                        </tr>
                    </thead>
                    <tbody>
                            
                    </tbody>
                </table>
            </div><!-- END MAIN -->
        </div>     
<div class="modal" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>编辑</h3>
    </div>
    <div class="modal-body">
        <form id="" class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="">责任部门</label>
                <div class="controls">
                    <select name="" id="editDutyDepartment" class="input-medium">
                        
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <button class="btn btn-primary" id="btnEditConfirm">确认编辑</button>
    </div>
</div>
	</body>
</html>
