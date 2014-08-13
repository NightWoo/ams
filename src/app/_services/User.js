define([
       'app'
  ], function ( app ) {
  app.registerFactory( 'User', [ '$http', function ( $http ) {
    return {
      /**
       * get current user
       * @default pageNo:1 pageSize:10
       * @param  {Object} paramObj pageSize,pageNo
       * @param  {Fuction} cbfn     callback function
       * @return {void}          no return
       */
      getCurrent: function ( paramObj, cbfn ) {
        var defaults = {};
        // todo: plan to do it in interceptor
        // angular.extend( paramObj, { _: new Date().getTime() }, defaults );//ie cache
        $http({
          method: 'get',
          // url: '../jsondata/orgs.json',  //for test data
          url: '/bms/user/getCurrent', //should switch to real url
          params : angular.extend( defaults, paramObj )
        }).success( function ( result ) {
          cbfn( result );
        });
      },
      /**
       * 检查是否有功能点权限
       * @param  {[type]} privilagePoint 功能点权限
       * @return {boolean} permit 是否有权限
       */
      checkPrivilage: function (privilagePoint) {
        var permit = false;
        return $http({
          method: 'get',
          url: '/bms/user/checkPrivilage',
          params: {
            privilagePoint: privilagePoint
          }
        });
      }
    };
  }]);
});