<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>体系概况</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/bms/css/managementSystem/ManagementSystemHome.css" rel="stylesheet">
		    <link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
		    <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/managementSystem/managementSystemHome.js"></script>
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
                    //1_overview
                    $dir1='/home/work/bms/web/bms/doc/browse/managementSystem/managementManual/AMS_Overview';  
                    $handle=opendir($dir1);  
                    $i=0;  
                    while(false!==($file=readdir($handle))){  
                      if($file!='.' && $file!='..' && $file!='thumb.jpg'){  
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
                      echo '<img src="/bms/doc/browse/managementSystem/managementManual/AMS_Overview/'. $n .'.JPG" alt="">';
                      echo '<div class="carousel-caption">';
                      echo '<p class="pull-right"><a href="/bms/doc/ppt/AMS_1_Overview.pptx"><i class="icon-download-alt icon-white"></i>下载该文档</a></p>';
                      echo '<h4>概述</h4>';
                      echo '<p></p>';
                      echo '</div>';
                      echo '</div>';
                    }

                    //3_management_goal
                    $dir2='/home/work/bms/web/bms/doc/browse/managementSystem/managementManual/AMS_Goal';  
                    $handle=opendir($dir2);  
                    $i=0;  
                    while(false!==($file=readdir($handle))){  
                      if($file!='.' && $file!='..' && $file!='thumb.jpg'){  
                        //var_dump($file);  
                        $i++;  
                      }  
                    }  
                    closedir($handle);
                    
                    for($n=1;$n<=$i;$n++){
                      echo '<div class="item">';
                      echo '<img src="/bms/doc/browse/managementSystem/managementManual/AMS_Goal/'. $n .'.JPG" alt="">';
                      echo '<div class="carousel-caption">';
                      echo '<p class="pull-right"><a href="/bms/doc/ppt/AMS_3_ManagementGoal.pptx"><i class="icon-download-alt icon-white"></i>下载该文档</a></p>';
                      echo '<h4>管理目标</h4>';
                      echo '<p></p>';
                      echo '</div>';
                      echo '</div>';
                    }

                    //5_structure_and_duty
                    $dir3='/home/work/bms/web/bms/doc/browse/managementSystem/managementManual/AMS_Structure';  
                    $handle=opendir($dir3);  
                    $i=0;  
                    while(false!==($file=readdir($handle))){  
                      if($file!='.' && $file!='..' && $file!='thumb.jpg'){  
                        //var_dump($file);  
                        $i++;  
                      }  
                    }  
                    closedir($handle);
                    
                    for($n=1;$n<=$i;$n++){
                      echo '<div class="item">';
                      echo '<img src="/bms/doc/browse/managementSystem/managementManual/AMS_Structure/'. $n .'.JPG" alt="">';
                      echo '<div class="carousel-caption">';
                      echo '<p class="pull-right"><a href="/bms/doc/ppt/AMS_5_StructureAndDuty.pptx"><i class="icon-download-alt icon-white"></i>下载该文档</a></p>';
                      echo '<h4>组织结构及职责</h4>';
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
