<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>组织架构</title>
	<link rel="stylesheet" href="/bms/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bms/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/bms/src/css/common.css">
    <link rel="stylesheet" href="/bms/vendor/jquery-ui/css/flick/jquery-ui.custom.min.css">
    <link rel="stylesheet" href="/bms/vendor/primitives/primitives.latest.css">
    <link rel="stylesheet" href="/bms/src/css/humanResources/orgStructure.css">
</head>
<body>
    <header class="navbar navbar-fixed-top navbar-default" role="banner">
    	<?php require_once(dirname(__FILE__)."/../common//header/navMaster.php"); ?>
    </header>
    <div class="body-main">
        <div class="container">
            <legend>组织结构</legend>
            <div class="org-level-label">
              <span class="label label-primary">工厂</span>
              <span class="label label-warning">科室</span>
              <span class="label label-success">&nbsp;&nbsp;班&nbsp;&nbsp;</span>
              <span class="label label-info">&nbsp;&nbsp;组&nbsp;&nbsp;</span>
            </div>
            <div id="orgDiagram" style="min-height: 720px;">
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
            <h4 class="modal-title" id="editModalLabel">部门<span id="titleSuffix"></span></h4>
          </div>
          <div class="modal-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
              <li id="liDetailEdit" class="active"><a href="#paneDetailEdit" data-toggle="tab">详情编辑</a></li>
              <li id="liChildrenSort"><a href="#paneChildrenSort" data-toggle="tab">子部门排列</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content" id="editTabConent">
              <div class="tab-pane fade in active" id="paneDetailEdit">
                <form class="form-horizontal" role="form">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">上级部门</label>
                    <div class="col-sm-10">
                      <select class="form-control" id="selectParentDept">
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputDisplayName" class="col-sm-2 control-label">部门名</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputDisplayName" placeholder="部门名...">
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
                    <label for="inputShortName" class="col-sm-2 control-label">主管</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="managerNumber" placeholder="请输入工号...">
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane fade" id="paneChildrenSort">
                <table class="table table-hover" id="tableChildren">
                  <thead>
                    <tr>
                      <th style="width:70px"></th>
                      <th style="width:50px">#</th>
                      <th style="width:80px">简称</th>
                      <th>部门名</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
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
<script data-main="/bms/src/js/humanResources/orgStructure.js" src="/bms/vendor/requirejs/require.js"></script>
</html>