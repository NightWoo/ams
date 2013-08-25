<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>总装长沙AMS</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/bms/css/font-awesome.min.css">
	<link href="/bms/css/pannel.css" rel="stylesheet">
	<style type="text/css">
		#maintContainer {
      margin-top: 10px;
    }
    #shortcut {
			width: 170px;
			margin-left:50px;
		}
		#shortcutContainer {
			max-height:600px;
			overflow-y: scroll;
		}
	</style>
</head>
<body>
	<div id="maintContainer" class="container">
		<div class="row">
			<div class="span9">
				<legend>
					<span id="welcome">欢迎使用AMS，<a href="/bms/generalInformation/accountMaintain"><?php echo Yii::app()->user->display_name; ?></a></span>
					<span class="pull-right"><a href="/bms/generalInformation/accountMaintain"><a href="/bms/site/logout"><i class="icon-signout"></i></a></span>
				</legend>
				<div>
            <ul class="thumbnails">
              <li class="span3">
                <div id="manufacturePannel" class="thumbnail pannel" style="height:120px">
				          <h5>生产</h5>
				          <h3 class="maindata"><span id="workingTimePercentage">-</span> / <span id="pauseTime">-</span><span class="unit">min</span></h3>
                </div>
              </li>
              <li class="span3">
                <div id="qualityPannel" class="thumbnail pannel" style="height:120px">
                  <div>
                      <h5>质量</h5>
                      <ul>
                      	<li id="vq1"></li>
              					<li id="vq2"></li>
              					<li id="vq3"></li>
                      </ul>
                  </div>
                </div>
                </li>
                <li class="span3">
                  <div id="planPannel" class="thumbnail pannel" style="height:120px">
                    <div>
                        <h5>计划</h5>
                        <ul>
                        	<li id="onLine"></li>
                					<li id="checkin"></li>
                					<li id="checkout"></li>
                        </ul>
                    </div>
                  </div>
                </li>
                <li class="span3">
                  <div id="fieldPannel" class="thumbnail pannel" style="height:120px">
                    <div>
                        <h5>现场</h5>
                    </div>
                  </div>
                </li>
                <li class="span3">
                  <div id="costPannel" class="thumbnail pannel" style="height:120px">
                    <div>
                        <h5>成本</h5>
                    </div>
                  </div>
                </li>
                <li class="span3">
                  <div id="manpowerPannel" class="thumbnail pannel" style="height:120px">
                    <div>
                        <h5>人事</h5>
                    </div>
                  </div>
                </li>
                <li class="span3">
                  <div id="managementSystemPannel" class="thumbnail pannel" style="height:120px">
                    <div>
                        <h5>体系</h5>
                    </div>
                  </div>
                </li>
                <li class="span3">
                  <div id="databasePannel" class="thumbnail pannel" style="height:120px">
                    <div>
                        <h5>数据库</h5>
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
                            <li class="span2" href="/bms/execution/index">
                              <div class="thumbnail shortcut" style="height:120px">
                                <div>
                                    <h5>数据录入</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2" href="/bms/execution/query?type=CarQuery" >
                              <div class="thumbnail shortcut" style="height:120px">
                                <div>
                                    <h5>数据查询</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2" href="/bms/execution/report?type=ManufactureReport">
                              <div class="thumbnail shortcut" style="height:120px">
                                <div>
                                    <h5>生产报表</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2" href="/bms/execution/report?type=QualityReport">
                              <div class="thumbnail shortcut" style="height:120px">
                                <div>
                                    <h5>质量报表</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2" href="/bms/execution/planMaintain">
                              <div id="" class="thumbnail shortcut" style="height:120px">
                                <div>
                                    <h5>生产计划</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2" href="/bms/execution/orderMaintain">
                              <div id="" class="thumbnail shortcut" style="height:120px">
                                <div>
                                    <h5>发车计划</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2" href="/bms/execution/pauseEdit">
                              <div id="" class="thumbnail shortcut" style="height:120px">
                                <div>
                                    <h5>停线编辑</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2" href="/bms/execution/warehouseReturn">
                              <div id="" class="thumbnail shortcut" style="height:120px">
                                <div>
                                    <h5>成品库退库</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2" href="/bms/execution/DetectShopAccess">
                              <div id="" class="thumbnail shortcut" style="height:120px">
                                <div>
                                    <h5>车辆门禁</h5>
                                </div>
                              </div>
                            </li>
                        </ul>
                    </div>
			</div>
		</div>
	</div>
</body>
<script data-main="/bms/rjs/home.js" src="/bms/rjs/lib/require.js"></script>
</html>
