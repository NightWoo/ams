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
		$("#leftComponentQueryLi").addClass("active");
		resetAll();
	}

	function resetAll (argument) {
		$("#pagination").hide();
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
		vin = $.trim($("#vinText").val());
		barCode = $.trim($("#barText").val());
		component = $.trim($("#componentText").val());
		provider = $.trim($("#providerText").val());
		sTime = $.trim($("#startTime").val());
		eTime = $.trim($("#endTime").val());

		if(vin == "" && barCode == "" && ((sTime == "" || eTime == "") || (component == "" && provider == ""))){
			alert("查询条件如不包含VIN或物料条码，则必须有起止时间且零部件名称或供应商不能均为空");
			return false;
		} else {
			ajaxQuery(1);
			return false;
		}
	}

	$("#prePage").click(
		function (){
			if(parseInt($("#curPage").attr("page")) > 1){
				$("#resultTable tbody").html("");
				ajaxQuery(parseInt($("#curPage").attr("page")) - 1);
			}
		}
	);

	$("#nextPage").click(
		function (){
			if(parseInt($("#curPage").attr("page")) * 20 < parseInt($("#total").attr("total")) ){
			$("#resultTable tbody").html("");
			ajaxQuery(parseInt($("#curPage").attr("page")) + 1);
		}
		}
	);

	$("#firstPage").click(
		function () {
			if(parseInt($("#curPage").attr("page")) > 1){
				$("#resultTable tbody").html("");
				ajaxQuery(parseInt(1));
			}
		}
	);

	$("#lastPage").click(
		function () {
			if(parseInt($("#curPage").attr("page")) * 20 < parseInt($("#total").attr("total")) ){
				$("#resultTable tbody").html("");
				totalPage = parseInt($("#total").attr("total"))%20 === 0 ? parseInt($("#total").attr("total"))/20 : parseInt($("#total").attr("total"))/20 + 1;
				ajaxQuery(parseInt(totalPage));
			}
		}
	)

	$("#export").click(
		function () {
			ajaxExport ();
			return false;
		}
	);

	// $("#startTime, #endTime").datetimepicker({
	//     format: 'yyyy-mm-dd hh:ii',
	//     autoclose: true,
	// 	todayBtn: true,
	// 	pickerPosition: "bottom-left",
	// 	language: "zh-CN"
 //    });

    $('#startTime, #endTime').datetimepicker({
		timeFormat: "HH:mm",
		changeMonth: true,
	    changeYear: true,
	    showOtherMonths: true,
	    selectOtherMonths: true,
	    duration: "fast",
	    buttonImageOnly: true,
	});
//-------------------END event bindings -----------------------


/*
 * ----------------------------------------------------------------
 * Ajax query
 * ----------------------------------------------------------------
 */	
	 function getSeriesChecked () {
		var f0Checked = $("#checkboxF0").attr("checked") === "checked";
		var m6Checked = $("#checkboxM6").attr("checked") === "checked";
		var _6BChecked = $("#checkbox6B").attr("checked") === "checked";
		
		var temp = [];
		if (f0Checked)
			temp.push($("#checkboxF0").val());
		if (m6Checked)
			temp.push($("#checkboxM6").val());
		if (_6BChecked)
			temp.push($("#checkbox6B").val());
		return temp.join(",");
	}

	function ajaxQuery (targetPage) {
		// series = getSeriesChecked();
		$("#pagination").hide();
		$("#resultTable").hide();
		$("#resultTable tbody").html("");
		$(".divLoading").show();
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: COMPONENT_QUERY,//ref:  /bms/js/service.js
		    data: { "vin": $('#vinText').val(), 
		    		"node":$("#selectNode").val(),
		    		"barcode":$("#barText").val(),
		    		"provider":$("#providerText").val(),
		    		"component":$("#componentText").val(),
					"series": $("#series").val(),
					"stime":$("#startTime").val(),
					"etime":$("#endTime").val(),
					"perPage":20,
					"curPage":targetPage},
		    success:function (response) {
		    	if(response.success){
		    		$.each(response.data.data,function (index,value) {
		    			var seriesTd = "<td>" + value.car_series + "</td>";
		    			var carTypeTd = "<td>" + value.car_type + "</td>";
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var componentTd = "<td>" + value.component_name + "</td>";
						var barTd = "<td>" + value.bar_code + "</td>";
						var providerTd = "<td>" + value.provider + "</td>";

		    			var nodeNameTd = "<td>" + value.node_name + "</td>";
		    			var createTimeTd = "<td>" + value.create_time + "</td>";
		    			var userNameTd = "<td>" + value.user_name + "</td>";
		    			var memoTd = "<td>" + value.modify_time + "</td>";
		    			var tr = "<tr>" + seriesTd + carTypeTd + vinTd + componentTd + barTd + 
		    				providerTd + nodeNameTd + userNameTd + createTimeTd + memoTd + "</tr>";
		    			$("#resultTable tbody").append(tr);
		    		});
		    		if(response.data.pager.curPage == 1) {
						$("#prePage, #firstPage").addClass("disabled");
						$("#prePage a, #firstPage a").removeAttr("href");
					} else {
						$("#prePage, #firstPage").removeClass("disabled");
						$("#prePage a, #firstPage a").attr("href","#");
					}
	    			if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
	    				//$(".nextPage").hide();
						$("#nextPage, #lastPage").addClass("disabled");
						$("#nextPage a, #lastPage a").removeAttr("href");
					} else {
	    				//$(".nextPage").show();
						$("#nextPage, #lastPage").removeClass("disabled");
						$("#nextPage a, #lastPage a").attr("href","#");
					}
					$("#curPage").attr("page", response.data.pager.curPage);
					$("#curPage a").html(response.data.pager.curPage);
					$("#total").attr("total", response.data.pager.total);
					$("#total").html("导出全部" + response.data.pager.total + "条记录");
					
					$(".divLoading").hide();
					$("#pagination").show();
					$("#resultTable").show();

		    	}else{
		    		alert(response.message);
		    	}
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxExport () {
		// series = getSeriesChecked();
		window.open(COMPONENT_EXPORT + 
			"?vin=" + $('#vinText').val() + 
			"&node=" + $("#selectNode").val() + 
			"&barcode=" + $("#barText").val() +
    		"&provider=" + $("#providerText").val() +
    		"&component=" + $("#componentText").val() +
			"&series=" + $("#series").val() +
			"&stime=" + $("#startTime").val() +
			"&etime=" + $("#endTime").val()
		);
	}
//-------------------END ajax query -----------------------

});