define([
  'app',
  'hr/ServiceStaff'
], function (app) {
  app.registerController('CtrlStaffQuery', [
    '$scope',
    '$filter',
    'Staff',
    'CModal',
  function ($scope, $filter, Staff, CModal) {
    //员工信息
    Staff.initQuery($scope);

    $scope.orgChanged = function (level) {
      Staff.orgClear($scope, level);
    }

    $scope.tabQuery = function (tab) {
      for (var i = $scope.queryTabs.length - 1; i >= 0; i--) {
        $scope.queryTabs[i].selected = false;
      }
      tab.selected = true;
      $scope.curQueryKey = tab.queryKey;
      doQuery($scope.curQueryKey);
    };

    $scope.query = function () {
    };

    $scope.$watch( "pager.pageNumber", function ( newValue, oldValue ){
      if ( newValue && newValue !== oldValue ) {
        doQuery($scope.curQueryKey);
      }
    });

    function doQuery(queryKey) {
      var postData = queryData();
      Staff[queryKey]($scope, postData);
    }

    function queryData() {
      var
        query = $scope.query,
        data = {
          employee: query.employee,
          conditions: {
            gradeId: query.grade,
            staffGrade: query.staffGrade,
            deptId: ($scope.org[3] && $scope.org[3].id) || ($scope.org[2] && $scope.org[2].id) || ($scope.org[1] && $scope.org[1].id),
            includeResigned: query.includeResigned || false
          },
          pager: $scope.pager
        };

      return data;
    }

    function resetPageNumber() {
      $scope.pager.pageNumber = 1;
    }

  }]);
});
