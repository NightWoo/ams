$(document).ready(function () {
	$(".inware-data").qtip({content: "s",position: {my: 'center bottom', at: 'bottom center'},show: {event: false,ready: false}, 	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'blue'}});
	$(".outware-data").qtip({content: "s",position: {my: 'bottom center', at: 'top left'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'blue'}});
	$(".vq3-data").qtip({content: "s",position: {my: 'bottom center', at: 'top left'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'red'}});
	$(".road-data").qtip({content: "s",position: {my: 'center left', at: 'bottom right'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'red'}});
	$(".leak-data").qtip({content: "s",position: {my: 'top center', at: 'bottom center'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'red'}});
	$(".vq1-data").qtip({content: "s",position: {my: 'bottom center', at: 'top center'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'red'}});
	
	//warehouse tips
	$(".inware-warehouse").qtip({content: "s",position: {my: 'center left', at: 'bottom right'},show: {event: false,ready: false}, 	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'blue'}});
	$(".outware-warehouse").qtip({content: "s",position: {my: 'bottom center', at: 'top left'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'blue'}});
	$(".vq3-warehouse").qtip({content: "s",position: {my: 'bottom center', at: 'top left'},show: {event: false,ready: false},	hide: false,style: {tip: true,classes: 'ui-tooltip-' + 'red'}});

	qtipMe(".node_pbs", "-", "blue");
	qtipMe(".node_t0", "-", "blue");
	qtipMe(".node_vq1", "-", "red");
	qtipMe(".pbs", "-", "purple");
	qtipMe(".vq1", "-", "purple");

	$(".vq2-road-data,.vq2-check-data,.vq2-leak-data").hover(
	  function () {
	    $(".vq2-road-data").addClass("border-purple");
	    $(".vq2-check-data").addClass("border-purple");
	    $(".vq2-leak-data").addClass("border-purple");
	  },
	  function () {
	    $(".vq2-road-data").removeClass("border-purple");
	    $(".vq2-check-data").removeClass("border-purple");
	    $(".vq2-leak-data").removeClass("border-purple");
	  }
	);

	// $("#block").hide().text("");
	$("#stockyardModal").modal("hide");
	//block click handler
	$(".stockyard,.B01,.B02").live("click", function () {
		var blockNumber = $(this).html();
		if (blockNumber == $("#block").data("currentBlock")) {
			$("#block").hide();
			$("#block").data("currentBlock", "");
		} else {
			$("#block").data("currentBlock", blockNumber);
			ajaxStockyard(blockNumber);
		}
	});
	$(".thumbnail").live("click", function () {
		// console.log();
		ajaxRow($(this).children("p").html());
	});

	window.markMap = {"T0":410,"T01":410,"T02":410,"T03":410,"T04":410,"T05":410,"T06":410,"T07":410,"T08":410,"T09":410,"T10":410,"T11":410,
	"T12":482,"T13":482,"T14":482,"T15":482,"T16":482,"T17":482,"T18":482,"T19":482,"T20":482,"T21":482,
	"T22":554,"T23":554,"T24":554,"T25":554,"T26":554,"T27":554,"T28":554,"T29":554,"T30":554,"T31":554,"T32":554,
	"C01":644,"C02":644,"C03":644,"C04":644,"C05":644,"C06":644,"C07":644,"C08":644,"C09":644,"C10":644,
	"C11":716,"C12":716,"C13":716,"C14":716,"C15":716,"C16":716,"C17":716,"C18":716,"C19":716,"C20":716,"C21":716,
	"F01":841,"F02":841,"F03":841,"F04":841,"F05":841,"F06":841,"F07":841,"F08":841,"F09":841,"F10":841,
	"F11":913,"F12":913,"F13":913,"F14":913,"F15":913,"F16":913,"F17":913,"F18":913,"F19":913,"F20":913
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

	// $("#pauseTimeArea").toggle();
	$("#togglePauseTime").change(function () {
		$("#pauseTimeArea").toggle();
	});


	$("#modal").modal("hide");
	$(".vq2-road-data,.vq2-check-data,.vq2-leak-data").live("click", function () {
		ajaxBalance("VQ2");
	});
	$(".vq3-balance-data").live("click", function () {
		ajaxBalance("VQ3");
	});

	$("#pbsBalanceModal").modal("hide");
	$("#pbsBalance, #ui-tooltip-12").live("click", function () {
		ajaxPbsBalance();
		
	});

	$("#vq1BalanceModal").modal("hide");
	$("#vq1Balance, #ui-tooltip-13").live("click", function () {
		ajaxVq1Balance();

		// $("#vq1BalanceModal").modal("show");
	});

	$("#vq1ExceptionBalanceModal").modal("hide");
	$("#vq1ExceptionBalance, #ui-tooltip-9-content").live("click", function () {
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

	// $("#radioInfo").click(function () {
	// 	getTipInfo();
	// });

	//added by wujun


	
	ajaxThreeInfo();
	var assemblyHouseIntervalId = setInterval(function () {
		ajaxThreeInfo();
	},10000);
	console.log(assemblyHouseIntervalId);
	ajaxRefresh();
	var assemblyHouseRefreshId = setInterval(function () {
		ajaxRefresh();
	},5000);
	
	var detectHouseRefreshId ;
	$("#liAssembly").live("click", showAssemblyTips);
	$("#liDetect").live("click", showDetectTips);
	$("#liWarehouse").live("click", showWarehouseTips);

function showWarehouseTips (argument) {
	ajaxGetStock();
	detectHouseRefreshId = setInterval(function () {
		ajaxGetStock();
	},10000);
	clearInterval(assemblyHouseIntervalId);
	clearInterval(assemblyHouseRefreshId);
	$('.node_pbs').qtip('toggle', false);
	$('.node_t0').qtip('toggle', false);
	$('.node_vq1').qtip('toggle', false);
	$('.pbs').qtip('toggle', false);
	$('.vq1').qtip('toggle', false);

	$(".data-node").qtip('toggle', false);

	$(".warehouse-node").qtip('toggle', true);

	// $('.inware-data').qtip('toggle', true);
	// $('.outware-data').qtip('toggle', true);
	// $('.vq3-data').qtip('toggle', true);
	// $('.road-data').qtip('toggle', true);
	// $('.check-data').qtip('toggle', true);
	// $('.leak-data').qtip('toggle', true);

}

function showDetectTips (argument) {
	ajaxGetStock();
	detectHouseRefreshId = setInterval(function () {
		ajaxGetStock();
	},10000);
	clearInterval(assemblyHouseIntervalId);
	clearInterval(assemblyHouseRefreshId);
	$('.node_pbs').qtip('toggle', false);
	$('.node_t0').qtip('toggle', false);
	$('.node_vq1').qtip('toggle', false);
	$('.pbs').qtip('toggle', false);
	$('.vq1').qtip('toggle', false);

	$(".data-node").qtip('toggle', true);

	$(".warehouse-node").qtip('toggle', false);

	// $('.inware-data').qtip('toggle', true);
	// $('.outware-data').qtip('toggle', true);
	// $('.vq3-data').qtip('toggle', true);
	// $('.road-data').qtip('toggle', true);
	// $('.check-data').qtip('toggle', true);
	// $('.leak-data').qtip('toggle', true);

}
function showAssemblyTips (argument) {
	ajaxThreeInfo();
	assemblyHouseIntervalId = setInterval(function () {
		ajaxThreeInfo();
	},10000);
	ajaxRefresh();
	assemblyHouseRefreshId = setInterval(function () {
		ajaxRefresh();
	},5000);
	$('.node_pbs').qtip('toggle', true);
	$('.node_t0').qtip('toggle', true);
	$('.node_vq1').qtip('toggle', true);
	$('.pbs').qtip('toggle', true);
	$('.vq1').qtip('toggle', true);

	$(".data-node").qtip('toggle', false);

	$(".warehouse-node").qtip('toggle', false);
	// $('.inware').qtip('toggle', false);
	// $('.outware').qtip('toggle', false);
	// $('.vq3').qtip('toggle', false);
	// $('.road').qtip('toggle', false);
	// $('.check').qtip('toggle', false);
	// $('.leak').qtip('toggle', false);
}
});



function qtipMe (target, text, color) {
	if (target === ".pbs" || target ==='.vq1') {
		$(target).qtip({
			content: text,
			position: {
				my: 'center', 
				at: 'center',
				// adjust: {  
		  //           y:5
		  //       } 
			},
			// effect: false,
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
				my: 'top center', 
				at: 'bottom center',
				adjust: {  
		            y:5
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
	}
}

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

	    		$("#recycleCar").text(parseInt(response.data.balance.VQ1) + parseInt(response.data.balance.VQ2) +
	    			parseInt(response.data.balance.VQ3));
	    		
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

	    		$(".node_pbs").qtip('option', 'content.text', response.data.list.production.PBS.all);
	    		$(".node_pbs").data("subData", response.data.list.production.PBS);

	    		$(".node_t0").qtip('option', 'content.text', response.data.list.production.T0.all);
	    		$(".node_t0").data("subData", response.data.list.production.T0);
	    		
	    		$(".node_vq1").qtip('option', 'content.text', response.data.list.quality.VQ1);
    			$(".pbs").qtip('option', 'content.text', response.data.list.balance.PBS);
    			$(".vq1").qtip('option', 'content.text', response.data.list.balance.VQ1);

	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}

$(".sub-flag").live("hover", function () {
	var tipContent = "";
	$.each($(this).data("subData"), function (k, v) {
		if (k != "all") {
			if (k == "6B") k = "思锐";
			tipContent += k + ": " + v + "  ";
		}
	});
	console.log(tipContent);
	$(this).qtip('option', 'content.text', tipContent.substr(0, tipContent.length - 1));
}).live("mouseout", function () {
	
	$(this).qtip('option', 'content.text', $(this).data("subData").all);
});

function ajaxGetStock (argument) {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: MONITOR_PRODUCT_INFO,//ref:  /bms/js/service.js
	    data: {},
	    success:function (response) {
	    	if (response.success){
	    		//refresh linespeed ,line urate ,total pause time
	    		$("#line_speed").text(response.data.line_speed);
	    		$("#line_urate").text(response.data.line_urate);
	    		$("#totalPauseTime").text(response.data.pause_time.total);
	    		$("#recycleCar").text(parseInt(response.data.balance.VQ1.all) + parseInt(response.data.balance.VQ2.all) +
	    			parseInt(response.data.balance.VQ3.all));
	    		//refresh tips
	    		$(".outware-data").qtip('option', 'content.text', response.data.balance.warehourse_out.all);
	    		$(".outware-data").data("subData", response.data.balance.warehourse_out);
	    		
	    		$(".inware-data").qtip('option', 'content.text', response.data.balance.warehourse_in.all);
	    		$(".inware-data").data("subData", response.data.balance.warehourse_in);

	    		$(".vq3-data").qtip('option', 'content.text', response.data.drr.VQ3.all);
	    		$(".vq3-data").data("subData", response.data.drr.VQ3);
    			$(".road-data").qtip('option', 'content.text', response.data.drr.VQ2_ROAD.all);
	    		$(".road-data").data("subData", response.data.drr.VQ2_ROAD);
    			$(".leak-data").qtip('option', 'content.text', response.data.drr.VQ2_LEAK.all);
	    		$(".leak-data").data("subData", response.data.drr.VQ2_LEAK);
    			$(".vq1-data").qtip('option', 'content.text', response.data.drr.VQ1.all);
	    		$(".vq1-data").data("subData", response.data.drr.VQ1);


	    		//warehouse things
	    		$(".outware-warehouse").qtip('option', 'content.text', response.data.balance.warehourse_out.all);
	    		$(".outware-warehouse").data("subData", response.data.balance.warehourse_out);
	    		
	    		$(".inware-warehouse").qtip('option', 'content.text', response.data.balance.warehourse_in.all);
	    		$(".inware-warehouse").data("subData", response.data.balance.warehourse_in);

	    		$(".vq3-warehouse").qtip('option', 'content.text', response.data.drr.VQ3.all);
	    		$(".vq3-warehouse").data("subData", response.data.drr.VQ3);

	    		//warehouse total 
	    		$(".area-total-amount").html(response.data.warehourse_cars.all);

    			//refresh stock
    			$(".vq3-balance-data").html(response.data.balance.VQ3.all);
    			$(".vq2-road-data").html(response.data.balance.VQ2.all);
    			$(".stock-amount").html(response.data.balance.warehourse_cars);
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}

// function ajaxGetWarehouseStock (argument) {
// 	$.ajax({
// 		type: "get",//使用get方法访问后台
// 	    dataType: "json",//返回json格式的数据
// 	    url: MONITOR_PRODUCT_INFO,//ref:  /bms/js/service.js
// 	    data: {},
// 	    success:function (response) {
// 	    	if (response.success){
// 	    		//refresh tips
// 	    		$(".outware-warehouse").qtip('option', 'content.text', response.data.balance.warehourse_out.all);
// 	    		$(".outware-warehouse").data("subData", response.data.balance.warehourse_out);
	    		
// 	    		$(".inware-warehouse").qtip('option', 'content.text', response.data.balance.warehourse_in.all);
// 	    		$(".inware-warehouse").data("subData", response.data.balance.warehourse_in);

// 	    		$(".vq3-warehouse").qtip('option', 'content.text', response.data.drr.VQ3.all);
// 	    		$(".vq3-warehouse").data("subData", response.data.drr.VQ3);
// 	    	} else {
// 	    		alert(response.message);
// 	    	}
// 	    },
// 	    error:function(){alertError();}
// 	});
// }


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
function ajaxBalance (node) {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: SHOW_BALANCE_DETAIL,//ref:  /bms/js/service.js
	    data: {"node" : node},
	    success:function (response) {
	    	if (response.success){
	    		//change title
	    		$("#modalTitle").text(node + "结存明细");
	    		$("#modalTable tbody").text("");
	    		$.each(response.data, function (index, value) {
	    			var tr = $("<tr />");
	    			$("<td />").html(value.series).appendTo(tr);
	    			$("<td />").html(value.vin).appendTo(tr);
	    			$("<td />").html(value.type).appendTo(tr);
	    			$("<td />").html(value.color).appendTo(tr);
	    			$("<td />").html(value.time).appendTo(tr);
	    			
	    			$("#modalTable tbody").append(tr);
	    		});

				$("#modal").modal("show");
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}

//block click handler
	$(".area-a,.area-b,.area-c,.area-d,.area-e,.area-e-right,.area-f").live("click", function () {
		var blockNumber = $(this).html();
		if (blockNumber == $("#blockDetail").data("currentBlock")) {
			$("#blockDetail").hide();
			$("#blockDetail").data("currentBlock", "");
		} else {
			$("#blockDetail").data("currentBlock", blockNumber);
			ajaxStockyard(blockNumber);
		}
	});
function ajaxStockyard (areaName) {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: MONITOR_AREA_INFO,//ref:  /bms/js/service.js
	    data: {"area" : areaName},
	    success:function (response) {
	    	if (response.success){
	    		//clear Text
	    		$("#blockDetail").text("");
	    		$.each(response.data, function (index, value) {
	    			var a = $("<a />").addClass("thumbnail").attr("href", "#");
	    			var p = $("<p />").addClass("pull-left").text(value.row);
	    			var progress = $("<div />").addClass("progress");
	    			var bar = $("<div />").addClass("bar").attr("style", "width:" + (parseInt(value.quantity) / parseInt(value.capacity) * 100) + "%").text(value.quantity + "/" + value.capacity);
	    			if (value.quantity == value.capacity) {
	    				progress.removeClass().addClass("progress").addClass("progress-success");
	    			} else if (value.quantity == "0"){
	    				bar.css("color", "black");
	    			}
	    				
	    			a.append(p);
	    			a.append(progress);
	    			progress.append(bar);
	    			$("#blockDetail").append(a);
	    		});

				$("#blockDetail").show();
	    	} else {
	    		alert(response.message);
	    	}
	    },
	    error:function(){alertError();}
	});
}
function ajaxRow (rowName) {
	$.ajax({
		type: "get",//使用get方法访问后台
	    dataType: "json",//返回json格式的数据
	    url: MONITOR_ROW_BALANCE_DETAIL,//ref:  /bms/js/service.js
	    data: {"row" : rowName},
	    success:function (response) {
	    	if (response.success){
	    		//change title
	    		$("#modalTitle").text(rowName + "结存明细");
	    		//clear Text
	    		$("#modalTable tbody").text("");
	    		$.each(response.data, function (index, value) {
	    			var tr = $("<tr />");
	    			$("<td />").html(value.series).appendTo(tr);
	    			$("<td />").html(value.vin).appendTo(tr);
	    			$("<td />").html(value.type).appendTo(tr);
	    			$("<td />").html(value.color).appendTo(tr);
	    			$("<td />").html(value.time).appendTo(tr);
	    			
	    			$("#modalTable tbody").append(tr);
	    		});

				$("#modal").modal("show");
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

$(".area-e,.area-e-right").live("hover", function () {
	$(".area-e").css("background", "url(../img/area-e.png) no-repeat 0 0");
	$(".area-e-right").css("background", "url(../img/area-e-right.png) no-repeat 0 0");
}).live("mouseout", function () {
	$(".area-e").css("background", "");
	$(".area-e-right").css("background", "");
});