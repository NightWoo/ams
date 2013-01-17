<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>质量</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/bms/css/managementSystem/MSQuality.css" rel="stylesheet">
		    <link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
		    <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/managementSystem/MSQuality.js"></script>
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
                  <a href="/bms/ManagementSystem/quality?view=andon" class="thumbnail">
                    <img src="/bms/doc/browse/managementSystem/quality/andon/thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/ppt/andon.pptx">下载</a>
                    <h4>Andon系统</h4>
                    </div>
                    <div class="description">
                      <p>暗灯（Andon）系统是流水线操作过程控制系统，当出现异常状况时，通过手动或自动激活系统来寻求帮助以及交流其他相关的信息。</p>
                    </div>
                  </div>
                </div>
              </li>
              <li class="span3">
                <div class="">
                  <a href="/bms/ManagementSystem/quality?view=controllLoop" class="thumbnail">
                    <img src="/bms/doc/browse/managementSystem/quality/controllLoop/thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/ppt/controllLoop.pptx">下载</a>
                    <h4>质量控制环</h4>
                    </div>
                    <div class="description">
                      <p>认识质量控制环</p>
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
