$(document).ready( function () {
	initPage();

	function initPage () {
		$("#headGeneralInformationLi").addClass("active");
		$("#leftComponentMaintainLi").addClass("active");
		
		$('#editModal').modal({
			"show" : false
		});
		$('#newModal').modal({
			"show" : false
		});
		$(".pagination").hide();

	}

	function ajaxQuery (series, name, code, cate, isFault, pageNumber) {


		$.ajax({
			url : QUERY_COMPONENT_LIST,
			type : "GET",
			dataType: "json",
			data : {"series" : series, "component" : name, "code" : code, "category" : cate,
					"isfault" : isFault,
					"perPage":20,
					"curPage": pageNumber || 1},
			success : function (response) {
				if (response.success) {
					$("#tableComponent>tbody").text("");
					$.each(response.data.list,function (index,value) {
		    			var tr = $("<tr />");
		    			if(value.car_series == '6B'){
			    			$("<td />").html('思锐').appendTo(tr);
		    			}else{
			    			$("<td />").html(value.car_series).appendTo(tr);
		    			}
		    			$("<td />").html(value.category).appendTo(tr);
		    			$("<td />").html(value.code).appendTo(tr);
		    			$("<td />").html(value.display_name).appendTo(tr);		//modifid by wujun
		    			$("<td />").html(value.simple_code).appendTo(tr);
		    			if (value.is_fault == "1") {
		    				$("<td />").html("是").appendTo(tr);
		    			} else {
		    				$("<td />").html("否").appendTo(tr);
		    			}
		    			providerTd = $("<td />").appendTo(tr);
		    			providerNames = [];
		    			for(i=1;i<=3;i++){
		    				if(value["provider_display_name_"+i] != ""){
		    					providerNames.push(value["provider_display_name_"+i]);
		    				}
		    			}
		    			providerText = providerNames.join("/");
		    			providerText = providerText == "" ? "<i class='fa fa-plus'></i>添加" : "<i class='fa fa-edit'></i>" + providerText;
		    			$("<a />").addClass("editProvider").html( providerText).appendTo(providerTd);
		    			$("<td />").html(value.unit_price).appendTo(tr);
		    			$("<td />").html(value.remark).appendTo(tr);
		    			var editTd = $("<td />").html(" ¦ ");
		    			$("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
		    			$("<button />").addClass("btn-link").html("删除").appendTo(editTd);
		    			editTd.appendTo(tr);

		    			//record id and name
		    			tr.data("carSeries", value.car_series);
		    			tr.data("componentId", value.id);
		    			tr.data("categoryId", value.category_id);
		    			tr.data("sapCode", value.sap_code);
		    			tr.data("componentCode", value.code);
		    			tr.data("componentName", value.name);
						tr.data("name",value.name);
						tr.data("unitPrice",value.unit_price);
						for(i=1;i<=3;i++){
							tr.data("provider_" + i, value["provider_" + i]);	
							tr.data("provider_display_name_" + i,value["provider_display_name_" + i]);	
							tr.data("provider_name_" + i, value["provider_name_" + i]);	
							tr.data("provider_code_" + i, value["provider_code_" + i]);	
		    			}	
		    			$("#tableComponent tbody").append(tr);


		    		});

					//pager thing
					$(".pagination").show();
		    		if(response.data.pager.curPage == 1)
		    			$(".prePage").hide();
		    		else
		    			$(".prePage").show();
		    		if(response.data.pager.curPage * 20 >= response.data.pager.total )
		    			$(".nextPage").hide();
		    		else
		    			$(".nextPage").show();
		    		$(".curPage").attr("page", response.data.pager.curPage);
		    		$("#curPage").html(response.data.pager.curPage);
				}
				
			},
			error : function (response) {
				alert(response.message);
			}
		});
	}

	function ajaxEdit (compId) {
		var isFault = 0;
		if($("#checkboxIsFault").attr("checked") === "checked")
			isFault = 1;
		$.ajax({
			url : SAVE_COMPONENT,
			type : "GET",
			dataType: "json",
			data : {"id" : $("#editModal").data("componentId"), 
					"series" : $("#inputSeries").attr("value"), 
					"name" : $("#inputName").attr("value"), 	
					"displayName": function(){												//added by wujun
										if($("#inputDisplayName").attr("value")===""){		//added by wujun
											return $("#inputName").attr("value");			//added by wujun
										}else{												//added by wujun
											return $("#inputDisplayName").attr("value");	//added by wujun
										}													//added by wujun
									},														//added by wujun
					"code" : $("#inputCode").attr("value"), 
					"sapCode" : $("#inputSapCode").attr("value"), 
					"category" : $("#inputCate").val(),
					"isfault" : isFault,
					"simpleCode" : $("#inputSimpleCode").attr("value"),
					"unitPrice" : $("#editUnitPrice").attr("value"),
					"remark" : $("#inputComment").attr("value")},
			success : function (response) {
				if(response.success) {
					$("#editModal").modal("hide");
					goQuery();
				} else {
					alert(response.message);
				}
			},
			error : function () {
				alertError();
			}
		});
	}

	function ajaxNew () {
		var isFault = 0;
		if($("#newIsFault").attr("checked") === "checked")
			isFault = 1;
		$.ajax({
			url : SAVE_COMPONENT,
			type : "GET",
			dataType: "json",
			data : {"id" : 0, 
					"series" : $("#newSeries").attr("value"), 
					"name" : $("#newName").attr("value"), 
					"displayName": function(){												//added by wujun
										if($("#newDisplayName").attr("value")===""){		//added by wujun
											return $("#newName").attr("value");				//added by wujun
										}else{												//added by wujun
											return $("#newDisplayName").attr("value");		//added by wujun
										}													//added by wujun
									},														//added by wujun
					"code" : $("#newCode").attr("value"),
					"sapCode" : $("#newSapCode").attr("value"), 
					"category" : $("#newCate").val(),
					"isfault" : isFault,
					"simpleCode" : $("#newSimpleCode").attr("value"),	
					"unitPrice" : $("#newUnitPrice").attr("value"),
					"remark" : $("#newComment").attr("value")},
			success : function (response) {
				$("#newSeries").val(0);
				$("#newCate").val(0);
				$("#newCode").attr("value", "");
				$("#newSapCode").attr("value", "");
				$("#newName").attr("value", "");
				$("#newIsFault").removeAttr("checked");
				$("#newSimpleCode").attr("value", "");
				$("#newComment").attr("value", "");
				$("#newModal").modal("hide");
				goQuery();
			},
			error : function (response) {
				alert(response.message);
			}
		});
	}

	function ajaxEditProvider(componentId) {
		$.ajax({
			url: SAVE_COMPONENT_PROVIDER,
			type: "get",
			dataType: "json",
			data:{
				"componentId": componentId,
				"providerId1" : $("#editProviderId1").val(),
				"providerId2" : $("#editProviderId2").val(),
				"providerId3" : $("#editProviderId3").val(),
			},
			error: function() {alertError();},
			success: function(response) {
				if(response.success) {
					goQuery();
					$("#editProviderModal").modal("hide");
					emptyEditProviderModal();
				} else {
					alert(response.message);
				}
			}
		})
	}

	function ajaxDelete (deleteId) {
		$.ajax({
			url : REMOVE_COMPONENT,
			type : "GET",
			dataType: "json",
			data : {"id" : deleteId},
			success : function (response) {
				alert(response.message);
				goQuery();
			},
			error : function (response) {
				alert(response.message);
			}
		});
	}

	$(window).bind('keydown', enterHandler);
	function enterHandler (event) {
		if (event.keyCode == "13"){
			goQuery();
			return false;
		}
	}

	function goQuery() {
		if ($("#liF0").hasClass("active")) {
				var isFault = Number($("#isFaultF0").attr("checked") === "checked");
				ajaxQuery("F0", $("#inputNameF0").val(), $("#inputCodeF0").val(), $("#selectCategoryF0").val(), isFault ,parseInt($(".curPage").attr("page")));
			} else if($("#liM6").hasClass("active")) {
				var isFault = Number($("#isFaultM6").attr("checked") === "checked");
				ajaxQuery("M6", $("#inputNameM6").val(), $("#inputCodeM6").val(), $("#selectCategoryM6").val(), isFault, parseInt($(".curPage").attr("page")));
			} else if($("#li6B").hasClass("active")){
				var isFault = Number($("#isFault6B").attr("checked") === "checked");
				ajaxQuery("6B", $("#inputName6B").val(), $("#inputCode6B").val(), $("#selectCategory6B").val(), isFault, parseInt($(".curPage").attr("page")));
			} else if($("#liG6").hasClass("active")){
				var isFault = Number($("#isFaultG6").attr("checked") === "checked");
				ajaxQuery("G6", $("#inputNameG6").val(), $("#inputCodeG6").val(), $("#selectCategoryG6").val(), isFault, parseInt($(".curPage").attr("page")));
			}
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

	function emptyEditProviderModal() {
		$("#editProviderModal").data("componentId", "0");
		for(i=1;i<=3;i++){
			$("#editProviderName" + i).val("");
			$("#editProviderCode" + i).val("");
			$("#editProviderId" + i).val("0");
		}
	}

	$("#btnQueryF0").click(function () {
		var isFault = Number($("#isFaultF0").attr("checked") === "checked");
		ajaxQuery("F0", $("#inputNameF0").val(), $("#inputCodeF0").val(), $("#selectCategoryF0").val(), isFault);
		return false;
	});

	$("#btnQueryM6").click(function () {
		var isFault = Number($("#isFaultM6").attr("checked") === "checked");
		ajaxQuery("M6", $("#inputNameM6").val(), $("#inputCodeM6").val(), $("#selectCategoryM6").val(),
			isFault);
		return false;
	});

	$("#btnQuery6B").click(function () {
		var isFault = Number($("#isFault6B").attr("checked") === "checked");
		ajaxQuery("6B", $("#inputName6B").val(), $("#inputCode6B").val(), $("#selectCategory6B").val(),
			isFault);
		return false;
	});

	$("#btnQueryG6").click(function () {
		var isFault = Number($("#isFaultG6").attr("checked") === "checked");
		ajaxQuery("G6", $("#inputNameG6").val(), $("#inputCodeG6").val(), $("#selectCategoryG6").val(),
			isFault);
		return false;
	});

	$("#tableComponent").click(function (e) {
		if($(e.target).is("button")) {
			if ($(e.target).html() === "编辑") {

				$('#editModal').modal("toggle");

				var siblings = $(e.target).parent("td").siblings();
				var tr = $(e.target).closest("tr");
				$("#inputSeries").val(tr.data("carSeries"));
				$("#inputCate").val(tr.data("categoryId"));
				$("#inputSapCode").val(tr.data("sapCode"));
				$("#inputCode").attr("value",siblings[2].innerHTML);
				$("#inputDisplayName").attr("value",siblings[3].innerHTML);		
				$("#inputSimpleCode").attr("value",siblings[4].innerHTML);
				if (siblings[5].innerHTML === '是') {
					$("#checkboxIsFault").attr("checked", "checked");
				} else {
					$("#checkboxIsFault").removeAttr("checked");
				}
				$("#editUnitPrice").val(tr.data("unitPrice"));
				$("#inputComment").attr("value",siblings[8].innerHTML);

				$("#editModal").data("componentId", tr.data("componentId"));
				$("#inputName").attr("value", tr.data("name"));	
			} else {
				ajaxDelete(tr.data("componentId"));
			}
		}

		if($(e.target).is("a")){
			if($(e.target).hasClass("editProvider")){
				var tr = $(e.target).closest("tr");
				for(i=1;i<=3;i++){
					$("#editProviderName" + i).val(tr.data("provider_display_name_" + i));
					$("#editProviderId" + i).val(tr.data("provider_" + i));
    				providerCode = tr.data("provider_" + i) == "0" ? "" : "<i class='fa fa-check'></i>[" + tr.data("provider_code_" + i) + "]";
    				$("#editProviderCode" + i).html(providerCode);
    			}
    			$("#editProviderModal").data("componentId", tr.data("componentId"));
    			$("#editProviderModal .modal-header h4").html("[" +tr.data("componentCode") + "]" + tr.data("componentName") );
				$("#editProviderModal").modal("show");
			}
		}
	});

	$("#btnAddF0").click(function () {
		$('#newSeries').attr("value", "F0");
		$('#newModal').modal("toggle");
	});

	$("#btnAddM6").click(function () {
		$('#newSeries').attr("value", "M6");
		$('#newModal').modal("toggle");
	});

	$("#btnAdd6B").click(function () {
		$('#newSeries').attr("value", "6B");
		$('#newModal').modal("toggle");
	});

	$("#btnAddG6").click(function () {
		$('#newSeries').attr("value", "G6");
		$('#newModal').modal("toggle");
	});

	$("#btnEditConfirm").click(function () {
		ajaxEdit();
	});

	$("#btnNewConfirm").click(function () {
		if ($.trim($("#newCode").attr("value")) === "" ||
			$.trim($("#newName").attr("value")) === "") {
			alert("零部件编号和零部件名称不能为空");
			return false;
		}
		ajaxNew();
	});

	$("#btnEditProviderConfirm").click(function () {
		componentId = $("#editProviderModal").data("componentId");
		ajaxEditProvider(componentId);
	});

	$(".prePage").click(
		function (){
			if ($("#liF0").hasClass("active")) {
				var isFault = Number($("#isFaultF0").attr("checked") === "checked");
				ajaxQuery("F0", $("#inputNameF0").val(), $("#inputCodeF0").val(), $("#selectCategoryF0").val()
					,parseInt($(".curPage").attr("page")) - 1);
			} else if($("#liM6").hasClass("active")){
				var isFault = Number($("#isFaultM6").attr("checked") === "checked");
				ajaxQuery("M6", $("#inputNameM6").val(), $("#inputCodeM6").val(), $("#selectCategoryM6").val(),
					isFault, parseInt($(".curPage").attr("page")) - 1);
			} else if(($("#li6B").hasClass("active"))) {
				var isFault = Number($("#isFault6B").attr("checked") === "checked");
				ajaxQuery("6B", $("#inputName6B").val(), $("#inputCode6B").val(), $("#selectCategory6B").val(),
					isFault, parseInt($(".curPage").attr("page")) - 1);
			} else if(($("#liG6").hasClass("active"))) {
				var isFault = Number($("#isFaultG6").attr("checked") === "checked");
				ajaxQuery("G6", $("#inputNameG6").val(), $("#inputCodeG6").val(), $("#selectCategoryG6").val(),
					isFault, parseInt($(".curPage").attr("page")) - 1);
			}
		}
	);

	$(".nextPage").click(
		function (){
			if ($("#liF0").hasClass("active")) {
				var isFault = Number($("#isFaultF0").attr("checked") === "checked");
				ajaxQuery("F0", $("#inputNameF0").val(), $("#inputCodeF0").val(), $("#selectCategoryF0").val(), 
					isFault ,parseInt($(".curPage").attr("page")) + 1);
			} else if($("#liM6").hasClass("active")){
				var isFault = Number($("#isFaultM6").attr("checked") === "checked");
				ajaxQuery("M6", $("#inputNameM6").val(), $("#inputCodeM6").val(), $("#selectCategoryM6").val(),
					isFault, parseInt($(".curPage").attr("page")) + 1);
			} else if(($("#li6B").hasClass("active"))) {
				var isFault = Number($("#isFault6B").attr("checked") === "checked");
				ajaxQuery("6B", $("#inputName6B").val(), $("#inputCode6B").val(), $("#selectCategory6B").val(),
					isFault, parseInt($(".curPage").attr("page")) + 1);
			} else if(($("#liG6").hasClass("active"))) {
				var isFault = Number($("#isFaultG6").attr("checked") === "checked");
				ajaxQuery("G6", $("#inputNameG6").val(), $("#inputCodeG6").val(), $("#selectCategoryG6").val(),
					isFault, parseInt($(".curPage").attr("page")) + 1);
			}
		}
	);

	$("#editProviderName1").typeahead({
	    source: function (input, process) {
	        $.get(GET_PROVIDER_NAME_LIST, {"providerName":input}, function (data) {
	        	if(data.data == '') {
	        		$("#editProviderCode1").html("<i class='fa fa-times'></i>");
	        		$("#editProviderId1").val("0");
	        	}
	        	return process(data.data);
	        },'json');
	    },

	    updater:function (item) {
	    	code = getProviderCode(item).provider_code;
			$("#editProviderCode1").html("<i class='fa fa-check'></i>[" + code + "]");
			$("#editProviderId1").val(getProviderCode(item).provider_id);
			return item;
    	}
	});
	$("#editProviderName2").typeahead({
	    source: function (input, process) {
	        $.get(GET_PROVIDER_NAME_LIST, {"providerName":input}, function (data) {
	        	if(data.data == '') {
	        		$("#editProviderCode2").html("<i class='fa fa-times'></i>");
	        		$("#editProviderId2").val("0");
	        	}
	        	return process(data.data);
	        },'json');
	    },

	    updater:function (item) {
	    	code = getProviderCode(item).provider_code;
			$("#editProviderCode2").html("<i class='fa fa-check'></i>[" + code + "]");
			$("#editProviderId2").val(getProviderCode(item).provider_id);
			return item;
    	}
	});
	$("#editProviderName3").typeahead({
	    source: function (input, process) {
	        $.get(GET_PROVIDER_NAME_LIST, {"providerName":input}, function (data) {
	        	if(data.data == '') {
	        		$("#editProviderCode3").html("<i class='fa fa-times'></i>");
	        		$("#editProviderId3").val("0");
	        	}
	        	return process(data.data);
	        },'json');
	    },

	    updater:function (item) {
	    	code = getProviderCode(item).provider_code;
			$("#editProviderCode3").html("<i class='fa fa-check'></i>[" + code + "]");
			$("#editProviderId3").val(getProviderCode(item).provider_id);
			return item;
    	}
	});

});