<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>AMS</title>
	<link rel="stylesheet" href="/bms/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/bms/vendor/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/bms/src/css/common.css">
  <link rel="stylesheet" href="/bms/src/css/humanResources/positionSystem.css">
</head>
<body>
    <header class="navbar navbar-fixed-top navbar-default" role="banner">
    	<?php require_once(dirname(__FILE__)."/../common//header/navMaster.php"); ?>
    </header>
    <main class="container" ui-view>

    </main>
</body>
<script data-main="/bms/build/src/app/main.js" src="/bms/vendor/requirejs/require.js"></script>
</html>