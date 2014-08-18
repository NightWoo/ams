define([
  'app',
  'hr/ServiceStaff'
], function (app) {
  app.registerController('CtrlStaffTransfer', [
    '$scope',
    '$rootScope',
    '$filter',
    'Staff',
    'CModal',
  function ($scope, $rootScope, $filter, Staff, CModal) {
    $scope.checkPagePrivilage('HR_TRANSFER');
    $rootScope.appState = 'hr';
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
          CModal.success({
            content: '调岗提交成功'
          }).then(function () {
            Staff.setTransferData($scope, data);
          });
        }
      });
    };

    $scope.submitApproval = function () {
      var submitData = {
        approvalForm: $scope.curApproval
      };
      if ($scope.curApproval.transferDate && $scope.curApproval.transferDate.val) {
        submitData.transferDate = $filter('date')($scope.curApproval.transferDate.val, 'yyyy-MM-dd');
      }
      Staff.transferApprove(submitData).success(function (response) {
        var data = {};
        data.basicInfo = $scope.basicInfo;
        data.applyInfo = $scope.applyInfo;
        data.applyInfo.transfer_date = submitData.transferDate;
        data.approvalRecords = response.data;
        CModal.success({
          content: '审批提交成功'
        }).then(function () {
          Staff.setTransferData($scope, data);
        });
      });
    };
  }]);
});
