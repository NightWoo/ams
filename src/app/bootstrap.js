/**
 *bootstraps angualr onto the window.document node
 */
 define([
    'angular',
    'app',
    'app-init'
], function (ng) {
    // 'use strict';

    require(['domReady!'], function (document) {
        ng.bootstrap(document, ['app', function () {
            // for good measure, put ng-app on the html element
            // studiously avoiding jQuery because angularjs.org says we shouldn't
            // use it.  In real life, there are a ton of reasons to use it.
            // karma likes to have ng-app on the html element when using requirejs.
            ng.element(document).find('html').addClass('ng-app');
            ng.element(document).find('body').attr('ng-controller', 'CtrlApp');
        }]);
    });

});