define([
  'app',
  'hr/ServiceStaff',
], function (app) {
  app.registerController('CtrlStaffQueryIn', [
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
    Staff.initQueryIn($scope);

    $scope.orgChanged = function (level) {
      Staff.orgClear($scope, level);
      Staff.resetStaffList($scope);
      Staff.resetQueryTabs($scope);
    };

    $scope.proviceChanged = function () {
      $scope.cities = ($scope.provinceSelected && $scope.provinceSelected.cities) || [];
      $scope.query.nativeProvinceId = ($scope.provinceSelected && $scope.provinceSelected.id) || '';
      $scope.query.nativeCityId = '';
      $scope.resetStaffList();
    };

    $scope.changeGrade =  function () {
      if ($scope.query.grade) {
        $scope.query.position = '';
      }
      $scope.resetStaffList();
    }

    $scope.resetStaffList = function () {
      Staff.resetStaffList($scope);
      Staff.resetQueryTabs($scope);
    };

    $scope.tabQuery = function (tab) {
      Staff.resetQueryTabs($scope);
      tab.selected = true;
      $scope.curQueryKey = tab.queryKey;
      doQuery($scope.curQueryKey);
    };

    $scope.exportStaffList = function () {
      var postData = queryData();
      var conditionsJson = angular.toJson(postData.conditions);
      var url = '/bms/staff/exportStaffList';
      if (postData.employee) {
        url = url + '?employee=' + postData.employee;
      } else {
        url = url + '?conditions=' + conditionsJson;
      }
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
      if (Staff[queryKey]) {
        Staff[queryKey]($scope, postData);
      }
    }

    function queryData() {
      var
        query = $scope.query,
        org = $scope.org,
        provinceSelected = $scope.provinceSelected,
        data = {
          employee: query.employee,
          conditions: {
            includeResigned: (query.includeResigned || false),
            gradeId: query.grade,
            position: query.position && query.position.toUpperCase(),
            staffGrade: query.staffGrade,
            deptId: (org[3] && org[3].id) || (org[2] && org[2].id) || (org[1] && org[1].id),
            countLevel: (org[2] && org[2].id &&  3) || (org[1] && org[1].id && 2) || 1,
            gender: (query.gender || -1),
            provinceId: provinceSelected && provinceSelected.id,
            cityId: query.nativeCityId,
            education: query.education,
            major: query.major
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
