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
		    			if (value.is_fault == "1") {
		    				$("<td />").html("是").appendTo(tr);
		    			} else {
		    				$("<td />").html("否").appendTo(tr);
		    			}
		    			
		    			$("<td />").html(value.simple_code).appendTo(tr);
		    			$("<td />").html(value.remark).appendTo(tr);
		    			var editTd = $("<td />").html(" ¦ ");
		    			$("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
		    			$("<button />").addClass("btn-link").html("删除").appendTo(editTd);
		    			editTd.appendTo(tr);

		    			//record id and name
		    			tr.data("carSeries", value.car_series);
		    			tr.data("componentId", value.id);
		    			tr.data("categoryId", value.category_id);
						tr.data("name",value.name);		//added by wujun
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
		if ($(e.target).is("button")) {
			if ($(e.target).html() === "编辑") {

				$('#editModal').modal("toggle");

				var siblings = $(e.target).parent("td").siblings();
				var tr = $(e.target).closest("tr");
				$("#inputSeries").val(tr.data("carSeries"));
				$("#inputCate").val(tr.data("categoryId"));
				// $("#inputCate").attr("value",siblings[1].innerHTML);
				$("#inputCode").attr("value",siblings[2].innerHTML);
				$("#inputDisplayName").attr("value",siblings[3].innerHTML);		//modified by wujun
				if (siblings[4].innerHTML === '是') {
					$("#checkboxIsFault").attr("checked", "checked");
				} else {
					$("#checkboxIsFault").removeAttr("checked");
				}
				$("#inputSimpleCode").attr("value",siblings[5].innerHTML);
				$("#inputComment").attr("value",siblings[6].innerHTML);

				// console.log($(e.target).parent("td").parent("tr").data("componentId"));
				$("#editModal").data("componentId", tr.data("componentId"));
				$("#inputName").attr("value", tr.data("name"));	//added by wujun
				// $("#editModal").data("componentId");//get the component id 
			} else {
				ajaxDelete(tr.data("componentId"));
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
					"remark" : $("#inputComment").attr("value")},
			success : function (response) {
				if(response.success) {
					$("#editModal").modal("hide");

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
					"simpleCode" : $("#newSimpleCode").attr("value"),			//added by wujun
					"remark" : $("#newComment").attr("value")},
			success : function (response) {
				$("#newSeries").val(0);
				$("#newCate").val(0);
				$("#newCode").attr("value", "");
				$("#newName").attr("value", "");
				$("#newIsFault").removeAttr("checked");
				$("#newSimpleCode").attr("value", "");
				$("#newComment").attr("value", "");
				$("#newModal").modal("hide");		//added by wujun
				//alert(response.message);			//modified by wujun
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
				if ($("#liF0").hasClass("active")) {
					var isFault = Number($("#isFaultF0").attr("checked") === "checked");
					ajaxQuery("F0", $("#inputNameF0").val(), $("#inputCodeF0").val(), $("#selectCategoryF0").val(), 
						isFault ,parseInt($(".curPage").attr("page")));
				} else {
					var isFault = Number($("#isFaultM6").attr("checked") === "checked");
					ajaxQuery("M6", $("#inputNameM6").val(), $("#inputCodeM6").val(), $("#selectCategoryM6").val(),
						isFault, parseInt($(".curPage").attr("page")));
			}
			},
			error : function (response) {
				alert(response.message);
			}
		});
	}

	$(window).bind('keydown', enterHandler);
	function enterHandler (event) {
		if (event.keyCode == "13"){
			if ($("#liF0").hasClass("active")) {
				var isFault = Number($("#isFaultF0").attr("checked") === "checked");
				ajaxQuery("F0", $("#inputNameF0").val(), $("#inputCodeF0").val(), $("#selectCategoryF0").val(), 
					isFault ,parseInt($(".curPage").attr("page")));
			} else if($("#liM6").hasClass("active")) {
				var isFault = Number($("#isFaultM6").attr("checked") === "checked");
				ajaxQuery("M6", $("#inputNameM6").val(), $("#inputCodeM6").val(), $("#selectCategoryM6").val(),
					isFault, parseInt($(".curPage").attr("page")));
			} else if($("#li6B").hasClass("active")){
				var isFault = Number($("#isFault6B").attr("checked") === "checked");
				ajaxQuery("6B", $("#inputName6B").val(), $("#inputCode6B").val(), $("#selectCategory6B").val(),
					isFault, parseInt($(".curPage").attr("page")));
			}
			return false;
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
});