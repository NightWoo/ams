$(document).ready(function  () {
	initPage();

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
			    	//send ajax to decide showing bag or not
			    	$.ajax({
			    		type: "get",//使用get方法访问后台
			    	    dataType: "json",//返回json格式的数据
					    url: RTF_GAS_BAG,
					    data: {vin: $('#vinText').val()},
					    success:function (response) {
					    	if(response.data)
					    		$("#formBag").show();
					    }
			    	});//END send ajax to decide showing bag or not

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
	function ajaxGetComponents (compType){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: RTF_GET_FAULT_PARTS + "?category=" + compType,
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
					var optionTd = "<td>" + '<select><option value="">-请选择故障-</option>' + options + "</td>";
					// var checkTd = '<td><input type="checkbox" value=""></td>';
					$("#tableGeneral tbody").append("<tr>" + indexTd + nameTd + optionTd + "</tr>");
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
			data: {"component":text},
			success: function(response){
				if(response.success){
					var tr = $("#tableOther tbody tr").eq(currentOtherFocusIndex);
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
		//init all

		$("#formBag").hide();
		//初始化第一栏
		ajaxGetComponents("VQ2_road_test");
		//初始化  ‘其他’栏
		$("#tableOther tbody").text("");
		for (var i = 0; i < 10; i++) {
			var indexTd = "<td>" + (i + 1) + "</td>";
			var nameTd = "<td><input type='text' /></td>";
			var optionTd = "<td>" + '<select disabled="disabled"><option value="">-请选择故障-</option></select>' + "</td>";
			//注释掉，路试结束没有checkbox
			// var checkTd = '<td><input type="checkbox"  value="" disabled="disabled"></td>';
			$("#tableOther tbody").append("<tr>" + indexTd + nameTd + optionTd  + "</tr>");
			// $("#tableOther tbody").append("<tr>" + indexTd + nameTd + optionTd + checkTd + "</tr>");
		};
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

	//提交
	//构造提交的json，包括以下 vin 和fault，fault如下
	// fault:[{"componentId":1,"faultId":1,"fixed":false},{}]
	$("#btnSubmit").click(function() {		
		//vin号，和故障数组
		var sendData = {};
		sendData.vin = $('#vinText').val();
		sendData.bag = $("#inputBag").val();
		sendData.fault = [];
		console.log($("#tabContent tr").length);
		var selects = $("#tabContent tr select option:selected");

		$.each(selects,function (index,value) {
			if($(value).val() != ""){
				var obj = {};
				obj.faultId = $(value).val();
				console.log($(value).parent().parent().parent().html());
				var tr = $(value).parent().parent().parent();
				console.log($(tr).find("input[type='checkbox']").attr("checked"));
				// obj.fixed = false;
				// if($(tr).find("input[type='checkbox']").attr("checked") == "checked")
				// 	obj.fixed = true;
				obj.componentId = $(tr).find("input[type='hidden']").val();
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

	//自动补全
	$("#tableOther input[type='text']").typeahead({
	    source: function (input, process) {
	    	disableTr(currentOtherFocusIndex);
	        $.get(VQ1_SEARCH_PART, {"component":input}, function (data) {
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
		var select = tr.find("select");
		select.text('');
		select.append('<option value="">-请选择故障-</option>');
		select.attr("disabled","disabled");
	}
	function enableTr (index) {
		var tr = $("#tableOther tbody tr").eq(index);
		var select = tr.find("select");
		select.removeAttr("disabled");
	}

});