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
		$("#leftAccessoryListPrintLi").addClass("active");
		toggleHint(true);
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
				var totalOK = 0;
				var boardArray = response.data.boardArray;
				var boardInfo = response.data.boardInfo;
				$.each(boardInfo, function (boardNumber, value){
					var div = $("<div />");
					var divContainer = $("<div />").addClass("pull-left boardContainer");
					var a = $("<a />").addClass("thumbnail board").attr("href", "#").attr("boardnum",boardNumber);
					var pNumber = $("<p />").addClass("pull-left board").attr("boardnum",boardNumber).html("#"+boardNumber);
					var progress = $("<div />").addClass("progress progress-info board").attr("boardnum",boardNumber);
					var bar = $("<div />").addClass("bar board").attr("style", "width:" +(parseInt(value.countSum) / parseInt(value.amountSum) * 100) + "%").html(value.countSum + "/" + value.amountSum).attr("boardnum",boardNumber);
					if (value.countSum == value.amountSum) {
	    				progress.removeClass("progress-info").addClass("progress").addClass("progress-success");
	    				totalOK++;
	    			} else if (value.countSum == "0"){
	    				bar.css("color", "black");
	    			}

	    			progress.append(bar);
	    			a.append(pNumber);
	    			// a.append(pOK);
	    			a.append(progress);
	    			divContainer.append(a);
	    			div.append(divContainer);
	    			$("#boardBar").append(div);
				})
				$("#totalOK").html(totalOK);
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
				distributors = [];
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
					$("#tableOrders tbody").append(tmp.children("tr"));

					if($.inArray(one.distributor, distributors) == -1){
						distributors.push(one.distributor);
					}
				})
				$(".distributorsText").html(distributors.join("，"));
				$(".boardNumberTextDiv").show();
				$("#tableOrders").show();
			},
			error: function(){alertError}
		})
	}

	function queryBoardNumberByVin(){
		toggleSeachIcon(".queryVinBtn","fa fa-spinner fa fa-spin");
		$.ajax({
			url: QUERY_BOARD_NUMBER_BY_VIN,
			type: "get",
			dataType: "json",
			data: {
				"vin": $.trim($("#vinInput").val()),
			},
			error: function(){
				toggleSeachIcon(".queryVinBtn","fa fa-search");
				alertError();
			},
			success: function(response) {
				toggleSeachIcon(".queryVinBtn","fa fa-search");
				if(response.success){
					toggleHint(false);
					queryOrdersByBoardNumber(response.data);
					ajaxQueryAccessoryList(response.data);
				} else {
					toggleHint(true);
					alert(response.message);
				}
			}
		});
	}

	function ajaxQueryAccessoryList(boardNumber) {
		$(".accessoryListTable>tbody").html("");
		$.ajax({
			url: QUERY_BOARD_ACCESSORY_LIST,
			type: "get",
			dataType: "json",
			data:{
				"boardNumber" : boardNumber,
			},
			error: function(){alertError();},
			success: function(response) {
				if(response.success){
					$.each(response.data, function (series, detail){
						var num = detail.length;
						var tmp = $("<tbody />");

						for(var i=0; i<num; i++){
							$("<tr />").appendTo(tmp);
						}

						$.each(detail, function (index, value) {
							tr = tmp.children("tr:eq("+ index +")");
							$("<td />").html(value.component_code).appendTo(tr);
							$("<td />").html(value.component_name).appendTo(tr);
							$("<td />").addClass("alignRight").html(value.quantity).appendTo(tr);
							$("<td />").html("").appendTo(tr);
							
							tr.data("componentId", value.component_id);
							tr.data("quantity", value.quantity);
						});

						var firstTr = tmp.children("tr:eq(0)");
						firstTr.addClass("thickBoarder");

						seriesTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(byd.SeriesName[series]).prependTo(firstTr);

						$(".accessoryListTable>tbody").append(tmp.children("tr"));
					})

					$(".accessoryListTable").show();
				} else {
					alertError(response.message);
				}
			}
		})
	}

	function printList(boardNumber) {
		$.ajax({
			url: PRINT_ACCESSORY_LIST,
			type: "get",
			dataType: "json",
			data: {
				"boardNumber" : boardNumber,
			},
			error: function() {alertError();},
			success: function(response) {
				if(response.success) {
					window.print();
				} else {
					alert(response.message);
				}
			},
		})
	}

	function toggleSeachIcon(selector,icon) {
		$(selector).children("i").removeClass().addClass(icon);
	}

	function toggleHint (showHint) {
		if(showHint){
			$("#printBtnDiv").hide();
			$("#hintDiv").fadeIn(1000);

		}else{
			$("#hintDiv").hide();
			$("#printBtnDiv").fadeIn(1000);
		}
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
			toggleHint(false);
			queryOrdersByBoardNumber(boardNumber);
			ajaxQueryAccessoryList(boardNumber);
			curBoard = boardNumber;
		}
	})

	$(".queryBoardBtn").click(function(){
		boardNumber = $.trim($("#boardNumberInput").val());
		queryOrdersByBoardNumber(boardNumber);
		ajaxQueryAccessoryList(boardNumber);
	})

	$(".queryVinBtn").click(function(){
		if($.trim($("#vinInput").val()) != ""){
			queryBoardNumberByVin()
		}
	})

	$(window).bind('keydown', enterHandler);
	function enterHandler (event) {
		if(event.keyCode == '13'){
			if($.trim($("#vinInput").val()) != ""){
				queryBoardNumberByVin()
				return false;
			}
		}
	}

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

	$("#printList").click(function () {
		printList(curBoard);
		getBoardInfo();
	})

	//make tooltip work
	$('body').tooltip(
        {
         selector: "a[rel=tooltip]"
	});
});


