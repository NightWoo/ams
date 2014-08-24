$("document").ready(function() {
	
	initPage();
	var compArray = [];
	var recordArray = [];

//------------------- ajax -----------------------	
	//校验
	function ajaxValidate() {
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: T11_F10_GET_PARTS,//ref:  /bms/js/service.js
			data: {
				"vin": $('#vinText').val(),
				"currentNode":$("#nodeSelect").val()
			},
			success: function(response) {
				if(response.success){
					$("#vinText").val(response.data.car.vin).attr("disabled","disabled");
					$("#btnSubmit").removeAttr("disabled");

					//show car infomation
			    	toggleVinHint(false);
			    	$('#compCodeText').removeAttr("disabled").focus();
					var car = response.data.car;
					$('#serialNumber').html(car.serial_number);
					$('#series').html(window.byd.SeriesName[car.series]);
					$('#type').html(car.type);
					$('#config').html(response.data.config);
					if(car.cold_resistant == "1") {
						$('#cold').html("耐寒")
					} else {
						$('#cold').html("非耐寒")
					}
					$('#color').html(car.color);
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
					
				}
				else{		
					resetPage();		
					fadeMessageAlert(response.message,"alert-error");
				}
			},
			error:function(){alertError();}
		});
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
					$("#comp"+compIndex).html(barCode);
					recordArray.push(compIndex);
				}else{
					addCheckMessage(response.message);
				}
			}
		})
	}

	//进入
	function ajaxEnter() {
		var obj = {};
		if(compArray.length > 0){
			for (var i = 0; i < recordArray.length; i++) {
				obj[compArray[recordArray[i]].id] = $("#comp"+recordArray[i]).html();
			};
		}
		var jsonText = JSON.stringify(obj);

		$.ajax({
			type: "post",
        	dataType: "json",
			url: RECORD_BARCODE,//ref:  /bms/js/service.js
			data: {
				"componentCode": jsonText,
				"vin": $('#vinText').val(),
				"currentNode": $("#nodeSelect").attr("value")},
			async: false,
			success: function(response) {
				resetPage();
				if(response.success){
				  	fadeMessageAlert(response.message,"alert-success");
				  	$("#vinHint").html("上一辆" + response.data);
				  	// recordArray = [];
				} else {
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

		if($("#currentNode").val() == "C21" || $("#currentNode").val() == "C21_2") {
			$(".linkCarLabel").show();
		}
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
		$("#vinText").removeAttr("disabled").val("").focus();
		toggleVinHint(true);
		$("#btnSubmit").attr("disabled","disabled");
		$('#compCodeText').val("").attr("disabled","disabled");		//added by wujun
		$("#componentTable tbody").text("");
		recordArray = [];
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
			},10000);
		});
	}

	/*
		validate 
	*/
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
//-------------------END common functions -----------------------

//------------------- event bindings -----------------------
	//输入回车，发ajax进行校验；成功则显示并更新车辆信息
	$('#vinText').bind('keydown', function(event) {
		if($(this).attr("disabled") == "disabled")
			return false;
		if (event.keyCode == "13" || event.keyCode == "10"){
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
			//if scan less than need to,prompt alert
			// if(compArray.length == 0 || (compArray.length != 0 && compArray.length == recordArray.length)){
			// 	ajaxEnter();
			// 	return false;
			// }
			// if(recordArray.length < compArray.length){
			// 	var unRecordComp = "";
			// 	var unRecordCount = 0
			// 	for (var i = 0; i < compArray.length; i++) {
			// 		if(!ifExistInRecordArray(i,recordArray)){
			// 			unRecordComp += "-" + compArray[i].display_name + "-\n";
			// 			++unRecordCount;
			// 		}
			// 	};
			// 	if(unRecordCount != 0){
			// 		var confirmResult = confirm("还有" + unRecordCount + "个零部件未扫描:\n\n" + 
			// 			unRecordComp + "\n请确认是否提交");

			// 		if (confirmResult) {
			// 	    	ajaxEnter();
			// 		}else{
			// 			$("#btnSubmit").removeAttr("disabled");
			// 		}
			// 	}else{
			// 		ajaxEnter();
			// 	}
			// }
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
		if(event.keyCode == "13" || event.keyCode == "10"){
			compText = jQuery.trim($('#compCodeText').val());
			if(compText != ""){
				if(compText.length == 21){
					compText = compText.substr(3);
				}

				var index = getCompIndex(compText);
				if(index != -1){
					if(!$("#comp"+index).html()){
						// barCode = jQuery.trim($(this).val());
						barCode = compText;
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

	
//-------------------END event bindings -----------------------

});
