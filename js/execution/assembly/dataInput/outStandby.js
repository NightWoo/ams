$(document).ready(function() {
	initPage();

	$("#btnSubmit").click(function() {
		$("#btnSubmit").attr("disabled", "disabled");
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

	function ajaxCardNumber() {
		$.ajax({
			url: CHECK_CARD_NUMBER,
			type:"get",
			dataType: "json",
			async:false,
			data: {
				"cardNumber": $("#cardNumber").val(),
			},
			success: function(response) {
				if(response.success){
					driver = response.data;
					$("#btnSubmit").removeAttr("disabled").focus();
					$("#cardNumber").attr("value", driver.card_number);
					$("#cardNumber").attr("disabled","disabled");
					$("#cardNumber").attr("driverid",driver.user_id);
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
				"driverId" : $("#cardNumber").attr("driverid"),
				"standbyArea" : $("#standbyArea").val(),
				"series" : getSeriesChecked(),
			},
			async:false,
			success: function(response) {
				if(response.success) {
					var data = response.data;
					// $(".nowTime").html("备车"+nowTime());
					$("#rowInfo").html(data.row);
					$("#rowPrint").html(data.row + '-' + data.driver_name);
					$("#vinInfo").html(data.vin);
					$("#vinPrint").html(data.vin + '-' + data.color);
					$("#seriesInfo").html(byd.SeriesName[data.series]);
					$("#typeInfo").html(data.type_info);
					$("#coldInfo").html(data.cold_resistant);
					$("#colorInfo").html(data.color);
					$("#orderNumberInfo, #orderNumberPrint").html(data.order_number);
					$("#distributorInfo, #distributorPrint").html(data.distributor_name);
					$("#laneInfo").html(data.lane + "道");
					// $("#lanePrint").html(data.lane + "道-" + byd.DateUtil.currentTime());
					$("#lanePrint").html(data.lane + "道 @ " + data.activate_time);
					$("#carInfo").attr("orderId", data.order_id);
					$("#carInfo").hide();
					toggleHint(false);
					window.print();
					resetPage();
				} else {
					resetPage();
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
		toggleHint(true);
		resetPage();
		$("#messageAlert").hide();
	}

	function refresh() {
		// ajaxGetOrder();
		toggleHint(true);
	}

	function resetPage() {
		$("#cardNumber").removeAttr("disabled");
		$("#cardNumber").attr("value","");
		$("#cardNumber").focus();
		$("#btnSubmit").attr("disabled","disabled");
		// ajaxGetOrder();
		$(".nowTime").html("备车"+nowTime());
		$("#driver").html("司机")
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

	function getSeriesChecked () {
		var f0Checked = $("#checkboxF0").attr("checked") === "checked";
		var m6Checked = $("#checkboxM6").attr("checked") === "checked";
		var _6BChecked = $("#checkbox6B").attr("checked") === "checked";
		
		var temp = [];
		if (f0Checked)
			temp.push($("#checkboxF0").val());
		if (m6Checked)
			temp.push($("#checkboxM6").val());
		if (_6BChecked)
			temp.push($("#checkbox6B").val());
		return temp.join(",");
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