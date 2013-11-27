$("document").ready(function() {
	initPage();
	var curBoard = ''
	var RefreshBoard = setInterval(function () {
		getBoardInfo();
		if(curBoard != ''){
			queryOrdersByBoardNumber(curBoard);
		}
	},30000);

	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftWarehousePrintLi").addClass("active");
		getBoardInfo();
	}

	function getBoardInfo() {
		$.ajax({
			url: GET_BOARD_INFO,
			type: "get",
			data: {
				"type" : "certificatePaper",
			},
			dataType: "json",
			success: function (response){
				$("#boardBar").html("");
				$("#totalOK").html(response.data.totalToPrint);
				var boardArray = response.data.boardArray;
				var boardInfo = response.data.boardInfo;
				$.each(boardInfo, function (boardNumber, value){
					var div = $("<div />");
					var divContainer = $("<div />").addClass("pull-left boardContainer");
					var a = $("<a />").addClass("thumbnail board").attr("href", "#").attr("boardnum",boardNumber);
					var pNumber = $("<p />").addClass("pull-left board").attr("boardnum",boardNumber).html("#"+boardNumber);
					var pOK = $("<p />").addClass("label pull-right boardOK board").attr("boardnum",boardNumber).html(value.toPrint);
					if(value.toPrint > 0){
						pOK.addClass("label-success");
					}else{
						pOK.removeClass("label-success");
					};
					var progress = $("<div />").addClass("progress progress-info board").attr("boardnum",boardNumber);
					var bar = $("<div />").addClass("bar board").attr("style", "width:" +(parseInt(value.countSum) / parseInt(value.amountSum) * 100) + "%").html(value.countSum + "/" + value.amountSum).attr("boardnum",boardNumber);
					if (value.countSum == value.amountSum) {
	    				progress.removeClass("progress-info").addClass("progress").addClass("progress-success");
	    			} else if (value.countSum == "0"){
	    				bar.css("color", "black");
	    			}

	    			progress.append(bar);
	    			a.append(pNumber);
	    			a.append(pOK);
	    			a.append(progress);
	    			divContainer.append(a);
	    			div.append(divContainer);
	    			$("#boardBar").append(div);
				})
			},
			error: function(){alertError();}
		})
	}



	function queryOrdersByBoardNumber(boardNumber) {
		$.ajax({
			url: QUERY_ORDER_BY_BOARD,
			type: "get",
			dataType: "json",
			data: {
				"boardNumber" : boardNumber,
				"type" : "certificatePaper",
			},
			success: function (response) {
				$("#boardNumber").html(boardNumber);
				$("#tableOrders>tbody").html("");
				$.each(response.data.group, function (laneDistributorSeries, one) {
					var num = one.orders.length;
					var tmp = $("<tbody />");

					for(var i=0;i<num;i++){
						$("<tr />").appendTo(tmp);
					}

					$.each(one.orders, function (index, order) {
						tr = tmp.children("tr:eq("+ index +")");
						$("<td />").html(order.order_number).appendTo(tr);
						$("<td />").html(byd.SeriesName[order.series]).appendTo(tr);
						$("<td />").html(order.car_type_config).appendTo(tr);
						$("<td />").html(order.cold).appendTo(tr);
						$("<td />").html(order.color).appendTo(tr);
						$("<td />").html(order.amount).appendTo(tr);
						$("<td />").html(order.hold).appendTo(tr);
						$("<td />").html(order.count).appendTo(tr);
						tdBtn = $("<td />");
						tdBtn.html("<a class='btn btn-link goDetail' href='#'' rel='tooltip' data-toggle='tooltip' data-placement='top' title='车辆明细'><i class='btnDetail fa fa-list'></i></a><a class='btn btn-link goPrint' href='#' rel='tooltip' data-toggle='tooltip' data-placement='top' title='打印此单' disabled><i class='btnPrint fa fa-print'></i></a>");
						tdBtn.appendTo(tr);
						if(order.count == order.amount){
							tdBtn.children(".goPrint").removeAttr("disabled");
							tr.addClass("success");
						}
						// $("#tableOrders tbody").append(tr);

						tr.data("orderId", order.order_id);
						tr.data("boardNumber", order.board_number);
						tr.data("amount", order.amount);
						tr.data("hold", order.hold);
						tr.data("count", order.count);
						tr.data("orderNumber", order.order_number);
						tr.data("distributorName", order.distributor_name);
						tr.data("laneName", order.lane_name);
					})
					var firstTr = tmp.children("tr:eq(0)");
					firstTr.addClass("thickBoarder");

					distributorTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(one.distributor).prependTo(firstTr);
					laneTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(one.lane).prependTo(firstTr);

					printTd = $("<td />").attr("rowspan", num).attr("style", "text-align:center;").addClass("rowSpanTd")
					a = $("<button />").addClass("btn printGroup").attr("disabled", "disabled").html("<i class='btnPrint fa fa-print'></i>&nbsp;打印此组");
					a.data("orderIds", one.orderIds);
					a.appendTo(printTd);
					printTd.prependTo(firstTr);
					if(one.amount == one.count){
						a.removeAttr("disabled").addClass("btn-success");
					}
		
					$("#tableOrders tbody").append(tmp.children("tr"));
				})
				
				trPrintAll = $("<tr />");
				$("<td />").attr("colspan", "12").attr("style", "text-align:center").html("<button class='btn btn-primary printAllByBoard' id='boardPrintAll' disabled><i class='btnPrint fa fa-print'></i>&nbsp;打印整板</button>").appendTo(trPrintAll);
				if(response.data.remainTotal == '0'){
					trPrintAll.children("td").children(".printAllByBoard").removeAttr("disabled");
				}

				$("#tableOrders tbody").append(trPrintAll);
				$("#tableOrders").show();
			},
			error: function(){alertError}
		})
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
					// tdBtn = $("<td />");
					// tdBtn.html("<a class='btn btn-link goPrint' href='#' rel='tooltip' data-toggle='tooltip' data-placement='top' title='打印' disabled><i class='btnPrint fa fa-print'></i></a>");
					// tdBtn.appendTo(tr);
					$('<td />').html(value.vin).appendTo(tr);
					if(value.distribute_time === '0000-00-00 00:00:00'){
						$('<td />').html('未出库').appendTo(tr);
					} else {
						$('<td />').html(value.distribute_time).appendTo(tr);
					}
					// $('<td />').html(value.status).appendTo(tr);
					$('<td />').html('新证').appendTo(tr);
					$('<td />').html(value.distributor_name).appendTo(tr);
					$('<td />').html(byd.SeriesName[value.series]).appendTo(tr);
					$('<td />').html(value.type_config).appendTo(tr);
					$('<td />').html(value.cold).appendTo(tr);
					$('<td />').html(value.color).appendTo(tr);
					$('<td />').html(value.engine_code).appendTo(tr);
					$('<td />').html(value.remark).appendTo(tr);

					$("#tableDetail tbody").append(tr);

					tr.data("carId", value.car_id);
					tr.data("vin", value.vin);
				})
				$("#tabelDetail").show();
			},
			error: function(){alertError();}
		})
	}

	function printOne(orderId) {
		$.ajax({
			url: WAREHOUSE_PRINT_BY_ORDER,
			type: "get",
			dataType: "json",
			data: {
				"orderId" : orderId,
			},
			success: function(response){
				queryOrdersByBoardNumber(response.data);
				getBoardInfo();
				$("#spinModal").modal("hide");
				alert("打印传输完成!");
			},
			error: function(){alertError();}
		})
	}

	function printGroup(orderIds) {
		$.ajax({
			url: WAREHOUSE_PRINT_BY_ORDERS,
			type: "get",
			dataType: "json",
			data: {
				"orderIds" : orderIds,
			},
			success: function(response){
				if(response.success){
					getBoardInfo();
					queryOrdersByBoardNumber(curBoard);
					$("#spinModal").modal("hide");
					alert("打印传输完成!");
					fadeMessageAlert(response.message,"alert-success");
				} else {
					fadeMessageAlert(response.message,"alert-error");
				}
			},
			error: function(){alertError();}
		})
	}

	function printAllByBoard(boardNumber) {
		$.ajax({
			url: WAREHOUSE_PRINT_BY_BOARD,
			type: "get",
			dataType: "json",
			data: {
				"boardNumber" : boardNumber,
			},
			success: function(response) {
				if(response.success){
					getBoardInfo();
					$("#spinModal").modal("hide");
					alert("打印传输完成!");
					$("#tableOrders tbody").html("");
					curBoard = '';
				} else {
					alert(response.message);
					getBoardInfo();
					queryOrdersByBoardNumber(curBoard);
					$("#spinModal").modal("hide");
				}
			},
			error: function(){alertError();}
		})
	}

	$("#refreshLane").click(function(){
		getBoardInfo();
		if(curBoard != ''){
			queryOrdersByBoardNumber(curBoard);
		}
	})

	$("#boardBar").live("click", function(e) {
		if($(e.target).hasClass("board")){
			boardNumber = $(e.target).attr("boardnum");
			queryOrdersByBoardNumber(boardNumber);
			curBoard = boardNumber;
		}
	})

	$("#boardPrintAll").live("click", function(e){
		if($(e.target).attr("disabled") == "disabled"){
			return false;
		} else {
			if(confirm('是否传输打印？')){
				$("#spinModal").modal("show");
				printAllByBoard(curBoard);
			}
		}
	})

	$(".printGroup").live("click", function(e) {
		orderIds = $(e.target).data("orderIds");
		if($(e.target).attr("disabled") == "disabled"){
			return false;
		} else {
			if(confirm("是否传输打印此组？")){
				$("#spinModal").modal("show");
				orderIds = $(e.target).data("orderIds");
				printGroup(orderIds);
			}
		}
	})

	$("#tableOrders").live('click', function(e) {
		if($(e.target).is('i')) {
			var tr = $(e.target).closest("tr");
			if($(e.target).hasClass("btnDetail")){
				queryOrderDetail(tr.data("orderId"));
				$("#detailModal").data("orderId", tr.data('orderId'));
				$("#detailModal").data("amount", tr.data('amount'));
				$("#detailModal").data("hold", tr.data('hold'));
				$("#detailModal").data("count", tr.data('count'));
				modalHead = "#" + tr.data("laneName") + "_" + tr.data("orderNumber") + "_" + tr.data("distributorName");
				$("#detailModal .modal-header h4").html(modalHead);

				$("#detailModal").modal('show');
				if($("#detailModal").data("amount") == $("#detailModal").data("count")){
					$("#detailPrintAll").removeAttr("disabled");
				}else{
					$("#detailPrintAll").attr("disabled", "disabled");
				}
			}

			if($(e.target).hasClass("btnPrint")){
				if($(e.target).closest("a").attr("disabled") == "disabled"){
					return false;
				} else {
					if(confirm('是否传输打印此单？')){
						$("#spinModal").modal("show");
						printOne(tr.data("orderId"));
					}
				}
			}
		}
	})

	$("#detailPrintAll").click(function () {
		if(confirm('是否传输打印？')){
			$("#detailModal").modal("hide");
			$("#spinModal").modal("show");
			printOne($("#detailModal").data("orderId"));
		}
	})

	//make tooltip work
	$('body').tooltip(
        {
         selector: "a[rel=tooltip]"
	});
});


