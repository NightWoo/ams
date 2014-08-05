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
          var data = response.data;
          data.basicInfo = $scope.basicInfo;
          Staff.setTransferData($scope, data);
          console.log(response.data);
        }
      });
    };

    $scope.submitApproval = function () {
      var submitData = {
        approvalForm: $scope.curApproval
      };
      if ($scope.curApproval.transferDate && $scope.curApproval.transferDate.val) {
        data.transferDate = $filter('date')($scope.curApproval.transferDate.val, 'yyyy-MM-dd');
      }
      Staff.transferApprove(submitData).success(function (response) {
        var data = {};
        data.basicInfo = $scope.basicInfo;
        data.applyInfo = $scope.applyInfo;
        data.approvalRecords = response.data;
        Staff.setTransferData($scope, data);
        console.log(response.data);
      });
    };
  }]);
});
