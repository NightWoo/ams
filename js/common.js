$(function () {
	window.byd = window.byd || {};
	window.byd.StringUtil = {
		isNotBlank : function (str) {
			if ($.trim(str) === '') return false;
			return true;
		}

		,isBlank : function (str) {
			if ($.trim(str) === '') return true;
			return false;
		}
	};
/* DataUtil CLASS DEFINITION
*  get firstDayOfTheMonth   1999-01-01 22:22
*  get current time         1999-01-01 22:22
  * ========================= */
	
	window.byd.DateUtil = {

		firstDayOfTheMonth : function () {
			return new Date().getFullYear() + '-' + (new Date().getMonth() + 1) 
				+ '-01 00:00';
		},

		currentTime : function () {
			var now = new Date();
	        var year = now.getFullYear();       //年
	        var month = now.getMonth() + 1;     //月
	        var day = now.getDate();            //日
	        var hh = now.getHours();            //时
	        var mm = now.getMinutes();          //分
	       
	        var clock = year + '-';

	        if(month < 10) clock += '0';
	        clock += month + '-';

	        if(day < 10) clock += '0';
	        clock += day + ' ';

	        if(hh < 10) clock += '0';
	        clock += hh + ':';

	        if (mm < 10) clock += '0'; 
	        clock += mm; 

	        return(clock); 
		},
		
		todayBeginTime : function () {
			var now = new Date();
	        var year = now.getFullYear();       //年
	        var month = now.getMonth() + 1;     //月
	        var day = now.getDate();            //日
	       
	        var clock = year + '-';

	        if(month < 10) clock += '0';
	        clock += month + '-';

	        if(day < 10) clock += '0';
	        clock += day + ' ';

	        clock += "08:00";

	        return(clock); 
		},

		todayEndTime : function () {
			var now = new Date();
		        var year = now.getFullYear();       //年
		        var month = now.getMonth() + 1;     //月
		        var day = now.getDate();            //日
		       
		        var clock = year + '-';

		        if(month < 10) clock += '0';
		        clock += month + '-';

		        if(day < 10) clock += '0';
		        clock += (day + 1) + ' ';

		        clock += "07:59";

		        return(clock); 
		}
	};/* END DataUtil CLASS DEFINITION */


/* Constants
  * ========================= */	

   window.BYD = window.BYD || {};
   window.BYD = {
   		"FAULT_QUERY_ALERT_VALIDATE" : '"故障零部件/部位"、"故障模式"这两个条件不可均为空值'
   }
});