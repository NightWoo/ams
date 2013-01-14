<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Accounts</title>
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
		require_once(dirname(__FILE__)."/../../common/head.php");
	?>
        <div class="offhead">
           <?php
            require_once(dirname(__FILE__)."/../../common/left/assembly_dataInput_left.php");
            ?>

        	<!-- Main体 -->	
    		
            <div id="bodyright" class="offset2">
                <div ><ul class="breadcrumb"><!-- 面包屑 -->
                        <li><a href="#">综合信息</a><span class="divider">&gt;</span></li>
                        <li><a href="#"> 维护与帮助</a><span class="divider">&gt;</span></li>
                        <li class="active">账户管理</li>                
                </ul></div><!-- end 面包屑 -->

                <table class='table'>
                	<thead>
                            <tr>
                            	<th>账号</th>
                              	<th>姓名</th>
                              	<th>邮箱</th>
                              	<th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr id="userInfoTr">
                            	<td id="userId">账号</td>
                              	<td id="userName">姓名</td>
                              	<td id="userEmail">邮箱</td>
                              	<td>
				                    <div class="btn-group">
						                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">修改 <span class="caret"></span></button>
						                <ul class="dropdown-menu">

						                  <li>
						                  	<a href="#emailModal" id="modifySelf">修改个人信息</a>
						                  </li>
						                  <li><a href="#passwordModal" data-toggle="modal">修改密码</a></li>
						                </ul>
						            </div>
                              	</td>
                            </tr>
                        </tbody>
                </table>
                <div id='adminThing'>
	                <form class="well form-horizontal">
	                    <table>
							<tr>
								<td><label>账号</label></td>
								<td><label>姓名</label></td>
								<td><label>邮箱</label></td>
								<td></td>
							</tr>
							<tr>
								<td><input id="queryUsername" type="text" class="span2" placeholder="账号"></td>
								<td><input id="queryDisplayName" type="text" class="span2" placeholder="姓名"></td>
								<td><input id="queryEmail" type="text" class="span3" placeholder="邮箱"></td>
								<td>
									<input type="button" class="btn btn-primary" id ="btnQuery" value ="查询"></input>
	            					<a href="#myModal" role="button" class="btn btn-success" data-toggle="modal">增加</a>
								</td>
							</tr>
						</table>
	                </form>
	                <div>
                    <h5 class='pull-left'>用户列表:<span id='totalText'></span></h5>  

                </div>
                   	<table id="resultTable" class="table table-bordered">
                        <thead>
                            <tr>
                            	<th>账号</th>
                              	<th>姓名</th>
                              	<th>邮箱</th>
                              	<th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    <ul class="pager">
                        <li class="prePage"><a>上一页</a></li>
                        <li class="curPage" page="1"> 第1页</li>
                        <li class="nextPage"><a>下一页</a></li>
                    </ul>
                </div>
                
            </div><!-- END MAIN -->
        </div>


<div class="modal" id="emailModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>修改个人信息</h3>
  	</div>
  	<div class="modal-body">
  		<form class="form-horizontal">
		  <div class="control-group">
		    <label class="control-label" for="inputEmail"> 新的邮箱地址</label>
		    <div class="controls">
		      <input type="text" id="inputEmailChange" placeholder="新的邮箱地址">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="selfCell"> 手机</label>
		    <div class="controls">
		      <input type="text" id="selfCell" placeholder="手机">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="sellTel"> 座机</label>
		    <div class="controls">
		      <input type="text" id="sellTel" placeholder="座机">
		    </div>
		  </div>	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnEmailChange">确认修改</button>
  	</div>
</div>

<div class="modal" id="passwordModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>修改密码</h3>
  	</div>
  	<div class="modal-body">
  		<form class="form-horizontal">
  			<div class="control-group">
			    <label class="control-label" for="inputPasswordOld">旧的密码  </label>
			    <div class="controls">
			      	<input type="password" id="inputPasswordOld" placeholder="Old Password">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="inputPasswordNew"> 新的密码</label>
			    <div class="controls">
			      	<input type="password" id="inputPasswordNew" placeholder="New Password">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="inputPasswordConfirm"> 确认新密码</label>
			    <div class="controls">
			      	<input type="password" id="inputPasswordConfirm" placeholder="Confirm Password">
			    </div>
			</div>	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnPasswordChange">确认修改</button>
  	</div>
</div>

<div class="modal" id="initPasswordModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>重置后的密码</h3>
  	</div>
  	<div class="modal-body">
  		<form class="form-horizontal">
  		
			<div class="control-group">
			    <label class="control-label" id='resetedPassword'></label>
			</div>

		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">确认</button>
  	</div>
</div>


<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3 id="myModalLabel"> 添加新用户</h3>
  	</div>
  	<div class="modal-body">
  		<form class="form-horizontal">
  			<div class="control-group">
		    <label class="control-label" for="inputCardNumber">工号</label>
		    <div class="controls">
		      <input type="text" id="inputCardNumber" placeholder="CardNumber">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputEmail">邮箱</label>
		    <div class="controls">
		      <input type="text" id="inputEmail" placeholder="Email">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputUsername">账号</label>
		    <div class="controls">
		      <input type="text" id="inputUsername" placeholder="Username">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputDisplayName">姓名</label>
		    <div class="controls">
		      <input type="text" id="inputDisplayName" placeholder="DisplayName">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputCellPhone">手机号</label>
		    <div class="controls">
		      <input type="text" id="inputCellPhone" placeholder="CellPhone">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputTelephone">电话号码</label>
		    <div class="controls">
		      <input type="text" id="inputTelephone" placeholder="Telephone">
		    </div>
		  </div>
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnAdd">确认</button>
  	</div>
</div>

<div class="modal" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>修改账户信息</h3>
  	</div>
  	<div class="modal-body">
  		<form class="form-horizontal">
		  <div class="control-group">
		    <label class="control-label" for="editCardNumber">工号</label>
		    <div class="controls">
		      <input type="text" id="editCardNumber" placeholder="CardNumber">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="editEmail">邮箱</label>
		    <div class="controls">
		      <input type="text" id="editEmail" placeholder="Email">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="editUsername">账号</label>
		    <div class="controls">
		      <input type="text" id="editUsername" placeholder="Username">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="editDisplayName">姓名</label>
		    <div class="controls">
		      <input type="text" id="editDisplayName" placeholder="DisplayName">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="editCellPhone">手机号</label>
		    <div class="controls">
		      <input type="text" id="editCellPhone" placeholder="CellPhone">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="editTelephone">电话号码</label>
		    <div class="controls">
		      <input type="text" id="editTelephone" placeholder="Telephone">
		    </div>
		  </div>	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnEdit">确认修改</button>
  	</div>
</div>
</body>
</html>