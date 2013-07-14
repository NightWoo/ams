$(document).ready(function(e) {
	initPage();

	$("#btnQuery").click(function() {
		ajaxQuery();
	})

	$("#btnAdd").click(function() {
		$("#newModal").modal("show");
	})

	$("#btnEditConfirm").click(function() {
		ajaxEdit();
	})

	$("#tableResult").live("click", function(e) {
		if($(e.target).html()==="编辑") {
			var siblings = $(e.target).parent("td").siblings();
			var thisTr = $(e.target).closest("tr");
			$("#editStartTime").val(siblings[3].innerHTML);
			$("#editEndTime").val(siblings[4].innerHTML);
			$("#editRemark").val(siblings[5].innerHTML);
			$("#editModal").data("id", thisTr.data("id"));
			$("#editModal").modal("show");
		} else if($(e.target).html()==="删除"){
			ajaxDelete($(e.target).closest("tr").data("id"));
		}
	})

	$(".prePage").click(function(){
		if(parseInt($(".curPage").attr("page")) > 1){
			$("#tableResult tbody").text("");
			ajaxQuery(parseInt($(".curPage").attr("page")) - 1);
		}
	})

	$(".nextPage").click(function(){
		if(parseInt($(".curPage").attr("page")) * 10 < parseInt($("#totalText").attr("total")) ){
			$("#tableResult tbody").text("");
			ajaxQuery(parseInt($(".curPage").attr("page")) + 1);
		}
	})

	$("#btnAddMore").click (function() {
		ajaxAdd();
		return false;
	});

	$("#btnAddConfirm").click(function() {
		ajaxAdd();
		$('#newModal').modal('hide');
		return false;
	});

	$("#btnEditConfirm").click(function() {
		ajaxEdit();
		return false;
	});

	$("#btnShiftQuery").click(function() {
		if($("#shiftDate").val() == "") {
			alert("查询班次日期不可为空");
		} else {
			ajaxQueryShift();
		}
	})

	$("#tableShiftResult").live("click", function(e) {
		if($(e.target).html()==="编辑") {
			var siblings = $(e.target).parent("td").siblings();
			var thisTr = $(e.target).closest("tr");
			$("#editShiftModal").data("id", thisTr.data("id"));
			$("#editLineSpeed").val(thisTr.data("line_speed"));
			$("#editShiftStartTime").val(thisTr.data("start_time"));
			$("#editShiftEndTime").val(thisTr.data("end_time"));
			$("#editShiftModal").modal("show");
		} else if($(e.target).html()==="删除"){
			if(confirm("是否删除本条班次记录？此操作不可恢复，请谨慎！")){
				ajaxDeleteShift($(e.target).closest("tr").data("id"));
			}
		}
	})

	$("#btnEditShiftConfirm").click(function() {
		ajaxEditShift();
	})

	 // $(".datetimepicker").datetimepicker({
	 //    format: 'yyyy-mm-dd hh:ii:ss',
	 //    autoclose: true,
		// todayBtn: true,
		// pickerPosition: "bottom-left",
		// language: "zh-CN"
  //   });

	 $('.datetimepicker').datetimepicker({
		timeFormat: "HH:mm:ss",
		changeMonth: true,
	    changeYear: true,
	    showOtherMonths: true,
	    selectOtherMonths: true,
	    duration: "fast",
	    buttonImageOnly: true,
	});

	$("#shiftDate").datepicker({
		changeMonth: true,
	    changeYear: true,
	    showOtherMonths: true,
	    selectOtherMonths: true,
	});


	function initPage() {
		$("#headPlanLi").addClass("active");
		$("#leftPlanPauseLi").addClass("active");

		$("#startTime").val(byd.DateUtil.currentDate8() + ":00");
		$("#endTime").val(byd.DateUtil.currentTime() + ":00");
		$("#shiftDate").val(byd.DateUtil.lastWorkDate());

		$("#tableResult, #tableShiftResult").hide();
		$(".pagination").hide();

		// ajaxQuery();
	}

	function ajaxQuery(targetPage) {
		$.ajax({
			type: "get",
			dataType: "json",
			url: QUERY_PAUSE_RECORD,
			data: {
				"startTime": $("#startTime").val(),
				"endTime": $("#endTime").val(),
				"pauseType": '计划停线',
				"perPage": 10,
				"curPage": targetPage || 1,
				"orderBy": 'ORDER BY pause_time DESC',
			},
			success: function(response) {
				if(response.success) {
					$("#tableResult>tbody").html("");
					$.each(response.data.data, function(index, value) {
						var tr = $("<tr />");
						$("<td />").html(value.id).appendTo(tr);
						$("<td />").html(value.pause_type).appendTo(tr);
						$("<td />").addClass("alignRight").html(value.howlong).appendTo(tr);
						$("<td />").html(value.pause_time).appendTo(tr);
						$("<td />").html(value.recover_time).appendTo(tr);
						$("<td />").html(value.remark).appendTo(tr);
						var editTd =$("<td />");
						var editTd = $("<td />").html(" ¦ ");
						$("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
						$("<button />").addClass("btn-link").html("删除").appendTo(editTd);
						editTd.appendTo(tr);

						tr.data("id",value.id);
						tr.data("pause_type", value.pause_type);

						$("#tableResult tbody").append(tr);

						if(response.data.pager.curPage == 1) {
		    			//$(".prePage").hide();
						$(".prePage a span").html("&times;");
					} else {
		    			//$(".prePage").show();
						$(".prePage a span").html("&lt;");
					}
		    		if(response.data.pager.curPage * 10 >= response.data.pager.total ) {
		    			//$(".nextPage").hide();
						$(".nextPage a span").html("&times;");
					} else {
		    			//$(".nextPage").show();
						$(".nextPage a span").html("&gt;");
					}
					$(".curPage").attr("page", response.data.pager.curPage);
					$(".curPage a span").html(response.data.pager.curPage);
					$("#totalText").attr("total", response.data.pager.total);
					$("#totalText").html("导出全部" + response.data.pager.total + "条记录");

					$("#tableResult").show();
					$(".pagination").show();
					});
				}else {
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
			url: PLAN_PAUSE_SAVE,
			type: "get",
			dataType: "json",
			data: {
				"id": $("#editModal").data("id"),
				"startTime" : $("#editStartTime").val(),
				"endTime" : $("#editEndTime").val(),
				"remark" : $("#editRemark").val(),
			},
			success: function(response) {
				if(response.success){
					ajaxQuery();
					emptyEditModal();
					$("#editModal").modal("hide");
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
		$.ajax({
			url: PLAN_PAUSE_SAVE,
			type: "get",
			dataType: "json",
			data: {
				"startTime" : $("#newStartTime").val(),
				"endTime" : $("#newEndTime").val(),
				"remark" : $("#newRemark").val(),
			},
			success: function(response) {
				if(response.success){
					ajaxQuery();
					emptyNewModal();
					//$("newModal").modal("hide");
				} else {
					alert(response.message);
				}
			},
			error: function(){
				alertError();
			}

		})
	}

	function ajaxDelete(pauseId) {
		$.ajax({
			url: PAUSE_DELETE,
			type: "get",
			dataType: "json",
			data: {
				"id" : pauseId
			},
			success: function (response) {
				if(response.success){
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function(){
				alertError();
			}
		})
	}

	function ajaxQueryShift() {
		$.ajax({
			type: "get",
			dataType: "json",
			url: QUERY_SHIFT_RECORD,
			data: {
				"shiftDate": $("#shiftDate").val(),
			},
			success: function(response) {
				shiftName = ['白班', '夜班'];
				if(response.success) {
					$("#tableShiftResult>tbody").html("");
					data = response.data;
					$.each(data, function(index, value) {
						var tr = $("<tr />");
						$("<td />").html(value.id).appendTo(tr);
						$("<td />").html(value.shift_date).appendTo(tr);
						$("<td />").html(shiftName[value.shift]).appendTo(tr);
						$("<td />").html(value.line).appendTo(tr);
						$("<td />").html(value.line_speed + '秒').appendTo(tr);
						$("<td />").html(value.start_time).appendTo(tr);
						$("<td />").html(value.end_time).appendTo(tr);
						var editTd = $("<td />").html(" ¦ ");
						$("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
						$("<button />").addClass("btn-link").html("删除").appendTo(editTd);
						editTd.appendTo(tr);

						tr.data("id",value.id);
						tr.data("line_speed", value.line_speed);
						tr.data("start_time", value.start_time);
						tr.data("end_time", value.end_time);

						$("#tableShiftResult tbody").append(tr);
					});

					$("#tableShiftResult").show();
				}else {
					alert(response.message);
				}
			},
			error: function() {
				alertError();
			}
		})
	}

	function ajaxEditShift() {
		$.ajax({
			url: SHIFT_RECORD_SAVE,
			type: "get",
			dataType: "json",
			data: {
				"id": $("#editShiftModal").data("id"),
				"lineSpeed" : $("#editLineSpeed").val(),
				"startTime" : $("#editShiftStartTime").val(),
				"endTime" : $("#editShiftEndTime").val(),
			},
			success: function(response) {
				if(response.success){
					ajaxQueryShift();
					emptyEditShiftModal();
					$("#editShiftModal").modal("hide");
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alertError();
			}
		})
	}

	function ajaxDeleteShift(shiftId) {
		$.ajax({
			url: SHIFT_RECORD_DELETE,
			type: "get",
			dataType: "json",
			data: {
				"id" : shiftId
			},
			success: function (response) {
				if(response.success){
					ajaxQueryShift();
				} else {
					alert(response.message);
				}
			},
			error: function(){
				alertError();
			}
		})
	}

	function emptyEditModal() {
		$("#editModal").data("id", 0);
		$("#editStartTime").val("");
		$("#editEndTime").val("");
		$("#editRemark").val("");
	}

	function emptyNewModal() {
		$("#newModal").data("id", 0);
		$("#newStartTime").val("");
		$("#newEndTime").val("");
		$("#newRemark").val("");
	}

	function emptyEditShiftModal() {
		$("#editShiftModal").data("id", 0);
		$("#editLineSpeed").val("");
		$("#editShiftStartTime").val("");
		$("#editShiftEndTime").val("");
	}
});