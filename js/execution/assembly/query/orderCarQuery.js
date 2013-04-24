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
		$("#tableOrderCars, #tableOrderDetail").hide();	//add by wujun
		$("#standbyDate").val(window.byd.DateUtil.currentDate);
	}

	$(".clearinput").click(function() {
		$(this).siblings().filter("input").val("");
	})
	
	$(".resetDate").click(function() {
		$(this).siblings().filter("input").val(window.byd.DateUtil.currentTime);
	})


	$("#tabs li").click(function () {
		var index = $("#tabs li").index(this);
		if(index<3)
			$("#paginationCars").hide();
		if (index == 0)
			ajaxQueryCars(1);
		else if (index === 1){
			standbyDate = $.trim($("#standbyDate").val());
			orderNumber = $.trim($("#orderNumberText").val());
			distributor = $.trim($("#distributorText").val());
			if(standbyDate == "" && orderNumber =="" && distributor =="")
				alert("除车系外至少要有1个查询条件")
			ajaxQueryOrder();
		}
	});

	//car pagination
	$("#preCars").click(
		function (){
			if(parseInt($("#curCars").attr("page")) > 1){
				$("#tableCars tbody").html("");
				ajaxQueryCars(parseInt($("#curCars").attr("page")) - 1);
			}
		}
	);

	$("#nextCars").click(
		function (){
			if(parseInt($("#curCars").attr("page")) * 20 < parseInt($("#totalCars").attr("total")) ){
			$("#tableCars tbody").html("");
			ajaxQueryCars(parseInt($("#curCars").attr("page")) + 1);
		}
		}
	);

	$("#firstCars").click(
		function () {
			if(parseInt($("#curCars").attr("page")) > 1){
				$("#tableCars tbody").html("");
				ajaxQueryCars(parseInt(1));
			}
		}
	);

	$("#lastCars").click(
		function () {
			if(parseInt($("#curCars").attr("page")) * 20 < parseInt($("#totalCars").attr("total")) ){
				$("#tableCars tbody").html("");
				totalPage = parseInt($("#totalCars").attr("total"))%20 === 0 ? parseInt($("#totalCars").attr("total"))/20 : parseInt($("#totalCars").attr("total"))/20 + 1;
				ajaxQueryCars(parseInt(totalPage));
			}
		}
	)

	$("#exportCars").click(
		function () {
			ajaxExportNodeTrace ();
			return false;
		}
	);

	function ajaxQueryCars (targetPage) {
		$("#tableOrderCars>tbody").html("");
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: QUERY_ORDER_CARS ,//ref:  /bms/js/service.js
		    data: {
		    	"orderNumber": $("#orderNumberText").val(),
		    	"standbyDate":$ ("#standbyDate").val(),
		    	"distributor": $("#distributorText").val(),
		    	"series" : $("#selectSeries").val(),
		    	"status" : getStatusChecked(),
		    	"perPage":20,
				"curPage":targetPage || 1,
		    },
		    success:function (response) {
		    	if(response.success){
		    		var cars = response.data.data;

		    		$.each(cars ,function (index,value) {
		    			var orderNumberTd = "<td>" + value.order_number + "</td>";
		    			var laneTd = "<td>" + value.lane + "</td>";
		    			var distributorNameTd = "<td>" + value.distributor_name + "</td>";
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var seriesTd = "<td>" + value.series + "</td>";
		    			var colorTd = "<td>" + value.color + "</td>";
		    			var configTd = "<td>" + value.config_name + "</td>";
		    			var coldTd = "<td>" + value.cold + "</td>";
		    			if(value.distribute_time == '0000-00-00 00:00:00'){
			    			var distributeTimeTd = "<td>" + '未出库' + "</td>";
		    			}else{
			    			var distributeTimeTd = "<td>" + value.distribute_time + "</td>";
		    			}
		    			var engineTd = "<td>" + value.engine_code + "</td>";
		    			var rowTd = "<td>" + value.row + "</td>";

		    			var tr = "<tr>"  + laneTd + orderNumberTd  + distributorNameTd + 
		    				vinTd + seriesTd + colorTd + configTd + coldTd + distributeTimeTd + engineTd + rowTd +"</tr>";
		    			$("#tableOrderCars tbody").append(tr);
		    		});
					if(response.data.pager.curPage == 1) {
		    			//$(".prePage").hide();
							$("#preCars, #firstCars").addClass("disabled");
							$("#preCars a, #firstCars a").removeAttr("href");
						} else {
		    				//$(".prePage").show();
							$("#preCars, #firstCars").removeClass("disabled");
							$("#preCars a, #firstCars a").attr("href","#");
						}
		    			if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
		    				//$(".nextPage").hide();
							$("#nextCars, #lastCars").addClass("disabled");
							$("#nextCars a, #lastCars a").removeAttr("href");
						} else {
		    				//$(".nextPage").show();
							$("#nextCars, #lastCars").removeClass("disabled");
							$("#nextCars a, #lastCars a").attr("href","#");
						}
						$("#curCars").attr("page", response.data.pager.curPage);
						$("#curCars a").html(response.data.pager.curPage);
						$("#totalCars").attr("total", response.data.pager.total);
						$("#totalCars").html("导出全部" + response.data.pager.total + "条记录");
						$("#paginationCars").show();
						$("#tableOrderCars").show();	//add by wujun
		    	}else{
		    		alert(response.message);

		    	}
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxQueryOrder() {
		$("#tableOrderDetail>tbody").html("");
		$("#tableOrderDetail>thead td").remove();
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    // url: ORDER_QUERY ,//ref:  /bms/js/service.js
		    url: QUERY_BOARD_ORDERS ,//ref:  /bms/js/service.js
		    data: {
		    	"orderNumber": $("#orderNumberText").val(),
		    	"standbyDate": $("#standbyDate").val(),
		    	"distributor": $("#distributorText").val(),
		    	"series" : $("#selectSeries").val(),
		    	"status" : getStatusChecked(),
		    	"orderBy": 'board_number,lane_id,priority,`status`',
		    },
		    success:function (response) {
	    		var boards = response.data;
	    		var amountSum = 0;
	    		var holdSum = 0;
	    		var countSum = 0;

	    		$.each(boards, function (board, value){
	    			//以board为单位构造子表
	    			var num = value.orders.length;
	    			var tmp = $("<tbody />");
	    			console.log(num);

	    			amountSum += value.boardAmount;
		    		holdSum += value.boardHold;
		    		countSum += value.boardCount;

	    			for(var i=0; i<num; i++){
	    				$("<tr />").appendTo(tmp);
	    				
	    			};
	    			console.log(tmp);

	    			$.each(value.orders, function (index,order){
	    				tr = tmp.children("tr:eq("+ index +")");
	    				$("<td />").html(order.lane_name).appendTo(tr);
	    				$("<td />").html(order.order_number).appendTo(tr);
	    				$("<td />").html(order.distributor_name).appendTo(tr);
	    				if(order.series == '6B'){
		    				$("<td />").html('思锐').appendTo(tr);
	    				} else {
		    				$("<td />").html(order.series).appendTo(tr);
	    				};
	    				$("<td />").html(order.car_type_config).appendTo(tr);
	    				$("<td />").html(order.cold).appendTo(tr);
	    				$("<td />").html(order.color).appendTo(tr);
	    				$("<td />").html(order.amount).addClass('amountTd').appendTo(tr);
	    				$("<td />").html(order.hold).addClass('holdTd').appendTo(tr);
	    				$("<td />").html(order.count).addClass('countTd').appendTo(tr);
	    				$("<td />").html(order.create_time).appendTo(tr);
	    				if(order.standby_finish_time === '0000-00-00 00:00:00'){
		    				$("<td />").html('未备齐').appendTo(tr);
	    				} else{
		    				$("<td />").html(order.standby_finish_time).appendTo(tr);
	    				}
	    				if(order.out_finish_time === '0000-00-00 00:00:00'){
		    				$("<td />").html('未完成').appendTo(tr);
	    				} else{
		    				$("<td />").html(order.out_finish_time).appendTo(tr);
	    				}
	    				if(order.status == 2){
		    				$(tr).addClass('success');
		    			}
	    			})

					//首行，被合并的单元格放在此行
	    			var firstTr = tmp.children("tr:eq(0)");	
	    			//合并的备板编号
	    			boardTd = $("<td />").attr("rowspan", num).html(value.boardNumber).prependTo(firstTr);
	    			//合并的需备数量
	    			boardAmountTd = $("<td />").attr("rowspan", num).addClass("totalTd text-info").html(value.boardAmount).insertAfter(firstTr.children("td:eq(8)"));
	    			//合并的已备数量
	    			boardHoldTd = $("<td />").attr("rowspan", num).addClass("totalTd text-info").html(value.boardHold).insertAfter(firstTr.children("td:eq(10)"));
	    			//合并的完成数量
	    			boardCountTd = $("<td />").attr("rowspan", num).addClass("totalTd text-info").html(value.boardCount).insertAfter(firstTr.children("td:eq(12)"));
	    			
	    			console.log(firstTr.children("td:eq(8)"));
	    			console.log(tmp.children("tr"));
	    			$("#tableOrderDetail tbody").append(tmp.children("tr"));
	    		})

					trTotal = $("<tr />");	
					tdLabel = "<td colspan='8' style='text-align:right'>合计&nbsp;&nbsp;&nbsp;&nbsp;</td>";
					trTotal.append(tdLabel);
					$("<td />").attr("colspan", "2").addClass("totalTd").html(amountSum).appendTo(trTotal);
					$("<td />").attr("colspan", "2").addClass("totalTd").html(holdSum).appendTo(trTotal);
					$("<td />").attr("colspan", "2").addClass("totalTd").html(countSum).appendTo(trTotal);
					$("<td />").attr("colspan", "3").appendTo(trTotal);
					$("#tableOrderDetail thead").prepend(trTotal);

	    		$("#tableOrderDetail").show();
		    },
		    error:function(){alertError();}
		});
	}

	function getStatusChecked () {
		var activeChecked = $("#checkboxActive").attr("checked") === "checked";
		var freezeChecked = $("#checkFreeze").attr("checked") === "checked";
		var closedChecked = $("#checkClosed").attr("checked") === "checked";
		
		if(!activeChecked && !freezeChecked && !closedChecked){
			return 'all'
		} else {
		var temp = [];
		if (activeChecked)
			temp.push($("#checkboxActive").val());
		if (freezeChecked)
			temp.push($("#checkFreeze").val());
		if (closedChecked)
			temp.push($("#checkClosed").val());
		return temp.join(",");
		}
	}

});
