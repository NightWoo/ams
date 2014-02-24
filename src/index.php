<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>AMS</title>
	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <header class="navbar navbar-fixed-top navbar-default" role="banner" ng-include=" 'common/head.master.tpl.html' ">
    </header>
    <main class="container" ui-view>

    </main>
</body>
<%= compiledJs %>
</html>