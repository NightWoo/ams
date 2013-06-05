<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>库位整理</title>
        <!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet">
        <link href="/bms/css/common.css" rel="stylesheet">
		<link href="/bms/css/execution/assembly/other/WarehouseAdjust.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/execution/assembly/other/warehouseAdjust.js"></script>
        <script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
    </head>


    <body>
        <?php
            require_once(dirname(__FILE__)."/../../../common/head.php");
        ?>
        <div class="offhead">
           <?php
            require_once(dirname(__FILE__)."/../../../common/left/assembly_plan_left.php");
            ?>

            <!-- Main体 -->  
            
            <div id="bodyright" class="offset2">
                <!-- <div ><ul class="breadcrumb">
                        <li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
                        <li><a href="/bms/execution/home">总装</a><span class="divider">&gt;</span></li>
                        <li><a href="#">维护与帮助</a><span class="divider">&gt;</span></li>
                        <li class="active">订单维护</li>                
                </ul></div> -->
                <div>
                    <legend>库位整理
                        <!-- <span class="pull-right">
                            <i class="icon-link"></i>&nbsp;
                            <a href="/bms/execution/OutStandbyMaintain">发车道分配</a>
                        </span> -->
                    </legend>
                </div>
                <div><!-- 主体 -->
                <form id="form" class="well form-search">
                    <table>
                        <tr>
                            <td>库区</td>
                            <td>库道</td>
                            <td>车系</td>
                            <td>配置</td>
                            <td>颜色</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <select id="area" class="input-small">
                                    <option value=''>全部</option>
                                    <option value='A'>A</option>
                                    <option value='B'>B</option>
                                    <option value='C'>C</option>
                                    <option value='D'>D</option>
                                    <option value='E'>E</option>
                                    <option value='F'>F</option>
                                    <option value='G'>G</option>
                                    <option value='H'>27#厂房</option>
                                    <option value='X'>X</option>
                                </select>
                            </td>
                            <td>
                                <input id="row" type="text" class="input-small" placeholder="库道..." />
                            </td>
                            <td>
                                <select id="series" class="input-small">
                                    <option value=''>未选择</option>
                                    <option value='F0'>F0</option>
                                    <option value='M6'>M6</option>
                                    <option value='6B'>思锐</option>
                                </select>
                            </td>
                            <td>
                                <select name="" id="orderConfig" class="input-large">
                                    <option value="">未选择</option>
                                </select>
                            </td>
                            <td>
                                <select name="" id="color" class="input-small">
                                    <option value="">未选择</option>
                                </select>
                            </td>
                            <td>
                                <input type="button" class="btn btn-primary" id="btnQuery" value="查询" style="margin-left:2px;"></input>   
                            </td>
                        </tr>
                    </table>
                </form>
                
                <table id="tableResult" class="table table-condensed table-hover" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th style="width:50px">整理</th>
                            <th style="width:50px">库道</th>
                            <th style="width:50px">容量</th>
                            <!-- <th style="width:50px">数量</th> -->
                            <!-- <th style="width:50px">空位</th> -->
                            <th style="">不可用/实车数/空位数</th>
                            <th style="width:50px">车系 </th>
                            <th style="width:300px">配置</th>
                            <th style="width:60px">耐寒性</th>
                            <th style="">颜色</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
        </div><!-- offhead -->
<!-- new record -->
<div class="modal" id="newModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>新增</h3>
    </div>
    <div class="modal-body">
        <form id="newForm" class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="">*&nbsp;备车日期</label>
                <div class="controls">
                    <input id="newStandbyDate"  type="text" class="input-medium" placeholder="选择备车日期..."onClick="WdatePicker({el:'newStandbyDate',dateFmt:'yyyy-MM-dd'});"/>
                </div>
            </div>
			<div class="control-group">
                <label class="control-label" for="">*&nbsp;激活</label>
                <div class="controls">
                    <input id="newStatus" type="checkbox">
                </div>
            </div>
			<div class="control-group">
                <label class="control-label" for="">*&nbsp;车道</label>
                <div class="controls">
                    <select id="newLane"  name=""class="input-small">
                        <option value="" selected>未选择</option>
                        <?php 
                            for($i=1;$i<51;$i++){
                                $num = sprintf("%02d", $i);
                                $ret = "<option value=". $num .">$num</option>";
                                echo $ret;
                            }
                        ?>
                    </select>
                </div>
            </div> 
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <button class="btn btn-success" id="btnAddMore">继续新增</button>
        <button class="btn btn-primary" id="btnAddConfirm">确认新增</button>
    </div>
</div>

</body>
</html>
