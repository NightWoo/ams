$("document").ready(function() {
	initPage();

//------------------- ajax -----------------------
	//校验
	function ajaxValidate (){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: SUB_CONFIG_VALIDATE,//ref:  /bms/js/service.js
		    data: {"vin" : $('#vinText').attr("value"),
		    	"type" : $("#subType").val()},//vin and node
		    success: function(response){
			    if (response.success){
			    	$("#vinText").val(response.data.vin);	//added by wujun
			    	//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
					$("#btnSubmit, #btnTopOut").removeAttr("disabled");
					//show car infomation
			    	toggleVinHint(false);
			    	//render car info data,include series,type and color
		    		var data = response.data;
		    	 	$('#infoSeries').html(data.series);
				    $('#infoType').html(data.type);
				    $('#infoColor').html(data.color);
				    if(data.status && data.status !== "0")
				    	$('#infoStatus').html(data.status);
				    else
				    	$('#infoStatus').text("");
			    }
			    else{
			    	ajaxGetPrintList();
					fadeMessageAlert(response.message,"alert-error");
				}
		    },
		    error:function(){alertError();}
       });
	}

	//进入
	function ajaxEnter(toPrint){
		// toPrint = arguments[0] ? arguments[0] : false;
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: SUB_CONFIG_PRINT,//ref:  /bms/js/service.js
			data: {"vin": $('#vinText').attr("value"),
				"type": $("#subType").val()},
			async:false,
			success: function(response){
				// resetPage();
				//fill data to print
				$(".printBarCode").attr("src", response.data.vinBarCode);
				$(".printFrontImage").attr("src", response.data.image);
				$(".printBackImage").attr("src", response.data.image);
				$(".printType").html(response.data.type);
				$(".printSeries").html(response.data.series);
				if(response.data.coldResistant == "1"){
					$(".printConfig").html(response.data.config +'/'+ '耐寒');							
				}else{
					$(".printConfig").html(response.data.config +'/'+ '非耐寒');							
				}
				$(".printSerialNumber").html(response.data.serialNumber);
				$(".printRemark").html("备注：" + response.data.remark);
				if(toPrint){
					if (response.data.frontImage == "" || response.data.backImage == "") {
						fadeMessageAlert(response.message + "(配置单图片不完整，无法打印出相应跟单)","alert-info");
					} else {
						setTimeout(function (){window.print();},800);
						fadeMessageAlert(response.message,"alert-success");
					}
				} else {
					fadeMessageAlert(response.message,"alert-success");
				}
				ajaxGetPrintList();
			},
			error:function(){alertError();}
		});
	}
	

	//get the car orig
	function ajaxGetPrintList(){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: SUB_CONFIG_SEARCH,//ref:  /bms/js/service.js
		    data: {
		    	"type": $("#subType").val(),
		    	"stime":$("#startTime").val(),
				"etime":$("#endTime").val(),
				"top" :20,
		    },//vin
		    async:false,
		    success: function(response){
		    	$("#tableList tbody").text("");
		    	$(response.data).each(function (index, value) {
		    		var tr = $("<tr />");
		    		if (index == 0) {
		    			tr.addClass("info");
		    		}
		    		$("<td />").html(value.serial_number).appendTo(tr);
		    		$("<td />").html(value.queueTime).appendTo(tr);
		    		$("<td />").html(value.vin).appendTo(tr);
		    		$("<td />").html(value.series).appendTo(tr);
		    		$("<td />").html(value.type_name + '/' + value.config_name).appendTo(tr);
		    		if(value.cold_resistant == "1"){
						$("<td />").html('耐寒').appendTo(tr);						
					}else{
						$("<td />").html('非耐寒').appendTo(tr);						
					}
		    		$("<td />").html(value.color).appendTo(tr);
		    		// $("<td />").html(value.year).appendTo(tr);
		    		// $("<td />").html(value.order_type).appendTo(tr);
		    		$("<td />").html(value.special_order).appendTo(tr);
		    		$("<td />").html(value.remark).appendTo(tr);
		    		tr.appendTo($("#tableList tbody"));

		    		//after fetch,set the 1st car to the print place
		    		
		    	});
		    	setFirstCarToPrint();
		    },
		    error:function(){}
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
		$("#headEfficiencyLi").addClass("active");
		$("#leftNodeSelectLi").addClass("active");
		
		toggleVinHint(true);

		//hide alert
		$("#messageAlert").hide();

		$("#tableList tbody").text("");
		//set default queue time and get queue
		// $("#startTime").val(window.byd.DateUtil.todayBeginTime);
		$("#startTime").val(byd.DateUtil.firstDayOfTheMonth);
		$("#endTime").val(byd.DateUtil.todayEndTime);
		ajaxGetPrintList();

	}

	function setFirstCarToPrint (argument) {
		
		if ($("#tableList tr").length > 1) {
			$("#vinText").attr("disabled", "disabled");
			//获取第一行的VIN号
			$("#vinText").attr("value", $("#tableList tr:eq(1) td:eq(2)").text());
			$("#btnSubmit, #btnTopOut").removeAttr("disabled");
		} else {
			$("#vinText").removeAttr("disabled");
			$("#vinText").attr("value","");
			$("#btnSubmit, #btnTopOut").attr("disabled", "disabled");
		}
		
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
		// $("#vinText").removeAttr("disabled");
		// $("#vinText").attr("value","");
		// //聚焦到vin输入框上
		// $("#vinText").focus();
		// //to show vin input hint
		// toggleVinHint(true);
		//disable submit button
		// $("#btnSubmit").attr("disabled","disabled");
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
		if(!($("#btnSubmit, #btnTopOut").hasClass("disabled"))){
			$("#btnSubmit, #btnTopOut").attr("disabled","disabled");
			ajaxEnter(true);
		}
		return false;
	});

	$("#btnTopOut").click(function() {
		if(!($("#btnTopOut, #btnSubmit").hasClass("disabled"))){
			$("#btnTopOut, #btnSubmit").attr("disabled","disabled");
			ajaxEnter(false);
		}
		return false;
	});

	$("#btnRefresh").live("click", function () {
		ajaxGetPrintList();
	});
	//清空
	$("#btnClear").click(function() {
		$("#btnSubmit, #btnTopOut").attr("disabled","disabled");
		$("#vinText").removeAttr("disabled");
		$("#vinText").attr("value","");
		$("#tableList tr:eq(1)").removeClass("info");
		return false;
	});
//-------------------END event bindings -----------------------
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

});
