/* ===================================================
 * faultQuery.js 
 * Author: ccx
 * Update: 2012-10-31
 * ========================================================== */
!$(function () {
	window.byd = window.byd || {};

	window.byd.Validator = {
		validateComponentAndFaultMode : function () {
			/*if (byd.StringUtil.isBlank($('#componentText').val()) && 
				byd.StringUtil.isBlank($('#faultModeText').val())) {
				return false;
			}*/
			return true;
		}
	};
	window.byd.getFormSeries = function () {
		var series = '';
		var f0Checked = $('#checkboxF0').attr('checked') === 'checked';
		var m6Checked = $('#checkboxM6').attr('checked') === 'checked';
		if((f0Checked + m6Checked)%2 === 0)
			series += $('#checkboxF0').val() + ',' + $('#checkboxM6').val();
		else if(f0Checked)
			series += $('#checkboxF0').val();
		else
			series += $('#checkboxM6').val();
		return series;
	}

	window.byd.ajaxSender = {

		ajaxQuery : function (targetPage) {
			var ajaxData = { 'vin': $('#vinText').val(), 
			    		'node': $('#selectNode').val(),
						'component': $('#componentText').val(),
						'mode': $('#faultModeText').val(),
						'series': byd.getFormSeries(),
						'stime': $('#startTime').val(),
						'etime': $('#endTime').val(),
						'perPage': 20,
						'curPage': targetPage || 1};
			
			$.ajax({
				type: 'get',//使用get方法访问后台
	    	    dataType: 'json',//返回json格式的数据
			    url: FAULT_QUERY,//ref:  /bms/js/service.js
			    data: ajaxData,
			    success: byd.handler.querySuccess,
			    error:function(){alertError();}
			});
		},

		ajaxFautlDistribute : function (targetPage) {
			var ajaxData = { 'vin': $('#vinText').val(), 
			    		'node': $('#selectNode').val(),
						'component': $('#componentText').val(),
						'mode': $('#faultModeText').val(),
						'series': byd.getFormSeries(),
						'stime': $('#startTime').val(),
						'etime': $('#endTime').val()};
			$.ajax({
				type: 'get',//使用get方法访问后台
			    dataType: 'json',//返回json格式的数据
			    url: FAULT_QUERY_DISTRIBUTE,//ref:  /bms/js/service.js
			    data: ajaxData,
			    success: byd.handler.distributeSuccess,
			    error:function(){alertError();}
			});
		}, 

		ajaxDpu : function (targetPage) {
			var ajaxData = { 'vin': $('#vinText').val(), 
			    		'node': $('#selectNode').val(),
						'component': $('#componentText').val(),
						'mode': $('#faultModeText').val(),
						'series': byd.getFormSeries(),
						'stime': $('#startTime').val(),
						'etime': $('#endTime').val()};
			$.ajax({
				type: 'get',//使用get方法访问后台
	    	    dataType: 'json',//返回json格式的数据
			    url: FAULT_QUERY_DPU,//ref:  /bms/js/service.js
			    data: ajaxData,
			    success: byd.handler.dpuSuccess,
			    error:function(){alertError();}
			});
		},

		ajaxExport : function () {
			window.open(FAULT_EXPORT + '?vin=' + $('#vinText').val() + 
				'&node=' + $('#selectNode').val() + 
				'&component=' + $('#componentText').val() +
				'&mode=' + $('#faultModeText').val() +
				'&series=' + ($('#checkboxF0').val() + ',' + $('#checkboxM6').val()) +
				'&stime=' + $('#startTime').val() +
				'&etime=' + $('#endTime').val()
			);
		}

	}
	window.byd.handler = {

		querySuccess : function (response) {
			if (response.success) {
				$('#resultTable tbody').text('');
	    		$.each(response.data.data,function (index,value) {
	    			//make sure table is clear
	    			var tr = $('<tr />');
	    			$('<td />').html( value.series ).appendTo(tr);
	    			$('<td />').html( value.vin ).appendTo(tr);
	    			$('<td />').html( value.component_name ).appendTo(tr);
	    			$('<td />').html( value.fault_mode ).appendTo(tr);
	    			$('<td />').html( value.fault_status ).appendTo(tr);
	    			$('<td />').html( value.node_name ).appendTo(tr);
	    			$('<td />').html( value.create_time ).appendTo(tr);
	    			$('<td />').html( value.user_name ).appendTo(tr);
	    			$('<td />').html( value.modify_time) .appendTo(tr);
	    			$('#resultTable tbody').append(tr);
					$('#resultTable').show();	//add by wujun
	    		});
	    		//deal with pager
	    		$('.pager').show();
	    		if(response.data.pager.curPage == 1)
	    			$('.prePage').hide();
	    		else
	    			$('.prePage').show();
	    		if(response.data.pager.curPage * 20 >= response.data.pager.total )
	    			$('.nextPage').hide();
	    		else
	    			$('.nextPage').show();
	    		$('.curPage').attr('page', response.data.pager.curPage);
	    		$('.curPage').html('第' + response.data.pager.curPage + '页');
	    	} else {
	    		alert(response.message);
	    	}
		},

		distributeSuccess : function (response) {
	    	if (response.success) {
	    		$('#pieContainer').text('');
	    		byd.pie.pieAjaxData = response.data;
	    		byd.pie.drawPie('component_chart_data');
	    		byd.pie.updatePieTable('component_chart_data');
	    	} else {
	    		alert(response.message);
	    	}
	    },

	    dpuSuccess : function (response) {
	    	if (response.success) {
	    		byd.dpu.dpuAjaxData = response.data;
	    		byd.dpu.drawDpuChart();
	    		byd.dpu.updateDpuTable();
	    	} else {
	    		alert(response.message);
	    	}
	    }

	};

	window.byd.pie = {

		pieAjaxData : {},

		pieData : {
            chart: {
                renderTo: 'pieContainer',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: ''
            },
            credits: {
                href: 'http://www.bydauto.com.cn',
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
                name: '故障分布',
                data: []
            }]
        },

        drawPie : function (type) {
        	this.pieData.series[0].data = this.pieAjaxData[type].series;
        	var chart;
        	// console.log(this.pieData);
			chart = new Highcharts.Chart(this.pieData);
        },

		updatePieTable : function  (type) {
			$('#tableFaultDistribute thead').html('<tr />');
			$('#tableFaultDistribute tbody').html('<tr /><tr />');
			var thTr = $('#tableFaultDistribute tr:eq(0)');
			var dataTr = $('#tableFaultDistribute tr:eq(1)');
			var percentageTr = $('#tableFaultDistribute tr:eq(2)');
			if(type === 'component_chart_data')
				$('<td />').html('零部件').appendTo(thTr);
			else if(type === 'fault_mode_chart_data')
				$('<td />').html('故障模式').appendTo(thTr);
			else if(type === 'series_chart_data')
				$('<td />').html('车系').appendTo(thTr);
			else if(type === 'node_chart_data')
				$('<td />').html('节点').appendTo(thTr);
			$('<td />').html('数量').appendTo(dataTr);
			$('<td />').html('百分比').appendTo(percentageTr);
			$.each(this.pieAjaxData[type].detail, function (index,value) {
				$('<td />').html(value.name).appendTo(thTr);
				$('<td />').html(value.count).appendTo(dataTr);
				$('<td />').html(value.percentage).appendTo(percentageTr);
			});
		}
	};

	window.byd.dpu = {

		dpuAjaxData : {},

		dpuChartData : {
            chart: {
                renderTo: 'lineContainer',
                type: 'line',
                //marginRight: 130,
                marginBottom: 60
            },
            title: {
                text: '',
                x: -20 //center
            },
            credits: {
                href: 'http://www.bydauto.com.cn',
                text: ''
            },
            // subtitle: {
            //     text: 'Source: WorldClimate.com',
            //     x: -20
            // },
            xAxis: {
                categories: {},
                labels: {
                    rotation: -45,
                    align: 'right'
                }
            },
            yAxis: {
                title: {
                    text: 'DPU'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                min: 0
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y;
                }
            },
            legend: {
                layout: 'vertical',
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
                            return this.y;
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
		                        byd.dpu.toggleClickPointData(this.x, this.y);
		                        var chart;
								chart = new Highcharts.Chart(byd.dpu.dpuChartData);
		                    }
		                }
		            }
            	}
            },
            series: []
        },

        drawDpuChart : function () {
        	var lineSeries = [];
			carSeries = this.dpuAjaxData.carSeries;
			lineData = this.dpuAjaxData.series;
			$.each(carSeries, function (index,series) {
				lineSeries[index] = {name : series + '.DPU', data: byd.dpu.prepare(lineData.y[series])};
			});

			this.dpuChartData.series = lineSeries;
			this.dpuChartData.xAxis.categories = lineData.x;
			var chart;
			chart = new Highcharts.Chart(this.dpuChartData);
        },

		updateDpuTable : function () {
			var carSeries = this.dpuAjaxData.carSeries;
			var detail = this.dpuAjaxData.detail;
			//clean before
			$('#tableDpu thead').html('<tr />');
			$('#tableDpu tbody').html('<tr />');
			$.each(carSeries, function (index,value) {
				$('<tr /><tr /><tr />').appendTo($('#tableDpu tbody'));
			});
			//get tr
			var timeTr = $('#tableDpu tr:eq(0)');
			//first column descriptions
			$('<td />').html('日期').appendTo(timeTr);
			$.each(carSeries, function (index,value) {
				$('<td />').html(value + '.DPU').appendTo($('#tableDpu tr:eq('+(index*3+1)+')'));
				$('<td />').html(value + '.故障数').appendTo($('#tableDpu tr:eq('+(index*3+2)+')'));
				$('<td />').html(value + '.车辆数').appendTo($('#tableDpu tr:eq('+(index*3+3)+')'));

			});

			$.each(detail, function (index,value) {
				$('<td />').html(value.time).appendTo(timeTr);
				$.each(carSeries, function (index,series) {
					$('<td />').html(value[series].dpu).appendTo($('#tableDpu tr:eq('+(index*3+1)+')'));
					$('<td />').html(value[series].faults).appendTo($('#tableDpu tr:eq('+(index*3+2)+')'));
					$('<td />').html(value[series].cars).appendTo($('#tableDpu tr:eq('+(index*3+3)+')'));
				});
			});
		},

		toggleClickPointData : function (x, y) {
			// console.log(this.dpuChartData);
			$(this.dpuChartData.series[0].data).each(function (index, value) {
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
     


	}
});

