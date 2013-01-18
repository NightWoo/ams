<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>人力资源</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/bms/css/managementSystem/MSManpower.css" rel="stylesheet">
		    <link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
		    <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/managementSystem/MSManpower.js"></script>
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
                  <a href="/bms/ManagementSystem/manpower?view=promotion" class="thumbnail">
                    <img src="/bms/doc/browse/managementSystem/manpower/promotion/thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/ppt/promotion.pptx">下载</a>
                    <h4>员工晋升与发展</h4>
                    </div>
                    <div class="description">
                      <p>了解我们的晋升通道，明确我们发展方向。</p>
                    </div>
                  </div>
                </div>
              </li>

              <li class="span3">
                <div class="">
                  <a href="/bms/ManagementSystem/manpower?view=performanceManagement" class="thumbnail">
                    <img src="/bms/doc/browse/managementSystem/manpower/performanceManagement/thumb.JPG" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/ppt/performanceManagement.pptx">下载</a>
                    <h4>绩效管理</h4>
                    </div>
                    <div class="description">
                      <p>通过制定有效、客观的考核标准，科学、动态地衡量员工的工作状态和效果</p>
                      <p>将公司/部门战略目标传导给每一位员工，以进一步激发员工的积极性和创造性</p>
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
