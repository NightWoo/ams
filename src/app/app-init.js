// Anything required here wil by default be combined/minified by r.js
// if you use it.
define(['app', 'routeDefs', '_services/User'], function(app) {
  app.factory('myHttpInterceptor',['$q','$log', '$rootScope', function ($q, $log, $rootScope) {
    var errorModalFlag = false;
    return function (promise) {
        return promise.then(function (response) {
            return response;
        }, function (response) {
            // do something on error
            $log.info(response);
       //      if ( !errorModalFlag && !$rootScope.isDev) {
       //          errorModalFlag = true;
       //          var $modal = $("<div class=\"modal hide\">\n" +
       // "  <div class=\"modal-header\">\n" +
       // "    <h3>系统异常</h3>\n" +
       // "  </div>\n" +
       // "  <div class=\"modal-body\">\n" +
       // "    <p>您当前操作的请求出现了异常，您可以选择下面的操作。</p>\n" +
       // "  </div>\n" +
       // "  <div class=\"modal-footer\">\n" +
       // "    <a class=\"btn btn-system-error-stay\">留在当前页</a>\n" +
       // "    <a class=\"btn btn-primary btn-system-error-backhome\">返回系统首页</a>\n" +
       // "  </div>\n" +
       // "</div>").appendTo($("body"));
       //          var $backdrop = $('<div class="modal-backdrop in" ng-class="{in: animate}" style="z-index: 1040;"></div>');
       //          $backdrop.appendTo($("body"));
       //          $(".btn-system-error-stay").on('click', function () {
       //              $modal.remove();
       //              $backdrop.remove();
       //              errorModalFlag = false;
       //          });
       //          $(".btn-system-error-backhome").on('click', function () {
       //              location.href = "#";
       //              $modal.remove();
       //              $backdrop.remove();
       //              errorModalFlag = false;
       //          });
       //          $modal.fadeIn(500);
            // }

            return $q.reject(response);
        });
    };
  }]);
  app.config(['routeDefsProvider', '$httpProvider', function(routeDefsProvider, $httpProvider ) {

    // in large applications, you don't want to clutter up app.config
    // with routing particulars.  You probably have enough going on here.
    // Use a service provider to manage your routing.

    //post header setting to make it work
    $httpProvider.defaults.headers.post = {
        'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With':'XMLHttpRequest'
    };

    //put header setting to make it work
    $httpProvider.defaults.headers.put = {
        'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With':'XMLHttpRequest'
    };

    //disable IE ajax request caching
    $httpProvider.defaults.headers.get = $httpProvider.defaults.headers.get || {};
    $httpProvider.defaults.headers.get['If-Modified-Since'] = '0';

    $httpProvider.responseInterceptors.push('myHttpInterceptor');
  }]);
  app.config(['$compileProvider', function($compileProvider){
      //unsafe url 处理，不然在href标签上会加上 unsafe：xxx
      // $compileProvider.urlSanitizationWhitelist(/^\s*((https?|ftp|mailto|tel):)|#/);
  }]);
  app.run([
    '$couchPotato', '$state', '$stateParams', '$rootScope',
    function($couchPotato, $state, $stateParams, $rootScope) {

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

    }
  ]);
  app.run(['$templateCache', function ($templateCache) {
    $templateCache.put("ConfirmModalContent.html",
        "<div class=\"modal-header\">\n" +
   "        <h3>{{title}}</h3>\n" +
   "    </div>\n" +
   "    <div class=\"modal-footer\">\n" +
   "        <button class=\"btn btn-primary\" ng-click=\"ok()\">确认</button>\n" +
   "        <button class=\"btn btn-warning\" ng-click=\"cancel()\" ng-show=\"showCancel\">取消</button>\n" +
   "    </div>");
    // var msie = parseInt((/msie (\d+)/.exec((navigator.userAgent).toLowerCase()) || [])[1], 10);
    //     if (msie || msie < 9) {

        // }
  }]);
  app.registerController('CtrlApp', ['$scope', '$http', '$rootScope', '$timeout','User',  function ($scope, $http, $rootScope, $timeout, User) {

    User.getCurrent( {}, function ( response ) {
      // console.log( response );
      //if have not login yet;
      if(typeof response === 'string') {
        window.location = "/bms/login.php";
      }
      $scope.user = response.data;
    });

  }]);
});
