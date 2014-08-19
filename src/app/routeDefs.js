define(['app'], function(app) {
  app.registerProvider('routeDefs', [
    '$stateProvider',
    '$urlRouterProvider',
    '$couchPotatoProvider',
    'STATIC_DIR',
  function ($stateProvider, $urlRouterProvider, $couchPotatoProvider, STATIC_DIR) {
    this.$get = function() {
      // this is a config-time-only provider
      // in a future sample it will expose runtime information to the app
      return {};
    };
    // $locationProvider.html5Mode(true);

    $urlRouterProvider.otherwise('/home');
    var baseUrl = STATIC_DIR + 'app/'
    var headerConfig = {
      templateUrl: baseUrl + '_common/header/header.tpl.html',
      controller: 'CtrlHeader',
      resolve: {
        ctrl: $couchPotatoProvider.resolveDependencies(['_common/header/CtrlHeader'])
      }
    };
    var footerHr = {
      templateUrl: baseUrl + '_common/footer/footerHr.tpl.html'
    };

    $stateProvider.state('home', {
      url: '/home',
      views: {
        body: {
          templateUrl: baseUrl + 'home/home.tpl.html',
          controller: 'CtrlHome',
          resolve: {
            ctrl: $couchPotatoProvider.resolveDependencies(['home/CtrlHome'])
          }
        },
        header: headerConfig,
        // footer: footerHr
      }
    });

    $stateProvider.state('error', {
      url: '/error',
      abstract: true,
      views: {
        header: headerConfig,
        body: {
          templateUrl: baseUrl + '_common/error/error.tpl.html'
        },
      }
    })
    .state('error.permissionDenied', {
      url: '/permissionDenied',
      views: {
        errorType: {
          templateUrl: baseUrl + '_common/error/permissionDenied.tpl.html'
        }
      }
    });

    $stateProvider.state('staff-add', {
      url: '/hr/staff-add',
      views: {
        header: headerConfig,
        footer: footerHr,
        body: {
          templateUrl: baseUrl + 'hr/staffAdd.tpl.html',
          controller: 'CtrlStaffAdd',
          resolve: {
            ctrl: $couchPotatoProvider.resolveDependencies(['hr/CtrlStaffAdd'])
          }
        },
      }
    });

    $stateProvider.state('staff-transfer', {
      url: '/hr/staff-transfer',
      views: {
        header: headerConfig,
        footer: footerHr,
        body: {
          templateUrl: baseUrl + 'hr/staffTransfer.tpl.html',
          controller: 'CtrlStaffTransfer',
          resolve: {
            ctrl: $couchPotatoProvider.resolveDependencies(['hr/CtrlStaffTransfer'])
          }
        },
      }
    });

    $stateProvider.state('staff-resign', {
      url: '/hr/staff-resign',
      views: {
        header: headerConfig,
        footer: footerHr,
        body: {
          templateUrl: baseUrl + 'hr/staffResign.tpl.html',
          controller: 'CtrlStaffResign',
          resolve: {
            ctrl: $couchPotatoProvider.resolveDependencies(['hr/CtrlStaffResign'])
          }
        },
      }
    });

    $stateProvider.state('inservice-staff-query', {
      url: '/hr/inservice-staff-query',
      views: {
        header: headerConfig,
        footer: footerHr,
        body: {
          templateUrl: baseUrl + 'hr/staffQueryIn.tpl.html',
          controller: 'CtrlStaffQueryIn',
          resolve: {
            ctrl: $couchPotatoProvider.resolveDependencies(['hr/CtrlStaffQueryIn'])
          }
        },
      }
    });

    angular.noop();//do not remove this line,grunt tool use this to do reg match.
  }]);
});
