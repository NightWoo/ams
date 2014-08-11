define([
  'app',
  'hr/ServiceStaffHttp'
], function (app) {
  app.registerFactory('Staff', [
    'StaffHttp',
    function (StaffHttp) {
      return {
        /**
         * 员工入职 初始化
         * @param  {[type]} scope [description]
         * @return {[type]}       [description]
         */
        initAdd: function (scope) {
          //表单数据初始化
          resetAddForm(scope);

          //籍贯 下拉
          StaffHttp.getProvinceCity().success(function (response) {
            if (response.success) {
              scope.provinces = response.data;
            }
          });

          //科室/班/组 下拉
          getOrg(scope);
          //岗位 下拉
          getGradePosition(scope);
        },
        resetAdd: function (scope) {
          resetAddForm(scope);
        },
        addExp: function (expType) {
          addExp(expType);
        },
        removeExp: function (expArr, index) {
          expArr.splice(index, 1);
        },
        orgClear: function (scope, level) {
          var length = scope.levels.length;
          for (var i = level + 1; i < length; i++) {
            scope.org[i] = {};
          }
        },
        save: function (paramObj) {
          return StaffHttp.save(paramObj);
        },
        initTransfer: function (scope) {
          scope.basic = {}; //员工基础信息
          scope.applyInfo = {} //调动岗位数据
          scope.apply = {}; //申请岗位表单数据
          scope.state = {}; //各种状态
          scope.approvalRecords = [];

          //科室/班/组 下拉
          getOrg(scope);
          //岗位 下拉
          getGradePosition(scope);

          var Staff = this;
          StaffHttp.getMyApproval().success(function (response) {
            if (response.success) {
              Staff.setTransferData(scope, response.data);
              if (response.data.basicInfo.id) {
                scope.state.hasQueried = true;
              }
            }
          });
        },
        getBasicInfo: function (scope) {
          StaffHttp.getBasicInfo({
            employeeNumber: scope.employeeNumber
          }).success(function (response) {
            if (response.success) {
              scope.basicInfo = response.data;
              scope.state.hasQueried = true;
            }
          });
        },
        getTransferInfo: function (scope) {
          var Staff = this;
          StaffHttp.getTransferInfo({
            employeeNumber: scope.employeeNumber
          }).success(function (response) {
            if (response.success) {
              Staff.setTransferData(scope, response.data);
              scope.state.hasQueried = true;
            }
          });
        },
        transferApply: function (paramObj) {
          return StaffHttp.transferApply(paramObj);
        },
        transferApprove: function (paramObj) {
          return StaffHttp.transferApprove(paramObj);
        },
        setTransferData: function (scope, data) {
          scope.basicInfo = data.basicInfo;
          scope.applyInfo = data.applyInfo;
          scope.approvalRecords = data.approvalRecords;
          scope.curApproval = null;
          if (scope.approvalRecords.length && ~~scope.approvalRecords[0].conclusion === -1) {
            scope.curApproval = angular.copy(scope.approvalRecords[0]);
            scope.approvalRecords.shift(0);
          }

          if (scope.basicInfo.employee_number) {
            scope.employeeNumber = scope.basicInfo.employee_number;
          }
        },
        initResign: function (scope) {
          scope.basicInfo = {}; //员工基础信息
          scope.state = {}; //各种状态
          scope.resignReasons = [
            '薪酬待遇',
            '工作环境',
            '部门管理',
            '和同事的关系',
            '个人因素'
          ];

          scope.resignSurvey = [
            {
              topic: '如果有机会，你是否会再次选择本部门？',
              result: ''
            },
            {
              topic: '你能否对部门管理提出个人建议？',
              result: ''
            }
          ];

          scope.resign = {
            type: '正常离职'
          };

          scope.temp = {
            regignDate: {
              val: new Date()
            },
            reasons: []
          }
        },
        resignSubmit: function (paramObj) {
          return StaffHttp.resignSubmit(paramObj);
        },
        /**
         * 员工库查询 初始化
         * @param  {[type]} scope [description]
         * @return {[type]}       [description]
         */
        initQuery: function (scope) {
          scope.query = {};
          StaffHttp.getGradeList().success(function (response) {
            if (response.success) {
              scope.gradeList = response.data;
            }
          });
          scope.staffGrades = staffGrades();
          //科室/班/组 下拉
          getOrg(scope);
          //岗位 下拉
          getGradePosition(scope);
        },
      };



      function resetAddForm(scope) {
        //员工信息
        scope.staff = {};
        scope.staffForm = {
          enterDate: {
            val: new Date()
          },
          startDate: {
            val: new Date()
          }
        };
        scope.staff.education = '中专';
        scope.staff.staff_grade = 'H2';
        scope.provinceSelected = '';
        //经历
        initExp(scope);
      }

      function initExp(scope) {
        scope.expTypes = [{
          type: 'career',
          title: '工作经历',
          expArr: []
        }, {
          type: 'training',
          title: '教育/培训经历',
          expArr: []
        }];
        for (var i = scope.expTypes.length - 1; i >= 0; i--) {
          addExp(scope.expTypes[i]);
        }
      }

      function addExp(expType) {
        expType.expArr.unshift({
          type: expType.type,
          start_date: '',
          end_date: '',
          description: ''
        });
      }

      function getOrg(scope) {
        //科室/班/组 下拉
        scope.levels = [{
          levelName: '工厂'
        }, {
          levelName: '科室'
        }, {
          levelName: '班'
        }, {
          levelName: '组'
        }];
        scope.org = [];
        StaffHttp.getDeptList().success(function (response) {
          if (response.success) {
            scope.org[0] = {
              children: response.data
            }
          }
        });
      }

      function getGradePosition(scope) {
        StaffHttp.getGradePosition().success(function (response) {
          if (response.success) {
            scope.grades = response.data;
          }
        });
      }

      function staffGrades() {
        return grades = [
          "I1",
          "I2",
          "I3",
          "H1",
          "H2",
          "H3",
          "G1",
          "G2",
          "G3",
          "F1",
          "F2",
          "F3",
          "E1",
          "E2",
          "E3",
          "D1",
          "D2",
          "D3",
        ];
      }
    }
  ]);
});