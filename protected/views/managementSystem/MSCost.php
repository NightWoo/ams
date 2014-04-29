<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>设备/资产与成本</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/bms/css/managementSystem/MSCost.css" rel="stylesheet">
		    <link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
		    <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/managementSystem/MSCost.js"></script>
    </head>
    <body>
        <?php
        	require_once(dirname(__FILE__)."/../common/head.php");
        ?>
		<div class="offhead">
			<?php
              require_once(dirname(__FILE__)."/../common/left/management_system_left.php");
      ?>
			<div id="bodyright" class="offset2"><!-- Main -->
				<!-- <div>
					<ul class="breadcrumb">
						<li>
							<a href="#">管理体系</a><span class="divider">&gt;</span>
						</li>
						<li class="active">
							体系概况
						</li>
					</ul>
				</div>-->
				<div class="main"><!-- 内容主体 -->
					<div class="row-fluid">
            <ul class="thumbnails">
              <li class="span3">
                <div class="">
                  <a href="/bms/ManagementSystem/cost?view=costPerUnit" class="thumbnail">
                    <img src="/bms/doc/browse/managementSystem/cost/costPerUnit/thumb.JPG" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/ppt/costPerUnit.pptx">下载</a>
                    <h4>单车自损成本</h4>
                    </div>
                    <div class="description">
                      <p>了解单车自损成</p>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
				</div><!-- end 内容主体 -->
			</div><!-- end Main -->
		</div><!-- end offhead -->
    </body>
</html>
