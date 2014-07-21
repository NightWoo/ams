define(['app'], function (app) {
  app.registerDirective('maxHeightBody', [
    '$window',
    '$position',
  function ($window, $position) {
    return {
      restrict : "AC",
      link : function (scope, element, attrs) {
        var w = angular.element($window);
        scope.getWindowDimensions = function () {
          return {
            'h': $window.innerHeight,
            'w': $window.innerWidth
          };
        };

        scope.$watch(scope.getWindowDimensions, function (newValue, oldValue) {
            scope.windowHeight = newValue.h;
            scope.windowWidth = newValue.w;
            var paddingHeight = getPaddingHeight();

            element.css({
              'height': (newValue.h - paddingHeight.header - paddingHeight.footer) + 'px',
            });
        }, true);
        w.bind('resize', function () {
          scope.$apply();
        });

        function getPaddingHeight () {
          var paddingHeight = {
            header: 0,
            footer: 0
          };

          var header = angular.element(document.querySelector('header'));
          var footer = angular.element(document.querySelector('footer'));
          if (header.length) {
            paddingHeight.header = $position.position(header).height;
          }
          if (footer.length) {
            paddingHeight.footer = $position.position(footer).height;
          }

          return paddingHeight;
        }
      }
    };
  }]);
});