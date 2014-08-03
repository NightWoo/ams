define(['app'], function (app) {
  app.registerFactory('CModal', [
    '$q',
    '$modal',
    '$timeout',
    'STATIC_DIR',
    function ($q, $modal, $timeout, STATIC_DIR) {
      return {
        alert: function (opts, cbfn) {
          var defered = $q.defer(),
            modalInstance,
            optsDefault = {
              title: '',
              content: ''
            };
          $timeout(function () {
            modalInstance = $modal.open({
              templateUrl: STATIC_DIR + 'app/_common/modal/modal.tpl.html',
              controller: 'CtrlCModal',
              // backdrop : 'static',
              windowClass: 'modal-alert ' + opts.ngClass,
              resolve: {
                opts: function () {
                  return angular.extend(optsDefault, opts);
                }
              }
            });
            modalInstance.result.then(function (result) {
              defered.resolve();
            });
          }, 0);

          return  defered.promise;
        },
        confirm: function (opts) {
          var
            defered = $q.defer(),
            optsDefault = {
              title: '',
              content: '',
              showCancel: true
            },
            modalInstance = $modal.open({
              templateUrl: STATIC_DIR + 'app/_common/modal/modal.tpl.html',
              controller: 'CtrlCModal',
              // backdrop : 'static',
              windowClass: 'modal-confirm',
              resolve: {
                opts: function () {
                  return angular.extend(optsDefault, opts);
                }
              }
            });

          modalInstance.result.then(function (result) {
            if (result === 'ok') {
              defered.resolve();
            }
          });

          return defered.promise;
        },
        success: function (opts) {
          var
            defered = $q.defer(),
            optsDefault = {
              title: '',
              content: ''
            },
            modalInstance = $modal.open({
              templateUrl: STATIC_DIR + 'app/_common/modal/successModal.tpl.html',
              controller: 'CtrlCModal',
              // backdrop : 'static',
              windowClass: 'modal-success',
              resolve: {
                opts: function () {
                  return angular.extend(optsDefault, opts);
                }
              }
            });

          $timeout(function () {
            modalInstance.dismiss('cancel');
            defered.resolve();
          }, opts.timeOut || 2000);

          return defered.promise;
        }
      };
    }
  ]);

  app.registerController('CtrlCModal', [
      '$scope',
      '$modalInstance',
      'opts',
  function ($scope, $modalInstance, opts) {

      $scope.title = opts.title;
      $scope.content = opts.content;
      $scope.contentList = opts.contentList;
      $scope.okText = opts.okText || '确认';
      $scope.cancelText = opts.cancelText || '取消';
      $scope.nextText = opts.nextText || '返回';
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