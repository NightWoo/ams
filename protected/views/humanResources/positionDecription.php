<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>岗位说明书</title>
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
            <legend id="leg">岗位说明书</legend>
            <div class="row">
              <div class="col-sm-12">
                <div class="panel panel-primary">
                  <div class="panel-heading">
                    <div id="currentPositionBtnGroup" class="btn-group btn-group-xs">
                      <!-- <button class="btn btn-link btn-xs" rel="tooltip" title="编辑"  btn-name="edit"><i class="fa fa-edit fa-lg"></i></button> -->
                      <button class="btn btn-link btn-xs" rel="tooltip" title="打印" btn-name="print"><i class="fa fa-print fa-lg"></i></button>
                    </div>
                    <span class="current-position-name" id="positionId"  data-position-id=<?php echo $positionId ?> position-detail="display_name">-</span>
                  </div>
                  <div class="panel-body current-position-description" id="descriptionPrintContent">
                    <dl>
                      <dt>岗位编号</dt><dd position-detail="position_number">-</dd>
                      <dt>岗位名称</dt><dd position-detail="display_name">-</dd>
                      <dt>英文名</dt><dd><span position-detail="name">-</span>&nbsp;/&nbsp;<span position-detail="short_name">-</span></dd>
                      <dt>岗位等级</dt><dd position-detail="grade">-</dd>
                      <dt>职位描述</dt>
                      <dd>
                        <br>
                        <div class="current-potition-detail" position-detail="description"></div>
                        <br>
                      </dd>
                      <dt>任职要求</dt>
                      <dd>
                        <div class="current-potition-qualification">
                          <div>学历：</div>
                          <div position-detail="education"></div>
                          <br>
                          <div>专业/经验：</div>
                          <div position-detail="experiences"></div>
                        </div>
                      </dd>
                    </dl>
                  </div>
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
</body>
<script data-main="/bms/src/js/humanResources/positionDescription.js" src="/bms/vendor/requirejs/require.js"></script>
</html>