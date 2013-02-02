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
		$(".pager").hide();
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
		ajaxQuery(1);
		return false;
	}

	$("#btnExport").click(
		function () {
			ajaxExport();
			return false;
		}
	);

	$(".prePage").click(
		function (){
			$("#resultTable tbody").text("");
			ajaxQuery(parseInt($(".curPage").attr("page")) - 1);
		}
	);

	$(".nextPage").click(
		function (){
			$("#resultTable tbody").text("");
			ajaxQuery(parseInt($(".curPage").attr("page")) + 1);
		}
	);
//-------------------END event bindings -----------------------


/*
 * ----------------------------------------------------------------
 * Ajax query
 * ----------------------------------------------------------------
 */
	function ajaxQuery (targetPage) {

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

		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: COMPONENT_QUERY,//ref:  /bms/js/service.js
		    data: { "vin": $('#vinText').val(), 
		    		"node":$("#selectNode").val(),
		    		"barcode":$("#barText").val(),
		    		"provider":$("#providerText").val(),
		    		"component":$("#componentText").val(),
					"series": temp.join(","),
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
		    		//deal with pager
		    		$(".pager").show();
		    		if(response.data.pager.curPage == 1)
		    			$(".prePage").hide();
		    		else
		    			$(".prePage").show();
		    		if(response.data.pager.curPage * 20 >= response.data.pager.total )
		    			$(".nextPage").hide();
		    		else
		    			$(".nextPage").show();
		    		$(".curPage").attr("page", response.data.pager.curPage);
		    		$(".curPage").html("第" + response.data.pager.curPage + "页");
		    		$("#totalText").html("共" + response.data.pager.total + "条记录");

		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxExport () {
		window.open(COMPONENT_EXPORT + "?vin=" + $('#vinText').val() + 
			"&node=" + $("#selectNode").val() + 
			"&barcode=" + $("#barText").val() +
    		"&provider=" + $("#providerText").val() +
    		"&component=" + $("#componentText").val() +
			"&series=" + ($("#checkboxF0").val() + "," + $("#checkboxM6").val()) +
			"&stime=" + $("#startTime").val() +
			"&etime=" + $("#endTime").val()
		);
	}
//-------------------END ajax query -----------------------

});