require.config({
	"baseUrl": "/bms/rjs/lib",
	"paths":{
		"jquery": "./jquery-2.0.3.min",
		"bootstrap": "./bootstrap.min",
		"head": "../head",
		"left": "../left",
		"service": "../service",
		"common": "../common",
		"component": "../component"
	},
	"shim": {
		"bootstrap": ["jquery"]
	}
})

require(["head","left","service","common","component","jquery","bootstrap"], function (head,left,service,common,component,$) {
	head.doInit();
	left.doInit();
	initPage();

	$('#vinText').on('keydown', function (event) {
		if (event.keyCode == "13"){
		    if($.trim($("#vinText").val()) != ""){
		        goValidate();
	        }   
		    return false;
		}
	});

	$("#validateVinBtn").click(function () {
		goValidate();
	})

	$("#reset").click(function () {
		resetPage();
		return false;
	});

	$("#faultsTable").on("click", ":radio[name=choseFault]", function (e) {
		if($("#componentsDiv").css("display") == "none"){
			$("#componentsDiv").fadeIn();
		}
		tr = $(e.target).closest("tr").addClass("info").siblings().removeClass("info");
	})

	$("#addComponent").click(function () {
		addComponentTr();
	})

	$("#btnSubmit").click(function () {
		ajaxSubmit();
	})

	// $("#componentsTable").on("focus", "input[name=componentName]" ,function (e) {
	// 	currentComponentFocusIndex = $("#componentsTable>tbody>tr").index($(this).colsest("tr"));
	// })

	$("#componentsTable").on("keydown", "input[name=componentName]", function (e) {
		currentComponentFocusIndex = $("#componentsTable>tbody>tr").index($(e.target).closest("tr"));
		$(e.target).typeahead({
		    source: function (input, process) {
		    	disableTr(currentComponentFocusIndex);
		        $.get(service.GET_COMPONENT_NAME_LIST, {"component":input,"series":$("#vinText").data("series")}, function (data) {
		        	return process(data.data);
		        },'json');
		    },
		    updater:function (item) {
		     	fillComponentCode(item);//根据part的名字查找故障模式
	        	return item;
	    	},
		});
	})

	$("#componentsTable").on("change", "select.componentCode", function (e) {
		tr = $(e.target).closest("tr");
		currentComponentFocusIndex = $("#componentsTable>tbody>tr").index(tr);
		selectCode = tr.find("select").filter(".componentCode")
		fillComponentInfo(selectCode.val());
	})

	$("#componentsTable").on("click", "a.removeTr", function (e) {
		tr = $(e.target).closest("tr");
		tr.remove();
	})

	currentComponentFocusIndex = -1;
	

	function disableTr (index) {
		var tr = $("#componentsTable>tbody>tr").eq(index);
		tr.find("select").filter(".componentCode").html("").attr("disabled", "disabled");
		tr.find("select").filter(".provider").html("").attr("disabled", "disabled");
		tr.find("input").filter(".barCode").val("").attr("disabled", "disabled");
		tr.find("input").filter(".collateralCheck").removeAttr("checked").attr("disabled", "disabled");

		tr.data("componentId", 0);
	}

	function fillComponentCode (componentName) {
		var tr = $("#componentsTable>tbody>tr").eq(currentComponentFocusIndex);
		var series = $("#vinText").data("series");
		selectCode = tr.find("select").filter(".componentCode").html("").removeAttr("disabled");
		options = component.getCodeOptions(componentName, series);
		selectCode.append(options);

		fillComponentInfo(selectCode.val());

	}

	function fillComponentInfo (componentId) {
		componentInfo = component.getInfo(componentId);
		needBarCode = isNeedBarCode(componentId);
		var tr = $("#componentsTable>tbody>tr").eq(currentComponentFocusIndex);
		
		selectProvider = tr.find("select").filter(".provider").html("").removeAttr("disabled");
		$.each(componentInfo.provider, function (providerId, value) {
			$("<option value='" + providerId + "'>" + value.display_name + "</option>").appendTo(selectProvider);
		});
		selectProvider.children().removeAttr("selected");
		if(needBarCode) {
			tr.find("input").filter(".barCode").removeAttr("disabled");
		}
		tr.find("input").filter(".collateralCheck").removeAttr("disabled");

		tr.data("componentId", componentInfo.component_id)
		  .data("simpleCode", componentInfo.simple_code)
		  .data("needBarCode", needBarCode)
		  .data("providerId", selectProvider.val())
		  .data("unitPrice", componentInfo.unit_price)
		  .data("componentName", componentInfo.display_name);
	}

	function packComponent () {
		var dataArray = [];
		var suspended = false;
		$("#componentsTable>tbody>tr").each(function (index, tr) {
			componentNameText = $(tr).find("input").filter(".componentName").val();
			if(!componentNameText) {
				alert("零部件名称存在空值，请确认");
					suspended = true;
					return false;
			}
			if($(tr).data("componentId") && $(tr).data("componentId") != 0 && componentNameText) {
				if(!$(tr).data("providerId") || $(tr).data("providerId") == 0) {
					alert("必须选择供应商，如零部件未维护供应商，请联系AMS零部件清单维护人员进行维护");
					suspended = true;
					return false;
				}

				barCode = $.trim($(tr).find("input").filter(".barCode").val());
				if($(tr).data("needBarCode")) {
					matched = component.validateBarCode(barCode, $(tr).data("simpleCode"))
					if(!matched){
						alert($(tr).data("componentName") + "条码校验失败，请确认");
						suspended = true;
						return false;
					} else {
						checkRet = component.ajaxCheckBarCode($("#vinText").val(), $(tr).data("componentId"), barCode);
						if(!checkRet.success){
							alert(checkRet.message);
							suspended = true;
							return false;
						}
					}
				}
				isCollateral = $(tr).find("input").filter(".collateralCheck").prop( "checked" ) ? 1 : 0;

				$(tr).data("carId", $("#vinText").data("carId"));
				$(tr).data("faultId", $(":radio[name=choseFault]:checked").data("id"));
				$(tr).data("faultComponentName", $(":radio[name=choseFault]:checked").data("component_name"));
				$(tr).data("faultMode", $(":radio[name=choseFault]:checked").data("fault_mode"));
				$(tr).data("dutyDepartmentId", $("#dutySelect").val());
				$(tr).data("barCode", barCode);
				$(tr).data("isCollateral", isCollateral);

				dataArray.push($(tr).data());
			}
		})
		//如果阻止或数据条目为0,返回包裹数据失败
		if(suspended || dataArray.length == 0) return false;

		var dataObj = {};
		for(var i=0;i<dataArray.length;i++) {
			dataObj[i] = dataArray[i];
		}
		var jsonText = JSON.stringify(dataObj);
		return jsonText;
	}

	function ajaxSubmit () {
		repares = packComponent();
		console.log(repares);
		//数据包装完全包裹成功才submit
		if(repares) {
			$.ajax({
				url: service.RELACE_SPARES,
				dataType: "json",
				data:{
					"vin": $("#vinText").val(),
					"spares": repares,
				},
				error: function () {common.alertError();},
				success: function (response) {
					resetPage();
					if(response.success) {
						common.fadeMessageAlert(response.message,"alert-success");
					} else {
						common.fadeMessageAlert(response.message,"alert-error");
					}
				} 
			})
		}
	}

	function isNeedBarCode (componentId) {
		need = false;
		for(i=0;i<configList.length;i++) {
			if(componentId == configList[i]["component_id"]){
				need = true;
				break;
			}
		}
		return need;
	}

	function validateBarCode (componentId, barCode) {
		if(barCode == "") {
			alert("追溯零部件必须填写零部件条码");
			return false;
		}
		vin = $("#vinText").val();
		result = component.validateBarCode(vin, componentId, barCode);
		if(!result.success){
			alert(result.message);
			return false;
		}
	}

	function initPage () {
		$("#divDetail").hide();
		$("#headAssemblyLi").addClass("active");
		$("#leftNodeSelectLi").addClass("active");
		resetPage();
		$("#messageAlert").hide();
	}

	function resetPage () {
		$("#vinText").removeAttr("disabled").val("").focus();
		$("#validateVinBtn").removeAttr("disabled");
		$("#btnSubmit").attr("disabled", "");
		toggleVinHint(true);

		$("#faultsTable>tbody").html("");
		$("#componentsTable>tbody").html("");
		$("#faultsDiv, #componentsDiv").hide();

		options = common.getDutyOptions("SparesStore", true);
		$("#dutySelect").append(options);
		configList = [];
	}

	function toggleVinHint (showVinHint) {
		if(showVinHint){
			$("#carInfo").hide();
			$("#vinHint").fadeIn(1000);

		}else{
			$("#vinHint").hide();
			$("#carInfo").fadeIn(1000);
		}
	}

	function goValidate () {
		if($("#vinText").attr("disabled") == "disabled")
			return false;
		if($.trim($("#vinText").val()) != "") {
		    ajaxValidate();
	    }   
	}

	function addComponentTr () {
		tr = $("<tr />");
		num = $("<td />").html($("#componentsTable>tbody>tr").length + 1);
		remove = $("<td />")
			.append($("<a />")
				.attr("rel", "tooltip").attr("data-toggle", "tooltip").attr("title", "删除此行")
				.addClass('removeTr')
				.html("<i class='icon-trash'></i>"));
		componentName = $("<td />")
			.append($("<input>")
				.attr("type", "text").attr("name", "componentName")
				.addClass("input-medium componentName"));
		componentCode = $("<td />")
			.append($("<select />")
				.attr("disabled", "disabled")
				.addClass("input-medium componentCode"));
		provider = $("<td />")
			.append($("<select />")
				.attr("disabled", "disabled")
				.addClass("input-medium provider"));
		barCode = $("<td />")
			.append($("<input>")
				.attr("type", "text").attr("disabled", "disabled").attr("name", "barCode")
				.addClass("input-medium barCode"));
		collateralCheck = $("<td />")
			.append($("<input>")
				.attr("type", "checkBox").attr("disabled", "disabled").attr("name", "collateralCheck")
				.addClass("collateralCheck"));

		tr.append(remove).append(componentName).append(componentCode).append(provider).append(barCode).append(collateralCheck);
		$("#componentsTable>tbody").prepend(tr);
	}

	function ajaxValidate () {
		$("#faultsTable>tbody").html("");
		$.ajax({
			url: service.SPARES_STORE_VALIDATE,
			dataType: "json",
			data: {
				"vin": $.trim($("#vinText").val()),
			},
			error: function(){common.alertError()},
			success: function(response) {
				if(response.success) {
					configList = response.data.configList;
					var car = response.data.car 
					$("#vinText").val(car.vin).data("series", car.series).data("carId", car.id);
			    	$("#vinText, #validateVinBtn").attr("disabled","disabled");
					$("#btnSubmit").removeAttr("disabled");
			    	toggleVinHint(false);

		    		$('#serialNumberInfo').html(car.serial_number);
		    	 	$('#seriesInfo').html(common.seriesName(car.series));
				    $('#typeInfo').html(car.type_config);
				    $('#coldInfo').html(car.cold);
			    	$('#colorInfo').html(car.color);
				    $('#statusInfo').html(car.status);
				    $('#rowInfo').html(car.row);

				    $.each(response.data.faultArray, function (faultClass, faults) {
				    	$.each(faults, function (index, fault) {
				    		if(fault.fault_mode){
					    		tr = $("<tr />");
					    		$("<td />").html(fault.node_name).appendTo(tr);
					    		$("<td />").html(fault.component_name + fault.fault_mode).appendTo(tr);
					    		$("<td />").html(fault.duty).appendTo(tr);
			    				radio = $("<input />")
			    					.attr("type", "radio").attr("name", "choseFault")
			    					.data("id", fault.id).data("duty_type",fault.duty_type).data("duty_department", fault.duty_department).data("faultClass", faultClass).data("component_name", fault.component_name).data("fault_mode",fault.fault_mode);
			    				$("<td />").append(radio).appendTo(tr);
				    		}

		    				$("#faultsTable>tbody").append(tr);
				    	})
				    });
				    $("#faultsDiv, #faultsTable").show();
				    addComponentTr();
			    } else {
				    resetPage();
					fadeMessageAlert(response.message,"alert-error");
			    }
			}
		})
	}

});
