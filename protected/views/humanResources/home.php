<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>人事</title>
	<link rel="stylesheet" href="/bms/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bms/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/bms/src/css/common.css">
</head>
<body>
    <header class="navbar navbar-fixed-top navbar-default" role="banner">
    	<?php require_once(dirname(__FILE__)."/../common//header/navMaster.php"); ?>
    </header>
    <div class="body-main">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <legend>人员规划</legend>
                    <div class="row pannel-thumbnails">
                        <div class="col-sm-4">
                            <a href="/bms/humanResources/orgStructure" class="thumbnail">
                                <h5>组织结构</h5>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="/bms/humanResources/positionSystem" class="thumbnail">
                                <h5>岗位体系</h5>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="" class="thumbnail">
                                <h5>人员编制</h5>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <legend>员工关系</legend>
                    <div class="row pannel-thumbnails">
                        <div class="col-sm-4">
                            <a href="#" class="thumbnail">
                                <h5>入职</h5>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="#" class="thumbnail">
                                <h5>调岗</h5>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="#" class="thumbnail">
                                <h5>离职</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <legend>员工发展</legend>
                    <div class="row pannel-thumbnails">
                        <div class="col-sm-4">
                            <a href="#" class="thumbnail">
                                <h5>培训</h5>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="#" class="thumbnail">
                                <h5>考核</h5>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="#" class="thumbnail">
                                <h5>晋升</h5>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <legend>绩效薪酬</legend>
                    <div class="row pannel-thumbnails">
                        <div class="col-sm-4">
                            <a href="#" class="thumbnail">
                                <h5>考勤</h5>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="#" class="thumbnail">
                                <h5>绩效考核</h5>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="#" class="thumbnail">
                                <h5>计件工资</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <footer class="navbar-inverse  navbar-fixed-bottom" role="banner">
        <style type="text/css" media="screen">
            body {padding-bottom: 70px;}
        </style>
        <?php /*require_once(dirname(__FILE__)."/../../common//footer/navQuery.php")*/; ?>
    </footer> -->
</body>
<script data-main="/bms/src/js/humanResources/home.js" src="/bms/vendor/requirejs/require.js"></script>
</html>