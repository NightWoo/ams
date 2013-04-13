<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>故障库维护</title>
	<!-- Le styles -->
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
    <link href="/bms/css/generalInformation/basicData/faultmaintain.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	<!-- Le script -->
	<script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="/bms/js/service.js"></script>
    <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bms/js/head.js"></script>
    <script type="text/javascript" src="/bms/js/jquery.form.js"></script>
	<script type="text/javascript" src="/bms/js/generalInformation/basicData/faultMaintain.js"></script>
</head>
<body>
	<!-- <div data-spy="affix" data-offset-top="200" data-offset-left="200">...</div> -->
<?php
	require_once(dirname(__FILE__)."/../../common/head.php");
?>
<div class="offhead">
<?php
	require_once(dirname(__FILE__)."/../../common/left/general_database_left.php");
?>
     
<div id="bodyright" class="offset2"><!-- 页体 -->

<div><!-- breadcrumb -->
	<ul class="breadcrumb">
		<li><a href="#">综合信息</a><span class="divider">&gt;</span></li>
    	<li><a href="#">基础数据库</a><span class="divider">&gt;</span></li>
    	<li class="active">故障库维护</li>            
	</ul>
</div><!-- end of breadcrumb -->
            
<div><!-- 主体 -->
	<div id="divTabs">
    	<ul id="tabs" class="nav nav-pills">
            <li class="active"   id="liAssembly"><a href="#assembly" data-toggle="tab">总装</a></li>
            <li class="" id="liPaint"><a href="#paint" data-toggle="tab">涂装</a></li>
			<li class="" id="liBody"><a href="#body" data-toggle="tab">焊装</a></li>
			<li class="" id="liPress"><a href="#press" data-toggle="tab">冲压</a></li>
        </ul>
    </div>

	<div id="formComponent" class="well form-search">
		<div class="tab-content">
			<div id="assembly" class="tab-pane active">
				<!-- <form id="formAssembly"> -->
					<table>
						<tr>
							<td><label>车系</label></td>
							<td><label>故障类别</label></td>
							<td><label>零部件名称</label></td>
							<td><label>故障模式</label></td>
							<td><label>状态</label></td>
							<td></td>
						</tr>
						<tr>
							<td>
								<select id="selectCarSeries" class="span1">
									<option value="F0" selected>F0</option>
									<option value="M6">M6</option>
									<option value="6B">思锐</option>
								</select>
							</td>
							<td>
								<select id="selectFaultKind" class="input-small">
									<option value="assembly" selected>全部</option>
									<option value="1">装配失当</option>
									<option value="2">零部件</option>
									<option value="3">设备相关</option>
									<option value="4">密封性</option>
									<option value="5">其它_整车</option>
								</select>
							</td>
							<td><input id="inputComponentName" type="text" class="span2" placeholder="零部件名称"></td>
							<td><input id="inputFaultMode" type="text" class="span2" placeholder="故障模式"></td>
							<td>
								<select id="selectCategory" class="input-small">
									<option value="all" selected>全部</option>
									<option value="enable">启用</option>
									<option value="disable">禁用</option>
								</select>
							</td>
							<td id="tdLevel">
								<button id="btnQuery" type="reset" class="btn btn-primary">查询</button>
								<button id="btnAdd" class="btn btn-success">新增</button>
								<label class="checkbox">
									<input type="checkbox" id="levelS" value="1">S
								</label>
								<label class="checkbox">
									<input type="checkbox" id="levelA" value="1">A
								</label>
								<label class="checkbox">
									<input type="checkbox" id="levelB" value="1">B
								</label>
								<label class="checkbox">
									<input type="checkbox" id="levelC" value="1">C
								</label>
							</td>
						</tr>
						<tr>
							<td colspan="5">
								
							</td>
							<td>
							
							</td>
						</tr>
					</table>
				<!-- </form> -->
			</div>
		</div>		               


    </div>
	<div id="divResult">
		<table id="tableFaultStandard" class="table table-condensed">
			<thead>
				<tr>
					<th>故障代码</th>
					<th>零部件名称</th>
					<th>故障模式</th>
					<th></th>
					<th>严重度</th>
					<th>故障类别</th>
					<th>状态</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>

		<div class="pagination">
			<ul>
				<li><a href="#"><span>全部导出</span></a></li>
			</ul>
			<ul>
				<li class="prePage"><a href="#"><span>&laquo;</span></a></li>
				<li class="active curPage" page="1"><a href="#"><span id="curPage">1</span></a></li>
				<li class="nextPage"><a href="#"><span>&raquo;</span></a></li>
			</ul>
		</div> 
	</div>     <!--End divResult -->
</div><!-- end of 页体 -->
</div><!-- offhead -->
  	
