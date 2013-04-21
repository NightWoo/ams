$("document").ready(function() {
	initPage();
	
	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftWarehousePrintLi").addClass("active");

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
					var progress = $("<div />").addClass("progress progress-info");
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
					tdBtn = $("<td />");
					tdBtn.html("<a class='btn btn-link goDetail' href='#'' rel='tooltip' data-toggle='tooltip' data-placement='top' title='车辆明细'><i class='btnDetail icon-list'></i></a><a class='btn btn-link goPrint' href='#' rel='tooltip' data-toggle='tooltip' data-placement='top' title='打印'' disabled><i class='btnPrint icon-print'></i></a>");
					if(value.count == value.amount){
						tdBtn.children(".goPrint").removeAttr("disabled");
					}
					tdBtn.appendTo(tr);
					$("#tableOrders tbody").append(tr);
				})
				$("#tableOrders").show();
				
			},
			error: function(){alertError}
		})
	}

	function queryOrderDetail(orderId) {

	}

	function printAll(orderId) {

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
		}
	})

	//make tooltip work
	$('body').tooltip(
        {
         selector: "a[rel=tooltip]"
	});
});


