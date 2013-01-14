<div id="bmsHead" class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">BMS</a>
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
							<li><a href="#">仓储物流过程1</a></li>
							<li><a href="#">仓储物流过程2</a></li>
							<li><a href="#">仓储物流过程3</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">新车型<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#">新车型过程1</a></li>
							<li><a href="#">新车型过程2</a></li>
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
          			<li><a href="#"><i class="icon-envelope"></i>&nbsp;0</a></li>
          			<li class="dropdown">
            			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i>&nbsp;<?php echo Yii::app()->user->display_name; ?> <b class="caret"></b></a>
            			<ul class="dropdown-menu">
              				<li><a href="#">个人中心</a></li>
              				<li><a href="/bms/site/logout">注销</a></li>
            			</ul>
         			 </li>
        		</ul>			
			</div>
		</div>	
	</div>
</div>
<a id="toggle-top" href="">
	<i id="icon-top" class="icon-chevron-up"></i>	
</a>