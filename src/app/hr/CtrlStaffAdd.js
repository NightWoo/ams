define([
  'app',
  'hr/ServiceStaff'
], function (app) {
  app.registerController('CtrlStaffAdd', [
    '$scope',
    '$filter',
    'Staff',
    'CModal',
  function ($scope, $filter, Staff, CModal) {

    Staff.initAdd($scope);

    $scope.proviceChanged = function () {
      $scope.cities = $scope.provinceSelected.cities;
      $scope.staff.native_city_id = $scope.cities[0].id;
    };

    $scope.orgChanged = function (level) {
      Staff.orgClear($scope, level);
    }

    $scope.gradeChanged = function () {
      $scope.staff.position_id = $scope.positions && $scope.positions[0].id;
    };

    $scope.addExp = function (expType) {
      Staff.addExp(expType);
    };

    $scope.removeExp = function (expArr, index) {
      Staff.removeExp(expArr, index);
    };

    $scope.saveStaff = function () {
      $scope.staff.enter_date = $filter('date')($scope.staffForm.enterDate.val, 'yyyy-MM-dd');
      $scope.staff.start_date = $filter('date')($scope.staffForm.startDate.val, 'yyyy-MM-dd');
      $scope.staff.dept_id = $scope.org[3].id || $scope.org[2].id || $scope.org[1].id;
      Staff.save({
        staffData: $scope.staff,
        expData: packExpData()
      }).success(function (response) {
        if (response.success) {
          Staff.resetAdd($scope);
          $scope.formStaff.$setPristine();
          CModal.success({
            content: '入职信息提交成功'
          });
        }
      });
    };

    function packExpData() {
      var data = [];
      for (var i = $scope.expTypes.length - 1; i >= 0; i--) {
        var expArr = $scope.expTypes[i].expArr;
        for (var j = expArr.length - 1; j >= 0; j--) {
          if (expArr[j].start_date.val && expArr[j].end_date.val && expArr[j].description) {
            data.push({
              type: expArr[j].type,
              start_date: $filter('date')(expArr[j].start_date.val, 'yyyy-MM-dd'),
              end_date: $filter('date')(expArr[j].end_date.val, 'yyyy-MM-dd'),
              description: expArr[j].description
            });
          }
        }
      }
      return data;
    }
  }]);
});
