define([
  'app',
], function (app) {
  app.registerFactory('Staff', [
    '$http',
    'transformRequestAsFormPost',
  function ($http, transformRequestAsFormPost) {
    return {
      /**
       * 员工入职 初始化
       * @param  {[type]} scope [description]
       * @return {[type]}       [description]
       */
      initAdd: function (scope) {

        this.resetForm(scope);

        //籍贯 下拉
        this.getProvinceCity().success(function (response) {
          if (response.success) {
            scope.provinces = response.data;
          }
        });

        //科室/班/组 下拉
        scope.levels = [
          {levelName: '工厂'},
          {levelName: '科室'},
          {levelName: '班'},
          {levelName: '组'}
        ];
        scope.org = [];
        this.getDeptList().success(function (response) {
          if (response.success) {
            scope.org[0] = {
              children: response.data
            }
          }
        });

        //岗位 下拉
        this.getGradePosition().success(function (response) {
          if (response.success) {
            scope.grades = response.data;
          }
        });
      },
      resetForm: function (scope) {
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
        //经历
        this.initExp(scope);
      },
      resetAdd: function (scope) {
        this.resetForm(scope);
        scope.formStaff.$setPristine();
      },
      addExp: function (expType) {
        expType.expArr.unshift({
          type: expType.type,
          start_date: '',
          end_date: '',
          description: ''
        });
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
      initExp: function (scope) {
        scope.expTypes = [
          {type: 'career', title: '工作经历', expArr: []},
          {type: 'training', title: '教育/培训经历', expArr: []}
        ];
        for (var i = scope.expTypes.length - 1; i >= 0; i--) {
          this.addExp(scope.expTypes[i]);
        }
      },
      save: function (paramObj) {
        return $http({
          method: 'post',
          url: '/bms/staff/saveStaff',
          transformRequest: transformRequestAsFormPost,
          data: paramObj
        });
      },
      getProvinceCity: function () {
        return $http({
          method: 'get',
          url: '/bms/staff/getProvinceCityList'
        });
      },
      getDeptList: function () {
        return $http({
          method: 'get',
          url: '/bms/orgStructure/get3LevelList'
        });
      },
      getGradePosition: function () {
        return $http({
          method: 'get',
          url: '/bms/positionSystem/getGradePositionList'
        });
      }
    };
  }]);
});
