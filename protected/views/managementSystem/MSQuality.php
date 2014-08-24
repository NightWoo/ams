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

              <li class="span3">
                <div class="">
                  <a href="/bms/ManagementSystem/quality?view=ControlLoop-1" class="thumbnail">
                    <img src="/bms/doc/browse/managementSystem/quality/AMS-Q-P3.1_ControlLoop-1/thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/ppt/AMS-Q-P3.1_ControlLoop-1.pptx">下载</a>
                    <h4>质量控制环-1</h4>
                    </div>
                    <div class="description">
                      <p>质量控制环1（CL1）定义了生产线建立并保证达到产品质量标准的流程和方法。CL1通过引导/监控每一个员工和生产管理人员的职责和行为，以确保他们按照工艺文件（SOP）和质量标准文件执行。
                      </p>
                    </div>
                  </div>
                </div>
              </li>

              <li class="span3">
                <div class="">
                  <a href="/bms/ManagementSystem/quality?view=ControlLoop-2" class="thumbnail">
                    <img src="/bms/doc/browse/managementSystem/quality/AMS-Q-P3.2_ControlLoop-2/thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/ppt/AMS-Q-P3.2_ControlLoop-2.pptx">下载</a>
                    <h4>质量控制环-2</h4>
                    </div>
                    <div class="description">
                      <p>质量控制环2（CL2）定义了检查/评估零部件和装配质量特性的方法，通过在生产线末端设置质量关卡进行区域检验，以及在生产线中设置工段检验以检查那些在后工段被覆盖而无法检查的零部件和操作内容。
                      </p>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
            <ul class="thumbnails">
              <li class="span3">
                <div class="">
                  <a href="/bms/ManagementSystem/quality?view=QCC" class="thumbnail">
                    <img src="/bms/doc/browse/managementSystem/quality/QCC/thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/ppt/QCC.pptx">下载</a>
                    <h4>QCC</h4>
                    </div>
                    <div class="description">
                      <p>QCC系由工作在同一现场的人员自动自发的进行质量管理所组成的小组,在自我启发和相互启发的原则下,活用各种统计方法,以全参加的方式不断的进行维护及改善自己工作现场的活动,以达成改善品质,环境,效率及降低成本的目的。
                      </p>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
            <ul class="thumbnails">
              <li class="span3">
                <div class="">
                  <a href="/bms/ManagementSystem/quality?view=QCBasic7Tools" class="thumbnail">
                    <img src="/bms/doc/browse/managementSystem/quality/QCBasic7Tools/thumb.JPG" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/ppt/QCBasic7Tools.pptx">下载</a>
                    <h4>QC七大手法（传统版）</h4>
                    </div>
                    <div class="description">
                      <p>QC七大手法是常用的统计管理方法，又称为初级统计管理方法。传统上主要包括控制图、因果图、直方图、排列图、检查表、层别法、散布图。
                      </p>
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
