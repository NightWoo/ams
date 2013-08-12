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


		// $("#selectCategoryF0").val(2);
		// var tr = $("<tr />");
		    		
		//     			var editTd = $("<td />").html(" ¦ ");
		//     			$("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
		//     			$("<button />").addClass("btn-link").html("删除").appendTo(editTd);
		//     			editTd.appendTo(tr);

		//     			$("#tableComponent tbody").append(tr);
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
		    			providerText = providerText == "" ? "<i class='icon-plus'></i>添加" : "<i class='icon-edit'></i>" + providerText;
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
						tr.data("name",value.name);
						tr.data("unitPrice",value.unit_price);	
						tr.data("provider_1",value.provider_1);	
						tr.data("provider_2",value.provider_2);	
						tr.data("provider_3",value.provider_3);	
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


	$("#tableComponent").click(function (e) {
		if($(e.target).is("button")) {
			if ($(e.target).html() === "编辑") {

				$('#editModal').modal("toggle");

				var siblings = $(e.target).parent("td").siblings();
				var tr = $(e.target).closest("tr");
				$("#inputSeries").val(tr.data("carSeries"));
				$("#inputCate").val(tr.data("categoryId"));
				// $("#inputCate").attr("value",siblings[1].innerHTML);
				$("#inputCode").attr("value",siblings[2].innerHTML);
				$("#inputDisplayName").attr("value",siblings[3].innerHTML);		//modified by wujun
				$("#inputSimpleCode").attr("value",siblings[4].innerHTML);
				if (siblings[5].innerHTML === '是') {
					$("#checkboxIsFault").attr("checked", "checked");
				} else {
					$("#checkboxIsFault").removeAttr("checked");
				}
				$("#editUnitPrice").val(tr.data("unitPrice"));
				$("#inputComment").attr("value",siblings[8].innerHTML);

				// console.log($(e.target).parent("td").parent("tr").data("componentId"));
				$("#editModal").data("componentId", tr.data("componentId"));
				$("#inputName").attr("value", tr.data("name"));	//added by wujun
				// $("#editModal").data("componentId");//get the component id 
			} else {
				ajaxDelete(tr.data("componentId"));
			}
		}

		if($(e.target).is("a")){
			if($(e.target).hasClass("editProvider")){
				var tr = $(e.target).closest("tr");
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
					"category" : $("#newCate").val(),
					"isfault" : isFault,
					"simpleCode" : $("#newSimpleCode").attr("value"),	
					"unitPrice" : $("#newUnitPrice").attr("value"),
					"remark" : $("#newComment").attr("value")},
			success : function (response) {
				$("#newSeries").val(0);
				$("#newCate").val(0);
				$("#newCode").attr("value", "");
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
			}
	}

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
			}
		}
	);

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

	$("#editProviderName1").typeahead({
	    source: function (input, process) {
	        $.get(GET_PROVIDER_NAME_LIST, {"providerName":input}, function (data) {
	        	if(data.data == '') {
	        		$("#editProviderCode1").html("<i class='icon-remove'></i>");
	        		$("#editProviderId1").val("0");
	        	}
	        	return process(data.data);
	        },'json');
	    },

	    updater:function (item) {
	    	code = getProviderCode(item).provider_code;
			$("#editProviderCode1").html("<i class='icon-ok'></i>[" + code + "]");
			$("#editProviderId1").val(getProviderCode(item).provider_id);
			return item;
    	}
	});
	$("#editProviderName2").typeahead({
	    source: function (input, process) {
	        $.get(GET_PROVIDER_NAME_LIST, {"providerName":input}, function (data) {
	        	if(data.data == '') {
	        		$("#editProviderCode1").html("<i class='icon-remove'></i>");
	        		$("#editProviderId1").val("0");
	        	}
	        	return process(data.data);
	        },'json');
	    },

	    updater:function (item) {
	    	code = getProviderCode(item).provider_code;
			$("#editProviderCode2").html("<i class='icon-ok'></i>[" + code + "]");
			$("#editProviderId2").val(getProviderCode(item).provider_id);
			return item;
    	}
	});
	$("#editProviderName3").typeahead({
	    source: function (input, process) {
	        $.get(GET_PROVIDER_NAME_LIST, {"providerName":input}, function (data) {
	        	if(data.data == '') {
	        		$("#editProviderCode3").html("<i class='icon-remove'></i>");
	        		$("#editProviderId3").val("0");
	        	}
	        	return process(data.data);
	        },'json');
	    },

	    updater:function (item) {
	    	code = getProviderCode(item).provider_code;
			$("#editProviderCode3").html("<i class='icon-ok'></i>[" + code + "]");
			$("#editProviderId3").val(getProviderCode(item).provider_id);
			return item;
    	}
	});

});