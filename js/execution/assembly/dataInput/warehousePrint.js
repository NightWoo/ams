$("document").ready(function() {
	initPage();
	
	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftWarehousePrintLi").addClass("active");

	}

	function getLaneInfo() {
		$.ajax({
			url: GET_LANE_INFO,
			type: "get",
			data: {},
			dataType: "json",
			success: function (response){
				$("#totalOK").html(response.data.totalToPrint);
				var laneInfo = response.data.laneInfo;
				$.each(laneInfo, function(lane, value){
					$(".laneOK[laneid='"+ lane +"']").html(value.toPrint);
					if(value.toPrint > 0){
						$(".laneOK[laneid='"+ lane +"']").addClass("label-success");
					}else{
						$(".laneOK[laneid='"+ lane +"']").removeClass("label-success");
					}
					progressWidth = (parseInt(value.countSum) / parseInt(value.amountSum)*100);
					$(".bar[laneid='"+ lane +"']").attr("style", "width:" + progressWidth + "%");
					$(".bar[laneid='"+ lane +"']").html(value.countSum + "/" + value.amountSum);
				})
			},
			error: function(){alertError();}
		})
	}

	function queryOrdersByLane(laneId) {
		url: QUERY_ORDER_BY_LANE,
		type: "get",
		data: {
			"laneId" : laneId,
		}
		success: function (response) {

		},
		error: function(){alertError}
	}

	function queryOrderDetail(orderId) {

	}

	function printAll(orderId) {

	}

	function printOne(vin) {

	}

	$("#refreshLane").click(function(){
		getLaneInfo();
	})

	//make tooltip work
	$('body').tooltip(
        {
         selector: "a[rel=tooltip]"
	});
});


