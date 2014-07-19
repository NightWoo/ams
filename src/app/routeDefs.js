define(['app'], function(app) {
  app.registerProvider(
    'routeDefs',
    [
      '$stateProvider',
      '$urlRouterProvider',
      '$couchPotatoProvider',
      function (
        $stateProvider,
        $urlRouterProvider,
        $couchPotatoProvider
      ) {

        this.$get = function() {
          // this is a config-time-only provider
          // in a future sample it will expose runtime information to the app
          return {};
        };
        // $locationProvider.html5Mode(true);

        $urlRouterProvider.otherwise('/home');
        var baseUrl = '/bms/src/app/'
        //site home
        $stateProvider
          .state('home', {
            url: '/home',
            templateUrl: baseUrl + 'home/home.tpl.html',
            controller: 'CtrlHome',
            resolve: {
              dummy: $couchPotatoProvider.resolveDependencies(['home/CtrlHome'])
            }
          });

        angular.noop();//do not remove this line,grunt tool use this to do reg match.
      }
    ]
  );
});
