$(document).ready(function  () {
	//初始化所有
	
	initPage();
//------------------- ajax -----------------------
	//校验
	function ajaxValidate (argument){
		$.ajax
		({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: VQ3_VALIDATE,
		    data: {"vin": $('#vinText').val(),"currentNode":$("#currentNode").attr("value")},
		    success: function(response){
			    if(response.success){
			    	$("#divDetail").data("series", response.data.series);
			    	

			    	$("#divDetail").fadeIn(1000);
			    	$("#vinText").val(response.data.vin);		//added by wujun
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
	//CompArray
	var compArray = [];
	function ajaxGetComponents(){
		$.ajax({
		    type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: VQ3_GET_FAULT_PARTS + "?category=VQ3_facade_test",
		    data: {"series" : $("#divDetail").data("series")},
		    // data: {vin: $('#vinText').val()},
		    success: function(response){
		    	$("#tableAssembly tbody").text("");
		    	$("#tablePaint tbody").text("");
		    	$("#tableBody tbody").text("");
		    	$("#tableMix tbody").text("");
		    	compArray = response.data;
		    	var allComps = [];
				$.each(compArray,function(index,comp){
					var indexTd = "<td>" + (index + 1) + "</td>";
					var nameTd = "<td>" + comp.component_name + "<input type='hidden' name='componentId' value='" + comp.component_id + "' />" + "</td>";
					//record in all Comps
					allComps.push({"component_name":comp.component_name,"component_id":comp.component_id});
					
					// var options = "";
					// $.each(comp.fault_mode,function (ind,value) {
					// 	options += '<option value="' + value.id + '">' + value.mode + '</option>';
					// });
					// var optionTd = "<td>" + '<select name="faultSelect"><option value="">-请选择故障-</option>' + options + "</td>";
					
					var optAssembly = "";
					$.each(comp.assembly_fault_mode,function (ind,value) {
						optAssembly += '<option value="' + value.id + '">' + value.mode + '</option>';
					});
					var optAssemblyTd = "<td>" + '<select name="faultSelect"><option value="">-请选择故障-</option>' + optAssembly + "</td>";

					var optPaint = "";
					$.each(comp.paint_fault_mode,function (ind,value) {
						optPaint += '<option value="' + value.id + '">' + value.mode + '</option>';
					});
					var optPaintTd = "<td>" + '<select name="faultSelect"><option value="">-请选择故障-</option>' + optPaint + "</td>";

					var optBody = "";
					$.each(comp.welding_fault_mode,function (ind,value) {
						optBody += '<option value="' + value.id + '">' + value.mode + '</option>';
					});
					var optBodyTd = "<td>" + '<select name="faultSelect"><option value="">-请选择故障-</option>' + optBody + "</td>";

					// var checkTd = '<td><input type="checkbox" value=""></td>';

					var hiddenAssembly = "<input type='hidden' value='assembly' name='category' />";
					// $("#tableAssembly tbody").append("<tr>" + hiddenAssembly + indexTd + nameTd + optAssemblyTd + checkTd + "</tr>");
					$("#tableAssembly tbody").append("<tr>" + hiddenAssembly + indexTd + nameTd + optAssemblyTd + dutyOption + "</tr>");
					var hiddenPaint = "<input type='hidden' value='paint' name='category' />";
					// $("#tablePaint tbody").append("<tr>" + hiddenPaint + indexTd + nameTd + optPaintTd + checkTd + "</tr>");
					$("#tablePaint tbody").append("<tr>" + hiddenPaint + indexTd + nameTd + optPaintTd + dutyOption + "</tr>");
					var hiddenWelding = "<input type='hidden' value='welding' name='category' />";
					// $("#tableBody tbody").append("<tr>" + hiddenWelding + indexTd + nameTd + optBodyTd + checkTd + "</tr>");
					$("#tableBody tbody").append("<tr>" + hiddenWelding + indexTd + nameTd + optBodyTd + dutyOption + "</tr>");

					
					
					
				});

				//init mix
				for (var i = 0; i <10; i++) {
					var mixIndex = "<td>" + (i + 1) + "</td>";
					var nameOptions = "";
					$.each(allComps,function (ind,value) {
						nameOptions += '<option value="' + value.component_id + '">' + value.component_name + '</option>';
					});
					var mixName = "<td>" + '<select name="compSelect"><option value="">-请选择故障-</option>' + nameOptions + "</select></td>";
					
					var mixOption = "<td>" + '<select name="faultSelect"><option value="">-请选择故障-</option>' +  "</select></td>";
					// var mixCheck = '<td><input type="checkbox" value=""></td>';
					// var mixResp = '<td><div class="btn-group responsibility" data-toggle="buttons-radio"><button class="btn" type="button" value="assembly">总装</button><button class="btn" type="button" value="paint">涂装</button><button class="btn" type="button" value="welding">焊装</button></div></td>';

					// $("#tableMix tbody").append("<tr>" + mixIndex + mixName + mixOption + mixCheck + mixResp + "</tr>");
					$("#tableMix tbody").append("<tr>" + mixIndex + mixName + mixOption + dutyOption + "</tr>");
				};
				// $("#tableMix tbody").find("select[name='faultSelect']").attr("disabled","disabled");
				$("#tableMix tbody").find("select[name='compSelect']").change(function (){
					//当综合中的select框改变时，故障模式加入响应的值
					var index = $("#tableMix tbody tr").index($(this).parent().parent());
					var tr = $("#tableMix tbody tr").eq(index);
					console.log($(this).val());
					var select = tr.find("select[name='faultSelect']");
					console.log($(select).text());

					$(select).text("");
					var componentId = $(this).val();
					if(componentId != ""){
						//重新选择的时候 清空select
						var compIndex = -1;

						$.each(compArray,function(i,val){
							if(val.component_id == componentId){
								compIndex = i;
								return false;
							}
						});
						var options = "";
						$.each(compArray[compIndex].fault_mode,function (ind,value) {
							options += '<option value="' + value.id + '">' + value.mode + '</option>';
							
						});
						var optionTd = '<option value="">-请选择故障模式-</option>' + options ;
						$(select).append(optionTd);
						$(select).removeAttr("disabled");
					}else{
						var optionTd = '<option value="">-请选择故障模式-</option>';
						tr.find("select").append(optionTd);
						$(select).attr("disabled","disabled");
					}
				});
				// //change toggle button color
				// $(".responsibility .btn").click(function(){
				// 	$($(this).siblings()).removeClass("btn-danger");
				// 	$(this).addClass("btn-danger");
				// })	
		    },
		   error:function(){alertError();}
        });
	}
	
	//进入
	function ajaxSubmit (sendData){
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: VQ3_SUBMIT,
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
			error:function(){alertError();}
		});
	}
	var dutyOption = "";
	ajaxDutyList();
	function ajaxDutyList() {
		$.ajax({
			url : QUERY_DUTY_DRPARTMENT,
			dataType : "json",
			data : {"node" : "VQ3"},
			success : function  (response) {
				var options = "";
				$.each(response.data, function(index, value) {
					options += '<option value="' + value.id + '">' + value.name + '</option>';
				});
				dutyOption = "<td>" + '<select class="duty"><option value="">-请选择责任部门-</option>' + options + "</td>";
				ajaxGetComponents();
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
		$("#vinText").attr("value","");
		//聚焦到vin输入框上
		$("#vinText").focus();
		//to show vin input hint
		toggleVinHint(true);
		//disable submit button
		$("#btnSubmit").attr("disabled","disabled");
		//hide detail
		$("#divDetail").hide();
		$("#tableAssembly tbody").text("");
		$("#tablePaint tbody").text("");
		$("#tableBody tbody").text("");
		$("#tableMix tbody").text("");
		
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
		//如果没有选择右侧的toggleButton，不让其提交
		// var flag = false;
		// $.each($(".other tr"),function(index,value){
		// 	var faultId = $(value).find("select[name='faultSelect']").val();
		// 	if(faultId != ""){
		// 		var activeLength = $(value).find(".active").length;
		// 		console.log("activeLength:" + activeLength);
		// 		if(activeLength == 0){
		// 			alert("vq3综合栏中，存在没有选择\"责任部门\"的条目，请选择后再进行提交");
		// 			flag = true;
		// 			return false;
		// 		}
		// 	}
		// });
		// if(flag) return false;//stop from submitting

		//遍历总装
		// var 


		// //vin号，和故障数组
		var sendData = {};
		sendData.vin = $('#vinText').val();
		sendData.fault = [];

		var trs = $("#tabContent tbody").not("[class='other']").find("tr");
		$.each(trs,function (index,value) {
			var faultId = $(value).find("select").val();
			if(faultId != ""){
				var obj = {};
				obj.faultId = faultId;
				obj.fixed = false;
				if($(value).find("input[type='checkbox']").attr("checked") == "checked")
					obj.fixed = true;
				obj.componentId = $(value).find("input[type='hidden'][name='componentId']").val();
				obj.category = $(value).find("input[type='hidden'][name='category']").val();
				console.log(obj);
				sendData.fault.push(obj);
			}
		});

		$.each($(".other tr"),function(index,value){
			var faultId = $(value).find("select[name='faultSelect']").val();
			if(faultId != ""){
				console.log($(value).find("select[name='faultSelect']").html());
				var obj = {};
				obj.faultId = faultId;
				obj.fixed = false;
				if($(value).find("input[type='checkbox']").attr("checked") == "checked")
					obj.fixed = true;
				obj.componentId = $(value).find("select[name='compSelect']").val();
				obj.category = $(value).find(".active").attr("value");
				console.log(obj);
				sendData.fault.push(obj);
			}
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
