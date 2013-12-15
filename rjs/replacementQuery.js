require.config({
	"paths" : {
		// "jquery": "lib/jquery-2.0.3.min",
		"jquery": "lib/jquery-1.8.0.min",
		"bootstrap": "lib/bootstrap.min",
		"jsrender": "lib/jsrender.min",
		"jquery-ui": "lib/jquery-ui-1.10.3.custom.min",
		"jquery-ui-timepicker-addon": "lib/jquery-ui-timepicker-addon",
		"highcharts": "lib/highcharts"
	},
	"shim": {
		"bootstrap": ["jquery"],
		"jsrender": ["jquery"],
		"jquery-ui": ["jquery"],
		"jquery-ui-timepicker-addon": ["jquery-ui"],
		"highcharts": ["jquery"]
	}
});

require(["head","service","common","dateTimeUtil","highcharts","jquery","bootstrap","jsrender","jquery-ui","jquery-ui-timepicker-addon"], function (head, service, common, dateTimeUtil, highcharts, $) {
	initPage();

	$('#startTime, #endTime').datetimepicker({
		timeFormat: "HH:mm",
		changeMonth: true,
	    changeYear: true,
	    showOtherMonths: true,
	    selectOtherMonths: true,
	    duration: "fast",
	    buttonImageOnly: true
	});

	$("#tabs li").click(function () {
		var index = $("#tabs li").index(this);
		if(index<3) {
			$(".pagination").hide();
		}

		switch (index) {
			case 0 :
				ajaxQueryReplacementDetail(1);
				break;
			case 1 :
				ajaxCostTrend();
				break;
			case 2 :
				ajaxCostDuty();
				break;
			default: 
				break;
		}
	});

	$("#exportDetail").click(function () {
		ajaxExportReplacementDetail();
	});


	function initPage () {
		$("#headCostLi").addClass("active");
		$("#startTime").val(dateTimeUtil.getTime("firstDayOfTheMonth"));
		$("#endTime").val(dateTimeUtil.getTime("currentTime"));

		options = common.getDutyOptions("SparesStore", true);
		$("#dutyDepartment").append(options);


		common.fillSeriesCheckbox();
		common.fillLineSelect();
	}

	function ajaxQueryReplacementDetail (targetPage) {
		$("#tableDetail>tbody").html("");
		$.ajax({
			url: service.QUERY_REPLACEMENT_DETAIL,
			dataType: "json",
			data: {
				"stime": $("#startTime").val(),
				"etime": $("#endTime").val(),
				"line": $("#line").val(),
				"series": common.getSeriesChecked(),
				"dutyId": $("#dutyDepartment").val(),
				"perPage": 20,
				"curPage": targetPage || 1
			},
			error: function () {common.alertError();},
			success: function (response) {
				if(response.success) {
					trs = $.templates("#tmplReplacementDetail").render(response.data.data);
					$("#tableDetail>tbody").append(trs);

					//deal with pager	
		    		if(response.data.pager.curPage == 1) {
						$("#preDetail, #firstDetail").addClass("disabled");
						$("#preDetail a, #firstDetail a").removeAttr("href");
					} else {
						$("#preDetail, #firstDetail").removeClass("disabled");
						$("#preDetail a, #firstDetail a").attr("href","#");
					}
	    			if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
						$("#nextDetail, #lastDetail").addClass("disabled");
						$("#nextDetail a, #lastDetail a").removeAttr("href");
					} else {
						$("#nextDetail, #lastDetail").removeClass("disabled");
						$("#nextDetail a, #lastDetail a").attr("href","#");
					}
					$("#curDetail").attr("page", response.data.pager.curPage);
					$("#curDetail a").html(response.data.pager.curPage);
					$("#totalDetail").attr("total", response.data.pager.total);
					$("#totalDetail").html("导出全部" + response.data.pager.total + "条记录");
				
					$("#paginationDetail").show();
					$("#tableDetail").show();
				} else {
					alert(response.message);
				}
			}
		})
	}

	function ajaxExportReplacementDetail () {
		window.open(service.EXPORT_REPLACEMENT_DETAIL
			+ "?&stime=" + $("#startTime").val()
			+ "&etime=" + $("#endTime").val()
			+ "&line=" + $("#line").val()
			+ "&dutyId=" + $("#dutyDepartment").val()
			+ "&series=" + common.getSeriesChecked()
		);
	}

	function ajaxCostTrend () {
		seriesText = common.getSeriesChecked();
		$.ajax({
			url: service.QUERY_REPLACEMENT_COST_TREND,
			dataType: "json",
			data:{
				"stime": $("#startTime").val(),
				"etime": $("#endTime").val(),
				"line": $("#line").val(),
				"series": seriesText,
				"dutyId": $("#dutyDepartment").val()
			},
			error: function () {common.alertError();},
			success: function (response) {
				if(response.success) {
					if(seriesText === "") {
						costTrendAll.costTrendAllAjaxData = response.data;
				    	costTrendAll.drawArea();
				    	costTrendAll.updateCostTrendTable();
					} else {
						costTrend.costTrendAjaxData = response.data;
				    	costTrend.drawLine();
				    	costTrend.updateCostTrendTable();
					}
				} else {
					alert(response.message);
				}
			}
		})
	}

	function ajaxCostDuty () {
		seriesText = common.getSeriesChecked();
		$.ajax({
			url: service.QUERY_REPLACEMENT_COST_DUTY,
			dataType: "json",
			data:{
				"stime": $("#startTime").val(),
				"etime": $("#endTime").val(),
				"line": $("#line").val(),
				"series": seriesText,
				"dutyId": $("#dutyDepartment").val()
			},
			error: function () {common.alertError();},
			success: function (response) {
				if(response.success) {
					costDuty.ajaxData = response.data;
					costDuty.drawCharts();
					if(response.data.detail.dutyDepartment){
						costDuty.updateCostDutyTable();
					}
				} else {
					alert(response.message);
				}
			}
		})
	}

	costTrendAll = {
		costTrendAllAjaxData: {},
		costTrendAllChartData: {
			chart: {
	                type: 'area',
	                renderTo: 'costTrendContainer'
            },
            title: {
                text: ''
            },
            credits: {
				href: '',
				text: ''
			},
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [],
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
            yAxis: {
                title: {
                    text: '单车成本'
                },
                labels: {
                    formatter: function() {
                        return this.value;
                    }
                }
            },
            tooltip: {
                shared: true,
                // valueSuffix: ' min'
                useHTML: true,
                formatter: function() {
                	var s = this.points[0].key +'<table>';
                	var ss = '';
                	total = 0;
                	$.each(this.points, function(i, point) {
                		value = point.y === null ? 0:point.y;
                    	ss += '<tr><td style="text-align: right; color: '+ point.series.color +'">'+ point.series.name +': </td>' +
            					'<td style="text-align: right;color: '+ point.series.color +'">￥<b>'+ value +'</b></td></tr>';
            			total += value;
                	});
                	s += '<tr><td style="text-align: right;border-bottom-style:solid;border-bottom-width: 1px;"><b>总计:</b></td><td style="text-align: right;border-bottom-style:solid;border-bottom-width: 1px;">￥<b>'+ total.toFixed(2) +'</b></td></tr>';
                	s += ss;
                	s += '</table>';
                	return s;
                        
                }
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666'
                    }
                }
            },
            series: []
	    },

	    drawArea: function() {
	    	var areaSeries = [];
	    	var carSeries = this.costTrendAllAjaxData.carSeries;
	    	var areaData = this.costTrendAllAjaxData.series;
	    	$.each(carSeries, function (index, series) {
	    		areaSeries[index] = {name: series, data:costTrendAll.prepare(areaData.y[series])};
	    	})
	    	this.costTrendAllChartData.series = areaSeries;
	    	this.costTrendAllChartData.xAxis.categories = areaData.x;
	    	var chart;
			chart = new Highcharts.Chart(this.costTrendAllChartData);
	    },

	    updateCostTrendTable: function() {
	    	var carSeries = this.costTrendAllAjaxData.carSeries;
	    	var detail = this.costTrendAllAjaxData.detail;
	    	var total = this.costTrendAllAjaxData.total;

			$("#tableCostTrend thead").html("<tr />");
			$("#tableCostTrend tbody").html("");		
	        $.each(carSeries, function (index,value) {
	            $("<tr />").appendTo($("#tableCostTrend tbody"));
	        });
	        
			var thTr = $("#tableCostTrend tr:eq(0)");
	        $("<th />").html("车系").appendTo(thTr);    
	        $("<th />").html("合计").appendTo(thTr);

			$.each(carSeries, function (index, series) {
	            $("<td />").html(series).appendTo($("#tableCostTrend tr:eq("+(index*1+1)+")"));
	            $("<td />").addClass("alignRight").html(parseFloat(total[series]).toFixed(2)).appendTo($("#tableCostTrend tr:eq(" + (index*1+1) + ")"));
	        });

	        var totalTr =  $("<tr />").appendTo($("#tableCostTrend tbody"));
	        $("<td />").html('综合').appendTo(totalTr);
	        $("<td />").addClass("alignRight").html(total['total']).appendTo(totalTr);

			$.each(detail, function (index,value) {
				$("<td />").html(value.time).appendTo(thTr);
				$.each(carSeries, function (index,series) {
					$("<td />").addClass("alignRight").html(value[series]).appendTo($("#tableCostTrend tr:eq("+(index*1+1)+")"));
				});
				$("<td />").addClass("alignRight").html(value['total']).appendTo(totalTr);
			});

	    },

	    prepare: function (dataArray) {
	    	return $(dataArray).map(function (index, item) {
	    		return {x: index, y: item, show: false};
	    	})
	    }
	}

	costTrend = {
		costTrendAjaxData: {},
		costTrendChartData: {
			chart: {
                renderTo: 'costTrendContainer',
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
                categories: [],
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
                    text: '单车成本'
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
                        this.x +': ￥'+ this.y + "";
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'top',
                borderWidth: 0
            },
            series: []
		},

		drawLine: function () {
			var areaSeries = [];
	    	var carSeries = this.costTrendAjaxData.carSeries;
	    	var areaData = this.costTrendAjaxData.series;
	    	$.each(carSeries, function (index, series) {
	    		areaSeries[index] = {name: series, data:costTrend.prepare(areaData.y[series])};
	    	})
	    	this.costTrendChartData.series = areaSeries;
	    	this.costTrendChartData.xAxis.categories = areaData.x;
	    	var chart;
			chart = new Highcharts.Chart(this.costTrendChartData);
		},

		updateCostTrendTable: function() {
			var carSeries = this.costTrendAjaxData.carSeries;
	    	var detail = this.costTrendAjaxData.detail;
	    	var total = this.costTrendAjaxData.total;
			$("#tableCostTrend thead").html("<tr />");
			$("#tableCostTrend tbody").html("<tr />");		
	        $.each(carSeries, function (index,value) {
	            $("<tr />").appendTo($("#tableCostTrend tbody"));
	        });
			
			var thTr = $("#tableCostTrend tr:eq(0)");
	        $("<th />").html("车系").appendTo(thTr);    
	        $("<th />").html("合计").appendTo(thTr);
			$.each(carSeries, function (index, series) {
	            $("<td />").html(series).appendTo($("#tableCostTrend tr:eq("+(index*1+1)+")"));
	            $("<td />").addClass("alignRight").html(parseFloat(total[series]).toFixed(2)).appendTo($("#tableCostTrend tr:eq(" + (index*1+1) + ")"));
	        });

			$.each(detail, function (index,value) {
				$("<td />").html(value.time).appendTo(thTr);

				$.each(carSeries, function (index,series) {
					$("<td />").addClass("alignRight").html(parseFloat(value[series]).toFixed(2)).appendTo($("#tableCostTrend tr:eq("+(index*1+1)+")"));
				});
			});
		},

		prepare: function (dataArray) {
	    	return $(dataArray).map(function (index, item) {
	    		return {x: index, y: item, show: false};
	    	})
	    }
	}

	costDuty = {
		ajaxData: {},

		chartData: {
			chart: {
				renderTo: 'costDutyPlatoContainer',
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
                    } else if(this.series.name == "百分率" || this.series.name == "累计百分率"){	//percentage
                    	s =	''+
                    		this.x  +': '+ (this.y * 100).toFixed(0) + '%';
                    }else{		//column
                        s = ''+
                            this.x  +': ￥'+ this.y;
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
						// text: '停线时长(分钟)',
						text: null,
						style: {
							color: Highcharts.getOptions().colors[4],
							fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
						}
					},
					labels: {
						enabled: false,
						formatter: function() {
							return parseInt(this.value/60)
						}
					},
					min: 0,
					endOnTick: false,
					gridLineWidth: 0
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
					//opposite: true
				},

			],

			series: [
				{
					type: 'column',
					color: Highcharts.getOptions().colors[4],
					name: '金额',
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
            				return "￥" +this.y;
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
					name: '换件区域',
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

		drawCharts: function(){
			this.chartData.series[0].data = this.ajaxData.series.column;
			this.chartData.series[1].data = this.ajaxData.series.p;
			this.chartData.series[2].data = this.ajaxData.series.y;
			this.chartData.series[3].data = this.ajaxData.series.cSeries;
			this.chartData.xAxis.categories = this.ajaxData.series.x;
			var chart;
			chart = new Highcharts.Chart(this.chartData);
		},

		updateCostDutyTable: function() {
			$("#tableCostDutyPlato>tbody").html("");
			data = this.ajaxData.detail.dutyDepartment;
			trName = $("<tr />").append("<td>责任</td>").append($.templates("#tmplCostDutyPlatoName").render(data));
			trCost = $("<tr />").append("<td>金额</td>").append($.templates("#tmplCostDutyPlatoCost").render(data));
			trUnitCost = $("<tr />").append("<td>单车成本</td>").append($.templates("#tmplCostDutyPlatoUnitCost").render(data));
			trPercentate = $("<tr />").append("<td>百分率</td>").append($.templates("#tmplCostDutyPlatoPercentage").render(data));

			$("#tableCostDutyPlato>tbody").append(trName).append(trCost).append(trUnitCost).append(trPercentate);
		}
	}
})