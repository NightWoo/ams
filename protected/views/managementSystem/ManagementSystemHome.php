<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />
        <meta http-equiv="expires" content="0" />
        <title>体系概况</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/bms/css/managementSystem/ManagementSystemHome.css" rel="stylesheet">
		    <link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
		    <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/jquery.event.move.js"></script>
        <script type="text/javascript" src="/bms/js/jquery.event.swipe.js"></script>
        <script type="text/javascript" src="/bms/js/managementSystem/managementSystemHome.js"></script>
    </head>
    <body>
        <?php
        	require_once(dirname(__FILE__)."/../common/head.php");
        ?>
		<div class="offhead">
<div id="divLeft">
<div id="bodyleft" class="span2"><!-- 侧边栏 -->
  <ul class="nav nav-list affix">
    <li id="holder" chapter=<?php echo $chapter ?>></li>
    <li class="nav-header" id="leftManualLi">管理手册</li>
      <li id="leftOverviewLi"><a href="#">概述</a></li>
      <li id="leftPolicyLi"><a href="#">方针与愿景</a></li>
      <li id="leftGoalLi"><a href="#">管理目标</a></li>
      <li id=""><a>八大原则</a></li>
      <li id="leftStructureLi"><a href="#">组织结构与职责</a></li>
      <li id="leftProcessLi"><a href="#">管理过程</a></li>
    <li class="nav-header">要素/方法/指南</li>
      <li id="leftFieldManagement"><a href="/bms/ManagementSystem/field?view=MSField">安全与现场</a></li>
      <li id="leftManpowerLi"><a href="/bms/ManagementSystem/manpower?view=MSManpower">人力资源</a></li>
      <li id="leftQualityLi"><a href="/bms/ManagementSystem/quality?view=MSQuality">质量</a></li>
      <li id=""><a>生产力</a></li>
      <li id=""><a>设备/资产与成本</a></li>
    <li class="nav-header">标准表单</li>
      <li id="leftStandarFormLi"><a href="/bms/ManagementSystem/standardForm">表单下载</a></li>
     <!-- <li class="divider"></li>      
      <li><a href="#">帮助</a></li>     -->
  </ul>        
</div><!-- end 侧边栏 -->
<div id="toggle-left" href="">
  <div id="leftHandle">
  </div>
  <i id="icon-left" class="icon-caret-right icon-large"></i>
</div>
</div>
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
					    <div id="Carousel" class="carousel slide">
    						<!-- Carousel items -->
    						<div class="carousel-inner">
    							<?php
                    //1_overview
                    $dir1='/home/work/bms/web/bms/doc/browse/managementSystem/managementManual/AMS_1_Overview';  
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
                      echo '<img class="slideImg" src="/bms/doc/browse/managementSystem/managementManual/AMS_1_Overview/'. $n .'.JPG">';
                      echo '<div class="marsking"></div>';
                      echo '<div class="carousel-caption">';
                      echo '<p class="pull-right"><a href="/bms/doc/ppt/AMS_1_Overview.pptx"><i class="icon-download-alt icon-white"></i>下载该文档</a></p>';
                      echo '<h4>概述</h4>';
                      echo '<p></p>';
                      echo '</div>';
                      echo '</div>';
                    }

                    //2_policy_and_vision
                    $dir2='/home/work/bms/web/bms/doc/browse/managementSystem/managementManual/AMS_2_PolicyAndVision';  
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
                      echo '<img src="/bms/doc/browse/managementSystem/managementManual/AMS_2_PolicyAndVision/'. $n .'.JPG" alt="">';
                      echo '<div class="marsking"></div>';
                      echo '<div class="carousel-caption">';
                      echo '<p class="pull-right"><a href="/bms/doc/ppt/AMS_2_PolicyAndVision.pptx"><i class="icon-download-alt icon-white"></i>下载该文档</a></p>';
                      echo '<h4>管理目标</h4>';
                      echo '<p></p>';
                      echo '</div>';
                      echo '</div>';
                    }

                    //3_management_goal
                    $dir3='/home/work/bms/web/bms/doc/browse/managementSystem/managementManual/AMS_3_ManagementGoal';  
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
                      echo '<img src="/bms/doc/browse/managementSystem/managementManual/AMS_3_ManagementGoal/'. $n .'.JPG" alt="">';
                      echo '<div class="marsking"></div>';
                      echo '<div class="carousel-caption">';
                      echo '<p class="pull-right"><a href="/bms/doc/ppt/AMS_3_ManagementGoal.pptx"><i class="icon-download-alt icon-white"></i>下载该文档</a></p>';
                      echo '<h4>管理目标</h4>';
                      echo '<p></p>';
                      echo '</div>';
                      echo '</div>';
                    }

                    //5_structure_and_duty
                    $dir5='/home/work/bms/web/bms/doc/browse/managementSystem/managementManual/AMS_5_StructureAndDuty';  
                    $handle=opendir($dir5);  
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
                      echo '<img src="/bms/doc/browse/managementSystem/managementManual/AMS_5_StructureAndDuty/'. $n .'.JPG" alt="">';
                      echo '<div class="marsking"></div>';
                      echo '<div class="carousel-caption">';
                      echo '<p class="pull-right"><a href="/bms/doc/ppt/AMS_5_StructureAndDuty.pptx"><i class="icon-download-alt icon-white"></i>下载该文档</a></p>';
                      echo '<h4>组织结构及职责</h4>';
                      echo '<p></p>';
                      echo '</div>';
                      echo '</div>';
                    }
					
					//6_process
                    $dir6='/home/work/bms/web/bms/doc/browse/managementSystem/managementManual/AMS_6_Process';  
                    $handle=opendir($dir6);  
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
                      echo '<img src="/bms/doc/browse/managementSystem/managementManual/AMS_6_Process/'. $n .'.JPG" alt="">';
                      echo '<div class="marsking"></div>';
                      echo '<div class="carousel-caption">';
                      echo '<p class="pull-right"><a href="#"><i class="icon-download-alt icon-white"></i>下载该文档</a></p>';
                      echo '<h4>管理过程</h4>';
                      echo '<p></p>';
                      echo '</div>';
                      echo '</div>';
                    }

                  ?>
                </div>
                <!-- Carousel nav -->
    						<a class="carousel-control left" href="#Carousel" data-slide="prev">&lsaquo;</a>
    						<a class="carousel-control right" href="#Carousel" data-slide="next">&rsaquo;</a>
    					</div>
				</div><!-- end 内容主体 -->
			</div><!-- end Main -->
		</div><!-- end offhead -->   
    </body>
</html>
