<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>计划面板</title>
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
    <link href="/bms/css/common.css" rel="stylesheet">
	<link href="/bms/css/pannel.css" rel="stylesheet">
</head>
<body>
	<?php
        require_once(dirname(__FILE__)."/../../common/head.php");
    ?>
    <div class="offhead">
        <div id="bodyright" class="offset2"><!-- 页体 -->
            <div class="container">
                <div>
                    <legend>计划</legend>
                    <div>
                        <ul class="thumbnails">
                            <li class="span2">
                              <div id="planMaintain" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>生产计划</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="orderMaintain" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>发车计划</h5>
                                </div>
                              </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div>
                    <legend>总装维护</legend>
                    <div>
                        <ul class="thumbnails">
                            <li class="span2">
                              <div id="planPause" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>班次/计划停线</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="pauseEdit" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>停线编辑</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="configMaintain" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>配置维护</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="subQueueMaintain" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>分装列队</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="spsQueueMaintain" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>SPS列队</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="dataThrow" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>数据抛送</h5>
                                </div>
                              </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div>
                    <legend>成品库维护</legend>
                    <div>
                        <ul class="thumbnails">
                            <li class="span2">
                              <div id="warehouseAdjust" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>库位整理</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="warehouseReturn" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>退库</h5>
                                </div>
                              </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div><!-- end of 页体 -->
    </div><!-- end of head -->
</body>
<script data-main="/bms/rjs/planPannel.js" src="/bms/rjs/lib/require.js"></script>
</html>