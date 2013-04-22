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
			data: {},
			dataType: "json",
			success: function (response){
				$("#boardBar").html("");
				$("#totalOK").html(response.data.totalToPrint);
				var boardArray = response.data.boardArray;
				var boardInfo = response.data.boardInfo;
				$.each(boardInfo, function (boardNumber, value){
					var div = $("<div />");
					var divContainer = $("<div />").addClass("pull-left boardContainer")
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
	    				progress.removeClass().addClass("progress").addClass("progress-success");
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

				// var laneInfo = response.data.laneInfo;
				// $.each(laneInfo, function(lane, value){
				// 	$(".laneOK[laneid='"+ lane +"']").html(value.toPrint);
				// 	if(value.toPrint > 0){
				// 		$(".laneOK[laneid='"+ lane +"']").addClass("label-success");
				// 	}else{
				// 		$(".laneOK[laneid='"+ lane +"']").removeClass("label-success");
				// 	}
				// 	progressWidth = (parseInt(value.countSum) / parseInt(value.amountSum)*100);
				// 	$(".bar[laneid='"+ lane +"']").attr("style", "width:" + progressWidth + "%");
				// 	$(".bar[laneid='"+ lane +"']").html(value.countSum + "/" + value.amountSum);
				// })
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
			},
			success: function (response) {
				$("#tableOrders tbody").html("");
				orders = response.data;
				$.each(orders, function (index, value) {
					tr = $("<tr />");
					tdBtn = $("<td />");
					tdBtn.html("<a class='btn btn-link goDetail' href='#'' rel='tooltip' data-toggle='tooltip' data-placement='top' title='车辆明细'><i class='btnDetail icon-list'></i></a><a class='btn btn-link goPrint' href='#' rel='tooltip' data-toggle='tooltip' data-placement='top' title='打印'' disabled><i class='btnPrint icon-print'></i></a>");
					tdBtn.appendTo(tr);
					$("<td />").html(value.board_number).appendTo(tr);
					$("<td />").html(value.lane_name).appendTo(tr);
					$("<td />").html(value.order_number).appendTo(tr);
					$("<td />").html(value.distributor_name).appendTo(tr);
					$("<td />").html(value.series).appendTo(tr);
					$("<td />").html(value.car_type_config).appendTo(tr);
					$("<td />").html(value.cold).appendTo(tr);
					$("<td />").html(value.color).appendTo(tr);
					$("<td />").html(value.amount).appendTo(tr);
					$("<td />").html(value.hold).appendTo(tr);
					$("<td />").html(value.count).appendTo(tr);
					if(value.count == value.amount){
						tdBtn.children(".goPrint").removeAttr("disabled");
						tr.addClass("success");
					}
					$("#tableOrders tbody").append(tr);

					tr.data("orderId", value.order_id);
					tr.data("boardNumber", value.board_number);
					tr.data("amount", value.amount);
					tr.data("hold", value.hold);
					tr.data("count", value.count);
					tr.data("orderNumber", value.order_number);
					tr.data("distributorName", value.distributor_name);
					tr.data("laneName", value.lane_name);

				})
				trPrintAll = $("<tr />");
				$("<td />").attr("colspan", "12").attr("style", "text-align:center").html("<button class='btn btn-primary' id='boardPrintAll' disabled><i class='btnPrint icon-print'></i>&nbsp;打印整板</button>").appendTo(trPrintAll);
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
					tdBtn = $("<td />");
					tdBtn.html("<a class='btn btn-link goPrint' href='#' rel='tooltip' data-toggle='tooltip' data-placement='top' title='打印' disabled><i class='btnPrint icon-print'></i></a>");
					tdBtn.appendTo(tr);
					$('<td />').html(value.vin).appendTo(tr);
					$('<td />').html(value.series).appendTo(tr);
					$('<td />').html(value.type_config).appendTo(tr);
					$('<td />').html(value.cold).appendTo(tr);
					$('<td />').html(value.color).appendTo(tr);
					$('<td />').html(value.engine_code).appendTo(tr);
					$('<td />').html(value.status).appendTo(tr);
					if(value.distribute_time === '0000-00-00 00:00:00'){
						$('<td />').html('未出库').appendTo(tr);
					} else {
						$('<td />').html(value.distribute_time).appendTo(tr);
					}

					$("#tableDetail tbody").append(tr);

					tr.data("carId", value.car_id);
					tr.data("vin", value.vin);
				})
				$("#tabelDetail").show();
			},
			error: function(){alertError();}
		})
	}

	function printAll(orderId) {
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
				$("#spinModal").modal("hide")
				alert("打印传输完成!");
			},
			error: function(){alertError();}
		})
	}

	function printOne(vin) {

	}

	$("#refreshLane").click(function(){
		getBoardInfo();
	})

	$("#boardBar").live("click", function(e) {
		if($(e.target).hasClass("board")){
			boardNumber = $(e.target).attr("boardnum");
			queryOrdersByBoardNumber(boardNumber);
			curBoard = boardNumber;
		}
	})

	$("#tableOrders").live('click', function(e) {
		if($(e.target).is('i')) {
			var tr = $(e.target).closest("tr");
			if($(e.target).hasClass("btnDetail")){
				console.log(tr.data("orderId"));
				queryOrderDetail(tr.data("orderId"));
				$("#detailModal").data("orderId", tr.data('orderId'));
				$("#detailModal").data("amount", tr.data('amount'));
				$("#detailModal").data("hold", tr.data('hold'));
				modalHead = "#" + tr.data("laneName") + "_" + tr.data("orderNumber") + "_" + tr.data("distributorName");
				$("#detailModal .modal-header h4").html(modalHead);
				$("#detailModal").modal('show');
			}

			if($(e.target).hasClass("btnPrint")){
				if($(e.target).closest("a").attr("disabled") == "disabled"){
					return false;
				} else {
					if(confirm('是否传输打印？')){
						$("#spinModal").modal("show");
						printAll(tr.data("orderId"));
					}
				}
			}
		}
	})

	//make tooltip work
	$('body').tooltip(
        {
         selector: "a[rel=tooltip]"
	});
});


