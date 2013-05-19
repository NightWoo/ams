$(document).ready(function () {
	initPage();
	//------------------- common functions -----------------------	
	//initialize this page
	/*
		1.add head class and resetPage
		2.resetPage();
		3.hide alert
	*/
	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftCarQueryLi").addClass("active");
		$("#carTag").hide();
		$("#resultTable").hide();	//add by wujun
	}


	$(window).bind('keydown', enterHandler);
	function enterHandler (event) {
		if(event.keyCode == '13'){
			if($.trim($("#vinText").val()) != ""){
				$("#resultTable tbody").text("");
				ajaxQuery();
			} else if ($.trim($("#serialText").val()) != "" && $("#selectSeries").val() == ""){
				alert("通过流水号查询需选择车系");
			} else if($.trim($("#serialText").val()) != "" || $.trim($("#serialText").val()) != "") {
				$("#resultTable tbody").text("");
				ajaxQuery();
			}
			return false;
		}
	}
	
	$("#btnQuery").click (function () {
		//clear last
		if($.trim($("#vinText").val()) != ""){
			$("#resultTable tbody").text("");
			ajaxQuery();
		} else if ($.trim($("#serialText").val()) != "" && $("#selectSeries").val() == ""){
			alert("通过流水号查询需选择车系");
		} else if($.trim($("#serialText").val()) != "" || $.trim($("#serialText").val()) != ""){
			$("#resultTable tbody").text("");
			ajaxQuery();
		}
		return false;
	});

	$("#btnExport").click(
		function () {
			ajaxExport();
			return false;
		}
	);

	$("#vinText, #serialText").bind("keydown", function (event) {
		if(event.keyCode == '13'){
			if($.trim($("#vinText").val()) != ""){
				$("#resultTable tbody").text("");
				ajaxQuery();
			} else if ($.trim($("#serialText").val()) != "" && $("#selectSeries").val() == ""){
				alert("通过流水号查询需选择车系");
			} else if($.trim($("#serialText").val()) != "" || $.trim($("#serialText").val()) != "") {
				$("#resultTable tbody").text("");
				ajaxQuery();
			}
			return false;
		}	
	});


	function ajaxQuery (argument) {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: SHOW_TRACE,//ref:  /bms/js/service.js
		    data: {
		    	"vin": $('#vinText').val(),
		    	"series": $('#selectSeries').val(),
		    	"serialNumber": $('#serialText').val(),
		    	"node":$("#selectNode").val()
		    },
		    success:function (response) {
		    	if(response.success){
		    		var car = response.data.car;
		    		// $("#vinText").val(car.vin);
		    		$("#vin").html(car.vin);
		    		$('#serialNumber').html(car.serial_number);
		    	 	$('#series').html(car.series);
			    	$('#color').html(car.color);
				    $('#type').html(car.type);
				    $('#configName').html(car.config_name);
				    if(response.data.status && response.data.status !== "")
				    	$('#statusInfo').html(response.data.status);
				    else
				    	$('#statusInfo').text("");
		    		$("#carTag").show();
					$("#resultTable").show();	//add by wujun

		    		$.each(response.data.traces,function (index,value) {
		    			var nodeNameTd = "<td>" + value.node_name + "</td>";
		    			var faultTd = "<td>" + value.fault + "</td>";
		    			var faultStatusTd = "<td>" + value.fault_status + "</td>";
		    			var userNameTd = "<td>" + value.user_name + "</td>";
		    			var memoTd = "<td>" + value.modify_time + "</td>";
		    			var createTimeTd = "<td>" + value.create_time + "</td>";
		    			var tr = "<tr>" + nodeNameTd + faultTd + 
		    				faultStatusTd + userNameTd + createTimeTd + memoTd + "</tr>";
		    			$("#resultTable tbody").append(tr);
		    		});
		    	}else{
		    		// $("#vinText").val("");
		    		alert(response.message);

		    	}
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxExport () {
		window.open(TRACE_EXPORT + "?vin=" + $('#vinText').val() + 
			"&node=" + $("#selectNode").val()
		);
	}
});
