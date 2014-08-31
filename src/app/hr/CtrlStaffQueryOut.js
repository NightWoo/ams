define([
  'app',
  'hr/ServiceStaff',
], function (app) {
  app.registerController('CtrlStaffQueryOut', [
    '$scope',
    '$rootScope',
    '$filter',
    '$window',
    'Staff',
    'CModal',
  function ($scope, $rootScope, $filter, $window, Staff, CModal) {
    $scope.checkPagePrivilage('HR_QUERY');
    $rootScope.appState = 'hr';
    //员工信息
    Staff.initQueryOut($scope);

    $scope.orgChanged = function (level) {
      Staff.orgClear($scope, level);
      Staff.resetStaffList($scope);
      Staff.resetQueryTabs($scope);
    };

    $scope.resetStaffList = function () {
      Staff.resetStaffList($scope);
      Staff.resetQueryTabs($scope);
    };

    $scope.tabQuery = function (tab) {
      var
        query = $scope.query,
        startDate = query.startDate && $filter('date')(query.startDate.val, 'yyyy-MM-dd'),
        endDate = query.endDate && $filter('date')(query.endDate.val, 'yyyy-MM-dd');
      if (!startDate || !endDate) {
        CModal.alert({
          content: '起始时间不可为空，且格式须为XXXX-XX-XX'
        });
        return false;
      }
      Staff.resetQueryTabs($scope);
      tab.selected = true;
      $scope.curQueryKey = tab.queryKey;
      doQuery($scope.curQueryKey);
    };

    $scope.exportStaffList = function () {
      var postData = queryData();
      var conditionsJson = angular.toJson(postData.conditions);
      var url = '/bms/staff/exportStaffList?isResigned=1&conditions=' + conditionsJson;
      $window.open(url, '_blank');
    };

    $scope.$watch( "pager.pageNumber", function ( newValue, oldValue ){
      if ( newValue && newValue !== oldValue ) {
        doQuery($scope.curQueryKey);
      }
    });

    $scope.selectAnalysis = function (analysisKey) {
      $scope.curAnalysis = analysisKey;
    };

    function doQuery(queryKey) {
      var postData = queryData();
      Staff[queryKey]($scope, postData);
    }

    function queryData() {
      var
        query = $scope.query,
        org = $scope.org,
        data = {
          employee: query.employee,
          conditions: {
            isResigned: true,
            startDate: query.startDate && $filter('date')(query.startDate.val, 'yyyy-MM-dd'),
            endDate: query.endDate && $filter('date')(query.endDate.val, 'yyyy-MM-dd'),
            gradeId: query.grade,
            staffGrade: query.staffGrade,
            deptId: (org[3] && org[3].id) || (org[2] && org[2].id) || (org[1] && org[1].id),
            countLevel: (org[2] && org[2].id &&  3) || (org[1] && org[1].id && 2) || 1
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
