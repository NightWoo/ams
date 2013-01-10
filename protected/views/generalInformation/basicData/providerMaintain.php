<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>供应商名录</title>
	<!-- Le styles -->
	<link href="../../../css/bootstrap.css" rel="stylesheet">
    <link href="../../../css/generalInformation/basicData/provider.css" rel="stylesheet">
	<!-- Le script -->
	<script type="text/javascript" src="../../../js/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="../../../js/dropdownmenu.js"></script>
</head>
<body>
		<div class="navbar navbar-fixed-top"><!-- 页眉 -->
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="#">BMS.Ver0.1</a>
					<div class="nav-collapse">
						<ul class="nav">
							<li ><a href="#">首页</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" >管理体系<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="http://www.baidu.com">体系概况</a></li>
									<li><a href="zz_nodeselect.php">管理考核与评价</a></li>
									<li><a href="#">体系审核</a></li>
								</ul>
							</li>
							<li class="dropdown active">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">生产执行<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#">总装</a></li>
									<li><a href="#">涂装</a></li>
									<li><a href="#">焊装</a></li>
									<li><a href="#">冲压</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">仓储物流<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#">预留</a></li>
									<li><a href="#">预留</a></li>
									<li><a href="#">预留</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">新车型<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#">XXX</a></li>
									<li><a href="#">YYY</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">质量管理<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#">质量工程</a></li>
									<li><a href="#">IQS售后质量</a></li>
									<li><a href="#">DRR内部质量</a></li>
									<li><a href="#">SQE零部件质量</a></li>
									<li><a href="#">CPA产品审核</a></li>
									<li><a href="#">LPA过程审核</a></li>
									<li><a href="#">Lesson Learned</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">资源管理<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#">人力资源管理</a></li>
									<li><a href="#">文件记录管理</a></li>
									<li><a href="#">工具量具管理</a></li>
									<li><a href="#">培训管理</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">综合信息<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#">情报中心</a></li>
									<li><a href="#">基础数据库</a></li>
									<li><a href="#">维护与帮助</a></li>
								</ul>
							</li>
							
						</ul>
                		<ul class="nav pull-right">
                  			<li><a href="#"><i class="icon-envelope"></i>（0）</a></li>
                  			<li class="dropdown">
                    			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i>&nbsp;吴俊 <b class="caret"></b></a>
                    			<ul class="dropdown-menu">
                      				<li><a href="#">个人中心</a></li>
                      				<li><a href="#">注销</a></li>
                    			</ul>
                 			 </li>
                		</ul>			
					</div>
				</div>	
			</div>
		</div><!-- end of 页眉 -->
		
		<div class="offhead">
    	<div class="span2 sidebar" ><!-- 侧边栏 -->
        	<ul class="nav nav-list">
            	<li class="nav-header">数据录入</li>
                	<li class="active"><a href="#">录入点选择</a></li>
                <li class="nav-header">生产监控</li>
                	<li><a href="#">监控点选择</a></li>
                <li class="nav-header">数据查询</li>
                	<li><a href="#">车辆查询</a></li>
                    <li><a href="#">故障查询</a></li>
                    <li><a href="#">节点查询</a></li>
                    <li><a href="#">零部件查询</a></li>
                <li class="nav-header">异常操作</li>
                	<li><a href="#">配置单补打印</a></li>
                    <li><a href="#">检验跟单补打印</a></li>
                    <li><a href="#">追溯零部件换件</a></li>
                <li class="nav-header">维护与帮助</li>
                	<li><a href="#">配置维护</a></li>
                	<li><a href="#">常见故障件维护</a></li>
                    <li><a href="#">操作手册.总装</a></li>          
            </ul>        
        </div><!-- end of 侧边栏 -->
     
        <div class="offset2"><!-- 页体 -->
            <div><!-- breadcrumb -->
            	<ul class="breadcrumb">
            		<li><a href="#">综合信息</a><span class="divider">&gt;</span></li>
                	<li><a href="#">基础数据库</a><span class="divider">&gt;</span></li>
                	<li class="active">供应商名录</li>            
            	</ul>
            </div><!-- end of breadcrumb -->
            
   	   		<div><!-- 主体 -->
				<div>
           	  		<div class="well form-search">
           	  			<form id="formProvider">
							<table>
								<tr>
									<td><label>供应商代码</label></td>
									<td><label>供应商名称</label></td>
									<td></td>
								</tr>
								<tr>
									<td><input id="inputCode" type="text" class="span3" placeholder="供应商代码"></td>
									<td><input id="inputName" type="text" class="span3" placeholder="供应商名称"></td>
									<td>
										<button id="btnQuery" type="reset" class="btn btn-primary">查询</button>                       	
                       					<button id="btnAdd" type="reset" class="btn">新增</button>
									</td>
								</tr>
							</table>
                    	</form>                    
                    	<div>
                    		<table id="tableProvider" class="table table-condensed">
                            	<thead>
                                	<tr>
                                    	<th class="span2">供应商代码</th>
                                        <th class="span4">供应商名称</th>
                                        <th class="span3">简称</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<tr>
                                    	<td>102475</td>
                                        <td>重庆长融机械有限责任公司</td>
                                        <td>重庆长融</td>
                                        <td><button class="btn-link">编辑</button>&nbsp;&brvbar;&nbsp;<button class="btn-link">删除</button></td>
                                    </tr>
                                    <tr>
                                    	<td>111307</td>
                                        <td>浙江诸暨万宝机械有限公司</td>
                                        <td>浙江诸暨万宝</td>
                                        <td><button class="btn-link">编辑</button>&nbsp;&brvbar;&nbsp;<button class="btn-link">删除</button></td>
                                    </tr>
									<tr>
                                    	<td>106399</td>
                                        <td>三菱自动车工业株式会社</td>
                                        <td>日本JATCO</td>
                                        <td><button class="btn-link">编辑</button>&nbsp;&brvbar;&nbsp;<button class="btn-link">删除</button></td>
                                    </tr>
                                    <tr>
                                    	<td>01041</td>
                                        <td>第十四事业部电动总成工厂</td>
                                        <td>十四部电动总成厂</td>
                                        <td><button class="btn-link">编辑</button>&nbsp;&brvbar;&nbsp;<button class="btn-link">删除</button></td>
                                    </tr>
                                </tbody>
                            </table>
							<div class="pagination">
								<ul>
									<li class="disable"><a href="#"><span>&laquo;</span></a></li>
									<li class="active"><a href="#"><span>1</span></a></li>
									<li><a href="#"><span>&raquo;</span></a></li>
								</ul>
							</div>                         
                    	</div>                   	
                    </div>                                          
                </div>
                <!--<div id="info" class="alert alert-success">LGXC16DGXC1234566已提交，请输入下一辆车VIN</div> -->
		  	</div><!-- end of 主体 -->
        </div><!-- end of 页体 -->
       	</div><!-- offhead -->
  	
</body>
</html>