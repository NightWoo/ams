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
                <!--<div ><ul class="breadcrumb">
                        <li><a href="#">综合信息</a><span class="divider">&gt;</span></li>
                        <li><a href="#"> 维护与帮助</a><span class="divider">&gt;</span></li>
                        <li class="active">账户管理</li>                
                </ul></div>-->
                <legend>个人中心</legend>
                <form class="well form-horizontal">
                <table class='table table-condense' style="margin-bottom:0;">
                	<thead>
                            <tr>
                            	<th style="width:80px">账号</th>
                              	<th style="width:80px">用户名</th>
                              	<th style="width:180px">邮箱</th>
                              	<th style="width:120px">手机</th>
                              	<th style="width:120px">座机</th>
                              	<th></th>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr id="userInfoTr">
                            	<td id="userId">账号</td>
                              	<td id="userName">用户名</td>
                              	<td id="userEmail">邮箱</td>
                              	<td id="userCell">手机</td>
                              	<td id="userTel">座机</td>
                              	<td>
				                    <div class="btn-group">
					                	<a class='btn btn-small' href="#emailModal" id="modifySelf">个人信息</a>
										<a class='btn btn-primary btn-small' href="#passwordModal" data-toggle="modal">修改密码</a>
						            </div>
                              	</td>
                            </tr>
                        </tbody>
                </table>
            	</form>
                <div id='adminThing'>
	                <legend>账号管理</legend>
	                <form class="well form-horizontal">
	                    <table>
							<tr>
								<td><label>账号</label></td>
								<td><label>用户名</label></td>
								<td><label>邮箱</label></td>
								<td></td>
							</tr>
							<tr>
								<td><input id="queryUsername" type="text" class="span2" placeholder="账号"></td>
								<td><input id="queryDisplayName" type="text" class="span2" placeholder="姓名"></td>
								<td><input id="queryEmail" type="text" class="span3" placeholder="邮箱"></td>
								<td>
									<input type="button" class="btn btn-primary" id ="btnQuery" value ="查询" style="margin-left:2px"></input>
	            					<a href="#newModal" role="button" class="btn btn-success" data-toggle="modal">增加</a>
								</td>
							</tr>
						</table>
	                </form>
	                <!-- <div>
                    <h5 class='pull-left'>用户列表:<span id='totalText'></span></h5>  
                	</div> -->
                   	<table id="resultTable" class="table table-hover table-condensed">
                        <thead>
                            <tr>
                            	<th style="width:50px">#</th>
                            	<th style="width:80px">账号</th>
                              	<th style="width:80px">用户名</th>
                              	<th style="width:180px">邮箱</th>
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
		    <label class="control-label" for="inputEmail"> 邮箱</label>
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


<div class="modal" id="newModal" tabindex="-1" role="dialog" aria-labelledby="newModalLabel" aria-hidden="true" style="display:none;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3 id="newModalLabel"> 添加新用户</h3>
  	</div>
  	<div class="modal-body">
  		<form class="form-horizontal">
  			<div class="control-group">
		    <label class="control-label" for="inputCardNumber">*工号</label>
		    <div class="controls">
		      <input type="text" id="inputCardNumber" placeholder="CardNumber">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputUsername">*账号</label>
		    <div class="controls">
		      <input type="text" id="inputUsername" placeholder="Username">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputDisplayName">*用户名</label>
		    <div class="controls">
		      <input type="text" id="inputDisplayName" placeholder="DisplayName">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputCard8H10D">厂牌</label>
		    <div class="controls">
		      <input type="text" id="inputCard8H10D" placeholder="Card8H10D">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputEmail">邮箱</label>
		    <div class="controls">
		      <input type="text" id="inputEmail" placeholder="Email">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputCellPhone">手机</label>
		    <div class="controls">
		      <input type="text" id="inputCellPhone" placeholder="CellPhone">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputTelephone">座机</label>
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
		    <label class="control-label" for="editCardNumber">*工号</label>
		    <div class="controls">
		      <input type="text" id="editCardNumber" placeholder="CardNumber">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="editUsername">*账号</label>
		    <div class="controls">
		      <input type="text" id="editUsername" placeholder="Username">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="editDisplayName">*用户名</label>
		    <div class="controls">
		      <input type="text" id="editDisplayName" placeholder="DisplayName">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="editCard8H10D">厂牌</label>
		    <div class="controls">
		      <input type="text" id="editCard8H10D" placeholder="8H10D">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="editEmail">邮箱</label>
		    <div class="controls">
		      <input type="text" id="editEmail" placeholder="Email">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="editCellPhone">手机</label>
		    <div class="controls">
		      <input type="text" id="editCellPhone" placeholder="CellPhone">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="editTelephone">座机</label>
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


<div class="modal" id="rightModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>编辑权限</h3>
  	</div>
  	<div class="modal-body">
  		<form class="form-horizontal">
		  <div class="control-group" id="rightControls">
		    <!-- <label class="control-label" for="editCardNumber">工号</label> -->
		      	<!-- <label class="checkbox inline">
				  <input type="checkbox" id="inlineCheckbox1" value="option1"> 1123123123
				</label>
				<label class="checkbox inline">
				  <input type="checkbox" id="inlineCheckbox2" value="option2" checked> 2sdsddssd
				</label>
				<label class="checkbox inline">
				  <input type="checkbox" id="inlineCheckbox3" value="option3"> 32sdsddssd2sdsddssd2sdsddssd2sdsddssd
				</label>
				<label class="checkbox inline">
				  <input type="checkbox" id="inlineCheckbox1" value="option1"> 1
				</label>
				<label class="checkbox inline">
				  <input type="checkbox" id="inlineCheckbox2" value="option2"> 2
				</label>
				<label class="checkbox inline">
				  <input type="checkbox" id="inlineCheckbox3" value="option3"> 3
				</label> -->
		  </div>
		  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnRight">确认修改</button>
  	</div>
</div>
</body>
</html>