$(document).ready(function  () {
	initPage();
	var compArray = [];
	var recordArray = [];

//------------------- ajax -----------------------
	//校验
	function ajaxValidate (argument){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: RTF_VALIDATE,
		    data: {"vin": $('#vinText').val(),"currentNode":$("#currentNode").attr("value")},
		    success: function(response){
			    if(response.success){
		    		 var car = response.data.car;
			    	$("#divDetail").data("series", response.data.series);
			    	ajaxDutyList();
			    	//send ajax to decide showing bag or not
			    	$("#divDetail").fadeIn(1000);
			    	$("#vinText").val(car.vin);	//added by wujun
			    	//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
			    	$("#cardText").removeAttr("disabled");
			    	$("#inputTemperature").removeAttr("disabled").focus();
					// $("#driver").removeAttr("disabled");
					//show car infomation
			    	toggleVinHint(false);
			    	//render car info data,include serialNumber,series,type and color
		    		 $('#serialNumber').html(car.serial_number);
		    	 	 $('#series').html(byd.SeriesName[car.series]);
			    	 $('#color').html(car.color);
				     $('#type').html(car.type);
				     if(car.status && car.status !== "0") {
				    	$('#statusInfo').html(car.status);
				     } else {
				    	$('#statusInfo').text("");
				     }

				    var comp = response.data.components;
					compArray = comp;//record  components
					$.each(comp,function (index,value) {
						var nameTd = "<td>" + value.display_name+"</td><td id='comp" + index + "'comp>" + value.bar_code + "</td>";
						var hiddenProviderCode = "<input name='provider' type='hidden' value='" + value.provider_code + "' />";
						var hiddenSimpleCode = "<input name='simple' type='hidden' value='" + value.simple_code + "' />";
						$("#componentTable tbody").append("<tr>" + nameTd + hiddenProviderCode + 
							hiddenSimpleCode + "</tr>");
					});

				     $.ajax({
			    		type: "get",//使用get方法访问后台
			    	    dataType: "json",//返回json格式的数据
					    url: CHECK_CONFIG_LIST,
					    data: {"vin": $('#vinText').val(),"currentNode":$("#currentNode").attr("value")},
					    success:function (response) {
					    	if(response.data) {
					    		$("#formBarCode").show();
					    	}
					    }
			    	});//END send ajax to decide showing bag or not
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
		    url: RTF_GET_FAULT_PARTS + "?category=" + compType,
		    data: {"series" : $("#divDetail").data("series")},
		    // data: {vin: $('#vinText').val()},
		    success: function(response){
		    	$("#tableGeneral tbody").text("");
				$.each(response.data,function(index,comp){
					var indexTd = "<td>" + (index + 1) + "</td>";
					var nameTd = "<td>" + comp.component_name + "<input type='hidden' value='" + comp.component_id + "' />" + "</td>";
					var options = "";
					$.each(comp.fault_mode,function (ind,value) {
						options += '<option value="' + value.id + '">' + value.mode + '</option>';
					});
					var optionTd = "<td>" + '<select class="fault-type"><option value="">-请选择故障-</option>' + options + "</td>";
					// var checkTd = '<td><input type="checkbox" value=""></td>';
					$("#tableGeneral tbody").append("<tr>" + indexTd + nameTd + optionTd + dutyOption + "</tr>");
				});
		    },
		    error:function(){alertError();}
        });
	}

	function ajaxCheckCard(point) {
		$.ajax({
			url: CHECK_CARD_NUMBER,
			type: "get",
			dataType: "json",
			data: {
				"point": "VQ2",
				"cardNumber" : $("#cardText").val()
			},
			async: false,
			success: function (response) {
				if(response.success){
					driver = response.data;
					$("#cardText").attr("value", driver.card_number).attr("cardid", driver.user_id).attr("disabled", "disabled");
					// $("#driver").html(driver.name);
					ajaxSubmit();
				}else{
					$("#cardText").attr("value", "").attr("cardid", "").removeAttr("disabled").focus();
					fadeMessageAlert(response.message, 'alert-error');
				}
			},
			error: function(){alertError();}
		});
	}

	//进入
	function ajaxSubmit (){
		temperature = $.trim($("#inputTemperature").val());
		// if(temperature == "" || !(/^\d*$/.test(temperature))){
		if(temperature == "" || !(temperature>0 && temperature<40)){
			$("#cardText").attr("value", "").attr("cardid", 0).removeAttr("disabled");
			$("#inputTemperature").removeAttr("disabled").val("").focus();
			alert("必须输入空调温度，且填写的必须是小于40的整数");
			return false;
		}

		//vin号，和故障数组
		var sendData = {};
		sendData.vin = $('#vinText').val();
		sendData.bag = $("#inputBag").val();
		// sendData.driver = $("#driver").val();
		sendData.driver = $("#cardText").attr("cardid");
		sendData.temperature = $.trim($("#inputTemperature").val());
		sendData.fault = [];
		var selects = $("#tabContent tr select").filter(".fault-type");

		$.each(selects,function (index,value) {
			value = $(value).find("option:selected");
			if($(value).val() != ""){
				var obj = {};
				obj.faultId = $(value).val();
				var tr = $(value).parent().parent().parent();
				// obj.fixed = false;
				// if($(tr).find("input[type='checkbox']").attr("checked") == "checked")
				// 	obj.fixed = true;
				obj.componentId = $(tr).find("input[type='hidden']").val();
				obj.dutyDepartment = $(tr).find(".duty").val();
				sendData.fault.push(obj);
			}
		})
		sendData.fault = JSON.stringify(sendData.fault);

		var obj = {};
		if(compArray.length > 0){
			for (var i = 0; i < recordArray.length; i++) {
				obj[compArray[recordArray[i]].id] = $("#comp"+recordArray[i]).html();
			};
		}
		sendData.barCode = JSON.stringify(obj);

		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: RTF_SUBMIT,
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
			data: {"component":text,
					"series" : $("#divDetail").data("series")},
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
	//ajaxDutyList();
	function ajaxDutyList() {
		$.ajax({
			url : QUERY_DUTY_DEPARTMENT,
			dataType : "json",
			data : {"node" : "VQ2"},
			success : function  (response) {
				var options = "";
				$.each(response.data, function(index, value) {
					options += '<option value="' + value.id + '">' + value.name + '</option>';
				});
				dutyOption = "<td>" + '<select class="duty"><option value="">-请选择责任部门-</option>' + options + "</td>";
				$("#tableOther tbody").text("");
				//初始化  ‘其他’栏
				for (var i = 0; i < 10; i++) {
					var indexTd = "<td>" + (i + 1) + "</td>";
					var nameTd = "<td><input type='text' /></td>";
					var optionTd = "<td>" + '<select disabled="disabled" class="fault-type"><option value="">-请选择故障-</option></select>' + "</td>";
					var checkTd = '<td><input type="checkbox"  value="" disabled="disabled"></td>';
					$("#tableOther tbody").append("<tr>" + indexTd + nameTd + optionTd  + dutyOption + "</tr>");
				};
				//初始化第一栏
				ajaxGetComponents("VQ2_road_test");
				$("#tableOther input[type='text']").typeahead({
				    source: function (input, process) {
				    	disableTr(currentOtherFocusIndex);
				        $.get(VQ1_SEARCH_PART, {"component":input,"series":$("#divDetail").data("series")}, function (data) {
				        	return process(data.data);
				        },'json');
				    },
				    updater:function (item) {
				     	ajaxViewParts(item);//根据part的名字查找故障模式
			        	return item;
			    	}
				});

				currentOtherFocusIndex = -1;
				$("#tableOther input[type='text']").focus(function () {
					currentOtherFocusIndex = $("#tableOther tbody tr").index($(this).parent().parent());
				});
			}
		})
	}

	function ajaxValidateBarCode (compIndex, barCode) {
		$.ajax({
			url: T11_F10_VALIDATE_BAR_CODE,
			type: "get",
			dataType: "json",
			data: {
				"vin" : $('#vinText').attr("value"),
				"componentId" : compArray[compIndex].id,
				"barCode" : barCode,
			},
			async:false,
			success: function (response) {
				if(response.success){
					$("#comp"+compIndex).html(barCode);	//modified by wujun
					recordArray.push(compIndex);
				}else{
					addCheckMessage(response.message);
				}
			}
		})
	}

	function getCompIndex (compCode) {
		var simpleCode = "";
		var providerCode = "";
		if(compCode.length == 17){//零部件代码为 6-8位
			//len 17 may be an engine
			if(compCode.substring(0,3) == 'BYD')
				simpleCode = compCode.substring(0,8);
			else{
				simpleCode = compCode.substring(5,8);
				providerCode = compCode.substring(0,5);
			}
		}else if(compCode.length == 18){//零部件代码为 7-9位
			//len 18 may be an DongAN gearbox
			if(compCode.substring(0,5) == 'F4A4B'){
				simpleCode = compCode.substring(0,5)
			} else if(compCode.substring(0,9) == 'BYD476ZQA'){ //len 18 may be an 475ZQA engine
				simpleCode = compCode.substring(0,9)
			} else{
				simpleCode = compCode.substring(6,9);
				providerCode = compCode.substring(0,6);
			}
		}else{//特殊零部件
			if(compCode.length == 16)
				simpleCode = compCode.substring(0,7);
			else if(compCode.length == 15)	//liandian ECU
				simpleCode = compCode.substring(0,3);
			else if(compCode.length == 3 || compCode.length == 6)
				simpleCode = compCode;
			else if(compCode.length == 14)	//4G69 engine
				simpleCode = compCode.substring(0,4);
		}

		for (var i = 0; i < compArray.length; i++) {
			//判断规则
			if(compArray[i].simple_code == simpleCode){
				if(compArray[i].provider_code == "" ||
					compArray[i].provider_code == providerCode)
					return i;
			}
				
		};
		message = compCode + "不是本工位扫描零部件条码";
		addCheckMessage(message);

		return -1;
	}

	/*
	*/
	function ifExistInRecordArray (index,recordArray) {
		for (var i = 0; i < compArray.length; i++) {
			//if(recordArray[i] == index)
			if($("#comp"+index).html())
				return true;
		};
		return false;
	}

	function addCheckMessage (message) {

		checkMessage  = "<div class='alert alert-error fade in'>";
		checkMessage += "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
		checkMessage += "<strong>注意！</strong>";
		checkMessage += message;
		checkMessage += "</div>";

		$("#checkAlert").prepend(checkMessage);
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
		$("#vinText, #driver").attr("value","");
		//聚焦到vin输入框上
		$("#vinText").focus();
		//to show vin input hint
		toggleVinHint(true);
		//disable submit button
		// $("#btnSubmit, #driver").attr("disabled","disabled");
		$("#cardText").attr("value", "").attr("cardid", "").attr("disabled", "disabled");
		$("#divDetail").hide();
		//init all

		$("#formBarCode").hide();
		$("#componentTable tbody").text("");
		recordArray = [];

		$("#inputTemperature").val("");
		if (dutyOption != "") {
			//初始化  ‘其他’栏
			$("#tableOther tbody").text("");
			for (var i = 0; i < 10; i++) {
				var indexTd = "<td>" + (i + 1) + "</td>";
				var nameTd = "<td><input type='text' /></td>";
				var optionTd = "<td>" + '<select disabled="disabled" class="fault-type"><option value="">-请选择故障-</option></select>' + "</td>";
				//注释掉，路试结束没有checkbox
				// var checkTd = '<td><input type="checkbox"  value="" disabled="disabled"></td>';
				$("#tableOther tbody").append("<tr>" + indexTd + nameTd + optionTd  + dutyOption + "</tr>");
				// $("#tableOther tbody").append("<tr>" + indexTd + nameTd + optionTd + checkTd + "</tr>");
			};
		}
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

	$('#inputBag').bind('keydown', function(event) {
		if (event.keyCode == "13"){
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

	$("#inputTemperature").bind('keydown', function(event) {
		if($(this).attr("disabled") == "disabled")
			return false;
		if(event.keyCode == "13"){
			if(jQuery.trim($("#inputTemperature").val()) != ""){
				$("#cardText").attr("value", "").attr("cardid", "").focus();
			}
			return false;
		}
	});

	// $('#driver').change(function(){
	// 	if($('#driver').val() === ''){
	// 		$('#btnSubmit').attr('disabled', 'disabled');
	// 	} else {
	// 		$('#btnSubmit').removeAttr('disabled');
	// 	}
	// })

	//提交
	//构造提交的json，包括以下 vin 和fault，fault如下
	// fault:[{"componentId":1,"faultId":1,"fixed":false},{}]
	$("#btnSubmit").click(function() {
		ajaxSubmit();
		return false;
	});

	$('#compCodeText').bind('keypress',function (event) {
		if(event.keyCode == "13" || event.keyCode == "10"){
			compText = jQuery.trim($('#compCodeText').val());
			if(compText != ""){
				if(compText.length == 21){
					compText = compText.substr(3);
				}

				var index = getCompIndex(compText);
				if(index != -1){
					if(!$("#comp"+index).html()){
						barCode = jQuery.trim($(this).val());
						ajaxValidateBarCode(index, barCode);
					}else{
						message = "此车辆已记录" + compArray[index].name + "条码：" + $("#comp"+index).html()
						addCheckMessage(message);
					}
				}
				$(this).val("");
				if(compArray.length == recordArray.length){
					$("#btnSubmit").focus();
				}
			}	
			return false;
		}
	});


	//清空
	$("#reset").click(function() {
		resetPage();
		return false;
	});
//-------------------END event bindings -----------------------

	//自动补全
	$("#tableOther input[type='text']").typeahead({
	    source: function (input, process) {
	    	disableTr(currentOtherFocusIndex);
	        $.get(VQ1_SEARCH_PART, {"component":input,"series" : $("#divDetail").data("series")}, function (data) {
	        	return process(data.data);
	        },'json');
	    },
	    updater:function (item) {
	     	ajaxViewParts(item);//根据part的名字查找故障模式
        	return item;
    	}
	});

	var currentOtherFocusIndex = -1;
	$("#tableOther input[type='text']").focus(function () {
		currentOtherFocusIndex = $("#tableOther tbody tr").index($(this).parent().parent());
	});

	function disableTr (index) {
		var tr = $("#tableOther tbody tr").eq(index);
		var select = tr.find("select").filter(".fault-type");
		select.text('');
		select.append('<option value="">-请选择故障-</option>');
		select.attr("disabled","disabled");
	}
	function enableTr (index) {
		var tr = $("#tableOther tbody tr").eq(index);
		var select = tr.find("select").filter(".fault-type");
		select.removeAttr("disabled");
	}

});