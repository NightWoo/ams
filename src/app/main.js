require.config({
  // baseUrl: 'app',

  paths: {
    'domReady'              : '../../vendor/requirejs/domReady',
    'angular'               : '../../vendor/angular/angular.min',
    'angular-ui-router'     : '../../vendor/angular-ui-router/angular-ui-router.min',
    'angular-couch-potato'  : '../../vendor/angular-couch-potato/angular-couch-potato',
    'angular-ui-bootstrap'  : '../../vendor/angular-ui-bootstrap/ui-bootstrap-tpls-0.11.0.min',
    'angular-loading-bar'   : '../../vendor/angular-loading-bar/loading-bar.min',
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
    'app-templates': {
      deps: ['angular']
    }
  },

  // kick start application
  deps: ['./app-bootstrap']

});