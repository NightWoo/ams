<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>数据</title>
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
    <style type="text/css">
        .thumbnail li {
            margin-bottom: 5px;
        }

        .thumbnail h5 {
            margin-left: 10px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
	<?php
        require_once(dirname(__FILE__)."/../common/head.php");
    ?>
    <div class="offhead">
        <div id="bodyright" class="offset2"><!-- 页体 -->
            <div class="container">
                <div>
                    <legend>总装报表<span>&nbsp;&nbsp;(2013-08-12)</span></legend>
                    <div>
                        <ul class="thumbnails">
                            <li class="span4">
                              <div id="manufactureReport" class="thumbnail" style="height:160px">
                                <div>
                                    <h5>生产报表</h5>
                                    <ul>
                                        <li>上线/计划完成[<span id="assemblyCount1"></span> / <span id="completion1"></span>]、[<span id="assemblyCount2"></span> / <span id="completion2"></span>]</li>
                                        <li>停线/生产利用[<span id="pauseTime"></span>min / <span id="useRate"></span>]</li>
                                        <li>周转车/生产周期[<span id="recycleBalance"></span> / <span id="assemblyPeriod"></span>H]</li>
                                        <li>发车/成品库周期[<span id="distributeCount"></span> / <span id="warehousePeriod"></span>H]</li>
                                    </ul>
                                </div>
                              </div>
                            </li>
                            <li class="span4">
                              <div id="qualityReport" class="thumbnail" style="height:160px">
                                <div>
                                    <h5>质量报表(F0/M6/思锐)</h5>
                                    <ul>
                                        <li>VQ1: <span class='VQ1 total'></span>[<span class="VQ1 sub F0"></span> / <span class="VQ1 sub M6"></span> / <span class="VQ1 sub 6B"></span>]</li>
                                        <li>VQ1-II线: <span class='VQ1_2 total'></span></li>
                                        <li>VQ2-路试: <span class='VQ2_ROAD_TEST total'></span>[<span class="VQ2_ROAD_TEST sub F0"></span> / <span class="VQ2_ROAD_TEST sub M6"></span> / <span class="VQ2_ROAD_TEST sub 6B"></span>]</li>
                                        <li>VQ2-淋雨: <span class='VQ2_LEAK_TEST total'></span>[<span class="VQ2_LEAK_TEST sub F0"></span> / <span class="VQ2_LEAK_TEST sub M6"></span> / <span class="VQ2_LEAK_TEST sub 6B"></span>]</li>
                                        <li>VQ3: <span class='VQ3 total'></span>[<span class="VQ3 sub F0"></span> / <span class="VQ3 sub M6"></span> / <span class="VQ3 sub 6B"></span>]</li>
                                    </ul>
                                </div>
                              </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div>
                    <legend>定制报表</legend>
                    <div>
                        <ul class="thumbnails">
                            <li class="span2">
                              <div id="" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>计划处日报</h5>
                                </div>
                              </div>
                            </li>
                            <!-- <li class="span2">
                              <div id="" class="thumbnail" style="height:120px">
                                <div>
                                    <h5></h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="" class="thumbnail" style="height:120px">
                                <div>
                                    <h5></h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="" class="thumbnail" style="height:120px">
                                <div>
                                    <h5></h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="" class="thumbnail" style="height:120px">
                                <div>
                                    <h5></h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="" class="thumbnail" style="height:120px">
                                <div>
                                    <h5></h5>
                                </div>
                              </div>
                            </li> -->
                        </ul>
                    </div>
                </div>

                <div>
                    <legend>基础数据库</legend>
                    <div>
                        <ul class="thumbnails">
                            <li class="span2">
                              <div id="componentMaintain" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>零部件清单</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="faultMaintain" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>标准故障库</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="providerMaintain" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>供应商名录</h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="distributorMaintain" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>经销商名录</h5>
                                </div>
                              </div>
                            </li>
                            <!-- <li class="span2">
                              <div id="" class="thumbnail" style="height:120px">
                                <div>
                                    <h5></h5>
                                </div>
                              </div>
                            </li>
                            <li class="span2">
                              <div id="" class="thumbnail" style="height:120px">
                                <div>
                                    <h5></h5>
                                </div>
                              </div>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div><!-- end of 页体 -->
    </div><!-- end of head -->
</body>
<script data-main="/bms/rjs/generalIndex.js" src="/bms/rjs/lib/require.js"></script>
</html>