<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Accounts</title>
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<style type="text/css">
		#sectionDiv label.checkbox  {
			margin-left:0;
			margin-right:10px;
		}
	</style>
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/rjs/lib/jsrender.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/generalInformation/maintain/accountMaintain.js"></script>
</head>
<body>
	<?php
		require_once(dirname(__FILE__)."/../../common/head.php");
	?>
        <div class="offhead">
           <?php
            // require_once(dirname(__FILE__)."/../../common/left/assembly_dataInput_left.php");
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
						            <a class="btn btn-link" id="btnPauseSms">停线短信订阅</a>
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
                              	<th>管理</th>
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
		    <label class="control-label" for="selfTel"> 座机</label>
		    <div class="controls">
		      <input type="text" id="selfTel" placeholder="座机">
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
		  <div class="control-group">
		    <label class="control-label" for="inputCertificate">操作凭证</label>
		    <div class="controls">
		      <input type="text" id="inputCertificate" placeholder="Certificate">
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
		  <div class="control-group">
		    <label class="control-label" for="editCertificate">操作凭证</label>
		    <div class="controls">
		      <input type="text" id="inputCertificate" placeholder="Certificate">
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

<div class="modal" id="pauseSmsSubscriptionModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;width:800px;margin-left:-420px">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h4 style="margin-bottom:0;">停线短信订阅</h4>
  	</div>
  	<div class="modal-body">
  		<form class="form-horizontal">
		  <div class="control-group" id="pauseSmsSubscription">
		  	<div style="margin-left:10px;">
			  	<label class="radio">
					<input type="radio" name="level" id="noSubscription" value="noSubscription" checked>
					不订阅停线短信通知
				</label>
		  		<label class="radio">
					<input type="radio" name="level" id="level_0" value="level_0">
					level-0（立即发送）建议领班、调度使用
				</label>
				<label class="radio">
					<input type="radio" name="level" id="level_1" value="level_1">
					level-1（立即发送）建议责任工程师使用
				</label>
				<label class="radio">
					<input type="radio" name="level" id="level_2" value="level_2">
					level-2（&lt;&nbsp;10Min）建议责任部门主管使用
				</label>
				<label class="radio">
					<input type="radio" name="level" id="level_3" value="level_3">
					level-3（&lt;&nbsp;30Min）建议责任部门经理使用
				</label>
				<label class="radio">
					<input type="radio" name="level" id="level_4" value="level_4">
					level-4（&lt;&nbsp;60Min）建议总经理使用
				</label>
		  	</div>
		  	<legend style="margin-top:15px;margin-bottom:10px">工段
		  		<label style="margin-left:5px;padding-bottom:8px" class="checkbox inline">
				    <input id="checkboxSectionAll" type="checkbox" name="sectionAll" value="all">全选
			    </label>
		  	</legend>
		  	<div id="sectionDiv" style="margin-left:10px;">
			  	<label class="checkbox inline">
				    <input type="checkbox" name="section" value="T0">T0
			    </label>
		  	    <label class="checkbox inline">
				    <input type="checkbox" name="section" value="T1">T1
			    </label>
			    <label class="checkbox inline">
				    <input type="checkbox" name="section" value="T2">T2
			    </label>
			    <label class="checkbox inline">
				    <input type="checkbox" name="section" value="T3">T3
			    </label>
			    <label class="checkbox inline">
				    <input type="checkbox" name="section" value="C1">C1
			    </label>
			    <label class="checkbox inline">
				    <input type="checkbox" name="section" value="C2">C2
			    </label>
			    <label class="checkbox inline">
				    <input type="checkbox" name="section" value="C3">C3
			    </label>
			    <label class="checkbox inline">
				    <input type="checkbox" name="section" value="F1">F1
			    </label>
			    <label class="checkbox inline">
				    <input type="checkbox" name="section" value="F2">F2
			    </label>
			    <label class="checkbox inline">
				    <input type="checkbox" name="section" value="VQ1">VQ1
			    </label>
		  	</div>
		  	<legend style="margin-top:20px;margin-bottom:10px">责任
		  		<label style="margin-left:5px;padding-bottom:8px" class="checkbox inline">
				    <input id="checkboxDutyAll" type="checkbox" name="dutyAll" value="all">全选
			    </label>
		  	</legend>
		  	<div id="dutyDiv" style="margin-left:10px;">
		  	</div>
		  	<script id="tmplDutyCheckbox" type="text/x-jsrander">
                <label style='margin-left:0;margin-right:10px;' class='checkbox inline'><input type='checkbox' name='duty' value='{{:duty_group}}'>{{:duty_group}}</input></label>
            </script>
		  </div>
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnConfirmscription">确认订阅</button>
  	</div>
</div>
</body>
</html>