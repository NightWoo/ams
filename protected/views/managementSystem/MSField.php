<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>安全与现场</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/bms/css/managementSystem/MSField.css" rel="stylesheet">
		    <link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
		    <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/managementSystem/MSField.js"></script>
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
                  <a href="/bms/ManagementSystem/field?view=visualManagement" class="thumbnail">
                    <img src="/bms/doc/browse/managementSystem/fieldManagement/visualManagement/thumb.JPG" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/ppt/visualManagementA0.pptx">下载</a>
                    <h4>目视化管理</h4>
                    </div>
                    <div class="description">
                      <p>目视管理是指通过视觉采集信息后，利用大脑对其进行简单判断（并非逻辑思考）而直接产生“对”或“错”的结论的管理方法。这种方法最大的优点是直接、快捷，因而被现代制造企业所广泛采用。简单地讲，目视管理就是用眼睛看的懂而非大脑想的通的管理方法。</p>
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
