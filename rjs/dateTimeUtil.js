define(function () {
	DateUtil = {
		firstDayOfTheMonth: function () {
			year = new Date().getFullYear();
			month = new Date().getMonth() + 1;
			month = month>=10 ? month : "0" + month;
			time = year + '-' + month + '-01 08:00';
			return time;
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
			var now = new Date();
			var hh = now.getHours();
			if(hh>=8 && hh<24) {
				endClock = DateUtil.tomorrowDate();
			} else {
				endClock = DateUtil.currentDate();
			}
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
			var LSTR_MM = LINT_MM >= 10?LINT_MM:("0"+LINT_MM)
			var LINT_DD = uom.getDate();
			var LSTR_DD = LINT_DD >= 10?LINT_DD:("0"+LINT_DD)
			//得到最终结果
			uom = uom.getFullYear() + "-" + LSTR_MM + "-" + LSTR_DD;
			return(uom);
		},

		dayBeforYesterday: function () {
			//获取系统时间
			var now = new Date();
			var nowYear = now.getFullYear();
			var nowMonth = now.getMonth();
			var nowDate = now.getDate();
			//处理
			var uom = new Date(nowYear,nowMonth, nowDate);
			uom.setDate(uom.getDate()-2);//取得系统时间的前一天,重点在这里,负数是前几天,正数是后几天
			var LINT_MM = uom.getMonth();
			LINT_MM++;
			var LSTR_MM = LINT_MM >= 10?LINT_MM:("0"+LINT_MM)
			var LINT_DD = uom.getDate();
			var LSTR_DD = LINT_DD >= 10?LINT_DD:("0"+LINT_DD)
			//得到最终结果
			uom = uom.getFullYear() + "-" + LSTR_MM + "-" + LSTR_DD;
			return(uom);
		},

		workDate: function () {
			var now = new Date();
			var hh = now.getHours();
			var workDate;

			if(hh>=8 && hh<24) {
				workDate = DateUtil.currentDate();
			} else {
				workDate = DateUtil.yesterday();
			}

			return(workDate);
		},

		lastWorkDate: function () {
			var now = new Date();
			var hh = now.getHours();
			var lastWorkDate;

			if(hh>=8 && hh<24) {
				workDate = DateUtil.yesterday();
			} else {
				workDate = DateUtil.dayBeforYesterday();
			}

			return(workDate);
		},

		nextWorkDate: function () {
			var now = new Date();
			var hh = now.getHours();
			var nextWorkDate

			if(hh>=8 && hh<24) {
				nextWorkDate = DateUtil.tomorrowDate();
			} else {
				nextWorkDate = DateUtil.currentDate();
			}

			return nextWorkDate;
		},

		currentDate8: function () {
			var clock = DateUtil.workDate();

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
	}

	return {
		getTime: function (timePoint) {
			return DateUtil[timePoint]();
		}
	}
});