$(document).ready(function(e) {
	initPage();
	
	$("#btnQuery").click(function() {
		ajaxQuery();	
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
	
	function initPage() {
		$("#headAssemblyLi").addClass("active");
		$("#leftPauseQueryLi").addClass("active");
		
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
				"pauseType": $("#pauseType").val(),
				"dutyDepartment": $("#dutyDepartment").val(),
				"section": $("#section").val(),	
				"perPage": 10,
				"curPage": targetPage || 1
			},
			success: function(response) {
				if(response.success) {
					$("#tableResult>tbody").html("");
					$.each(response.data.data, function(index, value) {
						var tr = $("<tr />");
						$("<td />").html(value.id).appendTo(tr);
						$("<td />").html(value.pause_type).appendTo(tr);
						$("<td />").html(value.node_name).appendTo(tr);
						$("<td />").html(value.duty_department).appendTo(tr);
						$("<td />").html(value.remark).appendTo(tr);
						$("<td />").addClass("alignRight").html(value.howlong).appendTo(tr);
						$("<td />").html(value.pause_time.substr(0,16)).appendTo(tr);
						if(value.recover_time === "0000-00-00 00:00:00"){
							$("<td />").html("未恢复").appendTo(tr);
						}else{
							$("<td />").html(value.recover_time.substring(0,16)).appendTo(tr);
						}
						$("<td />").html(value.editor_name).appendTo(tr);
						
						tr.data("id",value.id);
						
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