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

    $scope.query = function () {
    };

    //pagenation
    $scope.pager = {
      pageSize: 10,
      pageSizeSlots: [10,20,30,50],
      pageNumber: 1,
      totalCount: 0
    };

    //更改分页大小
    $scope.setPageSize = function (size) {
      $scope.pager.pageSize = size;
      resetPageNumber();
    };

    function resetPageNumber() {
      $scope.pager.pageNumber = 1;
    }

  }]);
});
