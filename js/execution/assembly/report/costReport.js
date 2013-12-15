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

    $(".queryReplacementCost").click(function(){
    	point = $(this).attr("point");
    	ajaxQueryReplacementCost("monthly");
    	ajaxQueryReplacementCost("yearly");
    	ajaxQueryCostDistribute();
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
		$("#headGeneralInformationLi").addClass("active");
		getSeries();
		$("#divLeft,#divHead,#divFoot").addClass("notPrintable");
		$("#startTime").val(window.byd.DateUtil.lastWorkDate());

		ajaxQueryReplacementCost("monthly");
    	ajaxQueryReplacementCost("yearly");
    	ajaxQueryCostDistribute();
		resetAll();
	}

	function resetAll (argument) {
		$(".initHide").hide();
	}

	function getSeries () {
		$.ajax({
			url: GET_SERIES_LIST,
			dataType: "json",
			data: {},
			async: false,
			error: function () {alertError();},
			success: function (response) {
				if(response.success){
					options = $.templates("#tmplSeriesRadio").render(response.data);
					$(".seriesRadio").append(options);
				} else {
					alert(response.message);
				}
			}
		})
	}

//END commonfunction --------------------


//ajax query -------------------------------------------

	function ajaxQueryReplacementCost(timespan) {
		$(".replacementCostChart[timespan="+ timespan +"]").hide();
		$(".tabReplacementCost .divLoading[timespan="+ timespan +"]").show();
		$.ajax({
			url: QUERY_REPLACEMENT_COST_REPORT,
			type: "get",
			dataType: "json",
			data: {
				"date": $("#startTime").val(),
				"timespan": timespan,
				// "series" : $(":radio[name=seriesRadios]:checked").val(),
				"series" : "all",
			},
			error: function(){alertError();},
			success: function(response){
				if(response.success){
					report.replacementCost.ajaxData = response.data;
					report.replacementCost.drawColumnLine(timespan);
				} else {
					alert(response.message);
				}
			},
		})
	}

	function ajaxQueryCostDistribute() {
		$(".dutyChart").hide();
		$(".dutyAreaChart").hide();
		$(".tabReplacementCost .divLoading[chart=column]").show();
		$(".tabReplacementCost .divLoading[chart=donut]").show();
		$.ajax({
			url: QUERY_COST_DISTRIBUTE_REPORT,
			type: "get",
			dataType: "json",
			data: {
				"date": $("#startTime").val(),
				// "series" : $(":radio[name=seriesRadios]:checked").val(),
				"series" : "all",
			},
			error: function(){alertError();},
			success: function(response){
				if(response.success){
					report.costDistribute.ajaxData = response.data;
					report.costDistribute.drawColumn();
					report.costDistribute.drawDonut();
				} else {
					alert(response.message);
				}
			},
		})
	}
//END ajax query ---------------------------------------

});

