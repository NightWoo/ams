$(document).ready(function () {
	initPage();

	areaState = {
		"assembly": [
			{
				"value": "assembly",
				"text": "全部"
			}
		],
		"pbs": [
			{
				"value": "PBS",
				"text": "全部"
			},{
				"value": "PBS-inventory",
				"text": "库存中"
			},{
				"value": "PBS-inQueue",
				"text": "预上线"
			}
		],
		"online": [
			{
				"value": "onLine-all",
				"text": "全部"
			},{
				"value": "onLine",
				"text": "I线"
			},{
				"value": "onLine-2",
				"text": "II线"
			}
		],
		"VQ": [
			{
				"value": "recycle",
				"text": "全部"
			},{
				"value": "VQ1",
				"text": "VQ1"
			},{
				"value": "VQ2",
				"text": "VQ2"
			},{
				"value": "VQ3",
				"text": "VQ3",
			}
		],
		"warehouse": [
			{
				"value": "WH",
				"text": "全部"
			},{
				"value": "WHin",
				"text": "可备"
			},{
				"value": "WH-WDI",
				"text": "WDI"
			}
		]

	}
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
		$("#leftBalanceQueryLi").addClass("active");

		// $("#startTime").val(currentDate8());
		// $("#endTime").val(currentDate16());

		getSeries();
		
		resetAll();
	}
	

	function resetAll (argument) {
		$(".pager").hide();
		$("#resultTable").hide();
		// $("#tableCarsDistribute").hide();
		$("#divCheckbox").hide();
		// $("#recyclePeriodLi").hide();
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

	$("#selectArea").change(function () {
		$("#selectState").html("");
		optionData = areaState[$(this).val()];
		options = $.templates("#tmplSelectStateOption").render(optionData);
		$("#selectState").append(options);
		if($(this).val() == "pbs") {
			$("#recyclePeriodLi").hide();
		} else {
			$("#recyclePeriodLi").show();
		}
	})
	
	$("#btnQuery").bind("click",toQuery);
	function toQuery() {
		//clear last
		$("#resultTable tbody").text("");

		//if validate passed
		var index = $("#tabs li").index($('#tabs .active'));
		$("#paginationCars").hide();
		if (index == 0)
			ajaxDetailQuery();
		return false;
	}

	$("#exportCars").click(
		function () {
			ajaxExportBalanceDetail();
			return false;
		}
	);

	//car pagination
	$("#preCars").click(
		function (){
			if(parseInt($("#curCars").attr("page")) > 1){
				$("#tableCars tbody").html("");
				ajaxDetailQuery(parseInt($("#curCars").attr("page")) - 1);
			}
		}
	);

	$("#nextCars").click(
		function (){
			if(parseInt($("#curCars").attr("page")) * 20 < parseInt($("#totalCars").attr("total")) ){
			$("#tableCars tbody").html("");
			ajaxDetailQuery(parseInt($("#curCars").attr("page")) + 1);
		}
		}
	);

	$("#firstCars").click(
		function () {
			if(parseInt($("#curCars").attr("page")) > 1){
				$("#tableCars tbody").html("");
				ajaxDetailQuery(parseInt(1));
			}
		}
	);

	$("#lastCars").click(
		function () {
			if(parseInt($("#curCars").attr("page")) * 20 < parseInt($("#totalCars").attr("total")) ){
				$("#tableCars tbody").html("");
				totalPage = parseInt($("#totalCars").attr("total"))%20 === 0 ? parseInt($("#totalCars").attr("total"))/20 : parseInt($("#totalCars").attr("total"))/20 + 1;
				ajaxDetailQuery(parseInt(totalPage));
			}
		}
	)

	$("#tabs li").click(function () {
		var index = $("#tabs li").index(this);
		if(index<3){
			$("#paginationCars").hide();
		}
		if (index == 0){
			$("#area").val("");
			ajaxDetailQuery();
		} else if (index === 1){
			carsDistribute();
		} else if (index === 2){
			queryRecyclePeriod();
		}
	});

	$("#checkboxMerge").change(function () {
		if($(this).attr("checked") == "checked"){
			ajaxQueryBalanceAssembly('mergeRecyle');
		} else {
			ajaxQueryBalanceAssembly();
		}
	})

	$(".queryCars").live("click", function(e){
		orderConfigId = $(this).attr("orderConfigId");
		coldResistant = $(this).attr("coldResistant");
		color = $(this).attr("color");
		headInfo =$("#selectState option:selected").html() + "-" + $("#selectSeries").val() + "-" + $(this).attr("configName");
		if(color !=""){
			headInfo = headInfo + "-" + color
		}
		$("#carsModal .modal-header h4").html(headInfo);
		ajaxQueryCars(orderConfigId,coldResistant,color);
		$("#carsModal").modal("show");

	})

	$(".recycleCars").live("click", function(e){
		state = $(this).attr("state");
		recyclePeriod = $(this).attr("recyclePeriod");
		headInfo = "周转车";
		if(state != "recycle"){
			headInfo = headInfo + "-" + state;
		}
		if($("#selectSeries").val() != ""){
			headInfo = headInfo + "-" + $("#selectSeries").val();
		}
		if(recyclePeriod != ""){
			headInfo = headInfo + "-周期" + recyclePeriod;
		}
		$("#carsModal .modal-header h4").html(headInfo);
		ajaxQueryRecycleCars(state,recyclePeriod);
		$("#carsModal").modal("show");

	})

	$("#area").change(function(){
		ajaxDetailQuery();
	})

	// $("#selectState").change(function (){
	// 	if($(this).val() == 'recycle'){
	// 		$("#recyclePeriodLi").show();
	// 	} else {
	// 		$("#recyclePeriodLi").hide();
	// 	}
	// })
	$('body').tooltip(
        {
         selector: "select[rel=tooltip], a[rel=tooltip]"
    });

//-------------------END event bindings -----------------------



/*
 * ----------------------------------------------------------------
 * Ajax query
 * ----------------------------------------------------------------
 */
	function ajaxDetailQuery (targetPage) {
		areaVal = $("#area").val();
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: BALANCE_DETAIL_QUERY,//ref:  /bms/js/service.js
		    data: { 
		    	"state" : $("#selectState").val(),
		    	"series" : $("#selectSeries").val(),
		    	"area" : areaVal,
				"curPage":targetPage || 1,
		    	"perPage":20,
		    },
		    success:function (response) {
		    	if(response.success){
		    		$("#resultTable tbody").html("");
		    		$.each(response.data.data,function (index,value) {
		    			var serialTd = "<td>" + value.serial_number + "</td>";
		    			var seriesTd = "<td>" + value.series + "</td>";
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var colorTd = "<td>" + value.color + "</td>";
						var typeInfoTd = "<td>" + value.type_info + "</td>";
						var coldTd = "<td>" + value.cold + "</td>";
		    			var statusTd = "<td>" + value.status + "</td>";
		    			var rowTd = "<td>" + value.row + "</td>";
		    			var finishTimeTd = "<td>" + value.finish_time + "</td>";
		    			var warehouseTimeTd = "<td>" + value.warehouse_time + "</td>";
		    			var tr = "<tr>" + serialTd + vinTd + seriesTd + typeInfoTd + 
		    				coldTd + colorTd + statusTd + rowTd + finishTimeTd + warehouseTimeTd + "</tr>";
		    			$("#resultTable tbody").append(tr);
						$("#resultTable").show();
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
					
					//area condition
					if(areaVal == ""){
						$("#area").html("");
						$("<option />").val("").html("库区").appendTo($("#area"));
						$.each(response.data.areaArray, function (key, area){
							if(area !=''){
								$("<option />").val(area).html(area).appendTo($("#area"));
							}
						})
					}
					$("#area").val(areaVal);


					$("#paginationCars").show();

		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxExportBalanceDetail(){
		areaVal = $("#area").val();
		window.open(BALANCE_DETAIL_EXPORT 
			+ "?state=" + $("#selectState").val() 
			+ "&series="+ $("#selectSeries").val()
			+ "&area=" + areaVal
			);
	}

	function carsDistribute() {
		if($("#selectSeries").val() == ""){
			if($("#selectState").val() == "assembly"){
				if($("#checkboxMerge").attr("checked") == "checked"){
					ajaxQueryBalanceAssembly('mergeRecyle');
				} else {
					ajaxQueryBalanceAssembly('assembly');
				}
				$("#divCheckbox").show()
			} else {
				ajaxQueryBalanceAssembly($("#selectState").val());
				$("#checkboxMerge").removeAttr("checked");
				$("#divCheckbox").hide();
			}
			$("#carsDistribute .tableContainer").addClass("span10");
		} else {
			$("#divCheckbox").hide();
			$("#carsDistribute .tableContainer").removeClass("span10");
			ajaxQueryBalanceDistribute();
		}
	}

	function ajaxQueryBalanceAssembly(state) {
		$(".carsDistributeContainer").hide();
		$.ajax({
			url: QUERY_BALANCE_ASSEMBLY,
			type: "get",
			data: {
				"state" : state,
			},
			dataType: "json",
			success: function(response) {
				balanceQuery.AssemblyAll.ajaxData = response.data;
				balanceQuery.AssemblyAll.updateDistributeTable();
				balanceQuery.AssemblyAll.drawColumn();
				$("#columnContainer").show();
				$(".carsDistributeContainer").show();
			},
			error: function(){
				alertError();
			}
		})
	}

	function ajaxQueryBalanceDistribute() {
		$(".carsDistributeContainer").hide();
		$.ajax({
			url: QUERY_BALANCE_DISTRIBUTE,
			type: "get",
			data: {
				"state" : $("#selectState").val(),
				"series" : $("#selectSeries").val(), 
			},
			dataType: "json",
			success: function (response) {
				if(response.success){
					balanceQuery.distribute.ajaxData = response.data;
					balanceQuery.distribute.updateDistributeTable();
					$("#columnContainer").hide();
					$(".carsDistributeContainer").show();
				} else {
					alert(response.message);
				}
			},
			error: function(){
				alertError();
			}
		})
	}

	function ajaxQueryCars(orderConfigId,coldResistant,color) {
		$("#resultCars>tbody").html("");
		$.ajax({
			url: SHOW_BALANCE_CARS,
			type: "get",
			dataType: "json",
			data: {
				"state" : $("#selectState").val(),
				"orderConfigId" : orderConfigId,
				"coldResistant" : coldResistant,
				"color" : color || "",
			},
			success: function (response) {
				if(response.success){
					cars = response.data
					$.each(cars, function (index, car){
						tr = $("<tr />");
						$("<td />").html(car.serial_number).appendTo(tr);
						$("<td />").html(car.vin).appendTo(tr);
						$("<td />").html(car.series).appendTo(tr);
						$("<td />").html(car.type_info).appendTo(tr);
						$("<td />").html(car.cold).appendTo(tr);
						$("<td />").html(car.color).appendTo(tr);
						$("<td />").html(car.status).appendTo(tr);
						$("<td />").html(car.row).appendTo(tr);
						$("<td />").html(car.finish_time.substring(0,16)).appendTo(tr);
						if(car.warehouse_time == "0000-00-00 00:00:00"){
							lastTd = $("<td />").html("<i class='icon-time'></i>" + car.recycle_last + "H").appendTo(tr);
							if (car.recycle_last > 24) {
								lastTd.addClass("text-error");
							} else if(car.recycle_last > 8){
								lastTd.addClass("text-warning");
							}
						} else {
							$("<td />").html(car.warehouse_time.substring(0,16)).appendTo(tr);
						}

						$("#resultCars>tbody").append(tr);
					})
					$("#resultCars").show();
				} else {
					alert(response.message);
				}
			},
			error: function(){
				alertError();
			}
		})
	}

	function queryRecyclePeriod() {
		$.ajax({
			url: QUERY_BALANCE_PERIOD,
			type: "get",
			data: {
				"state" : $("#selectState").val(),
				"series" : $("#selectSeries").val(), 
			},
			dataType: "json",
			success: function (response) {
				if(response.success){
					intervalInfo = {
						'period_all': '整车周期 = 生产周期 + 成品库周期',
						'assembly_period': '装配周期 = 下线时间 - 上线时间',
						'vq1_period': 'VQ1周期 = VQ1完成时间 - 下线时间',
						'vq2_period': 'VQ2周期 = VQ2完成时间 - VQ1完成时间',
						'vq3_period': 'VQ3周期 = 入库时间 - VQ2完成时间',
						'recycle_period': 'VQ周期 = VQ1周期 + VQ2周期 + VQ3周期',
						'warehouse_period': '成品库周期 = 库存周期 + 备车周期',
						'standby_period': '备车周期 = 出库时间 - 备车时间',
						'inventory_period': '库存周期 = 备车时间 - 入库时间'
					};
					$("#intervalInfo").html(intervalInfo[response.data.periodInterval])
					balanceQuery.balancePeriod.ajaxData = response.data;
					balanceQuery.balancePeriod.drawColumn();
					balanceQuery.balancePeriod.updatePeriodTable();
				} else {
					alert(response.message);
				}
			},
			error: function(){
				alertError();
			}
		})
	}

	function ajaxQueryRecycleCars(state,recyclePeriod) {
		$("#resultCars>tbody").html("");
		$.ajax({
			url: SHOW_RECYCLE_CARS,
			type: "get",
			dataType: "json",
			data: {
				"state" : state || "",
				"series" : $("#selectSeries").val(),
				"recyclePeriod" : recyclePeriod || "",
			},
			success: function (response) {
				if(response.success){
					cars = response.data
					$.each(cars, function (index, car){
						tr = $("<tr />");
						$("<td />").html(car.serial_number).appendTo(tr);
						$("<td />").html(car.vin).appendTo(tr);
						$("<td />").html(car.series).appendTo(tr);
						$("<td />").html(car.type_info).appendTo(tr);
						$("<td />").html(car.cold).appendTo(tr);
						$("<td />").html(car.color).appendTo(tr);
						$("<td />").html(car.status).appendTo(tr);
						$("<td />").html(car.row).appendTo(tr);
						$("<td />").html(car.finish_time.substring(0,16)).appendTo(tr);
						if(car.warehouse_time == "0000-00-00 00:00:00"){
							lastTd = $("<td />").html("<i class='icon-time'></i>" + car.recycle_last + "H").appendTo(tr);
							if (car.recycle_last > 20) {
								lastTd.addClass("text-error");
							} else if(car.recycle_last > 8){
								lastTd.addClass("text-warning");
							}
						} else {
							$("<td />").html(car.warehouse_time.substring(0,16)).appendTo(tr);
						}

						$("#resultCars>tbody").append(tr);
					})
					$("#resultCars").show();
				} else {
					alert(response.message);
				}
			},
			error: function(){
				alertError();
			}
		})
	}

	function getSeries () {
		$.ajax({
			url: GET_SERIES_LIST,
			dataType: "json",
			data: {},
			async: false,
			error: function () {common.alertError();},
			success: function (response) {
				if(response.success){
					options = $.templates("#tmplSeriesSelect").render(response.data);
					$("#selectSeries").append(options);
				} else {
					alert(response.message);
				}
			}
		})
	}
	

//-------------------END ajax query -----------------------

});

!$(function (){
	window.balanceQuery = window.balanceQuery || {};
	window.balanceQuery.AssemblyAll = {
		ajaxData : {},

		columnData: {
			chart: {
                type: 'column',
                renderTo: 'columnContainer'
            },
            title: {
                text: ''
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
                    text: '结存数量（辆）'
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

		updateDistributeTable: function() {
			var series = this.ajaxData.carSeries;
			var detail = this.ajaxData.detail;
			var stateTotal = this.ajaxData.stateTotal;
			var seriesTotal = this.ajaxData.seriesTotal;

			//clear table and initialize it
			$("#tableCarsDistribute thead").html("<tr />");
			$("#tableCarsDistribute tbody").html("");
			$.each(series, function (index, series) {
				$("<tr />").appendTo($("#tableCarsDistribute tbody"));
			});
			stateTotalTr = $("<tr />").appendTo($("#tableCarsDistribute tbody"));

			//first column description
			var stateTr = $("#tableCarsDistribute tr:eq(0)");
			$("<td />").html('车系').addClass('alignCenter').appendTo(stateTr);
			$.each(series, function (index, series){
				$("<td />").html(series).addClass('alignCenter').appendTo($("#tableCarsDistribute tr:eq("+ (index+1) +")"));
			});
			$("<td />").html('合计').addClass('alignCenter').appendTo(stateTotalTr);

			//detail data
			$.each(detail, function (index ,value) {
				$("<td />").html(value.state).appendTo(stateTr);
				$.each(series, function (index, series){
					$("<td />").html(value[series]).appendTo($("#tableCarsDistribute tr:eq("+ (index+1) +")"));
				});
			})

			//series total
			$("<td />").html('总计').appendTo(stateTr);
			$.each(series, function (index, series) {
				$("<td />").html(seriesTotal[series]).appendTo($("#tableCarsDistribute tr:eq("+ (index+1) +")"));
			})

			//state total
			var totalTotal = 0;
			$.each(stateTotal, function (index, value) {
				$("<td />").html(value).appendTo(stateTotalTr);
				totalTotal += value;
			})
			$("<td />").html(totalTotal).appendTo(stateTotalTr);

		},


		drawColumn: function() {
			columnSeries = [];
			carSeries = this.ajaxData.carSeries;
			columnSeriesData = this.ajaxData.series;
			$.each(carSeries, function (index, series) {
				columnSeries[index] = {
					name: series,
					data: columnSeriesData.y[series]
				}
			})

            // console.log(this);
			this.columnData.xAxis.categories = columnSeriesData.x;
			this.columnData.series = columnSeries;
			console.log(this.columnData.series);
			var chart;
			chart = new Highcharts.Chart(this.columnData);
		}
	}

	window.balanceQuery.distribute = {
		ajaxData: {},
		updateDistributeTable: function() {
			 var color = this.ajaxData.colorArray;
			 var configName = this.ajaxData.configNameArray;
			 var detail = this.ajaxData.detail;
			 var colorTotal = this.ajaxData.colorTotal;
			 var configTotal = this.ajaxData.configTotal;

			//clear table and initialize it
			$("#tableCarsDistribute thead").html("<tr />");
			$("#tableCarsDistribute tbody").html("");
			$.each(configName, function (index, configName) {
				$("<tr />").appendTo($("#tableCarsDistribute tbody"));
			})
			colorTotalTr = $("<tr />").appendTo($("#tableCarsDistribute tbody"));

			//first column description
			var colorTr = $("#tableCarsDistribute tr:eq(0)");
			$("<td />").html('车型/配置').addClass('alignCenter').appendTo(colorTr);
			$.each(configName, function (index, configName) {
				$("<td />").html(configName).addClass('configNameTd').appendTo($("#tableCarsDistribute tr:eq("+ (index+1) +")"));
			});
			$("<td />").html('合计').addClass('alignCenter').appendTo(colorTotalTr);

			//detail data
			$.each(detail, function (index, value) {
				$("<td />").html(value.color).appendTo(colorTr);
				$.each(configName, function (index, configName) {
					aCount = $("<a />").addClass("queryCars").attr("rel", "tooltip").attr("data-toggle", "tooltip").attr("data-placement", "top").attr("title", value.color);
					aCount.html(value[configName]['count']);
					aCount.attr("orderConfigId", value[configName]['orderConfigId']);
					aCount.attr("coldResistant", value[configName]['coldResistant']);
					aCount.attr("color", value.color);
					aCount.attr("configName", configName);
					$("<td />").html(aCount).appendTo($("#tableCarsDistribute tr:eq("+ (index+1) +")"));
				});
			});

			//config total
			$("<td />").html('总计').appendTo(colorTr)
			$.each(configName, function (index, configName) {
				aCount = $("<a />").addClass("queryCars").attr("rel", "tooltip").attr("data-toggle", "tooltip").attr("data-placement", "top");
				aCount.html(configTotal[configName]['count']);
				aCount.attr("orderConfigId", configTotal[configName]['orderConfigId']);
				aCount.attr("coldResistant", configTotal[configName]['coldResistant']);
				aCount.attr("color", "");
				aCount.attr("configName", configName);
				$("<td />").html(aCount).appendTo($("#tableCarsDistribute tr:eq("+ (index+1) +")"));
			});

			//color total
			var totalTotal = 0;
			$.each(colorTotal, function (index, value) {
				$("<td />").html(value).appendTo(colorTotalTr);
				totalTotal += value;
			})
			$("<td />").html(totalTotal).appendTo(colorTotalTr);
		}
	}

	window.balanceQuery.balancePeriod = {
		

		ajaxData: {},

		columnData: {
			chart: {
                type: 'column',
                renderTo: 'periodContainer'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            legend: {
            	verticalAlign: 'top'
            },
            xAxis: {
                categories: [],
                labels: {
                    rotation: -45,
                    align: 'right'
                }
            },
            credits: {
                enabled: false
            },
            yAxis: {
                min: 0,
                title: {
                    enabled: false,
                    // text: '数量（辆）'
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
                // headerFormat: '<span style="font-size:14px">{point.key}H</span><table>',
                // pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                //     '<td style="padding:0"><b>{point.y}</b></td></tr>',
                // footerFormat: '</table>',
                shared: true,
                useHTML: true,
                formatter: function()  {
                	console.log(this.points)
                	var s = this.x + 'H<table>';
                	var total = 0;
                	$.each(this.points, function (i, point) {
                		s += '<tr><td style="color:'+ point.series.color +';padding:0">'+ point.series.name +': </td>';
                    	s +='<td style="padding-left:5px;text-align:right"><b>'+point.y+'</b></td></tr>';
                    	total += this.y;
                	})
                	s += '<tr><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>总计:</b></td><td style="text-align: right;border-top-style:solid;border-top-width: 1px;"><b>'+ total +'</b></td></tr>';
                	s += '</table>';
	                return s;
                }
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

		drawColumn: function () {
			columnSeries = [];
			carSeries = this.ajaxData.carSeries;
			columnSeriesData = this.ajaxData.series;
			$.each(carSeries, function (index, series) {
				columnSeries[index] = {
					name: series,
					data: columnSeriesData.y[series]
				}
			})

			this.columnData.xAxis.categories = columnSeriesData.x;
			this.columnData.series = columnSeries;
			var chart;
			chart = new Highcharts.Chart(this.columnData);
		},

		updatePeriodTable: function () {
			var carseries = this.ajaxData.carSeries;
			var detail = this.ajaxData.detail;
			var total = this.ajaxData.seriesTotal;

			$("#tableBalancePeriod thead").html("<tr />");
			$("#tableBalancePeriod tbody").html("");		
	        $.each(carSeries, function (index,value) {
	            $("<tr />").appendTo($("#tableBalancePeriod tbody"));
	        });
	        
			var thTr = $("#tableBalancePeriod tr:eq(0)");
	        $("<th />").html("车系").appendTo(thTr);    
	        $("<th />").html("合计").appendTo(thTr);

	        totalTotal = 0;
			$.each(carSeries, function (index, series) {
	            $("<td />").html(series).appendTo($("#tableBalancePeriod tr:eq("+(index*1+1)+")"));
	            $("<td />").html(total[series]).appendTo($("#tableBalancePeriod tr:eq(" + (index*1+1) + ")"));
	            totalTotal += total[series];
	        });

	        var totalTr =  $("<tr />").appendTo($("#tableBalancePeriod tbody"));
	        $("<td />").html('总计').appendTo(totalTr);
	        $("<td />").html(totalTotal).appendTo(totalTr);

			$.each(detail, function (index,value) {
				$("<td />").html(value.periodSegment).appendTo(thTr);
				detailTotal = 0;
				$.each(carSeries, function (index,series) {
					$("<td />").html(value[series]).appendTo($("#tableBalancePeriod tr:eq("+(index*1+1)+")"));
					detailTotal += parseInt(value[series]);
				});
				$("<td />").html(detailTotal).appendTo(totalTr);
			});

		}
	}

	// window.balanceQuery.recyclePeriod ={
	// 	ajaxData: {},
		
	// 	chartData: {
 //            chart: {
 //                type: 'pie'
 //            },
 //            title: {
 //                text: ''
 //            },
 //            credits: {
 //                enabled: false
 //            },
 //            yAxis: {
 //                title: {
 //                    text: ''
 //                }
 //            },
 //            plotOptions: {
 //                pie: {
 //                    shadow: false,
 //                    center: ['50%', '50%']
 //                }
 //            },
 //            tooltip: {
 //        	    valueSuffix: '辆'
 //            },
 //            series: [{
 //                name: '区域',
 //                data: [],
 //                size: '60%',
 //                dataLabels: {
 //                    formatter: function() {
 //                        return this.y > 0 ? '<b>'+ this.point.name +'</b> ' + "[" + this.y +"]": null;
 //                    },
 //                    color: 'white',
 //                    distance: -30
 //                }
 //            }, {
 //                name: '周期',
 //                data: [],
 //                size: '80%',
 //                innerSize: '60%',
 //                dataLabels: {
 //                    formatter: function() {
 //                        // display only if larger than 0
 //                        return this.y > 0 ? '<b>'+ this.point.name +'</b> '+  "[" + this.y + "]" : null;
 //                    }
 //                }
 //            }]
 //        },

 //        drawDonut: function() {
 //        	var data = this.ajaxData.dataDonut;
 //        	colors = Highcharts.getOptions().colors;
 //        	var stateData = [];
	//         var periodData = [];
	//         $.each(data, function (key, value) {
	//         	stateData.push({
	//                 name: key,
	//                 y: value.y,
	//                 color: colors[value.colorIndex],
	//             });
	    
	//             // add version data
	//             for (var j = 0; j < value.drilldown.data.length; j++) {
	//                 var brightness = 0.2 - (j / value.drilldown.data.length) / 5 ;
	//                 periodData.push({
	//                     name: value.drilldown.categories[j],
	//                     y: value.drilldown.data[j],
	//                     color: Highcharts.Color(colors[value.colorIndex]).brighten(brightness).get()
	//                 });
	//             }
	//         })

	//         this.chartData.series[0].data = stateData;
	//         this.chartData.series[1].data = periodData;
	//         $("#recycleDonutContainer").highcharts(this.chartData);
 //        },

 //        updateRecycleTable: function() {
 //        	var recyclePeriod = this.ajaxData.recyclePeriod;
 //        	var detail  = this.ajaxData.detail;
 //        	var stateTotal = this.ajaxData.stateTotal;
 //        	var periodTotal = this.ajaxData.periodTotal;

 //        	//clear table and initialize it
 //        	$("#tableRecyclePeriod thead").html("<tr />");
	// 		$("#tableRecyclePeriod tbody").html("");
	// 		$.each(recyclePeriod, function (index, which) {
	// 			$("<tr />").appendTo($("#tableRecyclePeriod tbody"));
	// 		});
	// 		stateTotalTr = $("<tr />").appendTo($("#tableRecyclePeriod tbody"));

	// 		//first column description
	// 		var stateTr = $("#tableRecyclePeriod tr:eq(0)");
	// 		$("<td />").html('周期').addClass('alignCenter').appendTo(stateTr);
	// 		$.each(recyclePeriod, function (index, which){
	// 			$("<td />").html(which).addClass('alignCenter').appendTo($("#tableRecyclePeriod tr:eq("+ (index+1) +")"));
	// 		});
	// 		$("<td />").html('合计').addClass('alignCenter').appendTo(stateTotalTr);

	// 		//detail data
	// 		$.each(detail, function (index ,value) {
	// 			$("<td />").html(value.state).appendTo(stateTr);
	// 			$.each(recyclePeriod, function (index, which){
	// 				aCount = $("<a />").addClass("recycleCars");
	// 				aCount.html(value[which].countSum);
	// 				aCount.attr("state", value.state);
	// 				aCount.attr("recyclePeriod", which);
	// 				$("<td />").html(aCount).appendTo($("#tableRecyclePeriod tr:eq("+ (index+1) +")"));
	// 			});
	// 		})

	// 		//period total
	// 		$("<td />").html('总计').appendTo(stateTr);
	// 		$.each(recyclePeriod, function (index, which) {
	// 			aCount = $("<a />").addClass("recycleCars");
	// 			aCount.html(periodTotal[which].countSum);
	// 			aCount.attr("state", 'recycle');
	// 			aCount.attr("recyclePeriod", which);
	// 			$("<td />").html(aCount).appendTo($("#tableRecyclePeriod tr:eq("+ (index+1) +")"));
	// 		})

	// 		//state total
	// 		var totalTotal = 0;
	// 		$.each(stateTotal, function (key, value) {
	// 			aCount = $("<a />").addClass("recycleCars");
	// 			aCount.html(value.countSum);
	// 			aCount.attr("state", key);
	// 			aCount.attr("recyclePeriod", "");
	// 			$("<td />").html(aCount).appendTo(stateTotalTr);
	// 			totalTotal += value.countSum;
	// 		})
	// 		$("<td />").html(totalTotal).appendTo(stateTotalTr);
 //        }
	// }
})