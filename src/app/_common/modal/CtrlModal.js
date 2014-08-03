/**
 * [description] 弹出提示浮层
 * [USAGE]
 * @param  {[type]} app [description]
 * @return {[type]}     [description]
 */
define(['app'], function ( app ) {
  app.registerController('CtrlModal', [
    '$scope',
    '$modalInstance',
    'opts',
  function ($scope, $modalInstance, opts) {

    $scope.title = opts.title;
    $scope.content = opts.content;
    $scope.contentList = opts.contentList;
    $scope.okText = opts.okText || '确认';
    $scope.cancelText = opts.cancelText || '取消';
    $scope.nextText = opts.nextText || '下一步';
    $scope.showCancel = opts.showCancel || false;
    $scope.showReturn = opts.showReturn || false;
    $scope.modalDatas = opts.modalDatas;
    $scope.errors = opts.errors;

    //如果有附加的信息，加入
    if ( opts.psInfo ) {
        $scope.psInfo = '注：' + opts.psInfo;
    }
    $scope.ok = function () {
        $modalInstance.close('ok');
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

    $scope.back = function () {
        $modalInstance.close('back');
    };

  }]);
});