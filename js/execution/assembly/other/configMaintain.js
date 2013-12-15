$(document).ready(function() {
	initPage();
	
	function initPage() {
		$("#headGeneralInformationLi").addClass("active");
		$("#leftConfigMaintainLi").addClass("active");
		getSeries();
		$("#btnAdd, #btnQuery").attr("disabled", "disabled");
		$("#newModal").modal("hide");
		$("#editModal").modal("hide");
	}

	$("#carSeries").change(function () {
		emptyNewModal();
		emptyEditModal();
		if($("#carSeries").val() == ''){
			$("#btnAdd, #btnQuery").attr("disabled", "disabled");
		} else {
			$("#btnAdd, #btnQuery").removeAttr("disabled");
		}
		$("#carType").html(fillType($("#carSeries").val()));

	})
	
	$("#newCarSeries").change(function () {
		series = $("#newCarSeries").val();
		$("#newCarType").html(fillType(series));
		$("#newOrderConfig").html(fillOrderConfig(series));
		$(".newOilFilling").html(getOilFilling(series));
		if($("#newCarSeries").val() == "M6"){
			$("#newSideGlass").removeAttr("disabled");
		} else {
			$("#newSideGlass").attr("disabled", "disabled").val("");
		}
	})

	$("#editCarSeries").change(function () {
		$("#editCarType").html(fillType($("#editCarSeries").val()));
		$("#editOrderConfig").html(fillOrderConfig($("#editCarSeries").val()));
		if($("#editCarSeries").val() == "M6"){
			$("#editSideGlass").removeAttr("disabled");
		} else {
			$("#editSideGlass").attr("disabled", "disabled").val("");
		}
	})

	$("#newClime").change(function(){
		if($(this).val() == "国内"){
			$("#newExportCountry").attr("disabled", "disabled").val("");
		} else {
			$("#newExportCountry").removeAttr("disabled", "disabled");
		};
	})

	$("#editClime").change(function(){
		if($(this).val() == "国内"){
			$("#editExportCountry").attr("disabled", "disabled").val("");
		} else {
			$("#editExportCountry").removeAttr("disabled", "disabled");
		};
	})

	$("#btnAdd").click(function (argument){
		emptyNewModal();
		series = $("#carSeries").val();
		$("#newModal").modal("show");
		$("#newCarSeries").val(series);
		$("#newCarType").html(fillType(series));
		$("#newOrderConfig").html(fillOrderConfig(series));
		$(".newOilFilling").html(getOilFilling(series));
		if($("#newCarSeries").val() == "M6"){
			$("#newSideGlass").removeAttr("disabled");
		}
	})
	
	$("#btnQuery").click(function (){
		//clear last
		$("#tableConfig tbody").text("");
		ajaxQuery();
		return false;
	})
	
	$("#btnAddConfirm").click(function (){
		ajaxAdd();
		return false;	
	})
	
	$("#btnEditConfirm").click(function (){
		ajaxEdit();
		return false;	
	})
	
	$("#form").bind("keydown",function(event){
		if(event.keyCode=="13"){
			ajaxQuery();
			return false;	
		}
	})
	
	$("#tableConfig").live("click", function(e) {
		var tr = $(e.target).closest("tr");
		if($(e.target).html()==="编辑"){
			emptyEditModal();

			if (tr.data("isDisabled") == "1") {
				$("#editIsDisabled").attr("checked", "checked");
			} else {
				$("#editIsDisabled").removeAttr("checked");
			}

			$("#editCarType").html(fillType(tr.data("car_series")));
			$("#editOrderConfig").html(fillOrderConfig(tr.data("car_series")));
			$(".editOilFilling").html(getOilFilling(tr.data("car_series")));

			$("#editCarSeries").val(tr.data("car_series"));
			$("#editCarType").val(tr.data("car_type"));
			$("#editOilFillingCold").val(tr.data("oil_filling_id_cold"));
			$("#editOilFillingNormal").val(tr.data("oil_filling_id_normal"));
			$("#editConfigName").val(tr.data("name"));
			$("#editOrderConfig").val(tr.data("order_config_id"));
			$("#editClime").val(tr.data("mark_clime"));
			if($("#editClime").val() != "国内"){
				$("#editExportCountry").removeAttr("disabled");
			}
			$("#editExportCountry").val(tr.data("export_country"));
			if($("#editCarSeries").val() == "M6"){
				$("#editSideGlass").removeAttr("disabled");
			}
			$("#editSideGlass").val(tr.data("side_glass"));
			if(tr.data("aircondition") == 1) {
				$("#editAircondition").attr("checked", "checked");
			} else {
				$("#editAircondition").removeAttr("checked");
			}

			$("#editTyre").val(tr.data("tyre"));
			$("#editSteering").val(tr.data("assisted_steering"));
			$("#editCertificateNote").val(tr.data("certificate_note"));
			$("#editRemark").val(tr.data("remark"));
			
			$("#editModal").data("id",tr.data("id"));
			$("#editModal").modal("show");
			
		} else if($(e.target).html()==="SAP料号"){
			$("#sapEditModal>.modal-header>h3").html(tr.data("name"));
			getSap(tr.data("id"));
			$("#sapEditModal").modal("show");
		}	
	})

	$("#btnSapConfirm").click(function () {
		saveSapAll();
	})
	
	function ajaxQuery (argument) {
		//clear before render
		$("tableConfig tbody").html("");
		$.ajax({
			type:"get",
			dataType:"json",
			url: SEARCH_CONFIG,
			data: {
				"config_name" : $("#configName").val(),
				"car_series" : $("#carSeries").val(),
				"car_type" : $("#carType").val(),
			},
			success : function (response) {
				if (response.success) {
					$("#tableConfig>tbody").text("");
					$.each(response.data,function (index,value){
						var tr = $("<tr />");
						$("<td />").html(value.id).appendTo(tr);
						$("<td />").html(value.car_series).appendTo(tr);
						$("<td />").html(value.car_type).appendTo(tr);
						$("<td />").html(value.name).appendTo(tr);
						$("<td />").html(value.modify_time).appendTo(tr);
						$("<td />").html(value.user_name).appendTo(tr);
						$("<td />").html(value.remark).appendTo(tr);
						var editTd = $("<td />").html(" ¦ ");
						// var editTd = $("<td />");
						$("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
						$("<button />").addClass("btn-link").html("SAP料号").appendTo(editTd);
						editTd.appendTo(tr);
						
						tr.data("isDisabled", value.is_disabled);
						tr.data("aircondition", value.aircondition);
						// tr.data("car_series", value.car_series);
						// tr.data("car_type", value.car_type);
						// tr.data("config_name", value.name);
						// tr.data("remark", value.remark);
						if(value.disabled == "1"){
							tr.addClass("warning");
						}
						$.each(value, function	(key, val) {
							tr.data(key, val);
						})
						
						$("#tableConfig tbody").append(tr);
					})
				}
			},
			error : function() {
				alertError();	
			}
		})
	}
	
	function ajaxAdd (argument) {
		var isDisabled = 0;
		if($("#newIsDisabled").attr("checked") === "checked")
			isDisabled = 1;
		var aircondition = 0;
		if($("#newAircondition").attr("checked") === "checked")
			aircondition = 1;
		$.ajax({
			type: "get",
			dataType: "json",
			url: SAVE_CONFIG,
			data: {
				"isDisabled": isDisabled,
				"car_series": $("#newCarSeries").val(),
				"car_type": $("#newCarType").val(),
				"config_name": $("#newConfigName").val(),
				"order_config_id" : $("#newOrderConfig").val(),
				"mark_clime" : $("#newClime").val(),
				"export_country" : $("#newExportCountry").val(),
				"side_glass" : $("#newSideGlass").val(),
				"oil_filling_id_cold" : $("#newOilFillingCold").val(),
				"oil_filling_id_normal" : $("#newOilFillingNormal").val(),
				"tyre" : $("#newTyre").val(),
				"assisted_steering" : $("#newSteering").val(),
				"certificate_note" : $("#newCertificateNote").val(),
				"remark": $("#newRemark").val(),
				"aircondition": aircondition,
			},
			success: function (response) {
				if (response.success) {
					$("#newModal").modal("hide");
					ajaxQuery();
					emptyNewModal();
				} else {
					alert(response.message);
				}
			},
			error: function () {
				alertError();
			}
		});
	}
	
	function ajaxEdit (argument) {
		var isDisabled = 0;
		if($("#editIsDisabled").attr("checked") === "checked")
			isDisabled = 1;
		var aircondition = 0;
		if($("#editAircondition").attr("checked") === "checked")
			aircondition = 1;
		$.ajax({
			type: "get",
			dataType:"json",
			url: SAVE_CONFIG,
			data: {
				"id" : $("#editModal").data("id"),
				"isDisabled": isDisabled,
				"car_series" : $("#editCarSeries").val(),
				"car_type" : $("#editCarType").val(),
				"config_name" : $("#editConfigName").val(),
				"order_config_id" : $("#editOrderConfig").val(),
				"mark_clime" : $("#editClime").val(),
				"export_country" : $("#editExportCountry").val(),
				"oil_filling_id_cold" : $("#editOilFillingCold").val(),
				"oil_filling_id_normal" : $("#editOilFillingNormal").val(),
				"side_glass" : $("#editSideGlass").val(),
				"tyre" : $("#editTyre").val(),
				"assisted_steering" : $("#editSteering").val(),
				"certificate_note" : $("#editCertificateNote").val(),
				"remark" : $("#editRemark").val(),
				"aircondition": aircondition,
			},
			success: function (response) {
				if (response.success) {
					$("#editModal").modal("hide");
					ajaxQuery();
					emptyEditModal();
				} else {
					alert(response.message);
				}
			},
			error: function () {
				alertError();
			}	
		});
	}
	
	function ajaxDelete (configId) {
		$.ajax({
			type: "get",
			dataType:"json",
			url: DELETE_CONFIG,
			data: {"id" : configId},
			success: function (response) {
				if(response.success){
					ajaxQuery();	
				} else {
					alert(response.message);
				}	
			},
			error: function () {
				alertError();
			}
				
		});
	}

	function getSap (configId) {
		$("#sapEditTable>tbody").html("");
		$.ajax({
			url: GET_CONFIG_SAP,
			type: "get",
			dataType: "json",
			data: {"configId": configId},
			error: function () {alertError();},
			success: function (response) {
				if(response.success) {
					trs = $.templates("#tmplSapTr").render(response.data);
					$("#sapEditTable>tbody").append(trs);
				} else {
					alert(response.message)
				}
			}
		})
	}

	function saveSapAll () {
		var dataArray = [];
		$("#sapEditTable>tbody>tr").each(function (index, tr) {
			$(tr).data("id", $(tr).find("td").filter(".id").html());
			$(tr).data("material_code", $(tr).find("input").filter(".material_code").val());
			$(tr).data("description", $(tr).find("input").filter(".description").val());
			dataArray.push($(tr).data());
		});
		var dataObj = {};
		for(var i=0;i<dataArray.length;i++) {
			dataObj[i] = dataArray[i];
		}
		var jsonText = JSON.stringify(dataObj);

		$.ajax({
			url: SAVE_CONFIG_SAP_ALL,
			type: "get",
			dataType: "json",
			data: {
				"saveData": jsonText
			},
			error: function () {alertError();},
			success: function (response) {
				if(response.success) {
					$("#sapEditModal").modal("hide");
					$("#sapEditTable>tbody").html("");
				} else {
					alert(response.message);
				}
			}
		})
	}
	
	function emptyEditModal (argument) {
		$("#editCarSeries").val("");
		$("#editCarType").val("");
		$("#editConfigName").val("");
		$("#editOrderConfig").val("");
		$("#editClime").val("");
		$("#editExportCountry").val("");
		$("#editSideGlass").attr("disabled", "disabled").val("");
		$("#editTyre").val("");
		$("#editSteering").val("");
		$("#editCertificateNote").val("");
		$("#editRemark").text("");
		$("#editExportCountry").attr("disabled", "disabled").val("");
		$("#editAircondition").removeAttr("checked");	
	}
	
	function emptyNewModal (argument) {
		$("#newCarSeries").val("");
		$("#newtCarType").val("");
		$("#newConfigName").val("");
		$("#newOrderConfig").val("");
		$("#newClime").val("");
		$("#newExportCountry").val("");
		$("#newSideGlass").attr("disabled", "disabled").val("");
		$("#newTyre").val("");
		$("#newSteering").val("");
		$("#newCertificateNote").val("");
		$("#newRemark").text("");
		$("#newExportCountry").attr("disabled", "disabled").val("");
		$("#newRemark").text("");	
		$("#newAircondition").removeAttr("checked");	
	}

});

function fillType(carSeries) {
	ret=""
	$.ajax({
		url: FILL_CAR_TYPE,
		type: "get",
		dataType: "json",
		data: {
			"carSeries" : carSeries	
		},
		async: false,
		success: function(response) {
			if(response.success){
				var option = '<option value="" selected>请选择</option>'
				$.each(response.data, function(index, value){
					option += '<option value="'+ value.car_type +'">'+ value.car_type +'</option>';
				});
			}
			ret = option;
		},
		error: function() { 
	    	alertError(); 
	    }
	})
	return ret;
}

	function fillOrderConfig(carSeries, carType){
		var options = '<option value="0" selected>请选择</option>';
		$.ajax({
			url: FILL_ORDER_CONFIG,
			type: "get",
			dataType: "json",
			data: {
				"carSeries" : carSeries,
				"carType" : carType,	
			},
			async: false,
			success: function(response) {
				if(response.success){						
					$.each(response.data, function(index,value){
						// option +='<option value="' + value.config_id +'">'+ value.config_name +'</option>';	
						options +='<option value="' + value.config_id +'">'+ value.config_name +'</option>';	
					});
				}
			},
			error: function() { 
		    	alertError(); 
		    }
		})
		return options;
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
					$(".carSeries").append(options);
				} else {
					alert(response.message);
				}
			}
		})
	}

	function getOilFilling (series) {
		var otions = ""; 
		$.ajax({
			url: GET_OIL_FILLING,
			dataType: "json",
			data: {
				"series": series
			},
			async: false,
			error: function () {common.alertError();},
			success: function (response) {
				if(response.success){
					optionText = "<option value={{:id}}>{{:name}}【{{:engine_oil_type}}/{{:gear_oil_type}}】</option>"
					options = $.templates(optionText).render(response.data);
				} else {
					alert(response.message);
				}
			}
		})
		return options;
	}
