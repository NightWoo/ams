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
//END event bindings -------------------------


//common function -------------------------

	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftManufactureReportLi").addClass("active");

		$("#startTime").val(window.byd.DateUtil.currentDate());
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
					report.manufactureDaily.drawColumn();
					report.manufactureDaily.drawDonut();
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
			$("<td />").html("车系").appendTo(pointTr);
			$.each(countSeries, function (series, seriesName) {
				$("<td />").html(seriesName).addClass("alignCenter").appendTo($("#manufactureDailyTable tr[series=" + series + "]"));
			})

			//detail data
			$.each(countPoint, function (key, name) {
				$("<td />").html(name).appendTo(pointTr);
				$.each(count[key], function (series, value){
					$("<td />").html(value).appendTo($("#manufactureDailyTable tr[series=" + series + "]"));
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
})