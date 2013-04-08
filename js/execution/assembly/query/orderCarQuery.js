$(document).ready(function () {
	initPage();
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
		$("#leftOrderCarQueryLi").addClass("active");
		$("#divInfo").hide();
		$("#resultTable").hide();	//add by wujun
		$("#standbyDate").val(window.byd.DateUtil.currentDate);
	}

	$(".clearinput").click(function() {
		$(this).siblings().filter("input").val("");
	})
	
	$(".resetDate").click(function() {
		$(this).siblings().filter("input").val(window.byd.DateUtil.currentDate);
	})

	$("#btnQuery").click (function () {
		if($.trim($("#orderNumberText").val()) == "" && $.trim($("#standbyDate").val()) == ""){
			alert('至少需要一个查询条件')
		} else {
			$("#divInfo").hide();
			$("#resultTable").hide();
			$("#resultTable tbody").text("");
			ajaxQuery();
		}
		return false;
	});

	$("#orderNumberText").bind("keydown", function (event) {
		if(event.keyCode == '13'){
			if($.trim($("#orderNumberText").val()) != ""){
				if($.trim($("#orderNumberText").val()) == "" && $.trim($("#standbyDate").val()) == ""){
					alert('至少需要一个查询条件')
				} else {
					$("#divInfo").hide();
					$("#resultTable").hide();
					$("#resultTable tbody").text("");
					ajaxQuery();
				}
			}
			return false;
		}	
	});

	function ajaxQuery (argument) {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: QUERY_ORDER_CARS ,//ref:  /bms/js/service.js
		    data: {
		    	"orderNumber": $("#orderNumberText").val(),
		    	"standbyDate":$ ("#standbyDate").val()},
		    success:function (response) {
		    	if(response.success){
		    		var cars = response.data.cars;
		    		$("#distributorInfo").html(response.data.distributor_name);		    	 	
		    		$("#divInfo").fadeIn(500);

		    		$.each(cars ,function (index,value) {
		    			var laneTd = "<td>" + value.lane + "</td>";
		    			var orderNumberTd = "<td>" + value.order_number + "</td>";
		    			var distributorNameTd = "<td>" + value.distributor_name + "</td>";
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var seriesTd = "<td>" + value.series + "</td>";
		    			var typeTd = "<td>" + value.type + "</td>";
		    			var configTd = "<td>" + value.config_name + "</td>";
		    			var coldTd = "<td>" + value.cold + "</td>";
		    			var colorTd = "<td>" + value.color + "</td>";
		    			var engineTd = "<td>" + value.engine_code + "</td>";
		    			var remarkTd = "<td>" + value.remark + "</td>";
		    			

		    			var tr = "<tr>"  + orderNumberTd + laneTd + distributorNameTd + 
		    				vinTd + seriesTd + typeTd + configTd + coldTd + colorTd + engineTd + remarkTd +"</tr>";
		    			$("#resultTable tbody").append(tr);
		    		});
					$("#resultTable").fadeIn(500);	//add by wujun
		    	}else{
		    		$("#orderNumberText").val("");
		    		alert(response.message);

		    	}
		    },
		    error:function(){alertError();}
		});
	}

});
