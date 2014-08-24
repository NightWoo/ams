define(['app'], function ( app ) {
  app.registerController('CtrlModal', ['$scope', '$modalInstance', 'opts', function ($scope, $modalInstance, opts) {
    $scope.title = opts.title;
    $scope.content = opts.content;
    $scope.showCancel = opts.showCancel;
    
    $scope.ok = function () {
        $modalInstance.close('ok');
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
  }]);
});