$("document").ready(function() {
	initPage();
	var compArray = [];
	var recordArray = [];
//------------------- ajax -----------------------
	//校验
	function ajaxValidate (){
		$("#btnClear").attr("disabled", "disabled");
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: SUB_CONFIG_VALIDATE,//ref:  /bms/js/service.js
		    data: {
		    	"vin" : $('#vinText').val(),
		    	"type" : $("#subType").val(),
		    	"currentNode": $("#currentNode").val()
		    },//vin and node
		    success: function(response){
			    if (response.success){
			    	$("#btnSubmit, #btnTopOut, #btnClear").removeAttr("disabled");
			    	car = response.data.car;
				    $(".printFrontImage").attr("src", "");
			    	$("#vinText").val(car.vin);	//added by wujun
			    	//disable vinText and open submit button
			    	$("#vinText").attr("disabled","disabled");
					$("#btnSubmit, #btnTopOut").removeAttr("disabled");
					//show car infomation
			    	toggleVinHint(false);
			    	//render car info data,include series,type and color
		    	 	$('#infoSeries').html(car.series);
				    $('#infoType').html(car.type);
				    $('#infoColor').html(car.color);
				    $('#infoStatus').html(car.status);

				    var comp = response.data.components;
					compArray = comp;//record  components
					$.each(comp,function (index,value) {
						var nameTd = "<td>" + value.display_name+"</td><td id='comp" + index + "'comp>" + value.bar_code + "</td>";
						var hiddenProviderCode = "<input name='provider' type='hidden' value='" + value.provider_code + "' />";
						var hiddenSimpleCode = "<input name='simple' type='hidden' value='" + value.simple_code + "' />";
						$("#componentTable tbody").append("<tr>" + nameTd + hiddenProviderCode +
							hiddenSimpleCode + "</tr>");
					});
					if(compArray.length>0) {
						$("#compCodeText").val("").focus();
					} else {
						$("#btnSubmit").focus();
					}
			    }
			    else{
			    	ajaxGetDoneList()
			    	ajaxGetPrintList();
					fadeMessageAlert(response.message,"alert-error");
				}
		    },
		    error:function(){alertError();}
       });
	}

	//进入
	function ajaxEnter(toPrint){
		if(!isAllRecord()){
			return false;
		}

		var obj = {};
		if(compArray.length > 0){
			for (var i = 0; i < recordArray.length; i++) {
				obj[compArray[recordArray[i]].id] = $("#comp"+recordArray[i]).html();
			};
		}
		barCode = JSON.stringify(obj);

		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: SUB_CONFIG_PRINT,//ref:  /bms/js/service.js
			data: {
				"vin": $('#vinText').attr("value"),
				"type": $("#subType").val(),
				"currentNode": $("#currentNode").val(),
				"barCode" : barCode
			},
			async:false,
		  cache:false,
			success: function(response){
				//fill data to print
				$(".printBarCode").attr("src", response.data.vinBarCode);
				// $(".printFrontImage").attr("src", response.data.image);
				// $(".printBackImage").attr("src", response.data.image);
				$(".printEngineCode").html(response.data.engineCode);
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
					if (response.data.image == "") {
						fadeMessageAlert(response.message + "(配置单图片不完整，无法打印出相应跟单)","alert-info");
					} else {
						// var _img = document.getElementById('id1');
						var newImg = new Image();
						newImg.src = response.data.image;
						newImg.onload = function() {
					    $(".printFrontImage").src = this.src;
						    // window.print();
							setTimeout(function (){window.print();},2000);
						}
						fadeMessageAlert(response.message,"alert-success");
					}
				} else {
					fadeMessageAlert(response.message,"alert-success");
				}
				resetPage();
				ajaxGetDoneList()
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
					"top" :50,
		    },//vin
		    async:false,
		    success: function(response){
		    	$("#tableList tbody").text("");
		    	$("#infoCount").html(response.data.countAll);
		    	$(response.data.datas).each(function (index, value) {
		    		var tr = $("<tr />");
		    		if (index == 0) {
		    			tr.addClass("info");
		    		}
		    		$("<td />").html(byd.SeriesName[value.series]).appendTo(tr);
		    		$("<td />").html(value.serial_number).appendTo(tr);
		    		$("<td />").html(value.queueTime).appendTo(tr);
		    		$("<td />").html(value.vin).appendTo(tr);
		    		$("<td />").html(value.type_name + '/' + value.config_name).appendTo(tr);
		    		$("<td />").html(value.cold).appendTo(tr);
		    		$("<td />").html(value.color).appendTo(tr);
		    		$("<td />").html(value.special_order).appendTo(tr);
		    		$("<td />").html(value.remark).appendTo(tr);
		    		tr.appendTo($("#tableList tbody"));
		    	});
		    	setFirstCarToPrint();
		    },
		    error:function(){}
       });
	}

	function ajaxGetDoneList() {
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: SUB_CONFIG_SEARCH,//ref:  /bms/js/service.js
		    data: {
		    	"type": $("#subType").val(),
		    	"stime":$("#startTime").val(),
				"etime":$("#endTime").val(),
				"status": 1,
				"top" :50,
				"sortType": "DESC",
		    },//vin
		    async:false,
		    success: function(response){
		    	$("#tableDoneList tbody").text("");
		    	$(response.data.datas).each(function (index, value) {
		    		var tr = $("<tr />");
		    		$("<td />").html(byd.SeriesName[value.series]).appendTo(tr);
		    		$("<td />").html(value.serial_number).appendTo(tr);
		    		$("<td />").html(value.queueTime).appendTo(tr);
		    		$("<td />").html(value.vin).appendTo(tr);
		    		$("<td />").html(value.type_name + '/' + value.config_name).appendTo(tr);
						$("<td />").html(value.cold).appendTo(tr);
		    		$("<td />").html(value.color).appendTo(tr);
		    		$("<td />").html(value.special_order).appendTo(tr);
		    		$("<td />").html(value.remark).appendTo(tr);
		    		$("<td />").html(value.engine_code).appendTo(tr);
		    		a = "<a><i class='fa fa-print'></i></a>"
		    		$("<td />").html(a).appendTo(tr);
		    		tr.appendTo($("#tableDoneList tbody"));

		    		tr.data("vin", value.vin);
		    		//after fetch,set the 1st car to the print place

		    	});
		    },
		    error:function(){}
       });
	}

	function ajaxPrintOne(vin){

		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: SUB_CONFIG_PRINT,//ref:  /bms/js/service.js
			data: {
				"vin": vin,
				"type": $("#subType").val(),
			},
			async:false,
		   cache:false,
			success: function(response){
				//fill data to print
				$(".printBarCode").attr("src", response.data.vinBarCode);
				$(".printFrontImage").attr("src", response.data.image);
				// $(".printBackImage").attr("src", response.data.image);
				$(".printEngineCode").html(response.data.engineCode);
				$(".printType").html(response.data.type);
				$(".printSeries").html(response.data.series);
				if(response.data.coldResistant == "1"){
					$(".printConfig").html(response.data.config +'/'+ '耐寒');
				}else{
					$(".printConfig").html(response.data.config +'/'+ '非耐寒');
				}
				$(".printSerialNumber").html(response.data.serialNumber);
				$(".printRemark").html("备注：" + response.data.remark);
				if (response.data.image == "") {
					fadeMessageAlert(response.message + "(配置单图片不完整，无法打印出相应跟单)","alert-info");
				} else {
					setTimeout(function (){window.print();},800);
					fadeMessageAlert(response.message,"alert-success");
				}
				resetPage();
				ajaxGetDoneList()
				ajaxGetPrintList();
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

	function isAllRecord () {
		if(compArray.length == 0 || (compArray.length != 0 && compArray.length == recordArray.length)){
			return true;
		}

		if(recordArray.length < compArray.length){
			var unRecordComp = "";
			var unRecordCount = 0
			for (var i = 0; i < compArray.length; i++) {
				if(!ifExistInRecordArray(i,recordArray)){
					unRecordComp += "-" + compArray[i].display_name + "-\n";
					++unRecordCount;
				}
			};
			if(unRecordCount != 0){
				alert("还有" + unRecordCount + "个零部件未扫描:\n\n" +
					unRecordComp + "\n无法提交");
				$("#compCodeText").val("").focus();
				return false;
			}else{
				return true;
			}
		}
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
		ajaxGetDoneList();
		ajaxGetPrintList();

		resetPage();
		if($("#currentNode").val() == "subEngine") {
			$("#formBarCode").show();
		}

	}

	function resetPage () {
		toggleVinHint(true);
		$("#componentTable tbody").text("");
		compArray = [];
		recordArray = [];
		$("#btnSubmit, #btnTopOut").attr("disabled", "disabled");
	}

	function setFirstCarToPrint (argument) {
		if ($("#tableList tr").length > 1) {
			$("#vinText").attr("disabled", "disabled");
			//获取第一行的VIN号
			$("#vinText").attr("value", $("#tableList tr:eq(1) td:eq(3)").text());
			ajaxValidate();
			// $("#btnSubmit, #btnTopOut").removeAttr("disabled");
		} else {
			$("#vinText").removeAttr("disabled");
			$("#vinText").attr("value","");
			$("#btnSubmit, #btnTopOut").attr("disabled", "disabled");
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
		    if($.trim($('#vinText').val()) != ""){
				ajaxValidate();
	        }
		    return false;
		}
	});

	//进入彩车身库事件，发ajax，根据响应做提示
	$("#btnSubmit").click(function() {
		if(!($("#btnSubmit, #btnTopOut").hasClass("disabled"))){
			// $("#btnSubmit, #btnTopOut").attr("disabled","disabled");
			ajaxEnter(true);
		}
		return false;
	});

	$("#btnTopOut").click(function() {
		if(!($("#btnTopOut, #btnSubmit").hasClass("disabled"))){
			// $("#btnTopOut, #btnSubmit").attr("disabled","disabled");
			ajaxEnter(false);
		}
		return false;
	});

	$("#btnRefresh").live("click", function () {
		resetPage();
		ajaxGetDoneList()
		ajaxGetPrintList();
	});
	//清空
	$("#btnClear").click(function() {
		$("#btnSubmit, #btnTopOut").attr("disabled","disabled");
		$("#vinText").removeAttr("disabled");
		$("#vinText").attr("value","");
		$("#tableList tr:eq(1)").removeClass("info");
		resetPage();
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

	$("#tableDoneList").on("click", "a", function (e) {
		vin = $(e.target).closest("tr").data("vin");
		ajaxPrintOne(vin);
	})

//-------------------END event bindings -----------------------

});
