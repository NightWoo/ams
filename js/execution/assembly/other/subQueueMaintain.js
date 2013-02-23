$(document).ready(function() {

	initPage();

	function initPage() {
		$("#headAssemblyLi").addClass("active");
		$("#leftSubQueueMaintainLi").addClass("active");

		$("#editModal").modal("hide");

		//set default queue time and get queue
		$("#startTime").val(window.byd.DateUtil.todayBeginTime);
		$("#endTime").val(window.byd.DateUtil.todayEndTime);
	}

	$("#btnQuery").click(function() {
		ajaxQuery();
	})


	$("#tableList").live("click", function(e) {
		if ($(e.target).is("button")) {
			$("#editModal").data("id", $(e.target).parent("td").parent("tr").data("id"));
			$('#editModal').modal("toggle");
		}
	});

	function ajaxQuery() {
		$.ajax({
			type: "get",
			dataType: "json",
			url: SUB_CONFIG_SEARCH,
			data: {
				"vin" : $('#vinText').val(),
		    	"type" : $("#selectSub").val(),
		    	"stime" : $("#startTime").val(),
				"etime" : $("#endTime").val(),
				"status" : -1
			},
			success: function(response) {
				if(response.success) {
					$("#tableList tbody").text("");
			    	$(response.data).each(function (index, value) {
			    		var tr = $("<tr />").data("id", value.id);
			    		$("<td />").html(value.serial_number).appendTo(tr);
			    		$("<td />").html(value.vin).appendTo(tr);
			    		$("<td />").html(value.series).appendTo(tr);
			    		$("<td />").html(value.type + value.config_name).appendTo(tr);
			    		if(value.coldResistant == "1"){
							$("<td />").html('耐寒').appendTo(tr);						
						}else{
							$("<td />").html('非耐寒').appendTo(tr);						
						}
			    		$("<td />").html(value.color).appendTo(tr);
			    		$("<td />").html(value.year).appendTo(tr);
			    		// $("<td />").html(value.order_type).appendTo(tr);
			    		$("<td />").html(value.special_order).appendTo(tr);
			    		$("<td />").html(value.remark).appendTo(tr);

			    		var opTd = $("<td />");
			    		$("<button />").addClass("btn-link").html("编辑").appendTo(opTd);
			    		opTd.appendTo(tr);


			    		tr.appendTo($("#tableList tbody"));

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
		$.ajax({
			type: "get",
			dataType: "json",
			url: ORDER_SAVE,
			data: {
				"id": $("#editModal").data("id"),
				"status" : $("#editStatus").val(),
				"queueTime" : $("#queueTime").val()
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

