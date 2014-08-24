$("document").ready(function() {
	initPage();
	var flag = 0;//等于2的时候开放 路试按钮

//------------------- ajax -----------------------
	//校验
	function ajaxValidateVin (argument){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: RTS_VALIDATE,
		    data: {"vin": $('#vinText').val(),"currentNode":$("#currentNode").attr("value")},
		    success: function(response){
			    if(response.success){
			    	//
			    	flag ++;
			    	$("#inputDriver").removeAttr("disabled");
			    	$("#vinText").val(response.data.vin);		//added by wujun
			    	$("#vinText").attr("disabled","disabled");
					//show car info
			    	toggleVinHint(false);
			    	//render
		    		var car = response.data;
		    		$('#serialNumber').html(car.serial_number);
		    	 	$('#series').html(car.series);
			    	$('#color').html(car.color);
				    $('#type').html(car.type);
				    if(car.status && car.status !== "0")
				    	$('#statusInfo').html(car.status);
				    else
				    	$('#statusInfo').text("");
			    }
			    else{
				    resetPage();
					fadeMessageAlert(response.message,"alert-error");
			    }
		    },
		    error:function(){alertError();}
       });
	}

	//校验
	function ajaxValidateDriver (argument){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: RTS_DRIVER_VALIDATE,
		    data: {card: $('#inputDriver').val()},
		    success: function(response){
			    if(response.success){
			    	flag ++;
			    	if(flag == 2){
			    		//enable submit and disable driver input
			    		$("#btnSubmit").removeAttr("disabled");
				    	$("#inputDriver").attr("disabled","disabled");
				    	//show card info
				    	toggleCardHint(false);
				    	//render driver info
			    		var driver = response.data;
			    	 	$('#driverName').html(driver.display_name);
				    	$('#driverId').html(driver.id);
			    	}
			    }
			    else{
			    	//disable submit and enable driver input
		    		$("#btnSubmit").attr("disabled","disabled");
			    	$("#inputDriver").removeAttr("disabled","disabled");
			    	//hide card info
			    	toggleCardHint(false);
				    fadeMessageAlert(response.message,"alert-error");
			    }
		    },
		    error:function(){alertError();}
        });
	}

	//提交
	function ajaxSubmit (){
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: RTS_SUBMIT,
			data: {vin:$("#vinText").val(),driverId:$('#driverId').html()},
			success: function(response){
				resetPage();
				if(response.success){
				  	fadeMessageAlert(response.message,"alert-success");
				}
				else{
					fadeMessageAlert(response.message,"alert-error");
				}
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
		flag = 0;
		//show card hint and reset driver input
		toggleCardHint(true);
		$("#inputDriver").attr("value","");
		$("#inputDriver").attr("disabled","disabled");
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

	//toggle driver信息和提示信息
	function toggleCardHint (showCarHint) {
		if(showCarHint){
			$("#driverInfo").hide();
			$("#cardHint").fadeIn(1000);
		}else{
			$("#cardHint").hide();
			$("#driverInfo").fadeIn(1000);
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
		        ajaxValidateVin();
	        }   
		    return false;
		}
	});

	//输入回车，发ajax进行校验；成功则显示并更新driver信息
	$('#inputDriver').bind('keydown', function(event) {
		if($(this).attr("disabled") == "disabled")
			return false;
		if (event.keyCode == "13") { //如果是回车
			//remove blanks 
		    if(jQuery.trim($('#inputDriver').val()) != ""){//输入格式正确
		        ajaxValidateDriver();
	        }
		    return false;
		}
	});

	//进入彩车身库事件，发ajax，根据响应做提示
	$("#btnSubmit").click(function() {
		if(!($("#btnSubmit").hasClass("disabled"))){
			$("#btnSubmit").attr("disabled","disabled");
			ajaxSubmit();
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
