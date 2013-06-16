$(document).ready(function () {
	initPage();
	//------------------- common functions -----------------------	
	//initialize this page
	/*
		1.add head class and resetPage
		2.resetPage();
		3.hide alert
	*/
	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftWarehouseQueryLi").addClass("active");
		$("#divInfo").hide();
		$(".detailTable").hide();	//add by wujun
		$("#startTime").val(window.byd.DateUtil.currentDate8);
		$("#endTime").val(window.byd.DateUtil.currentTime);
	}

	$(".clearinput").click(function() {
		$(this).siblings().filter("input").val("");
	})
	
	$(".resetDate").click(function() {
		$(this).siblings().filter("input").val(window.byd.DateUtil.currentTime);
	})


	$("#tabs li").click(function () {
		var index = $("#tabs li").index(this);
		if(index<7 || $(this).hasClass("dropdown"))
			$("#paginationCars").hide();
		if (index == 1){
			ajaxQueryCars(1);
		} else if(index==2){
			ajaxQueryNodeCars();	
		} else if(index==3){
			$("#area").val("");
			ajaxQueryBalanceCars();	
		} else if (index==4) {
			if($("#selectSeries").val() === ''){
				ajaxStatisticsAll();
			}else{
				ajaxStatistics();
			}
		} else if(index === 5) {
			carsDistribute();
		} else if (index === 6){
			standbyDate = $.trim($("#startTime").val());
			orderNumber = $.trim($("#orderNumberText").val());
			boardNumber = $.trim($("#boardNumberText").val());
			distributor = $.trim($("#distributorText").val());
			if(standbyDate == "" && orderNumber =="" && distributor == "" && boardNumber =="")
				alert("除车系外至少要有1个查询条件")
			ajaxQueryOrder();
		} else if (index ===7){
			ajaxQueryPeriod();
		}
	});

	$("#checkboxMerge").change(function () {
		if($(this).attr("checked") == "checked"){
			ajaxQueryBalanceAssembly('mergeRecyle');
		} else {
			ajaxQueryBalanceAssembly();
		}
	})

	//car pagination
	$("#preCars").click(
		function (){
			if(parseInt($("#curCars").attr("page")) > 1){
				$(".tableCars tbody").html("");
				key = $("#exportCars").attr("export");
				targetPage = parseInt($("#curCars").attr("page")) - 1;
				goQuery(key, targetPage);
			}
		}
	);

	$("#nextCars").click(
		function (){
			if(parseInt($("#curCars").attr("page")) * 20 < parseInt($("#totalCars").attr("total")) ){
				$(".tableCars tbody").html("");
				key = $("#exportCars").attr("export");
				targetPage = parseInt($("#curCars").attr("page")) + 1;
				goQuery(key, targetPage);
			}
		}
	);

	$("#firstCars").click(
		function () {
			if(parseInt($("#curCars").attr("page")) > 1){
				$(".tableCars tbody").html("");
				key = $("#exportCars").attr("export");
				targetPage = parseInt(1);
				goQuery(key, targetPage);
			}
		}
	);

	$("#lastCars").click(
		function () {
			if(parseInt($("#curCars").attr("page")) * 20 < parseInt($("#totalCars").attr("total")) ){
				$("#tableCars tbody").html("");
				key = $("#exportCars").attr("export");
				totalPage = parseInt($("#totalCars").attr("total"))%20 === 0 ? parseInt($("#totalCars").attr("total"))/20 : parseInt($("#totalCars").attr("total"))/20 + 1;
				goQuery(key, totalPage);
			}
		}
	)

	$("#exportCars").click(
		function () {
			goExport($(this).attr("export"));
			return false;
		}
	);

	$(".orderCars").live("click", function(e) {
		tr = $(e.target).closest("tr");
		queryOrderDetail(tr.data("orderId"));

		$("#detailModal .modal-header h4").html("#" + tr.data("laneName") + "_" + tr.data("distributorName") + "_" + tr.data("orderNumber"));

		$("#detailModal").modal("show");
	})

    $('#startTime, #endTime').datetimepicker({
		timeFormat: "HH:mm",
		changeMonth: true,
	    changeYear: true,
	    showOtherMonths: true,
	    selectOtherMonths: true,
	    duration: "fast",
	    buttonImageOnly: true,
	});

	$(".queryCars").live("click", function(e){
		orderConfigId = $(this).attr("orderConfigId");
		coldResistant = $(this).attr("coldResistant");
		color = $(this).attr("color");
		headInfo ="成品库-" + $("#selectSeries").val() + "-" + $(this).attr("configName");
		if(color !=""){
			headInfo = headInfo + "-" + color
		}
		$("#carsModal .modal-header h4").html(headInfo);
		ajaxQueryCars(orderConfigId,coldResistant,color);
		$("#carsModal").modal("show");

	})

	$("#area").change(function(){
		ajaxQueryBalanceCars();
	})

    function goExport (exportKey) {
    	switch(exportKey) {
    		case "orderCars" :
    			ajaxExportOrderCars();
    			break;
    		case "nodeCars" :
    			ajaxExportNodeCars();
    			break;
    		case "balanceCars" :
    			ajaxExportBalanceCars();
    			break;
    		default:
    			break;
    	} 
    }

    function goQuery (key, targetPage) {
    	switch(key){
    		case "orderCars" :
    			ajaxQueryCars(targetPage);
    			break;
    		case "nodeCars" :
    			ajaxQueryNodeCars(targetPage);
    			break;
    		case "balanceCars" :
    			ajaxQueryBalanceCars(targetPage);
    			break;
    		default:
    			break;
    	}
    }


	function ajaxQueryCars (targetPage) {
		standbyDate =  $("#startTime").val().substr(0,10);
		standbyDateEnd =  $("#endTime").val().substr(0,10);
		$("#tableOrderCars>tbody").html("");
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: QUERY_ORDER_CARS ,//ref:  /bms/js/service.js
		    data: {
		    	"orderNumber": $("#orderNumberText").val(),
		    	"standbyDate": standbyDate,
		    	"boardNumber": $("#boardNumberText").val(),
		    	"standbyDateEnd": standbyDateEnd,
		    	"distributor": $("#distributorText").val(),
		    	"series" : $("#selectSeries").val(),
		    	"status" : getStatusChecked(),
		    	"perPage":20,
				"curPage":targetPage || 1,
				"orderBy": 'lane_id,priority,`status`',
		    },
		    success:function (response) {
		    	if(response.success){
		    		var cars = response.data.data;

		    		$.each(cars ,function (index,value) {
		    			var laneTd = "<td>" + value.lane + "</td>";
		    			var orderNumberTd = "<td>" + value.order_number + "</td>";
		    			var distributorNameTd = "<td>" + value.distributor_name + "</td>";
		    			var serialNumberTd = "<td>" + value.serial_number + "</td>";
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var seriesTd = "<td>" + value.series + "</td>";
		    			var configTd = "<td>" + value.config_name + "</td>";
		    			var coldTd = "<td>" + value.cold + "</td>";
		    			var colorTd = "<td>" + value.color + "</td>";
		    			var engineTd = "<td>" + value.engine_code + "</td>";
		    			if(value.distribute_time == '0000-00-00 00:00:00'){
			    			var distributeTimeTd = "<td>" + '未出库' + "</td>";
		    			}else{
			    			var distributeTimeTd = "<td>" + value.distribute_time + "</td>";
		    			}
		    			var rowTd = "<td>" + value.row + "</td>";

		    			var tr = "<tr>"  + laneTd + orderNumberTd  + distributorNameTd + 
		    				serialNumberTd + vinTd + seriesTd +  configTd + coldTd + colorTd + engineTd + distributeTimeTd + rowTd +"</tr>";
		    			$("#tableOrderCars tbody").append(tr);
		    		});
					if(response.data.pager.curPage == 1) {
							$("#preCars, #firstCars").addClass("disabled");
							$("#preCars a, #firstCars a").removeAttr("href");
						} else {
							$("#preCars, #firstCars").removeClass("disabled");
							$("#preCars a, #firstCars a").attr("href","#");
						}
		    			if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
							$("#nextCars, #lastCars").addClass("disabled");
							$("#nextCars a, #lastCars a").removeAttr("href");
						} else {
							$("#nextCars, #lastCars").removeClass("disabled");
							$("#nextCars a, #lastCars a").attr("href","#");
						}
						$("#curCars").attr("page", response.data.pager.curPage);
						$("#curCars a").html(response.data.pager.curPage);
						$("#totalCars").attr("total", response.data.pager.total);
						$("#totalCars").html("导出全部" + response.data.pager.total + "条记录");
						$("#exportCars").attr("export", "orderCars");
						$("#paginationCars").show();
						$("#tableOrderCars").show();	
		    	}else{
		    		alert(response.message);

		    	}
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxExportOrderCars(){
		window.open(EXPORT_ORDER_CARS +
			"?&orderNumber=" + $("#orderNumberText").val() +
			"&boardNumber=" + $("#boardNumberText").val() +
			"&standbyDate=" + $("#standbyDate").val() +
			"&standbyDateEnd=" + $("#standbyDateEnd").val() +
			"&distributor=" + $("#distributorText").val() +
			"&series=" + $("#selectSeries").val() +
			"&status=" + getStatusChecked()
			)
	}

	function ajaxQueryOrder() {
		standbyDate =  $("#startTime").val().substr(0,10);
		standbyDateEnd =  $("#endTime").val().substr(0,10);
		$("#tableOrderDetail>tbody").html("");
		$("#tableOrderDetail>thead td").remove();
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    // url: ORDER_QUERY ,//ref:  /bms/js/service.js
		    url: QUERY_BOARD_ORDERS ,//ref:  /bms/js/service.js
		    data: {
		    	"orderNumber" : $("#orderNumberText").val(),
		    	"boardNumber" : $("#boardNumberText").val(),
		    	"standbyDate" : standbyDate,
		    	"standbyDateEnd": standbyDateEnd,
		    	"distributor" : $("#distributorText").val(),
		    	"series" : $("#selectSeries").val(),
		    	"status" : getStatusChecked(),
		    	"orderBy": 'board_number,lane_id,priority,`status`',
		    },
		    success:function (response) {
	    		var boards = response.data;
	    		var amountSum = 0;
	    		var holdSum = 0;
	    		var countSum = 0;

	    		$.each(boards, function (board, value){
	    			//以board为单位构造子表
	    			var num = value.orders.length;
	    			var tmp = $("<tbody />");
	    			// console.log(num);

	    			amountSum += value.boardAmount;
		    		holdSum += value.boardHold;
		    		countSum += value.boardCount;

	    			for(var i=0; i<num; i++){
	    				$("<tr />").appendTo(tmp);
	    				
	    			};
	    			// console.log(tmp);

	    			$.each(value.orders, function (index,order){
	    				tr = tmp.children("tr:eq("+ index +")");
	    				$("<td />").html(order.lane_name).appendTo(tr);
	    				aTd = "<a class='orderCars'>"+ order.order_number +"</a>"
	    				$("<td />").html(aTd).appendTo(tr);
	    				$("<td />").html(order.distributor_name).appendTo(tr);
	    				if(order.series == '6B'){
		    				$("<td />").html('思锐').appendTo(tr);
	    				} else {
		    				$("<td />").html(order.series).appendTo(tr);
	    				};
	    				$("<td />").html(order.car_type_config + "/" + order.cold).appendTo(tr);
	    				// $("<td />").html(order.cold).appendTo(tr);
	    				$("<td />").html(order.color).appendTo(tr);
	    				$("<td />").html(order.amount).addClass('amountTd').appendTo(tr);
	    				$("<td />").html(order.hold).addClass('holdTd').appendTo(tr);
	    				$("<td />").html(order.count).addClass('countTd').appendTo(tr);
	    				if(order.activate_time==='0000-00-00 00:00:00'){
	    					$("<td />").html('-').appendTo(tr);
	    				}else{
		    				$("<td />").html(order.activate_time).appendTo(tr);
	    				}

	    				if(order.standby_finish_time === '0000-00-00 00:00:00'){
		    				if(order.activate_time === "0000-00-00 00:00:00"){
		    					$("<td />").html('-').appendTo(tr);
		    				}else{
			    				$("<td />").html("<i class='icon-time'></i>" + order.standby_last + "H").appendTo(tr);
		    				}
	    				} else{
		    				$("<td />").html(order.standby_finish_time).appendTo(tr);
	    				}

	    				if(order.out_finish_time === '0000-00-00 00:00:00'){
	    					if(order.standby_finish_time === '0000-00-00 00:00:00'){
	    						$("<td />").html("-").appendTo(tr);
	    					}else{
		    					$("<td />").html("<i class='icon-time'></i>" + order.out_last + "H").appendTo(tr);
	    					}
	    				} else{
		    				$("<td />").html(order.out_finish_time).appendTo(tr);
	    				}

	    				if(order.lane_release_time === '0000-00-00 00:00:00'){
	    					if(order.out_finish_time === '0000-00-00 00:00:00'){
	    						$("<td />").html("-").appendTo(tr);
	    					}else{
	    						$("<td />").html("<i class='icon-time'></i>" + order.lane_last + "H").appendTo(tr);
	    					}
	    				} else {
	    					$("<td />").html(order.lane_release_time).appendTo(tr);
	    				}

	    				if(order.is_printed == 1){
	    					$("<td />").addClass("alignCenter").html("<i class='icon-print'></i>").appendTo(tr);
	    				} else {
	    					if(order.short < 0){
		    					$("<td />").html(order.short).addClass("text-error alignCenter").appendTo(tr);
		    					$(tr).addClass('warning');
	    					} else {
	    						$("<td />").addClass("alignCenter").html("-").appendTo(tr);
	    					}
	    				}
	    				if(order.status ==1 && order.out_finish_time === '0000-00-00 00:00:00'){
	    					if(order.standby_last >= 12 || order.out_last >= 20 || order.lane_last >= 12){
	    						$(tr).removeClass('warning').addClass('error');
	    					}
	    				}else if(order.status == 2){
		    				$(tr).addClass('success');
		    			}

		    			tr.data("orderId", order.id);
		    			tr.data("laneName" ,order.lane_name);
		    			tr.data("distributorName" ,order.distributor_name);
		    			tr.data("orderNumber" ,order.order_number);
	    			})

					//首行，被合并的单元格放在此行
	    			var firstTr = tmp.children("tr:eq(0)");
	    			firstTr.addClass("thickBorder");	
	    			//合并的备板编号
	    			boardTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(value.boardNumber).prependTo(firstTr);
	    			//合并的需备数量
	    			boardAmountTd = $("<td />").attr("rowspan", num).addClass("totalTd text-info rowSpanTd").html(value.boardAmount).insertAfter(firstTr.children("td:eq(7)"));
	    			//合并的已备数量
	    			boardHoldTd = $("<td />").attr("rowspan", num).addClass("totalTd text-info rowSpanTd").html(value.boardHold).insertAfter(firstTr.children("td:eq(9)"));
	    			//合并的完成数量
	    			boardCountTd = $("<td />").attr("rowspan", num).addClass("totalTd text-info rowSpanTd").html(value.boardCount).insertAfter(firstTr.children("td:eq(11)"));
	    			
	    			// console.log(firstTr.children("td:eq(8)"));
	    			// console.log(tmp.children("tr"));
	    			$("#tableOrderDetail tbody").append(tmp.children("tr"));
	    		})

					trTotal = $("<tr />");	
					tdLabel = "<td colspan='7' style='text-align:right'>合计&nbsp;&nbsp;&nbsp;&nbsp;</td>";
					trTotal.append(tdLabel);
					$("<td />").attr("colspan", "2").addClass("totalTd").html(amountSum).appendTo(trTotal);
					$("<td />").attr("colspan", "2").addClass("totalTd").html(holdSum).appendTo(trTotal);
					$("<td />").attr("colspan", "2").addClass("totalTd").html(countSum).appendTo(trTotal);
					$("<td />").attr("colspan", "3").appendTo(trTotal);
					$("#tableOrderDetail thead").prepend(trTotal);

	    		$("#tableOrderDetail").show();
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxQueryPeriod() {
		standbyDate =  $("#startTime").val().substr(0,10);
		standbyDateEnd =  $("#endTime").val().substr(0,10);
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: QUERY_DISTRIBUTE_PERIOD ,//ref:  /bms/js/service.js
		    data: {
		    	"startDate": standbyDate,
		    	"endDate": standbyDateEnd,
		    	"status" : getStatusChecked(),
		    	"orderBy": 'board_number,lane_id,priority,`status`',
		    },
		    success: function (response) {
		    	orderQuery.areaPeriod.areaPeriodAjaxData = response.data;
		    	orderQuery.areaPeriod.drawAreaPeriod();
		    	orderQuery.areaPeriod.updatePoriodTable();
		    },
		    error: function() {alertError();}
		});
	}

	function queryOrderDetail(orderId) {
		$.ajax({
			url: QUERY_CARS_BY_ORDER_ID,
			type: "get",
			dataType: "json",
			data: {
				"orderId" : orderId,
			},
			success: function (response) {
				$("#tableDetail tbody").html("");
				cars = response.data;
				$.each(cars, function (index, value) {
					tr = $("<tr />");
					$('<td />').html(value.vin).appendTo(tr);
					$('<td />').html(value.standby_time).appendTo(tr);

					if(value.distribute_time === '0000-00-00 00:00:00'){
						lastTd = $('<td />').html("<i class='icon-time'></i>" + value.standby_last + "H").appendTo(tr);
						if (value.standby_last > 24) {
							lastTd.addClass("text-error");
						} else if(value.standby_last > 12){
							lastTd.addClass("text-warning");
						}
					} else {
						$('<td />').html(value.distribute_time).appendTo(tr);
					}

					$('<td />').html(value.old_row).appendTo(tr);
					if(value.series == "6B"){
						$('<td />').html("思锐").appendTo(tr);
					}{
						$('<td />').html(value.series).appendTo(tr);
					}
					$('<td />').html(value.type_config).appendTo(tr);
					$('<td />').html(value.cold).appendTo(tr);
					$('<td />').html(value.color).appendTo(tr);
					$('<td />').html(value.engine_code).appendTo(tr);

					$("#tableDetail tbody").append(tr);

					tr.data("carId", value.car_id);
					tr.data("vin", value.vin);
				})
				$("#tabelDetail").show();
			},
			error: function(){alertError();}
		})
	}

	function ajaxQueryNodeCars (targetPage) {
		$("#tableNodeCars tbody").html("");
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: QUERY_NODE_TRACE,//ref:  /bms/js/service.js
		    data: { "vin": $('#vinText').val(), 
		    		"node":$("#selectNode").val(),
					"series": $("#selectSeries").val(),
					"stime":$("#startTime").val(),
					"etime":$("#endTime").val(),
					"perPage":20,
					"curPage":targetPage || 1},
		    success:function (response) {
		    	if(response.success){
		    		$.each(response.data.data,function (index,value) {
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var seriesTd = "<td>" + value.series + "</td>";
		    			var serialTd = "<td>" + value.serial_number + "</td>";
		    			// var carTypeTd = "<td>" + value.type + "</td>";
		    			var configTd = "<td>" + value.type_config + "</td>";
		    			var colorTd = "<td>" + value.color + "</td>";
		    			var coldTd = "<td>" + value.cold_resistant + "</td>";
		    			var statusTd = "<td>" + value.status + "</td>";
		    			var remarkTd = "<td>" + value.node_remark + "</td>";
		    			var pTimeTd = "<td>" + value.pass_time + "</td>";
		    			// var orderNumberTd = "<td>" + value.order_number + "</td>";
		    			var tr = "<tr>"
		    				+ serialTd 
		    				+ vinTd 
		    				+ seriesTd 
		    				// + carTypeTd
		    				+ configTd
		    				+ coldTd 
		    				+ colorTd
		    				+ statusTd 
		    				+ pTimeTd 
		    				+ remarkTd 
		    				// + orderNumberTd
		    				+ "</tr>";

		    			$("#tableNodeCars tbody").append(tr);
		    		});

		    		//deal with pager	
		    		if(response.data.pager.curPage == 1) {
		    			//$(".prePage").hide();
						$("#preCars, #firstCars").addClass("disabled");
						$("#preCars a, #firstCars a").removeAttr("href");
					} else {
	    				//$(".prePage").show();
						$("#preCars, #firstCars").removeClass("disabled");
						$("#preCars a, #firstCars a").attr("href","#");
					}
	    			if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
	    				//$(".nextPage").hide();
						$("#nextCars, #lastCars").addClass("disabled");
						$("#nextCars a, #lastCars a").removeAttr("href");
					} else {
	    				//$(".nextPage").show();
						$("#nextCars, #lastCars").removeClass("disabled");
						$("#nextCars a, #lastCars a").attr("href","#");
					}
					$("#curCars").attr("page", response.data.pager.curPage);
					$("#curCars a").html(response.data.pager.curPage);
					$("#totalCars").attr("total", response.data.pager.total);
					$("#totalCars").html("导出全部" + response.data.pager.total + "条记录");
					$("#exportCars").attr("export", "nodeCars");

					$("#paginationCars").show();
					$("#tableNodeCars").show();
		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxExportNodeCars () {
		window.open(EXPORT_NODE_TRACE + 
			"?&node=" + $("#selectNode").val() + 
			"&series=" + $("#selectSeries").val() +
			"&stime=" + $("#startTime").val() +
			"&etime=" + $("#endTime").val()
		);
	}

	function ajaxQueryBalanceCars (targetPage) {
		$("#tableBalanceCars tbody").html("");
		areaVal = $("#area").val();
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: BALANCE_DETAIL_QUERY,//ref:  /bms/js/service.js
		    data: { 
		    	"state" : 'WH',
		    	"series" : $("#selectSeries").val(),
		    	"area" : areaVal,
				"curPage":targetPage || 1,
		    	"perPage":20,
		    },
		    success:function (response) {
		    	if(response.success){
		    		$.each(response.data.data,function (index,value) {
		    			var serialTd = "<td>" + value.serial_number + "</td>";
		    			var seriesTd = "<td>" + value.series + "</td>";
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var colorTd = "<td>" + value.color + "</td>";
						var typeInfoTd = "<td>" + value.type_info + "</td>";
						var coldTd = "<td>" + value.cold + "</td>";
		    			var statusTd = "<td>" + value.status + "</td>";
		    			var rowTd = "<td>" + value.row + "</td>";
		    			var finishTimeTd = "<td>" + value.finish_time + "</td>";
		    			var warehouseTimeTd = "<td>" + value.warehouse_time + "</td>";
		    			var tr = "<tr>" + serialTd + vinTd + seriesTd + typeInfoTd + 
		    				coldTd + colorTd + statusTd + rowTd + finishTimeTd + warehouseTimeTd + "</tr>";
		    			$("#tableBalanceCars tbody").append(tr);
		    		});
		    		//deal with pager
		    		if(response.data.pager.curPage == 1) {
	    			//$(".prePage").hide();
						$("#preCars, #firstCars").addClass("disabled");
						$("#preCars a, #firstCars a").removeAttr("href");
					} else {
	    				//$(".prePage").show();
						$("#preCars, #firstCars").removeClass("disabled");
						$("#preCars a, #firstCars a").attr("href","#");
					}
	    			if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
	    				//$(".nextPage").hide();
						$("#nextCars, #lastCars").addClass("disabled");
						$("#nextCars a, #lastCars a").removeAttr("href");
					} else {
	    				//$(".nextPage").show();
						$("#nextCars, #lastCars").removeClass("disabled");
						$("#nextCars a, #lastCars a").attr("href","#");
					}
					$("#curCars").attr("page", response.data.pager.curPage);
					$("#curCars a").html(response.data.pager.curPage);
					$("#totalCars").attr("total", response.data.pager.total);
					$("#totalCars").html("导出全部" + response.data.pager.total + "条记录");
					$("#exportCars").attr("export", "balanceCars");
					

					//area condition
					$("#area").html("");
					$("<option />").val("").html("库区").appendTo($("#area"));
					$.each(response.data.areaArray, function (key, area){
						if(area !=''){
							$("<option />").val(area).html(area).appendTo($("#area"));
						}
					})
					$("#area").val(areaVal);

					$("#tableBalanceCars").show();
					$("#paginationCars").show();

		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxExportBalanceCars(){
		areaVal = $("#area").val();
		window.open(BALANCE_DETAIL_EXPORT 
			+ "?state=WH" 
			+ "&series=" + $("#selectSeries").val()
			+ "&area=" + areaVal
			);
	}

	function ajaxStatisticsAll() {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: NODE_QUERY_CAR,//ref:  /bms/js/service.js
		    data: { 
	    		"node":$("#selectNode").val(),
				"series": $("#selectSeries").val(),
				"stime":$("#startTime").val(),
				"etime":$("#endTime").val(),
			},
		    success:function (response) {
		    	if(response.success){
		    		mQuery.statisticsAll.statisticsAllAjaxData = response.data;
			    	mQuery.statisticsAll.drawArea();
			    	mQuery.statisticsAll.updateStatisticsTable();
		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}


	function ajaxStatistics () {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: NODE_QUERY_CAR,//ref:  /bms/js/service.js
		    data: { "vin": $('#vinText').val(), 
		    		"node": $("#selectNode").val(),
					"series": $("#selectSeries").val(),
					"stime":$("#startTime").val(),
					"etime":$("#endTime").val(),
				},
		    success:function (response) {
		    	if(response.success){
		    		
		    		drawStatistic(response.data);
		    		changeStatisticsTable(response.data);
		    		// drawLineChart(response.data.series);
		    		// tempLineData = response.data.series;
		    		// changeLineTable(response.data.detail);
		    		
		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}
	/*
		draw draw statistic line
	*/
	function drawStatistic (data) {
		lineSeries = [];
        carSeries = data.carSeries;
        lineData = data.series;
        $.each(carSeries, function (index,series) {
            lineSeries[index] = {name : series, data: prepare(lineData.y[series])};
        });

		var chart;
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'statisticContainer',
                type: 'line',
                //marginRight: 130,
                marginBottom: 60
            },
            title: {
                text: '',
                x: -20 //center
            },
            credits: {
                href: '',
                text: ''
            },
            xAxis: {
                categories: lineData.x,
                labels: {
                    rotation: -45,
                    align: 'right'
                }
            },
            yAxis: {
            	labels: {
                    formatter: function() {
                        return Math.round(this.value);
                    }
                },
                title: {
                    text: '车辆数'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                min : 0
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y;
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'top',
                borderWidth: 0
            },
            series: lineSeries
        });
	}

	function changeStatisticsTable (data) {
		carSeries = data.carSeries;
		detail = data.detail;
		total =data.total;		//added by wujun
		$("#tableStatistic thead").html("<tr />");
		$("#tableStatistic tbody").html("<tr />");		
        $.each(carSeries, function (index,value) {
            $("<tr />").appendTo($("#tableStatistic tbody"));
        });
		
		var thTr = $("#tableStatistic tr:eq(0)");
        $("<th />").html("车系").appendTo(thTr);    
        $("<th />").html("合计").appendTo(thTr);
		$.each(carSeries, function (index, series) {
            $("<td />").html(series).appendTo($("#tableStatistic tr:eq("+(index*1+1)+")"));
            $("<td />").html(total[series]).appendTo($("#tableStatistic tr:eq(" + (index*1+1) + ")"));
        });

		$.each(detail, function (index,value) {
			$("<td />").html(value.time).appendTo(thTr);

			$.each(carSeries, function (index,series) {
				$("<td />").html(value[series]).appendTo($("#tableStatistic tr:eq("+(index*1+1)+")"));
			});
		});
	}

	var distinctLabel = [];

	function prepare (dataArray) {
            return $(dataArray).map(function (index, item) {
                if ($.inArray(index, distinctLabel) !== -1)
                    return { y: item, show: false};
                return { y: item, show: true};
            });
             
    }

    function carsDistribute() {
		if($("#selectSeries").val() == ""){
			$("#balanceDistribute .tableContainer").addClass("span10");
			$(".chartContainer").show();
			if($("#selectState").val() == "assembly"){
				if($("#checkboxMerge").attr("checked") == "checked"){
					ajaxQueryBalanceAssembly('mergeRecyle');
				} else {
					ajaxQueryBalanceAssembly('assembly');
				}
				$("#divCheckbox").show()
			} else {
				ajaxQueryBalanceAssembly('WH');
				$("#checkboxMerge").removeAttr("checked");
				$("#divCheckbox").hide();
			}
		} else {
			$("#divCheckbox").hide();
			$("#balanceDistribute .tableContainer").removeClass("span10");
			$(".chartContainer").hide();
			ajaxQueryBalanceDistribute();
		}
	}

	function ajaxQueryBalanceAssembly(state) {
		$("#balanceDistribute").hide();
		$.ajax({
			url: QUERY_BALANCE_ASSEMBLY,
			type: "get",
			data: {
				"state" : state,
			},
			dataType: "json",
			success: function(response) {
				balanceQuery.AssemblyAll.ajaxData = response.data;
				balanceQuery.AssemblyAll.updateDistributeTable();
				balanceQuery.AssemblyAll.drawColumn();
				$("#tableCarsDistribute").show();
				$("#columnContainer").show();
				$("#balanceDistribute").show();
			},
			error: function(){
				alertError();
			}
		})
	}

	function ajaxQueryBalanceDistribute() {
		$("#balanceDistribute").hide();
		$.ajax({
			url: QUERY_BALANCE_DISTRIBUTE,
			type: "get",
			data: {
				"state" : "WH",
				"series" : $("#selectSeries").val(), 
			},
			dataType: "json",
			success: function (response) {
				balanceQuery.distribute.ajaxData = response.data;
				balanceQuery.distribute.updateDistributeTable();
				$("#tableCarsDistribute").show();
				$("#columnContainer").hide();
				$("#balanceDistribute").show();
			}
		})
	}

	function ajaxQueryCars(orderConfigId,coldResistant,color) {
		$("#resultCars>tbody").html("");
		$.ajax({
			url: SHOW_BALANCE_CARS,
			type: "get",
			dataType: "json",
			data: {
				"state" : $("#selectState").val(),
				"orderConfigId" : orderConfigId,
				"coldResistant" : coldResistant,
				"color" : color || "",
			},
			success: function (response) {
				if(response.success){
					cars = response.data
					$.each(cars, function (index, car){
						tr = $("<tr />");
						$("<td />").html(car.serial_number).appendTo(tr);
						$("<td />").html(car.vin).appendTo(tr);
						$("<td />").html(car.series).appendTo(tr);
						$("<td />").html(car.type_info).appendTo(tr);
						$("<td />").html(car.cold).appendTo(tr);
						$("<td />").html(car.color).appendTo(tr);
						$("<td />").html(car.status).appendTo(tr);
						$("<td />").html(car.row).appendTo(tr);
						$("<td />").html(car.finish_time.substring(0,16)).appendTo(tr);
						$("<td />").html(car.warehouse_time.substring(0,16)).appendTo(tr);

						$("#resultCars>tbody").append(tr);
					})
					$("#resultCars").show();
				} else {
					alert(response.message);
				}
			},
			error: function(){
				alertError();
			}
		})
	}
	
	$('body').tooltip(
        {
         selector: "select[rel=tooltip], a[rel=tooltip]"
    });

	function getStatusChecked () {
		var activeChecked = $("#checkboxActive").attr("checked") === "checked";
		var freezeChecked = $("#checkFreeze").attr("checked") === "checked";
		var closedChecked = $("#checkClosed").attr("checked") === "checked";
		
		if(!activeChecked && !freezeChecked && !closedChecked){
			return 'all'
		} else {
		var temp = [];
		if (activeChecked)
			temp.push($("#checkboxActive").val());
		if (freezeChecked)
			temp.push($("#checkFreeze").val());
		if (closedChecked)
			temp.push($("#checkClosed").val());
		return temp.join(",");
		}
	}
 
 	

});

!$(function () {
	window.orderQuery = window.orderQeury || {};
	window.orderQuery.areaPeriod = {
		areaPeriodAjaxData: {},
		areaPeriodChartData: {
			chart: {
	                type: 'area',
	                renderTo: 'periodContainer'
            },
            title: {
                text: ''
            },
            credits: {
				href: '',
				text: ''
			},
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [],
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
            yAxis: {
                title: {
                    text: '周期（小时/板）'
                },
                labels: {
                    formatter: function() {
                        return this.value;
                    }
                }
            },
            tooltip: {
                shared: true,
                // valueSuffix: ' min'
                useHTML: true,
                formatter: function() {
                	console.log(this);
                	var s = this.points[0].key +'<table>';
                	total = 0;
                	$.each(this.points, function(i, point) {
                		value = point.y === null ? 0:point.y;
                    	s += '<tr><td style="color: '+ point.series.color +'">'+ point.series.name +': </td>' +
            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ value +'小时</b></td></tr>';
            			total += value
                	});
					total = total.toFixed(1);
                	s += '<tr><td>合计:</td><td style="text-align: right;"><b>'+ total +'小时</b></td></tr>'
                	s += '</table>';
                	return s;
                        
                }
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666'
                    }
                }
            },
            series: []
	    },

	    drawAreaPeriod: function() {
	    	var areaSeries = [];
	    	var periodSeries = this.areaPeriodAjaxData.periodSeries;
	    	var areaData = this.areaPeriodAjaxData.series;
	    	$.each(periodSeries, function (index, period) {
	    		areaSeries[index] = {name: period, data:orderQuery.areaPeriod.prepare(areaData.y[period])};
	    	})
	    	this.areaPeriodChartData.series = areaSeries;
	    	this.areaPeriodChartData.xAxis.categories = areaData.x;
	    	var chart;
			chart = new Highcharts.Chart(this.areaPeriodChartData);
	    },

	    updatePoriodTable: function() {
	    	var periodSeries = this.areaPeriodAjaxData.periodSeries;
	    	var detail = this.areaPeriodAjaxData.detail;
	    	var total = this.areaPeriodAjaxData.total;

	    	$("#tablePeriod thead").html('<tr />');
	    	$("#tablePeriod tbody").html('<tr />');
	    	$.each(periodSeries, function (index, value) {
	    		$('<tr />').appendTo($("#tablePeriod tbody"));
	    	})
    		$('<tr />').appendTo($("#tablePeriod tbody"));

	    	var thTr = $("#tablePeriod tr:eq(0)");
	    	$('<th />').html('日期').appendTo(thTr);
    		$.each(periodSeries, function (index, period) {
	    			$('<td />').html(period).appendTo($("#tablePeriod tr:eq("+ (index+1) +")"));
	    		})
    		$('<td />').html("总计").appendTo($("#tablePeriod tr:eq(3)"));

	    	$('<th />').html('合计').appendTo(thTr);
	    	$('<td />').html(total.transportPeriodAvg).appendTo($("#tablePeriod tr:eq(1)"));
	    	$('<td />').html(total.warehousePeriodAvg).appendTo($("#tablePeriod tr:eq(2)"));
	    	$('<td />').html(total.totalPeriodAvg).appendTo($("#tablePeriod tr:eq(3)"));

	    	$.each(detail, function (index, value){
	    		$('<td />').html(value.time).appendTo(thTr);
	    		$.each(periodSeries, function (index, period) {
	    			$('<td />').html(value[period]).appendTo($("#tablePeriod tr:eq("+ (index+1) +")"));
	    		})
	    		$('<td />').html(value.totalPeriod).appendTo($("#tablePeriod tr:eq(3)"));
	    	})

	    },

	    prepare: function (dataArray) {
	    	return $(dataArray).map(function (index, item) {
	    		return {x: index, y: item, show: false};
	    	})
	    }
	}

	window.mQuery = window.mQuery || {};
	window.mQuery.statisticsAll = {
		statisticsAllAjaxData: {},
		statisticsAllChartData: {
			chart: {
	                type: 'area',
	                renderTo: 'statisticContainer'
            },
            title: {
                text: ''
            },
            credits: {
				href: '',
				text: ''
			},
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [],
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
            yAxis: {
                title: {
                    text: '车辆数'
                },
                labels: {
                    formatter: function() {
                        return this.value;
                    }
                }
            },
            tooltip: {
                shared: true,
                // valueSuffix: ' min'
                useHTML: true,
                formatter: function() {
                	console.log(this);
                	var s = this.points[0].key +'<table>';
                	var ss = '';
                	total = 0;
                	$.each(this.points, function(i, point) {
                		value = point.y === null ? 0:point.y;
                    	ss += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ value +'辆</b></td></tr>';
            			total += value;
                	});
                	s += '<tr><td style="text-align: right;border-bottom-style:solid;border-bottom-width: 1px;"><b>总计:</b></td><td style="text-align: right;border-bottom-style:solid;border-bottom-width: 1px;"><b>'+ total +'辆</b></td></tr>';
                	s += ss;
                	s += '</table>';
                	return s;
                        
                }
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666'
                    }
                }
            },
            series: []
	    },

	    drawArea: function() {
	    	var areaSeries = [];
	    	var carSeries = this.statisticsAllAjaxData.carSeries;
	    	var areaData = this.statisticsAllAjaxData.series;
	    	$.each(carSeries, function (index, series) {
	    		areaSeries[index] = {name: series, data:mQuery.statisticsAll.prepare(areaData.y[series])};
	    	})
	    	this.statisticsAllChartData.series = areaSeries;
	    	this.statisticsAllChartData.xAxis.categories = areaData.x;
	    	var chart;
			chart = new Highcharts.Chart(this.statisticsAllChartData);
	    },

	    updateStatisticsTable: function() {
	    	var carSeries = this.statisticsAllAjaxData.carSeries;
	    	var detail = this.statisticsAllAjaxData.detail;
	    	var total = this.statisticsAllAjaxData.total;

			$("#tableStatistic thead").html("<tr />");
			$("#tableStatistic tbody").html("");		
	        $.each(carSeries, function (index,value) {
	            $("<tr />").appendTo($("#tableStatistic tbody"));
	        });
	        
			var thTr = $("#tableStatistic tr:eq(0)");
	        $("<th />").html("车系").appendTo(thTr);    
	        $("<th />").html("合计").appendTo(thTr);

	        totalTotal = 0;
			$.each(carSeries, function (index, series) {
	            $("<td />").html(series).appendTo($("#tableStatistic tr:eq("+(index*1+1)+")"));
	            $("<td />").html(total[series]).appendTo($("#tableStatistic tr:eq(" + (index*1+1) + ")"));
	            totalTotal += total[series];
	        });

	        var totalTr =  $("<tr />").appendTo($("#tableStatistic tbody"));
	        $("<td />").html('总计').appendTo(totalTr);
	        $("<td />").html(totalTotal).appendTo(totalTr);

			$.each(detail, function (index,value) {
				$("<td />").html(value.time).appendTo(thTr);
				detailTotal = 0;
				$.each(carSeries, function (index,series) {
					$("<td />").html(value[series]).appendTo($("#tableStatistic tr:eq("+(index*1+1)+")"));
					detailTotal += parseInt(value[series]);
				});
				$("<td />").html(detailTotal).appendTo(totalTr);
			});

	    },

	    prepare: function (dataArray) {
	    	return $(dataArray).map(function (index, item) {
	    		return {x: index, y: item, show: false};
	    	})
	    }
	}

	window.balanceQuery = window.balanceQuery || {};
	window.balanceQuery.AssemblyAll = {
		ajaxData : {},

		columnData: {
			chart: {
                type: 'column',
                renderTo: 'columnContainer'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: []
            },
            credits: {
                enabled: false
            },
            yAxis: {
                min: 0,
                title: {
                    text: '结存数量（辆）'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:14px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                	stacking: 'normal',
                    pointPadding: 0.1,
                    borderWidth: 0,
                    pointWidth: 15
                }
            },
            series: [],
        	navigation: {
	            buttonOptions: {
	                verticalAlign: 'bottom',
	                y: -20,
	            }
	        }
		},

		updateDistributeTable: function() {
			var series = this.ajaxData.carSeries;
			var detail = this.ajaxData.detail;
			var stateTotal = this.ajaxData.stateTotal;
			var seriesTotal = this.ajaxData.seriesTotal;

			//clear table and initialize it
			$("#tableCarsDistribute thead").html("<tr />");
			$("#tableCarsDistribute tbody").html("");
			$.each(series, function (index, series) {
				$("<tr />").appendTo($("#tableCarsDistribute tbody"));
			});
			stateTotalTr = $("<tr />").appendTo($("#tableCarsDistribute tbody"));

			//first column description
			var stateTr = $("#tableCarsDistribute tr:eq(0)");
			$("<td />").html('车系').addClass('alignCenter').appendTo(stateTr);
			$.each(series, function (index, series){
				$("<td />").html(series).addClass('alignCenter').appendTo($("#tableCarsDistribute tr:eq("+ (index+1) +")"));
			});
			$("<td />").html('合计').addClass('alignCenter').appendTo(stateTotalTr);

			//detail data
			$.each(detail, function (index ,value) {
				$("<td />").html(value.state).appendTo(stateTr);
				$.each(series, function (index, series){
					$("<td />").html(value[series]).appendTo($("#tableCarsDistribute tr:eq("+ (index+1) +")"));
				});
			})

			//series total
			$("<td />").html('总计').appendTo(stateTr);
			$.each(series, function (index, series) {
				$("<td />").html(seriesTotal[series]).appendTo($("#tableCarsDistribute tr:eq("+ (index+1) +")"));
			})

			//state total
			var totalTotal = 0;
			$.each(stateTotal, function (index, value) {
				$("<td />").html(value).appendTo(stateTotalTr);
				totalTotal += value;
			})
			$("<td />").html(totalTotal).appendTo(stateTotalTr);

		},


		drawColumn: function() {
			columnSeries = [];
			carSeries = this.ajaxData.carSeries;
			columnSeriesData = this.ajaxData.series;
			$.each(carSeries, function (index, series) {
				columnSeries[index] = {
					name: series,
					data: columnSeriesData.y[series]
				}
			})

            console.log(this);
			this.columnData.xAxis.categories = columnSeriesData.x;
			this.columnData.series = columnSeries;
			var chart;
			chart = new Highcharts.Chart(this.columnData);
		}
	}

	window.balanceQuery.distribute = {
		ajaxData: {},
		updateDistributeTable: function() {
			 var color = this.ajaxData.colorArray;
			 var configName = this.ajaxData.configNameArray;
			 var detail = this.ajaxData.detail;
			 var colorTotal = this.ajaxData.colorTotal;
			 var configTotal = this.ajaxData.configTotal;

			//clear table and initialize it
			$("#tableCarsDistribute thead").html("<tr />");
			$("#tableCarsDistribute tbody").html("");
			$.each(configName, function (index, configName) {
				$("<tr />").appendTo($("#tableCarsDistribute tbody"));
			})
			colorTotalTr = $("<tr />").appendTo($("#tableCarsDistribute tbody"));

			//first column description
			var colorTr = $("#tableCarsDistribute tr:eq(0)");
			$("<td />").html('车型/配置').addClass('alignCenter').appendTo(colorTr);
			$.each(configName, function (index, configName) {
				$("<td />").html(configName).addClass('configNameTd').appendTo($("#tableCarsDistribute tr:eq("+ (index+1) +")"));
			});
			$("<td />").html('合计').addClass('alignCenter').appendTo(colorTotalTr);

			//detail data
			$.each(detail, function (index, value) {
				$("<td />").html(value.color).appendTo(colorTr);
				$.each(configName, function (index, configName) {
					aCount = $("<a />").addClass("queryCars").attr("rel", "tooltip").attr("data-toggle", "tooltip").attr("data-placement", "top").attr("title", value.color);
					aCount.html(value[configName]['count']);
					aCount.attr("orderConfigId", value[configName]['orderConfigId']);
					aCount.attr("coldResistant", value[configName]['coldResistant']);
					aCount.attr("color", value.color);
					aCount.attr("configName", configName);
					$("<td />").html(aCount).appendTo($("#tableCarsDistribute tr:eq("+ (index+1) +")"));
				});
			});

			//config total
			$("<td />").html('总计').appendTo(colorTr)
			$.each(configName, function (index, configName) {
				aCount = $("<a />").addClass("queryCars").attr("rel", "tooltip").attr("data-toggle", "tooltip").attr("data-placement", "top");
				aCount.html(configTotal[configName]['count']);
				aCount.attr("orderConfigId", configTotal[configName]['orderConfigId']);
				aCount.attr("coldResistant", configTotal[configName]['coldResistant']);
				aCount.attr("color", "");
				aCount.attr("configName", configName);
				$("<td />").html(aCount).appendTo($("#tableCarsDistribute tr:eq("+ (index+1) +")"));
			});

			//color total
			var totalTotal = 0;
			$.each(colorTotal, function (index, value) {
				$("<td />").html(value).appendTo(colorTotalTr);
				totalTotal += value;
			})
			$("<td />").html(totalTotal).appendTo(colorTotalTr);
		}
	}
});
