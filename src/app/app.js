define([
  'angular',
  'angular-couch-potato',
  'angular-ui-router',
  'angular-ui-bootstrap',
  'angular-loading-bar',
  'angular-cookies',
  'angular-sanitize'
], function (angular, couchPotato) {
  var app = angular.module('app', [
    'scs.couch-potato',
    'ui.router',
    'ui.bootstrap',
    'ui.bootstrap.tpls',
    'chieffancypants.loadingBar',
    'ngCookies',
    'ngSanitize'
  ]);

  // have Couch Potato set up the registerXXX functions on the app so that
  // registration of components is as easy as can be
  couchPotato.configureApp(app);
  return app;

});

