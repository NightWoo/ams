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
		else if (index == 4)
			ajaxPauseDistribute();
		else if (index == 5)
			ajaxQueryUseRate();
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
			ajaxExportNodeTrace ();
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
		if(index<9)
			$(".pagination").hide();
		if (index == 1)
			ajaxStatistics();
		else if (index == 3)
			ajaxQueryPause(1);
		else if (index == 4)
			ajaxPauseDistribute();
		else if (index == 5)
			ajaxQueryUseRate();
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
		    url: QUERY_NODE_TRACE,//ref:  /bms/js/service.js
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
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var seriesTd = "<td>" + value.series + "</td>";
		    			var serialTd = "<td>" + value.serial_number + "</td>";
		    			var carTypeTd = "<td>" + value.type + "</td>";
		    			var configTd = "<td>" + value.config_name + "</td>";
		    			var colorTd = "<td>" + value.color + "</td>";
		    			var coldTd = "<td>" + value.cold_resistant + "</td>";
		    			var statusTd = "<td>" + value.status + "</td>";
		    			var remarkTd = "<td>" + value.remark + "</td>";
		    			var pTimeTd = "<td>" + value.pass_time + "</td>";
		    			var tr = "<tr>"
		    				+ vinTd 
		    				+ seriesTd 
		    				+ serialTd 
		    				+ carTypeTd
		    				+ configTd
		    				+ colorTd
		    				+ coldTd 
		    				+ statusTd 
		    				+ remarkTd 
		    				+ pTimeTd 
		    				+ "</tr>";

		    			$("#tableCars tbody").append(tr);
						$("#tableCars").show();
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
            lineSeries[index] = {name : series, data: prepare(lineData.y[series])};
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
            	labels: {
                    formatter: function() {
                        return Math.round(this.value);
                    }
                },
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
        $("<th />").html("日期").appendTo(thTr);    
        $("<th />").html("合计").appendTo(thTr);
		$.each(carSeries, function (index, series) {
            $("<td />").html(series).appendTo($("#tableStatistic tr:eq("+(index*1+1)+")"));
            $("<td />").html(total[series]).appendTo($("#tableStatistic tr:eq(" + (index*1+1) + ")"));
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

	function ajaxExportNodeTrace () {
		window.open(EXPORT_NODE_TRACE + 
			"?&node=" + $("#selectNode").val() + 
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
				"curPage": targetPage || 1,
				"orderBy": 'DESC'
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
							$("#prePause, #firstPause").addClass("disabled");
							$("#prePause a, #firstPause a").removeAttr("href");
						} else {
		    				//$(".prePage").show();
							$("#prePause, #firstPause").removeClass("disabled");
							$("#prePause a, #firstPause a").attr("href","#");
						}
		    			if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
		    				//$(".nextPage").hide();
							$("#nextPause, #lastPause").addClass("disabled");
							$("#nextPause a, #lastPause a").removeAttr("href");
						} else {
		    				//$(".nextPage").show();
							$("#nextPause, #lastPause").removeClass("disabled");
							$("#nextPause a, #lastPause a").attr("href","#");
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

	function ajaxPauseDistribute() {
		var ajaxData ={
				"startTime": $("#startTime").val(),
				"endTime": $("#endTime").val(),
				"causeType": $("#causeType").val(),
				"dutyDepartment": $("#dutyDepartment").val(),
				"section": $("#section").val(),	
				"pauseReason": $("#pauseReason").val(),
			}
		$.ajax({
			type: "get",
			dataType: "json",
			url: QUERY_PAUSE_DISTRIBUTE,
			data: ajaxData,
			success: function(response) {
				if(response.success) {
					$("#pieContainerPauseDistribute").html("");
					var type = $('#radioPauseDistribute input:radio[name="optionsRadios"]:checked').val();
					// mQuery.pie.pieAjaxData = response.data;
					// mQuery.pie.drawPausePie(type);
					// mQuery.pie.updatePausePieTable(type);
					mQuery.pauseAnalysis.pauseAjaxData = response.data;
					mQuery.pauseAnalysis.drawAnalysis();
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alertError();
			}
		})
	}

	$('#radioPauseDistribute :radio').change(function () {
		$('#pieContainerPauseDistribute').html('');
		var type = $(this).val();
		mQuery.pie.drawPausePie(type);
		mQuery.pie.updatePausePieTable(type);
	});

	function ajaxQueryUseRate() {
		var ajaxData ={
				"startTime": $("#startTime").val(),
				"endTime": $("#endTime").val(),
			}
			$.ajax({
			type: "get",
			dataType: "json",
			url: QUERY_USE_RATE,
			data: ajaxData,
			success: function(response) {
				if(response.success) {
					$("#useRateContainer").html("");
					mQuery.useRate.useRateAjaxData = response.data;
					mQuery.useRate.drawUseRate();
					mQuery.useRate.updateUseRateTable();
				} else {
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
		    			$("<td />").html(value.plan_date).appendTo(tr);
		    			$("<td />").html(value.total).appendTo(tr);
		    			$("<td />").html(value.ready).appendTo(tr);
		    			$("<td />").html(value.car_series).appendTo(tr);
		    			$("<td />").html(value.car_type).appendTo(tr);		//added by wujun
		    			$("<td />").html(value.config_name).appendTo(tr);
		    			
		    			if (value.cold_resistant == "1") {
		    				$("<td />").html("耐寒").appendTo(tr);
		    			} else {
		    				$("<td />").html("非耐寒").appendTo(tr);
		    			}
		    			
		    			$("<td />").html(value.color).appendTo(tr);
		    			$("<td />").html(value.car_year).appendTo(tr);
		    			$("<td />").html(value.order_type).appendTo(tr);
		    			$("<td />").html(value.special_order).appendTo(tr);
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
		    		if(response.data.pager.curPage * 10 >= response.data.pager.total ) {
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
			lineSeries[index] = {name: series, data: prepare(lineData.y[series])};
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
			plotOptions: {
            	series: {
                	connectNulls: true
            	}
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
						return Math.round(this.value * 100) + '%';
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
					this.x + ': ' + (this.y * 100).toFixed(0) + '%';
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
        $("<th />").html("日期").appendTo(thTr).addClass("wideTh");

        //合计
        $("<th />").html("合计").appendTo(thTr);
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

!$(function () {
	window.mQuery = window.mQuery || {};
	window.mQuery.pie = {
		pieAjaxData: {},

		pieData: {
			chart: {
				renderTo: 'pieContainerPauseDistribute',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: {
				text: ''
			},
			credits: {
				href: '',
				text: ''
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage}%</b>',
				percentageDecimals: 1
			},
			plotOptions: {
				 pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(1) +' %';
                        }
                    }
                }
			},
			series: [{
				type: 'pie',
				name: '停线分析',
				data: []
			}]
		},

		drawPausePie: function(type) {
			this.pieData.series[0].data = this.pieAjaxData[type].series;
			var chart;
			chart = new Highcharts.Chart(this.pieData);
		},

		updatePausePieTable: function (type) {
			$("#tablePauseDistribute thead").html('<tr />');
			$("#tablePauseDistribute tbody").html('<tr />');
			var thTr = $("#tablePauseDistribute tr:eq(0)");
			var dataTr = $("#tablePauseDistribute tr:eq(1)");
			var percentageTr = $("#tablePauseDistribute tr:eq(2)");
			if(type === 'cause_type_chart_data')
				$('<td />').html('停线类型').appendTo(thTr);
			else if(type === 'duty_department_chart_data')
				$('<td />').html('责任部门').appendTo(thTr);
			$('<td />').html('百分比').appendTo(percentageTr);
			$('<td />').html('停线时间').appendTo(dataTr);
			$.each(this.pieAjaxData[type].detail, function (index,value) {
				$('<td />').html(value.name).appendTo(thTr);
				$('<td />').html(value.percentage).appendTo(percentageTr);
				$('<td />').html(value.howlong).appendTo(dataTr);
			});
		}
	};

	window.mQuery.useRate = {

		useRateAjaxData : {},

		useRateChartData: {
			chart: {
				renderTo: 'useRateContainer',
			},

			title: {
				text: '',
				x: -20	//center
			},

			credits: {
				href: '',
				text: ''
			},

			xAxis: {
				categories: {},
                labels: {
                    rotation: -45,
                    align: 'right'
                }
			},

			yAxis: {
				labels: {
                    formatter: function() {
                        return Math.round(this.value * 100) + '%';
                    }
                },
                title: {
                    text: '生产利用率'
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
				shared: true,
				crosshairs: true,
				useHTML: true,
                formatter: function() {
                	console.log(this);
                	var s = this.points[0].key +'<table>';
                
                	$.each(this.points, function(i, point) {
                    	s += '<tr><td style="color: '+ point.series.color +'">'+ point.series.name +': </td>' +
            					'<td style="text-align: right"><b>'+ Math.round(point.y * 100)+'%</b></td></tr>';
                	});
                	
                	s += '</table>';
                	return s;
                        
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'top',
                borderWidth: 0
            },
            plotOptions:{
                line:{
                    dataLabels: { 
                        enabled: true,
                        formatter: function() {
                            if(!this.point.show)
                                return '';
                            return Math.round(this.y * 100) + '%';
                        }
                    }
                },
                series: {
            		cursor: 'pointer',
            		point: {
		                events: {
		                    click: function() {
		                        // console.log(this.series.name);
		                        console.log(this.series.name);
                        		console.log("len:" + this.series.chart.series.length);
		                        console.log(this.x + ":" + this.y);
		                        mQuery.useRate.toggleClickPointData(this.x, this.y, this.series.index);
		                        var chart;
								chart = new Highcharts.Chart(mQuery.useRate.useRateChartData);
		                    }
		                }
		            }
            	}
            },
            series: []

		},
	
		drawUseRate: function() {
			var lineSeries = [];
			shift = this.useRateAjaxData.shift;
			lineData = this.useRateAjaxData.series;
			$.each(shift, function (index, shift) {
				lineSeries[index] = {name:shift, data:mQuery.useRate.prepare(lineData.y[shift])};
			});

			this.useRateChartData.series = lineSeries;
			this.useRateChartData.xAxis.categories = lineData.x;
			var chart;
			chart = new Highcharts.Chart(this.useRateChartData);
		},
	
		updateUseRateTable: function() {
			var shift = this.useRateAjaxData.shift;
			var detail = this.useRateAjaxData.detail;
			var total = this.useRateAjaxData.total;

			$("#tableUseRate thead").html('<tr />');
			$("#tableUseRate tbody").html('');
			$.each(shift, function (index,value) {
				$('<tr /><tr /><tr />').appendTo($('#tableUseRate tbody'));
			});
			//get tr
			var thTr = $('#tableUseRate tr:eq(0)');
			//first column descriptions
			$('<th />').html('班次').appendTo(thTr);
			$.each(shift, function (index, value) {
				$('<td rowspan=3 />').html(value).appendTo($('#tableUseRate tr:eq('+(index*3+1)+')'));
			});

			$("<th />").html("合计").appendTo(thTr);
			$.each(total, function (index, value) {
        		$("<td />").html(value.rateTotal).appendTo($("#tableUseRate tr:eq(" + (index*3+1) + ")"));
        		$("<td />").html(value.productionTotal).appendTo($("#tableUseRate tr:eq(" + (index*3+2) + ")"));
        		$("<td />").html(value.capacityTotal).appendTo($("#tableUseRate tr:eq(" + (index*3+3) + ")"));
        	});

			$('<th />').html('日期').appendTo(thTr);
        	$.each(shift, function (index, value) {
				$('<td />').html('利用').appendTo($('#tableUseRate tr:eq('+(index*3+1)+')'));
				$('<td />').html('产量').appendTo($('#tableUseRate tr:eq('+(index*3+2)+')'));
				$('<td />').html('能力').appendTo($('#tableUseRate tr:eq('+(index*3+3)+')'));
			});

			$.each(detail, function (index,value) {
				$('<td />').html(value.time).appendTo(thTr);
				$.each(shift, function (index,shift) {
					$('<td />').html(value[shift].rate).appendTo($('#tableUseRate tr:eq('+(index*3+1)+')'));
					$('<td />').html(value[shift].production).appendTo($('#tableUseRate tr:eq('+(index*3+2)+')'));
					$('<td />').html(value[shift].capacity).appendTo($('#tableUseRate tr:eq('+(index*3+3)+')'));
				});
			});

		},

		toggleClickPointData : function (x, y, index) {
			// console.log(this.dpuChartData);
			$(this.useRateChartData.series[index].data).each(function (index, value) {
				if(value.y == y && value.x == x) {
					value.show = !value.show;
					return false;
				}
			})
		},

		prepare : function  (dataArray) {
            return $(dataArray).map(function (index, item) {                
                return { x: index, y: item, show: false};
            });  
        }
	};

	window.mQuery.pauseAnalysis = {
		pauseAjaxData: {},

		analysisData: {
			chart: {
				renderTo: 'pauseAnalysisContainer',
			},
			title: {
				text: ''
			},
			credits: {
				href: '',
				text: ''
			},
			tooltip: {
				formatter: function() {
                    var s;
                    if (this.point.name) { // the pie chart
                        s = ''+
                            this.point.name +': '+ (this.y * 100).toFixed(1) + '%';
                    } else if(this.y > 0 && this.y < 1){	//percentage
                    	s =	''+
                    		this.x  +': '+ (this.y * 100).toFixed(1) + '%';
                    }else{		//column
                        s = ''+
                            this.x  +': '+ parseInt(this.y / 60)+ '分' + (this.y % 60) + '秒';
                    }
                    return s;
                }
			},
			legend: {
				layout: 'horizontal',
				align: 'center',
				verticalAlign: 'top',
				borderWidth: 0,
			},
			xAxis: {
				categories: [],
				labels: {
					rotation: -45,
					align: 'right',
					style: {
						fontSize: '12px',	
						fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
					} 
				}
			},
			yAxis: [
				{		// Primary yAxis
					labels: {
						style: {
							color: Highcharts.getOptions().colors[4],
						}
					},
					title: {
						text: '停线时长(分钟)',
						style: {
							color: Highcharts.getOptions().colors[4],
							fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
						}
					},
					labels: {
						formatter: function() {
							return parseInt(this.value/60)
						}
					},
					min: 0
				},{		// Secondary yAxis
					title: {
						text: '累计百分率',
						style: {
							color: Highcharts.getOptions().colors[5],
							fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
						}
					},
					labels: {
						formatter: function() {
							return Math.round(this.value * 100) + '%'
						},
						style: {
							color: Highcharts.getOptions().colors[5],
							fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
						}
					},
					max: 1,
					min: 0,
					opposite: true
				},

			],

			series: [
				{
					type: 'column',
					color: Highcharts.getOptions().colors[4],
					name: '停线时长',
					data: [],
					dataLabels: {
						enabled:true,
						style: {
							
							fontSize: '14px',
							fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
						},
						align: 'center',
                    	//y: -30,
            			color: Highcharts.getOptions().colors[4],
            			formatter: function() {
            				mm = parseInt(this.y / 60);
            				ss = (this.y % 60);

            				mm = mm<10 ? '0'+mm : mm;
            				ss = ss<10 ? '0'+ss : ss;

            				return mm + '\'' + ss + '\"';
            			}
					}
				}, {
					type: 'line',
					yAxis: 1,
					showInLegend: false,
					color: Highcharts.getOptions().colors[4],
					name: '百分率',
					data: [],
					dataLabels:{
						enabled: false,
						style: {
							fontSize: '14px',
							fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
						},
						align: 'center',
                    	//y: 30,
            			color: 'black',
            			formatter: function() {
            				return (this.y * 100).toFixed(1) + '%';
            			}
					},
					marker: {
						lineWidth: 2,
						lineColor: Highcharts.getOptions().colors[4],
						fillColor: 'white'
					},
					lineWidth: 0
				}, {
					typs: 'spline',
					color: Highcharts.getOptions().colors[5],
					name: '累计百分率',
					data: [],
					yAxis: 1,
					marker: {
						lineWidth: 2,
						lineColor: Highcharts.getOptions().colors[5],
						fillColor: 'white'
					}
				}, {
					type: 'pie',
					name: '停线类型',
					data: [],
					center: [1000,100],
					size: 150,
					showInLegend: false,
					dataLabels: {
						enabled:true,
						style: {
							// color: Highcharts.getOptions().colors[4],
							fontSize: '12px',
							fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
						},
						distance: 10,
            			formatter: function() {
                			return this.point.name +'<br/>'+ (this.y * 100).toFixed(1) + '%';
            				},
            			//y: -10,
            			//color: 'white',
					}
				}
			]
		},

		drawAnalysis: function(){
			this.analysisData.series[0].data = this.pauseAjaxData.series.column;
			this.analysisData.series[1].data = this.pauseAjaxData.series.p;
			this.analysisData.series[2].data = this.pauseAjaxData.series.y;
			this.analysisData.series[3].data = this.pauseAjaxData.series.cSeries;
			this.analysisData.xAxis.categories = this.pauseAjaxData.series.x;
			var chart;
			chart = new Highcharts.Chart(this.analysisData);
		},

		updatePauseAnalysisTable: function() {

		}
	}
});

