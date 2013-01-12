$(document).ready(function () {
	initPage();
	setInterval(function () {
		ajaxRefresh();
	},10000);
	setInterval(function () {
		ajaxRefreshSeats ();

	},2000);
	var sectionName = $("#section").val();
	$("#EF1").hide();
	$("#EF2").hide();
	$("#EF3").hide();
	if(sectionName  === "T1") {
		$("#EF1").show();
		$("#EF2").show();
	} else if(sectionName === "F1") {
		$("#EF3").show();
	}

});

!(function (window) {
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


	window.seatController = {
		animateAndonCall : function (seatNumber, frontText, frontColor, frontBg, oppoText, oppoColor ) {
			var calledSeat = $("#seat" + seatNumber);
			//animate to front
			// calledSeat.css("color", frontColor).css("background", frontBg).html(frontText);
			//animate to opposite
			__sto(function (cS, oT, oC) {
				cS.css("color", oC).css("background", "black").html(oT);
				// $("#seat" + sN).removeClass("seat_call").addClass("seat_call_oppo").html(oT);
			},1000, calledSeat, oppoText, oppoColor);

			__sto(function (cS, fT, fC, fB) {
				cS.css("color", fC).css("background", fB).html(fT);
				// $("#seat" + sN).removeClass("seat_call").addClass("seat_call_oppo").html(oT);
			},2000, calledSeat, frontText, frontColor, frontBg);

			//animate back
			// __sto(function (cS, sN) {
			// 	cS.removeClass().removeAttr("style").addClass("seat").html(sN);
			// },2000, calledSeat, seatNumber);
		},
		animatePause : function (section, frontText, frontColor, frontBg, oppoText, oppoColor, flash) {
			var sectionTemp = $("#" + section);
			if (flash === "flash") {
				__sto(function (cS, oT, oC) {
					cS.css("color", oC).css("background", "black").html(oT);
					// $("#seat" + sN).removeClass("seat_call").addClass("seat_call_oppo").html(oT);
				},1000, sectionTemp, oppoText, oppoColor);

				__sto(function (cS, fT, fC, fB) {
					cS.css("color", fC).css("background", fB).html(fT);
					// $("#seat" + sN).removeClass("seat_call").addClass("seat_call_oppo").html(oT);
				},2000, sectionTemp, frontText, frontColor, frontBg);
			} else {
				// console.log(sectionTemp.html() + ":" + frontColor + frontBg);
				sectionTemp.css("color", frontColor).css("background", frontBg);
			}
		},
		animateEF : function (efId,  frontText, frontColor, frontBg, oppoText, oppoColor ) {
			var calledSeat = $("#" + efId);
			//animate to front
			// calledSeat.css("color", frontColor).css("background", frontBg).html(frontText);
			//animate to opposite
			__sto(function (cS, oT, oC) {
				cS.css("color", oC).css("background", "black").html(oT);
				// $("#seat" + sN).removeClass("seat_call").addClass("seat_call_oppo").html(oT);
			},1000, calledSeat, oppoText, oppoColor);

			__sto(function (cS, fT, fC, fB) {
				cS.css("color", fC).css("background", fB).html(fT);
				// $("#seat" + sN).removeClass("seat_call").addClass("seat_call_oppo").html(oT);
			},2000, calledSeat, frontText, frontColor, frontBg);
		}
		// ,
		// animateChainCall : function (chainId) {
		// 	var chain = $("#" + chainId);
		// 	__sto(function (chain) {
		// 		chain.css("background", "yellow");
		// 	},1000, chain);

		// 	__sto(function (chain) {
		// 		chain.css("background", "#00ff00");
		// 	},2000, chain);
		// },
		// animateChainPause : function (chainId) {
		// 	var chain = $("#" + chainId);
		// 	__sto(function (chain) {
		// 		chain.css("background", "red").css("color", "white");
		// 	},1000, chain);

		// 	__sto(function (chain) {
		// 		chain.css("background", "#00ff00").css("color", "black");
		// 	},2000, chain);
		// }
	};
})(window);


