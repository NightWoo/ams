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
		$("#carType").html(fillOrderCarType($("#carSeries").val()));
	})
	
	$("#newCarSeries").change(function () {
		$("#newCarType").html(fillCarType($("#newCarSeries").val()));
	})

	$("#editCarSeries").change(function () {
		$("#editCarType").html(fillOrderCarType($("#editCarSeries").val()));
	})

	$("#btnAdd").click(function (argument){
		$("#newModal").modal("show");
		$("#newCarSeries").val($("#carSeries").val());
		$("#newCarType").html(fillCarType($("#newCarSeries").val()))
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
			//var siblings = $(e.target).parent("td").siblings();
			var tr = $(e.target).closest("tr");
			$("#editCarType").html(fillOrderCarType(tr.data("car_series")));
			$("#editCarSeries").val(tr.data("car_series"));
			$("#editCarType").val(tr.data("car_type"));
			$("#editConfigName").val(tr.data("config_name"));
			$("#editRemark").val(tr.data("remark"));
			
			$("#editModal").data("id",tr.data("id"));
			$("#editModal").modal("show");
			
		} else if($(e.target).html()==="删除"){
			ajaxDelete($(e.target).closest("tr").data("id"));
		}	
	})
	
	function ajaxQuery (argument) {
		//clear before render
		$("tableConfig tbody").html("");
		$.ajax({
			type:"get",
			dataType:"json",
			url: SEARCH_ORDER_CONFIG,
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
						$("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
						$("<button />").addClass("btn-link").html("删除").appendTo(editTd);
						editTd.appendTo(tr);
						
						tr.data("id", value.id);
						tr.data("car_series", value.car_series);
						tr.data("car_type", value.car_type);
						tr.data("config_name", value.name);
						tr.data("remark", value.remark);
						
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
		$.ajax({
			type: "get",
			dataType: "json",
			url: SAVE_ORDER_CONFIG,
			data: {
				"car_series": $("#newCarSeries").val(),
				"car_type": $("#newCarType").val(),
				"config_name": $("#newConfigName").val(),
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
		$.ajax({
			type: "get",
			dataType:"json",
			url: SAVE_ORDER_CONFIG,
			data: {
				"id" : $("#editModal").data("id"),
				"car_series" : $("#editCarSeries").val(),
				"car_type" : $("#editCarType").val(),
				"config_name" : $("#editConfigName").val(),
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
			url: DELETE_ORDER_CONFIG,
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
		$("#editRemark").text("");	
	}
	
	function emptyNewModal (argument) {
		$("#newCarSeries").val("");
		$("#newtCarType").val("");
		$("#newConfigName").val("");
		$("#newRemark").text("");	
	}

});

function fillOrderCarType(carSeries) {
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
					option += '<option value="'+ value.order_type_name +'">'+ value.order_type_name +'</option>';
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

function fillCarType(carSeries) {
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