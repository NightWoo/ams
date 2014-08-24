$("document").ready(function() {
	
	initPage();
//------------------- ajax -----------------------
	//校验
	function ajaxValidate (argument){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: CAR_VALIDATE,//ref:  /bms/js/service.js
		    data: {"vin": $('#vinText').attr("value")},
		    success: function(response) 
		    {
			    if(response.success){
			    	$("#vinText").val(response.data.vin);	
			    	//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
					$("#btnSubmit").removeAttr("disabled").focus();
					//show car infomation
			    	toggleVinHint(false);
			    	//render car info data,include serialNumber,series,type and color
		    		var data = response.data;
		    		$('#serialNumber').html(data.serial_number);
		    	 	$('#series').html(window.byd.SeriesName[data.series]);
			    	$('#color').html(data.color);
				    $('#type').html(data.type);
				    if(data.status && data.status !== "0")
				    	$('#statusInfo').html(data.status);
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

	//进入
	function ajaxEnter() {
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: CONFIGPAPER_MAIN_SUBMIT,//ref:  /bms/js/service.js
			data: {
				"vin": $('#vinText').attr("value"),
				"currentNode": $('#currentNode').attr("value"),
			},
			success: function(response) 
			{
				resetPage();
				if(response.success){
					$("#vinHint").html(response.data.vinCode + "整车编号" + response.data.serialNumber);
					//fill data to print
					$(".printBarCode").attr("src", response.data.vinBarCode);
					$(".printFrontImage").attr("src", response.data.frontImage);
					$(".printBackImage").attr("src", response.data.backImage);
					$(".printFront2Image").attr("src", response.data.front2Image);
					$(".printBack2Image").attr("src", response.data.back2Image);
					// $(".printType").html(response.data.type);
					$(".printSeries").html(response.data.series);
					if(response.data.coldResistant == "1"){
						$(".printType").html(response.data.carModel + "/" + response.data.config + "/" + "耐寒");
						// $(".printConfig").html(response.data.config + "/" + "耐寒");							
					}else{
						$(".printType").html(response.data.carModel + "/" + response.data.config + "/" + "非耐寒");
						// $(".printConfig").html(response.data.config + "/" + "非耐寒");							
					}
					$(".printSerialNumber").html(response.data.serialNumber);
					$(".printRemark").html("备注：" + response.data.remark);
					if (response.data.frontImage == "") {
						fadeMessageAlert(response.message + "(配置单图片不完整，无法打印出相应跟单)","alert-info");
					} else {
						maxPage = 1;
						$(".configPaper[page=1]").addClass("toPrint");
						if(response.data.backImage != ""){
							$(".configPaper[page=2]").addClass("toPrint");
							maxPage = 2;
						}
						if(response.data.front2Image != ""){
							$(".configPaper[page=3]").addClass("toPrint");
							maxPage = 3;
						}
						if(response.data.back2Image != ""){
							$(".configPaper[page=4]").addClass("toPrint");
							maxPage = 4;
						}

						$(".maxPage").html(maxPage);
						setTimeout(function (){window.print();},1500);
						fadeMessageAlert(response.message,"alert-success");
					}
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
		$("#leftConfigPaperMainLi").addClass("active");
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
		$(".printable").removeClass("toPrint");
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
			ajaxEnter();
		}
		return false;
	});

	//清空
	$("#reset").click(function() {
		$("#vinHint").html("请输入VIN后回车");
		resetPage();
		return false;
	});
//-------------------END event bindings -----------------------
});
