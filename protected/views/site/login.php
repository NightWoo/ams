<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>总装长沙AMS</title>
		<!-- Le styles -->
		<link href="/bms/css/bootstrap.min.css" rel="stylesheet">
		<link href="/bms/css/login.css" rel="stylesheet">
		<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src="/bms/js/service.js"></script>
		<script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
	</head>


<body>
	<div class="span12" id="emputyHead">
	    <!--<img src="../img/logo.png">-->
	</div>
	<div class="span12 row">
	    <div class="row">
	    <div class="span6 well">
	        <legend>
	          欢迎访问 AMS
	          <div class="pull-right">
	               <a id="addFavor" href="#">加入收藏</a>
	               &nbsp;
	               <a id="setHome" href="#">设为首页</a>
	          </div>  
	        </legend>
            <form class="form-horizontal" action='/bms/site/login' method='post' name='LoginForm'>
                <div class="control-group">
                    <label class="control-label" for="inputEmail">地区</label>
                    <div class="controls form-inline">
                        <label class="radio inline">
                            <input type="radio" name="optionsRadios" id="optionsRadiosChangsha" value="option1" checked>
                                                                                                   长沙
                        </label>
                        &nbsp;&nbsp;&nbsp;
                        <label class="radio inline">
                            <input type="radio" name="optionsRadios" id="optionsRadiosShenzhen" value="option2">
                                                                                                   深圳
                        </label>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputEmail">账号</label>
                    <div class="controls">
                        <input type="text" id="inputUserName" placeholder="请输入账号" name='username' value="">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">密码</label>
                    <div class="controls">
                        <input type="password" id="inputPassword" placeholder="请输入密码" name='password' value="">
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls form-inline">              
                        <button type="submit" class="btn btn-primary">登陆</button>
                        &nbsp;&nbsp;&nbsp;
                        <label class="checkbox">
                            <input type="checkbox" name="rememberMe" value="true"> 让浏览器记住我
                        </label>
                    </div>
                </div>
            </form>
        </div>
	    <div class="span5">
	        <table>	            
	            <tr>
                    <td>
                        <ul class="unstyled">


                            <li class="title"><i class="icon-exclamation-sign"></i>&nbsp;警告</li>
                            <li>
                                <ul id="warning">
                                    <li class="text-error">本系统只允许合法用户使用</li>
                                    <li class="text-error">用户不得将账号交予他人使用，否则将被追究相关责任</li>
                                    <li class="text-error">任何越权操作将被记录</li>
                                    <li class="text-error">用户须定期修改密码，并保护密码安全</li>
                                </ul> 
                            </li>  
                            <li class="title"><i class="icon-envelope"></i>&nbsp;联系我们</li>
                            <li>
                                <ul id="contect">
                                    <li class="text-info">账号申请：<a href="liu.wenlu@byd.com">liu.wenlu@byd.com</a></li>
                                    <li class="text-info">系统维护：<a href="liu.wenlu@byd.com">liu.wenlu@byd.com</a></li>
                                    <li class="text-info">系统管理：<a href="wu.jun9@byd.com">wu.jun9@byd.com</a></li>
                                    <li class="text-info">联系电话：0755-89888888-69550</li>
                                </ul> 
                            </li>           
							<li class="title"><i class="icon-download"></i>&nbsp;浏览器下载</li>
							<li>
								<ul>
									<li class="text-info"><a href="/bms/download/browser/firefox.rar">firefox浏览器</a></li>
								</ul>
							</li>
                        </ul>   
                    </td>
                </tr>
	        </table>             
	    </div>
	    </div>
	</div>
	<div class="span12 row">
	    <div class="row">	    	    
		<div class="span6"><p class="muted">Copyright&nbsp;&copy;&nbsp;2012&nbsp;&nbsp;第十一事业部</p></div>
		<div class="span6"><p class="muted pull-right">更新于2012-12-28</p></div>
		</div>
	</div>
</body>
</html>
