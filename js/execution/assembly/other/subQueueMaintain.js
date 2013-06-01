$(document).ready(function() {

	initPage();

	function initPage() {
		$("#headPlanLi").addClass("active");
		$("#leftSubQueueMaintainLi").addClass("active");

		$("#editModal").modal("hide");

		//set default queue time and get queue
		$("#startTime").val(window.byd.DateUtil.todayBeginTime);
		$("#endTime").val(window.byd.DateUtil.todayEndTime);
	}

	$("#btnQuery").click(function() {
		ajaxQuery();
	});

	$("#btnEditConfirm").click(function () {
		ajaxEdit();
	});

	$("#tableList").live("click", function(e) {
		if ($(e.target).is("button")) {
			var tr = $(e.target).parent("td").parent("tr");
			$("#editModal").data("id", $(tr).data("id"));
			$("#queueTime").val($(tr).data("queueTime"));
			$("#editStatus").val($(tr).data("status"));
			$('#editModal').modal("toggle");
		}
	});

	// $(".datetimepicker").datetimepicker({
	//     format: 'yyyy-mm-dd hh:ii:ss',
	//     autoclose: true,
	// 	todayBtn: true,
	// 	pickerPosition: "bottom-left",
	// 	language: "zh-CN"
 //    });

    $('.datetimepicker').datetimepicker({
		timeFormat: "HH:mm:ss",
		changeMonth: true,
	    changeYear: true,
	    showOtherMonths: true,
	    selectOtherMonths: true,
	    duration: "fast",
	    buttonImageOnly: true,
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
			    		var tr = $("<tr />").data("id", value.id).data("status", value.status).data("queueTime", value.queueTime);

			    		$("<td />").html(value.serial_number).appendTo(tr);
			    		$("<td />").html(value.queueTime.substring(0,16)).appendTo(tr);
			    		$("<td />").html(value.vin).appendTo(tr);
			    		$("<td />").html(value.series).appendTo(tr);
			    		$("<td />").html(value.type_name + '/' + value.config_name).appendTo(tr);
			    		if(value.coldResistant == "1"){
							$("<td />").html('耐寒').appendTo(tr);						
						}else{
							$("<td />").html('非耐寒').appendTo(tr);						
						}
			    		$("<td />").html(value.color).appendTo(tr);
			    		// $("<td />").html(value.year).appendTo(tr);
			    		// $("<td />").html(value.order_type).appendTo(tr);
			    		// $("<td />").html(value.special_order).appendTo(tr);
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
			url: SUB_CONFIG_SAVE,
			data: {
				"id": $("#editModal").data("id"),
				"status" : $("#editStatus").val(),
				"queueTime" : $("#queueTime").val()
			},
			success: function(response) {
				if(response.success) {
					ajaxQuery();
					$('#editModal').modal("toggle");
				} else {
					alert(response.message);
				}
			},
			error: function(){alertError();}
		})
	}

	

	function emptyEditModal() {
		$("#queueTime").val("");
		$("#editStatus").val("");
	}
});

