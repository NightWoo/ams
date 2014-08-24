<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>标准表单</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/bms/css/managementSystem/MSStandardForm.css" rel="stylesheet">
		    <link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
		    <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/js/managementSystem/MSStandardForm.js"></script>
    </head>
    <body>
        <?php
        	require_once(dirname(__FILE__)."/../common/head.php");
        ?>
		<div class="offhead">
			<?php
              require_once(dirname(__FILE__)."/../common/left/management_system_left.php");
      ?>
			<div id="bodyright" class="offset2" style="margin-left: 0px;"><!-- Main -->				
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
              <li>
                <div class="">
                  <a href="#formViewModalJX" class="thumbnail" data-toggle="modal">
                    <img src="/bms/doc/browse/managementSystem/standardFrom/JiXiaoKaoHe_thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/excel/JiXiaoKaoHe_20130115.xlsx">下载</a>
                    <h4>绩效考核表</h4>
                    </div>
                    <div class="description">
                      <p></p>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="">
                  <a href="#formViewModalDD" class="thumbnail" data-toggle="modal">
                    <img src="/bms/doc/browse/managementSystem/standardFrom/NeiBuDiaoDongShenQing_thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/excel/NeiBuDiaoDongShenQing_20130115.xlsx">下载</a>
                    <h4>内部调动申请</h4>
                    </div>
                    <div class="description">
                      <p></p>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="">
                  <a href="#formViewModalJNJZ" class="thumbnail" data-toggle="modal">
                    <img src="/bms/doc/browse/managementSystem/standardFrom/GangWeiJiNengJuZhen_thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/excel/GangWeiJiNengJuZhen_20130115.xlsx">下载</a>
                    <h4>岗位技能矩阵</h4>
                    </div>
                    <div class="description">
                      <p></p>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="">
                  <a href="#formViewModalGWMX" class="thumbnail" data-toggle="modal">
                    <img src="/bms/doc/browse/managementSystem/standardFrom/GangWeiMingXi_thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/excel/GangWeiMingXi_20121220.xlsx">下载</a>
                    <h4>岗位明细</h4>
                    </div>
                    <div class="description">
                      <p></p>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="">
                  <a href="#formViewModalCXWG" class="thumbnail" data-toggle="modal">
                    <img src="/bms/doc/browse/managementSystem/standardFrom/GangWeiMingXi_CheXiWuGuan_thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/excel/GangWeiMingXi_CheXiWuGuan_20121220.xlsx">下载</a>
                    <h4>岗位明细_车系无关班组</h4>
                    </div>
                    <div class="description">
                      <p></p>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="">
                  <a href="#formViewModalJXCFJY" class="thumbnail" data-toggle="modal">
                    <img src="/bms/doc/browse/managementSystem/standardFrom/JiXiaoChuFaJianYi_thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/excel/JiXiaoChuFaJianYi_20130123.xlsx">下载</a>
                    <h4>员工绩效考核/处罚建议</h4>
                    </div>
                    <div class="description">
                      <p></p>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="">
                  <a href="#formViewModalCAR1" class="thumbnail" data-toggle="modal">
                    <img src="/bms/doc/browse/managementSystem/standardFrom/CorrectiveActionReport-model_1_thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/excel/CorrectiveActionReport-model_1.xlsx">下载</a>
                    <h4>纠正预防措施报告-模板1</h4>
                    </div>
                    <div class="description">
                      <p></p>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="">
                  <a href="#formViewModalCAR2" class="thumbnail" data-toggle="modal">
                    <img src="/bms/doc/browse/managementSystem/standardFrom/CorrectiveActionReport-model_2_thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/excel/CorrectiveActionReport-model_2.xlsx">下载</a>
                    <h4>纠正预防措施报告-模板2</h4>
                    </div>
                    <div class="description">
                      <p></p>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="">
                  <a href="#formViewModalCAR3" class="thumbnail" data-toggle="modal">
                    <img src="/bms/doc/browse/managementSystem/standardFrom/CorrectiveActionReport-model_3_thumb.jpg" alt="">
                  </a>
                  <div class="caption">
                    <div>
                    <a class="pull-right btn-link" href="/bms/doc/ppt/CorrectiveActionReport-model_3.pptx">下载</a>
                    <h4>纠正预防措施报告-模板3</h4>
                    </div>
                    <div class="description">
                      <p></p>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
				</div><!-- end 内容主体 -->
			</div><!-- end Main -->
		</div><!-- end offhead --> 
    
    <div id="formViewModalJX" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="">绩效考核表</h3>
        </div>
      <div class="modal-body">
        <img src="/bms/doc/browse/managementSystem/standardFrom/JiXiaoKaoHe_view.jpg" alt="">
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a class="btn btn-primary" href="/bms/doc/excel/JiXiaoKaoHe_20130115.xlsx">下载</a>
      </div>
    </div>

    <div id="formViewModalDD" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="">岗位调动申请表</h3>
        </div>
      <div class="modal-body">
        <img src="/bms/doc/browse/managementSystem/standardFrom/NeiBuDiaoDonShenQing_view.jpg" alt="">
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a class="btn btn-primary" href="/bms/doc/excel/NeiBuDiaoDongShenQing_20130115.xlsx">下载</a>
      </div>
    </div>

    <div id="formViewModalJNJZ" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="">岗位技能矩阵</h3>
        </div>
      <div class="modal-body">
        <img src="/bms/doc/browse/managementSystem/standardFrom/GangWeiJiNengJuZhen_view.jpg" alt="">
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a class="btn btn-primary" href="/bms/doc/excel/GangWeiJiNengJuZhen_20130115.xlsx">下载</a>
      </div>
    </div>

    <div id="formViewModalGWMX" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="">岗位明细</h3>
        </div>
      <div class="modal-body">
        <img src="/bms/doc/browse/managementSystem/standardFrom/GangWeiMingXi_view.jpg" alt="">
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a class="btn btn-primary" href="/bms/doc/excel/GangWeiMingXi_20121220.xlsx">下载</a>
      </div>
    </div>

    <div id="formViewModalCXWG" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="">岗位明细_车系无关班组</h3>
        </div>
      <div class="modal-body">
        <img src="/bms/doc/browse/managementSystem/standardFrom/GangWeiMingXi_CheXiWuGuan_view.jpg" alt="">
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a class="btn btn-primary" href="/bms/doc/excel/GangWeiMingXi_CheXiWuGuan_20121220.xlsx">下载</a>
      </div>
    </div>

    <div id="formViewModalJXCFJY" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="">岗位明细_车系无关班组</h3>
        </div>
      <div class="modal-body">
        <img src="/bms/doc/browse/managementSystem/standardFrom/JiXiaoChuFaJianYi_view.jpg" alt="">
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a class="btn btn-primary" href="/bms/doc/excel/JiXiaoChuFaJianYi_20130123.xlsx">下载</a>
      </div>
    </div>

    <div id="formViewModalCAR1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="">纠正预防措施报告-模板1</h3>
        </div>
      <div class="modal-body">
        <img src="/bms/doc/browse/managementSystem/standardFrom/CorrectiveActionReport-model_1_view.jpg" alt="">
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a class="btn btn-primary" href="/bms/doc/excel/CorrectiveActionReport-model_1.xlsx">下载</a>
      </div>
    </div>
    

    <div id="formViewModalCAR2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="">纠正预防措施报告-模板2</h3>
        </div>
      <div class="modal-body">
        <img src="/bms/doc/browse/managementSystem/standardFrom/CorrectiveActionReport-model_2_view.jpg" alt="">
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a class="btn btn-primary" href="/bms/doc/excel/CorrectiveActionReport-model_2.xlsx">下载</a>
      </div>
    </div>

    <div id="formViewModalCAR3" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 id="">纠正预防措施报告-模板3</h3>
        </div>
      <div class="modal-body">
        <img src="/bms/doc/browse/managementSystem/standardFrom/CorrectiveActionReport-model_3_view.jpg" alt="">
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
        <a class="btn btn-primary" href="/bms/doc/ppt/CorrectiveActionReport-model_3.pptx">下载</a>
      </div>
    </div>
    </body>
</html>
