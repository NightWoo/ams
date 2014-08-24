define([ 'app' ], function ( app ) {
  app.registerFactory( 'Modal', [ '$modal', '$timeout', function ( $modal, $timeout ) {
    return {
      alert: function ( opts, cbfn ) {
        var modalInstance;
        $timeout(function () {
          modalInstance = $modal.open({
            templateUrl: '_common/modal/modal.tpl.html',
            controller : 'CtrlModal',
            backdrop : 'static',
            resolve : {
              opts : function () {
                return opts;
              }
            }
          });
          if ( cbfn ) {
            modalInstance.result.then( function (result) {
              cbfn();
            }, function () {
                cbfn();
            });
          }
        }, 0);
      },
      confirm: function (opts, cbfn) {
          var modalInstance = $modal.open({
              templateUrl: '_common/modal/modal.tpl.html',
              controller : 'CtrlModal',
              backdrop : 'static',
              resolve : {
                  opts : function () {
                      return angular.extend(opts, {showCancel: true});
                  }
              }
          });
          modalInstance.result.then(function (result) {
              if (result === 'ok') {
                  cbfn();
              }
          });
      }
    };
  }]);

});