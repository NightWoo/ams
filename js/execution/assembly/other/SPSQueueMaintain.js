$(document).ready(function() {

	initPage();

	function initPage() {
		$("#headPlanLi").addClass("active");
		$("#leftSpsQueueMaintainLi").addClass("active");

		$("#editModal").modal("hide");

		//set default queue time and get queue
		$("#startTime").val(byd.DateUtil.todayBeginTime() + ":00");
		$("#endTime").val(byd.DateUtil.todayEndTime() + ":00");
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
			$("#editStatus").val($(tr).data("spsStatus"));
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

 //    $('.datetimepicker').datetimepicker({
	// 	timeFormat: "HH:mm:ss",
	// 	changeMonth: true,
	//     changeYear: true,
	//     showOtherMonths: true,
	//     selectOtherMonths: true,
	//     duration: "fast",
	//     buttonImageOnly: true,
	// });

	function ajaxQuery() {
		$.ajax({
			type: "get",
			dataType: "json",
			url:  SPS_QUEUE_QUERY,
			data: {
				"vin" : $('#vinText').val(),
		    	"point" : $("#selectPoint").val(),
		    	"stime" : $("#startTime").val(),
				"etime" : $("#endTime").val(),
				"status" : -1
			},
			success: function(response) {
				if(response.success) {
					$("#tableList tbody").text("");
			    	$(response.data.datas).each(function (index, value) {
			    		var tr = $("<tr />").data("id", value.id).data("spsStatus", value.sps_status).data("queueTime", value.queue_time);

			    		$("<td />").html(value.serial_number).appendTo(tr);
			    		switch(value.sps_status){
			    			case "0" :
					    		spsStatus = "未打印";
					    		break;
					    	case "1" :
					    		spsStatus = "已打印";
					    		break;
				    		case "2" :
					    		spsStatus = "不可打印";
					    		break;
					    	default :
					    		spsStatus = "";

			    		}
			    		$("<td />").html(spsStatus).appendTo(tr);
			    		$("<td />").html(value.queue_time.substring(0,16)).appendTo(tr);
			    		$("<td />").html(value.car_status).appendTo(tr);
			    		$("<td />").html(value.vin).appendTo(tr);
			    		$("<td />").html(byd.SeriesName[value.series]).appendTo(tr);
			    		$("<td />").html(value.type_name + '/' + value.config_name).appendTo(tr);
			    		if(value.coldResistant == "1"){
							$("<td />").html('耐寒').appendTo(tr);						
						}else{
							$("<td />").html('非耐寒').appendTo(tr);						
						}
			    		$("<td />").html(value.color).appendTo(tr);
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
			url: SPS_QUEUE_SAVE,
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

