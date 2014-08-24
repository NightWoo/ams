<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>入职</title>
	<link rel="stylesheet" href="/bms/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/bms/vendor/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/bms/src/css/common.css">
  <link rel="stylesheet" href="/bms/src/css/humanResources/staffAdd.css">
  <script type="text/javascript" src="/bms/js/datePicker/WdatePicker.js"></script>
</head>
<body>
  <header class="navbar navbar-fixed-top navbar-default" role="banner">
  	<?php require_once(dirname(__FILE__)."/../common//header/navMaster.php"); ?>
  </header>
  <div id="bodyMain" class="body-main">
    <div class="container">
      <legend>入职</legend>
      <h4>基本信息</h4>
      <div class="form-horizontal" role="form">
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>姓名</label>
          <div class="col-sm-4">
            <input class="form-control" type="text" id="staffName" placeholder="请输入姓名">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>个人联系电话</label>
          <div class="col-sm-4">
            <input class="form-control" type="text" id="phone" placeholder="请输入联系电话">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>性别</label>
          <div class="col-sm-4">
            <label class="radio-inline">
              <input type="radio" name="gender" value="0" checked>男
            </label>
            <label class="radio-inline">
              <input type="radio" name="gender" value="1">女
            </label>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>身份证号</label>
          <div class="col-sm-4">
            <input class="form-control" type="text" id="idNumber" placeholder="请输入身份证号">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>籍贯</label>
          <div class="col-sm-2">
            <select class="form-control" id="province">
              <option value="0" disabled>-- 省份 --</option>
            </select>
          </div>
          <div class="col-sm-2">
            <select class="form-control" id="city">
              <option value="" disabled>-- 城市 --</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>学历</label>
          <div class="col-sm-4">
            <select class="form-control" id="education">
              <option value="初中">初中</option>
              <option value="中专">中专</option>
              <option value="高中">高中</option>
              <option value="大专">大专</option>
              <option value="本科">本科</option>
              <option value="硕士">硕士</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>专业</label>
          <div class="col-sm-4">
            <input class="form-control" type="text" id="major" placeholder="请输入专业">
          </div>
          <div class="col-sm-4 row">
            <p class="form-control-static form-hint">大专以下可不填写</p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>学校</label>
          <div class="col-sm-4">
            <input class="form-control" type="text" id="school" placeholder="请填写学校">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>紧急联系人姓名</label>
          <div class="col-sm-4">
            <input class="form-control" type="text" id="emergencyContact" placeholder="请输入紧急联系人姓名">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>紧急联系人电话</label>
          <div class="col-sm-4">
            <input class="form-control" type="text" id="emergencyPhone" placeholder="请输入紧急联系人电话">
          </div>
        </div>
      </div>
      <h4>入职信息</h4>
      <div class="form-horizontal" role="form">
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>工号</label>
          <div class="col-sm-4">
            <input class="form-control" type="text" id="employeeNumber" placeholder="请输入工号">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>科室/班/组</label>
          <div class="col-sm-4">
            <div class="row">
              <div class="col-sm-4">
                <select class="form-control" id="orgLevel1">
                  <option value="">- 科室 -</option>
                </select>
              </div>
              <div class="col-sm-4">
                <select class="form-control" id="orgLevel2">
                  <option value="">- 班 -</option>
                </select>
              </div>
              <div class="col-sm-4">
                <select class="form-control" id="orgLevel3">
                  <option value="">- 组 -</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>岗位</label>
          <div class="col-sm-2">
            <select class="form-control" id="grade">
              <option value="">岗位等级</option>
            </select>
          </div>
          <div class="col-sm-2">
            <select class="form-control" id="position">
              <option value="">岗位名称</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>底薪</label>
          <div class="col-sm-4">
            <input class="form-control" type="text" id="basicSalary" placeholder="请输入底薪">
          </div>
          <div class="col-sm-4 row">
            <p class="form-control-static form-hint">必须为数字，如2500.00</p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="require-mark">*</span>入职日期</label>
          <div class="col-sm-4">
            <input class="form-control" type="text" id="enterDate" placeholder="请选择入职日期" onClick="WdatePicker({el:'enterDate',dateFmt:'yyyy-MM-dd'});">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">上岗日期</label>
          <div class="col-sm-4">
            <input class="form-control" type="text" id="startDate" placeholder="请输入上岗日期" onClick="WdatePicker({el:'startDate',dateFmt:'yyyy-MM-dd'});">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">工作邮箱</label>
          <div class="col-sm-4">
            <input class="form-control" type="text" id="email" placeholder="请输入工作邮箱">
          </div>
        </div>
      </div>
      <h4>工作经历</h4>
      <table class="table mb10">
        <thead>
          <tr>
            <th>&nbsp;#&nbsp;</th>
            <th class="col-sm-2">开始日期</th>
            <th class="col-sm-2">结束日期</th>
            <th class="col-sm-8">叙述</th>
          </tr>
        </thead>
        <tbody id="career" class="exp-tbody">
        </tbody>
      </table>
      <button class="btn btn-success ml8 mb10" id="addCareer"><span class="glyphicon glyphicon-plus"></span>&nbsp;添加工作经历</button>
      <h4>教育/培训经历</h4>
      <table class="table mb10">
        <thead>
          <tr>
            <th>&nbsp;#&nbsp;</th>
            <th class="col-sm-2">开始日期</th>
            <th class="col-sm-2">结束日期</th>
            <th class="col-sm-8">叙述</th>
          </tr>
        </thead>
        <tbody id="training" class="exp-tbody">
        </tbody>
      </table>
      <button class="btn btn-success ml8 mb10" id="addTraining"><span class="glyphicon glyphicon-plus"></span>&nbsp;教育/培训经历</button>
      <div class="save-wrap text-center">
        <button class="btn btn-primary btn-save">提交员工信息</button>
      </div>
    </div>
  </div>
</body>
<script data-main="/bms/src/js/humanResources/staffAdd.js" src="/bms/vendor/requirejs/require.js"></script>
</html>