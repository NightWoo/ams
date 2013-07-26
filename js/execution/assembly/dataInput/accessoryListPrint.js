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
				"type" : "accessoryList",
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
			},
			error: function(){alertError();}
		})
	}



	function queryOrdersByBoardNumber(boardNumber) {
		$("#boardNumberInput").val(boardNumber);
		$.ajax({
			url: QUERY_ORDER_BY_BOARD,
			type: "get",
			dataType: "json",
			data: {
				"boardNumber" : boardNumber,
				"type" : "accessoryList",
			},
			success: function (response) {
				$(".boardNumberText").html(boardNumber);
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
						$("<td />").html(order.series).appendTo(tr);
						$("<td />").html(order.car_type_config).appendTo(tr);
						$("<td />").html(order.cold).appendTo(tr);
						$("<td />").html(order.color).appendTo(tr);
						$("<td />").html(order.amount).appendTo(tr);
						$("<td />").html(order.hold).appendTo(tr);
						$("<td />").html(order.count).appendTo(tr);

						if(order.count == order.amount){
							tr.addClass("success");
						}

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
					// printTd = $("<td />").attr("rowspan", num).attr("style", "text-align:center;").addClass("rowSpanTd")
					// a = $("<button />").addClass("btn printGroup").attr("disabled", "disabled").html("<i class='btnPrint icon-print'></i>&nbsp;打印此组");
					// a.data("orderIds", one.orderIds);
					// a.appendTo(printTd);
					// printTd.prependTo(firstTr);
					// if(one.amount == one.count){
					// 	a.removeAttr("disabled").addClass("btn-success");
					// }
		
					$("#tableOrders tbody").append(tmp.children("tr"));
				})
				
				// trPrintAll = $("<tr />");
				// $("<td />").attr("colspan", "12").attr("style", "text-align:center").html("<button class='btn btn-primary printAllByBoard' id='boardPrintAll' disabled><i class='btnPrint icon-print'></i>&nbsp;打印整板</button>").appendTo(trPrintAll);
				// if(response.data.remainTotal == '0'){
				// 	trPrintAll.children("td").children(".printAllByBoard").removeAttr("disabled");
				// }

				// $("#tableOrders tbody").append(trPrintAll);
				$("#tableOrders").show();
			},
			error: function(){alertError}
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

	$(".queryBoardBtn").click(function(){
		boardNumber = $.trim($("#boardNumberInput").val());
		queryOrdersByBoardNumber(boardNumber);
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


