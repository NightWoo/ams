$("document").ready(function() {
	initPage();
//------------------- ajax -----------------------
	//校验
	function ajaxValidate (argument){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: CHECKIN_VALIDATE,
		    data: {
		    	"vin": $('#vinText').val(),
		    	"currentNode":$("#currentNode").attr("value")},
		    success: function(response){
			    if(response.success){
			    	$("#vinText").val(response.data.vin);
					//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
			    	$("#cardText").removeAttr("disabled").focus();
					$("#btnSubmit").removeAttr("disabled");
					//show car infomation
			    	toggleVinHint(false);
			    	//render car info data,include serialNumber,series,type and color
		    		var car = response.data;
		    		$('#serialNumber').html(car.serial_number);
		    	 	$('#series').html(byd.SeriesName[car.series]);
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

	function ajaxCheckCard() {
		$.ajax({
			url: CHECK_CARD_NUMBER,
			type: "get",
			dataType: "json",
			data: {
				"cardNumber" : $("#cardText").val()
			},
			async: false,
			success: function (response) {
				if(response.success){
					driver = response.data;
					$("#cardText").attr("value", driver.card_number).attr("cardid", driver.user_id).attr("disabled", "disabled");
					$("#driver").html(driver.name);
					ajaxSubmit();
				}else{
					resetPage();
					fadeMessageAlert(response.message, 'alert-error');
				}
			},
			error: function(){alertError();}
		});
	}

	//提交
	function ajaxSubmit (){
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: WAREHOUSE_RELOCATE,
			data: {
				"vin": $("#vinText").val(),
				"driverId": $("#cardText").attr("cardid"),
			},
			async:false,
			success: function(response){
				if(response.success){
					$(".nowTime").html("入库"+nowTime());
					$("#rowPrint").html(response.data.row);
					$("#vinPrint").html(response.data.vin);
					$("#distributorPrint").html(response.data.distributorName);
					$("#orderNumberPrint").html(response.data.orderNumber);
					$("#lanePrint").html(response.data.lane + "道" + byd.DateUtil.currentTime());
				  	fadeMessageAlert(response.message,"alert-success");
				  	fadeMessageRow(response.data.row,"alert-success");
				  	setTimeout(function (){window.print();},500);
					resetPage();
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
		$("#messageAlert, #messageRow").hide();
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
		$("#cardText").attr("value", "").attr("cardid", "").attr("disabled", "disabled");
		//聚焦到vin输入框上
		$("#vinText").focus();
		//to show vin input hint
		toggleVinHint(true);
		//disable submit button
		$("#btnSubmit").attr("disabled","disabled");
		$(".nowTime").html("入库"+nowTime());
		$("#driver").html("司机")
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

	function fadeMessageRow(message,alertClass){
		$("#messageRow").removeClass("alert-error alert-success").addClass(alertClass);
		$("#messageRow").html("<b class='text-error'>" + message + "<b>");
		$("#messageRow").show(500,function () {
			setTimeout(function() {
				$("#messageRow").hide(1000);
			},60000);
		});
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

	$("#cardText").bind('keydown', function(event) {
		if($(this).attr("disabled") == "disabled")
			return false;
		if(event.keyCode == "13"){
			if(jQuery.trim($("#cardText").val()) != ""){
				ajaxCheckCard();
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
