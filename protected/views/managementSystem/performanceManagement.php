<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>绩效管理</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/bms/css/managementSystem/performanceManagement.css" rel="stylesheet">
		    <link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
		    <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/managementSystem/performanceManagement.js"></script>
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
					    <div id="Carouse" class="carousel slide">
    						<!-- Carousel items -->
    						<div class="carousel-inner">
                  <?php 
                    $dir='/home/work/bms/web/bms/doc/browse/managementSystem/manpower/performanceManagement/';  
                    $handle=opendir($dir);  
                    $i=0;  
                    while(false!==($file=readdir($handle))){  
                      if($file!='.' && $file!='..' && $file!='thumb.JPG'){  
                        //var_dump($file);  
                        $i++;  
                      }  
                    }  
                    closedir($handle);
                    
                    for($n=1;$n<=$i;$n++){
                      if($n===1){
                        echo '<div class="item active">';
                      } else {
                         echo '<div class="item">';
                      }
                      echo '<img src="/bms/doc/browse/managementSystem/manpower/performanceManagement/'. $n .'.JPG" alt="">';
                      echo '<div class="carousel-caption">';
                      echo '<p class="pull-right"><a href="/bms/doc/ppt/PerformanceManagement.pptx"><i class="icon-download-alt icon-white"></i>下载该文档</a></p>';
                      echo '<h4>绩效管理</h4>';
                      echo '<p></p>';
                      echo '</div>';
                      echo '</div>';
                    }
                  ?>
    						</div>
                <!-- Carousel nav -->
    						<a class="carousel-control left" href="#Carouse" data-slide="prev">&lsaquo;</a>
    						<a class="carousel-control right" href="#Carouse" data-slide="next">&rsaquo;</a>
    					</div>
				</div><!-- end 内容主体 -->
			</div><!-- end Main -->
		</div><!-- end offhead -->   
    </body>
</html>
