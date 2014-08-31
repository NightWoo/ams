define([
  'app',
  'hr/ServiceStaffHttp',
  './ServiceStaffQueryCharts',
], function (app) {
  app.registerFactory('Staff', [
    'StaffHttp',
    'StaffQueryCharts',
  function (StaffHttp, StaffQueryCharts) {
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
        getPositionList(scope);
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
      getEditInfo: function (employeeNumber) {
        return StaffHttp.getEditInfo({
          employeeNumber: employeeNumber
        });
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
        getPositionList(scope);

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
            scope.apply.apply_position_id = response.data.basicInfo.position_id;
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
       * 在职查询 初始化
       * @param  {[type]} scope [description]
       * @return {[type]}       [description]
       */
      initQueryIn: function (scope) {
        scope.queryTabs = [
          {queryKey: 'queryStaffList', name: '详细信息'},
          {queryKey: 'queryAnalysisIn', name: '在职分析'},
          // {queryKey: 'queryEduPie', name: '学历分布'},
        ];
        scope.analysisTabs = [
          {key: 'org', name: '科室/班/组'},
          {key: 'grade', name: '岗位等级'},
          {key: 'staffGrade', name: '工资等级'},
          {key: 'edu', name: '学历'},
          {key: 'gender', name: '性别'},
          {key: 'native', name: '籍贯'}
        ];
        scope.chartConfig = {};
        scope.pager = {
          pageSize: 10,
          // pageSizeSlots: [10,20,30,50],
          pageNumber: 1,
          totalCount: 0
        };

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
        //籍贯 下拉
        StaffHttp.getProvinceCity().success(function (response) {
          if (response.success) {
            scope.provinces = response.data;
          }
        });
      },
      /**
       * 离职查询 初始化
       * @param  {[type]} scope [description]
       * @return {[type]}       [description]
       */
      initQueryOut: function (scope) {
        scope.queryTabs = [
          {queryKey: 'queryStaffList', name: '详细信息'},
          {queryKey: 'queryAnalysisOut', name: '离职率分析'},
          // {queryKey: 'queryEduPie', name: '学历分布'},
        ];
        scope.analysisTabs = [
          {key: 'org', name: '科室/班/组'},
          {key: 'trend', name: '时间趋势'},
          {key: 'reason', name: '离职原因'},
        ];
        scope.chartConfig = {};
        scope.pager = {
          pageSize: 10,
          // pageSizeSlots: [10,20,30,50],
          pageNumber: 1,
          totalCount: 0
        };

        var today = new Date();
        scope.query = {
          endDate: {
            val: new Date()
          },
          startDate: {
            val: today.setMonth(today.getMonth()-3)
          },
        };
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
      resetStaffList: function (scope) {
        scope.stafflList = [];
        scope.pager.pageNumber = 1;
        scope.pager.totalCount = 0;
      },
      resetQueryTabs: function (scope) {
        scope.curQueryKey = '';
        for (var i = scope.queryTabs.length - 1; i >= 0; i--) {
          scope.queryTabs[i].selected = false;
        }
      },
      queryStaffList: function (scope, postData) {
        StaffHttp.queryStaffList(postData).success(function (response) {
          if (response.success) {
            scope.stafflList = response.data.result || [];
            scope.pager.totalCount = response.data.total || 0;
          }
        });
      },
      queryAnalysisIn: function (scope, postData) {
        StaffHttp.queryAnalysisIn(postData).success(function (response) {
          if (response.success) {
            scope.chartConfig.orgBar = angular.copy(StaffQueryCharts.basicBar);
            scope.chartConfig.orgBar.series[0].data = response.data.org;

            scope.chartConfig.gradeBar = angular.copy(StaffQueryCharts.basicBar);
            scope.chartConfig.gradeBar.series[0].data = response.data.grade;

            scope.chartConfig.staffGradeBar = angular.copy(StaffQueryCharts.basicBar);
            scope.chartConfig.staffGradeBar.series[0].data = response.data.staffGrade;

            scope.chartConfig.nativeBar = angular.copy(StaffQueryCharts.basicBar);
            scope.chartConfig.nativeBar.series[0].data = scope.query.nativeProvinceId ? response.data.city : response.data.province;

            scope.chartConfig.eduPie = angular.copy(StaffQueryCharts.basicPie);
            scope.chartConfig.eduPie.series[0].data = response.data.edu;

            scope.chartConfig.genderPie = angular.copy(StaffQueryCharts.basicPie);
            scope.chartConfig.genderPie.series[0].data = response.data.gender;

          }
        });
      },
      queryAnalysisOut: function (scope, postData) {
        StaffHttp.queryAnalysisOut(postData).success(function (response) {
          if (response.success) {
            // console.log(response);
            scope.chartConfig.orgBar = angular.copy(StaffQueryCharts.basicBar);
            scope.chartConfig.orgBar.series[0].data = response.data.org;

            scope.chartConfig.colTrend = angular.copy(StaffQueryCharts.basicColTrand);
            scope.chartConfig.colTrend.series[0].data = response.data.trend[0];
            scope.chartConfig.colTrend.series[1].data = response.data.trend[1];

            scope.chartConfig.reasonPie = angular.copy(StaffQueryCharts.basicPie);
            scope.chartConfig.reasonPie.series[0].data = response.data.reason;
          }
        });
      },
      initQueryInfo: function (scope) {
        scope.query = {};
        scope.staff = {}; //员工基础信息
        scope.careerExp = [];
        scope.trainingExp = [];
        scope.state = {}; //各种状态
      },
      queryStaffInfo: function (scope) {
        StaffHttp.queryStaffInfo({
          employee: scope.query.employee
        }).success(function (response) {
          if (response.success) {
            scope.staff = response.data.staff;
            scope.resignSurvey = response.data.resignSurvey;
            scope.careerExp = response.data.careerExp;
            scope.trainingExp = response.data.trainingExp;
            scope.transferRecord = response.data.transferRecord;
            scope.state.hasQueried = true;
          }
        })
      }
    };



    function resetAddForm(scope) {
      //员工信息
      scope.employeeNumber = null;
      scope.staffId = null;
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
      scope.staff.native_city_id = '';
      if (scope.org) {
        for (var i = scope.org.length; i > 0; i--) {
          scope.org[i] = '';
        };
      }

      //经历
      initExp(scope);
      scope.state = {};
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
        desc: []
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

    function getPositionList(scope) {
      StaffHttp.getPositionList().success(function (response) {
        if (response.success) {
          scope.positionList = response.data;
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
  }]);
});