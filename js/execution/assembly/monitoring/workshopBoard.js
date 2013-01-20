$(document).ready(function () {
	facadeController.initFacade();
	ajaxRefresh();
	setInterval(function () {
		ajaxRefresh();
	},10000);
	setInterval(function () {
		ajaxRefreshSeats();
	},2000);
	
});
!function () {
	window.facadeController = {
		toHide : [],
		dealFacade : function (sectionStatus) {
			var _this = this;
			var toHideList = [];
			//hide facade when seat of the section calls
			$.each(sectionStatus, function (index, value) {
    			toHideList.push(value.section);
    			_this.hideFacade(value.section);
    		});
			//show other 
			var sectionList = ["T1", "T2", "T3", "C1", "C2", "F1", "F2", "VQ1"];
			$.each(sectionList, function (index, value) {
				if ( $.inArray(  value ,toHideList) < 0) {
    				_this.showFacade(value.section);
    			}
    		});

		},
		hideFacade : function (sectionName) {
			$("#section" + sectionName).show();
			$("#facade" + sectionName).hide();
		},
		showFacade : function (sectionName) {
			$("#section" + sectionName).hide();
			$("#facade" + sectionName).show();
		},
		planPause : function () {
			$("div[id^=facade]").css("background", "#B1B1B1").show();
			$("div[id^=section]").hide();
		},
		initFacade : function () {
			$("div[id^=section]").hide();
		}
	
	}
	window.seatController = {
		planPause : function () {
			this.planPauseQG();
			this.planPauseChain();
		},
		resetQG : function () {
			$("#QG").css("background", "#00FF00");
			$("#QGDiv").css("color", "black").html("QG");
		},
		planPauseQG : function () {
			$("#QG").css("background", "#B1B1B1");
			$("#QGDiv").css("color", "black").html("QG");
		},
		planPauseChain : function () {
			$(".chain").css("background", "#B1B1B1").css("color", "black");
		}
	}
	
	window.flashController = {
		flashing : [],
		facade : [],
		resetAll : function () {
			$(".chain").css("background", "#00ff00").css("color", "black");
			$("div[id^=section]").hide();
			$("div[id^=facade]").css("background", "#00ff00").show();
			// this.flashing = [];
		},
		dealSectionStatus : function (sectionStatus) {
			//deal with facade
			facadeController.dealFacade(sectionStatus);
			this.dealQG(sectionStatus);
		},
		dealSeatStatus : function (seatStatus) {
			this.dealChain(seatStatus);
			this.dealSeat(seatStatus);
		},
		dealSeat : function (seatStatus) {
			var _this = this;
			var toShowList = [];
    		//dealing with seats
    		$.each(seatStatus, function (index,value) {
    			if (value.seat != "00") {
	    			toShowList.push(value.full_seat);
	    			if (_this.existFlash(value.full_seat) == false) {
	    				
	    			} else {
	    				//stop pre
	    				$(_this.flashing).each(function (i, v) {
	    					if (value.full_seat == v.name) {
	    						clearInterval(v.interval);
	    						_this.flashing.splice(i, 1);
	    					}
	    				})
	    				//start cur
	    			}
	    			var ft = value.foreground_text;
    				var ftc = value.foreground_font_color;
    				var fc = value.foreground_color;
    				var bt = value.background_text;
    				var bfc = value.background_font_color;
    				var obg = "black";
    				var gap = 2000;

    				if (value.flash_type === "block") {
    					obg = "yellow";
    					bfc = "black";
    					bt = value.foreground_text;
					} else if (value.flash_type === "normal") {
						bt = "";
					} else if (value.flash_type === "fast") {
						bt = "";
						gap = 1000;
					} else if (value.flash_type === "red") {
						bt = "";
					}
	    			_this.flash($("#" + value.full_seat), ft, ftc, fc
    				, bt, bfc, obg, gap/2);
    				var interval = setInterval(_this.flash, gap, $("#" + value.full_seat), ft, ftc, fc
    				, bt, bfc, obg, gap/2);
    				_this.flashing.push({"name": value.full_seat, "interval": interval});
	    		}
    		});
    		//reset 
    		$.each($(".section"),function (index, value) {
				if ($.inArray(value.id, toShowList) < 0) {
					_this.clear(value.id);
				}
			});
		},
		dealChain : function (seatStatus) {
			var _this = this;
			var toShowList = [];
    		//dealing with seats
    		$.each(seatStatus, function (index,value) {
    			if (value.seat == "00") {
	    			toShowList.push(value.full_seat);
	    			if (_this.existFlash(value.full_seat) == false) {
	    				var ft = value.foreground_text;
	    				var ftc = value.foreground_font_color;
	    				var fc = value.foreground_color;
	    				var bt = value.background_text;
	    				var bfc = value.background_font_color;
	    				var obg = "black";
	    				var gap = 2000;

	    				if (value.flash_type === "block") {
	    					obg = "yellow";
	    					bfc = "black";
	    					bt = value.foreground_text;
						} else if (value.flash_type === "normal") {
						} else if (value.flash_type === "fast") {
							gap = 1000;
						} else if (value.flash_type === "red") {

						}
		    			_this.flash($("#" + value.full_seat), ft, ftc, fc
	    				, bt, bfc, obg, gap/2);
	    				var interval = setInterval(_this.flash, gap, $("#" + value.full_seat), ft, ftc, fc
	    				, bt, bfc, obg, gap/2);
	    				_this.flashing.push({"name": value.full_seat, "interval": interval});
	    			} else {
	    				console.log(_this.flashing);
	    			}
	    		}
    		});
    		//reset 
    		$.each(["L1", "EF1", "EF2", "EF3"],function (index, value) {
				if ($.inArray(value, toShowList) < 0) {
					_this.clear(value);
				}
			});
		},
		flash : function (element,  frontText, frontColor, frontBg, oppoText, oppoColor, oppoBg, gap ) {
			$(element).css("color", oppoColor).css("background", oppoBg).html(oppoText);
			if (parseInt(oppoText) > 0 && parseInt(oppoText) < 100){
				$(element).html(oppoText.substr(0,1) + " " + oppoText.substr(1,1));
			}
			__sto(function (cS, fT, fC, fB) {
				cS.css("color", fC).css("background", fB).html(fT);

				if (parseInt(fT) > 0 && parseInt(fT) < 100){
					cS.html(fT.substr(0,1) + " " + fT.substr(1,1));
				}
				// $("#seat" + sN).removeClass("seat_call").addClass("seat_call_oppo").html(oT);
			},gap, $(element), frontText, frontColor, frontBg);
		},
		clear : function (name) {
			var _this = this;

			// console.log(this.flashing);
			$.each(this.flashing, function (index, value) {
				if (name == value.name) {
					clearInterval(value.interval);
					__sto(function () {
						$("#" + name).css("color", "#00ff00").css("background", "#00ff00");
					}, 2000);
					_this.flashing.splice(index, 1);
					return true;
				}
			});
		},
		planPause : function () {
			// console.log(this.flashing);
			$.each(this.flashing, function (index, value) {
				clearInterval(value.interval);
			});
			this.flashing = [];
			setTimeout(function () {
				facadeController.planPause();
				seatController.planPause();
			}, 2000)
		},
		dealQG : function  (sectionStatus) {
			var _this = this;
			var QGFlag = false;
			$.each(sectionStatus, function (index, value) {
    			if (value.section === "QG") {
    				
    				if (!_this.existFlash(value.section)) {
    					_this.flashQG(value.background_text);
    					var interval = setInterval(_this.flashQG, 2000, value.background_text);
    					_this.flashing.push({"name": value.section, "interval": interval});
    				}
    				QGFlag = true;
    				return false;
    			}
    		});
    		if (!QGFlag)
    			this.clearQG();
		},
		flashQG : function () {
			var bT = arguments[0];
			$("#QG").css("background", "black");
			$("#QGDiv").css("color", "yellow").html(bT);

			__sto(function () {
				$("#QG").css("background", "yellow");
				$("#QGDiv").css("color", "black").html("QG");
			},1000);
		},
		clearQG : function () {
			$("#QG").css("background", "#00FF00");
			$("#QGDiv").css("color", "black").html("QG");
			$.each(this.flashing, function (index, value) {
				if ("QG" == value.name) {
					clearInterval(value.interval);
					return true;
				}
			});
		},
		existFlash : function (flashName) {
			var existFlag = false;
			console.log("flashName" + flashName);
			console.log(this.flashing);
			$.each(this.flashing, function (index, value) {
				if (flashName == value.name) {
					existFlag = true;
					return true;
				}
			});
			return existFlag;
		},
		coverFlash : function () {
			
		},
	}
	
}();
function ajaxRefreshSeats () {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_SECTION_STATUS,//ref:  /bms/js/service.js
	    data: {"section" : $("#section").val()},
	    success:function (response) {
	    	if (response.success){
	    		

	    		if (response.data.lineStatus === "white-pause") {
					$("#symbol").removeClass().addClass("symbol_white_pause");
					//deal with plan pause
					flashController.planPause();
					
				} else {
					if(response.data.lineStatus === "play") {
						$("#symbol").removeClass().addClass("symbol");
					} else if(response.data.lineStatus === "red-pause") {
						$("#symbol").removeClass().addClass("symbol_red_pause");
					} else{
						$("#symbol").removeClass().addClass("symbol_halt");
					}
					flashController.resetAll();
					flashController.dealSectionStatus(response.data.sectionStatus);
					flashController.dealSeatStatus(response.data.seatStatus);
				}
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}

function initPage (argument) {
	ajaxRefresh();
	$("#sectionT1").hide();
	$("#sectionT2").hide();
	$("#sectionT3").hide();
	$("#sectionC1").hide();
	$("#sectionC2").hide();
	$("#sectionF1").hide();
	$("#sectionF2").hide();
	$("#sectionVQ1").hide();
}

function ajaxRefresh (planId) {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_SHOP_PANEL,//ref:  /bms/js/service.js
	    data: {"section" : $("#section").val()},
	    success:function (response) {
	    	if (response.success){
				$("#title").text(response.data.title);
	    		$("#time").text(response.data.cur_time);
	    		$("#dpu").text(response.data.dpu);
				$("#qrate").text(response.data.qrate);
	    		$("#lineRate").text(response.data.line_speed + " sec");
	    		$("#pauseTime").text( response.data.line_pause_time + " min");
	    		$("#workingTimePercentage").text(response.data.line_urate);
	    		$("#productAmount").text(response.data.finish_cars + " / " + response.data.plan_cars + " 辆");
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}






	var __sto = function(callback,delayMS)
	　　{
			//arguments伪数组
		　　var args = Array.prototype.slice.call(arguments,2);

		　　var _cb = function()
		　　{
		　　    callback.apply(null,args);
		　　};
			setTimeout(_cb,delayMS);
	　　};


	