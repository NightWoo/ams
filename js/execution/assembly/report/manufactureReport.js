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
    	ajaxQueryCompletion("monthly");
    	ajaxQueryCompletion("yearly");
    })

    $(".queryUse").click(function(){
    	timespan = $(this).attr("timespan");
    	ajaxQueryUse("monthly");
    	ajaxQueryUse("yearly");
    })

    $(".queryRecycle").click(function(){
    	timespan = $(this).attr("timespan");
    	ajaxQueryRecycle("monthly");
    	ajaxQueryRecycle("yearly");
    	ajaxQueryOvertimeCars();
    })
//END event bindings -------------------------


//common function -------------------------

	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftManufactureReportLi").addClass("active");

		$("#startTime").val(window.byd.DateUtil.lastWorkDate());
		ajaxQueryManufactureDaily();
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
		$
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
					report.completion.ajaxData = response.data;
					report.completion.drawColumnLine(timespan);
					// report.completion.updateTable(timespan);
				}
			}
		})
	}

	function ajaxQueryUse(timespan){
		$(".useTable").hide();
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
					report.use.ajaxData = response.data;
					report.use.drawColumnLine(timespan);
					// report.use.updateTable(timespan);
				}
			}
		})
	}

	function ajaxQueryRecycle(timespan){
		$("#overtimeCarsTable").hide();
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
			$("#manufactureDailyTable thead").html("<tr />");
			$("#manufactureDailyTable tbody").html("");
			$.each(countSeries, function (series, seriesName){
				$("<tr />").attr("series", series).appendTo($("#manufactureDailyTable tbody"));
			})

			//first column description
			var pointTr = $("#manufactureDailyTable tr:eq(0)");
			$("<th />").html("车系").appendTo(pointTr);
			$.each(countSeries, function (series, seriesName) {
				$("<td />").html(seriesName).appendTo($("#manufactureDailyTable tr[series=" + series + "]"));
			})

			//detail data
			$.each(countPoint, function (key, name) {
				th = $("<th />").addClass("alignCenter").html(name).appendTo(pointTr);
				if(key.indexOf("Month")>0){
					th.addClass("countMonth");
				}
				$.each(count[key], function (series, value){
					td = $("<td />").html(value).appendTo($("#manufactureDailyTable tr[series=" + series + "]"));
					if(key.indexOf("Month")>0){
						td.addClass("countMonth");
					}
				})
			})

			$("#manufactureDailyTable").show();
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
		ajaxData: {},

		chartData: {
			chart: {
				renderTo: '',
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
					stackLabels: {
	                    enabled: true,
	                    style: {
	                        fontWeight: 'bold',
	                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
	                    }
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
                }
            },

			series: []
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
				data: this.ajaxData.series.line,
				dataLabels:{
					enabled: true,
					style: {
						fontSize: '14px',
						fontWeight: 'bold',
						fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
					},
					align: 'center',
        			color: Highcharts.getOptions().colors[i],
        			formatter: function() {
        				return (this.y * 100).toFixed(0) + '%';
        			}
				},
			}

			this.chartData.series = columnSeries;
			this.chartData.xAxis.categories = this.ajaxData.series.x;

			$(".completionChart[timespan="+ timespan +"]").highcharts(this.chartData);
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
	}

	window.report.use = {
		ajaxData: {},

		chartData: {
			chart: {
				renderTo: '',
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
					// plotBands: [{
	    //             	from: 0.6,
	    //                 to: 0.8,
	    //                 color: '#FCFFC5',
	    //                 // label: {
	    //                 // 	text: '50-80%',
	    //                 // 	align: 'right',
	    //                 // 	x: -10,
	    //                 // 	style: {
		   //                 //      color: 'white',
		   //                 //      fontWeight: 'bold'
		   //                 //  }
	    //                 // }
	    //             },{ 
	    //                 from: 0.8,
	    //                 to: 1,
	    //                 color: '#d0e9c6',
	    //                 label: {
	    //                 	text: '80%',
	    //                 	align: 'right',
	    //                 	x: -10,
	    //                 	style: {
		   //                      color: '#492970',
		   //                      fontWeight: 'bold'
		   //                  },
		   //                  verticalAlign: 'bottom',
	    //                 }
	    //             },{
	    //             	from: 0,
	    //                 to: 0.6,
	    //                 color: '#ebcccc',
	    //                 label: {
	    //                 	text: '60%',
	    //                 	align: 'right',
	    //                 	x: -10,
	    //                 	style: {
		   //                      color: '#492970',
		   //                      fontWeight: 'bold',
		   //                  },
		   //                  verticalAlign: 'top',
	    //                 }
	    //             }],
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
                }
            },

			series: []
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
				data: this.ajaxData.series.line,
				dataLabels:{
					enabled: true,
					style: {
						fontSize: '14px',
						fontWeight: 'bold',
						fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
					},
					align: 'center',
        			color: Highcharts.getOptions().colors[i],
        			formatter: function() {
        				return (this.y * 100).toFixed(0) + '%';
        			}
				},
			}

			this.chartData.series = columnSeries;
			this.chartData.xAxis.categories = this.ajaxData.series.x;

			$(".useChart[timespan="+ timespan +"]").highcharts(this.chartData);
		},

		updateTable: function(timespan) {
			var causeArray = this.ajaxData.causeArray;
			var pauseDetail = this.ajaxData.pauseDetail;
			var pauseTotal = this.ajaxData.pauseTotal;
			var useDetail = this.ajaxData.useDetail;
			var useTotal = this.ajaxData.useTotal;

			thead = $(".useTable[timespan="+ timespan +"] thead").html("<tr />");
			tbody = $(".useTable[timespan="+ timespan +"] tbody").html("");
			$.each(causeArray, function (index, cause) {
				$("<tr />").appendTo(tbody);
			})
			trPauseSum = $("<tr />").appendTo(tbody);

			var thTr = thead.children("tr:eq(0)");
			$("<th />").html("类别").attr("style", "min-width:60px").appendTo(thTr);
			$("<th />").html("合计").appendTo(thTr);

			$.each(causeArray, function (index, cause) {
				$("<td />").html(cause).appendTo($(".useTable[timespan="+ timespan +"] tr:eq("+ (index*1+1) +")"));
				$("<td />").html(pauseTotal[cause]).appendTo($(".useTable[timespan="+ timespan +"] tr:eq("+ (index*1+1) +")"));
			});
			$("<td />").html('停线总计').appendTo(trPauseSum);
			$("<td />").html(pauseTotal['总计']).appendTo(trPauseSum);

			$.each(pauseDetail, function (index, value) {
				$("<td />").html(value.time).appendTo(thTr);
				$.each(causeArray, function (index, cause) {
					$("<td />").html(value[cause]).appendTo($(".useTable[timespan="+ timespan +"] tr:eq("+ (index*1+1) +")"));
				})
				$("<td />").html(value['总计']).appendTo(trPauseSum);
			})

			trRunTime = $("<tr />").appendTo(tbody);
			trUseRate = $("<tr />").appendTo(tbody);
			$("<td />").html("总工时").appendTo(trRunTime);
			$("<td />").html("利用率").appendTo(trUseRate);
			$("<td />").html(useTotal.runTime).appendTo(trRunTime);
			$("<td />").html(useTotal.useRate).appendTo(trUseRate);
			$.each(useDetail, function (index, value) {
				$("<td />").html(value.runTime).appendTo(trRunTime);
				$("<td />").html(value.useRate).appendTo(trUseRate);
			})

			$(".useTable[timespan="+ timespan +"]").show();
		},
	}

	window.report.recycle = {
		ajaxData: {},
		ajaxOvertimeData: {},

		chartData: {
			chart: {
				renderTo: '',
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
							// color: Highcharts.getOptions().colors[0],
						}
					},
					stackLabels: {
	                    enabled: true,
	                    style: {
	                        fontWeight: 'bold',
	                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
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
                }
            },

			series: []
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
				data: this.ajaxData.series.line,
				dataLabels:{
					enabled: true,
					style: {
						fontSize: '14px',
						fontWeight: 'bold',
						fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
					},
					align: 'center',
        			color: Highcharts.getOptions().colors[i],
        			formatter: function() {
        				ret = this.y == null ? null : this.y + 'H';
        				return ret;
        			}
				},
			}

			this.chartData.series = columnSeries;
			this.chartData.xAxis.categories = this.ajaxData.series.x;

			$(".recycleChart[timespan="+ timespan +"]").highcharts(this.chartData);
		},

		updateTable: function(timespan) {
			cars = this.ajaxOvertimeData;
			tbody = $("#overtimeCarsTable tbody").html("");
			$.each(cars, function (index, car){
				tr = $("<tr />");
				$("<td />").html(byd.SeriesName[car.series]).appendTo(tr);
				$("<td />").html(car.serial_number).appendTo(tr);
				$("<td />").html(car.vin).appendTo(tr);
				$("<td />").html(car.config_name).appendTo(tr);
				$("<td />").html(car.color).appendTo(tr);
				$("<td />").html(car.status).appendTo(tr);
				$("<td />").html(car.faults).appendTo(tr);
				$("<td />").html(car.recycle_period + "H").appendTo(tr);

				tr.appendTo(tbody);
			})
			$("#overtimeCarsTable").show();
		},
	}
})