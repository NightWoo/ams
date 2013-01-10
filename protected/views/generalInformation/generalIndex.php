<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>基础数据库</title>
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/generalInformation/maintain/accountMaintain.js"></script>
</head>
<body>
	<?php
            require_once(dirname(__FILE__)."/../common/head.php");
        ?>
        <div class="offhead">
            <?php
              require_once(dirname(__FILE__)."/../common/left/general_database_left.php");
            ?>
            <div id="bodyright" class="offset2"><!-- 页体 -->
                
                <div>
                    <legend>管理维护</legend>
                </div>
                <div class="row">
                    <div class="span2 offset1">
                        <button class="btn btn-large" onclick="window.location.href='/bms/generalInformation/accountMaintain'">账户管理</button>
                    </div>
                    <div class="span2">
                        <button class="btn btn-large" onclick="window.location.href='/bms/generalInformation/componentMaintain'">零部件维护</button>
                    </div>
                    <div class="span2">
                        <button class="btn btn-large" onclick="window.location.href='/bms/generalInformation/faultMaintain'">故障库维护</button>
                    </div>
                </div>
                
            </div><!-- end of 页体 -->
    		
           <!--  <div class="offset2">
            	<a href="/bms/generalInformation/accountMaintain" class="btn btn-danger">个人中心</a>
                <a href="/bms/generalInformation/componentMaintain" class="btn btn-danger">零部件维护</a>
            	<a href="/bms/generalInformation/faultMaintain" class="btn btn-danger">故障库维护</a>
            </div> -->


        </div>

</body>
</html>