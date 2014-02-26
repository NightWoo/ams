<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>AMS</title>
  <%= compliedCss %>
</head>
<body>
    <header class="navbar navbar-fixed-top navbar-default" role="banner" ng-include=" 'common/head.master.tpl.html' ">
    </header>
    <div class="container" ui-view>

    </div>
</body>
<%= compiledJs %>
</html>