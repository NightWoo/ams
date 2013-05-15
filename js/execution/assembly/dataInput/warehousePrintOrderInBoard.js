$("document").ready(function() {
	initPage();
	
	$("#refresh").click(function(){
		getOrderInBoardInfo();
	})

	$(".ordersContainer a").live("click", function(e) {
		orderIds = $(this).data("orderIds");
		$("#orderBoardName").html($(this).data("orderBoardName"));
		$("#legendDetail").show();
		queryOrderDetail(orderIds);
		// $("#printAll").data("orderIds", orderIds);
		console.log($(this).data("toPrint"));
		if($(this).data("toPrint")){
			$("#printAll").removeAttr("disabled").data("orderIds", orderIds);
		} else {
			$("#printAll").attr("disabled", "disabled");
		}
	})

	$("#printAll").click(function(e) {
		orderIds = $(this).data("orderIds");
		if($(e.target).attr("disabled") == "disabled"){
					return false;
		} else {
			if(confirm('是否传输打印？')){
				$("#spinModal").modal("show");
				printAll(orderIds);
			}
		}
	})

	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftWarehousePrintLi").addClass("active");
		$("#legendDetail").hide();
		$("#messageAlert").hide();

		getOrderInBoardInfo();
	}

	function getOrderInBoardInfo() {
		$.ajax({
			url: GET_ORDER_In_BOARD_INFO,
			type: "get",
			data: {},
			dataType: "json",
			success: function (response){
				$("#orderInBoard").html("");
				$("#totalOK").html(response.data.totalToPrint);
				var orderInBoardArray = response.data.orderInBoardArray;
				var orderInBoardInfo = response.data.orderInBoardInfo;
				$.each(orderInBoardInfo, function (orderName, value){
					containerDiv = $("<div />").addClass("ordersContainer");
					a = $("<a />").addClass("btn btn-link").attr("href","#").html(orderName);
					a.data("orderIds", value.orderIdArray);
					a.data("orderBoardName", orderName);
					a.data("toPrint", value.toPrint);
					span = $("<span />").addClass("label").html("NY");
					if(value.toPrint){
						span.addClass("label-success").html("OK");
					}

					containerDiv.append(a).append(span).appendTo($("#orderInBoard"));
				})

				
			},
			error: function(){alertError();}
		})
	}

	function queryOrderDetail(orderIds) {
		$.ajax({
			url: QUERY_CARS_BY_ORDER_IDS,
			type: "get",
			dataType: "json",
			data: {
				"orderIds" : orderIds,
			},
			success: function (response) {
				$("#tableDetail tbody").html("");
				cars = response.data;
				$.each(cars, function (index, value) {
					tr = $("<tr />");
					$('<td />').html(value.lane_name).appendTo(tr);
					$('<td />').html(value.vin).appendTo(tr);
					if(value.distribute_time === '0000-00-00 00:00:00'){
						$('<td />').html('未出库').appendTo(tr);
					} else {
						$('<td />').html(value.distribute_time).appendTo(tr);
					}
					$('<td />').html('新证').appendTo(tr);
					$('<td />').html(value.distributor_name).appendTo(tr);
					$('<td />').html(value.series).appendTo(tr);
					$('<td />').html(value.type_config).appendTo(tr);
					$('<td />').html(value.cold).appendTo(tr);
					$('<td />').html(value.color).appendTo(tr);
					$('<td />').html(value.engine_code).appendTo(tr);

					$("#tableDetail tbody").append(tr);

					tr.data("carId", value.car_id);
					tr.data("vin", value.vin);
				})
				$("#tableDetail").show();
			},
			error: function(){alertError();}
		})
	}

	function printAll(orderIds) {
		$.ajax({
			url: WAREHOUSE_PRINT_BY_ORDERS,
			type: "get",
			dataType: "json",
			data: {
				"orderIds" : orderIds,
			},
			success: function(response){
				if(response.success){
					getOrderInBoardInfo()
					$("#spinModal").modal("hide");
					$("#tableDetail").hide();
					// alert("打印传输完成!");
					fadeMessageAlert(response.message,"alert-success");
				} else {
					fadeMessageAlert(response.message,"alert-error");
				}
			},
			error: function(){alertError();}
		})
	}

	function fadeMessageAlert(message,alertClass){
		$("#messageAlert").removeClass("alert-error alert-success").addClass(alertClass);
		$("#messageAlert").html(message);
		$("#messageAlert").show(500,function () {
			setTimeout(function() {
				$("#messageAlert").hide(1000);
			},30000);
		});
	}

	

	//make tooltip work
	$('body').tooltip(
        {
         selector: "a[rel=tooltip]"
	});
});


