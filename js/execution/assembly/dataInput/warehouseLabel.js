$(document).ready(function() {
	initPage();

	$("#reset").click(function() {
		resetPage();
		return false;
	});

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

	$("#btnSubmit").click(function() {
		if(!($("#btnSubmit").hasClass("disabled"))){
			$("#btnSubmit").attr("disabled","disabled");
			ajaxSubmit();
		}
		return false;
	});

	function ajaxValidate (argument){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: WAREHOUSE_LABEL_VALIDATE,
		    data: {"vin": $('#vinText').val()},
		    success: function(response){
			    if(response.success){
			    	$("#vinText").val(response.data.vin);	//added by wujun
					//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
			    	// $("#cardText").removeAttr("disabled").focus();
					$("#btnSubmit").removeAttr("disabled").focus();
					
			    	//render car info data,include serialNumber,series,type and color
		    		var car = response.data;
		    		$('#serialNumber').html(car.serial_number);
		    	 	$('#series').html(byd.SeriesName[car.series]);
			    	$('#color').html(car.color);
				    $('#type').html(car.type);
				    if(car.status && car.status !== "0"){
				    	$('#statusInfo').html(car.status);
				    } else {
				    	$('#statusInfo').html("");
				    }
				    if(car.distributor_name){
					    $("#distributorInfo").html(car.distributor_name);
				    } else {
				    	$("#distributorInfo").html("");
				    }
				    if(car.status.indexOf('公司外')>0 || car.distribute_time > '0000-00-00 00:00:00'){
				    	isOut = true;
				    }
					//show car infomation
			    	toggleVinHint(false);

				    // if(data.status && data.status !== "0")
				    // 	$('#statusInfo').html(data.status);
				    // else
				    // 	$('#statusInfo').text("");
			    }
			    else{
				    resetPage();
					fadeMessageAlert(response.message,"alert-error");
			    }
		    },
		    error:function(){alertError();}
       });
	}

	function ajaxSubmit (){
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: WAREHOUSE_LEBEL_PRINT,
			data: {
				"vin": $("#vinText").val(),
			},
			async:false,
			success: function(response){
				if(response.success){
					var data = response.data;
					// $(".nowTime").html("备车"+nowTime());
					$("#rowPrint").html(data.row + data.driver_name);
					$("#vinPrint").html(data.vin + '-' + data.color);
					$("#orderNumberPrint").html(data.order_number);
					$("#distributorPrint").html(data.distributor_name);
					$("#lanePrint").html(data.lane + "道");
				  	setTimeout(function (){window.print();},500);
					fadeMessageAlert(response.message,"alert-success");
					resetPage();
				}
				else{
					resetPage();
					fadeMessageAlert(response.message,"alert-error");
				}
			},
			error:function(){alertError();}
		});
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

	

	function initPage() {
		$("#headAssemblyLi").addClass("active");
		$("#leftNodeSelectLi").addClass("active");
		toggleVinHint(true);
		resetPage();
		$("#messageAlert").hide();
	}

	function resetPage() {
		$("#vinText").removeAttr("disabled");
		$("#vinText").attr("value","");
		$("#cardText").attr("value", "").attr("cardid", "").attr("disabled", "disabled");
		//聚焦到vin输入框上
		$("#vinText").focus();
		//to show vin input hint
		toggleVinHint(true);
		//disable submit button
		$("#btnSubmit").attr("disabled","disabled");
		$("#driver").html("司机")
	}

	function toggleVinHint (showVinHint) {
		if(showVinHint){
			$("#carInfo").hide();
			$("#vinHint").fadeIn(1000);

		}else{
			$("#vinHint").hide();
			$("#carInfo").fadeIn(1000);
		}
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