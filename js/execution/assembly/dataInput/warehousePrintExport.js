$(document).ready(function() {
	initPage();

	$("#specialOrder").bind("keydown", function(event) {
		if (event.keyCode == '13'){
			if($.trim($("#specialOrder").val()) != ""){
				ajaxQueryCars();
			}
			return false;
		}
	})

	$("#search-remove").click(function() {
		if($(this).hasClass("goSearch")){
			ajaxQueryCars();
		}else if($(this).hasClass("clearinput")){
			$(this).siblings().filter("input").val("");
			$(this).siblings().filter(".specialOrderText").removeAttr("disabled");
			$(this).parent().siblings().filter(".btnPrint").attr("disabled", "disabled");
			resetPage();
		}
	})

	$("#printAll").click(function() {
		if(confirm('是否传输打印？')){
			$("#spinModal").modal("show");
			printBySpecialOrder();
		}
	})

	function initPage() {
		$("#headAssemblyLi").addClass("active");
		$("#leftWarehousePrintLi").addClass("active");
		$("#messageAlert").hide();
		resetPage();
	}

	function resetPage() {
		$("#printAll").attr("disabled", "disabled");
		$("#specialOrder").val("").removeAttr("disabled");
		toggleVinHint(true);
		toggleSerachRemove(true);
		$("#tableResult").hide();
	}



	function ajaxQueryCars() {
		$.ajax({
			url: QUERY_CARS_BY_SPECIAL_ORDER,
			type: "get",
			dataType: "json",
			data: {
				'specialOrder' : $("#specialOrder").val(),
			},
			success: function(response) {
				if(response.success){
					$("#tableResult>tbody").html("");
					$("#total").html("共计=" + response.data.total);
					$("#isGood").html("完整=" + response.data.isGood);
					toggleVinHint(false);
					toggleSerachRemove(false);
					$("#specialOrder").attr("disabled", "disabled");
					if(response.data.total != 0 && response.data.total === response.data.isGood){
						$("#printAll").removeAttr("disabled");
					}
					cars = response.data.cars;
					$.each(cars, function (index, value) {
						tr = $("<tr />");
						$("<td />").html(value.special_order).appendTo(tr);
						$("<td />").html(value.vin).appendTo(tr);
						$("<td />").html(value.serial_number).appendTo(tr);
						$("<td />").html(value.series).appendTo(tr);
						$("<td />").html(value.type_config + '/' + value.cold).appendTo(tr);
						$("<td />").html(value.color).appendTo(tr);
						$("<td />").html(value.engine_code).appendTo(tr);
						$("<td />").html(value.status).appendTo(tr);
						if(value.finish_time === '0000-00-00 00:00:00'){
							$("<td />").html('未下线').appendTo(tr);
						} else {
							$("<td />").html(value.finish_time.substring(0,16)).appendTo(tr);
						}
						$("<td />").html(value.remark).appendTo(tr);
						if(value.inspectionSheet === 'OK'){
							$("<td />").html("<i class='icon-ok'></i>").addClass("alignCenter").appendTo(tr);
						} else {
							$("<td />").html("").appendTo(tr);
						}
						if(value.certificatePaper === 'OK'){
							$("<td />").html("<i class='icon-ok'></i>").addClass("alignCenter").appendTo(tr);
						} else {
							$("<td />").html("").appendTo(tr);
						}

						if(value.inspectionSheet === 'NG' && value.certificatePaper === 'NG'){
							tr.addClass("error");
						}else if(value.inspectionSheet === 'NG' || value.certificatePaper === 'NG'){
							tr.addClass("warning");
						}

						$("#tableResult>tbody").append(tr);
					})

					$("#tableResult").show();
				}
			},
			error: function(){
				alertError();
			}
		})
	}

	function printBySpecialOrder() {
		$.ajax({
			url: PRINT_BY_SPECIAL_ORDER,
			type: "get",
			dataType: "json",
			data: {
				'specialOrder' : $("#specialOrder").val(),
			},
			success: function(response) {
				if(response.success){
					data = response.data
					$("#spinModal").modal("hide");
					alert("打印传输完成!");
					resetPage();
					message = $("#specialOrder").val() + '共传输' + data.total + '，厂检单传输成功' + data.inspectionSuccess + '，合格证传输成功' + data.certificateSuccess;
					if(data.inspectionSuccess === 0 || data.certificateSuccess === 0){
						fadeMessageAlert(message,"alert-error");
					} else if(data.total === data.inspectionSuccess && data.total === data.certificateSuccess){
						fadeMessageAlert(message,"alert-success");
					} else if(data.total > data.inspectionSuccess || data.total > data.certificateSuccess){
						fadeMessageAlert(message,"alert-warning");
					}
				}else{
					$("#spinModal").modal("hide");
					alert(response.message);
					resetPage();
					fadeMessageAlert(response.message,"alert-error");
				}
			},
			error: function(){
				alertError();
			}
		})
	}

	function toggleVinHint (showVinHint) {
		if(showVinHint){
			$("#orderInfo").hide();
			$("#orderHint").fadeIn(1000);

		}else{
			$("#orderHint").hide();
			$("#orderInfo").fadeIn(1000);
		}
	}

	function toggleSerachRemove(isSearch){
	 	if(isSearch){
	 		$("#search-remove").removeClass("clearinput").addClass("goSearch");
	 		$("#search-remove i").removeClass("icon-remove").addClass("icon-search");
	 	}else{
	 		$("#search-remove").removeClass("goSearch").addClass("clearinput");
	 		$("#search-remove i").removeClass("icon-search").addClass("icon-remove");
	 	}
	}

	function fadeMessageAlert(message,alertClass){
		$("#messageAlert").removeClass("alert-error alert-success").addClass(alertClass);
		$("#messageAlert").html(message);
		$("#messageAlert").show(500,function () {
			setTimeout(function() {
				$("#messageAlert").hide(1000);
			},60000);
		});
	}

	$(".clearinput").bind("click", function() {
		
	})

	$('body').tooltip(
        {
         selector: "a[rel=tooltip]"
	});

});
