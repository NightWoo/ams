$(document).ready(function() {
	initPage();
	
	function initPage() {
		$("#headPlanLi").addClass("active");
		$("#leftConfigMaintainLi").addClass("active");

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
		$("#newCarType").html(fillType($("#newCarSeries").val()));
		$("#newOrderConfig").html(fillOrderConfig($("#newCarSeries").val()));
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
		$("#newModal").modal("show");
		$("#newCarSeries").val($("#carSeries").val());
		$("#newCarType").html(fillType($("#newCarSeries").val()));
		$("#newOrderConfig").html(fillOrderConfig($("#newCarSeries").val()));
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
		if($(e.target).html()==="编辑"){
			emptyEditModal();
			var tr = $(e.target).closest("tr");

			if (tr.data("isDisabled") == "1") {
				$("#editIsDisabled").attr("checked", "checked");
			} else {
				$("#editIsDisabled").removeAttr("checked");
			}

			$("#editCarType").html(fillType(tr.data("car_series")));
			$("#editOrderConfig").html(fillOrderConfig(tr.data("car_series")));
			$("#editCarSeries").val(tr.data("car_series"));
			$("#editCarType").val(tr.data("car_type"));
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
			$("#editTyre").val(tr.data("tyre"));
			$("#editSteering").val(tr.data("assisted_steering"));
			$("#editCertificateNote").val(tr.data("certificate_note"));
			$("#editRemark").val(tr.data("remark"));
			
			$("#editModal").data("id",tr.data("id"));
			$("#editModal").modal("show");
			
		} else if($(e.target).html()==="删除"){
			// ajaxDelete($(e.target).closest("tr").data("id"));
		}	
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
						// var editTd = $("<td />").html(" ¦ ");
						var editTd = $("<td />");
						$("<button />").addClass("btn-link").html("编辑").appendTo(editTd);
						// $("<button />").addClass("btn-link").html("删除").appendTo(editTd);
						editTd.appendTo(tr);
						
						tr.data("isDisabled", value.disabled);
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
				"tyre" : $("#newTyre").val(),
				"assisted_steering" : $("#newSteering").val(),
				"certificate_note" : $("#newCertificateNote").val(),
				"remark": $("#newRemark").val()
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
				"side_glass" : $("#editSideGlass").val(),
				"tyre" : $("#editTyre").val(),
				"assisted_steering" : $("#editSteering").val(),
				"certificate_note" : $("#editCertificateNote").val(),
				"remark" : $("#editRemark").val(),
			},
			success: function (response) {
				if (response.success) {
					$("#editModal").modal("hide");
					ajaxQuery();
					emptyEditModal();
				} else {
					alert(respose.message);
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
					alert(respose.message);
				}	
			},
			error: function () {
				aleatError();
			}
				
		});
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
//changed