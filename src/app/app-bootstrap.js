/**
 *bootstraps angualr onto the window.document node
 */
 define([
    'angular',
    'angular-deferred-bootstrap',
    'app',
    'app-init'
], function (angular, deferredBootstrap, app) {
    var staticDir = '/bms/src/';
    deferredBootstrapper.bootstrap({
      element: document.body,
      module: app['name'],
      resolve: {
        'STATIC_DIR': ['$q', '$timeout', function ($q, $timeout) {
          var deferred = $q.defer();
          $timeout(function () {
            deferred.resolve(staticDir);
          });
          return deferred.promise;
        }]
      }
    });
});