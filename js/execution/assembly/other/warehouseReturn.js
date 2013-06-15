$("document").ready(function() {
	initPage();
	var isOut = false;
//------------------- ajax -----------------------
	//校验
	function ajaxValidate (argument){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: WAREHOUSE_RETURN_VALIDATE,		
		    data: {
		    	"vin": $('#vinText').val(),
		    	"currentNode": "WAREHOUSE_RETURN",		
		    },	
		    success: function(response){
			    if(response.success){
			    	$("#vinText").val(response.data.vin);
					//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
					$(".btnSubmit, #remark").removeAttr("disabled");
					//show car infomation
			    	toggleVinHint(false);
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


			    } else {
				    resetPage();
					fadeMessageAlert(response.message,"alert-error");
			    }
		    },
		    error: function(){alertError();}
       });
	}

	//提交
	function ajaxSubmit (goTo){
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: WAREHOUSE_RETURN_SUBMIT,
			data: {
				"vin": $("#vinText").val(),
				"goTo": goTo,
				"remark": $("#remark").val(),
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
		$("#headPlanLi").addClass("active");
		$("#leftWarehouseReturnLi").addClass("active");
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
		isOut = false;
		$("#vinText").removeAttr("disabled").attr("value","").focus();
		$("#remark").val("").attr("disabled","disabled");
		toggleVinHint(true);
		$(".btnSubmit").attr("disabled","disabled");
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

	$(".btnSubmit").click(function() {
		if(!($("#btnSubmit").hasClass("disabled"))){
			goTo = $(this).val();
			if($.trim($("#remark").val()) === ""){
				alert("必须注明退回原因");
				return false;
			}
			if(isOut){
				if(confirm('此车已出库，是否坚持要返回['+ goTo +']，并释放订单？')){
					ajaxSubmit(goTo);
				}
			} else {
				ajaxSubmit(goTo);
			}
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
