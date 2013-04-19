$("document").ready(function() {
	initPage();
	var orderArray = [];

	$("#btnAdd").click(function() {
		$("#newModal").modal('show');
	})

	$("#newGetOrder").click(function() {
		if($.trim($("#newOrderNumber").val()) != ''){
			ajaxGetOriginalOrders();
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
		orderNumber = $.trim($("#orderNumber").val());
		distributor = $.trim($("#distributor").val());
		if(standbyDate == "" && orderNumber =="" && distributor ==""){
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
		ajaxDetailExist()
	})

	$("#btnAddConfirm").click(function(){
		//ajaxGenerate();
		ajaxDetailExist()
		$("#newModal").modal('hide');
	})

	$("#btnSplitConfirm").click(function(){
		ajaxSplit();
		$("#splitModal").modal('hide');
	})

	$("#tableResult").live("click", function(e) {
		if ($(e.target).is("i")) {
			var siblings = $(e.target).closest("td").siblings();
			var tr = $(e.target).closest("tr");

			if($(e.target).hasClass("icon-thumbs-up")) {
				ajaxTop(tr.data("id"));
			} else if($(e.target).hasClass("icon-hand-up")){
				ajaxUp(tr.data("id"));
			} else if($(e.target).hasClass("icon-remove")) {
				if(confirm('是否删除本订单？')){
					ajaxDelete(tr.data("id"));
				}	
			} else if($(e.target).hasClass("icon-edit")) {
				emptyEditModal();
				carSeries = tr.data("series");
				carType = tr.data("carType");
				$("#editCarType").html("").append(fillType(carSeries));
				$("#editOrderConfig").html("").append(fillOrderConfig(carSeries, carType));
				$("#editColor").html("").append(fillColor(carSeries));

				$("#editStandbyDate").val(tr.data("standbyDate"));
				$("#editStatus").val(tr.data("status"));

				$("#editLane").val(tr.data("laneId"));
				$("#editDistributorName").val(tr.data("distributorName"))
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
			} else if($(e.target).hasClass("icon-resize-full")){
				emptySplitModal();
				$("#splitModal").data("id",tr.data("id"));
				$("#splitModal").modal("show");
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
		ajaxQuery();
		$("#editModal").modal("hide");
		emptyEditModal();
	})
	
	function initPage() {
		// $("#headPlanLi").addClass("active");
		// $("#leftOutStandbyMaintainLi").addClass("active");
		$("#headAssemblyLi").addClass("active");
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
					$("#tableNewOrder").hide();
					$("#tableNewOrder tbody").html("");
					var i=0;
					$.each(response.data, function (index, value){
						var tr = $("<tr />");
						// $("<td />").html(value.order_detail_id).appendTo(tr);
						tdCheck =  "<input class='choose' type='checkbox' checked='checked'>";
						$("<td />").html(tdCheck).appendTo(tr);
						$("<td />").html(value.amount).appendTo(tr);
						$("<td />").html(value.series).appendTo(tr);
						$("<td />").html(value.car_type).appendTo(tr);
						if(value.cold_resistant == '1'){
							$("<td />").html("耐寒").appendTo(tr);
						} else {
							$("<td />").html("非耐寒").appendTo(tr);
						}
						$("<td />").html(value.color).appendTo(tr);
						
						
						configTip = "<select class='input-medium newOrderConfig' rel='tooltip' data-container='#newOrderNumber' data-toggle='tooltip' data-placement='top' title='"+ value.config_description +"' />"
						options = fillOrderConfig(value.series, value.car_type);
						configSelect = $(configTip).addClass("orderConfigSelect").append(options);
						$("<td />").append(configSelect).appendTo(tr);

						inputDate = "<input type='text' id='newStandbyDate"+ index +"' class='input-small newStandbyDate' placeholder='备车日期...' onClick=\"WdatePicker({el:'newStandbyDate"+ index +"',dateFmt:'yyyy-MM-dd'});\"/>";
						// inputDate = "<input type='text' id='newStandbyDate"+ index +" class='input-small newStandbyDate' placeholder='备车日期...'/>";
						$("<td />").html(inputDate).appendTo(tr);

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
						tr.data("amount", value.amount);
						tr.data("orderNature", value.order_nature);
						tr.data("coldResistant", value.cold_resistant);
						tr.data("remark", value.remark);
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
			thisConfig = $(tr).find("select").filter(".newOrderConfig").val();
			thisDate = $(tr).find("input").filter(".newStandbyDate").val();
			console.log(tr);
			console.log(thisConfig);
			$(tr).data("orderConfigId", thisConfig);
			$(tr).data("standbyDate", thisDate);
			chosen = ($(tr).find("input").filter(".choose").attr("checked") === "checked");
			
			console.log(chosen);
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
						confirmMessage = '订单明细：';
						detailIds= response.data.join(",");
						confirmMessage += detailIds;
						confirmMessage += '已经录入过AMS，请确认是否确实需要录入？'
						if(confirm(confirmMessage)) 
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
		distributor = $.trim($("#distributor").val());
		$.ajax({
			type: "get",
			dataType: "json",
			url: ORDER_QUERY,
			data: {
				"standbyDate": $("#standbyDate").val(),
				"orderNumber": orderNumber,
				"distributor": distributor,
				"status": 'all',
			},
			success: function(response) {
				if(response.success) {
					$("#tableResult>tbody").html("");
					$.each(response.data, function (index, value) {
						var tr = $("<tr />");
						var thumbTd = $("<td />");

						if(index !==0 && !(orderNumber != "" || distributor != "")){
							// if(orderNumber != "" || distributor != ""){
							// 	thumbTd.html('<a href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="删除"><i class="icon-remove"></i></a>&nbsp;<a href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="编辑"><i class="icon-edit"></i></a>&nbsp;<a href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="分拆"><i class="icon-resize-full"></i></a>').appendTo(tr);
							// } else {
								thumbTd.html('<a href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="删除"><i class="icon-remove"></i></a>&nbsp;<a href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="编辑"><i class="icon-edit"></i></a>&nbsp;<a href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="分拆"><i class="icon-resize-full"></i></a>&nbsp;<a href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="置顶"><i class="icon-thumbs-up"></i></a>&nbsp;<a href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="调高一位"><i class="icon-hand-up"></i></a>').appendTo(tr);
							// }
						} else {
							thumbTd.html('<a href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="删除"><i class="icon-remove"></i></a>&nbsp;<a href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="编辑"><i class="icon-edit"></i></a>&nbsp;<a href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="分拆"><i class="icon-resize-full"></i></a>&nbsp;<a href="#" rel="tooltip" data-toggle="tooltip" data-placement="top" title="置顶"><i class="icon-thumbs-up"></i></a>').appendTo(tr);
							thumbTd.appendTo(tr);
						}

						$("<td />").html(value.priority).appendTo(tr);

						if (value.status == "1") {
							$("<td />").html("激活").appendTo(tr);
						} else if(value.status == "2") {
							$("<td />").html("关闭").appendTo(tr);
						} else {
							$("<td />").html("冻结").appendTo(tr);
						}
						if(value.lane_name == ""){
							$("<td />").html("-").appendTo(tr);
						} else {
							$("<td />").html(value.lane_name).appendTo(tr);
						}

						$("<td />").html(value.order_number).appendTo(tr);
						$("<td />").html(value.distributor_name).appendTo(tr);
						$("<td />").html(value.amount).appendTo(tr);
						$("<td />").html(value.hold).appendTo(tr);
						$("<td />").html(value.count).appendTo(tr);
						$("<td />").html(value.series).appendTo(tr);
						$("<td />").html(value.car_type_config).appendTo(tr);
						
						if (value.cold_resistant == "1") {
		    				$("<td />").html("耐寒").appendTo(tr);
		    			} else {
		    				$("<td />").html("非耐寒").appendTo(tr);
		    			}
						$("<td />").html(value.color).appendTo(tr);

						// $("<td />").html(value.remark).appendTo(tr);

						tr.data("id", value.id);
						tr.data("orderNumber", value.order_number);
						tr.data("standbyDate", value.standby_date);
						tr.data("priority", value.priority);
						tr.data("status", value.status);
						tr.data("amount", value.amount);
						tr.data("hold", value.hold);
						tr.data("count", value.count);
						tr.data("series", value.series);
						tr.data("carType", value.car_type);
						tr.data("color", value.color);
						tr.data("coldResistant", value.cold_resistant);
						tr.data("orderConfigId", value.order_config_id);
						tr.data("laneId", value.lane_id);
						tr.data("orderNature", value.order_nature);
						tr.data("distributorName", value.distributor_name);
						tr.data("distributorCode", value.distributor_code);
						tr.data("country", value.country);
						tr.data("city", value.city);
						tr.data("carrier", value.carrier);
						tr.data("sellCarType", value.sell_color);
						tr.data("configDescription", value.config_description);
						tr.data("remark", value.remark);

						$("#tableResult>tbody").append(tr);

						$("#tableResult").show();

					});
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
		var isCold = 0;
		if($("#editColdResistant").attr("checked") === "checked")
			isCold = 1;

		$.ajax({
			type: "get",
			dataType: "json",
			url: ORDER_SAVE,
			data: {
				"id": $("#editModal").data("id"),
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
				"remark": $("#editRemark").val()
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

	// function ajaxLaneQuery() {
	// 	$.ajax({
	// 		// url:,
	// 		type: 'get',
	// 		dataType: 'json',
	// 		data: {},
	// 		success: function(response) {

	// 		},
	// 		error: function() {

	// 		}
	// 	})
	// }

	// function ajaxLaneBind(){
	// 	$.ajax({
	// 		// url:,
	// 		type: 'get',
	// 		dataType: 'json',
	// 		data: {},
	// 		success: function(response) {

	// 		},
	// 		error: function() {

	// 		}
	// 	})
	// }

	// function ajaxChangeStatus(){
	// 	$.ajax({
	// 		// url:,
	// 		type: 'get',
	// 		dataType: 'json',
	// 		data: {},
	// 		success: function(response) {

	// 		},
	// 		error: function() {

	// 		}
	// 	})
	// }

	// function ajaxLaneReset(){
	// 	$.ajax({
	// 		// url:,
	// 		type: 'get',
	// 		dataType: 'json',
	// 		data: {},
	// 		success: function(response) {

	// 		},
	// 		error: function() {

	// 		}
	// 	})
	// }

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
			url: FILL_CAR_TYPE,
			type: "get",
			dataType: "json",
			data: {
				"carSeries" : carSeries	
			},
			async: false,
			success: function(response) {
				if(response.success){
					$.each(response.data, function(index, value){
						options += '<option value="'+ value.car_type +'">'+ value.car_type +'</option>';
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
		$("#editLane").val("0"),
		$("#editDistributorName").val(""),
		$("#editAmount").val(""),
		$("#editSeries").val(""),
		$("#editCarType").val(""),
		$("#editOrderConfig").val("0"),
		$("#editColor").val(""),
		$("#editColdResistant").removeAttr("checked");
		$("#editRemark").val("")
	}
	
	function resetNewModal (argument) {
		clearOrderArray()
		$("#newOrderNumber").val("");
		$("#tableNewOrder").hide();
		$("#tableNewOrder tbody").html("");
		toggleOrderInfo(false);
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

	function toggleOrderInfo (showOrderInfo) {
		if(showOrderInfo){
			$("#hint").hide();
			$("#orderInfo").fadeIn(500);

		}else{
			$("#orderInfo").hide();
			$("#hint").fadeIn(500);
		}
	}

	$('body').tooltip(
        {
         selector: "select[rel=tooltip], a[rel=tooltip]"
    });	
});
