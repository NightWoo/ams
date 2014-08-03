define([
  'app',
  'hr/ServiceStaff'
], function (app) {
  app.registerController('CtrlStaffTransfer', [
    '$scope',
    '$filter',
    'Staff',
  function ($scope, $filter, Staff) {
    Staff.initTransfer($scope);

    $scope.query = function () {
      Staff.getTransferInfo($scope);
    };

    $scope.orgChanged = function (level) {
      Staff.orgClear($scope, level);
    };

    $scope.gradeChanged = function () {
      $scope.basicInfo.position_id = $scope.positions && $scope.positions[0].id;
    };

    $scope.submitApply = function () {
      $scope.apply.apply_dept_id = $scope.org[3].id || $scope.org[2].id || $scope.org[1].id;
      Staff.transferApply({
        staffId: parseInt($scope.basicInfo.id),
        applyForm: $scope.apply
      }).success(function (response) {
        if (response.success) {
          console.log(response.data);
        }
      });
    };

    $scope.submitApproval = function () {
      Staff.transferApprove({
        approvalForm: $scope.curApproval,
        transferDate: $filter('date')($scope.curApproval.transferDate.val, 'yyyy-MM-dd')
      }).success(function (response) {
        console.log(response.data);
      });
    };
  }]);
});
