$(document).ready( function () {

	$(".carousel").carousel('pause');
	$("#tabs").live("click", function () {
		var kindHtml = '';
		if ($("#liAssembly").hasClass("active")) {
			kindHtml = '<option value="1">装配失当</option><option value="2">零部件</option><option value="3">设备相关</option>	<option value="4">密封性</option><option value="5">其它_整车</option>';
		} else if ($("#liPaint").hasClass("active")){
			kindHtml =  '<option value="6">漆膜性能</option>'+
						'<option value="7">漆面外观</option>'+
						'<option value="8">密封胶PVC</option>'+
						'<option value="9">其它_涂装</option>';
		} else if ($("#liBody").hasClass("active")){
			kindHtml = '<option value="10">焊接质量</option>'+
						'<option value="11">车身尺寸</option>'+
						'<option value="12">车身装配</option>'+
						'<option value="13">其它_焊装</option>';
		} else if ($("#liPress").hasClass("active")){
			kindHtml = '<option value="14">钣金外观</option>'+
						'<option value="15">钣金尺寸</option>';
		} else {
			kindHtml = '<option value="1">装配失当</option>'+
						'<option value="2">零部件</option>'+
						'<option value="3">设备相关</option>'+
						'<option value="4">密封性</option>'+
						'<option value="5">其它_整车</option>'+
						'<option value="6">漆膜性能</option>'+
						'<option value="7">漆面外观</option>'+
						'<option value="8">密封胶PVC</option>'+
						'<option value="9">其它_涂装</option>'+
						'<option value="10">焊接质量</option>'+
						'<option value="11">车身尺寸</option>'+
						'<option value="12">车身装配</option>'+
						'<option value="13">其它_焊装</option>'+
						'<option value="14">钣金外观</option>'+
						'<option value="15">钣金尺寸</option>';
		}

		var allKindHtml = '';
		if ($("#liAssembly").hasClass("active")) {
			allKindHtml = '<option value="assembly" selected>全部</option>';
		} else if ($("#liPaint").hasClass("active")){
			allKindHtml = '<option value="paint" selected>全部</option>';
		} else if ($("#liBody").hasClass("active")){
			allKindHtml = '<option value="welding" selected>全部</option>';
		} else if ($("#liPress").hasClass("active")){
			allKindHtml = '<option value="press" selected>全部</option>';
		}

		$("#selectFaultKind").html(allKindHtml + kindHtml);
		$("#selectCarSeries").val('F0');
		$("#tdLevel input[type='checkbox']").removeAttr("checked");
		$("#inputComponentName").val("");
		$("#inputFaultMode").val("");
		$("#selectCategoryF0").val("all");
		$("#newKind").html(kindHtml);
		$("#inputKind").html(kindHtml);
	});
	initPage();

	function initPage () {
		$("#headGeneralInformationLi").addClass("active");
		$("#leftFaultMaintainLi").addClass("active");

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
		//     			//详细
		//     			var detailTd = $("<td />").html(" ¦ ");
		//     			$("<button />").attr("title", "ccx").addClass("btn-link").html("描述").prependTo(detailTd);
		//     			$("<button />").addClass("btn-link").html("图片").appendTo(detailTd);
		//     			detailTd.appendTo(tr);
		//     			$("#tableFaultStandard tbody").append(tr);
	}

	$(window).bind('keydown', enterHandler);
	function enterHandler (event) {
		if (event.keyCode == "13"){
		    queryPage(1);
			return false;
		}
	}
	
	$("#btnQuery").click(function () {

		queryPage(1);
		return false;
	});

	function queryPage (pageNumber) {
		var level = [];
		if($("#levelS").attr("checked") === "checked")
			level.push("S");
		if($("#levelA").attr("checked") === "checked")
			level.push("A");
		if($("#levelB").attr("checked") === "checked")
			level.push("B");
		if($("#levelC").attr("checked") === "checked")
			level.push("C");
		// console.log(level);

		ajaxQuery($("#selectCarSeries").val(), $("#inputComponentName").val(), $("#selectFaultKind").val(), level, $("#selectCategoryF0").val(), $("#inputFaultMode").val(), pageNumber);
	}
	function ajaxQuery (series, name, faultKind ,level, status, mode, pageNumber) {


		$.ajax({
			url : QUERY_FAULT_BASE,
			type : "GET",
			dataType: "json",
			data : {"series" : series, 
					"component" : name, 
					"fault_kind" : faultKind,
					"level" : level,
					"status" : status,
					"mode" : mode,
					"perPage":20,
					"curPage": pageNumber || 1},
			success : function (response) {
				if (response.success) {
					$("#tableFaultStandard>tbody").text("");
					$.each(response.data.data,function (index,value) {
						 // 故障代码，零部件敏称，故障模式，详细，严重度，故障类别，状态，编辑
		    			var tr = $("<tr />");

		    			// 故障代码，零部件敏称，故障模式
		    			$("<td />").html(value.fault_code).appendTo(tr);
		    			$("<td />").html(value.component_name).appendTo(tr);
		    			$("<td />").html(value.mode).appendTo(tr);
		    			
		    			//详细
		    			var detailTd = $("<td />").html(" ¦ ");
		    			var descBtn = $("<button />");
		    			descBtn.attr("title", value.description).addClass("btn-link").html("描述").prependTo(detailTd);
		    			if(!(value.description && value.description !== "")){
							descBtn.css("color", "#000");
		    			}
		    			$("<button />").addClass("btn-link").html("图片").appendTo(detailTd);
		    			detailTd.appendTo(tr);

		    			//严重度，故障类别，状态
		    			$("<td />").html(value.level).appendTo(tr);
		    			$("<td />").html(value.kind).appendTo(tr);
		    			if (value.status === "1") {
		    				$("<td />").html("启用").appendTo(tr);
		    			} else {
		    				$("<td />").html("冻结").appendTo(tr);
		    			}

		    			//编辑
		    			var editTd = $("<td />").html(" ¦ ");
		    			$("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
		    			$("<button />").addClass("btn-link").html("删除").appendTo(editTd);
		    			editTd.appendTo(tr);

		    			//record id
		    			tr.data("faultId", value.id);
		    			tr.data("kindId", value.kind_id);
		    			tr.data("carSeries", value.car_series);
		    			$("#tableFaultStandard tbody").append(tr);
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


	$("#tableFaultStandard").click(function (e) {
		if ($(e.target).is("button")) {
			if ($(e.target).html() === "编辑") {

				$('#editModal').modal("toggle");

				var siblings = $(e.target).parent("td").siblings();
				
				$("#inputSeries").val($(e.target).parent("td").parent("tr").data("carSeries"));
				$("#inputCode").val(siblings[0].innerHTML);
				$("#inputName").val(siblings[1].innerHTML);
				$("#inputMode").val(siblings[2].innerHTML);
				$("#inputLevel").val(siblings[4].innerHTML);
				$("#inputKind").val($(e.target).parent("td").parent("tr").data("kindId"));
				$("#inputDescription").val($(siblings[3]).children(":eq(0)").attr("title"));
				if (siblings[6].innerHTML === '启用') {
					$("#inputStatus").val(1);
				} else {
					$("#inputStatus").val(0);
				}

				$("#editModal").data("faultId", $(e.target).parent("td").parent("tr").data("faultId"));
				// $("#editModal").data("componentId");//get the component id 
			} else if($(e.target).html() === "删除"){
				ajaxDelete($(e.target).parent("td").parent("tr").data("faultId"));
			} else {
				return false;
			}
			
		}
	});

	$("#btnAddF0").click(function () {
		$('#newSeries').attr("value", "f0");
		$('#newModal').modal("toggle");
	});

	$("#btnAddM6").click(function () {
		$('#newSeries').attr("value", "m6");
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

		$.ajax({
			url : SAVE_FAULT_STANDARD,
			type : "GET",
			dataType: "json",
			data : {"id" : $("#editModal").data("faultId"), 
					"series" : $("#inputSeries").val(),
					"code" : $("#inputCode").val(),
					"mode" : $("#inputMode").val(),
					"component_name" : $("#inputName").val(),
					"level" : $("#inputLevel").val(),
					"fault_kind" : $("#inputKind").val(),
					"status" : $("#inputStatus").val(),
					"description" : $("#inputDescription").val()
				},
			success : function (response) {
				// console.log(response.message);
				$('#editModal').modal("hide");
				queryPage($(".curPage").attr("page"));
			},
			error : function (response) {
				alert(response.message);
			}
		});
	}

	function ajaxNew () {
		$.ajax({
			url : SAVE_FAULT_STANDARD,
			type : "GET",
			dataType: "json",
			data : {"id" : 0, 
					"series" : $("#newSeries").val(),
					"code" : $("#newCode").val(),
					"mode" : $("#newMode").val(),
					"component_name" : $("#newName").val(),
					"level" : $("#newLevel").val(),
					"fault_kind" : $("#newKind").val(),
					"status" : $("#newStatus").val(),
					"description" : $("#newDescription").val()
				},
			success : function (response) {
				if(response.success) {
					$("#newSeries").val(0);
					$("#newLevel").val("A");
					$("#newKind").val(0);
					$("#newStatus").val(1);
					$("#newCode").attr("value", "");
					$("#newName").attr("value", "");
					$("#newMode").attr("value", "");
					$("#newDescription").attr("value", "");
					queryPage(1);
					$('#newModal').modal("hide");
				}
				// alert(response.message);
			},
			error : function (response) {
				alert(response.message);
			}
		});
	}

	function ajaxDelete (deleteId) {
		$.ajax({
			url : REMOVE_FAULT_STANDARD,
			type : "GET",
			dataType: "json",
			data : {"id" : deleteId},
			success : function (response) {
				if (response.success) {
					alert(response.message);	
					queryPage($(".curPage").attr("page"));
				}
				// if ($("#liF0").hasClass("active")) {
				// 	var isFault = Number($("#isFaultF0").attr("checked") === "checked");
				// 	ajaxQuery("F0", $("#inputNameF0").val(), $("#inputCodeF0").val(), $("#selectCategoryF0").val(), 
				// 		isFault ,parseInt($(".curPage").attr("page")));
				// } else {
				// 	var isFault = Number($("#isFaultM6").attr("checked") === "checked");
				// 	ajaxQuery("M6", $("#inputNameM6").val(), $("#inputCodeM6").val(), $("#selectCategoryM6").val(),
				// 		isFault, parseInt($(".curPage").attr("page")));
				// }
			},
			error : function (response) {
				alert(response.message);
			}
		});
	}

	$(".prePage").click(
		function (){
			queryPage(parseInt($(".curPage").attr("page")) - 1);
			// if ($("#liF0").hasClass("active")) {
			// 	ajaxQuery("F0", $("#inputNameF0").val(), $("#inputCodeF0").val(), $("#selectCategoryF0").val()
			// 		,parseInt();
			// } else {
			// 	ajaxQuery("M6", $("#inputNameM6").val(), $("#inputCodeM6").val(), $("#selectCategoryM6").val(),
			// 		isFault, parseInt($(".curPage").attr("page")) - 1);
			// }
		}
	);

	$(".nextPage").click(
		function (){
			queryPage(parseInt($(".curPage").attr("page")) + 1);

			// if ($("#liF0").hasClass("active")) {
			// 	var isFault = Number($("#isFaultF0").attr("checked") === "checked");
			// 	ajaxQuery("F0", $("#inputNameF0").val(), $("#inputCodeF0").val(), $("#selectCategoryF0").val(), 
			// 		isFault ,parseInt($(".curPage").attr("page")) + 1);
			// } else {
			// 	var isFault = Number($("#isFaultM6").attr("checked") === "checked");
			// 	ajaxQuery("M6", $("#inputNameM6").val(), $("#inputCodeM6").val(), $("#selectCategoryM6").val(),
			// 		isFault, parseInt($(".curPage").attr("page")) + 1);
			// }
		}
	);

	//自动补全
	$("#newName").typeahead({
	    source: function (input, process) {
	        $.get(SEARCH_COMPONENT_NAME_LIST, {"component":input}, function (data) {
	        	return process(data.data);
	        },'json');
	    },
	    updater:function (item) {
	     	ajaxGetNewCode(item);//根据part的名字查找故障模式
        	return item;
    	}
	});

	function ajaxGetNewCode (item) {
		$.ajax({
			url : GENERATE_FAULT_CODE,
			type : "GET",
			dataType: "json",
			data : {"series" : $("#newSeries").val(),
					"component" : item
			},
			success : function (response) {
				if (response.success) {
					$("#newCode").val(response.data);
				} else {
					alert(response.message);
				}
			},
			error : function (response) {
				alert(response.message);
			}
		});
	}
});
