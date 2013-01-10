<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>总装节点选择</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src="/bms/js/head.js"></script>
    </head>
    <body>
        <?php
        	require_once(dirname(__FILE__)."/../common/head.php");
        ?>
		<div class="offhead">
			<?php
              require_once(dirname(__FILE__)."/../common/left/assembly_dataInput_left.php");
            ?>
			<div id="bodyright" class="offset2"><!-- 页体 -->
				<div>
					<legend>捷径</legend>
				</div>
				<div class="row">
					<div class="span2 offset1">
						<button class="btn btn-large" onclick="window.location.href='/bms/execution/index'">总装数据录入</button>
					</div>
					<div class="span2">
						<button class="btn btn-large" onclick="window.location.href='/bms/execution/query?type=NodeQuery'">总装节点查询</button>
					</div>
					<div class="span2">
						<button class="btn btn-large" onclick="window.location.href='/bms/execution/query?type=CarQuery'">总装车辆查询</button>
					</div>
					<div class="span2">
						<button class="btn btn-large" onclick="window.location.href='/bms/execution/query?type=FaultQuery'">总装故障查询</button>
					</div>
				</div>
				
				<!--<div>
					<legend>生产简报</legend>
					<div>
                    	<ul id="tabs" class="nav nav-pills">
                            <li class="active"><a href="#assembly" data-toggle="tab">总装</a></li>
                            <li><a href="#paint" data-toggle="tab">涂装</a></li>
                            <li><a href="#body" data-toggle="tab">焊装</a></li>
                            <li><a href="#press" data-toggle="tab">冲压</a></li>
                         </ul>
                     </div>
                         <div id="tabContent" class="tab-content span10">
                             <div class="tab-pane active" id="assembly">
                            	<div id="infoAssembly" class="alert alert-info">
									<P><a href="#"><i class="icon-share-alt"></i>总装生产监控</a></P>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
								</div>
                             </div>
							 
                             <div class="tab-pane" id="paint">
                                <div id="infoPaint" class="alert alert-info">
									<p><a href="#"><i class="icon-share-alt"></i>涂装生产监控</a></p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
								</div>
                             </div>
							 
							 <div class="tab-pane" id="body">
                                <div id="infoBody" class="alert alert-info">
									<P><a href="#"><i class="icon-share-alt"></i>焊装生产监控</a></P>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
								</div>
                             </div>
                                
                        	<div class="tab-pane" id="press">
                                <div id="infoPress" class="alert alert-info">
									<p><a href="#"><i class="icon-share-alt"></i>冲压生产监控</a></p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
									<p>info</p>
								</div>
                        	</div>
                        </div>
				</div>-->
        	</div><!-- end of 页体 -->
    </body>
</html>