<!-- new record -->
<div class="modal" id="newModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>新增</h3>
  	</div>
  	<div class="modal-body" style="max-height:435px;">
  		<form class="form-horizontal">
  			<div class="control-group">
			    <label class="control-label" for="newSeries">车系 </label>
			    <div class="controls">
			      	<select id="newSeries" class="span2">
						<option value="F0">F0</option>
						<option value="M6">M6</option>
						<option value="6B">思锐</option>
					</select>
			    </div>
			</div>
  			<div class="control-group">
			    <label class="control-label" for="newCode"> 	故障代码 </label>
			    <div class="controls">
			      	<input type="text" id="newCode" placeholder="故障代码" disabled>
			    </div>
			</div>
			
			<div class="control-group">
			    <label class="control-label" for="newName"> 	零部件名称  </label>
			    <div class="controls">
			      	<input type="text" id="newName" placeholder="零部件名称">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="newMode"> 故障模式</label>
			    <div class="controls">
			      	<input type="text" id="newMode" placeholder="故障模式">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="newLevel"> 严重度</label>
			    <div class="controls">
			    	<select id="newLevel" class="span2">
						<option value="S">S</option>
						<option value="A" selected="selected">A</option>
						<option value="B">B</option>
						<option value="C">C</option>
					</select>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="newKind"> 故障类别</label>
			    <div class="controls">
			    	<select id="newKind" class="span2">
						<option value="1">装配失当</option>
						<option value="2">零部件</option>
						<option value="3">设备相关</option>
						<option value="4">密封性</option>
						<option value="5">其它_整车</option>
						
					</select>
			    </div>
			</div>	  
			<div class="control-group">
			    <label class="control-label" for="newStatus"> 状态</label>
			    <div class="controls">
			      	<select id="newStatus" class="span2">
						<option value="1">启用</option>
						<option value="0">冻结</option>
					</select>
			    </div>
			</div>
			<div class="control-group">
	            <label class="control-label" for="newDescription">描述</label>
	            <div class="controls">
	              <textarea class="input-xlarge" id="newDescription" rows="2"></textarea>
	            </div>
          	</div>	  	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnNewConfirm">确认新增</button>
  	</div>
</div>
<!-- edit record -->
<div class="modal" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3>编辑</h3>
  	</div>
  	<div class="modal-body" style="max-height:435px;">
  		<form class="form-horizontal">
  			<div class="control-group">
			    <label class="control-label" for="inputSeries">车系 </label>
			    <div class="controls">
			      	<select id="inputSeries" class="span2" disabled="disabled">
						<option value="F0">F0</option>
						<option value="M6">M6</option>
						<option value="6B">思锐</option>
						
					</select>
			    </div>
			</div>
  			<div class="control-group">
			    <label class="control-label" for="inputCode"> 	故障代码 </label>
			    <div class="controls">
			      	<input type="text" id="inputCode" placeholder="故障代码" disabled="disabled">
			    </div>
			</div>
			
			<div class="control-group">
			    <label class="control-label" for="inputName"> 	零部件名称  </label>
			    <div class="controls">
			      	<input type="text" id="inputName" placeholder="零部件名称" disabled="disabled">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="inputMode"> 故障模式</label>
			    <div class="controls">
			      	<input type="text" id="inputMode" placeholder="故障模式">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="inputLevel"> 严重度</label>
			    <div class="controls">
			    	<select id="inputLevel" class="span2">
						<option value="S">S</option>
						<option value="A">A</option>
						<option value="B">B</option>
						<option value="C">C</option>
					</select>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="inputKind"> 故障类别</label>
			    <div class="controls">
			    	<select id="inputKind" class="span2">
						<option value="1">装配失当</option>
						<option value="2">零部件</option>
						<option value="3">设备相关</option>
						<option value="4">密封性</option>
						<option value="5">其它_整车</option>
						
					</select>
			    </div>
			</div>	  
			<div class="control-group">
			    <label class="control-label" for="inputStatus"> 状态</label>
			    <div class="controls">
			      	<select id="inputStatus" class="span2">
						<option value="1">启用</option>
						<option value="0">冻结</option>
					</select>
			    </div>
			</div>	  
			<div class="control-group">
	            <label class="control-label" for="inputDescription">描述</label>
	            <div class="controls">
	              <textarea class="input-xlarge" id="inputDescription" rows="2"></textarea>
	            </div>
          	</div>	  	  
		</form>
  	</div>
  	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-primary" id="btnEditConfirm">确认修改</button>
  	</div>
</div>

<!-- picModal -->
<div class="modal" id="picModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
  	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   	 	<h3 id="picMoadlHeader">编辑</h3>
  	</div>
  	<div class="modal-body" style="max-height:435px;">
  		<div id="myCarousel" class="carousel slide">
			  
			  <!-- Carousel items -->
			  <div class="carousel-inner" id="carouselInner">
			    <div class="active item">
			    	<img src="/bms/img/test01.jpg">
			    </div>
			    <div class="item">
			    	<img src="/bms/img/test02.jpg">
			    </div>
			    <div class="item">
			    	<img src="/bms/img/test03.jpg">
			    </div>
			  </div>
			  <!-- Carousel nav -->
			  <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
			  <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
			</div>
			<p id="picModalDesc">
this is description
hehhe

  		</p>
  		</div>

  	<div class="modal-footer">
  		<form name="front" id="imageUploadForm" method="post" enctype="multipart/form-data" 
						action="/bms/fault/uploadImage"> 
			上传故障图片 <input type="file" name="image" />
			 <input type="submit" value="上传" class="btn btn-primary"/>
			<input type="reset" value="清空" class="btn">
		</form>
	    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	    <button class="btn btn-danger" id="btnDeletePicture"> 删除该图片</button>
  	</div>
</div>


</body>
</html>