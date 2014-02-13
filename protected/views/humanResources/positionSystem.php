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
                      <button id="btnAdd" class="btn btn-link btn-xs" rel="tooltip" title="添加岗位" btn-name="add"><i class="fa fa-plus fa-lg"></i></button>
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
<!--               <div class="col-sm-8">
                <div class="panel panel-primary">
                  <div class="panel-heading">
                    <div id="currentPositionBtnGroup" class="btn-group btn-group-xs">
                      <button class="btn btn-link btn-xs" rel="tooltip" title="编辑"  btn-name="edit"><i class="fa fa-edit fa-lg"></i></button>
                      <button class="btn btn-link btn-xs" rel="tooltip" title="打印" btn-name="print"><i class="fa fa-print fa-lg"></i></button>
                    </div>
                    <span class="current-position-name">工段长</span>
                  </div>
                  <div class="panel-body current-position-description">
                    <dl>
                      <dt>岗位编号</dt><dd>AS-TA-SL</dd>
                      <dt>岗位名称</dt><dd>工段长</dd>
                      <dt>英文名</dt><dd>Section Leader/SL</dd>
                      <dt>岗位等级</dt><dd>E3</dd>
                      <dt>职位描述</dt>
                      <dd>
                        <div class="current-potition-detail">
                          <br>
                          1.Operatively responsible so that products and services within the assigned area are performed and delivered in time, within specification, using the right amount of resources regarding material, machines, personnel and taking care of the environment.能够在分配的区域内执行和及时交付产品和服务，并能在规范内考虑到材料、机器、人员和环境情况，正确使用资源数量。<br><br>
                          2. Secure that the team understands the direction (mission, vision, strategy, targets, rules, safety laws and regulations).确保团队成员了解工作方向（包括使命、愿景、战略、目标、规则、安全法律和法规等）。<br><br>
                          3. Act according to and communicate the company philosophy and Assembly  Manufacturing’s principles.按照公司的沟通原则和总装工厂制造原则（AMS）来执行和沟通。<br><br>
                          4. Perform all first line manager tasks concerning personnel and finance management.执行第一线经理有关人事和财务管理方面的任务。<br><br>
                          5. Responsible for the target setting process of the assigned teams.对指定团队的目标设置过程负责。<br><br>
                          6. Follow up results in the own teams and help prioritizing when necessary.对自己团队工作结果的跟进，并在必要时优先帮助和处理。<br><br>
                          7. Works actively with cost awareness mind-set within the assigned teams.在指定团队工作积极并具有成本意识。<br><br>
                          8. Secures that laws, regulations and procedures are followed.确保法律、法规和过程的最终落实。<br><br>
                          9. Ensure right manning on short and long term.
                          保证在长期和短期的人员配备是正确的。<br><br>
                          10. Actively contribute to the department activity plan.积极参与部门的活动计划。<br><br>
                          11. Responsible for AMS implementation by following up the result and train the team members in methods and tools.
                          通过跟踪结果和使用恰当方法及工具培训团队成员来实施AMS系统。<br><br>
                          12. Supervisor is the primary coach for the assigned team leaders and team members.主管指定团队的领导和团队成员。<br><br>
                          13. Coaches and supports the assigned teams and makes sure they have the right conditions and prerequisites for their job.指导和支持指定团队并确保他们有正确的先决条件完成工作。<br><br>
                          14. It is the Supervisors responsibility to assure that the operators can do their job with the right quality and speed.主管要负责保证操作员工能够以正确的质量和速度完成工作。<br><br>
                          15. It is the Supervisors responsibility to secure back-up of the team leader in case of absence.在班长缺席时，主管要替代班长履行职责。<br><br>
                          16. Secures the right competence for the team leader and the team.确保班组长和班组成员具有正确的能力。<br><br>
                          17. Secures that everybody contributes to the team’s performance, according to the Autonomous Management team concept.根据团队自治管理的理念要确保团队中的每个人都能为团队绩效有所贡献。<br><br>
                          18. Ensure a good communication and co-operation with and between the team members, other teams and the relevant support functions.确保团队成员之间、本团队与其他团队及相关支持部门间的良好沟通和合作。<br><br>
                          19. Support the team leaders in their First Line Analysis work.协助班组长的一线分析工作。<br><br>
                          20. Provides framework for teams to generate ideas and improvements. Assists team with implementation of ideas where appropriate.为团队提供思想和改进的框架，当合适的时候协助团队成员将想法变成实现。<br><br>
                          21. Makes sure the problems that occur within his/her teams are taken care of.确保团队内有问题发生时，团队能完全解决。<br><br>
                          22. Approve the non-operational tasks assigned to the team and make sure that they contribute to help the team carry out their operational tasks more lean and efficient.批准分配给团队的非经营性任务和确保他们能帮助团队更精简和高效执行其操作任务。<br><br>
                        </div>
                      </dd>
                      <dt>任职要求</dt>
                      <dd>
                        <div class="current-potition-qualification">
                          学历：<br>
                          University degree or equivalent.
                          <br>
                          大学本科及同等学力
                          <br>
                          专业/经验：
                          <br>
                          Over 3 years of production management experience in automotive or manufacture industry.
                          <br>
                          三年以上汽车或制造业生产管理经验
                        </div>
                      </dd>
                    </dl>
                  </div>
                </div>
              </div> -->
            </div>
        </div>
    </div>
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