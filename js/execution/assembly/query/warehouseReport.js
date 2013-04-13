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
		$("#leftWarehouseReportLi").addClass("active");

		$("#startTime").val(window.byd.DateUtil.currentDate8());
		// $("#endTime").val(currentDate16());
		$("#endTime").val(window.byd.DateUtil.currentTime);
		resetAll();
	}
	

	function resetAll (argument) {
		$(".pagination").hide();
		$("#resultCheckin").hide();
	}

	$("#resetST").click(function() {
		$("#startTime").val(window.byd.DateUtil.currentDate8());
	});

	$("#refreshET").click(function() {
		$("#endTime").val(window.byd.DateUtil.currentTime);
	});

/*
 * ----------------------------------------------------------------
 * Event bindings
 * ----------------------------------------------------------------
 */	
 	$(window).bind('keydown', enterHandler);
	function enterHandler (event) {
		if (event.keyCode == "13"){
		    toQuery();
		    return false;
		}
	}

	//checkin pagination
	$("#preCheckin").click(
		function (){
			if(parseInt($("#curCheckin").attr("page")) > 1){
				$("#resultCheckin tbody").html("");
				ajaxQueryCheckinDetail(parseInt($("#curCheckin").attr("page")) - 1);
			}
		}
	);

	$("#nextCheckin").click(
		function (){
			if(parseInt($("#curCheckin").attr("page")) * 20 < parseInt($("#totalCheckin").attr("total")) ){
			$("#resultCheckin tbody").html("");
			ajaxQueryCheckinDetail(parseInt($("#curCheckin").attr("page")) + 1);
		}
		}
	);

	$("#firstCheckin").click(
		function () {
			if(parseInt($("#curCheckin").attr("page")) > 1){
				$("#resultCheckin tbody").html("");
				ajaxQueryCheckinDetail(parseInt(1));
			}
		}
	);

	$("#lastCheckin").click(
		function () {
			if(parseInt($("#curCheckin").attr("page")) * 20 < parseInt($("#totalCheckin").attr("total")) ){
				$("#resultCheckin tbody").html("");
				totalPage = parseInt($("#totalCheckin").attr("total"))%20 === 0 ? parseInt($("#totalCheckin").attr("total"))/20 : parseInt($("#totalCheckin").attr("total"))/20 + 1;
				ajaxQueryCheckinDetail(parseInt(totalPage));
			}
		}
	)

	$("#exportCheckin").click(
		function () {
			exportCheckinDetail();
			return false;
		}
	);

	$("#tabs li").click(function () {
		var index = $("#tabs li").index(this);
		if(index<3)
			$(".pagination").hide();
		if (index == 0)
			ajaxQueryCheckinDetail(0);
		else if (index === 1)
			ajaxQueryCheckoutDetail(0);
	});
	
	//checkout pagination
	$("#preCheckout").click(
		function (){
			if(parseInt($("#curCheckout").attr("page")) > 1){
				$("#resultCheckout tbody").html("");
				ajaxQueryCheckoutDetail(parseInt($("#curCheckout").attr("page")) - 1);
			}
		}
	);

	$("#nextCheckout").click(
		function (){
			if(parseInt($("#curCheckout").attr("page")) * 20 < parseInt($("#totalCheckout").attr("total")) ){
			$("#resultCheckout tbody").html("");
			ajaxQueryCheckoutDetail(parseInt($("#curCheckout").attr("page")) + 1);
		}
		}
	);

	$("#firstCheckout").click(
		function () {
			if(parseInt($("#curCheckout").attr("page")) > 1){
				$("#resultCheckout tbody").html("");
				ajaxQueryCheckoutDetail(parseInt(1));
			}
		}
	);

	$("#lastCheckout").click(
		function () {
			if(parseInt($("#curCheckout").attr("page")) * 20 < parseInt($("#totalCheckout").attr("total")) ){
				$("#resultCheckout tbody").html("");
				totalPage = parseInt($("#totalCheckout").attr("total"))%20 === 0 ? parseInt($("#totalCheckout").attr("total"))/20 : parseInt($("#totalCheckout").attr("total"))/20 + 1;
				ajaxQueryCheckoutDetail(parseInt(totalPage));
			}
		}
	)

	$("#exportCheckout").click(
		function () {
			exportCheckoutDetail();
			return false;
		}
	);

	$("#tabs li").click(function () {
		var index = $("#tabs li").index(this);
		if(index<3)
			$(".pagination").hide();
		if (index == 0)
			ajaxQueryCheckinDetail(0);
		else if (index === 1)
			ajaxQueryCheckoutDetail(0);
	});

