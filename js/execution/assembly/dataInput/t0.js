$("document").ready(function() {
	initPage();
	ajaxGetPlan();

//------------------- ajax -----------------------	
	//获取计划
	function ajaxGetPlan (argument) {
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: T0_GET_PLAN,//ref:  /bms/js/service.js
			data: {
				"vin": $('#vinText').attr("value"),
				"assembly_line" : $("#line").attr("value"),
			},
			success: function(response) {
				if(response.success){
					var plans = response.data;
					var totalAll = 0;
					var readyAll = 0;
					var leftAll = 0;
					$("#planTable tbody").html("");		//added by wujun
					$.each(plans,function (i) {
						var num = "<td>" + (i + 1) + "</td>";
						var left = "<td>" + (plans[i].total - plans[i].ready) + "</td>";
						readyAll += parseInt(plans[i].ready);
						totalAll += parseInt(plans[i].total);
						leftAll = totalAll - readyAll;
						// var car_series = "<td>" + plans[i].car_series + "</td>";
						var car_series = "<td>" + byd.SeriesName[plans[i].car_series] + "</td>";
						var car_type = "<td>" + plans[i].car_type_name + "</td>";
						var config_name = "<td>" + plans[i].config_name + "</td>";
						if(plans[i].cold_resistant == "1") {
							var cold_resistant = "<td>耐寒</td>";
						} else {
							var cold_resistant = "<td>非耐寒</td>";
						}
						var color = "<td>" + plans[i].color + "</td>";
						var car_year = "<td>" + plans[i].car_year + "</td>";
						// var order_type = "<td>" + plans[i].order_type + "</td>";
						var special_order = "<td>" + plans[i].special_order + "</td>";
						var remark = "<td>" + plans[i].remark + "</td>";
						//modified by wujun
						$("#planTable tbody").append("<tr id='" + plans[i].id + "'>" + num + left + car_series + car_type + config_name + cold_resistant + color + car_year + /*order_type +*/ special_order + remark + "</tr>");
						// console.log($("#"+plans[i].id));
						if(plans[i].is_frozen == 1){
							$("#"+plans[i].id).addClass("warning");
						}
					});
					$("#planDiv").show();
					$("#infoCount").html(leftAll +" = " + totalAll + " - " + readyAll );
				}
				else{
					
				}
			},
			error:function(){alertError();}
		});
	}

	//added by wujun
	//获取明日计划
	function ajaxPlanTomorrow (argument) {
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: T0_GET_PLAN,//ref:  /bms/js/service.js
			data: {
				"plan_date": nextWorkDate(),
				"assembly_line" : $("#line").attr("value"),
			},
			success: function(response) {
				if(response.success){
					var plans = response.data;
					$("#planTomorrow tbody").html("");
					$.each(plans,function (i) {
						var num = "<td>" + (i + 1) + "</td>";
						var left = "<td>" + (plans[i].total - plans[i].ready) + "</td>";
						// var car_series = "<td>" + plans[i].car_series + "</td>";
						var car_series = "<td>" + byd.SeriesName[plans[i].car_series] + "</td>";
						var car_type = "<td>" + plans[i].car_type_name + "</td>";
						var config_name = "<td>" + plans[i].config_name + "</td>";
						if(plans[i].cold_resistant == "1") {
							var cold_resistant = "<td>耐寒</td>";
						} else {
							var cold_resistant = "<td>非耐寒</td>";
						}
						var color = "<td>" + plans[i].color + "</td>";
						var car_year = "<td>" + plans[i].car_year + "</td>";
						// var order_type = "<td>" + plans[i].order_type + "</td>";
						var special_order = "<td>" + plans[i].special_order + "</td>";
						var remark = "<td>" + plans[i].remark + "</td>";
						//modified by wujun
						$("#planTomorrow tbody").append("<tr id='" + plans[i].id + "'>" + num + left + car_series + car_type + config_name + cold_resistant + color + car_year + /*order_type +*/ special_order + remark + "</tr>");						
					});
					$("#planDiv").show();
				}
				else{
					
				}
			},
			error:function(){alertError();}
		});
	}

	//校验
	function ajaxValidate () {
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: T0_MATCH_PLAN,//ref:  /bms/js/service.js
			data: {
				"vin": $('#vinText').attr("value"),
				"currentNode": $('#currentNode').attr("value"),
				"line" : $("#line").attr("value"),
			},//vin and node
			success: function(response) {
				if(response.success){
					$("#vinText").val(response.data.vin);		//added by wujun
					//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
					$("#btnSubmit").removeAttr("disabled");
					//show car infomation
			    	toggleVinHint(false);
			    	//clear before and render car info data,include series,type and color
				 	var car = response.data;
				 	$("#infoSeries").html(car.series);
				 	$("#infoType").html(car.type);
				 	$("#infoColor").html(car.color);
				 	$("#infoStatus").html(car.status);
				 	if(car.cold_resistant == "1"){
				 		$("#infoColdResistant").html("耐寒")
				 	} else {
				 		$("#infoColdResistant").html("非耐寒")
				 	}
				 	//$("#tableCarInfo tbody").text("");
					//var car_series = "<td>" + car.series + "</td>";//车系
					//var car_vin = "<td>" + car.vin + "</td>";//vin
					//var car_type = "<td>" + car.type + "</td>";//车型
					//var color = "<td>" + car.color + "</td>";//颜色
					// var car_year = "<td>" + car.car_year + "</td>";//年份
					//var config_name = "<td>" + car.config_name + "</td>";
					// var order_type = "<td>" + car.order_type + "</td>";
					//var special_order = "<td>" + car.special_order + "</td>";
					//var remark = "<td>" + car.remark + "</td>";
					//$("#tableCarInfo tbody").append("<tr>" + car_series + car_vin + car_type + color + 
					//	/*car_year +*/ config_name /*+ order_type*/ + special_order + remark + hiddenPlanId + "</tr>");
					//var hiddenPlanId = "<input type='hidden' value='" + car.plan_id + "' />";
					$("#carInfo").attr("planId", car.plan_id);	//added by wujun
					$("#" + car.plan_id).addClass("info");	//added by wujun
				}
				else{
					resetPage();
					fadeMessageAlert(response.message,"alert-error");
				}
			},
			error:function(){alertError();}
		});
	}

	//进入
	function ajaxEnter () {
		//get PlanId to send back 
		//var planId = $("#tableCarInfo tbody").find("input[type='hidden']").val();
		var planId = $("#carInfo").attr("planId");		//added by wujun
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: T0_ENTER_AND_PRINT,//ref:  /bms/js/service.js
			data: {
				"vin": $('#vinText').attr("value"),
				"planId":planId,
				"currentNode": $('#currentNode').attr("value"),
				"line" : $("#line").attr("value"),
			},
			async: false,
			success: function(response) {
				resetPage();
				if(response.success){
				  	$("#vinHint").html(response.data.vinCode + "整车编号" + response.data.serialNumber);	//added by wujun
					//fill data to print
					$(".printVin").html(response.data.vinCode+"["+ response.data.serialNumber +"]");
					$(".printBarCode").attr("src", response.data.vinBarCode);
					$(".printFrontImage").attr("src", response.data.frontImage);
					$(".printBackImage").attr("src", response.data.backImage);
					// $(".printFront2Image").attr("src", response.data.front2Image);
					// $(".printBack2Image").attr("src", response.data.back2Image);
					$(".printType").html(response.data.type);
					$(".printModel").html(response.data.carModel);
					$(".printSeries").html(response.data.series);
					$(".printGlass").html(response.data.sideGlass);
					if(response.data.coldResistant == "1"){
						$(".printConfig").html(response.data.config +'/'+ '耐寒');							
					}else{
						$(".printConfig").html(response.data.config +'/'+ '非耐寒');							
					}
					$(".printSerialNumber").html(response.data.serialNumber);
					$(".printRemark").html("备注：" + response.data.remark);
					if($("#currentNode").val() == 'T0'){
						if (response.data.frontImage == "" || response.data.backImage == "") {
							fadeMessageAlert(response.message + "(配置单图片不完整，无法打印出相应跟单)","alert-info");
						} else {
							// console.log($(".configPaper"))
							$(".configPaper").addClass("toPrint");
							setTimeout(function (){window.print();},1500);
							fadeMessageAlert(response.message,"alert-success");
						}
					} else if($("#currentNode").val() == 'T0_2') {
						$("#M6Glass").addClass("toPrint");
						setTimeout(function (){window.print();},500);
						fadeMessageAlert(response.message,"alert-success");
					}
				}
				else{
					fadeMessageAlert(response.message,"alert-error");
				}

				//added by wujun
				setTimeout(function() {
					$("#vinHint").hide().html("未输入VIN");
					toggleVinHint(true);
				},5000);
			},
			error:function(){alertError();}
		});
	}
	//get the car original data, added by wujun 
	function ajaxGetCar(){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
			async: false,
		    url: GET_CAR,//ref:  /bms/js/service.js
		    data: {"vin": $('#vinText').attr("value")},//vin
		    success: function(){},
		    error:function(){}
       });
	}

	function ajaxConfig () {
		
	}
