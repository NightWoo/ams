$("document").ready(function() {
	
	initPage();
	var compArray = [];
	var recordArray = [];
	console.log($("#currentNode").attr("value"));
	//$("#myNode").html($("#currentNode").attr("value"));

//------------------- ajax -----------------------	
	//校验
	function ajaxValidate() {
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: T11_F10_GET_PARTS,//ref:  /bms/js/service.js
			data: {"vin": $('#vinText').attr("value"),"currentNode":$("#currentNode").attr("value")},
			success: function(response) {
				if(response.success){
					$("#vinText").val(response.data.car.vin);	//added by wujun
					//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
					$("#btnSubmit").removeAttr("disabled");
					//show car infomation
			    	toggleVinHint(false);
			    	//focus compCodeText
			    	$('#compCodeText').removeAttr("disabled");	//added by wujun
			    	$("#compCodeText").focus();
			    	//render car info data,include series,type and color
					var car = response.data.car;
					$('#serialNumber').html(car.serial_number);
					$('#series').html(car.series);
					$('#type').html(car.type);
					$('#config').html(response.data.config);
					if(car.cold_resistant == "1") {
							$('#cold').html("耐寒")
						} else {
							$('#cold').html("非耐寒")
						}
					$('#color').html(car.color);
					if(car.status && car.status !== "0")
				    	$('#statusInfo').html(car.status);
				    else
				    	$('#statusInfo').text("");
				    
					var comp = response.data.components;
					compArray = comp;//record  components
					$.each(comp,function (index,value) {
						var nameTd = "<td>" + value.display_name+"</td><td id='comp" + index + "'comp>" + value.bar_code + "</td>";
						var hiddenProviderCode = "<input name='provider' type='hidden' value='" + value.provider_code + "' />";
						var hiddenSimpleCode = "<input name='simple' type='hidden' value='" + value.simple_code + "' />";
						$("#componentTable tbody").append("<tr>" + nameTd + hiddenProviderCode + 
							hiddenSimpleCode + "</tr>");
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

	//进入
	function ajaxEnter() {
		var obj = {};
		if(compArray.length > 0){
			for (var i = 0; i < recordArray.length; i++) {
				// console.log(recordArray[i]);
				// console.log(compArray[recordArray[i]].id + "#" + $("#comp"+recordArray[i]).html());
				obj[compArray[recordArray[i]].id] = $("#comp"+recordArray[i]).html();
			};
		}
		var jsonText = JSON.stringify(obj);
		console.log(jsonText);

		$.ajax({
			type: "post",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: T11_F10_SUBMIT_PARTS,//ref:  /bms/js/service.js
			data: {"componentCode":jsonText,vin: $('#vinText').val(),currentNode:$("#currentNode").attr("value")},
			success: function(response) {
				resetPage();
				if(response.success){
				  	fadeMessageAlert(response.message,"alert-success");
				  	$("#vinHint").html("上一辆" + response.data);	//added by wujun
				  	recordArray = [];		//added by wujun
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
		5.clear componentText
		6.clear componentTable
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
		// clear compCodeText
		$('#compCodeText').val("").attr("disabled","disabled");		//added by wujun
		//$('#compCodeText').attr("disabled","disabled");
		// clear componentTable
		$("#componentTable tbody").text("");
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

	/*
		validate 
	*/
	function getCompIndex (compCode) {
		console.log(compCode);
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
			simpleCode = compCode.substring(6,9);
			providerCode = compCode.substring(0,6);
		}else{//特殊零部件
			if(compCode.length == 16)
				simpleCode = compCode.substring(0,7);
			else if(compCode.length == 15)
				simpleCode = compCode.substring(0,3);
			else if(compCode.length == 3)
				simpleCode = compCode;
		}

		console.log("simpleCode:" + simpleCode);
		for (var i = 0; i < compArray.length; i++) {
			//判断规则
			if(compArray[i].simple_code == simpleCode){
				console.log("match" + i);
				if(compArray[i].provider_code == "" ||
					compArray[i].provider_code == providerCode)
					return i;
			}
				
		};
		return -1;
	}

	/*
	*/
	function ifExistInRecordArray (index,recordArray) {
		for (var i = 0; i < recordArray.length; i++) {
			if(recordArray[i] == index)
				return true;
		};
		return false;
	}
//-------------------END common functions -----------------------

//------------------- event bindings -----------------------
	//输入回车，发ajax进行校验；成功则显示并更新车辆信息
	$('#vinText').bind('keydown', function(event) {
		//if vinText disable,stop propogation
		if($(this).attr("disabled") == "disabled")
			return false;

		//debug回车换行wujun
		//if(event.keyCode == "79") {
		//	event.keyCode = "10"
		//}

		//modified by wujun
		if (event.keyCode == "13" || event.keyCode == "10"){
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
			//if scan less than need to,prompt alert
			if(compArray.length == 0 || (compArray.length != 0 && compArray.length == compArray.length)){
				ajaxEnter();
				return false;
			}
			if(recordArray.length < compArray.length){
				var unRecordComp = "";
				for (var i = 0; i < compArray.length; i++) {
					if(!ifExistInRecordArray(i,recordArray)){
						unRecordComp += compArray[i].display_name + "\n";
					}
				};
				var confirmResult = confirm("已扫描描零部件" + recordArray.length + "个" +
					"\n需扫描零部件" + compArray.length + "个\n未扫零部件有:\n" + 
						unRecordComp + "请确认是否提交");
				if (confirmResult) {
				    ajaxEnter();
				}else{
					$("#btnSubmit").removeAttr("disabled");
				}
			}
		}
		return false;
	});

	//清空
	$("#reset").click(function() {
		resetPage();
		return false;
	});

	//
	$('#compCodeText').bind('keypress',function (event) {
		
		//debug回车换行wujun
		//if(event.keyCode <= "48" && event.keyCode != "13" && event.keyCode != "8" && event.keyCode != "48") {
		//	event.keyCode = "0";
		//}

		//modified by wujun
		if(event.keyCode == "13" || event.keyCode == "10"){
			
			console.log($('#compCodeText').val());
			var index = getCompIndex(jQuery.trim($('#compCodeText').val()));	//modified by wujun
			if(index != -1){
				// $("#comp"+index).html(compArray[index].simple_code);
				//not simple_code above,just things inputed
				$("#comp"+index).html(jQuery.trim($(this).val()));	//modified by wujun
				recordArray.push(index);
			}
			$(this).val("");
			return false;
		}
	});
//-------------------END event bindings -----------------------

});
