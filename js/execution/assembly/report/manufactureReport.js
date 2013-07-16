$(document).ready(function () {
	initPage();

//event bindings -----------------------------

	//jquery-ui-datetimepicker
    $("#startTime").datetimepicker({
	    format: 'yyyy-mm-dd',
	    autoclose: true,
		todayBtn: true,
		pickerPosition: "bottom-left",
		language: "zh-CN",
		minView: "2",
    });


    $("#queryManufactureDaily").click(function (){
    	ajaxQueryManufactureDaily();
    })

    $(".exportCars").click(function(){
    	point = $(this).attr("point");
    	timespan = $(this).attr("timespan");
    	ajaxExportCars(point, timespan);
    })

    $(".queryCompletion").click(function(){
    	// timespan = $(this).attr("timespan");
    	ajaxQueryManufactureDaily();
    	ajaxQueryCompletion("monthly");
    	ajaxQueryCompletion("yearly");
    	$("#tabUl>li").addClass("notPrintable");
    	$(this).parent("li").removeClass("notPrintable");
    })

    $(".queryUse").click(function(){
    	timespan = $(this).attr("timespan");
    	ajaxQueryUse("monthly");
    	ajaxQueryUse("yearly");
    	$("#tabUl>li").addClass("notPrintable");
    	$(this).parent("li").removeClass("notPrintable");
    })

    $(".queryRecycle").click(function(){
    	timespan = $(this).attr("timespan");
    	ajaxQueryRecycle("monthly");
    	ajaxQueryRecycle("yearly");
    	ajaxQueryOvertimeCars();
    	$("#tabUl>li").addClass("notPrintable");
    	$(this).parent("li").removeClass("notPrintable");
    })

    $(".queryWarehouse").click(function(){
    	ajaxQueryWarehouse("monthly");
    	ajaxQueryWarehouse("yearly");
    	ajaxQueryOvertimeOrders();
    	$("#tabUl>li").addClass("notPrintable");
    	$(this).parent("li").removeClass("notPrintable");
    })

    $(".print").click(function() {
    	window.print();
    })
//END event bindings -------------------------


//common function -------------------------

	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftManufactureReportLi").addClass("active");

		$("#divLeft,#divHead").addClass("notPrintable");


		$("#startTime").val(window.byd.DateUtil.lastWorkDate());
		ajaxQueryManufactureDaily();
		$("#headText").html("计划完成情况");
		ajaxQueryCompletion("monthly");
    	ajaxQueryCompletion("yearly");
		resetAll();
	}

	function resetAll (argument) {
		$(".initHide").hide();
	}

//END commonfunction --------------------


//ajax query -------------------------------------------

	function ajaxQueryManufactureDaily(){
		$("#manufactureDailyTable").hide();
		$.ajax({
			url: QUERY_MANUFACTURE_DAILY,
			type: "get",
			dataType: "json",
			data: {
				"date": $("#startTime").val(),
			},
			error:function() {alertError();},
			success: function(response) {
				if(response.success){
					report.manufactureDaily.ajaxData = response.data;
					report.manufactureDaily.updateDailyTable();
					// report.manufactureDaily.drawColumn();
					// report.manufactureDaily.drawDonut();
				}
			}
		})
	}

	function ajaxExportCars(point, timespan){
		window.open(MANUFACTURE_REPORT_EXPORT_CARS
			+ "?date=" + $("#startTime").val()
			+ "&point=" + point
			+ "&timespan=" + timespan
		);
	}

	function ajaxQueryCompletion(timespan){
		$(".completionTable").hide();
		$(".completionChart[timespan="+ timespan +"]").hide();
		$("#tabCompletion .divLoading[timespan="+ timespan +"]").show();
		$.ajax({
			url: QUERY_COMPLETION_REPORT,
			type: "get",
			dataType: "json",
			data: {
				"date": $("#startTime").val(),
				"timespan" : timespan,
			},
			error: function() {alertError();},
			success: function(response) {
				if(response.success){
					report["completion"].ajaxData = response.data;
					report["completion"].drawColumnLine(timespan);
					// report.completion.updateTable(timespan);
				}
			}
		})
	}

	function ajaxQueryUse(timespan){
		$(".useTable").hide();
		$(".useChart[timespan="+ timespan +"]").hide();
		$("#tabManufactureUse .divLoading[timespan="+ timespan +"]").show();
		$.ajax({
			url: QUERY_USE_REPORT,
			type: "get",
			dataType: "json",
			data: {
				"date": $("#startTime").val(),
				"timespan" : timespan,
			},
			error: function() {alertError();},
			success: function(response) {
				if(response.success){
					report["use"].ajaxData = response.data;
					report["use"].drawColumnLine(timespan);
					if(timespan == "monthly"){
						report.use.updateTable();
					}
				}
			}
		})
	}

	function ajaxQueryRecycle(timespan){
		$(".recycleChart[timespan="+ timespan +"]").hide();
		$("#tabRecycle .divLoading[timespan="+ timespan +"]").show();
		$.ajax({
			url: QUERY_RECYCLE_REPROT_CHART,
			type: "get",
			dataType: "json",
			data: {
				"date": $("#startTime").val(),
				"timespan" : timespan,
			},
			error: function() {alertError();},
			success: function(response) {
				if(response.success){
					report.recycle.ajaxData = response.data;
					report.recycle.drawColumnLine(timespan);
				}
			}
		})
	}

	function ajaxQueryOvertimeCars(){
		$("#overtimeCarsTable").hide();
		$.ajax({
			url: QUERY_OVERTIME_CARS,
			type: "get",
			dataType: "json",
			data: {},
			error: function() {alertError();},
			success: function(response) {
				if(response.success){
					report.recycle.ajaxOvertimeData = response.data;
					report.recycle.updateTable();
				}
			}
		})
	}

	function ajaxQueryWarehouse(timespan){
		$(".warehouseChart[timespan="+ timespan +"]").hide();
		$("#tabWarehouse .divLoading[timespan="+ timespan +"]").show();
		$.ajax({
			url: QUERY_WAREHOUSE_CHART,
			type: "get",
			dataType: "json",
			data: {
				"date": $("#startTime").val(),
				"timespan" : timespan,
			},
			error: function() {alertError();},
			success: function(response) {
				if(response.success){
					report.warehouse.ajaxData = response.data;
					report.warehouse.drawColumnLine(timespan);
				}
			}
		})
	}

	function ajaxQueryOvertimeOrders() {
		$("#overtimeOrdersTable").hide();
		$.ajax({
			url: QUERY_OVERTIME_ORDERS,
			type: "get",
			dataType: "json",
			data: {},
			error: function() {alertError();},
			success: function(response) {
				if(response.success){
					report.warehouse.ajaxOvertimeData = response.data;
					report.warehouse.updateTable();
				}
			}
		})
	}
//END ajax query ---------------------------------------

});

