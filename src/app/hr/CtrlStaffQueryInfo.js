define([
  'app',
  'hr/ServiceStaff',
], function (app) {
  app.registerController('CtrlStaffQueryInfo', [
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
    Staff.initQueryInfo($scope);

    $scope.btnQuery = function () {
      if ($scope.query.employee) {
        Staff.queryStaffInfo($scope);
      }
    };
  }]);
});
