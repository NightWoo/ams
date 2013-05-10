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
			$("#editStartTime").val(siblings[4].innerHTML);
			$("#editEndTime").val(siblings[5].innerHTML);
			$("#editRemark").val(siblings[2].innerHTML);
			$("#editModal").data("id", thisTr.data("id"));			
			$("#editModal").modal("show");	
		} else if($(e.target).html()==="删除"){
			ajaxDelete($(e.target).closest("tr").data("id"));
		}
	})
	
	$(".prePage").click(function (){
		if(parseInt($(".curPage").attr("page")) > 1){
			$("#tableResult tbody").text("");
			ajaxQuery(parseInt($(".curPage").attr("page")) - 1);
		}
	})

	$(".nextPage").click(function (){
		if(parseInt($(".curPage").attr("page")) * 10 < parseInt($("#totalText").attr("total")) ){
			$("#tableResult tbody").text("");
			ajaxQuery(parseInt($(".curPage").attr("page")) + 1);
		}
	})

	$("#btnAddMore").click (function () {
		ajaxAdd();
		return false;
	});

	$("#btnAddConfirm").click (function () {
		ajaxAdd();
		$('#newModal').modal('hide');
		return false;
	});

	$("#btnEditConfirm").click (function () {
		ajaxEdit();
		return false;
	});

	 $(".datetimepicker").datetimepicker({
	    format: 'yyyy-mm-dd hh:ii:ss',
	    autoclose: true,
		todayBtn: true,
		pickerPosition: "bottom-left",
		language: "zh-CN"
    });

	
	function initPage() {
		$("#headPlanLi").addClass("active");
		$("#leftPlanPauseLi").addClass("active");
		
		$("#startTime").val(currentDate8());
		//$("#endTime").val(currentDate16());
		$("#endTime").val(currentTime());
		
		$("#tableResult").hide();
		$(".pagination").hide();
		
		ajaxQuery();
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
				"orderBy": 'DESC',
			},
			success: function(response) {
				if(response.success) {
					$("#tableResult>tbody").html("");
					$.each(response.data.data, function(index, value) {
						var tr = $("<tr />");
						$("<td />").html(value.id).appendTo(tr);
						 $("<td />").html(value.pause_type).appendTo(tr);
						$("<td />").html(value.remark).appendTo(tr);
						$("<td />").addClass("alignRight").html(value.howlong).appendTo(tr);
						$("<td />").html(value.pause_time).appendTo(tr);
						$("<td />").html(value.recover_time).appendTo(tr);
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
	
	function currentTime (argument) {
		var now = new Date();
	        var year = now.getFullYear();       //年
	        var month = now.getMonth() + 1;     //月
	        var day = now.getDate();            //日
	        var hh = now.getHours();            //时
	        var mm = now.getMinutes();          //分
	       
	        var clock = year + '-';

	        if(month < 10) clock += '0';
	        clock += month + '-';

	        if(day < 10) clock += '0';
	        clock += day + ' ';
			
			if(hh < 10 ) clock += '0'
	        clock += hh + ':'; 
			
			if(mm < 10) clock += '0';
			clock += mm;

	        return(clock); 
	}
	
	function currentDate8 (argument) {
		var now = new Date();
	        var year = now.getFullYear();       //年
	        var month = now.getMonth() + 1;     //月
	        var day = now.getDate();            //日
	        var hh = now.getHours();            //时
	        var mm = now.getMinutes();          //分
	       
	        var clock = year + '-';

	        if(month < 10) clock += '0';
	        clock += month + '-';

	        if(day < 10) clock += '0';
	        clock += day + ' ';

	        clock += "08:00";

	        return(clock); 
	}

	function currentDate16 (argument) {
		var now = new Date();
	        var year = now.getFullYear();       //年
	        var month = now.getMonth() + 1;     //月
	        var day = now.getDate();            //日
	        var hh = now.getHours();            //时
	        var mm = now.getMinutes();          //分
	       
	        var clock = year + '-';

	        if(month < 10) clock += '0';
	        clock += month + '-';

	        if(day < 10) clock += '0';
	        clock += day + ' ';

	        clock += "16:00";

	        return(clock); 
	}

});