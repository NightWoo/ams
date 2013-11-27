$("document").ready(function() {
	initPage();
	var RefreshLane = setInterval(function () {
		queryLaneOrderInfo();
	},30000);

	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftLaneManageLi").addClass("active");
		queryLaneOrderInfo();
	}

	function queryLaneOrderInfo(){
		$.ajax({
			url: GET_LANE_INFO,
			type: "get",
			dataType: "json",
			data: {},
			success: function(response) {

				$("#laneCount").html(response.data.laneCount);
				$("#tableBoard tbody").html("");
				var boards = response.data.boards;
				$.each(boards, function(board, value){
					var num = value.laneNum;
					console.log(num);
					var tmp = $("<tbody />");
					var lanes = value.lane;
					for(var i=0; i<num; i++){
						$("<tr />").appendTo(tmp);
					}

					laneIndex = 0;
					$.each(lanes, function(lane, value){
						tr = tmp.children("tr:eq(" + laneIndex +")");
						$("<td />").html(value.laneName).appendTo(tr);
						orderTd = $("<td />").html("<a class='btn btn-link goRelease' href='#'' rel='tooltip' data-toggle='tooltip' data-placement='top' title='释放'><i class='btnRelease fa fa-truck'></i></a>").appendTo(tr);
						var progressTd = $("<td />");
						var a = $("<a />").addClass("thumbnail lane").attr("href", "#").attr("laneid", lane);
						var progress = $("<div />").addClass("progress progress-info lane").attr("laneid", lane);
						var bar = $("<div />").addClass("bar lane").attr("laneid", lane).attr("style", "width:" + (parseInt(value.laneCount) / parseInt(value.laneAmount) * 100) + "%").html(value.laneCount + "/" + value.laneAmount);
						if(value.laneCount == value.laneAmount){
							progress.removeClass().addClass("progress").addClass("progress-success");
						} else if (value.laneCount == "0"){
							bar.css("color", "black");
						}

						progress.append(bar);
						a.append(progress);
						progressTd.append(a);
						progressTd.appendTo(tr);
						// console.log(lane);
						tr.data("laneId", lane);
						tr.data("laneName", value.laneName);
						++laneIndex;
					})
					var firstTr = tmp.children("tr:eq(0)");
					firstTr.addClass("thickBorder");

					boardNumberTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(value.boardNumber).prependTo(firstTr);
					
					boardActivateTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(value.boardActivateTime).insertAfter(firstTr.children("td:eq(3)"));
					
					boardStandbyFinishTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd");
					if(value.boardStandbyFinishTime === '0000-00-00 00:00:00'){
						boardStandbyFinishTd.html("<i class='fa fa-clock-o'></i>" + value.boardStandbyLast + "H").insertAfter(firstTr.children("td:eq(4)"));
						if(value.boardStandbyLast >= 12){
							boardStandbyFinishTd.addClass("text-error");
						}
					} else {
						boardStandbyFinishTd.html(value.boardStandbyFinishTime).insertAfter(firstTr.children("td:eq(4)"));
					}
					
					boardOutFinishTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(value.boardOutFinishTime).insertAfter(firstTr.children("td:eq(5)"));
					if(value.boardOutFinishTime === "0000-00-00 00:00:00"){
						if(value.boardStandbyFinishTime === '0000-00-00 00:00:00'){
							boardOutFinishTd.html("未备齐").insertAfter(firstTr.children("td:eq(5)"));
						}else{
							boardOutFinishTd.html("<i class='fa fa-clock-o'></i>" + value.boardOutLast + "H").insertAfter(firstTr.children("td:eq(5)"));
							if(value.boardOutLast >= 12){
								boardOutFinishTd.addClass("text-error");
							}
						}
					} else {
						boardOutFinishTd.html(value.boardOutFinishTime).insertAfter(firstTr.children("td:eq(5)"));
					}

					$("#tableBoard tbody").append(tmp.children("tr"));
					// console.log(tmp);
				})
				
				$("#tableBoard").show();
			},
			error: function(){alertError();}
		})
	}

	function releaseLaneOrders(laneId) {
		$.ajax({
			url: RELEASE_LANE_ORDERS,
			type: "get",
			dataType: "json",
			data: {
				"laneId" : laneId,
			},
			success: function(response){
				queryLaneOrderInfo();
			},
			error: function(){alertError();}
		})
	}


	$("#refresh").click(function(){
		queryLaneOrderInfo();
	})

	$("#tableBoard").live('click', function(e) {
		if($(e.target).is('i')){
			var tr = $(e.target).closest("tr");
			if($(e.target).hasClass("btnRelease")){
				if(confirm("是否释放当前车道["+ tr.data("laneName") +"]的订单？" )){
					releaseLaneOrders(tr.data("laneId"));
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


