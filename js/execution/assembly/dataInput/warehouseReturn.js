$("document").ready(function() {
	initPage();
//------------------- ajax -----------------------
	//校验
	function ajaxValidate (argument){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: CHECKOUT_VALIDATE,		//借用出库的校验
		    data: {
		    	"vin": $('#vinText').val(),
		    	"currentNode": "CHECK_OUT",		//借用出库的校验
		    },	
		    success: function(response){
			    if(response.success){
			    	$("#vinText").val(response.data.vin);
					//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
					$("#btnSubmit").removeAttr("disabled");
					$("#releaseOnly").removeAttr("disabled");
					//show car infomation
			    	toggleVinHint(false);
			    	//render car info data,include serialNumber,series,type and color
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
		    error: function(){alertError();}
       });
	}

	//提交
	function ajaxSubmit (releaseOnly){
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: ORDER_HOLD_RELEASE,
			data: {
				"vin": $("#vinText").val(),
				"releaseOnly": releaseOnly || 0,
			},
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
		$("#releaseOnly").attr("disabled","disabled");
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
			},60000);
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
			$("#releaseOnly").attr("disabled", "disabled");
			ajaxSubmit();
		}
		return false;
	});

	$("#releaseOnly").click(function() {
		if(!($("#releaseOnly").hasClass("disabled"))){
			$("#btnSubmit").attr("disabled","disabled");
			$("#releaseOnly").attr("disabled", "disabled");
			ajaxSubmit(1);
		}
	})

	//清空
	$("#reset").click(function() {
		resetPage();
		return false;
	});
//-------------------END event bindings -----------------------
});