//-------------------END event bindings -----------------------



/*
 * ----------------------------------------------------------------
 * Ajax query
 * ----------------------------------------------------------------
 */
 	function ajaxQueryCheckinDetail (targetPage) {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: WAREHOUSE_CHECKIN_QUERY,//ref:  /bms/js/service.js
		    data: { 
		    	"startTime" : $("#startTime").val(),
		    	"endTime" : $("#endTime").val(),
		    	"series" : $("#selectSeries").val(),
				"curPage":targetPage || 1,
		    	"perPage":20,
		    },
		    success:function (response) {
		    	if(response.success){
		    		$("#resultCheckin tbody").html("");
		    		var cars = response.data.data;

		    		$.each(cars ,function (index,value) {
		    			// var carIdTd = "<td>" + value.car_id + "</td>";
		    			// var serialTd = "<td>" + value.serial_number + "</td>";
		    			var warehouseTd = "<td>" + value.row + "</td>";
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var seriesTd = "<td>" + value.series + "</td>";
		    			// var typeTd = "<td>" + value.type + "</td>";
		    			var configTd = "<td>" + value.config_name + "</td>";
		    			var coldTd = "<td>" + value.cold + "</td>";
		    			var colorTd = "<td>" + value.color + "</td>";
		    			var engineTd = "<td>" + value.engine_code + "</td>";
						var warehouseTimeTd = "<td>" + value.warehouse_time.substr(0,16) + "</td>";
		    			var lineTd = "<td>" + value.assembly_line + "</td>";
		    			var finishTimeTd = "<td>" + value.finish_time + "</td>";
		    			// var remarkTd = "<td>" + value.remark + "</td>";

		    			var tr = "<tr>"  
		    					+ warehouseTd 
		    					+ vinTd 
		    					+ seriesTd 
		    					+ configTd 
		    					+ coldTd 
		    					+ colorTd 
		    					+ engineTd 
		    					+ warehouseTimeTd
		    					+ lineTd 
		    					+ finishTimeTd +"</tr>";
		    			$("#resultCheckin tbody").append(tr);
		    			$("#resultCheckin").show();
		    		});

		    		//deal with pager
		    		if(response.data.pager.curPage == 1) {
		    			//$(".prePage").hide();
							$("#preCheckin, #firstCheckin").addClass("disabled");
							$("#preCheckin a, #firstCheckin a").removeAttr("href");
						} else {
		    				//$(".prePage").show();
							$("#preCheckin, #firstCheckin").removeClass("disabled");
							$("#preCheckin a, #firstCheckin a").attr("href","#");
						}
		    			if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
		    				//$(".nextPage").hide();
							$("#nextCheckin, #lastCheckin").addClass("disabled");
							$("#nextCheckin a, #lastCheckin a").removeAttr("href");
						} else {
		    				//$(".nextPage").show();
							$("#nextCheckin, #lastCheckin").removeClass("disabled");
							$("#nextCheckin a, #lastCheckin a").attr("href","#");
						}
						$("#curCheckin").attr("page", response.data.pager.curPage);
						$("#curCheckin a").html(response.data.pager.curPage);
						$("#totalCheckin").attr("total", response.data.pager.total);
						$("#totalCheckin").html("导出全部" + response.data.pager.total + "条记录");
					
						$("#paginationCheckin").show();

		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}

	function exportCheckinDetail(){
		window.open(WAREHOUSE_CHECKIN_EXPORT + "?startTime=" + $("#startTime").val() +"&endTime=" + $("#endTime").val()  +"&series="+ $("#selectSeries").val());
	}

	function ajaxQueryCheckoutDetail (targetPage) {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: WAREHOUSE_CHECKOUT_QUERY,//ref:  /bms/js/service.js
		    data: { 
		    	"startTime" : $("#startTime").val(),
		    	"endTime" : $("#endTime").val(),
		    	"series" : $("#selectSeries").val(),
				"curPage":targetPage || 1,
		    	"perPage":20,
		    },
		    success:function (response) {
		    	if(response.success){
		    		$("#resultCheckout tbody").html("");
		    		var cars = response.data.data;

		    		$.each(cars ,function (index,value) {
		    			var laneTd = "<td>" + value.lane + "</td>";
		    			var orderNumberTd = "<td>" + value.order_number + "</td>";
		    			var distributorNameTd = "<td>" + value.distributor_name + "</td>";
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var seriesTd = "<td>" + value.series + "</td>";
		    			// var typeTd = "<td>" + value.type + "</td>";
		    			var configTd = "<td>" + value.config_name + "</td>";
		    			var coldTd = "<td>" + value.cold + "</td>";
		    			var colorTd = "<td>" + value.color + "</td>";
		    			var engineTd = "<td>" + value.engine_code + "</td>";
						var outDateTd = "<td>" + value.distribute_time.substr(0,16) + "</td>";
		    			// var remarkTd = "<td>" + value.remark + "</td>";

		    			var tr = "<tr>"  + orderNumberTd + laneTd + distributorNameTd + 
		    				vinTd + seriesTd + configTd + coldTd + colorTd + engineTd +  outDateTd +"</tr>";
		    			$("#resultCheckout tbody").append(tr);
		    			$("#resultCheckout").show;
		    		});

		    		//deal with pager
		    		if(response.data.pager.curPage == 1) {
		    			//$(".prePage").hide();
							$("#preCheckout, #firstCheckout").addClass("disabled");
							$("#preCheckout a, #firstCheckout a").removeAttr("href");
						} else {
		    				//$(".prePage").show();
							$("#preCheckout, #firstCheckout").removeClass("disabled");
							$("#preCheckout a, #firstCheckout a").attr("href","#");
						}
		    			if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
		    				//$(".nextPage").hide();
							$("#nextCheckout, #lastCheckout").addClass("disabled");
							$("#nextCheckout a, #lastCheckout a").removeAttr("href");
						} else {
		    				//$(".nextPage").show();
							$("#nextCheckout, #lastCheckout").removeClass("disabled");
							$("#nextCheckout a, #lastCheckout a").attr("href","#");
						}
						$("#curCheckout").attr("page", response.data.pager.curPage);
						$("#curCheckout a").html(response.data.pager.curPage);
						$("#totalCheckout").attr("total", response.data.pager.total);
						$("#totalCheckout").html("导出全部" + response.data.pager.total + "条记录");
					
						$("#paginationCheckout").show();

		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}

	function exportCheckoutDetail(){
		window.open(WAREHOUSE_CHECKOUT_EXPORT + "?startTime=" + $("#startTime").val() +"&endTime=" + $("#endTime").val()  +"&series="+ $("#selectSeries").val());
	}


	function ajaxQueryBalanceAssembly(state) {
		$.ajax({
			url: QUERY_BALANCE_ASSEMBLY,
			type: "get",
			data: {
				"state" : state,
			},
			dataType: "json",
			success: function(response) {
				balanceQuery.AssemblyAll.ajaxData = response.data;
				balanceQuery.AssemblyAll.updateDistributeTable();
				balanceQuery.AssemblyAll.drawColumn();
				$("#tableCarsDistribute").show();
				$("#columnContainer").show();
			},
			error: function(){
				alertError();
			}
		})
	}

	function ajaxQueryBalanceDistribute() {
		$.ajax({
			url: QUERY_BALANCE_DISTRIBUTE,
			type: "get",
			data: {
				"state" : $("#selectState").val(),
				"series" : $("#selectSeries").val(), 
			},
			dataType: "json",
			success: function (response) {
				balanceQuery.distribute.ajaxData = response.data;
				balanceQuery.distribute.updateDistributeTable();
				$("#tableCarsDistribute").show();
				$("#columnContainer").hide();
			}
		})
	}
	

//-------------------END ajax query -----------------------

});