!$(function() {
	window.report = window.report || {};
	window.report.manufactureDaily = {
		ajaxData: {},
		columnData: {
			chart: {
                type: 'column',
                // renderTo: 'columnContainer'
            },
            title: {
                text: '生产完成情况'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: []
            },
            credits: {
                enabled: false
            },
            yAxis: {
                min: 0,
                title: {
                    text: '车辆数'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:14px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                	stacking: 'normal',
                    pointPadding: 0.1,
                    borderWidth: 0,
                    pointWidth: 15
                }
            },
            series: [],
            navigation: {
	            buttonOptions: {
	                verticalAlign: 'bottom',
	                y: -20,
	            }
	        }
		},

		donutData: {
            chart: {
                type: 'pie'
            },
            title: {
                text: '周转车分布'
            },
            credits: {
                enabled: false
            },
            yAxis: {
                title: {
                    text: ''
                }
            },
            plotOptions: {
                pie: {
                    shadow: false,
                    center: ['50%', '50%']
                }
            },
            tooltip: {
        	    valueSuffix: '辆'
            },
            navigation: {
	            buttonOptions: {
	                verticalAlign: 'bottom',
	                y: -20,
	            }
	        },
            series: [{
                name: '区域',
                data: [],
                size: '60%',
                dataLabels: {
                    formatter: function() {
                        return this.y > 0 ? '<b>'+ this.point.name +'</b> ' + "[" + this.y +"]": null;
                    },
                    color: 'white',
                    distance: -30
                }
            }, {
                name: '周期',
                data: [],
                size: '80%',
                innerSize: '60%',
                dataLabels: {
                    formatter: function() {
                        // display only if larger than 0
                        return this.y > 0 ? '<b>'+ this.point.name +'</b> '+  "[" + this.y + "]" : null;
                    }
                }
            }]
        },

		updateDailyTable: function() {
			var countPoint = this.ajaxData.countPoint;
			var countSeries = this.ajaxData.countSeries;
			var count = this.ajaxData.count;

			//clear table and initialize it
			headTr = "<tr>"
					+"<th rowspan='2'>车系</th>"
					+"<th colspan='3'>当日上线-I线</th>"
					+"<th colspan='3'>当日上线-II线</th>"
					+"<th colspan='2'>当日成品库</th>"
					+"<th colspan='2'>当日结存</th>"
					+"<th colspan='3'>当月完成</th>"
					+"</tr>"
					+"<tr></tr>";
			$(".manufactureDailyTable>thead").html(headTr);
			$(".manufactureDailyTable>tbody").html("");
			$.each(countSeries, function (series, seriesName){
				$("<tr />").attr("series", series).appendTo($(".manufactureDailyTable tbody"));
			})

			//first column description
			var pointTr = $(".manufactureDailyTable tr:eq(1)");
			// $("<th />").html("车系").appendTo(pointTr);
			$.each(countSeries, function (series, seriesName) {
				$("<td />").html(seriesName).appendTo($(".manufactureDailyTable tr[series=" + series + "]"));
			})

			//detail data
			$.each(countPoint, function (key, name) {
				th = $("<th />").addClass("alignCenterDaily").html(name).appendTo(pointTr);
				// if(key.indexOf("Month")>0){
				// 	th.addClass("countMonth");
				// }
				$.each(count[key], function (series, value){
					td = $("<td />").html(value).appendTo($(".manufactureDailyTable tr[series=" + series + "]"));
					// if(key.indexOf("Month")>0){
					// 	td.addClass("countMonth");
					// }
				})
			})

			$(".manufactureDailyTable").show();
		},

		drawColumn: function() {
			columnSeries = [];
			carSeries = this.ajaxData.carSeries;
			columnSeriesData = this.ajaxData.columnSeries;
			$.each(carSeries, function (index, series) {
				columnSeries[index] = {
					name: series,
					data: columnSeriesData.y[series]
				}
			})

			this.columnData.xAxis.categories = columnSeriesData.x;
			this.columnData.series = columnSeries;
			$("#manufacureDailyColumnContainer").highcharts(this.columnData);
		},

		drawDonut: function() {
        	var data = this.ajaxData.dataDonut;
        	colors = Highcharts.getOptions().colors;
        	var stateData = [];
	        var periodData = [];
	        $.each(data, function (key, value) {
	        	stateData.push({
	                name: key,
	                y: value.y,
	                color: colors[value.colorIndex],
	            });

	            // add version data
	            for (var j = 0; j < value.drilldown.data.length; j++) {
	                var brightness = 0.2 - (j / value.drilldown.data.length) / 5 ;
	                periodData.push({
	                    name: value.drilldown.categories[j],
	                    y: value.drilldown.data[j],
	                    color: Highcharts.Color(colors[value.colorIndex]).brighten(brightness).get()
	                });
	            }
	        })

	        this.donutData.series[0].data = stateData;
	        this.donutData.series[1].data = periodData;
	        $("#recycleDonutContainer").highcharts(this.donutData);
        },
	}

	window.report.completion = {
		// timespan : 'monthly',
		ajaxData: {},

		chartData: {
			"monthly" : {
				chart: {
					renderTo: '',
				},
				navigation: {
		            buttonOptions: {
		                enabled: false
		            }
		        },
				title: {
					text: ''
				},
				credits: {
					href: '',
					text: ''
				},
				tooltip: {
					shared: true,
					useHTML: true,
					formatter: function() {
		                var s = this.x +'<table>';
		                var sRate = '';
		                var sCar = '';
		                total = 0;
		                $.each(this.points, function(i, point) {
		                	if(point.series.name === "计划完成率"){
		                		sRate += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ Math.round(this.y * 100) +'%</b></td></tr>';
		                	} else {
		                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ point.y +'</b></td></tr>';
	            				total += this.y;
		                	}
		                });
		                s += sCar;
		                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>总计:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ total +'</b></td></tr>';
		                s += sRate;
		                s += '</table>';
		                return s;
		            },
				},
				legend: {
					layout: 'horizontal',
					align: 'center',
					verticalAlign: 'top',
					floating: false,
					backgroundColor: "white",
					borderRadius: 2,
					borderWidth: 0,
				},
				xAxis: {
					categories: [],
					labels: {
						// rotation: -45,
						align: 'center',
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
						stackLabels: {
		                    enabled: true,
		                    style: {
		                        fontWeight: 'bold',
		                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
		                    },
		                    formatter: function() {
		                    	if(this.total > 0){
				                    return this.total;
		                    	} else {
		                    		return null;
		                    	}
			                },
		                },
						title: {
							text: '车辆数',
							text: null,
							style: {
								color: Highcharts.getOptions().colors[4],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						min: 0,
						endOnTick: false,

					},{		// Secondary yAxis
						title: {
							enabled: false,
							text: '计划完成率',
							style: {
								color: Highcharts.getOptions().colors[5],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						labels: {
							enabled: false,
							formatter: function() {
								return Math.round(this.value * 100) + '%'
							},
							style: {
								color: Highcharts.getOptions().colors[5],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						plotBands: [{
		                	from: 0.6,
		                    to: 0.8,
		                    color: '#FCFFC5',
		                    // label: {
		                    // 	text: '50-80%',
		                    // 	align: 'right',
		                    // 	x: -10,
		                    // 	style: {
			                   //      color: 'white',
			                   //      fontWeight: 'bold'
			                   //  }
		                    // }
		                },{
		                    from: 0.8,
		                    to: 1,
		                    color: '#d0e9c6',
		                    label: {
		                    	text: '80%',
		                    	align: 'right',
		                    	x: -10,
		                    	style: {
			                        color: '#910000',
			                        fontWeight: 'bold'
			                    },
			                    verticalAlign: 'bottom',
		                    }
		                },{
		                	from: 0,
		                    to: 0.6,
		                    color: '#ebcccc',
		                    label: {
		                    	text: '60%',
		                    	align: 'right',
		                    	x: -10,
		                    	style: {
			                        color: '#910000',
			                        fontWeight: 'bold',
			                    },
			                    verticalAlign: 'top',
		                    }
		                }],
						max: 1,
						min: 0,
						opposite: true,
						gridLineWidth: 0,
						endOnTick: false,
					},

				],

				plotOptions: {
	                column: {
	                	stacking: 'normal',
	                    pointPadding: 0.1,
	                    borderWidth: 0,
	                    pointWidth: 15,
	                },
	                line:{
	                    dataLabels: { 
	                        enabled: true,
	                        formatter: function() {
	                        	// console.log(this.point);
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
			                        console.log(this);
	                        		console.log("len:" + this.series.chart.series.length);
	                        		console.log(this.series.chart.series);
			                        console.log(this.x + ":" + this.y);
			                        report.completion.toggleClickPointData("monthly", this.x, this.y, this.series.index);

			      					$(".completionChart[timespan=monthly]").highcharts(report.completion.chartData['monthly'])
			                    }
			                }
			            }
	            	}
	            },

				series: []
			},
			"yearly" : {
				chart: {
					renderTo: '',
					spacingTop: 48,
				},
				navigation: {
		            buttonOptions: {
		                enabled: false
		            }
		        },
				title: {
					text: ''
				},
				credits: {
					href: '',
					text: ''
				},
				tooltip: {
					shared: true,
					useHTML: true,
					formatter: function() {
		                var s = this.x +'<table>';
		                var sRate = '';
		                var sCar = '';
		                total = 0;
		                $.each(this.points, function(i, point) {
		                	if(point.series.name === "计划完成率"){
		                		sRate += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ Math.round(this.y * 100) +'%</b></td></tr>';
		                	} else {
		                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ point.y +'</b></td></tr>';
	            				total += this.y;
		                	}
		                });
		                s += sCar;
		                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>总计:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ total +'</b></td></tr>';
		                s += sRate;
		                s += '</table>';
		                return s;
		            },
				},
				legend: {
					enabled: false,
					layout: 'horizontal',
					align: 'center',
					verticalAlign: 'top',
					borderWidth: 0,
				},
				xAxis: {
					categories: [],
					labels: {
						// rotation: -45,
						align: 'center',
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
						stackLabels: {
		                    enabled: true,
		                    style: {
		                        fontWeight: 'bold',
		                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
		                    },
		                    formatter: function() {
		                    	if(this.total > 0){
				                    return this.total;
		                    	} else {
		                    		return null;
		                    	}
			                },
		                },
						title: {
							text: '车辆数',
							text: null,
							style: {
								color: Highcharts.getOptions().colors[4],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						min: 0,
						endOnTick: false,

					},{		// Secondary yAxis
						title: {
							enabled: false,
							text: '计划完成率',
							style: {
								color: Highcharts.getOptions().colors[5],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						labels: {
							enabled: false,
							formatter: function() {
								return Math.round(this.value * 100) + '%'
							},
							style: {
								color: Highcharts.getOptions().colors[5],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						plotBands: [{
		                	from: 0.6,
		                    to: 0.8,
		                    color: '#FCFFC5',
		                    // label: {
		                    // 	text: '50-80%',
		                    // 	align: 'right',
		                    // 	x: -10,
		                    // 	style: {
			                   //      color: 'white',
			                   //      fontWeight: 'bold'
			                   //  }
		                    // }
		                },{
		                    from: 0.8,
		                    to: 1,
		                    color: '#d0e9c6',
		                    label: {
		                    	text: '80%',
		                    	align: 'right',
		                    	x: -10,
		                    	style: {
			                        color: '#910000',
			                        fontWeight: 'bold'
			                    },
			                    verticalAlign: 'bottom',
		                    }
		                },{
		                	from: 0,
		                    to: 0.6,
		                    color: '#ebcccc',
		                    label: {
		                    	text: '60%',
		                    	align: 'right',
		                    	x: -10,
		                    	style: {
			                        color: '#910000',
			                        fontWeight: 'bold',
			                    },
			                    verticalAlign: 'top',
		                    }
		                }],
						max: 1,
						min: 0,
						opposite: true,
						gridLineWidth: 0,
						endOnTick: false,
					},

				],

				plotOptions: {
	                column: {
	                	stacking: 'normal',
	                    pointPadding: 0.1,
	                    borderWidth: 0,
	                    pointWidth: 15,
	                },
	                line:{
	                    dataLabels: { 
	                        enabled: true,
	                        formatter: function() {
	                        	// console.log(this.point);
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
			                        console.log(this);
	                        		console.log("len:" + this.series.chart.series.length);
	                        		console.log(this.series.chart.series);
			                        console.log(this.x + ":" + this.y);
			                        report.completion.toggleClickPointData("yearly", this.x, this.y, this.series.index);

			      					$(".completionChart[timespan=yearly]").highcharts(report.completion.chartData['yearly'])
			                    }
			                }
			            }
	            	}
	            },

				series: []
			},
		},

		drawColumnLine: function(timespan) {
			columnSeries = [];
			carSeries = this.ajaxData.carSeries;
			columnSeriesData = this.ajaxData.series.column;
			i=0;
			$.each(carSeries, function (index, series) {
				columnSeries[index] = {
					type: 'column',
					name: series,
					data: columnSeriesData[series]
				}
				i=index;
			})
			columnSeries[++i] ={
				type: 'line',
				yAxis: 1,
				showInLegend: false,
				name: '计划完成率',
				data: report.completion.prepare(this.ajaxData.series.line),
				// data: this.ajaxData.series.line,
				dataLabels:{
					enabled: true,
					style: {
						fontSize: '12px',
						fontWeight: 'bold',
						fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
					},
					align: 'center',
        			color: Highcharts.getOptions().colors[i],
        			// formatter: function() {
        			// 	return (this.y * 100).toFixed(0) + '%';
        			// }
				},
			}

			this.chartData[timespan].series = columnSeries;
			this.chartData[timespan].xAxis.categories = this.ajaxData.series.x;

			$("#tabCompletion .divLoading[timespan="+ timespan +"]").hide();
			$(".completionChart[timespan="+ timespan +"]").show().highcharts(this.chartData[timespan]);
		},

		updateTable: function(timespan) {
			var carSeries = this.ajaxData.carSeries;
			var countDetail = this.ajaxData.countDetail;
			var countTotal = this.ajaxData.countTotal;
			var completionDetail = this.ajaxData.completionDetail;
			var completionTotal = this.ajaxData.completionTotal;

			thead = $(".completionTable[timespan="+ timespan +"] thead").html("<tr />");
			tbody = $(".completionTable[timespan="+ timespan +"] tbody").html("");
			$.each(carSeries, function (index, value) {
				$("<tr />").appendTo(tbody);
			})

			var thTr = thead.children("tr:eq(0)");
			$("<th />").html("车系").attr("style", "min-width:60px").appendTo(thTr);
			$("<th />").html("合计").appendTo(thTr);

			$.each(carSeries, function (index, series) {
				$("<td />").html(series).appendTo($(".completionTable[timespan="+ timespan +"] tr:eq("+ (index*1+1) +")"));
				$("<td />").html(countTotal[series]).appendTo($(".completionTable[timespan="+ timespan +"] tr:eq("+ (index*1+1) +")"));
			});

			$.each(countDetail, function (index, value) {
				$("<td />").html(value.time).appendTo(thTr);
				$.each(carSeries, function (index, series) {
					$("<td />").html(value[series]).appendTo($(".completionTable[timespan="+ timespan +"] tr:eq("+ (index*1+1) +")"));
				})
			})

			trReadySum = $("<tr />").appendTo(tbody);
			trCompletion = $("<tr />").appendTo(tbody);
			$("<td />").html("总计").appendTo(trReadySum);
			$("<td />").html("完成率").appendTo(trCompletion);
			$("<td />").html(completionTotal.readySum).appendTo(trReadySum);
			$("<td />").html((completionTotal.completion*100).toFixed(0) + "%").appendTo(trCompletion);
			$.each(completionDetail, function (index, value) {
				$("<td />").html(value.readySum).appendTo(trReadySum);
				$("<td />").html((value.completion*100).toFixed(0) + "%").appendTo(trCompletion);
			})

			$(".completionTable[timespan="+ timespan +"]").show();
		},

		toggleClickPointData : function (timespan, x, y, index) {
			console.log(this.chartData);
			$(this.chartData[timespan].series[index].data[x]).each(function (index, value) {
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
        },
	}

	window.report.use = {
		ajaxData: {},

		chartData: {
			"monthly": {
				chart: {
					renderTo: '',
				},
				navigation: {
		            buttonOptions: {
		                enabled: false
		            }
		        },
				title: {
					text: ''
				},
				credits: {
					href: '',
					text: ''
				},
				tooltip: {
					shared: true,
					useHTML: true,
					formatter: function() {
		                var s = this.x +'<table>';
		                var sRate = '';
		                var sCar = '';
		                total = 0;
		                $.each(this.points, function(i, point) {
		                	if(point.series.name === "生产利用率"){
		                		sRate += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ Math.round(this.y * 100) +'%</b></td></tr>';
		                	} else {
		                		hh = parseInt(this.y / 3600);
		                		mm = parseInt((this.y%3600) / 60);
	            				ss = (this.y % 60);

	            				mm = mm<10 ? '0'+mm : mm;
	            				ss = ss<10 ? '0'+ss : ss;

		                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +':&nbsp&nbsp</td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ hh + ':' +  mm + '\'' + ss + '\"' +'</b></td></tr>';
	            				total += this.y;
		                	}
		                });
		                s += sCar;

		                hht = parseInt(total / 3600);
	            		mmt = parseInt((total%3600) / 60);
	    				sst = (total % 60);

	    				mmt = mmt<10 ? '0'+mmt : mmt;
	    				sst = sst<10 ? '0'+sst : sst;

		                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>总计:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ hht + ':' +  mmt + '\'' + sst + '\"' +'</b></td></tr>';
		                s += sRate;
		                s += '</table>';
		                return s;
	            },
				},
				legend: {
					layout: 'horizontal',
					align: 'center',
					verticalAlign: 'top',
					floating: false,
					backgroundColor: "white",
					borderRadius: 2,
					borderWidth: 0,
				},
				xAxis: {
					categories: [],
					labels: {
						// rotation: -45,
						align: 'center',
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
							},
							formatter: function() {
			                    	hh = parseInt(this.value / 3600).toFixed(0);
				                    return hh + "H";
			                }
						},
						stackLabels: {
		                    enabled: true,
		                    style: {
		                        fontWeight: 'bold',
		                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
		                    },
		                    formatter: function() {
		                    	if(this.total > 0){
			                    	hh = parseInt(this.total / 3600);
				            		mm = ((this.total%3600) / 60).toFixed(0);
				            		mm = mm<10 ? '0'+mm : mm;
				                    return hh + ':' +  mm + '\'';
		                    	} else {
		                    		return null;
		                    	}
			                }
		                },
						title: {
							text: '停线时长',
							text: null,
							style: {
								color: Highcharts.getOptions().colors[4],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						min: 0,
						endOnTick: false,

					},{		// Secondary yAxis
						title: {
							enabled: false,
							text: '生产利用率',
							style: {
								color: Highcharts.getOptions().colors[5],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						labels: {
							// enabled: false,
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
						opposite: true,
						gridLineWidth: 0,
						endOnTick: false,
					},

				],

				plotOptions: {
	                column: {
	                	stacking: 'normal',
	                    pointPadding: 0.1,
	                    borderWidth: 0,
	                    pointWidth: 15,
	                },
	                line:{
	                    dataLabels: { 
	                        enabled: true,
	                        formatter: function() {
	                        	// console.log(this.point);
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
			                        report.use.toggleClickPointData("monthly", this.x, this.y, this.series.index);

			      					$(".useChart[timespan=monthly]").highcharts(report.use.chartData['monthly'])
			                    }
			                }
			            }
	            	},
	            },

				series: []
			},
			"yearly": {
				chart: {
					renderTo: '',
					spacingTop: 48,
				},
				navigation: {
		            buttonOptions: {
		                enabled: false
		            }
		        },
				title: {
					text: ''
				},
				credits: {
					href: '',
					text: ''
				},
				tooltip: {
					shared: true,
					useHTML: true,
					formatter: function() {
		                var s = this.x +'<table>';
		                var sRate = '';
		                var sCar = '';
		                total = 0;
		                $.each(this.points, function(i, point) {
		                	if(point.series.name === "生产利用率"){
		                		sRate += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ Math.round(this.y * 100) +'%</b></td></tr>';
		                	} else {
		                		hh = parseInt(this.y / 3600);
		                		mm = parseInt((this.y%3600) / 60);
	            				ss = (this.y % 60);

	            				mm = mm<10 ? '0'+mm : mm;
	            				ss = ss<10 ? '0'+ss : ss;

		                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +':&nbsp&nbsp</td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ hh + ':' +  mm + '\'' + ss + '\"' +'</b></td></tr>';
	            				total += this.y;
		                	}
		                });
		                s += sCar;

		                hht = parseInt(total / 3600);
	            		mmt = parseInt((total%3600) / 60);
	    				sst = (total % 60);

	    				mmt = mmt<10 ? '0'+mmt : mmt;
	    				sst = sst<10 ? '0'+sst : sst;

		                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>总计:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ hht + ':' +  mmt + '\'' + sst + '\"' +'</b></td></tr>';
		                s += sRate;
		                s += '</table>';
		                return s;
	            },
				},
				legend: {
					enabled: false,
					layout: 'horizontal',
					align: 'center',
					verticalAlign: 'top',
					floating: false,
					backgroundColor: "white",
					borderRadius: 2,
					borderWidth: 0,
				},
				xAxis: {
					categories: [],
					labels: {
						// rotation: -45,
						align: 'center',
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
							},
							formatter: function() {
			                    	hh = parseInt(this.value / 3600).toFixed(0);
				                    return hh + "H";
			                }
						},
						stackLabels: {
		                    enabled: true,
		                    style: {
		                        fontWeight: 'bold',
		                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
		                    },
		                    formatter: function() {
		                    	if(this.total > 0){
			                    	hh = parseInt(this.total / 3600);
				            		// mm = ((this.total%3600) / 60).toFixed(0);
				            		// mm = mm<10 ? '0'+mm : mm;
				                    return hh;
		                    	} else {
		                    		return null;
		                    	}
			                }
		                },
						title: {
							text: '停线时长',
							text: null,
							style: {
								color: Highcharts.getOptions().colors[4],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						min: 0,
						endOnTick: false,

					},{		// Secondary yAxis
						title: {
							enabled: false,
							text: '生产利用率',
							style: {
								color: Highcharts.getOptions().colors[5],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						labels: {
							// enabled: false,
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
						opposite: true,
						gridLineWidth: 0,
						endOnTick: false,
					},

				],

				plotOptions: {
	                column: {
	                	stacking: 'normal',
	                    pointPadding: 0.1,
	                    borderWidth: 0,
	                    pointWidth: 15,
	                },
	                line:{
	                    dataLabels: { 
	                        enabled: true,
	                        formatter: function() {
	                        	// console.log(this.point);
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
			                        report.use.toggleClickPointData("yearly", this.x, this.y, this.series.index);

			      					$(".useChart[timespan=yearly]").highcharts(report.use.chartData['yearly'])
			                    }
			                }
			            }
	            	},
	            },

				series: []
			},
		},

		drawColumnLine: function(timespan) {
			columnSeries = [];
			causeArray = this.ajaxData.causeArray;
			columnSeriesData = this.ajaxData.series.column;
			i=0;
			$.each(causeArray, function (index, cause) {
				columnSeries[index] = {
					type: 'column',
					name: cause,
					data: columnSeriesData[cause]
				}
				i=index;
			})
			columnSeries[++i] ={
				type: 'line',
				yAxis: 1,
				showInLegend: false,
				name: '生产利用率',
				data: report.use.prepare(this.ajaxData.series.line),
				// data: this.ajaxData.series.line,
				dataLabels:{
					enabled: true,
					style: {
						fontSize: '12px',
						fontWeight: 'bold',
						fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
					},
					align: 'center',
        			color: Highcharts.getOptions().colors[i],
        			// formatter: function() {
        			// 	return (this.y * 100).toFixed(0) + '%';
        			// }
				},
			}

			this.chartData[timespan].series = columnSeries;
			this.chartData[timespan].xAxis.categories = this.ajaxData.series.x;
			
			$("#tabManufactureUse .divLoading[timespan="+ timespan +"]").hide();
			$(".useChart[timespan="+ timespan +"]").show();
			$(".useChart[timespan="+ timespan +"]").highcharts(this.chartData[timespan]);
			// $(".useChartPrint[timespan="+ timespan +"]").highcharts(this.chartData);
			
		},

		updateTable: function(timespan) {
			var pauseDetail = this.ajaxData.pauseDetail;
			$(".tablePause>tbody").html("");
			$.each(pauseDetail, function(index, value) {
				var num = value.details.length;
				var tmp = $("<tbody />");
				for(var i=0; i<num; i++){
					$("<tr />").appendTo(tmp);
				}
				var firstTr = tmp.children("tr:eq(0)");
				firstTr.addClass("thickBorder");
				causeTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(value.cause_type).appendTo(firstTr);
				dutyTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(value.duty_department).appendTo(firstTr);
				reasonTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(value.pause_reason).appendTo(firstTr);
				totalTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").addClass("alignRight").html(value.howlong).appendTo(firstTr);
				$.each(value.details,  function (index, detail){
					var tr = tmp.children("tr:eq("+ index +")");
					$("<td />").addClass("alignRight").html(detail.howlong).appendTo(tr);
					$("<td />").html(detail.pause_time.substr(0,16)).appendTo(tr);
					if(detail.recover_time === "0000-00-00 00:00:00"){
						$("<td />").html("未恢复").appendTo(tr);
					}else{
						$("<td />").html(detail.recover_time.substring(0,16)).appendTo(tr);
					}
					$("<td />").html(detail.node_name).appendTo(tr);
					
					tr.data("id",detail.id);
				})
				
				
				$(".tablePause tbody").append(tmp.children("tr"));
				
			});
	
			$(".tablePause").show();
		},

		toggleClickPointData : function (timespan, x, y, index) {
			console.log(this.chartData);
			$(this.chartData[timespan].series[index].data[x]).each(function (index, value) {
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
        },
	}

	window.report.recycle = {
		ajaxData: {},
		ajaxOvertimeData: {},

		chartData: {
			"monthly": {
				chart: {
					renderTo: '',
				},
				title: {
					text: ''
				},
				navigation: {
		            buttonOptions: {
		                enabled: false
		            }
		        },
				credits: {
					href: '',
					text: ''
				},
				tooltip: {
					shared: true,
					useHTML: true,
					formatter: function() {
		                var s = this.x +'<table>';
		                var sPeriod = '';
		                var sCar = '';
		                total = 0;
		                $.each(this.points, function(i, point) {
		                	if(point.series.name === "总装周期"){
		                		sPeriod += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ this.y +'H</b></td></tr>';
		                	} else {
		                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +':&nbsp&nbsp</td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ this.y +'</b></td></tr>';
	            				total += this.y;
		                	}
		                });
		                s += sCar;
		                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>总计:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ total +'</b></td></tr>';
		                s += sPeriod;
		                s += '</table>';
		                return s;
	            },
				},
				legend: {
					layout: 'horizontal',
					align: 'center',
					verticalAlign: 'top',
					floating: false,
					backgroundColor: "white",
					borderRadius: 2,
					borderWidth: 0,
				},
				xAxis: {
					categories: [],
					labels: {
						// rotation: -45,
						align: 'center',
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
								// color: Highcharts.getOptions().colors[0],
							}
						},
						stackLabels: {
		                    enabled: true,
		                    style: {
		                        fontWeight: 'bold',
		                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
		                    },
		                    formatter: function() {
		                    	if(this.total > 0){
				                    return this.total;
		                    	} else {
		                    		return null;
		                    	}
			                },
		                },
						title: {
							enabled: false,
							text: '结存车辆',
							style: {
								// color: Highcharts.getOptions().colors[4],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						min: 0,
						// endOnTick: false,

					},{		// Secondary yAxis
						title: {
							enabled: false,
							text: '总装周期(Hour)',
							style: {
								color: Highcharts.getOptions().colors[4],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						labels: {
							// enabled: false,
							style: {
								color: Highcharts.getOptions().colors[4],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},

						min: 0,
						opposite: true,
						// gridLineWidth: 0,
					},

				],

				plotOptions: {
	                column: {
	                	stacking: 'normal',
	                    pointPadding: 0.1,
	                    borderWidth: 0,
	                    pointWidth: 15,
	                },
	                line:{
	                    dataLabels: { 
	                        enabled: true,
	                        formatter: function() {
	                            if(!this.point.show)
	                                return '';
	                            return this.y + 'H';
	                        }
	                    }
	                },
	                series: {
	            		cursor: 'pointer',
	            		point: {
			                events: {
			                    click: function() {
			                        report.recycle.toggleClickPointData("monthly", this.x, this.y, this.series.index);
			      					$(".recycleChart[timespan=monthly]").highcharts(report.recycle.chartData['monthly'])
			                    }
			                }
			            }
	            	}
	            },

				series: []
			},
			"yearly":{
				chart: {
					renderTo: '',
					spacingTop: 47,
				},
				title: {
					text: ''
				},
				navigation: {
		            buttonOptions: {
		                enabled: false
		            }
		        },
				credits: {
					href: '',
					text: ''
				},
				tooltip: {
					shared: true,
					useHTML: true,
					formatter: function() {
		                var s = this.x +'<table>';
		                var sPeriod = '';
		                var sCar = '';
		                total = 0;
		                $.each(this.points, function(i, point) {
		                	if(point.series.name === "总装周期"){
		                		sPeriod += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ this.y +'H</b></td></tr>';
		                	} else {
		                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +':&nbsp&nbsp</td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ this.y +'</b></td></tr>';
	            				total += this.y;
		                	}
		                });
		                s += sCar;
		                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>总计:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ total +'</b></td></tr>';
		                s += sPeriod;
		                s += '</table>';
		                return s;
	            },
				},
				legend: {
					enabled: false,
					layout: 'horizontal',
					align: 'center',
					verticalAlign: 'top',
					floating: false,
					backgroundColor: "white",
					borderRadius: 2,
					borderWidth: 0,
				},
				xAxis: {
					categories: [],
					labels: {
						// rotation: -45,
						align: 'center',
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
								// color: Highcharts.getOptions().colors[0],
							}
						},
						stackLabels: {
		                    enabled: true,
		                    style: {
		                        fontWeight: 'bold',
		                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
		                    },
		                    formatter: function() {
		                    	if(this.total > 0){
				                    return this.total;
		                    	} else {
		                    		return null;
		                    	}
			                },
		                },
						title: {
							enabled: false,
							text: '结存车辆',
							style: {
								// color: Highcharts.getOptions().colors[4],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						min: 0,
						// endOnTick: false,

					},{		// Secondary yAxis
						title: {
							enabled: false,
							text: '总装周期(Hour)',
							style: {
								color: Highcharts.getOptions().colors[4],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						labels: {
							// enabled: false,
							style: {
								color: Highcharts.getOptions().colors[4],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},

						min: 0,
						opposite: true,
						// gridLineWidth: 0,
					},

				],

				plotOptions: {
	                column: {
	                	stacking: 'normal',
	                    pointPadding: 0.1,
	                    borderWidth: 0,
	                    pointWidth: 15,
	                },line:{
	                    dataLabels: { 
	                        enabled: true,
	                        formatter: function() {
	                            if(!this.point.show)
	                                return '';
	                            return this.y + 'H';
	                        }
	                    }
	                },
	                series: {
	            		cursor: 'pointer',
	            		point: {
			                events: {
			                    click: function() {
			                        report.recycle.toggleClickPointData("yearly", this.x, this.y, this.series.index);
			      					$(".recycleChart[timespan=yearly]").highcharts(report.recycle.chartData['yearly'])
			                    }
			                }
			            }
	            	}
	            },

				series: []
			},
		},

		drawColumnLine: function(timespan) {
			columnSeries = [];
			stateArray = this.ajaxData.stateArray;
			columnSeriesData = this.ajaxData.series.column;
			i=0;
			$.each(stateArray, function (index, state) {
				columnSeries[index] = {
					type: 'column',
					name: state,
					data: columnSeriesData[state]
				}
				i=index;
			})
			columnSeries[++i] ={
				type: 'line',
				yAxis: 1,
				showInLegend: false,
				name: '总装周期',
				data: report.recycle.prepare(this.ajaxData.series.line),
				// data: this.ajaxData.series.line,
				dataLabels:{
					enabled: true,
					style: {
						fontSize: '12px',
						fontWeight: 'bold',
						fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
					},
					align: 'center',
        			color: Highcharts.getOptions().colors[i],
        			// formatter: function() {
        			// 	ret = this.y == null ? null : this.y + 'H';
        			// 	return ret;
        			// }
				},
			}

			this.chartData[timespan].series = columnSeries;
			this.chartData[timespan].xAxis.categories = this.ajaxData.series.x;

			$("#tabRecycle .divLoading[timespan="+ timespan +"]").hide();
			$(".recycleChart[timespan="+ timespan +"]").show().highcharts(this.chartData[timespan]);
		},

		updateTable: function(timespan) {
			cars = this.ajaxOvertimeData;
			tbody = $(".overtimeCarsTable tbody").html("");
			$.each(cars, function (index, car){
				tr = $("<tr />");
				$("<td />").html(car.recycle_period + "H").appendTo(tr);
				$("<td />").html(byd.SeriesName[car.series]).appendTo(tr);
				$("<td />").html(car.serial_number).appendTo(tr);
				$("<td />").html(car.vin).appendTo(tr);
				$("<td />").html(car.config_name).appendTo(tr);
				$("<td />").html(car.color).appendTo(tr);
				$("<td />").html(car.status).appendTo(tr);
				remark = car.node_remark == "" ? car.faults : car.node_remark + "。" + car.faults
				$("<td />").html(remark).appendTo(tr);

				tr.appendTo(tbody);
			})
			$(".overtimeCarsTable").show();
		},

		toggleClickPointData : function (timespan, x, y, index) {
			$(this.chartData[timespan].series[index].data[x]).each(function (index, value) {
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
        },
	}

	window.report.warehouse = {
		ajaxData: {},
		ajaxOvertimeData: {},
		chartData: {
			"monthly":{
				chart: {
					renderTo: '',
				},
				title: {
					text: ''
				},
				navigation: {
		            buttonOptions: {
		                enabled: false
		            }
		        },
				credits: {
					href: '',
					text: ''
				},
				tooltip: {
					shared: true,
					useHTML: true,
					formatter: function() {
		                var s = this.x +'<table>';
		                var sRate = '';
		                var sCar = '';
		                total = 0;
		                $.each(this.points, function(i, point) {
		                	if(point.series.name === "成品库周期平均"){
		                		sRate += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ this.y +'H</b></td></tr>';
		                	} else {
		                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ point.y +'</b></td></tr>';
	            				total += this.y;
		                	}
		                });
		                s += sCar;
		                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>总计:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ total +'</b></td></tr>';
		                s += sRate;
		                s += '</table>';
		                return s;
		            },
				},
				legend: {
					enabled: true,
					layout: 'horizontal',
					align: 'center',
					verticalAlign: 'top',
					floating: false,
					backgroundColor: "white",
					borderRadius: 2,
					borderWidth: 0,
				},
				xAxis: {
					categories: [],
					labels: {
						// rotation: -45,
						align: 'center',
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
								color: Highcharts.getOptions().colors[0],
							}
						},
						stackLabels: {
		                    enabled: true,
		                    style: {
		                        fontWeight: 'bold',
		                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
		                    },
		                    formatter: function() {
		                    	if(this.total > 0){
				                    return this.total;
		                    	} else {
		                    		return null;
		                    	}
			                },
		                },
						title: {
							text: '车辆数',
							text: null,
							style: {
								color: Highcharts.getOptions().colors[0],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						min: 0,
						endOnTick: true,

					},{		// Secondary yAxis
						title: {
							enabled: false,
							text: '平均周期',
							style: {
								color: Highcharts.getOptions().colors[3],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						labels: {
							enabled: true,
							formatter: function() {
								return this.value + 'H'
							},
							style: {
								color: Highcharts.getOptions().colors[3],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						min: 0,
						opposite: true,
						gridLineWidth: 0,
						endOnTick: true,
					},

				],

				plotOptions: {
	                column: {
	                	stacking: 'normal',
	                    pointPadding: 0.1,
	                    borderWidth: 0,
	                    pointWidth: 15,
	                },
	                line:{
	                    dataLabels: { 
	                        enabled: true,
	                        formatter: function() {
	                            if(!this.point.show)
	                                return '';
	                            return this.y + 'H';
	                        }
	                    }
	                },
	                series: {
	            		cursor: 'pointer',
	            		point: {
			                events: {
			                    click: function() {
			                        report.warehouse.toggleClickPointData("monthly", this.x, this.y, this.series.index);
			      					$(".warehouseChart[timespan=monthly]").highcharts(report.warehouse.chartData['monthly'])
			                    }
			                }
			            }
	            	}
	            },

				series: []
			},
			"yearly":{
				chart: {
					renderTo: '',
					spacingTop: 48,
				},
				title: {
					text: ''
				},
				navigation: {
		            buttonOptions: {
		                enabled: false
		            }
		        },
				credits: {
					href: '',
					text: ''
				},
				tooltip: {
					shared: true,
					useHTML: true,
					formatter: function() {
		                var s = this.x +'<table>';
		                var sRate = '';
		                var sCar = '';
		                total = 0;
		                $.each(this.points, function(i, point) {
		                	if(point.series.name === "成品库周期平均"){
		                		sRate += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ this.y +'H</b></td></tr>';
		                	} else {
		                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ point.y +'</b></td></tr>';
	            				total += this.y;
		                	}
		                });
		                s += sCar;
		                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>总计:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ total +'</b></td></tr>';
		                s += sRate;
		                s += '</table>';
		                return s;
		            },
				},
				legend: {
					enabled: false,
					layout: 'horizontal',
					align: 'center',
					verticalAlign: 'top',
					floating: false,
					backgroundColor: "white",
					borderRadius: 2,
					borderWidth: 0,
				},
				xAxis: {
					categories: [],
					labels: {
						// rotation: -45,
						align: 'center',
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
								color: Highcharts.getOptions().colors[0],
							}
						},
						stackLabels: {
		                    enabled: true,
		                    style: {
		                        fontWeight: 'bold',
		                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
		                    },
		                    formatter: function() {
		                    	if(this.total > 0){
				                    return this.total;
		                    	} else {
		                    		return null;
		                    	}
			                },
		                },
						title: {
							text: '车辆数',
							text: null,
							style: {
								color: Highcharts.getOptions().colors[0],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						min: 0,
						endOnTick: true,

					},{		// Secondary yAxis
						title: {
							enabled: false,
							text: '平均周期',
							style: {
								color: Highcharts.getOptions().colors[3],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						labels: {
							enabled: true,
							formatter: function() {
								return this.value + 'H'
							},
							style: {
								color: Highcharts.getOptions().colors[3],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						min: 0,
						opposite: true,
						gridLineWidth: 0,
						endOnTick: true,
					},

				],

				plotOptions: {
	                column: {
	                	stacking: 'normal',
	                    pointPadding: 0.1,
	                    borderWidth: 0,
	                    pointWidth: 15,
	                },
	                line:{
	                    dataLabels: { 
	                        enabled: true,
	                        formatter: function() {
	                            if(!this.point.show)
	                                return '';
	                            return this.y + 'H';
	                        }
	                    }
	                },
	                series: {
	            		cursor: 'pointer',
	            		point: {
			                events: {
			                    click: function() {
			                        report.warehouse.toggleClickPointData("yearly", this.x, this.y, this.series.index);
			      					$(".warehouseChart[timespan=yearly]").highcharts(report.warehouse.chartData['yearly'])
			                    }
			                }
			            }
	            	}
	            },

				series: []
			},
		},

		drawColumnLine: function(timespan) {
			columnSeries = [];
			carSeries = this.ajaxData.carSeries;
			columnSeriesData = this.ajaxData.series.column;
			i=0;
			$.each(carSeries, function (index, series) {
				columnSeries[index] = {
					type: 'column',
					name: series,
					data: columnSeriesData[series]
				}
				i=index;
			})
			columnSeries[++i] ={
				type: 'line',
				yAxis: 1,
				showInLegend: false,
				name: '成品库周期平均',
				data: report.warehouse.prepare(this.ajaxData.series.line),
				// data: this.ajaxData.series.line,
				dataLabels:{
					enabled: true,
					style: {
						fontSize: '12px',
						fontWeight: 'bold',
						fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
					},
					align: 'center',
        			color: Highcharts.getOptions().colors[i],
        			// formatter: function() {
        			// 	return this.y +'H';
        			// }
				},
			}

			this.chartData[timespan].series = columnSeries;
			this.chartData[timespan].xAxis.categories = this.ajaxData.series.x;

			$("#tabWarehouse .divLoading[timespan="+ timespan +"]").hide();
			$(".warehouseChart[timespan="+ timespan +"]").show().highcharts(this.chartData[timespan]);
		},

		updateTable: function() {
			boards = this.ajaxOvertimeData;
			tbody = $(".overtimeOrdersTable tbody").html("");
			$.each(boards, function (board, value) {
				var num = value.orders.length;
				var tmp = $("<tbody />");
				for(var i=0; i<num; i++){
					$("<tr />").appendTo(tmp);
				}

				$.each(value.orders, function (index, order) {
					tr = tmp.children("tr:eq("+ index +")");
					$("<td />").html(order.lane_name).appendTo(tr);
					$("<td />").html(order.order_number).appendTo(tr);
					// $("<td />").html(order.distributor_name).appendTo(tr);
					$("<td />").html(order.series_name).appendTo(tr);
					$("<td />").html(order.car_type_config).appendTo(tr);
					$("<td />").html(order.color).appendTo(tr);
					$("<td />").html(order.amount).addClass('alignRight').appendTo(tr);
    				$("<td />").html(order.hold).addClass('alignRight').appendTo(tr);
    				$("<td />").html(order.count).addClass('alignRight').appendTo(tr);
    				$("<td />").html(order.activate_time).appendTo(tr);
				})
				var firstTr = tmp.children("tr:eq(0)");
	    		firstTr.addClass("thickBorder");
	    		boardTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(value.boardNumber).prependTo(firstTr);
	    		periodTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(value.boardWarehousePeriod + "H").appendTo(firstTr);
	    		$(".overtimeOrdersTable tbody").append(tmp.children("tr"));
			})

			$(".overtimeOrdersTable").show();
		},

		toggleClickPointData : function (timespan, x, y, index) {
			$(this.chartData[timespan].series[index].data[x]).each(function (index, value) {
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
        },
	}
})