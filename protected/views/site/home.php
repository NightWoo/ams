<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>总装长沙AMS</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/bms/css/font-awesome.min.css">
	<link href="/bms/css/home.css" rel="stylesheet">
</head>
<body>
	<div id="maintContainer" class="container">
		<div class="row">
			<div class="span9">
				<legend>
					<span id="welcome">欢迎使用AMS</span>
          <span class="pull-right">
            <a href="/bms/generalInformation/accountMaintain" rel="tooltip" data-toggle="tooltip" data-placement="bottom" title="账户管理"><?php echo Yii::app()->user->display_name; ?></a>
  					&nbsp;
            <a href="/bms/site/logout" rel="tooltip" data-toggle="tooltip" data-placement="bottom" title="注销"><i class="fa fa-signout"></i></a>
          </span>
				</legend>
				<div>
            <ul class="thumbnails">
              <li class="span3">
                <div id="managementSystemPannel" class="thumbnail pannel" style="background-color:#2b5797;">
                  <div>
                      <h4><i class="fa fa-sitemap"></i>&nbsp;体系</h4>
                  </div>
                </div>
              </li>
              <li class="span3">
                  <div id="technologyPannel" class="thumbnail pannel" style="background-color:#ffc40d;">
                  <h4><i class="fa fa-cogs"></i>&nbsp;技术</h4>
                  <h3 class="maindata"></h3>
                </div>
              </li>
              <li class="span3">
                  <div id="manufacturePannel" class="thumbnail pannel" style="background-color:#1BA1E2;">
                  <h4><i class="fa fa-wrench"></i>&nbsp;生产</h4>
                  <h3 class="maindata"></h3>
                </div>
              </li>
              <li class="span9">
                  <div id="monitorPannel" class="thumbnail">
                    <h4 class="pull-right"><i class="fa fa-desktop"></i>&nbsp;监控</h4>
                    <!-- <img src="/bms/img/workshop_thumbnail.jpg" alt=""> -->
                  </div>
              </li>
              <li class="span3">
                <div id="qualityPannel" class="thumbnail pannel" style="background-color:#b91d47;">
                  <div>
                      <div>
                        <div class="mainData pull-right">
                          <span id="DRR">-</span>
                        </div>
                        <h4><i class="fa fa-thumbs-up"></i>&nbsp;质量</h4>
                      </div>
                      <ul>
                      	<li id="vq1"></li>
              					<li id="vq2"></li>
              					<li id="vq3"></li>
                      </ul>
                  </div>
                </div>
                </li>
                <li class="span3">
                  <div id="efficiencyPannel" class="thumbnail pannel" style="background-color:#339933;">
                    <div>
                        <div>
                            <div class="mainData pull-right">
                              <span id="workingTimePercentage">-</span> / <span id="pauseTime">-</span><span class="unit">min</span>
                            </div>
                            <h4><i class="fa fa-dashboard"></i>&nbsp;效率</h4>
                        </div>
                        <ul>
                        	<li id="onLine"></li>
                					<li id="checkin"></li>
                					<li id="checkout"></li>
                        </ul>
                    </div>
                  </div>
                </li>
                <li class="span3">
                  <div id="fieldPannel" class="thumbnail pannel" style="background-color:#F09609;">
                    <div>
                        <h4><i class="fa fa-map-marker"></i>&nbsp;现场</h4>
                    </div>
                  </div>
                </li>
                <li class="span3">
                  <div id="costPannel" class="thumbnail pannel" style="background-color:#E671B8;">
                    <div>
                        <div>
                            <div class="mainData pull-right">
                              <span>￥</span><span id="uintCost">-</span>
                            </div>
                            <h4><i class="fa fa-money"></i>&nbsp;成本</h4>
                        </div>
                        <ul>
                          <li id="costF0"></li>
                          <li id="costM6"></li>
                          <li id="cost6B"></li>
                        </ul>
                    </div>
                  </div>
                </li>
                <li class="span3">
                  <div id="manpowerPannel" class="thumbnail pannel" style="background-color:#00ABA9;">
                    <div>
                        <h4><i class="fa fa-group"></i>&nbsp;人事</h4>
                    </div>
                  </div>
                </li>
                <li class="span3">
                  <div id="databasePannel" class="thumbnail pannel" style="background-color:#A200FF;">
                    <div>
                        <h4><i class="fa fa-list-alt"></i>&nbsp;数据</h4>
                    </div>
                  </div>
                </li>
              </ul>
        </div>
			</div>
			<div id="shortcut" class="span3">
				<legend>捷径</legend>
				<div id="shortcutContainer">
            <ul id="shortcutUl" class="thumbnails">
                <li class="span2" href="/bms/execution/query?type=ManufactureQuery">
                  <div class="thumbnail shortcut">
                    <div>
                        <h5>生产查询</h5>
                    </div>
                  </div>
                </li>
                <li class="span2" href="/bms/execution/query?type=CarQuery" >
                  <div class="thumbnail shortcut">
                    <div>
                        <h5>车辆查询</h5>
                    </div>
                  </div>
                </li>
                <li class="span2" href="/bms/execution/report?type=ManufactureReport">
                  <div class="thumbnail shortcut">
                    <div>
                        <h5>生产报表</h5>
                    </div>
                  </div>
                </li>
                <li class="span2" href="/bms/execution/report?type=QualityReport">
                  <div class="thumbnail shortcut">
                    <div>
                        <h5>质量报表</h5>
                    </div>
                  </div>
                </li>
                <li class="span2" href="/bms/execution/planMaintain">
                  <div id="" class="thumbnail shortcut">
                    <div>
                        <h5>生产计划</h5>
                    </div>
                  </div>
                </li>
                <li class="span2" href="/bms/execution/orderMaintain">
                  <div id="" class="thumbnail shortcut">
                    <div>
                        <h5>发车计划</h5>
                    </div>
                  </div>
                </li>
                <li class="span2" href="/bms/execution/pauseEdit">
                  <div id="" class="thumbnail shortcut">
                    <div>
                        <h5>停线编辑</h5>
                    </div>
                  </div>
                </li>
                <li class="span2" href="/bms/execution/warehouseAdjust">
                  <div id="warehouseAdjust" class="thumbnail shortcut">
                    <div>
                        <h5>库位整理</h5>
                    </div>
                  </div>
                </li>
<!--                 <li class="span2" href="/bms/execution/warehouseReturn">
                  <div id="" class="thumbnail shortcut">
                    <div>
                        <h5>成品库退库</h5>
                    </div>
                  </div>
                </li> -->
<!--                 <li class="span2" href="/bms/execution/DetectShopAccess">
                  <div id="" class="thumbnail shortcut">
                    <div>
                        <h5>车辆门禁</h5>
                    </div>
                  </div>
                </li> -->
            </ul>
        </div>
			</div>
		</div>
	</div>
</body>
<script data-main="/bms/rjs/home.js" src="/bms/rjs/lib/require.js"></script>
</html>
