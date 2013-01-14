$(document).ready(function () {
	$(".in").qtip({content: "s",position: {my: 'center left', at: 'center right'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'red'}});
	$(".out").qtip({content: "s",position: {my: 'bottom center', at: 'top center'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'red'}});
	$(".vq3").qtip({content: "s",position: {my: 'bottom center', at: 'top center'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'red'}});
	$(".road").qtip({content: "s",position: {my: 'center left', at: 'center right'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'red'}});
	$(".leak").qtip({content: "s",position: {my: 'bottom center', at: 'top center'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'red'}});
	$(".check").qtip({content: "s",position: {my: 'top center', at: 'bottom center'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'red'}});
	
	$(".vq2-road,.vq2-check,.vq2-leak").hover(
	  function () {
	    $(".vq2-road").addClass("border-purple");
	    $(".vq2-check").addClass("border-purple");
	  },
	  function () {
	    $(".vq2-road").removeClass("border-purple");
	    $(".vq2-check").removeClass("border-purple");
	  }
	);



	window.markMap = {"T0":209,"T1":220,"T2":224,"T3":228,"T4":232,"T5":236,"T6":240,"T7":244,"T8":248,"T9":252,"T10":256,
	"T11":271,"T12":282,"T13":286,"T14":290,"T15":294,"T16":298,"T17":302,"T18":306,"T19":310,"T20":311,
	"T21":333,"T22":344,"T23":350,"T24":356,"T25":362,"T26":268,"T27":374,"T28":380,"T29":386,"T30":392,"T31":398,"T32":414,
	"C1":420,"C2":426,"C3":430,"C4":434,"C5":438,"C6":442,"C7":446,"C8":450,"C9":456,"C10":476,
	"C11":488,"C12":490,"C13":494,"C14":498,"C15":502,"C16":504,"C17":508,"C18":512,"C19":516,"C20":520,"C21":538,
	"F1":552,"F2":556,"F3":560,"F4":564,"F5":568,"F6":572,"F7":576,"F8":580,"F9":588,"F10":600,
	"F11":614,"F12":619,"F13":624,"F14":628,"F15":633,"F16":637,"F17":642,"F18":646,"F19":650,"F20":663

	};
	// console.log(markMap['T1']);
	//add head class
	$("#headAssemblyLi").addClass("active");
	$("#leftMonitoringLi").addClass("active");

	$("#rangeT1").data("sectionName","T1");
	$("#rangeT2").data("sectionName","T2");
	$("#rangeT3").data("sectionName","T3");
	$("#rangeC1").data("sectionName","C1");
	$("#rangeC2").data("sectionName","C2");
	$("#rangeF1").data("sectionName","F1");
	$("#rangeF2").data("sectionName","F2");


	$("#stopMark").hide();
	// var vvv = markMap.T01;
	// $(".main").qtip({
	// 	content: "hehe",
	// 	position: {
	// 		my: 'bottom center', 
	// 		at: 'top left',
	// 			adjust: {  
	//             x: vvv  ,y:70
	//         }  
	// 	},
	// 	show: {
	// 				event: false, // Don't specify a show event...
	// 				ready: true // ... but show the tooltip when ready
	// 			},
	// 			hide: false, // Don't specify a hide event either!
	// 	style: {
	// 		tip: true,
	// 		classes: 'ui-tooltip-red'
	// 	}
         
 //    });
	$("#pauseTimeArea").toggle();
	$("#togglePauseTime").change(function () {
		console.log("ge");
		$("#pauseTimeArea").toggle();
	});




	$("#pbsBalanceModal").modal("hide");
	$("#pbsBalance").live("click", function () {
		ajaxPbsBalance();
		
	});

	$("#vq1BalanceModal").modal("hide");
	$("#vq1Balance").live("click", function () {
		ajaxVq1Balance();

		// $("#vq1BalanceModal").modal("show");
	});

	$("#vq1ExceptionBalanceModal").modal("hide");
	$("#vq1ExceptionBalance").live("click", function () {
		ajaxVq1ExceptionBalance();
		// $("#vq1ExceptionBalanceModal").modal("show");
	});
	// });
	$(".range").click(function (e) {
		console.log($(e.target).data("sectionName"));
		window.open("/bms/execution/MonitoringSection?section=" + $(e.target).data("sectionName"),"_blank","width=320, height=128, location=no");
	});
	
	$("#andon_board").click(function () {
		window.open("/bms/execution/MonitoringWorkshop","_blank","width=384, height=240, location=no");
	});

	//added by wujun
	$("#welcomeShop").click(function () {
		window.open("/bms/execution/WelcomeShop","_blank","width=384, height=240, location=no");
	});

	//added by wujun
	$("#welcomeSection").click(function (e) {
		console.log($(e.target).data("sectionName"));
		window.open("/bms/execution/WelcomeSection","_blank","width=320, height=128, location=no");
	});

	$("#radioInfo").click(function () {
		getTipInfo();
	});

	//added by wujun


	
	getTipInfo();
	setInterval(function () {
		getTipInfo();
	},10000);
	ajaxRefresh();
	setInterval(function () {
		ajaxRefresh();
	},5000);
});

