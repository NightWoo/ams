define([
  'app',
  'routeDefs',
  '_services/User',
  '_directives/maxHeightBody',
  '_directives/checklistModel',
  '_services/transformRequestAsFormPost',
  '_common/modal/CModal',
], function (app) {

  app.factory('myHttpInterceptor', [
    '$q',
    '$log',
    '$rootScope',
  function ($q, $log, $rootScope) {
    var errorModalFlag = false;
    return function (promise) {
      return promise.then(function (response) {
        return response;
      }, function (response) {
        // do something on error
        $log.info(response);

        return $q.reject(response);
      });
    };
  }]);

  app.config([
    'routeDefsProvider',
    '$httpProvider',
  function (routeDefsProvider, $httpProvider ) {
    // in large applications, you don't want to clutter up app.config
    // with routing particulars.  You probably have enough going on here.
    // Use a service provider to manage your routing.

    //post header setting to make it work
    $httpProvider.defaults.headers.post = {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With': 'XMLHttpRequest'
    };

    //disable IE ajax request caching
    $httpProvider.defaults.headers.get = $httpProvider.defaults.headers.get || {};
    $httpProvider.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
    $httpProvider.defaults.headers.get['Cache-Control'] = 'no-cache';
  }]);

  app.run([
    '$couchPotato',
    '$state',
    '$stateParams',
    '$rootScope',
  function ($couchPotato, $state, $stateParams, $rootScope) {
    // by assigning the couchPotato service to the lazy property, we
    // the register functions will know to run-time-register components
    // instead of config-time-registering them.
    app.lazy = $couchPotato;

    // angular-ui-project recommends assigning these services to the root
    // scope.  Others have argued that doing so can lead to obscured
    // dependencies and that making services directly available to html and
    // directives is unclean.  In any case, the ui-router demo assumes these
    // are available in the DOM, therefore they should be on $rootScope.
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
  }]);

  app.registerController('CtrlApp', [
    '$scope',
    '$http',
    '$rootScope',
    '$state',
    '$timeout',
    'User',
  function ($scope, $http, $rootScope, $state, $timeout, User) {
    User.getCurrent( {}, function ( response ) {
      //if have not login yet;
      if (typeof response === 'string') {
        window.location = "/bms/login.php";
      }
      $scope.user = response.data;
    });

    $rootScope.checkPagePrivilage = function (privilagePoint) {
      User.checkPrivilage(privilagePoint).success(function (response) {
        if (!(response.success && response.data)) {
          $state.go('error.permissionDenied');
        }
      });
    };
  }]);

});
