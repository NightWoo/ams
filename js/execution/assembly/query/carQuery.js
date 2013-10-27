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
		getSeries();
		$("#carTag").hide();
		$("#resultTable").hide();
		$("#tabTestLine").hide();
	}


	$(window).bind('keydown', enterHandler);
	function enterHandler (event) {
		if(event.keyCode == '13'){
			if($.trim($("#vinText").val()) != ""){
				goQuery()
			} else if ($.trim($("#serialText").val()) != "" && $("#selectSeries").val() == ""){
				alert("通过流水号查询需选择车系");
			} else if($.trim($("#serialText").val()) != "" || $.trim($("#serialText").val()) != "") {
				goQuery();
			}
			return false;
		}
	}

	function goQuery() {
		if($("#selectNode").val() != "CHECK_LINE"){
			ajaxQuery();
		} else {
			ajaxQueryTestlineRecord();
		}
	}
	
	$("#btnQuery").click (function () {
		//clear last
		if($.trim($("#vinText").val()) != ""){
			goQuery();
		} else if ($.trim($("#serialText").val()) != "" && $("#selectSeries").val() == ""){
			alert("通过流水号查询需选择车系");
		} else if($.trim($("#serialText").val()) != "" || $.trim($("#serialText").val()) != ""){
			goQuery();
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

	$("#resultTable").on("click", ".spares", function (e) {
		tr = $(e.target).closest("tr");
		$("#sparesModal .faultText").html("-" + tr.data("fault")+"-换件");
		ajaxQuerySpares(tr.data("traceId"));
	})


	function ajaxQuery (argument) {
		$("#resultTable tbody").html("");
		$("#resultTable").hide();
		$("#tabTestLine").hide();
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
		    		$("#vin, .vinText").html(car.vin);
		    		$('#serialNumber').html(car.serial_number);
		    	 	$('#series').html(car.series);
			    	$('#color').html(car.color);
				    $('#type').html(car.type);
				    configCold = car.config_name == "" ? "" : car.config_name + "/" + car.cold;
				    $('#configName').html(configCold);
				    row = car.row == "" ? "" : "-" + car.row;
				    lane = car.lane == "" ? "" : "-" + car.lane;
				    distributor = car.distributor_name == "" ? "" : "-" + car.distributor_name 
			    	$('#statusInfo').html(car.status + row + lane + distributor);
		    		$("#carTag").show();
					$("#resultTable").show();	//add by wujun

		    		$.each(response.data.traces,function (index,value) {
		    			tr = $("<tr />");
		    			$("<td />").html(value.node_name).appendTo(tr);
		    			$("<td />").html(value.fault).appendTo(tr);
		    			if(value.fault_status == "换件") {
		    				faultStatus = $("<a />").addClass("spares").html("<i class='icon-list'></i>&nbsp;" + value.fault_status);
		    			} else {
		    				faultStatus = value.fault_status;
		    			}
		    			$("<td />").html(faultStatus).appendTo(tr);
		    			$("<td />").html(value.user_name).appendTo(tr);
		    			$("<td />").html(value.create_time).appendTo(tr);
		    			$("<td />").html(value.modify_time).appendTo(tr);

		    			tr.data("traceId", value.id);
		    			tr.data("fault", value.fault);
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

	function ajaxQueryTestlineRecord() {
		$("#resultTable").hide();
		$("#tabTestLine").hide();
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: QUERY_TESTLINE_RECORD,//ref:  /bms/js/service.js
		    data: {
		    	"vin": $('#vinText').val(),
		    	"series": $('#selectSeries').val(),
		    	"serialNumber": $('#serialText').val()
		    },
		    success:function (response) {
		    	if(response.success){
		    		var car = response.data.car;
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

		    		$.each(response.data.record, function (index,value) {
		    			$("." + index).html(value);
		    			if(value == "T"){
		    				$("." + index).closest("td").removeClass("text-error").addClass("text-success");
		    			} else if (value == "F") {
		    				$("." + index).closest("td").removeClass("text-success").addClass("text-error");
		    			}
		    		});

					$("#tabTestLine").show();
		    	}else{
		    		alert(response.message);

		    	}
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxQuerySpares(traceId) {
		$("#sparesDetail>tbody").html("");
		$.ajax({
			url: QUERY_SPARES_TRACE,
			type: "get",
			dataType: "json",
			data:{
				"traceId": traceId
			},
			error: function () {alertError();},
			success: function (response) {
				if(response.success) {
					$.each(response.data, function (index, value) {
						tr = $("<tr />");
						isCollateral = value.is_collateral == "1" ? "是" : "否";
						$("<td />").html(isCollateral).appendTo(tr);
						$("<td />").html(value.component_code).appendTo(tr);
						$("<td />").html(value.component_name).appendTo(tr);
						$("<td />").html(value.bar_code).appendTo(tr);
						$("<td />").html(value.provider_name).appendTo(tr);
						$("#sparesDetail>tbody").append(tr);
					});
					$("#sparesModal").modal("show");
				} else {
					alert(response.message);
				}
			}
		})
	}

	function getSeries () {
		$.ajax({
			url: GET_SERIES_LIST,
			dataType: "json",
			data: {},
			async: false,
			error: function () {common.alertError();},
			success: function (response) {
				if(response.success){
					options = $.templates("#tmplSeriesSelect").render(response.data);
					$("#selectSeries").append(options);
				} else {
					alert(response.message);
				}
			}
		})
	}
});