!$(function() {
	window.report = window.report || {};
	window.report.replacementCost = {
		point: "",
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
		                	if(point.series.name === "综合CPU"){
		                		sRate += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ this.y +'</b></td></tr>';
		                	} else {
		                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ point.y +'</b></td></tr>';
	            				total += this.y;
		                	}
		                });
		                s += sCar;
		                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>累加CPU:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ total.toFixed(2) +'</b></td></tr>';
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
				                    return this.total.toFixed(1);
		                    	} else {
		                    		return null;
		                    	}
			                },
		                },
						title: {
							text: 'CPU',
							text: null,
							style: {
								color: Highcharts.getOptions().colors[4],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						min: 0,
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
	                            return this.y;
	                        }
	                    }
	                },
	                series: {
	            		cursor: 'pointer',
	            		point: {
			                events: {
			                    click: function() {
			                        report.replacementCost.toggleClickPointData("monthly", this.x, this.y, this.series.index);
			      					$(".replacementCostChart[timespan=monthly]").highcharts(report.replacementCost.chartData['monthly']);
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
		                	if(point.series.name === "综合CPU"){
		                		sRate += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ this.y  +'</b></td></tr>';
		                	} else {
		                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ point.y +'</b></td></tr>';
	            				total += this.y;
		                	}
		                });
		                s += sCar;
		                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>累加CPU:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ total.toFixed(2) +'</b></td></tr>';
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
				                    return this.total.toFixed(1);
		                    	} else {
		                    		return null;
		                    	}
			                },
		                },
						title: {
							text: 'CPU',
							text: null,
							style: {
								color: Highcharts.getOptions().colors[4],
								fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
							}
						},
						min: 0,
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
	                            return Math.round(this.y * 100) + '%';
	                        }
	                    }
	                },
	                series: {
	            		cursor: 'pointer',
	            		point: {
			                events: {
			                    click: function() {
			                    	report.replacementCost.toggleClickPointData("yearly", this.x, this.y, this.series.index);
			      					$(".replacementCostChart[timespan=yearly]").highcharts(report.replacementCost.chartData['yearly']);
			                    }
			                }
			            }
	            	}
	            },

				series: []
			},
		},

		drawColumnLine: function(timespan) {
			point = this.point;
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
				// yAxis: 1,
				showInLegend: true,
				name: '综合CPU',
				data: report.replacementCost.prepare(this.ajaxData.series.line),
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

			$(".tabReplacementCost .divLoading[timespan="+ timespan +"]").hide();
			$(".replacementCostChart[timespan="+ timespan +"]").show().highcharts(this.chartData[timespan]);
		},

		toggleClickPointData: function(timespan, x, y, index) {
			$(this.chartData[timespan].series[index].data[x]).each(function (index, value) {
				if(value.y == y && value.x == x) {
					value.show = !value.show;
					return false;
				}
			})
		},

		prepare: function(dataArray) {
			return $(dataArray).map(function (index, item) {
				return {x:index, y: item, show: false}
			})
		},
	}

	window.report.costDistribute = {
		point: "",
		ajaxData: {},
		columnData: {
			chart: {
                type: 'column',
                // spacingLeft: 70,
            },
            navigation: {
	            buttonOptions: {
	                enabled: false
	            }
	        },
            title: {
                text: '',
            },
            subtitle: {
                text: ''
            },
            legend: {
				enabled: true,
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
						fontSize: '10px',
						fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
					}
				}
            },
            credits: {
                enabled: false
            },
            yAxis: {
                min: 0,
                title: {
                	enabled: false,
                    text: '金额',
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
                formatter: function() {
	                var s = this.x +'<table>';
	                var sRate = '';
	                var sCar = '';
	                total = 0;
	                $.each(this.points, function(i, point) {
	                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ point.y +'</b></td></tr>';
            				total += this.y;
	                });
	                s += sCar;
	                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>累加CPU:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ total.toFixed(2) +'</b></td></tr>';
	                s += sRate;
	                s += '</table>';
	                return s;
	            },
                shared: true,
                useHTML: true,
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
		},
		donutData: {
            chart: {
                type: 'pie',
                spacingLeft: 120,
                spacingRight: 80,
            },
            navigation: {
            	enabled: false,
	            buttonOptions: {
	                enabled: false
	            }
	        },
            title: {
                enabled: false,
                text: '',
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
                    // center: ['50%', '50%']
                }
            },
            tooltip: {
                shared: true,
                useHTML: true,
        	    formatter: function() {
        	    	s = "<span>" + this.key +": " + (this.y*100).toFixed(1) + "%</span>";
                	return s;
                },
            },
            series: [{
                name: '换件区域',
                data: [],
                size: '90%',
                dataLabels: {
                    formatter: function() {
                        return this.y > 0.01 ? '<b>'+ this.point.name +'</b> ' + "[" + (this.y*100).toFixed(1) +"%]": null;
                    },
                    // color: 'white',
                    // distance: -30
                }
            }
	            // ,{
	            //     name: '处置',
	            //     data: [],
	            //     size: '80%',
	            //     innerSize: '60%',
	            //     dataLabels: {
	            //     	distance: 5,
	            //         formatter: function() {
	            //             // display only if larger than 0
	            //             return (this.y > 0.01 && this.key != "-") ? '<b>'+ this.point.name +'</b> '+  "[" + (this.y*100).toFixed(1) + "%]" : null;
	            //         }
	            //     }
	            // }
            ]
        },

        drawColumn: function() {
        	columnSeries = [];
        	carSeries = this.ajaxData.carSeries;
        	columnSeriesData = this.ajaxData.columnData;
        	$.each(carSeries, function (index, series) {
        		columnSeries[index] = {
        			name: series,
        			data: columnSeriesData.columnSeriesY[series],
        		}
        	})
        	this.columnData.xAxis.categories = columnSeriesData.columnSeriesX;
        	this.columnData.series = columnSeries;
        	$(".tabReplacementCost .divLoading[chart=column]").hide();
        	$(".dutyChart").show().highcharts(this.columnData);
        },

        drawDonut: function() {
        	data = this.ajaxData.donutData;
        	areaData = [];
        	$.each(data, function (key, value) {
        		areaData.push({
        			name: key,
        			y: value.y,
        		});
        	})

        	this.donutData.series[0].data = areaData;
        	$(".tabReplacementCost .divLoading[chart=donut]").hide();
	        $(".dutyAreaChart").show().highcharts(this.donutData);
        }
	}
})