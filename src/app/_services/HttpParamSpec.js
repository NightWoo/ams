define(['app'], function ( app ) {
  app.registerFactory( 'HttpParamSpec', [ '$http', '$log', function ( $http, $log ) {
    return {
      spec: function ( paramObj, specArray, cbfn ) {
        try {
          // if ( !paramObj ) {
          //   throw Error( 'Expect param to pass into http service,but none.' );
          // }
          // if ( !specArray ) {
          //   throw Error( 'Expect specArray in HttpParamSpec,but none.' );
          // }
          if ( typeof paramObj === 'object' && specArray.length ) {
            angular.forEach( specArray, function ( value ) {
              if ( !paramObj[value] ) {
                throw Error('Expect ' + value + ' in http param,but got undefined.');
              }
            } );
          }
        } catch ( e ) {
          $log.error( e.message );
          cbfn( {
            code: 404,
            message: 'Http request without required parameters.'
          } );
          return false;
        }
        return true;
      }
    };
  }]);
});