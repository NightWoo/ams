define([
       'app',
       './HttpParamSpec'
  ], function ( app ) {
  app.registerFactory( 'Org', [ '$http', 'HttpParamSpec', function ( $http, HttpParamSpec ) {
    return {
      /**
       * get orgnization list
       * @default pageNo:1 pageSize:10
       * @param  {Object} paramObj pageSize,pageNo
       * @param  {Fuction} cbfn     callback function
       * @return {void}          no return
       */
      get: function ( paramObj, cbfn ) {
        // if ( !HttpParamSpec.spec( paramObj, [ 'id' ], cbfn ) ) {
        //   // make sure the wanted parameter(s) pass in
        //   return false;
        // }
        var defaults = {
          pageNo: 1,
          pageSize: 10
        }; // default params for get orgnization list
        // todo: plan to do it in interceptor
        // angular.extend( paramObj, { _: new Date().getTime() }, defaults );//ie cache
        $http({
          method: 'get',
          // url: '../jsondata/orgs.json',  //for test data
          url: '/bms/common/getSeriesList', //should switch to real url
          params : angular.extend( defaults, paramObj )
        }).success( function ( result ) {
          cbfn( result );
        });
      }
    };
  }]);
});