$(document).ready(function () {
	/*
 * ----------------------------------------------------------------
 * Event bindings
 * ----------------------------------------------------------------
 */	
 	function toQuery () {
 		if (!byd.Validator.validateComponentAndFaultMode()) {
			alert(BYD.FAULT_QUERY_ALERT_VALIDATE);
			return false;
		}
		
		//if validate passed
		var index = $('#tabs li').index($('#tabs .active'));
		if(index === 1) {
			byd.ajaxSender.ajaxFautlDistribute();
		}
		else if(index === 2) {
			byd.ajaxSender.ajaxDpu();
		}
		else if (index === 0) {
			byd.ajaxSender.ajaxQuery();
		}
		return false;//prevent default
 	}
 	//query click event
	$('#btnQuery').live('click', toQuery);

	//监听tab切换事件
	$('#tabs li').live('click', toQuery);

	// $('#componentText').bind('keydown', enterHandler);
	// $('#faultModeText').bind('keydown', enterHandler);
	// $('#startTime').bind('keydown', enterHandler);
	// $('#endTime').bind('keydown', enterHandler);

	$(window).bind('keydown', enterHandler);
	function enterHandler (event) {
		if (event.keyCode == "13"){
		    toQuery();
		    return false;
		}
	}

	$('#btnExport').live("click", function () {
		byd.ajaxSender.ajaxExport();
		return false;
	});

	//pager
	$('.prePage').live("click", function () {
		byd.ajaxSender.ajaxQuery(parseInt($('.curPage').attr('page')) - 1);
		return false;
	});

	$('.nextPage').live("click", function () {
		byd.ajaxSender.ajaxQuery(parseInt($('.curPage').attr('page')) + 1);
		return false;
	});

	//pie
	$('#divRadio :radio').change(function () {
		$('#pieContainer').text('');
		var type = $(this).val();
		byd.pie.drawPie(type);
		byd.pie.updatePieTable(type);
	});
//END------------------- event bindings -----------------------

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
		$('#headAssemblyLi').addClass('active');
		$('#leftFaultQueryLi').addClass('active');
		resetAll();
		$('#endTime').val(byd.DateUtil.currentTime());
		$('#startTime').val(byd.DateUtil.firstDayOfTheMonth());
	}
	
	function resetAll (argument) {
		$('.pager').hide();
		$('#resultTable').hide();	//add by wujun
	}


	

	

	var tempLineData ;
	

	/*
	  When ajaxDpu success,update the DPU line table.
	  @param data -> response.data.series
	*/
	

	/*
		drawPie with type,using highcharts
		@param type -> dimention type
			value -> component_chart_data,fault_mode_chart_data,series_chart_data,node_chart_data
	*/

	
	/*
		draw DPU line
	*/
	// function drawLineChart (data) {
		
	// }

     
	
//-------------------END ajax query -----------------------

});
