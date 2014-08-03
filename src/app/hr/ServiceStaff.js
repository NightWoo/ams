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
        scope.formStaff.$setPristine();
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
        scope.basic = {};  //员工基础信息
        scope.applyInfo = {} //调动岗位数据
        scope.apply = {}; //申请岗位表单数据
        scope.approvalRecords = [];

        //科室/班/组 下拉
        getOrg(scope);
        //岗位 下拉
        getGradePosition(scope);
      },
      getTransferInfo: function (scope) {
        StaffHttp.getTransferInfo({
          employeeNumber: scope.employeeNumber
        }).success(function (response) {
          if (response.success) {
            scope.basicInfo = response.data.basicInfo;
            scope.applyInfo = response.data.applyInfo;
            scope.approvalRecords = response.data.approvalRecords;
            if (scope.approvalRecords.length && ~~scope.approvalRecords[0].conclusion === -1) {
              scope.curApproval = angular.copy(scope.approvalRecords[0]);
              scope.approvalRecords.shift(0);
            }
          }
        });
      },
      transferApply: function (paramObj) {
        return StaffHttp.transferApply(paramObj);
      },
      transferApprove: function (paramObj) {
        return StaffHttp.transferApprove(paramObj);
      }
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
      scope.expTypes = [
        {type: 'career', title: '工作经历', expArr: []},
        {type: 'training', title: '教育/培训经历', expArr: []}
      ];
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
      scope.levels = [
        {levelName: '工厂'},
        {levelName: '科室'},
        {levelName: '班'},
        {levelName: '组'}
      ];
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
  }]);
});
