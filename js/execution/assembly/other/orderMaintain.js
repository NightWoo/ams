$(document).ready(function() {

	initPage();

	$("#btnQuery").click(function() {
		ajaxQuery();
	})

	$("#btnAdd").click(function() {
		$("#newModal").modal("show");
		if($("#newStandbyDate").val() === ""){
			$("#newStandbyDate").val(tomorrowDate());
		}
	})

	$("#tableResult").live("click", function(e) {
		if ($(e.target).is("i")) {
			if($(e.target).hasClass("icon-thumbs-up")) {
				ajaxTop($(e.target).closest("tr").data("id"));
			} else if($(e.target).hasClass("icon-hand-up")){
				ajaxUp($(e.target).closest("tr").data("id"));
			} else if($(e.target).hasClass("icon-edit")) {
				var siblings = $(e.target).closest("td").siblings();

				$("#editStandbyDate").val($(e.target).closest("tr").data("standbyDate"));
				if(siblings[1].innerHTML === '激活') {
					$("#editStatus").attr("checked", "checked");
				} else {
					$("#editStatus").removeAttr("checked");
				}
				$("#editLane").val(siblings[2].innerHTML);
				$("#editCarrier").val(siblings[3].innerHTML);
				$("#editOrderNumber").val(siblings[4].innerHTML);
				$("#editDistributorName").val(siblings[5].innerHTML);
				$("#editDistributorId").val($(e.target).closest("tr").data("distributorId"));
				$("#editAmount").val(siblings[6].innerHTML);
				$("#editSeries").val(siblings[7].innerHTML);
				$("#editColor").val(siblings[8].innerHTML);
				$("#editOrderConfig").val($(e.target).closest("tr").data("orderConfigId"));
				if (siblings[10].innerHTML === '耐寒') {
					$("#editColdResistant").attr("checked", "checked");
				} else {
					$("#editColdResistant").removeAttr("checked");
				}
				$("#editCarType").val(siblings[11].innerHTML);
				//$("#editCarYear").val(siblings[12].innerHTML);
				$("#editOrderType").val(siblings[12].innerHTML);
				$("#editCity").val(siblings[13].innerHTML);
				$("#editRemark").val(siblings[14].innerHTML);

				$("#editModal").data("id", $(e.target).closest("tr").data("id"));

				$("#editModal").modal("show");
			} else if($(e.target).hasClass("icon-remove")) {
				if(confirm('是否删除本订单？')){
					ajaxDelete($(e.target).closest("tr").data("id"));
				}	
			}
		}
	})

	$("#btnAddConfirm").click(function() {
		ajaxAdd();
		$("#newModal").modal("hide");
		emptyNewModal();
	})

	$("#btnAddMore").click(function() {
		ajaxAdd();
		emptyNewModal;
	})

	$("#btnEditConfirm").click(function() {
		ajaxEdit();
		$("#editModal").modal("hide");
		emptyEditModal();
	})

	function initPage() {
		$("#headAssemblyLi").addClass("active");
		$("#leftOrderMaintainLi").addClass("active");

		$("#newModal").modal("hide");
		$("#editModal").modal("hide");

		$("#standbyDate").val(currentDate());
		ajaxQuery();

		emptyNewModal();
		emptyEditModal();
	}

	function ajaxQuery() {
		$.ajax({
			type: "get",
			dataType: "json",
			url: ORDER_SEARCH,
			data: {
				"standbyDate": $("#standbyDate").val()
			},
			success: function(response) {
				if(response.success) {
					$("#tableResult>tbody").html("");
					$.each(response.data, function (index, value) {
						var tr = $("<tr />");
						var thumbTd = $("<td />");

						if(index !==0){
							thumbTd.html('<a href="#" title="删除"><i class="icon-remove"></i></a>&nbsp;<a href="#" title="编辑"><i class="icon-edit"></i></a>&nbsp;<a href="#" title="置顶"><i class="icon-thumbs-up"></i></a>&nbsp;<a href="#" title="调高一位"><i class="icon-hand-up"></i></a>').appendTo(tr);
						} else {
							thumbTd.html('<a href="#" title="删除"><i class="icon-remove"></i></a>&nbsp;<a href="#" title="编辑"><i class="icon-edit"></i></a>').appendTo(tr);
							thumbTd.appendTo(tr);
						}

						$("<td />").html(value.priority).appendTo(tr);

						if (value.status == "1") {
							$("<td />").html("激活").appendTo(tr);
						} else {
							$("<td />").html("冻结").appendTo(tr);
						}

						$("<td />").html(value.lane).appendTo(tr);
						$("<td />").html(value.carrier).appendTo(tr);
						$("<td />").html(value.order_number).appendTo(tr);
						$("<td />").html(value.distributor_name).appendTo(tr);
						$("<td />").html(value.amount).appendTo(tr);
						$("<td />").html(value.series).appendTo(tr);
						$("<td />").html(value.color).appendTo(tr);
						$("<td />").html(value.order_config_name).appendTo(tr);
						
						if (value.cold_resistant == "1") {
		    				$("<td />").html("耐寒").appendTo(tr);
		    			} else {
		    				$("<td />").html("非耐寒").appendTo(tr);
		    			}

						$("<td />").html(value.car_type).appendTo(tr);
		    			//$("<td />").html(value.car_year).appendTo(tr)
						$("<td />").html(value.order_type).appendTo(tr);
						$("<td />").html(value.city).appendTo(tr);
						$("<td />").html(value.remark).appendTo(tr);

						//var editTd = $("<td />").html(" ¦ ");
		    			//$("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
		    			//$("<button />").addClass("btn-link").html("删除").appendTo(editTd);
		    			//editTd.appendTo(tr);

						tr.data("id", value.id);
						tr.data("distributorId", value.distributor_id);
						tr.data("standbyDate", value.standby_date);
						tr.data("orderConfigId", value.order_config_id);

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

	function ajaxAdd() {
		var isCold = 0;
		if($("#newColdResistant").attr("checked") === "checked")
			isCold = 1;

		var status = 0;
		if($("#newStatus").attr("checked") === "checked")
			status = 1;

		$.ajax({
			type: "get",
			dataType: "json",
			url: ORDER_SAVE,
			data: {
				"id": 0,
				"standbyDate": $("#newStandbyDate").val(),
				"status": status,
				"lane": $("#newLane").val(),
				"carrier": $("#newCarrier").val(),
				"city": $("#newCity").val(),
				"distributorId": $("#newDistributorId").val(),
				"orderNumber": $("#newOrderNumber").val(),
				"amount": $("#newAmount").val(),
				"series": $("#newSeries").val(),
				"carType": $("#newCarType").val(),
				"orderConfig": $("#newOrderConfig").val(),
				"color": $("#newColor").val(),
				"coldResistant": isCold,
				//"carYear": $("#newCarYear").val(),
				"orderType": $("#newOrderType").val(),
				"remark": $("#newRemark").val()
			},
			success: function(response) {
				if(response.success) {
					emptyNewModal();
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function(){alertError();}
		})
	}

	function ajaxEdit() {
		var isCold = 0;
		if($("#editColdResistant").attr("checked") === "checked")
			isCold = 1;

		var status = 0;
		if($("#editStatus").attr("checked") === "checked")
			status = 1;

		$.ajax({
			type: "get",
			dataType: "json",
			url: ORDER_SAVE,
			data: {
				"id": $("#editModal").data("id"),
				"standbyDate": $("#editStandbyDate").val(),
				"status": status,
				"lane": $("#editLane").val(),
				"carrier": $("#editCarrier").val(),
				"city": $("#editCity").val(),
				"distributorId": $("#editDistributorId").val(),
				"orderNumber": $("#editOrderNumber").val(),
				"amount": $("#editAmount").val(),
				"series": $("#editSeries").val(),
				"carType": $("#editCarType").val(),
				"orderConfig": $("#editOrderConfig").val(),
				"color": $("#editColor").val(),
				"coldResistant": isCold,
				//"carYear": $("#editCarYear").val(),
				"orderType": $("#editOrderType").val(),
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

	function emptyEditModal() {
		$("#editStandbyDate").val("");
		$("#editStatus").attr("checked", "checked");
		$("#editLane").val("");
		$("#editCarrier").val("");
		$("#editCity").val("");
		$("#editDistributorId").val("");
		$("#editDistributorName").val("");
		$("#editOrderNumber").val("");
		$("#editAmount").val("");
		$("#editSeries").val("");
		$("#editCarType").val("");
		$("#editOrderConfig").val("");
		$("#editColor").val("");
		$("#editColdResistant").removeAttr("checked");
		//$("#editCarYear").val("");
		$("#editOrderType").val("");
		$("#editRemark").val("");
		$("#editDistributorCode").html("");
	}

	function emptyNewModal() {
		//$("#newStandbyDate").val(tomorrowDate());
		$("#newStatus").attr("checked", "checked");
		$("#newLane").val("");
		$("#newCarrier").val("");
		$("#newCity").val("");
		$("#newDistributorId").val("");
		$("#newDistributorName").val("");
		$("#newOrderNumber").val("");
		$("#newAmount").val("");
		$("#newSeries").val("");
		$("#newCarType").val("");
		$("#newOrderConfig").val("");
		$("#newColor").val("");
		$("#newColdResistant").removeAttr("checked");
		//$("#newCarYear").val("");
		$("#newOrderType").val("");
		$("#newRemark").val("");
		$("#newDistributorCode").html("");
	}

	function currentDate (argument) {
			var now = new Date();
			var year = now.getFullYear();       //年
			var month = now.getMonth() + 1;     //月
			var day = now.getDate();            //日
		   // var hh = now.getHours();            //时
			//var mm = now.getMinutes();          //分
		   
			var clock = year + '-';

			if(month < 10) clock += '0';
			clock += month + '-';

			if(day < 10) clock += '0';
			clock += day + '';

			//clock += "08:00";

			return(clock); 
		}
	function tomorrowDate (argument) {
		//获取系统时间 
		var now = new Date();
		var nowYear = now.getFullYear();
		var nowMonth = now.getMonth();
		var nowDate = now.getDate();
		//处理
		var uom = new Date(nowYear,nowMonth, nowDate);
		uom.setDate(uom.getDate() + 1);//取得系统时间的前一天,重点在这里,负数是前几天,正数是后几天
		var LINT_MM = uom.getMonth();
		LINT_MM++;
		var LSTR_MM = LINT_MM > 10?LINT_MM:("0"+LINT_MM)
		var LINT_DD = uom.getDate();
		var LSTR_DD = LINT_DD > 10?LINT_DD:("0"+LINT_DD)
		//得到最终结果
		uom = uom.getFullYear() + "-" + LSTR_MM + "-" + LSTR_DD; 
		return(uom);  
	}

	function getDistributorId(distributorName) {
		var data;
		$.ajax ({
			url: GET_DISTRIBUTOR_ID,
			type: "get",
			async: false,
			dataType: "json",
			data:{
				"distributorName": distributorName
			},
			success: function(response) {
				if(response.success) {
					data = response.data[0];
				}
			},
			error: function(){alertError();}
		})
		return data;
	}

	//经销商的自动补全
	$("#newDistributorName").typeahead({
	    source: function (input, process) {
	        $.get(GET_DISTRIBUTOR_NAME_LIST, {"distributorName":input}, function (data) {
	        	if(data.data == '') {
	        		$("#newDistributorCode").html("<i class='icon-remove'></i>");
	        	}
	        	return process(data.data);
	        },'json');
	    },
	    updater:function (item) {
			$("#newDistributorId").val(getDistributorId(item).distributor_id);

			if(getDistributorId(item).distributor_id != 0) {
				$("#newDistributorCode").html("<i class='icon-ok'></i>");
			}

			return item;
    	}
	});
	
	$("#editDistributorName").typeahead({
	    source: function (input, process) {
	        $.get(GET_DISTRIBUTOR_NAME_LIST, {"distributorName":input}, function (data) {
	        	if(data.data == '') {
	        		$("#editDistributorCode").html("<i class='icon-remove'></i>");
	        	}
	        	return process(data.data);
	        },'json');
	    },
	    updater:function (item) {
			$("#editDistributorId").val(getDistributorId(item).distributor_id);

			if(getDistributorId(item).distributor_id != 0) {
				$("#editDistributorCode").html("<i class='icon-ok'></i>");
			}
			
			return item;
    	}
	});


});

