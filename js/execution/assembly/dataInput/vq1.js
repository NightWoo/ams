$(document).ready(function  () {
	initPage();

//------------------- ajax -----------------------
	//校验
	function ajaxValidate (argument){

		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: VQ1_VALIDATE,//ref:  /bms/js/service.js
		    data: {"vin": $('#vinText').attr("value"),
		    	"currentNode": $('#currentNode').attr("value")},//vin and node
		    success: function(response){
			    if(response.success){
			    	$("#divDetail").data("series", response.data.series);
					ajaxGetComponents("tableEngine", "engine");

			    	$("#divDetail").fadeIn(1000);
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

	//校验
	function ajaxGetComponents (tableId, compType) 
	{
		$.ajax
		({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: VQ1_GET_FAULT_PARTS + "?category=" + compType,
		    data : {"series" : $("#divDetail").data("series")},
		    // data: {vin: $('#vinText').val()},
		    success: function(response){
		    	$("#" + tableId + " tbody").text("");
				$.each(response.data,function(index,comp){
					var indexTd = "<td>" + (index + 1) + "</td>";
					var nameTd = "<td>" + comp.component_name + "<input type='hidden' value='" + comp.component_id + "' />" + "</td>";
					var options = "";
					$.each(comp.fault_mode,function (ind,value) {
						options += '<option value="' + value.id + '">' + value.mode + '</option>';
					});
					var optionTd = "<td>" + '<select class="fault-type"><option value="">-请选择故障-</option>' + options + "</td>";
					var checkTd = '<td><input type="checkbox" value=""></td>';
					$("#" + tableId + " tbody").append("<tr>" + indexTd + nameTd + optionTd + checkTd + dutyOption + "</tr>");
				});
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
			data : {"node" : $("#currentNode").val()},
			success : function  (response) {
				var options = "";
				$.each(response.data, function(index, value) {
					options += '<option value="' + value.id + '">' + value.name + '</option>';
				});
				dutyOption = "<td>" + '<select class="duty"><option value="">-请选择责任部门-</option>' + options + "</td>";
				$("#otherTable tbody").text("");
				//初始化  ‘其他’栏
				for (var i = 0; i < 10; i++) {
					var indexTd = "<td>" + (i + 1) + "</td>";
					var nameTd = "<td><input type='text' /></td>";
					var optionTd = "<td>" + '<select disabled="disabled" class="fault-type"><option value="">-请选择故障-</option></select>' + "</td>";
					var checkTd = '<td><input type="checkbox"  value="" disabled="disabled"></td>';
					$("#otherTable tbody").append("<tr>" + indexTd + nameTd + optionTd + checkTd + dutyOption + "</tr>");
				};
			}
		})
	}
	//进入
	function ajaxSubmit (sendData){
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: VQ1_SUBMIT_FAULT,//ref:  /bms/js/service.js
			data:  sendData,
			success: function(response){
				resetPage();
				if(response.success){
				  	fadeMessageAlert(response.message,"alert-success");
				 //  	$("#messageAlert").removeClass("alert-error").addClass("alert-success");
					// $("#messageAlert").html(response.message);
					// $("#messageAlert").show();
					clearInputs();
				  	// alert('记录提交成功!');
				  	// location.reload();
				}
				else{
					fadeMessageAlert(response.message,"alert-error");
				}
			},
			error:function(){alertError();}
		});
	}

	function clearInputs () {
		$("select").val(0);
		$(":checkbox").removeAttr("checked");
		if (dutyOption != "") {
			$("#otherTable tbody").text("");
			//初始化  ‘其他’栏
			for (var i = 0; i < 10; i++) {
				var indexTd = "<td>" + (i + 1) + "</td>";
				var nameTd = "<td><input type='text' /></td>";
				var optionTd = "<td>" + '<select disabled="disabled" class="fault-type"><option value="">-请选择故障-</option></select>' + "</td>";
				var checkTd = '<td><input type="checkbox"  value="" disabled="disabled"></td>';
				$("#otherTable tbody").append("<tr>" + indexTd + nameTd + optionTd + checkTd + dutyOption + "</tr>");
			};

		}
		
		$("#otherTable input[type='text']").typeahead({
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
		$("#otherTable input[type='text']").focus(function () {
			currentOtherFocusIndex = $("#otherTable tbody tr").index($(this).parent().parent());
		});
	}
	//根据零部件的名字查找故障模式
	function ajaxViewParts (text) 
	{
		$.ajax
		(
		    {
				type: "get",//使用get方法访问后台
	        	dataType: "json",//返回json格式的数据
				url: VQ1_VIEW_PART,
				data: {
					"component":text,
					"series" : $("#divDetail").data("series")
				},
				success: function(response) 
				{
					if(response.success){
						var tr = $("#otherTable tbody tr").eq(currentOtherFocusIndex);
						//重新选择的时候 清空select
						tr.find("select").text("");
						var options = "";
						$.each(response.data.fault_mode,function (ind,value) {
							options += '<option value="' + value.id + '">' + value.mode + '</option>';
							
						});
						var optionTd = '<option value="">-请选择故障-</option>' + options ;
						tr.find("select").append(optionTd);
						enableTr(currentOtherFocusIndex);
					}
					else
						fadeMessageAlert(response.message,"alert-error");
				},
				error:function(){
					
				}
			}
		);
	}
//-------------------END ajax -----------------------


	var clicked = [true,false,false,false,false,true];
	//初始化第一栏
	
	

	$("#otherTable input[type='text']").typeahead({
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

	var currentOtherFocusIndex = -1;
	$("#otherTable input[type='text']").focus(function () {
		currentOtherFocusIndex = $("#otherTable tbody tr").index($(this).parent().parent());
	});

	function disableTr (index) {
		var tr = $("#otherTable tbody tr").eq(index);
		var select = tr.find("select");
		select.text('');
		select.append('<option value="">-请选择故障-</option>');
		select.attr("disabled","disabled");
		tr.find("input[type='checkbox']").attr("disabled","disabled");
	}
	function enableTr (index) {
		var tr = $("#otherTable tbody tr").eq(index);
		var select = tr.find("select");
		select.removeAttr("disabled");
		tr.find("input[type='checkbox']").removeAttr("disabled");
	}


	//监听tab切换事件，去取comp列表
	$("#tabs li").click(function () {
		var index = $('#tabs li').index(this);
		if(!clicked[index]){
			clicked[index] = true;
			if(index == 1)
				ajaxGetComponents("tablePerformance","performance");
			else if(index == 2)
				ajaxGetComponents("tableLeft","left_rear");
			else if(index == 3)
				ajaxGetComponents("tableRight","right_vice");
			else if(index == 4)
				ajaxGetComponents("tableBaggage","luggage");
		}
	});
	// enableButton($("#btnSubmit"));// for test

//------------------- common functions -----------------------	
	//initialize this page
	/*
		1.add head class and resetPage
		2.resetPage();
		3.hide alert
	*/
	function initPage(){
		//hide detail
		$("#divDetail").hide();
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
		$("#divDetail").hide();

		clearInputs();									//added by wujun
		$("#tabs li").removeClass("active");			//added by wujun
		$("#tabs li:first-child").addClass("active");	//added by wujun
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

	//提交
	//构造提交的json，包括以下 vin 和fault，fault如下
	// fault:[{"componentId":1,"faultId":1,"fixed":false},{}]
	$("#btnSubmit").click(function() {	
		if(!($("#btnSubmit").hasClass("disabled"))){	
			//vin号，和故障数组
			var sendData = {};
			sendData.vin = $('#vinText').val();
			sendData.fault = [];
			console.log($("#tabContent tr").length);
			var selects = $("#tabContent tr select").filter(".fault-type");

			$.each(selects,function (index,value) {
				value = $(value).find("option:selected");
				if($(value).val() != ""){
					var obj = {};
					obj.faultId = $(value).val();
					console.log($(value).parent().parent().parent().html());
					var tr = $(value).parent().parent().parent();
					console.log($(tr).find("input[type='checkbox']").attr("checked"));
					obj.fixed = false;
					if($(tr).find("input[type='checkbox']").attr("checked") == "checked")
						obj.fixed = true;
					obj.componentId = $(tr).find("input[type='hidden']").val();

					obj.dutyDepartment = $(tr).find(".duty").val();
					console.log(obj.componentId);
					sendData.fault.push(obj);
				}
			});
			sendData.fault = JSON.stringify(sendData.fault);
			ajaxSubmit(sendData);
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