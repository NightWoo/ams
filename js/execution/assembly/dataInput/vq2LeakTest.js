$(document).ready(function  () {
	
	initPage();

//------------------- ajax -----------------------
	//校验
	function ajaxValidate (argument){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: LEAK_VALIDATE,
		    data: {"vin": $('#vinText').val(),"currentNode":$("#currentNode").attr("value")},
		    success: function(response){
			    if(response.success){
			    	$("#divDetail").data("series", response.data.series);
			    	

			    	$("#divDetail").fadeIn(1000);
			    	$("#vinText").val(response.data.vin)	//added by wujun
			    	//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
					$("#driver").removeAttr("disabled");
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

	//校验
	function ajaxGetComponents (compType){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: LEAK_GET_FAULT_PARTS + "?category=" + compType,
		    data: {"series" : $("#divDetail").data("series")},
		    // data: {vin: $('#vinText').val()},
		    success: function(response){
		    	$("#tableGeneral tbody").text("");
				$.each(response.data,function(index,comp){
					var indexTd = "<td>" + (index + 1) + "</td>";
					var checkTd = "<td> <input type='checkbox' value='" + comp.fault_id + "'/> </td>";
					var nameTd = "<td>" + comp.component_name + "<input type='hidden' value='" + comp.component_id + "' />" + "</td>";
					
					// var checkTd = '<td><input type="checkbox" value=""></td>';
					$("#tableGeneral tbody").append("<tr>" + indexTd + checkTd + nameTd + dutyOption + "</tr>");


				});
		    },
		     error:function(){alertError();}
        });
	}

	//进入
	function ajaxSubmit (sendData){
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: LEAK_SUBMIT,
			data:  sendData,
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

	//根据零部件的名字查找故障模式
	function ajaxViewParts (text){
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: VQ1_VIEW_PART,
			data: {"component":text},
			success: function(response){
				if(response.success){
					var tr = $("#tableOther tbody tr").eq(currentOtherFocusIndex);
					//重新选择的时候 清空select
					tr.find("select").filter(".fault-type").text("");
					var options = "";
					$.each(response.data.fault_mode,function (ind,value) {
						options += '<option value="' + value.id + '">' + value.mode + '</option>';
						
					});
					var optionTd = '<option value="">-请选择故障-</option>' + options ;
					tr.find("select").filter(".fault-type").append(optionTd);
					enableTr(currentOtherFocusIndex);
				}
				else
					fadeMessageAlert(response.message,"alert-error");
			},
			error:function(){alertError();}
		});
	}

	var dutyOption = "";
	ajaxDutyList();
	function ajaxDutyList() {
		$.ajax({
			url : QUERY_DUTY_DRPARTMENT,
			dataType : "json",
			data : {"node" : "VQ2"},
			success : function  (response) {
				var options = "";
				$.each(response.data, function(index, value) {
					options += '<option value="' + value.id + '">' + value.name + '</option>';
				});
				dutyOption = "<td>" + '<select class="duty"><option value="">-请选择责任部门-</option>' + options + "</td>";
				//初始化第一栏
					ajaxGetComponents("VQ2_leak_test");
			}
		})
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
		$("#vinText, #driver").attr("value","");
		//聚焦到vin输入框上
		$("#vinText").focus();
		//to show vin input hint
		toggleVinHint(true);
		//disable submit button
		$("#btnSubmit, #driver").attr("disabled","disabled");


		$("#tableGeneral input[type='checkbox']").removeAttr("checked");
		
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

	$('#driver').change(function(){
		if($('#driver').val() === ''){
			$('#btnSubmit').attr('disabled', 'disabled');
		} else {
			$('#btnSubmit').removeAttr('disabled');
		}
	})

	//提交
	//构造提交的json，包括以下 vin 和fault，fault如下
	// fault:[{"componentId":1,"faultId":1,"fixed":false},{}]
	$("#btnSubmit").click(function() {		
		//vin号，和故障数组
		var sendData = {};
		sendData.vin = $('#vinText').val();
		sendData.driver = $('#driver').val();
		sendData.fault = [];
		var selects = $("#tableGeneral tbody tr");

		$.each(selects,function (index,value) {
			if($(value).find("input[type='checkbox']").attr("checked") == "checked"){
				var obj = {};
				obj.faultId = $(value).find("input[type='checkbox']").attr("value");
				obj.componentId = $(value).find("input[type='hidden']").val();
				obj.dutyDepartment = $(value).find(".duty").val();
				console.log(obj.componentId);
				sendData.fault.push(obj);
			}
			
		})
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