//-------------------END ajax -----------------------	

//------------------- common functions -----------------------	
	//initialize this page
	/*
		1.add head class and resetPage
		2.resetPage();
		3.hide alert
	*/
	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftNodeSelectLi").addClass("active");
		resetPage();
		$("#messageAlert").hide();

		$("#today").html(workDate());
		$("#tomorrow").html(nextWorkDate());
	}

	/*
		to resetPage:
		1.enable and empty vinText
		2.focus vinText
		3.show vin hint
		4.disable submit
	*/
	function resetPage () {
		//empty vinText
		$("#vinText").removeAttr("disabled");
		$("#vinText").attr("value","");
		//聚焦到vin输入框上
		$("#vinText").focus();
		//to show vin input hint
		toggleVinHint(true);
		//disable submit button
		$("#btnSubmit").attr("disabled","disabled")
		ajaxGetPlan();
		ajaxPlanTomorrow();
		//$(".printable").removeClass("toPrint");		
	}

	//toggle 车辆信息和提示信息
	/*
		@param showVinHint Boolean
		if want to show hint,set to "true"
	*/
	function toggleVinHint (showVinHint) {
		if(showVinHint){
			$("#carInfo").hide();
			$("#vinHint").fadeIn(1000);

		}else{
			$("#vinHint").hide();
			$("#carInfo").fadeIn(1000);
		}
	}

	/*
		fade infomation(error or success)
		fadeout after 5s
		@param message
		@param alertClass 
			value: alert-error or alert-success
	*/
	function fadeMessageAlert(message,alertClass){
		$("#messageAlert").removeClass("alert-error alert-success").addClass(alertClass);
		$("#messageAlert").html(message);
		$("#messageAlert").show(500,function () {
			setTimeout(function() {
				$("#messageAlert").hide(1000);
			},5000);
		});
	}

	//added by wujun
	function currentDate (argument) {
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

	//added by wujun
	function yesterday () {			
			//获取系统时间 
			var now = new Date();
			var nowYear = now.getFullYear();
			var nowMonth = now.getMonth();
			var nowDate = now.getDate();
			//处理
			var uom = new Date(nowYear,nowMonth, nowDate);
			uom.setDate(uom.getDate()-1);//取得系统时间的前一天,重点在这里,负数是前几天,正数是后几天
			var LINT_MM = uom.getMonth();
			LINT_MM++;
			var LSTR_MM = LINT_MM > 10?LINT_MM:("0"+LINT_MM)
			var LINT_DD = uom.getDate();
			var LSTR_DD = LINT_DD > 10?LINT_DD:("0"+LINT_DD)
			//得到最终结果
			uom = uom.getFullYear() + "-" + LSTR_MM + "-" + LSTR_DD; 
			return(uom);
	}

	//added by wujun
	function tomorrowDate () {
			//获取系统时间 
			var now = new Date();
			var nowYear = now.getFullYear();
			var nowMonth = now.getMonth();
			var nowDate = now.getDate();
			//处理
			var uom = new Date(nowYear,nowMonth, nowDate);
			uom.setDate(uom.getDate() + 1);//取得系统时间的前一天,重点在这里,负数是前几天,正数是后几天
			var LINT_MM = uom.getMonth();
			LINT_MM++;
			var LSTR_MM = LINT_MM >= 10?LINT_MM:("0"+LINT_MM)
			var LINT_DD = uom.getDate();
			var LSTR_DD = LINT_DD >= 10?LINT_DD:("0"+LINT_DD)
			//得到最终结果
			uom = uom.getFullYear() + "-" + LSTR_MM + "-" + LSTR_DD; 
			return(uom); 
	}

	//added by wujun
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

	//added by wujun
	function nextWorkDate() {
		var now = new Date();
		var hh = now.getHours();
		var nextWorkDate

		if(hh>=8 && hh<24) {
			nextWorkDate = tomorrowDate();
		} else {
			nextWorkDate = currentDate();
		}

		return nextWorkDate;
	}

//-------------------END common functions -----------------------

//------------------- event bindings -----------------------
	//输入回车，发ajax进行校验；成功则显示并更新车辆信息
	$('#vinText').bind('keydown', function(event) {
		//if vinText disable,stop propogation
		if($(this).attr("disabled") == "disabled")
			return false;
		if (event.keyCode == "13"){
			//remove blanks 
		    if(jQuery.trim($('#vinText').val()) != ""){
				ajaxValidate();
	        }   
		    return false;
		}
	});

	//进入彩车身库事件，发ajax，根据响应做提示
	$("#btnSubmit").click(function() {
		if(!($("#btnSubmit").hasClass("disabled"))){
			$("#btnSubmit").attr("disabled","disabled");
			ajaxEnter();
		}
		return false;
	});

	//清空
	$("#reset").click(function() {
		resetPage();
		return false;
	});
//-------------------END event bindings -----------------------
});
