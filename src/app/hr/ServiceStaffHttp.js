define([
  'app',
], function (app) {
  app.registerFactory('StaffHttp', [
    '$http',
    'transformRequestAsFormPost',
  function ($http, transformRequestAsFormPost) {
    return {
      save: function (paramObj) {
        return $http({
          method: 'post',
          url: '/bms/staff/saveStaff',
          transformRequest: transformRequestAsFormPost,
          data: paramObj
        });
      },
      getProvinceCity: function () {
        return $http({
          method: 'get',
          url: '/bms/staff/getProvinceCityList'
        });
      },
      getDeptList: function () {
        return $http({
          method: 'get',
          url: '/bms/orgStructure/get3LevelList'
        });
      },
      getGradePosition: function () {
        return $http({
          method: 'get',
          url: '/bms/positionSystem/getGradePositionList'
        });
      },
      getTransferInfo: function (paramObj) {
        return $http({
          method: 'get',
          url: '/bms/staff/queryTansferInfo',
          params: paramObj
        });
      },
      transferApply: function (paramObj) {
        return $http({
          method: 'post',
          url: '/bms/staff/applyTransfer',
          transformRequest: transformRequestAsFormPost,
          data: paramObj
        });
      },
      transferApprove: function (paramObj) {
        return $http({
          method: 'post',
          url: '/bms/staff/submitApproval',
          transformRequest: transformRequestAsFormPost,
          data: paramObj
        });
      }
    };
  }]);
});
