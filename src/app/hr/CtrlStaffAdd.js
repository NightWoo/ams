define([
  'app',
  'hr/ServiceStaff'
], function (app) {
  app.registerController('CtrlStaffAdd', [
    '$scope',
    '$rootScope',
    '$filter',
    'Staff',
    'CModal',
  function ($scope, $rootScope, $filter, Staff, CModal) {
    $scope.checkPagePrivilage('HR_ADD');
    $rootScope.appState = 'hr';
    Staff.initAdd($scope);

    $scope.query = function () {
      if (!$scope.employeeNumber) {
        return;
      }
      Staff.getEditInfo($scope.employeeNumber).success(function (response) {
        $scope.state.isEdit = true;
        $scope.staff.employee_number = $scope.employeeNumber;
        if (response.success && response.data) {
          var basicInfo = response.data.basicInfo;
          var exp = response.data.exp;
          if (basicInfo.id) {
            $scope.staffId = basicInfo.id;
            $scope.basicInfo = basicInfo;
            setBasicData(basicInfo);
            if (exp.length) {
              setExpData(exp);
            }
          }
        }
      });
    };

    $scope.reset = function () {
      Staff.resetAdd($scope);
      $scope.formStaff.$setPristine();
    };

    $scope.proviceChanged = function () {
      $scope.cities = $scope.provinceSelected.cities;
      $scope.staff.native_city_id = $scope.cities[0].id;
    };

    $scope.orgChanged = function (level) {
      Staff.orgClear($scope, level);
    }

    $scope.gradeChanged = function () {
      $scope.positions = $scope.gradeSeclect;
      if ($scope.positions) {
        $scope.staff.position_id = $scope.positions && $scope.positions[0].id;
      }
    };

    $scope.addExp = function (expType) {
      Staff.addExp(expType);
    };

    $scope.removeExp = function (expArr, index) {
      Staff.removeExp(expArr, index);
    };

    $scope.saveStaff = function () {
      if (!$scope.staffId) {
        $scope.staff.enter_date = $filter('date')($scope.staffForm.enterDate.val, 'yyyy-MM-dd');
        $scope.staff.start_date = $filter('date')($scope.staffForm.startDate.val, 'yyyy-MM-dd');
        $scope.staff.dept_id = $scope.org[3].id || $scope.org[2].id || $scope.org[1].id;
      } else {
        $scope.staff.staff_grade = null;
      }
      Staff.save({
        staffId: $scope.staffId,
        staffData: $scope.staff,
        expData: packExpData()
      }).success(function (response) {
        if (response.success) {
          $scope.reset();
          CModal.success({
            content: '员工信息提交成功'
          });
        }
      });
    };

    function packExpData() {
      var data = [];
      for (var i = $scope.expTypes.length - 1; i >= 0; i--) {
        var expArr = $scope.expTypes[i].expArr;
        for (var j = expArr.length - 1; j >= 0; j--) {
          if (expArr[j].start_date.val && expArr[j].end_date.val) {
            data.push({
              id: expArr[j].id,
              type: expArr[j].type,
              start_date: $filter('date')(expArr[j].start_date.val, 'yyyy-MM-dd'),
              end_date: $filter('date')(expArr[j].end_date.val, 'yyyy-MM-dd'),
              description: expArr[j].desc.join('/')
            });
          }
        }
      }
      return data;
    }

    function setBasicData(basicInfo) {
      var staff = $scope.staff;
      staff.name = basicInfo.name;
      staff.contact_phone = basicInfo.contact_phone;
      staff.gender = basicInfo.gender;
      staff.id_number = basicInfo.id_number;
      selectProvice(basicInfo.province_id);
      staff.native_city_id = basicInfo.native_city_id;
      staff.education = basicInfo.education;
      staff.major = basicInfo.major;
      staff.school = basicInfo.school;
      staff.emergency_contact = basicInfo.emergency_contact;
      staff.emergency_phone = basicInfo.emergency_phone;
      staff.email = basicInfo.email;
      staff.remark = basicInfo.remark;
    }

    function setExpData(exp) {
      var typeIndex = {
        career: 0,
        training: 1
      };
      for (var i = $scope.expTypes.length - 1; i >= 0; i--) {
        $scope.expTypes[i].expArr = [];
      };
      for (var i = exp.length - 1; i >= 0; i--) {
        var index = typeIndex[exp[i]['type']];
        // var startDate = new Date(exp[i].start_date);
        // var endDate = new Date(exp[i].end_date);
        exp[i].start_date = {
          val: new Date(exp[i].start_date),
        };
        exp[i].end_date = {
          val: new Date(exp[i].end_date),
        };
        exp[i].desc = exp[i].description.split('/');
        $scope.expTypes[index].expArr.push(exp[i]);
      };
    }

    function selectProvice(provinceId) {
      var cities = {};
      for (var i = $scope.provinces.length - 1; i >= 0; i--) {
        if (~~$scope.provinces[i].id === ~~provinceId) {
          $scope.provinceSelected = $scope.provinces[i];
          $scope.proviceChanged();
        }
      };
    }
  }]);
});
