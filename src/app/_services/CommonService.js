define(['app'], function (app) {
  app.registerFactory('Utils', ['$modal', function ($modal) {
      return {
          joinWithKey : function (array, wantedKey, splitter) {
              if (!array || array.length === 0) {
                  return "";
              }
              var arr = [];
              for (var i = 0; i < array.length; i++) {
                arr.push(array[i][wantedKey]);
              }

              return arr.join(splitter || ",");
          },
          substrByByte : function (source, length) {
              return (source+'').substr(0,length).replace(/([^\x00-\xff])/g,' $1').substr(0,length).replace(/ ([^\x00-\xff])/g,'$1');
          },
          isLengthOverflow : function (source, maxLength) {
              if ((source+'').replace(/([^\x00-\xff])/g,'aa').length > maxLength) {
                  return true;
              }
              return false;
          },
          isLengthShort : function (source, minLength) {
              if ((source+'').replace(/([^\x00-\xff])/g,'aa').length < minLength) {
                  return true;
              }
              return false;
          },
          containSpecialCharactor: function (str, isNameZone) {
              var pattern = new RegExp("[`~!@$^&*()=|{}':;',\\[\\].<>/?~！@￥……&*（）——|{}【】‘；：”“'。，、？]");
              if ( isNameZone ) {
                  pattern = new RegExp("[`~!@$^&*=|{}':;',<>/?~！@￥……&*——|{}‘；：”“'。，、？]");
              }
              return pattern.test(str);
          },
          alert : function (content) {
              var modalInstance = $modal.open({
                  templateUrl: 'ConfirmModalContent.html',
                  controller : 'ConfirmModalController',
                  backdrop : false,
                  resolve : {
                      title : function () {
                          return content;
                      }
                  }
              });
          }
      };
  }]);


});