$(document).ready(function () {
	initPage();
	setInterval(function () {
		ajaxRefresh();
	// 	seatController.animateChainCall("EF1");
	// seatController.animateChainPause("mainChain");
	},60000);
	setInterval(function () {
		ajaxRefreshSeats();
	},10000);
	
});


function initPage (argument) {
	ajaxRefresh();
	$("#sectionT1").hide();
	$("#sectionT2").hide();
	$("#sectionT3").hide();
	$("#sectionC1").hide();
	$("#sectionC2").hide();
	$("#sectionF1").hide();
	$("#sectionF2").hide();
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


function switchCalledSection (section, showFacade) {
	if(showFacade) {
		$("#facade" + section).show();
		$("#section" + section).hide();
	} else {
		$("#section" + section).show();
		$("#facade" + section).hide();
	}
}
function ajaxRefreshSeats () {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_SECTION_STATUS,//ref:  /bms/js/service.js
	    data: {"section" : $("#section").val()},
	    success:function (response) {
	    	if (response.success){

	    		var VQ1FlashFlag = false;
	    		
	    		//hide or show facade
	    		var hideFacadeList = [];
	    		$.each(response.data.sectionStatus, function (index, value) {
	    			hideFacadeList.push(value.section);
	    			switchCalledSection(value.section, false);
	    			if (value.section === "VQ1") {
	    				seatController.animateVQ1(value.background_text);
	    				VQ1FlashFlag = true;
	    			}
	    			
	    		});
	    		if (!VQ1FlashFlag) {
	    			$("#VQ1").css("background", "#00FF00");
					$("#VQ1Div").css("color", "black").html("VQ1");
	    		}
	    		var sectionList = ["T1", "T2", "T3", "C1", "C2", "F1", "F2"];
	    		$(sectionList).each(function (index, value) {
	    			if ( $.inArray(  value ,hideFacadeList) < 0) {
	    				switchCalledSection(value, true);
	    			}
	    		});



	    		$(".normal_seat").css("color", "#00FF00");

	    		var tempEFList = [];
	    		//dealing with seats
	    		$.each(response.data.seatStatus, function (index,value) {
	    			if (value.seat != "00") {
		    			$("#" + value.full_seat +" span").css("color", "black");
		    			seatController.dealWithRawType(value.full_seat, value.flash_type);
		    		} else {
		    			tempEFList.push(value.full_seat);
		    			seatController.animateEF(value.full_seat, value.foreground_text, value.foreground_font_color, value.foreground_color
	    				, value.background_text, value.background_font_color);
		    		}

	    			// this.seatController.dealWithRawType(value.full_seat, value.flash_type);
	    			
	    		});
	    		seatController.clearEF(tempEFList);
	    		seatController.dealWithFlash();

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
		flashingList : [],
		
		rawList : [],

		toFlashList : [],
		stopList : [],
		efList : [],
		VQ1 : false,
		dealWithRawType : function (seatNumber, flashType) {
			//delegate the raw type to flash Type "yellow" "yellow_flash" etc..
			var type = "";
			if(flashType === "block")
				type = "yellow";
			else if (flashType === "fast")
				type = "yellow_quick_flash";
			else if (flashType === "normal")
				type = "yellow_flash";
			else if (flashType === "red")
				type = "red_flash";
			
			this.rawList.push({"seatNumber" : seatNumber , "type": type});

			
		},
		dealWithFlash : function () {
			//value --new             v-----old
			$(this.rawList).each(function (index, value) {
					var flag = 0;
					$(seatController.flashingList).each(function (i, v) {
						if (v.seatNumber === value.seatNumber && v.type === value.type) {
							//do nothing
							// stopList.push({"seatNumber", v.seatNumber, "type" : v.type});
							// toFlashList.push({"seatNumber", value.seatNumber, "type" : value.type});
							flag =1;
						} else if (v.seatNumber === value.seatNumber && v.type !== value.type){
							//stop origin and flash new
							seatController.flashingList.splice(i, 1);
							seatController.stopList.push({"seatNumber" : v.seatNumber, "type" : v.type, "intervalId": v.intervalId});
							seatController.toFlashList.push({"seatNumber" : value.seatNumber, "type" : value.type});
							flag = 1;
						}
					});
				if(flag == 0) {
					seatController.toFlashList.push({"seatNumber" : value.seatNumber, "type" : value.type});
				}
				flag= 0;
			});
			// console.log(this.flashingList);
			// console.log(this.rawList);
			// console.log(this.stopList);
			this.stopUngoingFlashes();
			// this.flashingList = this.toFlashList;
			// console.log("===deal flash");
			// console.log(this.toFlashList);
		     $.each(this.toFlashList, function (index, value){
		     	
		     	if (value.type === "yellow") {

					$("#" + value.seatNumber).css("background", "yellow");
					seatController.flashingList.push({"type" : "yellow", "seatNumber" : value.seatNumber});
				} else if (value.type === "yellow_flash") {
					var intervalId = setInterval(seatController.yellow_quick_flash, 1000, value.seatNumber);
					seatController.flashingList.push({"seatNumber" : value.seatNumber,
						"type" : value.type,
						"intervalId" : intervalId});
				} else if (value.type === "yellow_quick_flash") {
					var intervalId = setInterval(seatController.yellow_quick_flash, 500, value.seatNumber);
					seatController.flashingList.push({"seatNumber" : value.seatNumber,
						"type" : value.type,
						"intervalId" : intervalId});
				} else if (value.type === "red_flash") {
					var intervalId = setInterval(seatController.red_flash, 1000, value.seatNumber);
					seatController.flashingList.push({"seatNumber" : value.seatNumber,
						"type" : value.type,
						"intervalId" : intervalId});
				}
		     });

		     this.rawList = [];
		     this.toFlashList = [];
		     this.stopList = [];

			
		},
		stopUngoingFlashes : function  () {
		     $.each(this.stopList, function (index, value) {		    
		     	 seatController.clearFlash(value.seatNumber, value.type, value.intervalId);
		     });
		},
		yellow : function (seatNumber) {
			
		},
		yellow_quick_flash : function () {
			var seat = $("#" + arguments[0]);
			// console.log($(seat).getHexBackgroundColor());
			if($(seat).getHexBackgroundColor() === "#000000")
				seat.css("background", "yellow");
			else
				seat.css("background", "black");
		},
		red_flash : function () {
			var seat = $("#" + arguments[0]);
			var seatFont = $("#" + arguments[0] + " span");

			if($(seat).getHexBackgroundColor() === "#000000") {

				seatFont.css("color", "white");
				seat.css("background", "red");
				}
			else {
				seatFont.css("color", "black");
				seat.css("background", "black");
			}
		},
		clearFlash : function (seatNumber, flashType, intervalId) {
			$("#" + seatNumber).css("background", "#00FF00");
			$("#" + seatNumber +" span").css("color", "#000");
			if (flashType === "yellow") {
				
			} else{
				clearInterval(intervalId);
			}
		},
		// dealWithVQ1 : function (ifFlash, backgroundText) {
		// 	if (ifFlash !== this.VQ1) {
		// 		var intervalId = setInterval(seatController.VQ1Flash, 500, value.seatNumber);
		// 		this.VQ1IntervalId = intervalId;

		// 	}
		// },
		// VQ1IntervalId : -1,
		// VQ1Flash : function () {
		// 	if ()
		// },
		animateVQ1 : function (backgroundText) {
			// $("#VQ1").css("background-color", VQ1color);
			__sto(function (bT) {
				$("#VQ1").css("background", "black");
				$("#VQ1Div").css("color", "yellow").html(bT);
				// $("#seat" + sN).removeClass("seat_call").addClass("seat_call_oppo").html(oT);
			},1000, backgroundText);

			__sto(function () {
				$("#VQ1").css("background", "yellow");
				$("#VQ1Div").css("color", "black").html("VQ1");
				// $("#seat" + sN).removeClass("seat_call").addClass("seat_call_oppo").html(oT);
			},2000);
		},
		animateChainCall : function (chainId) {
			var chain = $("#" + chainId);
			__sto(function (chain) {
				chain.css("background", "yellow");
			},1000, chain);

			__sto(function (chain) {
				chain.css("background", "#00ff00");
			},2000, chain);
		},
		animateChainPause : function (chainId) {
			var chain = $("#" + chainId);
			__sto(function (chain) {
				chain.css("background", "red").css("color", "white");
			},1000, chain);

			__sto(function (chain) {
				chain.css("background", "#00ff00").css("color", "black");
			},2000, chain);
		},
		animateEF : function (efId,  frontText, frontColor, frontBg, oppoText, oppoColor ) {
			// if ($.inArray(efId, efList) < 0) {
			// 	this.efList.push(efId);
			// }
			var calledSeat = $("#" + efId);
			//animate to opposite
			__sto(function (cS, oT, oC) {
				cS.css("color", oC).css("background", "black").html(oT);
				// $("#seat" + sN).removeClass("seat_call").addClass("seat_call_oppo").html(oT);
			},1000, calledSeat, oppoText, oppoColor);

			__sto(function (cS, fT, fC, fB) {
				cS.css("color", fC).css("background", fB).html(fT);
				// $("#seat" + sN).removeClass("seat_call").addClass("seat_call_oppo").html(oT);
			},2000, calledSeat, frontText, frontColor, frontBg);
		},
		clearEF : function (array) {
			$.each(["L1", "EF1", "EF2", "EF3"],function (index, value) {
				if ($.inArray(value, array) < 0) {
					$("#" + value).css("color", "#000").css("background", "#00ff00");
				}
			})
		}
	};

	$.fn.getHexBackgroundColor = function(rgb) {
	    var rgb = $(this).css('background-color');
	    if(!$.browser.msie){
	        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	        function hex(x) {return ("0" + parseInt(x).toString(16)).slice(-2);}
	        rgb= "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
	    }
	    return rgb;
	}
})(window);
