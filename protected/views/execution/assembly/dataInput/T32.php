<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>BMS.Ver0.1</title>
	<!-- Le styles -->
	<link href="/bms/trunk/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bms/trunk/css/nodeselection-as.css" rel="stylesheet">
	<link href="/bms/trunk/css/bms.css" rel="stylesheet">
	
	</head>


	<body>
		
        <div class="span10">
            <!-- breadcrumb -->
            <div>
            	<ul class="breadcrumb">
            		<li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
                	<li><a href="#">总装</a><span class="divider">&gt;</span></li>
					<li><a href="dataInputNodeSelect.html">数据录入</a><span class="divider">&gt;</span></li>
                	<li class="active"><a href="#">Ⅰ线.T32</a></li>                
            	</ul>
            </div>
            <!-- 主体 -->
			<div>
				<form class="well form-search">
					<label>VIN：</label>
					<input type="text" class="span3" placeholder="请扫描/输入VIN...">
					<button type="submit" class="btn btn-primary disabled">提交</button>
					<button class="btn">清空</button>
				</form>
			</div>
			
			<div class="span8"><div class="span2"><b>车系:</b><span id="chexi"></span></div><div class="span4"><b>车型:</b></div><div class="span2"><b>颜色:</b></div><div class="span1"><b>备注:/</b></div>
			</div>
			
		    <div>	
		    <table class="table table-striped table-bordered">
				<thead>
				  <tr>
					<th class="lingbujianmingchen">零部件名称</th>
					<th class="lingbujiantiaoma">零部件条码</th>
				  </tr>
				</thead>
				<tbody>
				  <tr>
					<td>机械转向管柱</td>
					<td></td>
				  </tr>
				  <tr>
					<td>转向轴锁</td>
					<td></td>
				  </tr>
				  <tr>
					<td>前排乘员安全气囊模块及饰盖总成</td>
					<td></td>
				  </tr>
				  <tr>
					<td>ABS7.8控制单元及阀体总成</td>
					<td></td>
				  </tr>
				  <tr>
					<td>钥匙及锁芯组件</td>
					<td></td>
				  </tr>				
				</tbody>
			</table>
		    </div> 
			  
			 <div>
				
			    <span>零部件条码输入：</span>
				<input type="text" class="input-medium search-query">
				
			</div>
			
			<div>现已生产：<span id="生产数">辆</span></div>
        </div>
        
		<script type="text/javascript" src="../../../js/jquery-1.8.0.min.js"></script>
    	<script type="text/javascript" src="../../../js/dropdownmenu.js"></script>

    	
	</body>
</html>