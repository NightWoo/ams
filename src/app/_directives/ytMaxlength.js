/**
 * @ngdoc directive
 * @name yt.directive:ytMaxLength
 * @element input
 * @function
 *
 * @description
 * 允许输入的最长字符数，一个汉字算两个字符，
 *  ie下，会飘红，其他，不让输入
 *
 * @example
   <input yt-maxlength="60">


 */
define(['app', '../_services/CommonService'], function (app) {
  app.registerDirective('ytMaxlength', ['Utils', 'Modal', '$parse', function (Utils, Modal, $parse) {
      return {
          restrict : "A",
          // transclude : true,
          require: 'ngModel',
          link : function (scope, element, attrs, ctrl) {
            var limit = attrs.ytMaxlength;
            if ( !limit ) {
              return false;
            }
            var msie = parseInt((/msie (\d+)/.exec((navigator.userAgent).toLowerCase()) || [])[1], 10);
            if ( !msie ) { /*非ie做处理*/
              scope.$watch(attrs.ngModel, function (n, o) {
                if (n && o && Utils.isLengthOverflow( n, limit ) ) {
                  var parsed = $parse(attrs.ngModel);
                  parsed.assign(scope, Utils.substrByByte(n, limit));
                }
              });
            } else {
              ctrl.$parsers.unshift( function( viewValue ) {
                if ( Utils.isLengthOverflow( viewValue, limit ) ) {
                  ctrl.$setValidity('ytMaxlength', false);
                  return undefined;
                } else {
                  ctrl.$setValidity('ytMaxlength', true);
                  return viewValue;
                }
              });
            }
          }
      };
  }]);
});