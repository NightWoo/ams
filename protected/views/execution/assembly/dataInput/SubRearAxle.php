<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>BMS.Ver0.1</title>
	<!-- Le styles -->
	<link href="/bms/trunk/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bms/trunk/css/nodeselection-as.css" rel="stylesheet">
	<style type="text/css">
	
	body{

		padding-bottom:40px;
	}
		</style>
        <style type="text/css">td img {display: block;}</style>
	</head>


	<body>
		
		<!-- 页眉 高亮生产执行-->
		<!--#include virtual="/bms/views/html/head.html" -->
		<!-- END 页眉 -->

		<!-- 侧边栏 高亮录入节点选择-->
    	<!--#include virtual="/bms/views/html/head.html" -->	
		<!-- END 侧边栏 高亮录入节点选择-->

     	<!-- 页体 -->
        <div class="span10">
            <!-- breadcrumb -->
            <div>
            	<ul class="breadcrumb">
            		<li><a href="#">生产执行</a><span class="divider">&gt;</span></li>
                	<li><a href="#">总装</a><span class="divider">&gt;</span></li>
					<li><a href="dataInputNodeSelect.html">数据录入</a><span class="divider">&gt;</span></li>
                	<li class="active"><a href="#">仪表分装</a></li>                
            	</ul>
            </div>
            <!-- 主体 -->
			<h2>已选择：F0 . C123456 . LGXC16DGXC1234566 . 2012-06-06</h2>
		    <button class="btn">打印</button>
			<hr/>
			<div class="tabbable">
	        <ul class="nav nav-tabs">
		          <li class="active"><a href="#tab1" data-toggle="tab">当前列队</a></li>
		          <li class=""><a href="#tab2" data-toggle="tab">已打印列队</a></li>
		          <li class=""><a href="#tab3" data-toggle="tab">指定车辆</a></li>
	        </ul>
			<div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
				  <div class="tab-pane active" id="tab1">
						<p>I'm in Section 1.</p>
				  </div>
				  <div class="tab-pane" id="tab2">
						<p>Howdy, I'm in Section 2.</p>
				  </div>
				  <div class="tab-pane" id="tab3">
						<p>What up girl, this is Section 3.</p>
				  </div>
			</div>
		</div>
	  
	  
	  
	  
		 
        
		<script type="text/javascript" src="../../../js/jquery-1.8.0.min.js"></script>
    	<script type="text/javascript" src="../../../js/bootstrap.min.js"></script>

    	
	</body>
</html>