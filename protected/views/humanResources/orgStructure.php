<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>组织架构</title>
	<link rel="stylesheet" href="/bms/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bms/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/bms/src/css/common.css">
</head>
<body>
    <header class="navbar navbar-fixed-top navbar-default" role="banner">
        <style type="text/css" media="screen">
            body {padding-top: 70px;}
        </style>
    	<?php require_once(dirname(__FILE__)."/../common//header/navMaster.php"); ?>
    </header>
    <main>
        <div class="container">
            <legend>组织结构</legend>
        </div>
    </main>
    <!-- <footer class="navbar-inverse  navbar-fixed-bottom" role="banner">
        <style type="text/css" media="screen">
            body {padding-bottom: 70px;}
        </style>
        <?php /*require_once(dirname(__FILE__)."/../../common//footer/navQuery.php")*/; ?>
    </footer> -->
</body>
<script data-main="/bms/src/js/humanResources/orgStructure.js" src="/bms/vendor/requirejs/require.js"></script>
</html>