!function (window) {
	window.tipRadio = {};
	tipRadio.type = "none";
	tipRadio.clearLast = function () {
		console.log(this.type);
		if (this.type === "productInfo") {
			$('.node_pbs').qtip('toggle', false);
			$('.node_t0').qtip('toggle', false);
			$('.node_vq1').qtip('toggle', false);
		} else if (this.type === "qualityInfo") {
			$('.node_vq1').qtip('toggle', false);
		} else if (this.type === "storeInfo") {
			$('.pbs').qtip('toggle', false);
			$('.vq1').qtip('toggle', false);
		} 
	};
}(window);
// {"success":true,"message":"OK","data":{"line_speed":120,"line_run_time":0,"line_urate":"-","pause_time":{"total":-3029,"T1":0,"T2":0,"T3":-3029,"C1":0,"C2":0,"F1":0,"F2":0,"VQ1":0,"L1":0,"EF1":0,"EF2":0,"EF3":0}}}
function getTipInfo (argument) {
	ajaxThreeInfo();
	// // hideAllTips();
	// var type = $("#radioInfo input:checked").val();
	// if(type !== window.tipRadio.type) {
	// 	window.tipRadio.clearLast();
	// }
	
	// if (type === "productInfo") {
	// 	ajaxProductInfo();
	// } else if (type === "qualityInfo") {
	// 	ajaxQualityInfo();
	// } else if (type === "storeInfo") {
	// 	ajaxStoreInfo();
	// } else {
	// 	window.tipRadio.type = "hideAllInfo";
	// }
}
function ajaxRefresh () {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_MONITOR_INFO,//ref:  /bms/js/service.js
	    data: {},
	    success:function (response) {
	    	if (response.success){
	    		$("#line_speed").text(response.data.line_speed);
	    		$("#line_urate").text(response.data.line_urate);
	    		$("#totalPauseTime").text(response.data.pause_time.total);

	    	
	    		$("#DPVeDRR").text(response.data.DPU.total + " / " + response.data.DRR.total);
	    		$("#VQ1DPVeDRR").text(response.data.DPU.VQ1 + " / " + response.data.DRR.VQ1);
	    		$("#VQ2DPVeDRR").text(response.data.DPU.VQ2 + " / " + response.data.DRR.VQ2);
	    		$("#VQ3DPVeDRR").text(response.data.DPU.VQ3 + " / " + response.data.DRR.VQ3);

	    		$("#pauseTimeDevice").text(response.data.pause_time.device);
	    		$("#pauseTimeT1").text(response.data.pause_time.T1);
	    		$("#pauseTimeT2").text(response.data.pause_time.T2);
	    		$("#pauseTimeT3").text(response.data.pause_time.T3);
	    		$("#pauseTimeC1").text(response.data.pause_time.C1);
	    		$("#pauseTimeC2").text(response.data.pause_time.C2);
	    		$("#pauseTimeF1").text(response.data.pause_time.F1);
	    		$("#pauseTimeF2").text(response.data.pause_time.F2);

	    		if(response.data.pause_seat !== "") {
	    			$("#stopMark").show().text(response.data.pause_seat);
	    			$("#stopMark").css("left", window.markMap[response.data.pause_seat] + "px");
	    	
	    		} else {
	    			$("#stopMark").hide();
	    				// $('.main').qtip('toggle', false);
	    		}

	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}


function ajaxThreeInfo () {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_MONITOR_LABEL,//ref:  /bms/js/service.js
	    data: {},
	    success:function (response) {
	    	if (response.success){
	    		qtipMe(".node_pbs", response.data.list.production.PBS, "blue");
	    		qtipMe(".node_t0", response.data.list.production.T0, "blue");
	    		qtipMe(".node_vq1", response.data.list.quality.VQ1, "red");
    			qtipMe(".pbs", response.data.list.balance.PBS, "purple");
    			qtipMe(".vq1", response.data.list.balance.VQ1, "purple");
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}

$("#liDetecthouse").live("click", hideAllTips);
$("#liAssembly").live("click", showAllTips);

function hideAllTips (argument) {
	$('.node_pbs').qtip('toggle', false);
	$('.node_t0').qtip('toggle', false);
	$('.node_vq1').qtip('toggle', false);
	$('.pbs').qtip('toggle', false);
	$('.vq1').qtip('toggle', false);

	$('.in').qtip('toggle', true);
	$('.out').qtip('toggle', true);
	$('.vq3').qtip('toggle', true);
	$('.road').qtip('toggle', true);
	$('.check').qtip('toggle', true);
	$('.leak').qtip('toggle', true);
}
function showAllTips (argument) {
	$('.node_pbs').qtip('toggle', true);
	$('.node_t0').qtip('toggle', true);
	$('.node_vq1').qtip('toggle', true);
	$('.pbs').qtip('toggle', true);
	$('.vq1').qtip('toggle', true);

	$('.in').qtip('toggle', false);
	$('.out').qtip('toggle', false);
	$('.vq3').qtip('toggle', false);
	$('.road').qtip('toggle', false);
	$('.check').qtip('toggle', false);
	$('.leak').qtip('toggle', false);
}
function ajaxProductInfo () {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_MONITOR_LABEL,//ref:  /bms/js/service.js
	    data: {"type":"production"},
	    success:function (response) {
	    	if (response.success){
	    		if (window.tipRadio.type === "productInfo") {
	    			$(".node_pbs").qtip('option', 'content.text', response.data.list.PBS);
	    			$(".node_vq1").qtip('option', 'content.text', response.data.list.VQ1);
	    			$(".node_t0").qtip('option', 'content.text', response.data.list.T0);
	    		} else {
	    			qtipMe(".node_pbs", response.data.list.PBS, "blue");
		    		qtipMe(".node_vq1", response.data.list.VQ1, "blue");
		    		qtipMe(".node_t0", response.data.list.T0, "blue");
	    		}
	    		window.tipRadio.type = "productInfo";
	    		

	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}

function ajaxQualityInfo () {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_MONITOR_LABEL,//ref:  /bms/js/service.js
	    data: {"type":"quality"},
	    success:function (response) {
	    	if (response.success){
	    		if (window.tipRadio.type === "qualityInfo") {
	    			$(".node_vq1").qtip('option', 'content.text', response.data.list.VQ1);
	    		} else {
	    			qtipMe(".node_vq1", response.data.list.VQ1, "red");
	    		}
	    		window.tipRadio.type = "qualityInfo";
	    		
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}

function ajaxStoreInfo () {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_MONITOR_LABEL,//ref:  /bms/js/service.js
	    data: {"type":"balance"},
	    success:function (response) {
	    	if (response.success){
	    		if (window.tipRadio.type === "storeInfo") {
	    			$(".pbs").qtip('option', 'content.text', response.data.list.PBS);
	    			$(".vq1").qtip('option', 'content.text', response.data.list.VQ1);
	    		} else {
	    			qtipMe(".pbs", response.data.list.PBS, "purple");
	    			qtipMe(".vq1", response.data.list.VQ1, "purple");
	    		}
	    		window.tipRadio.type = "storeInfo";
	    		
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}

function qtipMe (target, text, color) {
	if (target === ".pbs") {
		$(target).qtip({
			content: text,
			position: {
				my: 'bottom center', 
				at: 'top center',
				adjust: {  
		            y:35
		        } 
			},
			show: {
						event: false, // Don't specify a show event...
						ready: true // ... but show the tooltip when ready
					},
					hide: false, // Don't specify a hide event either!
			style: {
				tip: true,
				classes: 'ui-tooltip-' + color
			}
	         
	    });
	} else {
		$(target).qtip({
			content: text,
			position: {
				my: 'bottom center', 
				at: 'top center'
			},
			show: {
						event: false, // Don't specify a show event...
						ready: true // ... but show the tooltip when ready
					},
					hide: false, // Don't specify a hide event either!
			style: {
				tip: true,
				classes: 'ui-tooltip-' + color
			}
	         
	    });
	}
	
     
}



// balance
function ajaxPbsBalance () {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_BALANCE_DETAIL,//ref:  /bms/js/service.js
	    data: {"node":"PBS"},
	    success:function (response) {
	    	if (response.success){
	    		$("#pbsBalanceTable tbody").text("");
	    		$.each(response.data, function (index, value) {
	    			var tr = $("<tr />");
	    			$("<td />").html(value.series).appendTo(tr);
	    			$("<td />").html(value.vin).appendTo(tr);
	    			$("<td />").html(value.type).appendTo(tr);
	    			$("<td />").html(value.color).appendTo(tr);
	    			$("<td />").html(value.time).appendTo(tr);
	    			
	    			$("#pbsBalanceTable tbody").append(tr);
	    		});

	    		// hideAllTips();
				$("#pbsBalanceModal").modal("show");
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}

// balance
function ajaxVq1Balance () {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_BALANCE_DETAIL,//ref:  /bms/js/service.js
	    data: {"node":"VQ1"},
	    success:function (response) {
	    	if (response.success){
	    		$("#vq1BalanceTable tbody").text("");
	    		$.each(response.data, function (index, value) {
	    			var tr = $("<tr />");
	    			$("<td />").html(value.series).appendTo(tr);
	    			$("<td />").html(value.vin).appendTo(tr);
	    			$("<td />").html(value.type).appendTo(tr);
	    			$("<td />").html(value.color).appendTo(tr);
	    			$("<td />").html(value.time).appendTo(tr);
	    			
	    			$("#vq1BalanceTable tbody").append(tr);
	    		});

	    		// hideAllTips();
				$("#vq1BalanceModal").modal("show");
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}

// balance
function ajaxVq1ExceptionBalance () {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_BALANCE_DETAIL,//ref:  /bms/js/service.js
	    data: {"node":"VQ1-EXCEPTION"},
	    success:function (response) {
	    	if (response.success){
	    		$("#vq1ExceptionBalanceTable tbody").text("");
	    		$.each(response.data, function (index, value) {
	    			var tr = $("<tr />");
	    			$("<td />").html(value.series).appendTo(tr);
	    			$("<td />").html(value.vin).appendTo(tr);
	    			$("<td />").html(value.type).appendTo(tr);
	    			$("<td />").html(value.color).appendTo(tr);
	    			$("<td />").html(value.time).appendTo(tr);
	    			
	    			$("#vq1ExceptionBalanceTable tbody").append(tr);
	    		});

	    		// hideAllTips();
				$("#vq1ExceptionBalanceModal").modal("show");
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}