<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>成本与资产</title>
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
                    <legend>资产</legend>
                    <div>
                        <ul class="thumbnails">
                            <li class="span2">
                              <div id="toolsManagement" class="thumbnail" style="height:120px">
                                <div>
                                    <h5>工具管理</h5>
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
<script data-main="/bms/rjs/costPannel.js" src="/bms/rjs/lib/require.js"></script>
</html>