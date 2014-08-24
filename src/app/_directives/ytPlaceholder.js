/**
 * @ngdoc directive
 * @name yt.directive:ytPlaceholder
 * @element input
 * @function
 *
 * @description
 * 用于通用的placeholder（在input输入框中无内容时显示的占位文字）
 *
 * @example
   <input yt-placeholder="请输入名字">

 */
define(['app'], function (app) {
  app.registerDirective('ytPlaceholder', ['$rootScope', function ($rootScope) {
    var uniquePlaceHolderId = 0;
    return {
        link: function (scope, element, attrs) {
            var placeHolderText = attrs.ytPlaceholder;
            /*for ie below 9,placeholder attribute not supported*/
            if ($rootScope.msie && $rootScope.msie < 9) {
                var elementId = attrs.id || ("iePlaceHolderId" + uniquePlaceHolderId++);
                var label = angular.element("<label class='place-holder-label' for='" + elementId + "'>" + placeHolderText + "</label>");
                element.after(label);

                /*trick to init the ueditor....*/
                var unregister = scope.$watch(attrs.ngModel, function (n, o) {
                    if (n) {
                        label.hide();
                        unregister();
                    }
                });

                label.bind("click", function () {
                    $(this).hide();
                });
                element.bind("focus", function () {
                    label.hide();
                });
                element.bind("blur", function () {
                    if (!element.val()) {
                        label.show();
                    }
                });

                scope.$on('$destroy', function() {
                    element.unbind("blur").unbind("focus");
                });
            } else {
                element.attr("placeholder", placeHolderText);
            }
        }
    };
  }]);
});