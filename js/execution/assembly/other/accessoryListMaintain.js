$(document).ready(function() {
	
	initPage();
		
	$("#orderConfig").change(function() {						
		if(!($("#orderConfig").val() == 0)){						
			$("#btnAdd").removeAttr("disabled");
			$("#btnCopy").removeAttr("disabled");
		} else {
			$("#btnAdd").attr("disabled", "disabled");
			$("#btnCopy").attr("disabled", "disabled");
		}	
	})
	
	$("#carSeries").change(function () {
		emptyNewModal();
		emptyEditModal();
		$("#btnAdd").attr("disabled", "disabled");
		$("#btnCopy").attr("disabled", "disabled");

		if(!($(this).val()==="")){
			$("#carType").html("").append(fillType($(this).val()));
			$("#orderConfig").html("").append(fillOrderConfig($(this).val()));
		} else {
			$("#orderConfig, #carType").html('<option value="">请先选择车系</option>');
		}		
	})
	
	$("#carType").change(function() {
		$("#orderConfig").html("").append(fillOrderConfig($("#carSeries").val(), $(this).val()));
		$("#btnAdd").attr("disabled", "disabled");
		$("#btnCopy").attr("disabled", "disabled");
	})
	
	$("#btnQuery").click(function() {
		if(!($("#orderConfig").val() === "")){
			ajaxQuery();
		} else {
			alert("必须选择配置")	
		}
	})
	
	$("#btnAdd").click(function() {
		$("#newModal").modal("show");
		$("#newModal h3").html($("#orderConfig option:selected").html());
	})
	
	$("#btnCopy").click(function() {
		$("#clonedConfig").html("").append(fillOrderConfig($("#carSeries").val(), $("#carType").val()));
		$("#copyModal").modal("show");
		$("#copyModal h3").html($("#orderConfig option:selected").html());
	})
	
	$("#tableAccessoryList").live("click",function(e) {
		if($(e.target).html()==="编辑"){
			emptyEditModal();
			var siblings = $(e.target).parent("td").siblings();
			var thisTr = $(e.target).closest("tr");
			$("#editComponentName").val(thisTr.data("componentName"));
			$("#editComponentCode").html("");
			$("#editComponentCode").html(fillComponentCode(thisTr.data("componentName")));
			$("#editComponentCode").val(thisTr.data("componentId"));

			$("#editUnit").val(siblings[3].innerHTML);
			$("#editRemark").val(siblings[4].innerHTML);
			
			
			$("#editModal").data("id", thisTr.data("accessoryListId"));
			$("#editModal").data("orderConfigId", thisTr.data("orderConfigId"));
			
			$("#editModal").modal("show");
			$("#editModal h3").html($("#orderConfig option:selected").html());
			
		}else if($(e.target).html()==="删除"){
			if(confirm("是否删除本随车附件？")){
				ajaxDelete($(e.target).closest("tr").data("accessoryListId"));
			}
		}
		
	})
		
	$("#btnAddMore").click(function() {
		configId = $("#orderConfig").val();
		ajaxAdd();
		emptyNewModal();
	})
	
	$("#btnNewConfirm").click(function() {
		configId = $("#orderConfig").val();
		ajaxAdd();
		emptyNewModal();
		$("#newModal").modal("hide");
	})
	
	$("#btnAddClose").click(function() {
		ajaxQuery();	
	})
	
	$("#btnEditConfirm").click(function() {
		ajaxEdit();
		emptyEditModal();
		$("#editModal").modal("hide");
		ajaxQuery();
	})

	$("#btnCopyConfirm").click(function() {
		if(confirm('请确认是否将\"' + $("#orderConfig option:selected").html() + '\"随车附件明细复制给\"'+ $("#clonedConfig option:selected").html() +'\"?此操作不可恢复，请谨慎')){
			ajaxCopy();
			$("#copyModal").modal("hide");	
		}
	})
	
	
	function initPage() {
		$("#headPlanLi").addClass("active");
		$("#leftConfigMaintainLi").addClass("active");
		
		$("#btnAdd").attr("disabled", "disabled");
		$("#btnCopy").attr("disabled", "disabled");
		
		$("#newModal").modal("hide");
		$("#editModal").modal("hide");
		$("#copyModal").modal("hide");
		
		$("#tableAccessoryList").hide();
	}
	
	function ajaxQuery() {
		$.ajax({
			url: QUERY_ACCESSORY_LIST,
			type: "get",
			dataType: "json",
			data: {
				"orderConfigId": $("#orderConfig").val(),
			},
			success: function(response) {
				if(response.success){
					$("#tableAccessoryList>tbody").html("");
					$.each(response.data, function (index, value){
						var tr = $("<tr />");
						
						//config_list_id, component_name
						$("<td />").html(value.id).appendTo(tr);
						$("<td />").html(value.component_code).appendTo(tr);
						$("<td />").html(value.component_name).appendTo(tr);
						$("<td />").html(value.unit).appendTo(tr);
						
						//remark, modify_time, user_name	
						$("<td />").html(value.remark).appendTo(tr);
						$("<td />").html(value.modify_time).appendTo(tr);
						$("<td />").html(value.user_name).appendTo(tr);
						
						//edit
						var editTd = $("<td />").html(" ¦ ");
						$("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
						$("<button />").addClass("btn-link").html("删除").appendTo(editTd);
						editTd.appendTo(tr);
						
						//record data
						tr.data("accessoryListId", value.id);
						tr.data("componentId", value.component_id);
						tr.data("componentName", value.component_name);
						tr.data("componentCode", value.component_code);
						tr.data("orderConfigId", value.order_config_id);
						
						//add to tableConfigList
						$("#tableAccessoryList>tbody").append(tr);
					});
					
					$("#tableAccessoryList").show();
				};
			},
			error: function() { 
		    	alertError(); 
		    }
		})
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
	
	function fillType(carSeries) {
		var options = '<option value="" selected>请选择</option>';
		$.ajax({
			url: FILL_ORDER_CAR_TYPE,
			type: "get",
			dataType: "json",
			data: {
				"carSeries" : carSeries	
			},
			async: false,
			success: function(response) {
				if(response.success){
					$.each(response.data, function(index, value){
						options += '<option value="'+ value +'">'+ value +'</option>';
					});
				}
			},
			error: function() { 
		    	alertError(); 
		    }
		})
		return options;
	}
		
	function ajaxAdd() {
		var bol;
		$.ajax({
			url: SAVE_ACCESSORY_DETAIL,
			async: false,
			type: "get",
			dataType: "json",
			data: {
				"id": 0,
				"orderConfigId":  $("#orderConfig").val(),
				"componentId": $("#newComponentCode").val(),
				"unit": $("#newUnit").val(),
				"remark": $("#newRemark").val()
			},
			success: function (response) {
				ajaxQuery();
				if(!response.success){
					alert(response.message);
				}
				bol = response.success;
			},
			error: function() { 
		    	alertError(); 
		    }
		})
		return bol;
	}
	
	function ajaxEdit() {
		$.ajax({
			url: SAVE_ACCESSORY_DETAIL,
			type: "get",
			dataType: "json",
			data: {				
				"id": $("#editModal").data("id"),
				"orderConfigId": $("#editModal").data("orderConfigId"),
				"componentId": $("#editComponentCode").val(),
				"unit": $("#editUnit").val(),
				"remark": $("#editRemark").val()
			},
			success: function (response) {
				ajaxQuery();
				if(!response.success){
					alert(response.message);
				}
			},
			error: function() { 
		    	alertError(); 
		    }
		})
	}
	
	function ajaxCopy() {
		$.ajax({
			url: COPY_ACCESSORY_LIST,
			type: "get",
			dataType: "json",
			data: {
				"originalId": $("#orderConfig").val(),
				"clonedId": $("#clonedConfig").val()
			},
			success: function (response) {
				$("#orderConfig").val($("#clonedConfig").val());
				ajaxQuery();
			},
			error: function () {
				alertError();
			}	
		})
	}
	
	function ajaxDelete(accessoryListId) {
		$.ajax({
			url: DELETE_ACCESSORY_DETAIL,
			type: "get",
			dataType: "json",
			data: {
				"id": accessoryListId	
			},
			success: function(response) {
				if(response.success) {
					ajaxQuery();	
				}
			},
			error: function() { 
		    	alertError(); 
		    }	
		})
	}

	function emptyNewModal() {
		$("#newIsTrace").val("1"),
		$("#newNode").val(""),
		$("#newComponentName").val(""),
		$("#newComponentCode").html(""),
		$("#newReplacementName").val(""),
		$("#newReplacementCode").html(""),
		$("#newHaveReplacement").removeAttr("checked"),
		$("#newProviderName").val(""),
		$("#newProviderCode").html(""),
		$("#newProviderId").val(""),	
		$("#newRemark").val("")
	}
	
	function emptyEditModal() {
		$("#editIsTrace").val("1"),
		$("#editNode").val(""),
		$("#editComponentName").val(""),
		$("#editComponentCode").html(""),
		$("#editReplacementName").val("").attr("disabled", "disabled"),
		$("#editReplacementCode").html("").attr("disabled", "disabled"),
		$("#editHaveReplacement").removeAttr("checked"),
		$("#editProviderName").val(""),
		$("#editProviderCode").html(""),
		$("#editProviderId").val(""),	
		$("#editRemark").val("")
	}
	
	
	function fillComponentCode(componentName) {
		var option;
		$.ajax({
			url: GET_COMPONENT_CODE,
			type: "get",
			async: false, 
			dataType: "json",
			data: {
				"series" : $("#carSeries").val(),
				"componentName": componentName	
			},
			success: function(response) {
				if(response.success){
					//$("#newComponentCode").html("");					
					$.each(response.data, function(index, value){
						if(value.simple_code === ""){
							option += '<option value="'+ value.component_id +'">'+ value.component_code + '</option>';
						} else {
							option += '<option value="'+ value.component_id +'">'+ value.component_code + ' &lt;' + value.simple_code + '&gt;</option>';							
						}
					})
					//$("#newComponentCode").html(option);
					//$("#newComponentCode>option:first-child").select();					
				}				
			},
			error: function() { 
		    	alertError(); 
		    }
		})
		return option;
	}	
	
	//供应商的自动补全
	$("#newProviderName").typeahead({
	    source: function (input, process) {
	        $.get(GET_PROVIDER_NAME_LIST, {"providerName":input}, function (data) {
	        	return process(data.data);
	        },'json');
	    },
	    updater:function (item) {
			$("#newProviderCode").html(getProviderCode(item).provider_code);
			$("#newProviderId").val(getProviderCode(item).provider_id);
			return item;
    	}
	});
	
	$("#editProviderName").typeahead({
	    source: function (input, process) {
	        $.get(GET_PROVIDER_NAME_LIST, {"providerName":input}, function (data) {
	        	return process(data.data);
	        },'json');
	    },
	    updater:function (item) {
			$("#editProviderCode").html(getProviderCode(item).provider_code);
			$("#editProviderId").val(getProviderCode(item).provider_id);
			return item;
    	}
	});
	
	//零部件的自动补全
	$("#newComponentName").typeahead({
	    source: function (input, process) {
	        $.get(GET_COMPONENT_NAME_LIST, {"component":input, "series":$("#carSeries").val()}, function (data) {
	        	return process(data.data);
	        },'json');
	    },
	    updater:function (item) {
			$("#newComponentCode").html("");
			$("#newComponentCode").html(fillComponentCode(item));
			$("#newComponentCode>option:first-child").select();
			return item;			
    	}
	});

	$("#newReplacementName").typeahead({
	    source: function (input, process) {
	        $.get(GET_COMPONENT_NAME_LIST, {"component":input, "series":$("#carSeries").val()}, function (data) {
	        	return process(data.data);
	        },'json');
	    },
	    updater:function (item) {
			$("#newReplacementCode").html("");
			$("#newReplacementCode").html(fillComponentCode(item));
			$("#newReplacementCode>option:first-child").select();
			return item;			
    	}
	});
	
	$("#editComponentName").typeahead({
	    source: function (input, process) {
	        $.get(GET_COMPONENT_NAME_LIST, {"component":input, "series":$("#carSeries").val() }, function (data) {
	        	return process(data.data);
	        },'json');
	    },
	    updater:function (item) {
			$("#editComponentCode").html("");
			$("#editComponentCode").html(fillComponentCode(item));
			$("#editComponentCode>option:first-child").select();
			return item;			
    	}
	});

	$("#editReplacementName").typeahead({
	    source: function (input, process) {
	        $.get(GET_COMPONENT_NAME_LIST, {"component":input, "series":$("#carSeries").val()}, function (data) {
	        	return process(data.data);
	        },'json');
	    },
	    updater:function (item) {
			$("#editReplacementCode").html("");
			$("#editReplacementCode").html(fillComponentCode(item));
			$("#editReplacementCode>option:first-child").select();
			return item;			
    	}
	});
});