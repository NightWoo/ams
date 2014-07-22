define(['app'], function(app) {
  app.registerProvider('routeDefs', [
    '$stateProvider',
    '$urlRouterProvider',
    '$couchPotatoProvider',
  function ($stateProvider, $urlRouterProvider, $couchPotatoProvider) {
    this.$get = function() {
      // this is a config-time-only provider
      // in a future sample it will expose runtime information to the app
      return {};
    };
    // $locationProvider.html5Mode(true);

    $urlRouterProvider.otherwise('/home');
    var baseUrl = '/bms/src/app/'
    var headerConfig = {
      templateUrl: baseUrl + '_common/header/header.tpl.html',
      controller: 'CtrlHeader',
      resolve: {
        ctrl: $couchPotatoProvider.resolveDependencies(['_common/header/CtrlHeader'])
      }
    };
    var footerConfig = {
      templateUrl: baseUrl + '_common/footer/footer.tpl.html'
    };

    $stateProvider.state('home', {
      url: '/home',
      views: {
        'body': {
          templateUrl: baseUrl + 'home/home.tpl.html',
          controller: 'CtrlHome',
          resolve: {
            ctrl: $couchPotatoProvider.resolveDependencies(['home/CtrlHome'])
          }
        },
        header: headerConfig,
        footer: footerConfig
      }
    });

    $stateProvider.state('staff-add', {
      url: '/hr/staff-add',
      views: {
        header: headerConfig,
        // footer: footerConfig,
        'body': {
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
        // footer: footerConfig,
        'body': {
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
        // footer: footerConfig,
        'body': {
          templateUrl: baseUrl + 'hr/staffResign.tpl.html',
          controller: 'CtrlStaffResign',
          resolve: {
            ctrl: $couchPotatoProvider.resolveDependencies(['hr/CtrlStaffResign'])
          }
        },
      }
    });

    angular.noop();//do not remove this line,grunt tool use this to do reg match.
  }]);
});
