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
		if (event.keyCode == "13"){
		    //clear last
			$("#resultTable tbody").text("");
			ajaxQuery();
			return false;
		}
	}
	
	$("#btnQuery").click (function () {
		//clear last
		$("#resultTable tbody").text("");
		ajaxQuery();
		return false;
	});

	$("#btnExport").click(
		function () {
			ajaxExport();
			return false;
		}
	);

	$("#vinText").bind("keydown", function (event) {
		if(event.keyCode == '13'){
			if($.trim($("#vinText").val()) != ""){
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
		    data: {"vin": $('#vinText').val(),"node":$("#selectNode").val()},
		    success:function (response) {
		    	if(response.success){
		    		var car = response.data.car;
		    		$("#vinText").val(car.vin);
		    		$('#serialNumber').html(car.serial_number);
		    	 	$('#series').html(car.series);
			    	$('#color').html(car.color);
				    $('#type').html(car.type);
				    if(car.status && car.status !== "0")
				    	$('#statusInfo').html(car.status);
				    else
				    	$('#statusInfo').text("");
		    		$("#carTag").show();
					$("#resultTable").show();	//add by wujun

		    		$.each(response.data.traces,function (index,value) {
		    			var createTimeTd = "<td>" + value.create_time + "</td>";
		    			var nodeNameTd = "<td>" + value.node_name + "</td>";
		    			var faultTd = "<td>" + value.fault + "</td>";
		    			var faultStatusTd = "<td>" + value.fault_status + "</td>";
		    			var userNameTd = "<td>" + value.user_name + "</td>";
		    			var memoTd = "<td>" + value.modify_time + "</td>";
		    			var tr = "<tr>" + createTimeTd + nodeNameTd + faultTd + 
		    				faultStatusTd + userNameTd + memoTd + "</tr>";
		    			$("#resultTable tbody").append(tr);
		    		});
		    	}else{
		    		$("#vinText").val("");
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
