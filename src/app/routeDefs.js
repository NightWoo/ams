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

        //site home
        $stateProvider
          .state('home', {
            url: '/home',
            templateUrl: 'home/home.tpl.html',
            controller: 'CtrlHome',
            resolve: {
              dummy: $couchPotatoProvider.resolveDependencies(['home/CtrlHome'])
            }
          });

        //about
        // $stateProvider
        //   .state('about', {
        //     url: '/about',
        //     templateUrl: 'about/layout.tpl.html',
        //     // controller: 'CtrlHome'
        //   })
        //   .state('about.home', {
        //     url: 'home',
        //     views: {
        //       'left' : {
        //         templateUrl: 'about/leftNav.tpl.html',
        //       },
        //       'main' : {
        //         templateUrl: 'about/main.tpl.html',
        //         controller: 'CtrlHome'
        //       },
        //       'right' : {
        //         templateUrl: 'about/rightNav.tpl.html',
        //       }
        //     }
        //     // resolve: {
        //     //   dummy: $couchPotatoProvider.resolveDependencies(['home/CtrlHome'])
        //     // }
        //   });
        angular.noop();//do not remove this line,grunt tool use this to do reg match.
      }
    ]
  );
});
