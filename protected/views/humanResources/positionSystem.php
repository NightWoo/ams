<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>岗位体系</title>
	<link rel="stylesheet" href="/bms/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/bms/vendor/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/bms/src/css/common.css">
  <link rel="stylesheet" href="/bms/src/css/humanResources/positionSystem.css">
</head>
<body>
    <header class="navbar navbar-fixed-top navbar-default" role="banner">
    	<?php require_once(dirname(__FILE__)."/../common//header/navMaster.php"); ?>
    </header>
    <div id="bodyMain" class="body-main">
        <div class="container">
            <legend id="leg">岗位体系</legend>
            <div class="row">
              <div class="col-sm-9" style="height:680px">
                <div id="PyramidSelection" class="panel panel-default">
                  <div class="panel-heading">
                    <span class="current-position-name">通道</span>
                  </div>
                  <div id="panelPositionPyramid" class="panel-body">

                    <div class="row">
                      <div class="col-sm-2">
                        <div id="positionLevel">
                          <div class="position-level" level="1">D</div>
                          <div class="position-level" level="2">E</div>
                          <div class="position-level" level="3">F</div>
                          <div class="position-level sub-level" level="4">G3</div>
                          <div class="position-level sub-level" level="4">G2</div>
                          <div class="position-level sub-level" level="4">G1</div>
                          <div class="position-level sub-level" level="5">H3</div>
                          <div class="position-level sub-level" level="5">H2</div>
                          <div class="position-level sub-level" level="5">H1</div>
                          <div class="position-level" level="6">I2</div>
                        </div>
                      </div>
                      <div class="col-sm-10">
                        <div id="channelLabels" class="pull-right">
                          <h4><span class="label label-primary">管理</span></h4>
                          <h4><span class="label label-success">技术专家</span></h4>
                          <h4><span class="label label-warning">技能</span></h4>
                        </div>
                        <div id="positionPyramid">
                          <a href="#" class="pyramid-grade pyramid-triangle grade-mg" level="1" channel="管理"><span class="pyramid-grade-text">经理</span></a>
                          <a href="#" class="pyramid-grade grade-chief" level="2" channel="管理"><span class="pyramid-grade-text">科长</span></a>
                          <a href="#" class="pyramid-grade grade-high-engineer" level="2"  channel="技术专家"><span class="pyramid-grade-text line-2">高级<br>工程师</span></a>
                          <a href="#" class="pyramid-grade grade-engineer" level="3"  channel="技术专家"><span class="pyramid-grade-text">工程师</span></a>
                          <a href="#" class="pyramid-grade grade-coach grade-coach-3" level="3"  channel="管理"><span class="pyramid-grade-text">三级<br>指导员</span></a>
                          <a href="#" class="pyramid-grade grade-engineer-as" level="4"  channel="技术专家"><span class="pyramid-grade-text">助理工程师</span></a>
                          <a href="#" class="pyramid-grade grade-coach grade-coach-2" level="4" channel="管理"><span class="pyramid-grade-text">二级<br>指导员</span></a>
                          <a href="#" class="pyramid-grade pyramid-triangle grade-technician" level="4"  channel="技能"><span class="pyramid-grade-text">技师<br>初/中/高</span></a>
                          <a href="#" class="pyramid-grade grade-mechanic" level="5" channel="技能"><span class="pyramid-grade-text">技工<br>初/中/高</span></a>
                           <a href="#" class="pyramid-grade grade-coach grade-coach-1" level="5" channel="管理"><span class="pyramid-grade-text">一级<br>指导员</span></a>
                            <a href="#" class="pyramid-grade grade-worker" level="6" channel="技能"><span class="pyramid-grade-text">普工</span></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <div class="btn-group btn-group-xs">
                      <button id="btnAdd" class="btn btn-link btn-xs" rel="tooltip" title="添加岗位" btn-name="add" style="display: none"><i class="fa fa-plus fa-lg"></i></button>
                    </div>
                    <span class="current-position-name">岗位</span>
                  </div>
                  <ul id="positionList" class="list-group">
                  </ul>
                  <script id="tmplPositionList" type="text/x-jsrander">
                    <li class="list-group-item" data-position-id={{:id}}>
                      <div class="btn-group btn-group-xs">
                        <button class="btn btn-link btn-xs" rel="tooltip" title="编辑" btn-name="edit"><i class="fa fa-edit fa-lg"></i></button>
                        <button class="btn btn-link btn-xs" rel="tooltip" title="移除" btn-name="remove" data-display-name={{:display_name}}><i class="fa fa-trash-o fa-lg"></i></button>
                      </div>
                      <a href="#">{{:display_name}}({{:short_name}})</a>
                    </li>
                  </script>
                </div>
              </div>
            </div>
        </div>
    </div>
    <footer class="navbar-inverse  navbar-fixed-bottom" role="banner">
        <style type="text/css" media="screen">
            body {padding-bottom: 70px;}
        </style>
        <?php require_once(dirname(__FILE__)."/../common/footer/navQueryHR.php"); ?>
    </footer>
    <!-- edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="editModalLabel">岗位<span id="titleSuffix"></span></h4>
          </div>
          <div class="modal-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
              <li id="liDefinition" class="active"><a href="#paneDefinition" data-toggle="tab">定义</a></li>
              <li id="liDescription"><a href="#paneDescription" data-toggle="tab">描述</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content" id="editTabConent">
              <div class="tab-pane fade in active" id="paneDefinition">
                <form class="form-horizontal" role="form">
                  <div class="form-group">
                    <label for="inputPositionNumber" class="col-sm-2 control-label">岗位编号</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputPositionNumber" placeholder="岗位编号...">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="selectGrade" class="col-sm-2 control-label">岗位等级</label>
                    <div class="col-sm-10">
                      <select class="form-control" id="selectGrade">
                      </select>
                    </div>
                  </div>
<!--                   <div class="form-group">
                    <label for="selectParent" class="col-sm-2 control-label">岗位上级</label>
                    <div class="col-sm-10">
                      <select class="form-control" id="selectParent">
                      </select>
                    </div>
                  </div> -->
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">中文名</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputDisplayName" placeholder="中文名称...">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">英文名</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputName" placeholder="英文名...">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputShortName" class="col-sm-2 control-label">简称</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputShortName" placeholder="简称...">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="textEducation" class="col-sm-2 control-label">学历</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" id="textEducation" rows="3"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="textExperiences" class="col-sm-2 control-label">专业/经验</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" id="textExperiences" rows="4"></textarea>
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane fade" id="paneDescription">
                <textarea class="form-control" id="textDescription" rows="20"></textarea>
              </div>
            </div>
          </div><!-- /.modal-body -->
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="editCancel">取消</button>
            <button type="button" class="btn btn-primary"  id="editCommit">确定</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</body>
<script data-main="/bms/src/js/humanResources/positionSystem.js" src="/bms/vendor/requirejs/require.js"></script>
</html>