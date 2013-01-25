$("document").ready(function() {
	
	initPage();
//------------------- ajax -----------------------
	//校验
	function ajaxValidate (argument){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: F20_GET_INFO,//ref:  /bms/js/service.js
		    data: {"vin": $('#vinText').attr("value"),
		    	"currentNode": $('#currentNode').attr("value")},//vin and node
		    success: function(response) 
		    {
			    if(response.success){
			    	$("#vinText").val(response.data.vin);	//added by wujun
			    	//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
					$("#btnSubmit").removeAttr("disabled");
					//show car infomation
			    	toggleVinHint(false);
			    	//render car info data,include serialNumber,series,type and color
		    		var data = response.data;
		    		$('#serialNumber').html(data.serial_number);
		    	 	$('#series').html(data.series);
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
			url: F20_PRINT_CHECK_LIST,//ref:  /bms/js/service.js
			data: {"vin": $('#vinText').attr("value")},
			success: function(response) 
			{
				resetPage();
				if(response.success){
					var data = response.data;
					$("#printSerialNumber").html(data.serialNumber);
					$("#printDate").html(data.date);
					$("#printCarType").html(data.type);
					$("#printColor").html(data.color);
					$("#printMemo").html(data.remark);
					$("#vinBarcode").attr("src",data.vinBarCode);
					$("#engineBarcode").attr("src",data.engineBarCode);
					//$("#vinBarcode").html(data.vinCode);
					//$("#engineBarcode").html(data.engineCode);

					// var theLocation = top.location.href;
					// var locations = theLocation.split("/");
				 //  	console.log("response.data: http://" + locations[2] + '/' + locations[3] + '/' + response.data);
				 //  	window.open("http://" + locations[2] + '/' + locations[3] + '/' + response.data);
				 	//while(!($("#vinBarcode").readyState==="complete" && $("#engineBarcode").readyState==="complete")) {
					//	;
					//}
					setTimeout(function (){window.print();},500);
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
		$("#btnSubmit").attr("disabled","disabled")
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
