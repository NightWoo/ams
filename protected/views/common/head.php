<link rel="stylesheet" href="/bms/css/font-awesome.min.css">
<div id="divHead">
<div class="navbar navbar-fixed-top" id="bmsHead">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="/bms/site">AMS</a>
			<div class="nav-collapse">
				<ul class="nav">
					<li id="headManagementSystemLi">
						<a href="/bms/ManagementSystem/home?chapter=0" data-toggle="tooltip" data-placement="bottom" title="体系"><i class="icon-sitemap"></i>&nbsp;体系</a>
					</li>
					<li id="headTechnologyLi">
						<a href="" data-toggle="tooltip" data-placement="bottom" title="技术"><i class="icon-cogs"></i>&nbsp;技术</a>
					</li>
					<li id="headAssemblyLi">
						<a href="/bms/execution" data-toggle="tooltip" data-placement="bottom" title="生产"><i class="icon-wrench"></i>&nbsp;生产</a>
					</li>
					<li class="divider-vertical"></li>
					<li id="headEfficiencyLi">
						<a href="/bms/site/efficiencyPannelIndex" data-toggle="tooltip" data-placement="bottom" title="效率"><i class="icon-dashboard"></i>&nbsp;效率</a>
					</li>
					<li id="headQualityLi">
						<a href="/bms/execution/query?type=NodeQuery"  data-toggle="tooltip" data-placement="bottom" title="质量"><i class="icon-thumbs-up-alt"></i>&nbsp;质量</a>
					</li>
					<li>
						<a href="#" data-toggle="tooltip" data-placement="bottom" title="现场"><i class="icon-check"></i>&nbsp;现场</a>
					</li>
					<li id="headCostLi">
						<a href="/bms/managementSystem/workSummaryCost" data-toggle="tooltip" data-placement="bottom" title="成本"><i class="icon-money"></i>&nbsp;成本</a>
					</li>
					<li id="headManpowerLi">
						<a href="/bms/managementSystem/workSummaryManpower" data-toggle="tooltip" data-placement="bottom" title="人事"><i class="icon-group"></i>&nbsp;人事</a>
					</li>
					<li class="divider-vertical"></li>
					<li id="headGeneralInformationLi">
						<a href="/bms/generalInformation" data-toggle="tooltip" data-placement="bottom" title="数据"><i class="icon-list-alt"></i>&nbsp;数据</a>
					</li>
				</ul>
        		<ul class="nav pull-right">
          			<li>
            			<a href="/bms/generalInformation/accountMaintain" data-toggle="tooltip" data-placement="bottom" title="账户管理"><i class="icon-user"></i>&nbsp;<?php echo Yii::app()->user->display_name;?></a>
         			 </li>
         			 <li>
            			<a href="/bms/site/logout" rel="tooltip" data-toggle="tooltip" data-placement="bottom" title="注销"><i class="icon-signout"></i></a>
         			 </li>
        		</ul>			
			</div>
		</div>	
	</div>
</div>
<div id="toggle-top" href="">
	<div id="icon-top-container">
		<i id="icon-top" class="icon-chevron-up"></i>
	</div>
</div>
</div>
<div id="divFoot">
<div class="navbar navbar-fixed-bottom navbar-inverse" id="bmsFoot">
	<div class="navbar-inner" style="min-height: 30px">
		<div class="container">
			<a class="brand" href=""><i class="icon-search"></i></a>
			<div class="nav-collapse">
				<ul class="nav">
					<!-- <li ><a href="/bms/site">首页</a></li> -->
					<li id=""><a href="/bms/execution/query?type=CarQuery">车辆</a></li>
					<li id=""><a href="/bms/execution/query?type=ComponentQuery">零部件</a></li>
					<li id=""><a href="/bms/execution/query?type=ManufactureQuery">生产</a></li>
					<li id=""><a href="/bms/execution/query?type=NodeQuery">质量</a></li>
					<li id=""><a href="/bms/execution/query?type=BalanceQuery">结存</a></li>
					<li id=""><a href="/bms/execution/query?type=OrderCarQuery">发车</a></li>
					<!-- <li id=""><a href="/bms/execution/query?type=WarehouseQuery">成品库</a></li> -->
				</ul>
			</div>
		</div>	
	</div>
</div>
</div>