<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>AMS</title>
	<link rel="stylesheet" href="/bms/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/bms/vendor/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/bms/src/app/app.css">
</head>
<body ng-controller="CtrlApp">
    <div ui-view="header" class="clearfix"></div>
    <div ui-view="body"  class="max-height-body"></div>
    <div ui-view="footer" class="clearfix"></div>
</body>
<script data-main="/bms/src/app/main.js" src="/bms/vendor/requirejs/require.js"></script>
</html>