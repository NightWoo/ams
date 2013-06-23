$("document").ready(function() {
	initPage();

//------------------- ajax -----------------------
	//校验
	function ajaxValidate (argument){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: LEAK_SHOW_EXCEPTION,
		    data: {"vin": $('#vinText').val(),"currentNode":$("#currentNode").attr("value")},
		    success: function(response){
			    if(response.success){
			    	$("#divDetail").fadeIn(1000);
			    	$("#vinText").val(response.data.car.vin);		//added by wujun
			    	//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
					$("#btnSubmit").removeAttr("disabled");
					//show car infomation
			    	toggleVinHint(false);
			    	//render car info data,include serialNumber,series,type and color
		    		var car = response.data.car;
		    		$('#serialNumber').html(car.serial_number);
		    	 	$('#series').html(byd.SeriesName[car.series]);
			    	$('#color').html(car.color);
				    $('#type').html(car.type);
				    if(car.status && car.status !== "0")
				    	$('#statusInfo').html(car.status);
				    else
				    	$('#statusInfo').text("");
				    //遍历拿到的json，拼html,塞到table中
				    $("#tableConfirmation tbody").text("");//清除之前的东东
				    $.each(response.data.faults,function(index,comp){
						var tr = $("<tr />");
				    	$("<td />").html(index + 1).appendTo(tr);
				    	$("<td />").html("<input type='checkbox' value=''>").appendTo(tr);
						var hiddenInputs = "<input name='componentId' type='hidden' value='" + comp.component_id + "' />" +"<input name='faultId' type='hidden' value='" + comp.fault_id + "' />"
				    	$("<td />").html(comp.component_name + comp.fault_mode + hiddenInputs).appendTo(tr);
						var duty = $(ajaxDutyList());
						duty.val(comp.duty_department_id);
						var dutyTd = $("<td />").append(duty)
			 			dutyTd.appendTo(tr);
						console.log(dutyTd);

						$("<td />").html(comp.display_name).appendTo(tr);
						$("<td />").html(comp.create_time).appendTo(tr);

						tr.data("componentId", comp.component_id);
						tr.data("faultId", comp.fault_id);
						tr.data("dutyDepartment", comp.duty_department_id);

						$("#tableConfirmation tbody").append(tr);
					});
			    }
			    else{
				    resetPage();
					fadeMessageAlert(response.message,"alert-error");
			    }
		    },
		    error:function(){alertError();}
       });
	}

	//提交修复的零部件
	// 	param:  sendData->
	//				{vin:vin,fault:[{fixed:true,faultId:xxx,componentId:xxx},{}]}
	function ajaxSubmit (sendData) {
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: LEAK_SUBMIT_EXCEPTION,
			data: sendData,
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
		$("#tableConfirmation tbody").text("");
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

	function ajaxDutyList() {
		var dutyOption = '<select class="duty input-medium"><option value="">-请选择责任部门-</option>';
		$.ajax({
			url: QUERY_DUTY_DEPARTMENT,
			dataType: "json",
			data: {"node" : 'VQ2'},
			async: false,
			success: function (response) {
				var options = '';
				$.each(response.data, function(index, value) {
						options += '<option value="' + value.id + '">' + value.name + '</option>';
				});
				
				dutyOption += options;
			},
			error: function () {
				alertError();
			}
		})
		return dutyOption;
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

	//全选，清空
	$("#btnPickAll").click(function () {
		var checkedBoxes = $("#tableConfirmation input").not(':checked');
		$.each(checkedBoxes,function  (index,value) {
			$(value).attr("checked","checked");
		})
	});
	$("#btnPickNone").click(function () {
		var checkedBoxes = $("#tableConfirmation input:checked");
		$.each(checkedBoxes,function  (index,value) {
			$(value).removeAttr("checked");
		})
	});

	//进入彩车身库事件，发ajax，根据响应做提示
	$("#btnSubmit").click(function() {
		var flag = false;
		$.each($(".tableFault tr"), function (index, value){
			fixed = $(value).find("input[type='checkbox']").attr("checked") == "checked" ? true : false;
			unDuty = $(value).find(".duty").val()=="" ? true : false;
			if(fixed && unDuty){
				alert("所有故障均需选择\"责任部门\"，请确认选择后再进行提交");
				flag = true;
				return false;
			}
		})
		if(flag) return false; //stop from submitting
		
		var sendData = {};
		sendData.vin = $("#vinText").val();
		sendData.fault = [];
		//遍历tr，将故障id，零部件id整到  data中来
		var trs = $("#tableConfirmation tbody tr");
		$.each(trs,function (index,value) {
			var obj = {};
			obj.fixed = false;
			if($(value).find("input[type='checkbox']").attr("checked") == "checked")
				obj.fixed = true;
			// obj.faultId = $(value).find("input[type='hidden'][name='faultId']").val();
			obj.faultId = $(value).data("faultId");
			// obj.componentId = $(value).find("input[type='hidden'][name='componentId']").val();
			obj.componentId = $(value).data("componentId");
			obj.dutyDepartment = $(value).data("dutyDepartment");
			obj.newDutyDepartment = $(value).find(".duty").val();
			sendData.fault.push(obj);
		});
		sendData.fault = JSON.stringify(sendData.fault);
		ajaxSubmit(sendData);
		return false;
	});

	//清空
	$("#reset").click(function() {
		resetPage();
		return false;
	});
//-------------------END event bindings -----------------------
});
