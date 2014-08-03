require.config({
  // baseUrl: 'app',

  paths: {
    'angular'                    : '../../vendor/angular/angular.min',
    'angular-ui-router'          : '../../vendor/angular-ui-router/angular-ui-router.min',
    'angular-couch-potato'       : '../../vendor/angular-couch-potato/angular-couch-potato',
    'angular-ui-bootstrap'       : '../../vendor/angular-ui-bootstrap/ui-bootstrap-tpls-0.11.0.min',
    'angular-deferred-bootstrap' : '../../vendor/angular-deferred-bootstrap/angular-deferred-bootstrap',
    'angular-loading-bar'        : '../../vendor/angular-loading-bar/loading-bar.min',
    'angular-cookies'            : '../../vendor/angular/angular-cookies',
    'angular-animate'            : '../../vendor/angular/angular-animate',
    'angular-sanitize'           : '../../vendor/angular/angular-sanitize'
  },

  shim: {
    'angular': {
      exports: 'angular'
  },
    'angular-couch-potato': {
      deps: ['angular']
    },
    'angular-ui-router': {
      deps: ['angular']
    },
    'angular-ui-bootstrap' : {
      deps: ['angular']
    },
    'angular-loading-bar': {
      deps: ['angular']
    },
    'angular-deferred-bootstrap': {
      deps: ['angular'],
      exports: 'deferredBootstrap'
    },
    'angular-cookies': {
      deps      : ['angular']
    },
    'angular-animate' : {
      deps :['angular']
    },

    'angular-sanitize' : {
      deps :['angular']
    },
  },

  // kick start application
  deps: ['./app-bootstrap']

});