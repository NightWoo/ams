$("document").ready(function() {
	initPage();
	var orderArray = [];

	$("#btnAdd, #addOrder").click(function() {
		$("#newStandbyDate").val(window.byd.DateUtil.currentDate);
		boardNum = getBoardNumber();
		$("#newBoardNumber").val(boardNum);
		$("#newModal").modal('show');
	})

	$("#newGetOrder").click(function() {
		if($.trim($("#newOrderNumber").val()) != ''){
			ajaxGetOriginalOrders();
		}
	})

	$("#addSpecialOrder").click(function() {
		$("#specialStandbyDate").val(window.byd.DateUtil.currentDate);
		boardNum = getBoardNumber();
		$("#specialBoardNumber").val(boardNum);
		$("#specialModal").modal('show');
	})

	$("#addInternalOrder").click(function() {
		$("#internalStandbyDate").val(window.byd.DateUtil.currentDate);
		boardNum = getBoardNumber();
		$("#internalBoardNumber").val(boardNum);
		laneOption = getLaneList();
		$("#internalLane").append(laneOption);
		$("#internalModal").modal('show');
	})

	$("#specialGetOrder").click(function() {
		if($.trim($("#specialOrderNumber").val()) != ''){
			ajaxGetSpecialOrders();
		}
	})

	$("#specialGetOrder").click(function() {
		if($.trim($("#specialGetOrder").val()) != ''){
			ajaxGetSpeceialOrders();
		}
	})

	$(".clearinput").click(function() {
		$(this).siblings().filter("input").val("");
	})

	$("#refreshDate").click(function() {
		$("#standbyDate").val(window.byd.DateUtil.currentDate);
	})

	$("#btnQuery").click(function() {
		standbyDate = $.trim($("#standbyDate").val());
		boardNumber = $.trim($("#boardNumber").val());
		orderNumber = $.trim($("#orderNumber").val());
		distributor = $.trim($("#distributor").val());
		if(standbyDate == "" && orderNumber =="" && distributor =="" && boardNumber == ""){
			alert("至少要有1个查询条件")
		} else{
			ajaxQuery();
		}
	})

	$("#newOrderNumber").bind("keydown", function(event){
		if(event.keyCode == "13"){
			if($.trim($("#newOrderNumber").val()) != ''){
				ajaxGetOriginalOrders();
			}
			return false;
		}
	})

	$("#newClearOrder").click(function(){
		resetNewModal();
	})

	$("#btnAddMore").click(function(){
		// ajaxGenerate();
		// ajaxDetailExist();
		details = packOrders();
		ajaxGenerate(details);
	})

	$("#btnAddConfirm").click(function(){
		//ajaxGenerate();
		// ajaxDetailExist();
		details = packOrders();
		ajaxGenerate(details);
		$("#newModal").modal('hide');
	})

	$("#specialOrderNumber").bind("keydown", function(event){
		if(event.keyCode == "13"){
			if($.trim($("#specialOrderNumber").val()) != ''){
				ajaxGetSpecialOrders();
			}
			return false;
		}
	})

	$("#specialClearOrder").click(function(){
		resetSpecialModal();
	})

	$("#btnAddConfirmSpecial").click(function(){
		details = packSpecialOrders();
		ajaxGenerate(details);
		$("#specialModal").modal('hide');
	})

	$("#btnAddMoreSpecial").click(function(){
		details = packSpecialOrders();
		ajaxGenerate(details);
	})

	$("#btnSplitConfirm").click(function(){
		ajaxSplit();
		$("#splitModal").modal('hide');
	})

	$("#tableResult").live("click", function(e) {
		if ($(e.target).is("i") || $(e.target).is("a")) {
			var siblings = $(e.target).closest("td").siblings();
			var tr = $(e.target).closest("tr");

			if($(e.target).hasClass("icon-thumbs-up")) {
				// ajaxTop(tr.data("id"));
				ajaxSetBoardTop(tr.data("boardNumber"));
			// } else if($(e.target).hasClass("icon-hand-up")){
			// 	ajaxUp(tr.data("id"));
			} else if($(e.target).hasClass("icon-trash")) {
				if(confirm('是否删除本订单？')){
					ajaxDelete(tr.data("id"));
				}	
			} else if($(e.target).hasClass("icon-edit")) {
				emptyEditModal();
				carSeries = tr.data("series");
				carType = tr.data("carType");

				if (tr.data("toCount") == '1') {
					$("#editToCount").attr("checked", "checked");
				} else {
					$("#editToCount").removeAttr("checked");
				}

				$("#editCarType").html("").append(fillType(carSeries));
				$("#editOrderConfig").html("").append(fillOrderConfig(carSeries, carType));
				$("#editColor").html("").append(fillColor(carSeries));

				$("#editStandbyDate").val(tr.data("standbyDate"));
				$("#editStatus").val(tr.data("status"));

				$("#editLane").val(tr.data("laneId"));
				$("#editBoardNumber").val(tr.data("boardNumber"));
				$("#editCarrier").val(tr.data("carrier"));
				$("#editDistributorName").val(tr.data("distributorName"));
				$("#editAmount").val(tr.data("amount"));
				$("#editSeries").val(carSeries);
				$("#editCarType").val(tr.data("carType"));
				$("#editColor").val(tr.data("color"));
				$("#editOrderConfig").val(tr.data("orderConfigId"));
				if (tr.data("coldResistant") == '1') {
					$("#editColdResistant").attr("checked", "checked");
				} else {
					$("#editColdResistant").removeAttr("checked");
				}
				$("#editRemark").val(tr.data("remark"));

				$("#editModal").data("id", tr.data("id"));

				$("#editModal").modal("show");
			// } else if($(e.target).hasClass("icon-resize-full")){
			// 	emptySplitModal();
			// 	$("#splitModal").data("id",tr.data("id"));
			// 	$("#splitModal").modal("show");
			} else if($(e.target).hasClass("icon-tags")){
				if($(e.target).parent().attr("disabled") != "disabled"){
					emptyManualModal();
					tr = $(e.target).closest("tr");
					$("#manualModal").data("orderId", tr.data("id"));
					$("#manualModal").data("series", tr.data("series"));
					$("#manualModal").data("orderConfigId", tr.data("orderConfigId"));
					$("#manualModal").data("coldResistant", tr.data("coldResistant"));
					$("#manualModal").data("color", tr.data("color"));
					$("#manualModal .modal-header h4").html("手工配单" + tr.data("orderNumber") + "-" + tr.data("distributorName"));
					$("#manualModal").modal("show");
				}
			} else if($(e.target).attr("action") == "activate"){
				ajaxActivateBoard(tr.data("boardNumber"));
			} else if($(e.target).attr("action") == "frozen"){
				ajaxFrozenBoard(tr.data("boardNumber"));
			}
		}
	})

	$("#tableSpecialOrder").live("click", function(e){
		if ($(e.target).is("i")) {
			// var siblings = $(e.target).closest("td").siblings();
			var tr = $(e.target).closest("tr");
			if($(e.target).hasClass("specialSplit")) {
				splitSpecialOrder(tr);
			}
		}
	})

	$("#editSeries").change(function() {
		carSeries = $(this).val();
		$("#editCarType").html("").append(fillType(carSeries));
		$("#editOrderConfig").html("");
		$("#editColor").html("").append(fillColor(carSeries));
	})

	$("#editCarType").change(function() {
		carSeries = $("#editSeries").val();
		carType = $(this).val();
		$("#editOrderConfig").html("").append(fillOrderConfig(carSeries, carType));
	})

	$("#btnEditConfirm").click(function() {
		ajaxEdit();
		$("#editModal").modal("hide");
	})

	$("#manualSearch").click(function() {
		ajaxManualQuery();
	})

	$("#btnAddMoreManual").click(function(){
		ajaxManualMatch();
	})

	$("#btnAddConfirmManual").click(function(){
		ajaxManualMatch();
		$("#manualModal").modal('hide');
	})

	$("#internalSeries").change(function() {
		carSeries = $(this).val();
		$("#internalCarType").html("").append(fillType(carSeries));
		$("#internalOrderConfig").html("");
		$("#internalColor").html("").append(fillColor(carSeries));
	})

	$("#internalCarType").change(function() {
		carSeries = $("#internalSeries").val();
		carType = $(this).val();
		$("#internalOrderConfig").html("").append(fillOrderConfig(carSeries, carType));
	})

	$("#btnInternalConfirm").click(function() {
		ajaxAddInternalOrder();
		ajaxQuery();
		$("#internalModal").modal("hide");
		emptyInternalModal();
	})

	$("#standbyDate").datetimepicker({
	    format: 'yyyy-mm-dd',
	    minView: 2,
	    autoclose: true,
		todayBtn: true,
		pickerPosition: "bottom-left",
		language: "zh-CN"
    });
	
	function initPage() {
		// $("#headPlanLi").addClass("active");
		// $("#leftOutStandbyMaintainLi").addClass("active");
		$("#headPlanLi").addClass("active");
		$("#leftOrderMaintainLi").addClass("active");

		$("#standbyDate").val(window.byd.DateUtil.currentDate);

	}

	function ajaxGetOriginalOrders() {
		$.ajax({
			url: GET_ORIGIANAL_ORDERS,
			type: "post",
			dataType: "json",
			data: {
				"orderNumber" : $.trim($("#newOrderNumber").val())
			},
			success: function(response) {
				if(response.success && response.data.length != 0){
					$("#newOrderNumber").val($.trim($("#newOrderNumber").val()));
					$("#newDistributor").attr("code", response.data[0].distributor_code).html(response.data[0].distributor);
					toggleOrderInfo(true);
					// $("#tableNewOrder").hide();
					// $("#tableNewOrder tbody").html("");
					var i=0;
					optionLane = getLaneList();
					selectLane = "<select class='input-small selectLane'>" + optionLane + "</select>";
					$.each(response.data, function (index, value){
						var tr = $("<tr />");
						// $("<td />").html(value.order_detail_id).appendTo(tr);
						if(value.amount>0){
							tdCheck =  "<input class='choose' type='checkbox' checked='checked' />";
						} else {
							tdCheck =  "<input class='choose' type='checkbox' disabled/>";
						}
						$("<td />").html(tdCheck).appendTo(tr);
						distributor = value.distributor
						$("<td />").html(distributor).appendTo(tr);
						// tdBoardNum = "<input type='text' id='newBoardNum'"+ index +"' class='input-mini newBoardNum' />"
						// $("<td />").html(tdBoardNum).appendTo(tr);
						// tdAmount = "<input type='text' id='newAmount'" + index +"' class='input-mini newAmount' value='"+ value.amount +"'/>";
						tdAmount = "<input type='text' class='input-mini newAmount' value='"+ value.amount +"'/>";
						$("<td />").html(tdAmount).appendTo(tr);
						// $("<td />").html(value.amount).appendTo(tr);
						if(value.series === '6B'){
							$("<td />").html('思锐').appendTo(tr);
						} else {
							$("<td />").html(value.series).appendTo(tr);
						}
						$("<td />").html(value.car_type).appendTo(tr);
						if(value.cold_resistant == '1'){
							$("<td />").html("耐寒").appendTo(tr);
						} else {
							$("<td />").html("非耐寒").appendTo(tr);
						}
						$("<td />").html(value.color).appendTo(tr);
						
						
						configTip = "<select class='input-medium newOrderConfig' rel='tooltip' data-toggle='tooltip' data-placement='top' title='"+ value.config_description +"' />"
						options = fillOrderConfig(value.series, value.car_type);
						configSelect = $(configTip).addClass("orderConfigSelect").append(options);
						$("<td />").append(configSelect).appendTo(tr);

						// tdLane = "<input type='text' class='input-mini newLane'/>";
						// $("<td />").html(tdLane).appendTo(tr);
						$("<td />").html(selectLane).appendTo(tr);

						// inputDate = "<input type='text' id='newStandbyDate"+ index +"' class='input-small newStandbyDate' placeholder='备车日期...' onClick=\"WdatePicker({el:'newStandbyDate"+ index +"',dateFmt:'yyyy-MM-dd'});\"/>";
						// $("<td />").html(inputDate).appendTo(tr);

						tr.data("orderDetailId", value.order_detail_id);
						tr.data("distributorName", value.distributor);
						tr.data("distributorCode", value.distributor_code);
						tr.data("orderNumber", value.order_number);
						tr.data("series", value.series);
						tr.data("carTypeCode", value.car_type_code);
						tr.data("sellCarType", value.sell_car_type);
						tr.data("carModel", value.car_model);
						tr.data("carType", value.car_type);
						tr.data("color", value.color);
						tr.data("sellColor", value.sell_color);
						// tr.data("amount", value.amount);
						tr.data("orderNature", value.order_nature);
						tr.data("coldResistant", value.cold_resistant);
						tr.data("remark", value.remark);
						tr.data("orderType", '普通');
						tr.data("configDescription" , value.config_description);

						$("#tableNewOrder tbody").append(tr);
						
					})

					$(".newStandbyDate").val(window.byd.DateUtil.currentDate);
					$("#tableNewOrder").show();
				} else {
					alert("销服系统查无订单[" + $("#newOrderNumber").val() + "]");
					resetNewModal();
				}
			},
			error: function(){
				alertError();
			}
		})
	}


	function packOrders() {
		orderArray = [];
		$("#tableNewOrder tbody tr").each(function (index, tr){
			thisAmount = $(tr).find("input").filter(".newAmount").val();
			// thisBoard = $(tr).find("input").filter(".newBoardNum").val();
			thisBoard = $("#newBoardNumber").val();
			thisConfig = $(tr).find("select").filter(".newOrderConfig").val();
			// thisDate = $(tr).find("input").filter(".newStandbyDate").val();
			thisDate = $("#newStandbyDate").val();
			thisCarrier = $("#newCarrier").val();
			thisLane = $(tr).find("select").filter(".selectLane").val();
			$(tr).data("orderConfigId", thisConfig);
			$(tr).data("standbyDate", thisDate);
			$(tr).data("carrier", thisCarrier);
			$(tr).data("amount", thisAmount);
			$(tr).data("boardNumber", thisBoard);
			$(tr).data("laneId", thisLane);
			chosen = ($(tr).find("input").filter(".choose").attr("checked") === "checked");
			
			console.log(chosen);
			if(chosen){
				orderArray.push($(tr).data());
			}
		})

		var  orderObj ={};
		for(var i=0; i<orderArray.length;i++){
			orderObj[i] = orderArray[i];
		}
		var jsonText = JSON.stringify(orderObj);

		return jsonText;
	}

	function ajaxGetSpecialOrders() {
		$("#tableSpecialOrder tbody").html("");
		specialNumber = $.trim($("#specialOrderNumber").val());
		$.ajax({
			url: GET_SPECIAL_ORDERS,
			type: "post",
			dataType: "json",
			data: {
				"specialNumber" : specialNumber,
			},
			success: function(response) {
				if(response.success && response.data.length != 0){
					$("#specialOrderNumber").val($.trim($("#specialOrderNumber").val()));
					toggleSpecialOrderInfo(true);
					var i=0;
					optionLane = getLaneList();
					selectLane = "<select class='input-small selectLane'>" + optionLane + "</select>";
					console.log(selectLane);
					$.each(response.data, function (index, value) {
						var tr = $("<tr />");
						tdCheck = "<input class='choose' type='checkbox' checked='checked' />&nbsp;&nbsp;<a href='#'' rel='tooltip' data-toggle='tooltip' data-placement='top' title='分拆''><i class='icon-resize-full specialSplit'></i></a>";
						$("<td />").html(tdCheck).appendTo(tr);

						// tdSplit = "<a href='#'' rel='tooltip' data-toggle='tooltip' data-placement='top' title='分拆''><i class='icon-resize-full'></i></a>";
						// $("<td />").html(tdSplit).appendTo(tr);

						tdAmount = "<input type='text' class='input-mini specialAmount' value='"+ value.amount +"'/>";
						$("<td />").html(tdAmount).appendTo(tr);
						if(value.series === '6B'){
							$("<td />").html('思锐').appendTo(tr);
						} else {
							$("<td />").html(value.series).appendTo(tr);
						}
						$("<td />").html(value.car_type).appendTo(tr);
						if(value.cold_resistant == '1'){
							$("<td />").html("耐寒").appendTo(tr);
						} else {
							$("<td />").html("非耐寒").appendTo(tr);
						}
						$("<td />").html(value.color).appendTo(tr);

						$("<td />").html(value.order_config_name).appendTo(tr);
						
						// tdLane = "<input type='text' class='input-mini specialLane'/>";
						$("<td />").html(selectLane).appendTo(tr);

						tr.data("orderConfigId", value.order_config_id);
						tr.data("distributorName", value.export_country);
						tr.data("country", value.mark_clime);
						tr.data("orderDetailId", 0);
						tr.data("distributorCode", '');
						tr.data("orderNumber", specialNumber);
						tr.data("series", value.series);
						tr.data("carType", value.car_type);
						tr.data("sellCarType", '');
						tr.data("color", value.color);
						tr.data("sellColor", value.color);
						tr.data("orderNature", 0);
						tr.data("coldResistant", value.cold_resistant);
						tr.data("remark", value.remark);
						tr.data("orderType", '出口');
						tr.data("configDescription" , '');

						$("#tableSpecialOrder tbody").append(tr);

						$(".specialStandbyDate").val(window.byd.DateUtil.currentDate);
						$("#tableSpecialOrder").show();
					})
				} else {
					alert("查无匹配单[" + $("#specialOrderNumber").val() + "]");
					resetNewModal();
				}
			},
			error: function(){
				alertError();
			}
		})
	}

	function splitSpecialOrder(tr) {
		newTr = tr.clone(true);
		newTr.find("input").filter(".specialAmount").val(0);
		newTr.insertAfter(tr);
	}

	function packSpecialOrders() {
		orderArray = [];
		$("#tableSpecialOrder tbody tr").each(function (index, tr){
			thisAmount = $(tr).find("input").filter(".specialAmount").val();
			thisBoard = $("#specialBoardNumber").val();
			thisDate = $("#specialStandbyDate").val();
			thisLane = $(tr).find("select").filter(".selectLane").val();
			$(tr).data("standbyDate", thisDate);
			$(tr).data("amount", thisAmount);
			$(tr).data("boardNumber", thisBoard);
			$(tr).data("laneId", thisLane);
			$(tr).data("carrier", "");
			chosen = ($(tr).find("input").filter(".choose").attr("checked") === "checked");
			
			if(chosen){
				orderArray.push($(tr).data());
			}
			console.log(orderArray);
		})

		var  orderObj ={};
		for(var i=0; i<orderArray.length;i++){
			orderObj[i] = orderArray[i];
		}
		var jsonText = JSON.stringify(orderObj);

		return jsonText;
	}

	function ajaxDetailExist(){
		details = packOrders();
		$.ajax({
			url: ORDER_CHECK_DETAIL,
			type: "post",
			dataType: "json",
			data:{
				"orderDetails" : details
			},
			success: function(response) {
				if(response.success){
					if(response.data.length == 0){
						ajaxGenerate(details)
					} else {
						// confirmMessage = '订单明细：';
						// detailIds= response.data.join(",");
						// confirmMessage += detailIds;
						// confirmMessage += '已经录入过AMS，请确认是否确实需要录入？'
						// if(confirm(confirmMessage)) 
							ajaxGenerate(details);
					}
				}else{
					alert(response.message);
				}
			},
			error: function(){
				alertError();
			}
		})
	}

	function ajaxGenerate(details) {
		//details = packOrders();
		$.ajax({
			url: ORDER_GENERATE,
			type: 'post',
			dataType: "json",
			data: {
				"orderDetails" : details
			},
			success: function(response) {
				if(response.success){
					resetNewModal();
					resetSpecialModal();
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alertError();
			}
		})
	}

	function ajaxQuery() {
		orderNumber = $.trim($("#orderNumber").val());
		boardNumber = $.trim($("#boardNumber").val());
		distributor = $.trim($("#distributor").val());
		$("#tableResult>tbody").html("");
		$.ajax({
			type: "get",
			dataType: "json",
			url: QUERY_BOARD_ORDERS,
			data: {
				"standbyDate": $("#standbyDate").val(),
				"orderNumber": orderNumber,
				"boardNumber": boardNumber,
				"distributor": distributor,
				"series" : $("#selectSeries").val(),
		    	"status" : getStatusChecked(),
				// "orderBy": 'priority,lane_id,`status`',
				"orderBy": 'priority,board_number,lane_id,`status`',
			},
			success: function(response) {
				if(response.success) {
					var boards = response.data;
					$.each(boards, function (index, value) {
						var num = value.orders.length;
		    			var tmp = $("<tbody />");

		    			for(var i=0; i<num; i++){
		    				tr = $("<tr />").appendTo(tmp);
		    				switch(value.boardStatus){
			    				case "0" :
			    					tr.addClass("info");
			    					break;
		    					case "1" :
			    					break;
			    				case "2" :
			    					tr.addClass("success");
			    					break;
			    				default: 
			    					statusA = "";
			    			}
		    			};


		    			$.each(value.orders, function (index, order) {
		    				tr = tmp.children("tr:eq("+ index +")");
		    				editTd = $("<td />");
		    				editTd.html('<a class="btn btn-link" rel="tooltip" data-toggle="tooltip" data-placement="top" title="删除"><i class="icon-trash"></i></a>'
		    					+'&nbsp;<a class="btn btn-link" rel="tooltip" data-toggle="tooltip" data-placement="top" title="编辑"><i class="icon-edit"></i></a>'
								+'&nbsp;<a class="btn btn-link" rel="tooltip" data-toggle="tooltip" data-placement="top" title="手动配单"><i class="icon-tags"></i></a>').appendTo(tr);
							editTd.appendTo(tr);
							laneName = order.lane_name == "" ? "-" : order.lane_name
		    				$("<td />").html(laneName).appendTo(tr);

		    				$("<td />").html(order.order_number).appendTo(tr);
		    				$("<td />").html(order.distributor_name).appendTo(tr);
		    				if(order.series == '6B'){
			    				$("<td />").html('思锐').appendTo(tr);
		    				} else {
			    				$("<td />").html(order.series).appendTo(tr);
		    				};
		    				$("<td />").html(order.car_type_config + "/" + order.cold).appendTo(tr);
		    				$("<td />").html(order.color).appendTo(tr);
		    				$("<td />").html(order.amount).appendTo(tr);
		    				$("<td />").html(order.hold).appendTo(tr);
		    				$("<td />").html(order.count).appendTo(tr);


							countIcon = order.to_count == "1" ? "<i class='icon-pushpin'></i>" : "";
		    				if(order.short < 0){
		    					$(tr).removeClass('info warning success');
		    					$(tr).addClass('warning');
		    					$("<td />").html(countIcon + order.short).addClass("text-error alignCenter").appendTo(tr);
	    					} else {
	    						$("<td />").addClass("alignCenter").html(countIcon).appendTo(tr);
	    					}

	    					tr.data("id", order.id);
							tr.data("orderNumber", order.order_number);
							tr.data("standbyDate", order.standby_date);
							tr.data("priority", order.priority);
							tr.data("status", order.status);
							tr.data("amount", order.amount);
							tr.data("hold", order.hold);
							tr.data("count", order.count);
							tr.data("series", order.series);
							tr.data("carType", order.car_type);
							tr.data("color", order.color);
							tr.data("coldResistant", order.cold_resistant);
							tr.data("orderConfigId", order.order_config_id);
							tr.data("laneId", order.lane_id);
							tr.data("distributorName", order.distributor_name);
							tr.data("distributorCode", order.distributor_code);
							tr.data("carrier", order.carrier);
							tr.data("remark", order.remark);
							tr.data("boardNumber", order.board_number);
							tr.data("toCount", order.to_count);
							// tr.data("orderNature", order.order_nature);
							// tr.data("country", order.country);
							// tr.data("city", order.city);
							// tr.data("sellCarType", order.sell_color);
							// tr.data("configDescription", order.config_description);
		    			})
						var firstTr = tmp.children("tr:eq(0)");
		    			firstTr.addClass("thickBorder");
		    			boardTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(value.boardNumber).prependTo(firstTr);
		    			switch(value.boardStatus){
		    				case "0" :
		    					statusA = "<a class='btn btn-link' rel='tooltip' data-toggle='tooltip' data-placement='top' title='激活此板' action='activate'>激活</a>";
		    					break;
	    					case "1" :
		    					statusA = "<a class='btn btn-link' rel='tooltip' data-toggle='tooltip' data-placement='top' title='冻结此板' action='frozen'>冻结</a>";
		    					break;
		    				case "2" :
		    					statusA = "已关闭";
		    					break;
		    				default: 
		    					statusA = "";
		    			}
		    			if(value.boardShort <0 && value.boardStatus==0) statusA="库存不足";
		    			statusTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(statusA).prependTo(firstTr);
		    			priorityTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(value.boardPriority).prependTo(firstTr);
		    			reorderA = value.boardPriority != 0 ? "<a class='btn btn-link' rel='tooltip' data-toggle='tooltip' data-placement='top' title='置顶此板'><i class='icon-thumbs-up'></i></a>" : "";
		    			priorityTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(reorderA).prependTo(firstTr);

		    			$("#tableResult>tbody").append(tmp.children("tr"));
					});
					$("#tableResult").show();
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alertError();
			}
		})

	}

	function ajaxEdit() {
		var toCount = 0;
		if($("#editToCount").attr("checked") === "checked")
			toCount = 1;

		var isCold = 0;
		if($("#editColdResistant").attr("checked") === "checked")
			isCold = 1;

		$.ajax({
			type: "get",
			dataType: "json",
			url: ORDER_SAVE,
			data: {
				"id": $("#editModal").data("id"),
				"boardNumber": $("#editBoardNumber").val(),
				"carrier": $("#editCarrier").val(),
				"standbyDate": $("#editStandbyDate").val(),
				"status": $("#editStatus").val(),
				"laneId": $("#editLane").val(),
				"distributorName": $("#editDistributorName").val(),
				"amount": $("#editAmount").val(),
				"series": $("#editSeries").val(),
				"carType": $("#editCarType").val(),
				"orderConfigId": $("#editOrderConfig").val(),
				"color": $("#editColor").val(),
				"coldResistant": isCold,
				"remark": $("#editRemark").val(),
				"toCount": toCount,
			},
			success: function(response) {
				if(response.success) {
					emptyEditModal();
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function(){alertError();}
		})
	}

	function ajaxAddInternalOrder() {
		var toCount = 0;
		if($("#internalToCount").attr("checked") === "checked")
			toCount = 1;

		var isCold = 0;
		if($("#internalColdResistant").attr("checked") === "checked")
			isCold = 1;

		$.ajax({
			type: "get",
			dataType: "json",
			url: ORDER_SAVE,
			data: {
				"boardNumber": $("#internalBoardNumber").val(),
				"standbyDate": $("#internalStandbyDate").val(),
				// "status": $("#internalStatus").val(),
				"laneId": $("#internalLane").val(),
				"distributorName": $("#internalDistributorName").val(),
				"amount": $("#internalAmount").val(),
				"series": $("#internalSeries").val(),
				"carType": $("#internalCarType").val(),
				"orderConfigId": $("#internalOrderConfig").val(),
				"color": $("#internalColor").val(),
				"coldResistant": isCold,
				"remark": $("#internalRemark").val(),
				"toCount": toCount,
			},
			success: function(response) {
				if(response.success) {
					emptyInternalModal();
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function(){alertError();}
		})
	}

	function ajaxDelete(orderId) {
		$.ajax({
			type: "get",
			dataType: "json",
			url: ORDER_DELETE,
			data: {
				"id": orderId
			},
			success: function(response) {
				if(response.success) {
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function() {alertError();}
		})
	}

	function ajaxSetBoardTop(boardNumber) {
		$.ajax({
			type: "get",
			dataType: "json",
			url: SET_BOARD_TOP,
			data: {
				"boardNumber": boardNumber,
			},
			success: function(response) {
				if(response.success) {
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function(){alertError();}
		})
	}

	function ajaxActivateBoard(boardNumber) {
		$.ajax({
			type: "get",
			dataType: "json",
			url: ACTIVATE_BOARD,
			data: {
				"boardNumber": boardNumber,
			},
			success: function(response) {
				if(response.success) {
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function(){alertError();}
		})
	}

	function ajaxFrozenBoard(boardNumber) {
		$.ajax({
			type: "get",
			dataType: "json",
			url: FROZEN_BOARD,
			data: {
				"boardNumber": boardNumber,
			},
			success: function(response) {
				if(response.success) {
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function(){alertError();}
		})
	}

	function ajaxTop(orderId) {
		$.ajax({
			type: "get",
			dataType: "json",
			url: ORDER_TOP_PRI,
			data: {
				"id": orderId
			},
			success: function(response) {
				if(response.success) {
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function(){alertError();}
		})
	}

	function ajaxUp(orderId) {
		$.ajax({
			type: "get",
			dataType: "json",
			url: ORDER_INC_PRI,
			data: {
				"id": orderId
			},
			success: function(response) {
				if(response.success) {
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function(){alertError();}
		})
	}

	function ajaxSplit(orderId){
		$.ajax({
			url: ORDER_SPLIT,
			type: 'get',
			dataType: 'json',
			data: {
				"id" : $("#splitModal").data("id"),
				"number" : $.trim($("#splitAmount").val()),
				"laneId" : $("#splitLane").val(),
			},
			success: function(response) {
				if(response.success) {
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alertError();
			}
		})
	}

	function ajaxManualQuery(){
		$.ajax({
			url: ORDER_MANUAL_QUERY,
			type: "get",
			dataType: "json",
			data: {
				"vinText": $("#manualVinText").val(),
			},
			success: function(response) {
				if(response.success){
					$("#tableManual>tbody").html("");
					data = response.data;
					$.each(data, function (index, value){
						var tr = $("<tr />");

						if(value.order_id > 0 || value.order_config_id != $("#manualModal").data("orderConfigId") || value.cold_resistant != $("#manualModal").data("coldResistant") || value.color != $("#manualModal").data("color")){
							tdCheck ="<input class='choose' type='checkbox' disabled/>"
						} else {
							tdCheck = "<input class='choose' type='checkbox'/>";
						}
						$("<td />").html(tdCheck).appendTo(tr);
						$("<td />").html(value.vin).appendTo(tr);
						$("<td />").html(value.series).appendTo(tr);
						$("<td />").html(value.order_config_name).appendTo(tr);
						$("<td />").html(value.cold).appendTo(tr);
						$("<td />").html(value.color).appendTo(tr);
						$("<td />").html(value.engine_code).appendTo(tr);
						$("<td />").html(value.remark).appendTo(tr);

						tr.data("vin", value.vin);

						$("#tableManual>tbody").append(tr);
					})

					$("#tableManual").show();
				}
			},
			error: function() {
				alertError();
			}
		})
	}

	function ajaxManualMatch(){
		vins  = packManualVin();
		$.ajax({
			url: ORDER_MATCH_MANUALLY,
			type: "post",
			dataType: "json",
			data: {
				"orderId" : $("#manualModal").data("orderId"),
				"vins" : vins,
			},
			success: function(response) {
				if(response.success){
					$("#tableManual>tbody").html("");
					$("#tableManual").hide();
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alertError();
			}
		})
	}

	function packManualVin() {
		vinArray = [];
		$("#tableManual tbody tr").each(function (index, tr){
			chosen = ($(tr).find("input").filter(".choose").attr("checked") === "checked");
			if(chosen){
				vinArray.push($(tr).data("vin"));
			}
		})

		var  vinObj ={};
		for(var i=0; i<vinArray.length;i++){
			vinObj[i] = vinArray[i];
		}
		var jsonText = JSON.stringify(vinObj);

		return jsonText;
	}

	function getLaneList(){
		// var options = "<select class='input-small selectLane'>"
		var options = "<option value='0' selected>未选择</option>";
		$.ajax({
			url: FILL_LANE,
			type: "get",
			dataType: "json",
			data: {},
			async: false,
			success: function(response) {
				if(response.success){
					$.each(data = response.data, function (index, value) {
						options += '<option value="' + value.lane_id +'">'+ value.lane_name +'</option>';
					});
					// options += "</select>";
				}
			},
			error: function(){
				alertError();
			}
		})
		return options;
	}

	function fillOrderConfig(carSeries, carType){
		var options = '<option value="0" selected>请选择</option>';
		$.ajax({
			url: FILL_ORDER_CONFIG,
			type: "get",
			dataType: "json",
			data: {
				"carSeries" : carSeries,
				"carType" : carType,	
			},
			async: false,
			success: function(response) {
				if(response.success){						
					$.each(response.data, function(index,value){
						// option +='<option value="' + value.config_id +'">'+ value.config_name +'</option>';	
						options +='<option value="' + value.config_id +'">'+ value.config_name +'</option>';	
					});
				}
			},
			error: function() { 
		    	alertError(); 
		    }
		})
		return options;
	}

	function fillColor(carSeries) {
		var options = '<option value="" selected>请选择</option>';
		$.ajax({
			url: FILL_CAR_COLOR,
			type: "get",
			dataType: "json",
			data: {
				"carSeries" : carSeries
			},
			async: false,
			success: function(response) {
				if(response.success){
					$.each(response.data, function(index, value){
						options += '<option value="'+ value.color +'">'+ value.color +'</option>';
					});
				}
			},
			error: function() {
				alertError();
			}
		})
		return options;
	}

	function fillType(carSeries) {
		var options = '<option value="" selected>请选择</option>';
		$.ajax({
			url: FILL_ORDER_CAR_TYPE,
			type: "get",
			dataType: "json",
			data: {
				"carSeries" : carSeries	
			},
			async: false,
			success: function(response) {
				if(response.success){
					$.each(response.data, function(index, value){
						options += '<option value="'+ value +'">'+ value +'</option>';
					});
				}
			},
			error: function() { 
		    	alertError(); 
		    }
		})
		return options;
	}

	function emptyEditModal (argument) {
		$("#editModal").data("id", 0),
		$("#editStandbyDate").val(""),
		$("#editStatus").val("0"),
		// $("#editLane").val("0"),
		option = getLaneList();
		$("#editLane").append(option);
		$("#editDistributorName").val(""),
		$("#editAmount").val(""),
		$("#editSeries").val(""),
		$("#editCarType").val(""),
		$("#editOrderConfig").val("0"),
		$("#editColor").val(""),
		$("#editColdResistant").removeAttr("checked");
		$("#editRemark").val("")
	}

	function emptyInternalModal (argument) {
		$("#internalModal").data("id", 0),
		$("#internalStandbyDate").val(""),
		$("#internalStatus").val("0"),
		// $("#internalLane").val("0"),
		option = getLaneList();
		$("#internalLane").append(option);
		$("#internalDistributorName").val(""),
		$("#internalAmount").val(""),
		$("#internalSeries").val(""),
		$("#internalCarType").val(""),
		$("#internalOrderConfig").val("0"),
		$("#internalColor").val(""),
		$("#internalColdResistant").removeAttr("checked");
		$("#internalRemark").val("")
	}

	function emptyManualModal(argument) {
		$("#manualModal").data("orderId", 0);
		$("#manualModal").data("series", "");
		$("#manualModal").data("orderConfigId", 0);
		$("#manualModal").data("coldResistant", 0);
		$("#manualModal").data("color", '');
		$("#manualVinText").val("");
		$("#tableManual>tbody").html("");
		$("#tableManual").hide();
	}
	
	function resetNewModal (argument) {
		clearOrderArray()
		$("#newOrderNumber").val("");
		$("#tableNewOrder").hide();
		$("#tableNewOrder tbody").html("");
		boardNum = getBoardNumber();
		$("#newBoardNumber").val(boardNum);
		toggleOrderInfo(false);
	}

	function resetSpecialModal (argument) {
		clearOrderArray()
		$("#specialOrderNumber").val("");
		$("#tableSpecialOrder").hide();
		$("#tableSpecialOrder tbody").html("");
		boardNum = getBoardNumber();
		$("#sepcialBoardNumber").val(boardNum);
		toggleSpecialOrderInfo(false);
	}

	function emptySplitModal() {
		$("#splitLane").val("0");
		$("#splitAmount").val("");
		$("#splitModal").data("id", "0");
	}

	function clearOrderArray() {
		orderArray = [];
	}

	function emptyLaneModal (argument) {

	}

	function toggleOrderInfo (showInfo) {
		if(showInfo){
			$("#hint").hide();
			$("#orderInfo").fadeIn(500);

		}else{
			$("#orderInfo").hide();
			$("#hint").fadeIn(500);
		}
	}

	function toggleSpecialOrderInfo (showInfo) {
		if(showInfo){
			$("#specialHint").hide();
			$("#specialOrderInfo").fadeIn(500);

		}else{
			$("#specialOrderInfo").hide();
			$("#specialHint").fadeIn(500);
		}
	}

	function getBoardNumber() {
		var boardNumber;
		$.ajax({
			url: GET_BOARD_NUMBER,
			type: "get",
			data:{},
			dataType: "json",
			async: false,
			success: function(response){
				boardNumber = response.data;
			},
			error: function(){alertError();}
		})
		return boardNumber;
	}

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

	$('body').tooltip(
        {
         selector: "select[rel=tooltip], a[rel=tooltip]"
    });	
});
