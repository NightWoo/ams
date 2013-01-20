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

		resetAll();
	}

	$("#btnQuery").bind("click",toQuery);
	function toQuery() {
		//clear last
		$("#tableCars tbody").html("");
		$("#tablePause tbody").html("");

		//if validate passed
		var index = $("#tabs li").index($('#tabs .active'));
		console.log(index);
		if (index === 1)
			ajaxStatistics();
		else if (index === 3)
			ajaxQueryPause(1);
		else if (index === 7)
			ajaxQueryPlan(1);
		else if (index === 8)
			ajaxCompletionRate();
		else if (index === 0)
			ajaxQuery(1);
		return false;
	}

	$("#dutyDepartment").typeahead({
	    source: function (input, process) {
	        $.get(GET_PAUSE_DUTY_DEPARTMENT_LIST, {"departmentName":input}, function (data) {
	        	return process(data.data);
	        },'json');
	    },
	});

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

	//car pagination
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

	$("#firstCars").click(
		function () {
			if(parseInt($("#curCars").attr("page")) > 1){
				$("#tableCars tbody").html("");
				ajaxQuery(parseInt(1));
			}
		}
	);

	$("#lastCars").click(
		function () {
			if(parseInt($("#curCars").attr("page")) * 20 < parseInt($("#totalCars").attr("total")) ){
				$("#tableCars tbody").html("");
				totalPage = parseInt($("#totalCars").attr("total"))%20 === 0 ? parseInt($("#totalCars").attr("total"))/20 : parseInt($("#totalCars").attr("total"))/20 + 1;
				ajaxQuery(parseInt(totalPage));
			}
		}
	)

	$("#exportCars").click(
		function () {
			ajaxExport();
			return false;
		}
	);

	//pause pagination
	$("#prePause").click(
		function (){
			if(parseInt($("#curPause").attr("page")) > 1){
				$("#tablePause tbody").html("");
				ajaxQueryPause(parseInt($("#curPause").attr("page")) - 1);
			}
		}
	);

	$("#nextPause").click(
		function (){
			if(parseInt($("#curPause").attr("page")) * 10 < parseInt($("#totalPause").attr("total")) ){
			$("#tablePause tbody").html("");
			ajaxQueryPause(parseInt($("#curPause").attr("page")) + 1);
		}
		}
	);

	$("#firstPause").click(
		function () {
			if(parseInt($("#curPause").attr("page")) > 1){
				$("#tablePause tbody").html("");
				ajaxQueryPause(parseInt(1));
			}
		}
	);

	$("#lastPause").click(
		function () {
			if(parseInt($("#curPause").attr("page")) * 10 < parseInt($("#totalPause").attr("total")) ){
				$("#tablePause tbody").html("");
				totalPage = parseInt($("#totalPause").attr("total"))%10 === 0 ? parseInt($("#totalPause").attr("total"))/10 : parseInt($("#totalPause").attr("total"))/10 + 1;
				ajaxQueryPause(parseInt(totalPage));
			}
		}
	)

	// $("#exportPause").click(
	// 	function () {
	// 		ajaxExport();
	// 		return false;
	// 	}
	// );

	//plan pagination
	$("#prePlan").click(
		function (){
			if(parseInt($("#curPlan").attr("page")) > 1){
				$("#tablePlan tbody").html("");
				ajaxQueryPlan(parseInt($("#curPlan").attr("page")) - 1);
			}
		}
	);

	$("#nextPlan").click(
		function (){
			if(parseInt($("#curPlan").attr("page")) * 10 < parseInt($("#totalPlan").attr("total")) ){
			$("#tablePlan tbody").html("");
			ajaxQueryPlan(parseInt($("#curPlan").attr("page")) + 1);
		}
		}
	);

	$("#firstPlan").click(
		function () {
			if(parseInt($("#curPlan").attr("page")) > 1){
				$("#tablePlan tbody").html("");
				ajaxQueryPlan(parseInt(1));
			}
		}
	);

	$("#lastPlan").click(
		function () {
			if(parseInt($("#curPlan").attr("page")) * 10 < parseInt($("#totalPlan").attr("total")) ){
				$("#tablePlan tbody").html("");
				totalPage = parseInt($("#totalPlan").attr("total"))%10 === 0 ? parseInt($("#totalPlan").attr("total"))/10 : parseInt($("#totalPlan").attr("total"))/10 + 1;
				ajaxQueryPlan(parseInt(totalPage));
			}
		}
	)

	// $("#exportPlan").click(
	// 	function () {
	// 		ajaxExport();
	// 		return false;
	// 	}
	// );




	//监听tab切换事件，去取comp列表
	$("#tabs li").click(function () {
		var index = $("#tabs li").index(this);
		if (index == 1)
			ajaxStatistics();
		if (index == 3)
			ajaxQueryPause(1);
		else if (index === 7)
			ajaxQueryPlan(1);
		else if (index === 8)
			ajaxCompletionRate();
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
		    		$("#tableCars tbody").html("");
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
						//$("#tableCars").show();
		    		});

		    		//deal with pager	
		    		if(response.data.pager.curPage == 1) {
		    			//$(".prePage").hide();
							$("#preCars a span").html("&times;");
							$("#firstCars a span").html("&times;");
						} else {
		    				//$(".prePage").show();
							$("#preCars a span").html("&lt;");
							$("#firstCars a span").html("&lt;&lt;");
						}
		    			if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
		    				//$(".nextPage").hide();
							$("#nextCars a span").html("&times;");
							$("#lastCars a span").html("&times;");
						} else {
		    				//$(".nextPage").show();
							$("#nextCars a span").html("&gt;");
							$("#lastCars a span").html("&gt;&gt;");
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

	function ajaxQueryPause(targetPage) {
		$.ajax({
			type: "get",
			dataType: "json",
			url: QUERY_PAUSE_RECORD,
			data: {
				"startTime": $("#startTime").val(),
				"endTime": $("#endTime").val(),
				"causeType": $("#causeType").val(),
				"dutyDepartment": $("#dutyDepartment").val(),
				"section": $("#section").val(),	
				"pauseReason": $("#pauseReason").val(),
				"perPage": 10,
				"curPage": targetPage || 1
			},
			success: function(response) {
				if(response.success) {
					$("#tablePause>tbody").html("");
					$.each(response.data.data, function(index, value) {
						var tr = $("<tr />");
						$("<td />").html(value.id).appendTo(tr);
						$("<td />").html(value.cause_type).appendTo(tr);
						$("<td />").html(value.node_name).appendTo(tr);
						$("<td />").html(value.duty_department).appendTo(tr);
						$("<td />").html(value.remark).appendTo(tr);
						$("<td />").addClass("alignRight").html(value.howlong).appendTo(tr);
						$("<td />").html(value.pause_time.substr(0,16)).appendTo(tr);
						if(value.recover_time === "0000-00-00 00:00:00"){
							$("<td />").html("未恢复").appendTo(tr);
						}else{
							$("<td />").html(value.recover_time.substring(0,16)).appendTo(tr);
						}
						$("<td />").html(value.editor_name).appendTo(tr);
						
						tr.data("id",value.id);
						
						$("#tablePause tbody").append(tr);
						
						if(response.data.pager.curPage == 1) {
		    			//$(".prePage").hide();
							$("#prePause a span").html("&times;");
							$("#firstPause a span").html("&times;")
						} else {
		    				//$(".prePage").show();
							$("#prePause a span").html("&lt;");
							$("#firstPause a span").html("&lt;&lt;");
						}
		    			if(response.data.pager.curPage * 10 >= response.data.pager.total ) {
		    				//$(".nextPage").hide();
							$("#nextPause a span").html("&times;");
							$("#lastPause a span").html("&times;");
						} else {
		    				//$(".nextPage").show();
							$("#nextPause a span").html("&gt;");
							$("#lastPause a span").html("&gt;&gt;");
						}
						$("#curPause").attr("page", response.data.pager.curPage);
						$("#curPause a span").html(response.data.pager.curPage);
						$("#totalPause").attr("total", response.data.pager.total);
						$("#totalPause").html("导出全部" + response.data.pager.total + "条记录");
					
						$("#tablePause").show();
						$("#paginationPause").show();
					});
				}else {
					alert(response.message);	
				}
			},
			error: function() {
				alertError();
			}	
		})
	}

	function ajaxQueryPlan(targetPage) {
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
			type: "get",
			dataType: "json",
			url: QUERY_PLAN,
			data: {
				"stime": $("#startTime").val(),
				"etime": $("#endTime").val(),
				"line": "A",
				"series":series,
				"perPage": 10,
				"curPage": targetPage || 1,
			},
			success: function (response) {
				if(response.success) {
					$("#tablePlan>tbody").html("");
					length = response.data.length;
					$.each(response.data.data,function (index,value) {
		    			var tr = $("<tr />");
						//$("<td />").html(value.id).appendTo(tr);
						$("<td />").html(value.batch_number).appendTo(tr);		//added by wujun
		    			$("<td />").html(value.car_series).appendTo(tr);
		    			$("<td />").html(value.plan_date).appendTo(tr);
		    			$("<td />").html(value.total).appendTo(tr);
		    			$("<td />").html(value.ready).appendTo(tr);
		    			$("<td />").html(value.config_name).appendTo(tr);
		    			$("<td />").html(value.car_type).appendTo(tr);		//added by wujun
		    			$("<td />").html(value.color).appendTo(tr);
		    			
		    			if (value.cold_resistant == "1") {
		    				$("<td />").html("耐寒").appendTo(tr);
		    			} else {
		    				$("<td />").html("非耐寒").appendTo(tr);
		    			}
		    			
		    			$("<td />").html(value.car_year).appendTo(tr);
		    			$("<td />").html(value.order_type).appendTo(tr);
		    			$("<td />").html(value.remark).appendTo(tr);

		    			$("#tablePlan tbody").append(tr);
					});
					$("#tablePlan").show();

					//deal with pager	
		    		if(response.data.pager.curPage == 1) {
		    			//$(".prePage").hide();
						$("#prePlan a span").html("&times;");
						$("#firstPlan a span").html("&times;");
					} else {
		    			//$(".prePage").show();
						$("#prePlan a span").html("&lt;");
						$("#firstPlan a span").html("&lt;&lt;");
					}
		    		if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
		    			//$(".nextPage").hide();
						$("#nextPlan a span").html("&times;");
						$("#lastPlan a span").html("&times;");
					} else {
		    			//$(".nextPage").show();
						$("#nextPlan a span").html("&gt;");
						$("#lastPlan a span").html("&gt;&gt;");
					}
					$("#curPlan").attr("page", response.data.pager.curPage);
					$("#curPlan a span").html(response.data.pager.curPage);
					$("#totalPlan").attr("total", response.data.pager.total);
					$("#totalPlan").html("导出全部" + response.data.pager.total + "条记录");
					
					$("#paginationPlan").show();
				} else {
					alert(response.message);
				}
			},
			error: function () {
				alertError();
			}
		});
	}

	function ajaxCompletionRate() {
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
			type: "get",
			dataType: "json",
			url: PLAN_QUERY_COMPLETION,
			data: {
				"line" : 'A',
				"series":series,
				"stime":$("#startTime").val(),
				"etime":$("#endTime").val(),
			},
			success: function(response) {
				if(response.success){
					drawCompletionRate(response.data);
					changeCompletionRateTable(response.data);
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alertError();
			}
		});
	}



	function drawCompletionRate(data) {
		lineSeries = [];
		carSeries = data.carSeries;
		lineData = data.series;
		$.each(carSeries, function (index, series) {
			lineSeries[index] = {name: series + '_完成率', data: prepare(lineData.y[series])};
		});
		var chart;
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'completionRateContainer',
				type: 'line',
				marginBottom: 60
			},
			title: {
				text: '',
				x: -20 //center
			},
			credits: {
				//href: 'http://www.bydauto.com.cn',
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
				labels: {
					formatter: function() {
						return (this.value * 100) + '%';
					}
				},
				title: {
					text: '完成率'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}],
				min: 0,
				max: 1
			},
			tooltip: {
				formatter: function() {
					return '<b>' + this.series.name + '</b><br/>' +
					this.x + ': ' + this.y;
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

	function changeCompletionRateTable (data) {
		carSeries = data.carSeries;
		detail = data.detail;
		total = data.total;
		$("#tablecompletionRate thead").html("<tr />");
		$("#tablecompletionRate tbody").html("");
		$.each(carSeries, function (index, value) {
			$("<tr /><tr /><tr />").appendTo($("#tablecompletionRate tbody"));
		});

		//get tr
        //first column descriptions
        $.each(carSeries, function (index,value) {
            $("<td />").html(value + "_完成率").appendTo($("#tablecompletionRate tr:eq("+(index*3+1)+")"));
            $("<td />").html(value + "_完成数").appendTo($("#tablecompletionRate tr:eq("+(index*3+2)+")"));
            $("<td />").html(value + "_计划数").appendTo($("#tablecompletionRate tr:eq("+(index*3+3)+")"));

        });

        var thTr = $("#tablecompletionRate tr:eq(0)");
        $("<td />").html("日期").appendTo(thTr);

        //合计
        $("<td />").html("合计").appendTo(thTr);
        $.each(total, function (index, value) {
        	$("<td />").html(value.completionTotal).appendTo($("#tablecompletionRate tr:eq("+(index*3+1)+")"));
        	$("<td />").html(value.readyTotal).appendTo($("#tablecompletionRate tr:eq("+(index*3+2) +")"));
        	$("<td />").html(value.totalTotal).appendTo($("#tablecompletionRate tr:eq("+(index*3+3)+")"));
        });

        $.each(detail, function (index, value) {
        	$("<td />").html(value.time).appendTo(thTr);
        	$.each(carSeries, function (index, series) {
        		$("<td />").html(value[series].completion).appendTo($("#tablecompletionRate tr:eq(" + (index*3+1) +")"));
        		$("<td />").html(value[series].readySum).appendTo($("#tablecompletionRate tr:eq(" + (index*3+2) +")"));
        		$("<td />").html(value[series].totalSum).appendTo($("#tablecompletionRate tr:eq(" + (index*3+3) +")"));
        	});
        });
	}

//-------------------END ajax query -----------------------

});
