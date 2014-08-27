define(['app'], function (app) {
  app.registerFactory('ModalApprovalRecord', [
    '$q',
    '$modal',
    '$timeout',
    'STATIC_DIR',
  function ($q, $modal, $timeout, STATIC_DIR) {
    return {
      show: function (opts) {
        var defered = $q.defer(),
          modalInstance,
          optsDefault = {
            title: '',
            content: ''
          };

        modalInstance = $modal.open({
          templateUrl: STATIC_DIR + 'app/hr/modalApprovalRecord.tpl.html',
          controller: 'CtrlModalApprovalRecord',
          // backdrop : 'static',
          windowClass: 'modal-approval-record',
          resolve: {
            opts: function () {
              return angular.extend(optsDefault, opts);
            }
          }
        });
        modalInstance.result.then(function (result) {
          defered.resolve();
        });

        return  defered.promise;
      }
    };
  }]);

  app.registerController('CtrlModalApprovalRecord', [
    '$scope',
    '$modalInstance',
    'opts',
    'StaffHttp',
  function ($scope, $modalInstance, opts, StaffHttp) {
    StaffHttp.getApprovalRecord({
      transferId: opts.transferId
    }).success(function (response) {
      if (response.success) {
        $scope.approvalRecords = response.data;
        if ($scope.approvalRecords.length) {
          $scope.approvalRecords[0].isOpen = true;
        }
      }
    });

    $scope.ok = function () {
      $modalInstance.close('ok');
    };

    $scope.cancel = function () {
      $modalInstance.dismiss('cancel');
    };
  }]);
});