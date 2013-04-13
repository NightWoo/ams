$(document).ready(function() {
	
	initPage();
		
	$("#config").change(function() {						
		if(!($("#config").val()=== "")){						
			$("#btnAdd").removeAttr("disabled");
			$("#btnCopy").removeAttr("disabled");
		}else {
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
			fillType($("#carSeries").val());
			fillConfig($("#carSeries").val());
		} else {
			$("#config, #carType").html('<option value="">请先选择车系</option>');
		}		
	})
	
	$("#carType").change(function() {
		//$("#tableConfigList>tbody").html("");
		//$("#config").html("");
		fillConfig($("#carSeries").val(), $(this).val());
	})
	
	$("#btnQuery").click(function() {
		if(!($("#config").val() === "")){
			ajaxQuery();
		} else {
			alert("必须选择配置")	
		}
	})
	
	$("#btnAdd").click(function() {
		$("#newModal").modal("show");
		$("#newModal h3").html($("#config option:selected").html());
	})
	
	$("#btnCopy").click(function() {
		$("#copyModal").modal("show");
		$("#copyModal h3").html($("#config option:selected").html());
	})
	
	$("#tableConfigList").live("click",function(e) {
		if($(e.target).html()==="编辑"){
			var siblings = $(e.target).parent("td").siblings();
			var thisTr = $(e.target).closest("tr");
			$("#editIsTrace").val(thisTr.data("istrace"));
			$("#editNode").val(thisTr.data("nodeId"));
			$("#editComponentName").val(siblings[1].innerHTML);
			//$("#editComponentCode").html('<option value="'+ thisTr.data("componentId") +'">'+ thisTr.data("componentCode") +'</option>');
			//fill editComponentCode dropdown list with codes of components which have same name
			$("#editComponentCode").html("");
			$("#editComponentCode").html(fillComponentCode(siblings[1].innerHTML));
			$("#editComponentCode").val(thisTr.data("componentId"));

			$("#editProviderName").val(siblings[4].innerHTML);
			$("#editProviderCode").html(thisTr.data("providerCode"));
			$("#editRemark").val(siblings[5].innerHTML);
			
			
			$("#editModal").data("id", thisTr.data("configListId"));
			$("#editModal").data("configId", thisTr.data("configId"));
			
			$("#editProviderId").val(thisTr.data("providerId"));
			$("#editModal").modal("show");
			$("#editModal h3").html($("#config option:selected").html());
			
		}else if($(e.target).html()==="删除"){
			ajaxDelete($(e.target).closest("tr").data("configListId"));
		}
		
	})
		
	$("#btnAddMore").click(function() {
		ajaxAdd();
		emptyNewModal();
	})
	
	$("#btnNewConfirm").click(function() {
		if(ajaxAdd()){
			emptyNewModal();
			$("#newModal").modal("hide");
			ajaxQuery();
		}
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
		if(confirm('请确认是否将\"' + $("#config option:selected").html() + '\"明细复制给\"'+ $("#clonedConfig option:selected").html() +'\"?此操作不可恢复，请谨慎')){
			ajaxCopy();
			$("#copyModal").modal("hide");	
		}
	})
	
	$(".prePage").click(function (){
		if(parseInt($(".curPage").attr("page")) > 1){
			$("#tableConfigList tbody").text("");
			ajaxQuery(parseInt($(".curPage").attr("page")) - 1);
		}
	})

	$(".nextPage").click(function (){
		if(parseInt($(".curPage").attr("page")) * 50 < parseInt($("#totalText").attr("total")) ){
			$("#tableConfigList tbody").text("");
			ajaxQuery(parseInt($(".curPage").attr("page")) + 1);
		}
	})
	
	function initPage() {
		$("#headAssemblyLi").addClass("active");
		$("#leftConfigMaintainLi").addClass("active");
		
		$("#btnAdd").attr("disabled", "disabled");
		$("#btnCopy").attr("disabled", "disabled");
		
		$("#newModal").modal("hide");
		$("#editModal").modal("hide");
		$("#copyModal").modal("hide");
		
		$("#tableConfigList").hide();
		$(".pagination").hide();
		
		// $("#carSeries").val("F0")
		fillType($("#carSeries").val());
		fillConfig($("#carSeries").val());
	}
	
	function ajaxQuery(targetPage) {
		$.ajax({
			url: QUERY_CONFIG_LIST,
			type: "get",
			dataType: "json",
			data: {
				"configId": $("#config").val(),
				"nodeId": $("#node").val(),
				"perPage":50,
				"curPage":targetPage || 1
			},
			success: function(response) {
				if(response.success){
					$("#tableConfigList>tbody").html("");
					$.each(response.data.list, function (index, value){
						var tr = $("<tr />");
						
						//config_list_id, component_name
						$("<td />").html(value.id).appendTo(tr);
						$("<td />").html(value.component_name).appendTo(tr);
						//trace_type
						if(value.istrace === "1"){
							$("<td />").html("单件追溯").appendTo(tr);
						}else if(value.istrace === "2"){
							$("<td />").html("批次追溯").appendTo(tr);
						}else {
							$("<td />").html("不追溯").appendTo(tr);
						}
						
						//node
						if(!(value.node_id === "0")){
							$("<td />").html(value.node_name).appendTo(tr);
						}else {
							$("<td />").html("未指定").appendTo(tr);
						}
						//provier
						if(!(value.provider_id === "0")){
							$("<td />").html(value.provider_name).appendTo(tr);
						} else {
							$("<td />").html("未指定").appendTo(tr);
						}
						
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
						tr.data("configListId", value.id);
						tr.data("componentId", value.component_id);
						tr.data("componentCode", value.component_code);
						tr.data("providerId", value.provider_id);
						tr.data("providerCode", value.provider_code);
						tr.data("istrace", value.istrace);
						tr.data("nodeId", value.node_id);
						tr.data("configId", value.config_id);
						
						//add to tableConfigList
						$("#tableConfigList>tbody").append(tr);
						$("#tableConfigList").show();						
					});
					
					//pager thing
					
		    		if(response.data.pager.curPage == 1) {
		    			//$(".prePage").hide();
						$(".prePage a span").html("&times;");
					} else {
		    			//$(".prePage").show();
						$(".prePage a span").html("&lt;");
					}
		    		if(response.data.pager.curPage * 50 >= response.data.pager.total ) {
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
		    		// $(".curPage").html("第" + response.data.pager.curPage + "页");
					
					$("#tableConfigList").show();
					$(".pagination").show();
				};
			},
			error: function() { 
		    	alertError(); 
		    }
		})
	}
	
	function fillConfig(carSeries, carType){
		$.ajax({
			url: FILL_CONFIG,
			type: "get",
			dataType: "json",
			data: {
				"carSeries" : carSeries,
				"carType" : carType,	
			},
			success: function(response) {
				if(response.success){
					$("#tableConfigList>tbody").html("");
					$("#config").html("");
					var option = '<option value="" selected>请选择配置</option>';	
					$.each(response.data, function(index,value){
						option +='<option value="' + value.config_id +'">'+ value.config_name +'</option>';	
					});
				 	$("#config").html(option);
				 
					// if(typeof(carType) == "undefined") {
						// $("#clonedConfig").html("");
					// } else {
						$("#clonedConfig").html(option); 
					// }	
				}
			},
			error: function() { 
		    	alertError(); 
		    }
		})
	}
	
	function fillType(carSeries) {
		$.ajax({
			url: FILL_CAR_TYPE,
			type: "get",
			dataType: "json",
			data: {
				"carSeries" : carSeries	
			},
			success: function(response) {
				if(response.success){
					$("#tableConfigList>tbody").html("");
					$("#carType").html("");
					var option = '<option value="" selected>请选择车型</option>'
					$.each(response.data, function(index, value){
						option += '<option value="'+ value.car_type +'">'+ value.car_type +'</option>';
					});
					$("#carType").html(option);
				}
			},
			error: function() { 
		    	alertError(); 
		    }
		})
	}
		
	function ajaxAdd() {
		var bol;
		$.ajax({
			url: SAVE_CONFIG_DETAIL,
			async: false,
			type: "get",
			dataType: "json",
			data: {
				"id": 0,
				"configId":  $("#config").val(),
				"istrace": $("#newIsTrace").val(),
				"nodeId": $("#newNode").val(),
				"componentId": $("#newComponentCode").val(),	//option text is componentCode, option value is componentId
				"providerId": $("#newProviderId").val(),	
				"remark": $("#newRemark").val()
			},
			success: function (response) {
				//ajaxQuery();
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
			url: SAVE_CONFIG_DETAIL,
			type: "get",
			dataType: "json",
			data: {				
				"id": $("#editModal").data("id"),
				"configId": $("#editModal").data("configId"),
				"istrace": $("#editIsTrace").val(),
				"nodeId": $("#editNode").val(),
				"componentId": $("#editComponentCode").val(),	//option text is componentCode, option value is componentId
				"providerId": $("#editProviderId").val(),
				"remark": $("#editRemark").val()
			},
			success: function (response) {
				//ajaxQuery();
			},
			error: function() { 
		    	alertError(); 
		    }
		})
	}
	
	function ajaxCopy() {
		$.ajax({
			url: COPY_CONFIG_LIST,
			type: "get",
			dataType: "json",
			data: {
				"originalId": $("#config").val(),
				"clonedId": $("#clonedConfig").val()
			},
			success: function (response) {
				$("#config").val($("#clonedConfig").val());
				ajaxQuery();
			},
			error: function () {
				alertError();
			}	
		})
	}
	
	function ajaxDelete(configListId) {
		$.ajax({
			url: DELETE_CONFIG_DETAIL,
			type: "get",
			dataType: "json",
			data: {
				"id": configListId	
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
	
	function getProviderCode(providerName) {
		var data;
		$.ajax ({
			url: GET_PROVIDER_CODE,
			type: "get",
			async: false,
			dataType: "json",
			data: {
				"providerName": providerName	
			},
			success: function(response) {
				if(response.success){
					data = response.data[0];
				}
			},
			error: function() { 
		    	alertError(); 
		    }
		})
		return data;
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
});