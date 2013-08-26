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
		$("#leftFaultDutyLi").addClass("active");
		$("#carTag").hide();
		$("#resultTable").hide();
	}


	$(window).bind('keydown', enterHandler);
	function enterHandler (event) {
		if(event.keyCode == '13'){
			if($.trim($("#vinText").val()) != ""){
				ajaxQuery();
			} else if ($.trim($("#serialText").val()) != "" && $("#selectSeries").val() == ""){
				alert("通过流水号查询需选择车系");
			} else if($.trim($("#serialText").val()) != "" || $.trim($("#serialText").val()) != "") {
				ajaxQuery();
			}
			return false;
		}
	}
	
	$("#btnQuery").click (function () {
		//clear last
		if($.trim($("#vinText").val()) != ""){
			ajaxQuery();
		} else if ($.trim($("#serialText").val()) != "" && $("#selectSeries").val() == ""){
			alert("通过流水号查询需选择车系");
		} else if($.trim($("#serialText").val()) != "" || $.trim($("#serialText").val()) != ""){
			ajaxQuery();
		}
		return false;
	});

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

	$("#resultTable").live("click", function(e) {
		if($(e.target).hasClass("faultEdit")){
			$("#editDutyDepartment").html("");
			node = $(e.target).data("duty_type");
			dutyId = $(e.target).data("duty_department");
			$("#editDutyDepartment").append(getDutyList(node)).val(dutyId);

			$("#editModal").data("id", $(e.target).data("id"));
			$("#editModal").data("faultClass", $(e.target).data("faultClass"));
			$("#editModal").modal("show");
		}
	})

	$("#btnEditConfirm").click(function(e) {
		ajaxSaveDuty();
		$("#editModal").modal("hide");
	})


	function ajaxQuery (argument) {
		$("#resultTable tbody").html("");
		$("#resultTable").hide();
		$("#tabTestLine").hide();
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: SHOW_CAR_FAULTS,//ref:  /bms/js/service.js
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
		    	 	$('#series').html(byd.SeriesName[car.series]);
			    	$('#cold').html(car.cold);
			    	$('#color').html(car.color);
				    $('#type').html(car.type);
				    $('#configName').html(car.config_name);
			    	$('#statusInfo').html(car.status);
		    		$("#carTag").show();

		    		$.each(response.data.faultArray,function (faultClass, faults) {
		    			$.each(faults, function (index, fault) {
		    				tr = $("<tr />");
		    				$("<td />").html(fault.node_name).appendTo(tr);
		    				$("<td />").html(fault.component_name + fault.fault_mode).appendTo(tr);
		    				$("<td />").html(fault.status).appendTo(tr);
		    				a = $("<a />").addClass("faultEdit").html(fault.duty).data("id", fault.id).data("duty_type",fault.duty_type).data("duty_department", fault.duty_department).data("faultClass", faultClass);
		    				$("<td />").append(a).appendTo(tr);
		    				$("<td />").html(fault.create_time).appendTo(tr);
		    				$("<td />").html(fault.user_name).appendTo(tr);
			    			$("#resultTable tbody").append(tr);
		    			})
		    		});
		    		$("#resultTable").show();
		    	}else{
		    		// $("#vinText").val("");
		    		alert(response.message);

		    	}
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxSaveDuty() {
		$.ajax({
			url: SAVE_FAULT_DUTY_DEPARTMENT,
			type: "get",
			dataType: "json",
			data: {
				"id": $("#editModal").data("id"),
				"faultClass": $("#editModal").data("faultClass"),
				"duty": $("#editDutyDepartment").val(),
			},
			success: function(response) {
				ajaxQuery();
			},
			error: function(){alertError();}
		})
	}

	function getDutyList(node) {
		options = "<option value='' selected>-请选择责任部门-</option>";
		$.ajax({
			url : QUERY_DUTY_DEPARTMENT,
			dataType: "json",
			data: {"node" : node},
			async: false,
			success : function (response) {
				$.each(response.data, function(index, value) {
					options += "<option value='" + value.id + "'>" + value.name + "</option>";
				});
			},
			error: function() {alertError();}
		})
		return options;
	}
});
