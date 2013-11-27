<link rel="stylesheet" href="/bms/css/font-awesome.min.css">
<style type="text/css" media="screen">
	#headNav > li > a {
	    padding: 10px;
	}
</style>
<div id="divHead">
<div class="navbar navbar-fixed-top" id="bmsHead">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="/bms/site">AMS</a>
			<div class="nav-collapse">
				<ul id="headNav" class="nav">
					<li id="headManagementSystemLi">
						<a href="/bms/ManagementSystem/home?chapter=0" data-toggle="tooltip" data-placement="bottom" title="体系"><i class="fa fa-sitemap"></i>&nbsp;体系</a>
					</li>
					<li id="headTechnologyLi">
						<a href="" data-toggle="tooltip" data-placement="bottom" title="技术"><i class="fa fa-cogs"></i>&nbsp;技术</a>
					</li>
					<li id="headAssemblyLi">
						<a href="/bms/execution" data-toggle="tooltip" data-placement="bottom" title="生产"><i class="fa fa-wrench"></i>&nbsp;生产</a>
					</li>
					<li class="divider-vertical"></li>
					<li id="headEfficiencyLi">
						<a href="/bms/site/pannelIndex?pannel=efficiencyPannel" data-toggle="tooltip" data-placement="bottom" title="效率"><i class="fa fa-dashboard"></i>&nbsp;效率</a>
					</li>
					<li id="headQualityLi">
						<a href="/bms/execution/query?type=NodeQuery"  data-toggle="tooltip" data-placement="bottom" title="质量"><i class="fa fa-thumbs-up"></i>&nbsp;质量</a>
					</li>
					<li>
						<a href="#" data-toggle="tooltip" data-placement="bottom" title="现场"><i class="icon-map-marker"></i>&nbsp;现场</a>
					</li>
					<li id="headCostLi">
						<!-- <a href="/bms/managementSystem/workSummaryCost" data-toggle="tooltip" data-placement="bottom" title="成本"><i class="icon-money"></i>&nbsp;成本</a> -->
						<a href="/bms/site/pannelIndex?pannel=costPannel" data-toggle="tooltip" data-placement="bottom" title="成本"><i class="fa fa-money"></i>&nbsp;成本</a>
					</li>
					<li id="headManpowerLi">
						<a href="/bms/managementSystem/workSummaryManpower" data-toggle="tooltip" data-placement="bottom" title="人事"><i class="fa fa-group"></i>&nbsp;人事</a>
					</li>
					<li class="divider-vertical"></li>
					<li id="headMonitoringLi">
						<a href="/bms/execution/monitoringIndex" rel="tooltip" data-toggle="tooltip" data-placement="bottom" title="监控"><i class="fa fa-desktop"></i>&nbsp;</a>
					</li>
					<li id="headGeneralInformationLi">
						<a href="/bms/generalInformation" rel="tooltip" data-toggle="tooltip" data-placement="bottom" title="数据"><i class="fa fa-list-alt"></i>&nbsp;</a>
					</li>
				</ul>
        		<ul class="nav pull-right">
          			<li>
            			<a href="/bms/generalInformation/accountMaintain" rel="tooltip" data-toggle="tooltip" data-placement="bottom" title="账户管理"><i class="fa fa-user"></i>&nbsp;<?php echo Yii::app()->user->display_name;?></a>
         			 </li>
         			 <li>
            			<a href="/bms/site/logout" rel="tooltip" data-toggle="tooltip" data-placement="bottom" title="注销"><i class="fa fa-sign-out"></i></a>
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
			<a class="brand" href=""><i class="fa fa-search"></i></a>
			<div class="nav-collapse">
				<ul class="nav">
					<!-- <li ><a href="/bms/site">首页</a></li> -->
					<li id=""><a href="/bms/execution/query?type=CarQuery">车辆</a></li>
					<li id=""><a href="/bms/execution/query?type=ComponentQuery">零部件</a></li>
					<li id=""><a href="/bms/execution/query?type=ManufactureQuery">生产</a></li>
					<li id=""><a href="/bms/execution/query?type=NodeQuery">质量</a></li>
					<li id=""><a href="/bms/execution/query?type=BalanceQuery">结存</a></li>
					<li id=""><a href="/bms/execution/query?type=OrderCarQuery">发车</a></li>
					<li id=""><a href="/bms/execution/query?type=ReplacementQuery">换件</a></li>
				</ul>
			</div>
		</div>	
	</div>
</div>
</div>