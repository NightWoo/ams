<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>备件库</title>
	<link href="/bms/css/bootstrap.css" rel="stylesheet">
	<link href="/bms/css/common.css" rel="stylesheet">
	
</head>
<body>
	<?php 
		require_once(dirname(__FILE__)."/../../../common/head.php");
	?>
	<div class="offhead">
		<?php
			require_once(dirname(__FILE__)."/../../../common/left/assembly_dataInput_left.php");
		?>
		<div id="bodyright" class="offset2">
			<legend>
				备件库
			</legend>

			<div><!-- mainbody -->
				<div>
					<form id="formVin" class="well form-search">
						<div>
							<div class="input-prepend input-append">
								<span class="add-on">VIN</span>
								<input id="vinText" type="text" class="span3" placeholder="请扫描/输入VIN...">
								<a id="validateVinBtn" class="btn validateVinBtn appendBtn"><i class="icon-search"></i></a>
							</div>
							<button type="button" id="btnSubmint" class="btn btn-primary">提交</button>
							<button type="button" id="reset" class="btn">清空</button>
							<input type="hidden" id="currentNod" name="currentNode" value="<?php echo $node?>">
							<div class="help-inline" id="carInfo">
								<span class="label label-info" rel="tooltip" title="流水号" id="serialNumber"></span>
								<span class="label label-info" rel="tooltip" title="车系" id="series"></span>
								<span class="label label-info" rel="tooltip" title="车型" id="type"></span>
								<span class="label label-info" rel="tooltip" title="颜色" id="color"></span>
                        		<span class="label label-info" rel="tooltip" title="车辆区域" id="statusInfo"></span>
							</div>
						</div>
					</form>
					<div>
						<div id="messageAlert" class="alert"></div>
					</div>
					<div class="row-fluid">
						<div class="span5">
							<table class="table">
								<caption>故障</caption>
								<thead>
									<tr>
										<th>节点</th>
										<th>故障</th>
										<th>责任</th>
										<th>选择</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>data</td>
										<td>data</td>
										<td>data</td>
										<td>data</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="span7">
							<table class="table">
								<caption>table title and/or explanatory text</caption>
								<thead>
									<tr>
										<th>零部件名称</th>
										<th>零部件编码</th>
										<th>供应商</th>
										<th>零部件条码</th>
										<th>连带损</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>data</td>
										<td>data</td>
										<td>data</td>
										<td>data</td>
										<td>data</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div><!-- end of mainbody -->
		</div><!-- end of bodyright -->
	</div>
<div id="modaltest" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>对话框标题</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn">关闭</a>
    <a href="#" class="btn btn-primary">Save changes</a>
  </div>
</div>
<script data-main="/bms/rjs/sparesStore.js" src="/bms/rjs/require.js"></script>
</body>
</html>