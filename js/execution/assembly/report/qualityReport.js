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

    $(".queryQualification").click(function(){
    	point = $(this).attr("point");
    	ajaxQueryQualification(point, "monthly");
    	ajaxQueryQualification(point, "yearly");
    	ajaxQueryFaultDistribute(point);
    	$("#tabUl>li").addClass("notPrintable");
    	$(this).parent("li").removeClass("notPrintable");
    })

    $(".print").click(function() {
    	window.print();
    })

    $(":radio[name=seriesRadios]").click(function(){
    	series = $(":radio[name=seriesRadios]:checked").val();
    });
//END event bindings -------------------------


//common function -------------------------

	function initPage(){
		//add head class
		$("#headGeneralInformationLi").addClass("active");
		$("#leftQualityReportLi").addClass("active");
		$("#divLeft,#divHead,#divFoot").addClass("notPrintable");
		$("#startTime").val(window.byd.DateUtil.lastWorkDate());

		ajaxQueryQualification("VQ1", "monthly");
    	ajaxQueryQualification("VQ1", "yearly");
    	ajaxQueryFaultDistribute("VQ1");
		resetAll();
	}

	function resetAll (argument) {
		$(".initHide").hide();
	}

//END commonfunction --------------------


//ajax query -------------------------------------------

	function ajaxQueryQualification(point,timespan) {
		$(".qualificationChart[point="+ point +"][timespan="+ timespan +"]").hide();
		$(".tabQualification[point="+ point +"] .divLoading[timespan="+ timespan +"]").show();
		$.ajax({
			url: QUERY_QUALIFICATION_REPORT,
			type: "get",
			dataType: "json",
			data: {
				"point": point,
				"date": $("#startTime").val(),
				"timespan": timespan,
				"series" : $(":radio[name=seriesRadios]:checked").val(),
			},
			error: function(){alertError();},
			success: function(response){
				if(response.success){
					report.qualification.point = point;
					report.qualification.ajaxData = response.data;
					report.qualification.drawColumnLine(timespan);
				} else {
					alert(response.message);
				}
			},
		})
	}

	function ajaxQueryFaultDistribute(point) {
		$(".faultsChart[point="+ point +"]").hide();
		$(".dutyChart[point="+ point +"]").hide();
		$(".tabQualification[point="+ point +"] .divLoading[chart=column]").show();
		$(".tabQualification[point="+ point +"] .divLoading[chart=donut]").show();
		$.ajax({
			url: QUERY_FAULT_DISTRIBUTE_REPORT,
			type: "get",
			dataType: "json",
			data: {
				"point": point,
				"date": $("#startTime").val(),
				"series" : $(":radio[name=seriesRadios]:checked").val(),
			},
			error: function(){alertError();},
			success: function(response){
				if(response.success){
					report.faultDistribute.point = point;
					report.faultDistribute.ajaxData = response.data;
					report.faultDistribute.drawColumn();
					report.faultDistribute.drawDonut();
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
	window.report.qualification = {
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
		                	if(point.series.name === "合格率"){
		                		sRate += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ Math.round(this.y * 100) +'%</b></td></tr>';
		                	} else {
		                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ point.y +'</b></td></tr>';
	            				total += this.y;
		                	}
		                });
		                s += sCar;
		                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>DPU:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ total.toFixed(2) +'</b></td></tr>';
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
							text: 'DPU',
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
							text: '合格率',
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
			                        report.qualification.toggleClickPointData("monthly", this.x, this.y, this.series.index);
			      					$(".qualificationChart[timespan=monthly][point="+ report.qualification.point +"]").highcharts(report.qualification.chartData['monthly']);
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
		                	if(point.series.name === "合格率"){
		                		sRate += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ Math.round(this.y * 100) +'%</b></td></tr>';
		                	} else {
		                		sCar += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
	            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ point.y +'</b></td></tr>';
	            				total += this.y;
		                	}
		                });
		                s += sCar;
		                s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>DPU:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ total.toFixed(2) +'</b></td></tr>';
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
							text: 'DPU',
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
							text: '合格率',
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
			                    	report.qualification.toggleClickPointData("yearly", this.x, this.y, this.series.index);
			      					$(".qualificationChart[timespan=yearly][point="+ report.qualification.point +"]").highcharts(report.qualification.chartData['yearly']);
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
				yAxis: 1,
				showInLegend: true,
				name: '合格率',
				data: report.qualification.prepare(this.ajaxData.series.line),
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

			$(".tabQualification[point="+ this.point +"] .divLoading[timespan="+ timespan +"]").hide();
			$(".qualificationChart[point="+ this.point +"][timespan="+ timespan +"]").show().highcharts(this.chartData[timespan]);
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

	window.report.faultDistribute = {
		point: "",
		ajaxData: {},
		columnData: {
			chart: {
                type: 'column',
                spacingLeft: 70,
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
                    text: '故障数',
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
                	s = '<table>';
                	$.each(this.points, function(i, point) {
	                	if(point.y >0){
	                		s += "<tr><td style='color:{series.color};padding:0'></td>"+ point.series.name + "-" + point.key +": " + point.y +"</tr>";
	                	}
                	});
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
                    center: ['50%', '50%']
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
                name: '责任部门',
                data: [],
                size: '60%',
                dataLabels: {
                    formatter: function() {
                        return this.y > 0.1 ? '<b>'+ this.point.name +'</b> ' + "[" + (this.y*100).toFixed(1) +"%]": null;
                    },
                    color: 'white',
                    distance: -30
                }
            }, {
                name: '部门细分',
                data: [],
                size: '80%',
                innerSize: '60%',
                dataLabels: {
                	distance: 5,
                    formatter: function() {
                        // display only if larger than 0
                        return (this.y > 0.01 && this.key != "-") ? '<b>'+ this.point.name +'</b> '+  "[" + (this.y*100).toFixed(1) + "%]" : null;
                    }
                }
            }]
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
        	$(".tabQualification[point="+ this.point +"] .divLoading[chart=column]").hide();
        	$(".faultsChart[point=" + this.point + "]").show().highcharts(this.columnData);
        },

        drawDonut: function() {
        	data = this.ajaxData.donutData;
        	colors = Highcharts.getOptions().colors;
        	departmentData = [];
        	subDepartmentData = [];
        	$.each(data, function (key, value) {
        		departmentData.push({
        			name: key,
        			y: value.y,
        			color: colors[value.colorIndex],
        		});

        		for(var j=0; j<value.drilldown.data.length; j++){
        			var brightness = 0.2 - (j / value.drilldown.data.length) / 5 ;
	                subDepartmentData.push({
	                    name: value.drilldown.categories[j],
	                    y: value.drilldown.data[j],
	                    color: Highcharts.Color(colors[value.colorIndex]).brighten(brightness).get()
	                });
        		}
        	})

        	this.donutData.series[0].data = departmentData;
	        this.donutData.series[1].data = subDepartmentData;
        	$(".tabQualification[point="+ this.point +"] .divLoading[chart=donut]").hide();
	        $(".dutyChart[point=" + this.point + "]").show().highcharts(this.donutData);
        }
	}
})