define([
  'app',
  'hr/ServiceStaff',
  'hr/ModalApprovalRecord',
], function (app) {
  app.registerController('CtrlStaffQueryInfo', [
    '$scope',
    '$rootScope',
    '$filter',
    '$window',
    'Staff',
    'CModal',
    'ModalApprovalRecord',
  function ($scope, $rootScope, $filter, $window, Staff, CModal, ModalApprovalRecord) {
    $scope.checkPagePrivilage('HR_QUERY');
    $rootScope.appState = 'hr';
    //员工信息
    Staff.initQueryInfo($scope);

    $scope.btnQuery = function () {
      if ($scope.query.employee) {
        Staff.queryStaffInfo($scope);
      }
    };

    $scope.approvalRecord = function (transferId) {
      ModalApprovalRecord.show({
        transferId: transferId
      });
    };
  }]);
});
