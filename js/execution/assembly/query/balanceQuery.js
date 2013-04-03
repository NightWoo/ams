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
		$("#leftBalanceQueryLi").addClass("active");

		// $("#startTime").val(currentDate8());
		// $("#endTime").val(currentDate16());
		resetAll();
	}
	

	function resetAll (argument) {
		$(".pager").hide();
		$("#resultTable").hide();
	}

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
	
	$("#btnQuery").bind("click",toQuery);
	function toQuery() {
		//clear last
		$("#resultTable tbody").text("");

		//if validate passed
		var index = $("#tabs li").index($('#tabs .active'));
		$("#paginationCars").hide();
		if (index == 0)
			ajaxDetailQuery();
		return false;
	}

	$("#exportCars").click(
		function () {
			ajaxExportBalanceDetail();
			return false;
		}
	);

	//car pagination
	$("#preCars").click(
		function (){
			if(parseInt($("#curCars").attr("page")) > 1){
				$("#tableCars tbody").html("");
				ajaxDetailQuery(parseInt($("#curCars").attr("page")) - 1);
			}
		}
	);

	$("#nextCars").click(
		function (){
			if(parseInt($("#curCars").attr("page")) * 20 < parseInt($("#totalCars").attr("total")) ){
			$("#tableCars tbody").html("");
			ajaxDetailQuery(parseInt($("#curCars").attr("page")) + 1);
		}
		}
	);

	$("#firstCars").click(
		function () {
			if(parseInt($("#curCars").attr("page")) > 1){
				$("#tableCars tbody").html("");
				ajaxDetailQuery(parseInt(1));
			}
		}
	);

	$("#lastCars").click(
		function () {
			if(parseInt($("#curCars").attr("page")) * 20 < parseInt($("#totalCars").attr("total")) ){
				$("#tableCars tbody").html("");
				totalPage = parseInt($("#totalCars").attr("total"))%20 === 0 ? parseInt($("#totalCars").attr("total"))/20 : parseInt($("#totalCars").attr("total"))/20 + 1;
				ajaxDetailQuery(parseInt(totalPage));
			}
		}
	)

	$("#tabs li").click(function () {
		var index = $("#tabs li").index(this);
		if(index<3)
			$("#paginationCars").hide();
		if (index == 0)
			ajaxDetailQuery();
		// else if (index === 2)
	});

//-------------------END event bindings -----------------------



/*
 * ----------------------------------------------------------------
 * Ajax query
 * ----------------------------------------------------------------
 */
	function ajaxDetailQuery (targetPage) {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: BALANCE_Detail_QUERY,//ref:  /bms/js/service.js
		    data: { 
		    	"state" : $("#selectState").val(),
		    	"series" : $("#selectSeries").val(),
				"curPage":targetPage || 1,
		    	"perPage":20,
		    },
		    success:function (response) {
		    	if(response.success){
		    		$("#resultTable tbody").html("");
		    		$.each(response.data.data,function (index,value) {
		    			var seriesTd = "<td>" + value.series + "</td>";
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var colorTd = "<td>" + value.color + "</td>";
						var typeInfoTd = "<td>" + value.type_info + "</td>";
						var coldTd = "<td>" + value.cold + "</td>";
		    			var statusTd = "<td>" + value.status + "</td>";
		    			var rowTd = "<td>" + value.row + "</td>";
		    			var finishTimeTd = "<td>" + value.finish_time + "</td>";
		    			var warehouseTimeTd = "<td>" + value.warehouse_time + "</td>";
		    			var tr = "<tr>" + seriesTd + vinTd + colorTd + typeInfoTd + 
		    				coldTd + statusTd + rowTd + finishTimeTd + warehouseTimeTd + "</tr>";
		    			$("#resultTable tbody").append(tr);
						$("#resultTable").show();
		    		});
		    		//deal with pager
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

		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxExportBalanceDetail(){
		window.open(BALANCE_Detail_EXPORT + "?state=" + $("#selectState").val() +"&$series="+ $("#selectSeries"));
	}
	

//-------------------END ajax query -----------------------

});
