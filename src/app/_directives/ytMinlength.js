/**
 * @ngdoc directive
 * @name yt.directive:ytMinLength
 * @element input
 * @function
 *
 * @description
 * 允许输入的最短字符数，一个汉字算两个字符，
 * 不足飘红
 *
 * @example
   <input yt-Minlength="4">


 */
define(['app', '../_services/CommonService'], function (app) {
  app.registerDirective('ytMinlength', ['Utils', 'Modal', '$parse', function (Utils, Modal, $parse) {
      return {
          restrict : "A",
          // transclude : true,
          require: 'ngModel',
          link : function (scope, element, attrs, ctrl) {
            var limit = attrs.ytMinlength;
            if ( !limit ) {
              return false;
            }
            ctrl.$parsers.unshift( function( viewValue ) {
              if ( Utils.isLengthShort( viewValue, limit ) ) {
                ctrl.$setValidity('ytMinlength', false);
                return undefined;
              } else {
                ctrl.$setValidity('ytMinlength', true);
                return viewValue;
              }
            });
          }
      };
  }]);
});