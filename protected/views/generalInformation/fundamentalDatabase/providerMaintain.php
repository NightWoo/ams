<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>T0节点</title>
    	<!-- Le styles -->
    	<link href="/bms/css/bootstrap.min.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
    	<script type="text/javascript" src="/bms/js/execution/assembly/dataInput/t0.js"></script>
	</head>


	<body>
        <?php
            require_once(dirname(__FILE__)."/../../../common/head/execution_head.php");
            require_once(dirname(__FILE__)."/../../../common/left/general_database_left.php");
        ?>

        <!-- Main体 -->
        <div class="offset2">
           <!-- 面包屑 -->
            <div ><ul class="breadcrumb">
                    <li><a href="#">综合信息</a><span class="divider">&gt;</span></li>
                    <li><a href="child?node=NodeSelect">基础数据库</a><span class="divider">&gt;</span></li>
                    <li class="active">供应商名录</li>                
            </ul></div>
            <!-- END 面包屑 -->
            
            <!-- 主体 -->
            <div >
                <form id="form" class="well form-inline">
                <div>
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;VIN 码：</label>
                    <input type="text" class="span3" placeholder="请输入..." id="vinText" />
                    <label>物料条码：</label>
                    <input type="text" class="span3" placeholder="请输入..." id="vinText" />
                    <label class="checkbox">
                        <input type="checkbox"> F0</input>
                    </label>
                    <label class="checkbox">
                        <input type="checkbox"> M6</input>
                    </label>
                </div>
               <div>
                
                <table id="planTable" class="table table-bordered">
                    <thead>
                        <tr>
                          <th>供应商代码</th>
                          <th>供应商名称</th>
                          <th>简称</th>
                          <th>编辑</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>F0</td>
                            <td>LGXC14AA9C1079470</td>
                            <td>底盘</td>
                            <td>异响</td>
                            <td>修复</td> 
                            <td>VQ2动态检验.路试</td>
                            <td>2012-07-11 14：23</td>
                            <td>吴俊</td>             
                        </tr>
                       <tr>
                            <td>2012-07-11 14：40</td>
                            <td>t0</td>
                            <td>-</td>
                            <td>-</td>
                            <td>陈承星</td>              
                        </tr>
                    </tbody>
                </table>
            </div><!-- end of 主体 -->
           
            

        </div>
        
        

        
    </body>
</html>
