$(document).ready(function() {
	initPage();

	$("#btnSubmit").click(function() {
		
		ajaxGetStandbyCar();
		return false;
	});

	//清空
	// $("#refresh").click(function() {
	// 	resetPage();
	// 	refresh();
	// 	return false;
	// });

	$("#reset").click(function() {
		$("#carInfo").attr("orderId", ""); 
		resetPage();
		refresh();
	})

	$("#cardNumber").bind('keydown', function(event) {
		//if vinText disable,stop propogation
		if($(this).attr("disabled") == "disabled")
			return false;
		if (event.keyCode == "13"){
			//remove blanks 
		    if(jQuery.trim($('#cardNumber').val()) != ""){
		        ajaxCardNumber();
	        }   
		    return false;
		}
	});


	function ajaxGetOrder() {
		$.ajax({
			url: ORDER_SEARCH,
			type: "get",
			dataType: "json",
			data: {
				//"standbyDate": workDate(), 
				"status": 1,
			},
			success: function(response) {
				if(response.success){
					$("#tableOrder>tbody").html("");
					var orders = response.data;
					var amountAll = 0;
					var countAll = 0;
					var remainAll = 0;
					var holdAll = 0;
					$.each(orders, function (index,value) {
						var tr =$("<tr />");

						$("<td />").html(value.priority).appendTo(tr);
						$("<td />").html(value.remain).appendTo(tr);
						$("<td />").html(value.order_number).appendTo(tr);
						$("<td />").html(value.amount).appendTo(tr);
						$("<td />").html(value.count).appendTo(tr);
						$("<td />").html(value.series).appendTo(tr);
						$("<td />").html(value.color).appendTo(tr);
						$("<td />").html(value.order_config_name).appendTo(tr);
						$("<td />").html(value.car_type).appendTo(tr);
						if(value.cold_resistant == "1"){
							$("<td />").html("耐寒").appendTo(tr);
						} else {
							$("<td />").html("非耐寒").appendTo(tr);
						}
						//$("<td />").html(value.car_year).appendTo(tr);
						$("<td />").html(value.lane).appendTo(tr);
						$("<td />").html(value.carrier).appendTo(tr);
						$("<td />").html(value.distributor_name).appendTo(tr);
						$("<td />").html(value.city).appendTo(tr);
						$("<td />").html(value.remark).appendTo(tr);

						countAll += parseInt(value.count);
						amountAll += parseInt(value.amount);
						remainAll = amountAll - countAll;
						amountAll += parseInt(value.hold);

						tr.data("id", value.id);
						tr.data("distributerId", value.distributer_id);
						if(value.id == $("#carInfo").attr("orderId")){
							tr.addClass("info")
						}
						$("#tableOrder>tbody").append(tr);
						$("#infoCount").html(remainAll +" = " + amountAll + " - " + countAll + " (" + holdAll + ")");						
					});
						
				}
			},
			error: function() {
				alertError();
			}
		})
	}

	function ajaxCardNumber() {
		$.ajax({
			url: CHECK_CARD_NUMBER,
			type:"get",
			dataType: "json",
			data: {
				"cardNumber": $("#cardNumber").val(),
			},
			success: function(response) {
				if(response.success){
					driver = response.data
					$("#btnSubmit").removeAttr("disabled").focus();
					$("#cardNumber").attr("value", driver.car_number);
					$("#cardNumber").attr("disabled","disabled");
					$("#driver").html(driver.name);
				} else {
					$("#carInfo").attr("orderId", "");
					resetPage();
					refresh();
					fadeMessageAlert(response.message, "alert-error");
				}
			},
			error: function() {
				alertError();
			}

		})
	}

	function ajaxGetStandbyCar() {
		$.ajax({
			url: ORDER_GET_CAR_STANDBY,
			type: "get",
			dataType: "json",
			data: {
				//"standbyDate": workDate(),
			},
			success: function(response) {
				if(response.success) {
					var data = response.data;
					$("#rowInfo,#rowPrint").html(data.row);
					$("#vinInfo,#vinPrint").html(data.vin);
					$("#seriesInfo").html(data.series);
					$("#typeInfo").html(data.type);
					$("#colorInfo").html(data.color);
					$("#orderNumberInfo").html(data.order_number);
					$("#carInfo").attr("orderId", data.order_id);
					$("#carInfo").hide();
					toggleHint(false);
					setTimeout(function (){window.print();},500);
					resetPage();
				} else {
					fadeMessageAlert(response.message,"alert-error");
				}
			},
			error: function(){alertError();}
			})
	}

	function initPage() {
		$("#headAssemblyLi").addClass("active");
		$("#leftNodeSelectLi").addClass("active");
		$(".today").html(workDate())
		$(".nowTime").html(nowTime());
		toggleHint(true);
		resetPage();
		$("#messageAlert").hide();
	}

	function refresh() {
		ajaxGetOrder();
		toggleHint(true);
	}

	function resetPage() {
		$("#cardNumber").removeAttr("disabled");
		$("#cardNumber").attr("value","");
		$("#cardNumber").focus();
		$("#btnSubmit").attr("disabled","disabled");
		ajaxGetOrder();
	}

	function toggleHint (showVinHint) {
		if(showVinHint){
			$("#carInfo").hide();
			$("#hint").fadeIn(1000);
			//$("#hint").show();

		}else{
			$("#hint").hide();
			$("#carInfo").fadeIn(1000);
		}
	}


	function currentDate () {
			var now = new Date();
			var year = now.getFullYear();       //年
			var month = now.getMonth() + 1;     //月
			var day = now.getDate();            //日
		   // var hh = now.getHours();            //时
			//var mm = now.getMinutes();          //分
		   
			var clock = year + '-';

			if(month < 10) clock += '0';
			clock += month + '-';

			if(day < 10) clock += '0';
			clock += day;

			//clock += "08:00";

			return(clock); 
	}

	function nowTime () {
		var now = new Date();
		var year = now.getFullYear();
		var month = now.getMonth();
		var day = now.getDate();
		var hh = now.getHours();
		var mm = now.getMinutes();

		var clock = year + '-';

		if(month < 10) clock += '0';
		clock += month + '-';

		if(day < 10) clock += '0';
		clock += day + ' ';

		if(hh < 10) clock += '0';
		clock += hh + ':';

		if(mm < 10) clock += '0';
		clock += mm;

		return(clock);
	}

	function yesterday () {
			//获取系统时间 
			var now = new Date();
			var nowYear = now.getFullYear();
			var nowMonth = now.getMonth();
			var nowDate = now.getDate();
			//处理
			var uom = new Date(nowYear,nowMonth, nowDate);
			uom.setDate(uom.getDate() - 1);//取得系统时间的前一天,重点在这里,负数是前几天,正数是后几天
			var LINT_MM = uom.getMonth();
			LINT_MM++;
			var LSTR_MM = LINT_MM >= 10?LINT_MM:("0"+LINT_MM)
			var LINT_DD = uom.getDate();
			var LSTR_DD = LINT_DD >= 10?LINT_DD:("0"+LINT_DD)
			//得到最终结果
			uom = uom.getFullYear() + "-" + LSTR_MM + "-" + LSTR_DD; 
			return(uom);
	}

	function workDate() {
		var now = new Date();
		var hh = now.getHours();
		var workDate;

		if(hh>=8 && hh<24) {
			workDate = currentDate();
		} else {
			workDate = yesterday();
		}

		return(workDate);
	}

	function fadeMessageAlert(message,alertClass){
		$("#messageAlert").removeClass("alert-error alert-success").addClass(alertClass);
		$("#messageAlert").html(message);
		$("#messageAlert").show(500,function () {
			setTimeout(function() {
				$("#messageAlert").hide(1000);
			},5000);
		});
	}
})