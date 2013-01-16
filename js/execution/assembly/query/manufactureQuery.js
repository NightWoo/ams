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
		$("#leftManufactureQueryLi").addClass("active");

		$("#startTime").val(currentDate8());
		$("#endTime").val(currentDate16());

		$("#carDetail, #statistics, .withNode").hide();
		$("#pauseDetail, #pauseDistribution, #useRate, #planDetail, #completion, .dividerLi, .withSection").show();
		resetAll();
	}

	$("#selectNode").change(function () {
		var nV = this.value;
		if (nV === "PBS" || nV === "T0" || nV === "CHECK_IN" || nV === "CHECK_OUT") {
			$("#carDetail, #statistics, .withNode").show();
			$("#pauseDetail, #pauseDistribution, #useRate, #planDetail, #completion, .dividerLi, .withSection").hide();
			if(!$("#carDetail").hasClass("active") && !$("#statistics").hasClass("active")){
				$("#carDetail, #dataList").addClass("active").siblings().removeClass("active");
			}
		} else {
			$("#carDetail, #statistics, .withNode").hide();
			$("#pauseDetail, #pauseDistribution, #useRate, #planDetail, #completion, .dividerLi, .withSection").show();
			if($("#carDetail").hasClass("active") || $("#statistics").hasClass("active")){
				$("#pauseDetail, #dataPauseDetail").addClass("active").siblings().removeClass("active");
			}
		}
	});

	$("#btnQuery").bind("click",toQuery);
	function toQuery() {
		//clear last
		$("#tableCars tbody").text("");

		//if validate passed
		var index = $("#tabs li").index($('#tabs .active'));
		console.log(index);
		if (index === 1)
			ajaxStatistics();
		else if (index === 2)
			ajaxQueryPause();
		else if (index === 0)
			ajaxQuery(1);
		return false;
	}

	$("#btnExport").click(
		function () {
			ajaxExport();
			return false;
		}
	);

	function currentDate8 (argument) {
		var now = new Date();
	        var year = now.getFullYear();       //年
	        var month = now.getMonth() + 1;     //月
	        var day = now.getDate();            //日
	        var hh = now.getHours();            //时
	        var mm = now.getMinutes();          //分
	       
	        var clock = year + '-';

	        if(month < 10) clock += '0';
	        clock += month + '-';

	        if(day < 10) clock += '0';
	        clock += day + ' ';

	        clock += "08:00";

	        return(clock); 
	}

	function currentDate16 (argument) {
		var now = new Date();
	        var year = now.getFullYear();       //年
	        var month = now.getMonth() + 1;     //月
	        var day = now.getDate();            //日
	        var hh = now.getHours();            //时
	        var mm = now.getMinutes();          //分
	       
	        var clock = year + '-';

	        if(month < 10) clock += '0';
	        clock += month + '-';

	        if(day < 10) clock += '0';
	        clock += day + ' ';

	        clock += "16:00";

	        return(clock); 
	}

	function resetAll (argument) {
		$(".pager").hide();
		$("#tableCars").hide();
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

	$("#preCars").click(
		function (){
			if(parseInt($("#curCars").attr("page")) > 1){
			$("#tableCars tbody").html("");
			ajaxQuery(parseInt($("#curCars").attr("page")) - 1);
		}
		}
	);

	$("#nextCars").click(
		function (){
			if(parseInt($("#curCars").attr("page")) * 20 < parseInt($("#totalCars").attr("total")) ){
			$("#tableCars tbody").html("");
			ajaxQuery(parseInt($("#curCars").attr("page")) + 1);
		}
		}
	);

	//监听tab切换事件，去取comp列表
	$("#tabs li").click(function () {
		var index = $("#tabs li").index(this);
		if (index == 1)
			ajaxStatistics();
		else if (index === 0)
			ajaxQuery(1);
	});

//-------------------END event bindings -----------------------


/*
 * ----------------------------------------------------------------
 * Ajax query
 * ----------------------------------------------------------------
 */
	function ajaxQuery (targetPage) {
		//get series for query
		var series = "";
		var f0Checked = $("#checkboxF0").attr("checked") === "checked";
		var m6Checked = $("#checkboxM6").attr("checked") === "checked";
		if((f0Checked + m6Checked)%2 === 0)
			series += $("#checkboxF0").val() + "," + $("#checkboxM6").val();
		else if(f0Checked)
			series += $("#checkboxF0").val();
		else
			series += $("#checkboxM6").val();
		
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: FAULT_QUERY,//ref:  /bms/js/service.js
		    data: { "vin": $('#vinText').val(), 
		    		"node":$("#selectNode").val(),
					"series":series,
					"stime":$("#startTime").val(),
					"etime":$("#endTime").val(),
					"perPage":20,
					"curPage":targetPage || 1},
		    success:function (response) {
		    	if(response.success){
		    		$.each(response.data.data,function (index,value) {
		    			var seriesTd = "<td>" + value.series + "</td>";
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var componentTd = "<td>" + value.component_name + "</td>";
						var faultTd = "<td>" + value.fault_mode + "</td>";
						var faultStatusTd = "<td>" + value.fault_status + "</td>";
		    			var nodeNameTd = "<td>" + value.node_name + "</td>";
		    			var createTimeTd = "<td>" + value.create_time + "</td>";
		    			var userNameTd = "<td>" + value.user_name + "</td>";
		    			var memoTd = "<td>" + value.modify_time + "</td>";
		    			var tr = "<tr>" + seriesTd + vinTd + componentTd + faultTd + 
		    				faultStatusTd + nodeNameTd + userNameTd + createTimeTd + memoTd + "</tr>";
		    			$("#tableCars tbody").append(tr);
						$("#tableCars").show();
		    		});
		    		//deal with pager
		    		

		    		if(response.data.pager.curPage == 1) {
		    			//$(".prePage").hide();
							$("#preCars a span").html("&times;");
						} else {
		    				//$(".prePage").show();
							$("#preCars a span").html("&lt;");
						}
		    			if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
		    				//$(".nextPage").hide();
							$("#nextCars a span").html("&times;");
						} else {
		    				//$(".nextPage").show();
							$("#nextCars a span").html("&gt;");
						}
						$("#curCars").attr("page", response.data.pager.curPage);
						$("#curCars a span").html(response.data.pager.curPage);
						$("#totalCars").attr("total", response.data.pager.total);
						$("#totalCars").html("导出全部" + response.data.pager.total + "条记录");
					
						$("#tableCars").show();
						$("#paginationCars").show();
		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}


	function ajaxStatistics () {
		// //get series for query
		var series = "";
		var f0Checked = $("#checkboxF0").attr("checked") === "checked";
		var m6Checked = $("#checkboxM6").attr("checked") === "checked";
		if((f0Checked + m6Checked)%2 === 0)
			series += $("#checkboxF0").val() + "," + $("#checkboxM6").val();
		else if(f0Checked)
			series += $("#checkboxF0").val();
		else
			series += $("#checkboxM6").val();
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: NODE_QUERY_CAR,//ref:  /bms/js/service.js
		    data: { "vin": $('#vinText').val(), 
		    		"node":$("#selectNode").val(),
					"series":series,
					"stime":$("#startTime").val(),
					"etime":$("#endTime").val(),
				},
		    success:function (response) {
		    	if(response.success){
		    		
		    		drawStatistic(response.data);
		    		changeStatisticsTable(response.data);
		    		// drawLineChart(response.data.series);
		    		// tempLineData = response.data.series;
		    		// changeLineTable(response.data.detail);
		    		
		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}
	/*
		draw draw statistic line
	*/
	function drawStatistic (data) {
		lineSeries = [];
        carSeries = data.carSeries;
        lineData = data.series;
        $.each(carSeries, function (index,series) {
            lineSeries[index] = {name : series + "车辆统计", data: prepare(lineData.y[series])};
        });

		var chart;
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'statisticContainer',
                type: 'line',
                //marginRight: 130,
                marginBottom: 60
            },
            title: {
                text: '',
                x: -20 //center
            },
            credits: {
                href: '',
                text: ''
            },
            xAxis: {
                categories: lineData.x,
                labels: {
                    rotation: -45,
                    align: 'right'
                }
            },
            yAxis: {
                title: {
                    text: '车辆数'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                min : 0
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y;
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'top',
                borderWidth: 0
            },
            series: lineSeries
        });
	}

	function changeStatisticsTable (data) {
		carSeries = data.carSeries;
		detail = data.detail;
		total =data.total;		//added by wujun
		$("#tableStatistic thead").html("<tr />");
		$("#tableStatistic tbody").html("<tr />");		
        $.each(carSeries, function (index,value) {
            $("<tr />").appendTo($("#tableStatistic tbody"));
        });
		
		var thTr = $("#tableStatistic tr:eq(0)");
        $("<td />").html("日期").appendTo(thTr);    
        
		$.each(carSeries, function (index,value) {
            $("<td />").html(value + ".车辆数").appendTo($("#tableStatistic tr:eq("+(index*1+1)+")"));
        });

         //合计,added by wujun
		$("<td />").html("合计").appendTo(thTr);
        $.each(total, function (index, value) {
        	$("<td />").html(value.carTotal).appendTo($("#tableStatistic tr:eq(" + (index*1+1) + ")"));
        });

		$.each(detail, function (index,value) {
			$("<td />").html(value.time).appendTo(thTr);

			$.each(carSeries, function (index,series) {
				$("<td />").html(value[series]).appendTo($("#tableStatistic tr:eq("+(index*1+1)+")"));
			});
		});
	}

	var distinctLabel = [];

	function prepare (dataArray) {
            return $(dataArray).map(function (index, item) {
                if ($.inArray(index, distinctLabel) !== -1)
                    return { y: item, show: false};
                return { y: item, show: true};
            });
             
    }

	function ajaxExport () {
		window.open(FAULT_EXPORT + "?vin=" + $('#vinText').val() + 
			"&node=" + $("#selectNode").val() + 
			"&series=" + ($("#checkboxF0").val() + "," + $("#checkboxM6").val()) +
			"&stime=" + $("#startTime").val() +
			"&etime=" + $("#endTime").val()
		);
	}
//-------------------END ajax query -----------------------

});