function ajaxRefreshSeats () {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_SECTION_STATUS,//ref:  /bms/js/service.js
	    data: {"section" : $("#section").val()},
	    success:function (response) {
	    	if (response.success){
	    		var showList = [];
	    		var showDouble =[];
	    		var efList = [];

	    		$.each(response.data.seatStatus, function (index,value) {
	    			if (value.seat != "00") {
	    				showList.push(value.seat);
		    			if (value.multi) {
		    				$("#double" + value.seat).addClass("double_show");
		    				showDouble.push("double" + value.seat);
		    			}else{
		    				console.log(value.multi);
		    			}
		    			seatController.animateAndonCall(value.seat, value.foreground_text, value.foreground_font_color, value.foreground_color
	    				, value.background_text, value.background_font_color);
	    			}
	    			
	    			else {
	    				seatController.animateEF(value.full_seat, value.foreground_text, value.foreground_font_color, value.foreground_color
	    				, value.background_text, value.background_font_color);
	    				efList.push(value.full_seat);
	    			}
	    			
	    		});


	    		
	    		$("#seatDiv div").each(function (index, value) {
	    			if ( $.inArray(  $(value).data("seatNumber") ,showList) < 0) {
	    				$(value).removeClass().removeAttr("style").addClass("seat").html($(value).data("seatNumber"));
	    			}
	    		});

	    		$("#multiCallDiv div").each(function (index, value) {
	    			if ( $.inArray(  $(value).attr("id") , showDouble) < 0) {
	    				$(value).removeClass("double_show");
	    			}
	    		});

	    		$("#divSection div").each(function (index, value) {
	    			if ( $.inArray(  $(value).attr("id") , efList) < 0) {
	    				$(value).css("color", "#000").css("background", "#00ff00").html($(value).attr("id"));
	    			}
	    		});
	    		
	    		// var showSectionList = [];
	    		// //pause
	    		// $.each(response.data.sectionStatus, function (index,value) {
	    		// 	showSectionList.push(value.section);
	    		// 	seatController.animatePause(value.section, value.foreground_text, value.foreground_font_color, value.foreground_color
	    		// 		, value.background_text, value.background_font_color, value.flash);
	    		// });


	    		// $("#divSection div").each(function (index, value) {
	    		// 	if ( $.inArray(  $(value).attr("id") ,showSectionList) < 0) {
	    		// 		$(value).removeClass().removeAttr("style").addClass("section").html( $(value).attr("id"));
	    		// 		if($("#section").val() === $(value).attr("id"))
	    		// 			$("#" + $("#section").val()).addClass("current_section");
	    		// 	}
	    		// });
				

				//deal with status
				if(response.data.lineStatus === "play") {
					$("#symbol").removeClass().addClass("symbol");
				} else if(response.data.lineStatus === "white-pause") {
					$("#symbol").removeClass().addClass("symbol_white_pause");
				} else if(response.data.lineStatus === "red-pause") {
					$("#symbol").removeClass().addClass("symbol_red_pause");
				} else{
					$("#symbol").removeClass().addClass("symbol_halt");
				}
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}


function initPage (argument) {
	$("#seatDiv").text("");
	ajaxGetSection();
	// $("#double06").addClass("double_show");
	// $("#double05").addClass("double_show");
	ajaxRefresh();
	$("#" + $("#section").val()).addClass("current_section");
}

function ajaxGetSection () {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: QUERY_SECTION,//ref:  /bms/js/service.js
	    async: false,
	    data: {"section" : $("#section").val()},
	    success:function (response) {
	    	if (response.success){
	    		$("#title").text(response.data.title);
	    		$.each(response.data.seat_list, function (index,value) {
	    			// $("<div />").addClass("seat").addClass("normal_seat").attr("id", "seat" + value).html(value).appendTo($("#seatDiv"));
	    			var temp = $("<div />");
	    			temp.addClass("seat").addClass("normal_seat").attr("id", "seat" + value).html(value).appendTo($("#seatDiv"));
	    			temp.data("seatNumber", value);

	    			$("<div />").addClass("double").attr("id", "double" + value).appendTo($("#multiCallDiv"));
	    		});

	    		

	    		// seatController.seatCall("06");
	    		
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}


function ajaxRefresh (planId) {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_SECTION_PANEL,//ref:  /bms/js/service.js
	    data: {"section" : $("#section").val()},
	    success:function (response) {
	    	if (response.success){
	    		$("#time").text(response.data.cur_time);
	    		$("#dpu").text(response.data.dpu);
	    		$("#lineRate").text(response.data.line_speed + " sec");
	    		$("#pauseTime").text(response.data.section_pause_time + " / " + response.data.line_pause_time + " min");
	    		$("#workingTimePercentage").text(response.data.line_urate);
	    		$("#productAmount").text(response.data.finish_cars + " / " + response.data.plan_cars + " 辆");
	    		$("#qrate").text(response.data.qrate);
	    		$("#otherSectionInfo").text(response.data.other_section_calls);

	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}


