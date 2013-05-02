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

		currentDate: function() {
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
	        clock += day;

	        return(clock); 
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
			// var now = new Date();
	  //       var year = now.getFullYear();       //年
	  //       var month = now.getMonth() + 1;     //月
	  //       var day = now.getDate();            //日
	       
	  //       var clock = year + '-';

	  //       if(month < 10) clock += '0';
	  //       clock += month + '-';

	  //       if(day < 10) clock += '0';
	  //       clock += (day + 1) + ' ';
			var now = new Date();
			var hh = now.getHours();
			if(hh>=8 && hh<24) {
				endClock = self.byd.DateUtil.tomorrowDate();
			} else {
				endClock = self.byd.DateUtil.currentDate();
			}
			console.log(self);
	        endClock += " 07:59";

	        return(endClock); 
		},

		tomorrowDate: function () {
			//获取系统时间 
			var now = new Date();
			var nowYear = now.getFullYear();
			var nowMonth = now.getMonth();
			var nowDate = now.getDate();
			//处理
			var uom = new Date(nowYear,nowMonth, nowDate);
			uom.setDate(uom.getDate() + 1);//取得系统时间的前一天,重点在这里,负数是前几天,正数是后几天
			var LINT_MM = uom.getMonth();
			LINT_MM++;
			var LSTR_MM = LINT_MM >= 10?LINT_MM:("0"+LINT_MM)
			var LINT_DD = uom.getDate();
			var LSTR_DD = LINT_DD >= 10?LINT_DD:("0"+LINT_DD)
			//得到最终结果
			uom = uom.getFullYear() + "-" + LSTR_MM + "-" + LSTR_DD; 
			return(uom); 
		},

		yesterday: function () {			
			//获取系统时间 
			var now = new Date();
			var nowYear = now.getFullYear();
			var nowMonth = now.getMonth();
			var nowDate = now.getDate();
			//处理
			var uom = new Date(nowYear,nowMonth, nowDate);
			uom.setDate(uom.getDate()-1);//取得系统时间的前一天,重点在这里,负数是前几天,正数是后几天
			var LINT_MM = uom.getMonth();
			LINT_MM++;
			var LSTR_MM = LINT_MM > 10?LINT_MM:("0"+LINT_MM)
			var LINT_DD = uom.getDate();
			var LSTR_DD = LINT_DD > 10?LINT_DD:("0"+LINT_DD)
			//得到最终结果
			uom = uom.getFullYear() + "-" + LSTR_MM + "-" + LSTR_DD; 
			return(uom);
		},

		workDate: function () {
			var now = new Date();
			var hh = now.getHours();
			var workDate;

			if(hh>=8 && hh<24) {
				workDate = self.byd.DateUtil.currentDate();
			} else {
				workDate = self.byd.DateUtil.yesterday();
			}

			return(workDate);
		},

		nextWorkDate: function () {
			var now = new Date();
			var hh = now.getHours();
			var nextWorkDate

			if(hh>=8 && hh<24) {
				nextWorkDate = self.byd.DateUtil.tomorrowDate();
			} else {
				nextWorkDate = self.byd.DateUtil.currentDate();
			}

			return nextWorkDate;
		},

		currentDate8: function () {
			var clock = self.byd.DateUtil.workDate();

	        clock += " 08:00";

	        return(clock); 
	},

	currentDate16: function () {
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

	        clock += "16:00";

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