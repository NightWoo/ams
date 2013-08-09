$("document").ready(function() {
	initPage();

//------------------- ajax -----------------------
	function ajaxGetPlan (table,date) {
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: T0_GET_PLAN,//ref:  /bms/js/service.js
			data: {
				"plan_date": date || byd.DateUtil.workDate,
				"assembly_line" : $("#line").attr("value"),
			},
			success: function(response) {
				if(response.success){
					var plans = response.data;
					var totalAll = 0;
					var readyAll = 0;
					var leftAll = 0;
					$(table + ">tbody").html("");		//added by wujun
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
						$(table + ">tbody").append("<tr id='" + plans[i].id + "'>" + num + left + car_series + car_type + config_name + cold_resistant + color + car_year + /*order_type +*/ special_order + remark + "</tr>");
						if(plans[i].is_frozen == 1){
							$("#"+plans[i].id).addClass("warning");
						}
					});
					$("#planDiv").show();
					if(table=="#planTable"){
						$("#infoCount").html(leftAll +" = " + totalAll + " - " + readyAll );
					}
				}
				else{
					
				}
			},
			error:function(){alertError();}
		});
	}

	//校验
	function ajaxValidate (){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: PBS_VALIDATE,//ref:  /bms/js/service.js
		    data: {
		    	"vin": $('#vinText').attr("value"),
				"line" : $("#line").attr("value"),
		    },
		    success: function(response){
			    if(response.success){
			    	$("#vinText").val(response.data.vin);	//added by wujun
			    	//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
					$("#btnSubmit").removeAttr("disabled");
					//show car infomation
			    	toggleVinHint(false);
			    	//render car info data,include series,type and color
		    		var car = response.data;
				 	$("#infoSeries").html(byd.SeriesName[car.series]);
				 	$("#infoType").html(car.type);
				 	$("#infoColor").html(car.color);
				 	$("#infoStatus").html(car.status);
				 	if(car.cold_resistant == "1"){
				 		$("#infoColdResistant").html("耐寒")
				 	} else {
				 		$("#infoColdResistant").html("非耐寒")
				 	}
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
	function ajaxEnter(){
		var planId = $("#carInfo").attr("planId");
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: PBS_ENTER_WORKSHOP,//ref:  /bms/js/service.js
			data: {
				"vin": $('#vinText').attr("value"),
				"planId": planId,
				"currentNode": $('#currentNode').attr("value"),
				"line" : $("#line").attr("value"),
			},
			success: function(response){
				resetPage();
				if(response.success){
					$("#vinHint").html("上一辆：" + response.data);
				  	fadeMessageAlert(response.message,"alert-success");
				}
				else{
					fadeMessageAlert(response.message,"alert-error");
				}

				setTimeout(function() {
					$("#vinHint").hide().html("未输入VIN");
					toggleVinHint(true);
				},60000);
			},
			error:function(){alertError();}
		});
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
		$("#today").html(byd.DateUtil.workDate);
		$("#tomorrow").html(byd.DateUtil.nextWorkDate);
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
		$("#btnSubmit").attr("disabled","disabled");
		ajaxGetPlan("#planTable", byd.DateUtil.workDate);
		ajaxGetPlan("#planTomorrow", byd.DateUtil.nextWorkDate);
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
