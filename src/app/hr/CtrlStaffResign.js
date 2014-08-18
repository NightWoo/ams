define([
  'app',
  'hr/ServiceStaff'
], function (app) {
  app.registerController('CtrlStaffResign', [
    '$scope',
    '$rootScope',
    '$filter',
    'Staff',
    'CModal',
  function ($scope, $rootScope, $filter, Staff, CModal) {
    $scope.checkPagePrivilage('HR_RESIGN');
    $rootScope.appState = 'hr';
    Staff.initResign($scope);

    $scope.query = function () {
      Staff.getBasicInfo($scope);
    };

    $scope.submitResign = function () {
      $scope.resign.reason = $scope.temp.reasons.join(',') || '其他';
      $scope.resign.date = $filter('date')($scope.temp.regignDate.val, 'yyyy-MM-dd');
      Staff.resignSubmit({
        staffId: $scope.basicInfo.id,
        resignForm: {
          resign: $scope.resign,
          resignSurvey: $scope.resignSurvey
        }
      }).success(function (response) {
        if (response.success) {
          $scope.employeeNumber = '';
          Staff.initResign($scope);
          $scope.formResign.$setPristine();
          CModal.success({
            content: '离职提交成功'
          });
        }
      });
    }
  }]);
});
