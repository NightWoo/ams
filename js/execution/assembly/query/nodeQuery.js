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
		$("#headQualityLi").addClass("active");
		$("#leftNodeQueryLi").addClass("active");

		$("#startTime").val(currentDate8());
		$("#endTime").val(currentDate16());

		resetAll();
	}

	$("#selectNode").change(function () {
		var nV = this.value;

		if (nV === "CHECK_LINE") {
			$("#dpuTab,#passRateTab,#platoTab,#dutyDistributionTab").hide();
			$("#selectItem").show();
		} else {
			$("#dpuTab,#passRateTab,#platoTab,#dutyDistributionTab").show();
			$("#selectItem").val("").hide();
		}
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
		$(".tableTestline, #resultTable").hide();
		$("#selectItem").hide();
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
	
	$("#btnQuery").bind("click",toQuery);
	function toQuery() {
		//clear last
		$("#resultTable tbody").text("");

		//if validate passed
		var index = $("#tabs li").index($('#tabs .active'));
		console.log(index);
		if (index === 1)
			ajaxPlato();
		else if (index === 2)
			ajaxFautlDistribute();
		else if (index === 3)
			ajaxDpu();
		else if (index === 4)
			ajaxPassRate();
		else if (index === 5)
			ajaxStatistics();
		else if (index === 0){
			testlineItem = $("#selectItem").val();
			if(testlineItem == ""){
				ajaxQuery(1);
			} else {
				toQueryTestline(testlineItem, 1);
			}
		}
		return false;
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

	//testline pagination
	$("#preTestline").click(
		function (){
			if(parseInt($("#curTestline").attr("page")) > 1){
				$(".tableTestline tbody").html("");
				item = $("#selectItem").val();
				targetPage = parseInt($("#curTestline").attr("page")) - 1;
				toQueryTestline(item,targetPage);
			}
		}
	);

	$("#nextTestline").click(
		function (){
			if(parseInt($("#curTestline").attr("page")) * 20 < parseInt($("#totalTestline").attr("total")) ){
				$(".tableTestline tbody").html("");
				item = $("#selectItem").val();
				targetPage = parseInt($("#curTestline").attr("page")) + 1;
				toQueryTestline(item,targetPage);
			}
		}
	);

	$("#firstTestline").click(
		function () {
			if(parseInt($("#curTestline").attr("page")) > 1){
				$(".tableTestline tbody").html("");
				item = $("#selectItem").val();
				toQueryTestline(item,1);
			}
		}
	);

	$("#lastTestline").click(
		function () {
			if(parseInt($("#curTestline").attr("page")) * 20 < parseInt($("#totalTestline").attr("total")) ){
				$(".tableTestline tbody").html("");
				item = $("#selectItem").val();
				totalPage = parseInt($("#totalTestline").attr("total"))%20 === 0 ? parseInt($("#totalTestline").attr("total"))/20 : parseInt($("#totalTestline").attr("total"))/20 + 1;
				toQueryTestline(item,totalPage);
			}
		}
	)

	$("#exportTestline").click(
		function () {
			toExportTestline($(this).attr("export"));
			return false;
		}
	);

	//监听tab切换事件，去取comp列表
	$("#tabs li").click(function () {
		var index = $("#tabs li").index(this);
		if(index<5)
			$("#paginationCars, #paginationTestline").hide();
		if (index == 1)
			ajaxPlato();
		else if (index === 2)
			ajaxDutyDistribution();
		else if (index === 3)
			ajaxDpu();
		else if (index === 4)
			ajaxPassRate();
		// else if (index === 5)
		// 	ajaxStatistics();
		else if (index === 0){
			testlineItem = $("#selectItem").val();
			if(testlineItem == ""){
				ajaxQuery(1);
			} else {
				toQueryTestline(testlineItem);
			}
		}
	});

	$('#divRadio :radio').change(function () {
		$('#pieContainer').text('');
		var type = $(this).val();
		byd.pie.drawPie(type);
		byd.pie.updatePieTable(type);
	});

	//bootstrap-datatimepicker
	// $("#startTime, #endTime").datetimepicker({
	//     format: 'yyyy-mm-dd hh:ii',
	//     autoclose: true,
	// 	todayBtn: true,
	// 	pickerPosition: "bottom-left",
	// 	language: "zh-CN"
 	//  });
	
	//jquery-ui-datetimepicker
    $('#startTime, #endTime').datetimepicker({
		timeFormat: "HH:mm",
		changeMonth: true,
	    changeYear: true,
	    showOtherMonths: true,
	    selectOtherMonths: true,
	    duration: "fast",
	    buttonImageOnly: true,
	});
//-------------------END event bindings -----------------------



function getSeriesChecked () {
	var f0Checked = $("#checkboxF0").attr("checked") === "checked";
	var m6Checked = $("#checkboxM6").attr("checked") === "checked";
	var _6BChecked = $("#checkbox6B").attr("checked") === "checked";
	
	var temp = [];
	if (f0Checked)
		temp.push($("#checkboxF0").val());
	if (m6Checked)
		temp.push($("#checkboxM6").val());
	if (_6BChecked)
		temp.push($("#checkbox6B").val());
	return temp.join(",");
}
/*
 * ----------------------------------------------------------------
 * Ajax query
 * ----------------------------------------------------------------
 */
	function ajaxQuery (targetPage) {
		$(".tableTestline, #resultTable").hide();
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: FAULT_QUERY,//ref:  /bms/js/service.js
		    data: { "vin": $('#vinText').val(), 
		    		"node":$("#selectNode").val(),
		    		'component': $('#componentText').val(),
					'mode': $('#faultModeText').val(),
					"series": getSeriesChecked(),
					"stime":$("#startTime").val(),
					"etime":$("#endTime").val(),
					"perPage":20,
					"curPage":targetPage || 1},
		    success:function (response) {
		    	if(response.success){
		    		$("#resultTable tbody").html("");
		    		if ($("#selectNode").val() == "WDI") {
		    			$("#thChecker, #thSubChecker").show();
		    		} else {
		    			$("#thChecker, #thSubChecker").hide();
		    		}
		    		$.each(response.data.data,function (index,value) {
		    			var nodeNameTd = "<td>" + value.node_name + "</td>";
		    			var seriesTd = "<td>" + value.series + "</td>";
		    			var vinTd = "<td>" + value.vin + "</td>";
		    			var componentTd = "<td>" + value.component_name + "</td>";
						var faultTd = "<td>" + value.fault_mode + "</td>";
						var faultStatusTd = "<td>" + value.fault_status + "</td>";
		    			var driverNameTd = "<td>" + value.driver_name + "</td>";
		    			var createTimeTd = "<td>" + value.create_time + "</td>";
		    			var userNameTd = "<td>" + value.user_name + "</td>";
		    			var memoTd = "<td>" + value.modify_time + "</td>";
		    			var checkerTd = "";
		    			var subCheckerTd = "";
		    			if ($("#selectNode").val() == "WDI") {
		    				checkerTd = "<td>" + value.checker + "</td>";
		    				subCheckerTd = "<td>" + value.sub_checker + "</td>";
		    			}
		    			var tr = "<tr>" + nodeNameTd + seriesTd + vinTd + componentTd + faultTd + 
		    				faultStatusTd + driverNameTd + createTimeTd + memoTd 
		    				+ checkerTd + subCheckerTd+  "</tr>";
		    			$("#resultTable tbody").append(tr);
		    		});
					$("#resultTable").show();
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

	function toExportTestline(item){
		window.open(EXPORT_TESTLINE_RECORDS +
			"?&item=" + item +
			"&stime=" + $("#startTime").val() +
			"&etime=" + $("#endTime").val() +
			"&series=" + getSeriesChecked()
		)
	}

	function toQueryTestline(item,targetPage) {
		$(".tableTestline, #resultTable").hide();
		$.ajax({
			url: QUERY_TESTLINE_RECORDS,
			type: "get",
			dataType: "json",
			data: {
				"item" : item,
				"stime": $("#startTime").val(),
				"etime": $("#endTime").val(),
				"series": getSeriesChecked(),
				"perPage": 20,
				"curPage":targetPage || 1
			},
			error: function(){alertError();},
			success: function (response) {
				if(response.success){
					showResult(item, response.data.data);
					//deal with pager	
		    		if(response.data.pager.curPage == 1) {
		    			//$(".prePage").hide();
						$("#preTestline, #firstTestline").addClass("disabled");
						$("#preTestline a, #firstTestline a").removeAttr("href");
					} else {
	    				//$(".prePage").show();
						$("#preTestline, #firstTestline").removeClass("disabled");
						$("#preTestline a, #firstTestline a").attr("href","#");
					}
	    			if(response.data.pager.curPage * 20 >= response.data.pager.total ) {
	    				//$(".nextPage").hide();
						$("#nextTestline, #lastTestline").addClass("disabled");
						$("#nextTestline a, #lastTestline a").removeAttr("href");
					} else {
	    				//$(".nextPage").show();
						$("#nextTestline, #lastTestline").removeClass("disabled");
						$("#nextTestline a, #lastTestline a").attr("href","#");
					}
					$("#curTestline").attr("page", response.data.pager.curPage);
					$("#curTestline a").html(response.data.pager.curPage);
					$("#totalTestline").attr("total", response.data.pager.total);
					$("#totalTestline").html("导出全部" + response.data.pager.total + "条记录");
					$("#exportTestline").attr("export", item);

					$("#paginationTestline").show();
				} else {
					alert(response.message);
				}
			}
		})
	}

	function showResult(item, data){
		switch(item){
			case "NCA" :
				setTableNCA(data);
				break;
			case "Light" :
				setTableLight(data);
				break;
			case "Slide" :
				setTableSlide(data);
				break;
			case "Brake" :
				setTableBrake(data);
				break;
			case "Speed" :
				setTableSpeed(data);
				break;
			case "Gas" :
				setTableGas(data);
				break;
			default: 
				break;
		}
	}

	function setTableNCA(data) {
		$("#tableNCA tbody").html("");
		$.each(data, function(index, value){
			tr = $("<tr />");
			$("<td />").html(value.series_name).appendTo(tr);
			$("<td />").html(value.vin).appendTo(tr);
			$("<td />").html(value.ToeLeft_F).appendTo(tr);
			$("<td />").html(value.ToeRight_F).appendTo(tr);
			$("<td />").html(value.ToeTotal_F).appendTo(tr);
			$("<td />").html(value.ToeFlag_F).appendTo(tr);
			$("<td />").html(value.ToeLeft_R).appendTo(tr);
			$("<td />").html(value.ToeRight_R).appendTo(tr);
			$("<td />").html(value.ToeTotal_R).appendTo(tr);
			$("<td />").html(value.ToeFlag_R).appendTo(tr);
			$("<td />").html(value.Toe_Flag).appendTo(tr);
			$("#tableNCA tbody").append(tr);
		})
		$("#tableNCA").show();
	}

	function setTableLight(data) {
		$("#tableLight tbody").html("");
		$.each(data, function(index, value){
			tr = $("<tr />");
			$("<td />").html(value.series_name).appendTo(tr);
			$("<td />").html(value.vin).appendTo(tr);
			$("<td />").html(value.LM_Inten).appendTo(tr);
			$("<td />").html(value.LM_UDAngle).appendTo(tr);
			$("<td />").html(value.LM_LRAngle).appendTo(tr);
			$("<td />").html(value.LM_Height).appendTo(tr);
			$("<td />").html(value.LM_Flag).appendTo(tr);
			$("<td />").html(value.LL_Inten).appendTo(tr);
			$("<td />").html(value.LL_UDAngle).appendTo(tr);
			$("<td />").html(value.LL_LRAngle).appendTo(tr);
			$("<td />").html(value.LL_Height).appendTo(tr);
			$("<td />").html(value.LL_Flag).appendTo(tr);
			$("<td />").html(value.RM_Inten).appendTo(tr);
			$("<td />").html(value.RM_UDAngle).appendTo(tr);
			$("<td />").html(value.RM_LRAngle).appendTo(tr);
			$("<td />").html(value.RM_Height).appendTo(tr);
			$("<td />").html(value.RM_Flag).appendTo(tr);
			$("<td />").html(value.RL_Inten).appendTo(tr);
			$("<td />").html(value.RL_UDAngle).appendTo(tr);
			$("<td />").html(value.RL_LRAngle).appendTo(tr);
			$("<td />").html(value.RL_Height).appendTo(tr);
			$("<td />").html(value.RL_Flag).appendTo(tr);
			$("<td />").html(value.Light_Flag).appendTo(tr);
			$("#tableLight tbody").append(tr);
		})
		$("#tableLight").show();
	}

	function setTableSlide(data) {
		$("#tableSlide tbody").html("");
		$.each(data, function(index, value){
			tr = $("<tr />");
			$("<td />").html(value.series_name).appendTo(tr);
			$("<td />").html(value.vin).appendTo(tr);
			$("<td />").html(value.Slide).appendTo(tr);
			$("<td />").html(value.Slide_Flag).appendTo(tr);
			$("#tableSlide tbody").append(tr);
		})
		$("#tableSlide").show();
	}

	function setTableBrake(data) {
		$("#tableBrake tbody").html("");
		$.each(data, function(index, value){
			tr = $("<tr />");
			$("<td />").html(value.series_name).appendTo(tr);
			$("<td />").html(value.vin).appendTo(tr);
			$("<td />").html(value.AxleWeight_F).appendTo(tr);
			$("<td />").html(value.Brake_FL).appendTo(tr);
			$("<td />").html(value.Brake_FR).appendTo(tr);
			$("<td />").html(value.BrakeSumPer_F).appendTo(tr);
			$("<td />").html(value.BrakeDiffPer_F).appendTo(tr);
			$("<td />").html(value.BrakeResistance_FL).appendTo(tr);
			$("<td />").html(value.BrakeResistance_FR).appendTo(tr);
			$("<td />").html(value.BrakeFlag_F).appendTo(tr);
			$("<td />").html(value.AxleWeight_R).appendTo(tr);
			$("<td />").html(value.Brake_RL).appendTo(tr);
			$("<td />").html(value.Brake_RR).appendTo(tr);
			$("<td />").html(value.BrakeSumPer_R).appendTo(tr);
			$("<td />").html(value.BrakeDiffPer_R).appendTo(tr);
			$("<td />").html(value.BrakeResistance_RL).appendTo(tr);
			$("<td />").html(value.BrakeResistance_RR).appendTo(tr);
			$("<td />").html(value.BrakeFlag_R).appendTo(tr);
			$("<td />").html(value.BrakeSum).appendTo(tr);
			$("<td />").html(value.BrakeSumPer).appendTo(tr);
			$("<td />").html(value.BrakeSum_Flag).appendTo(tr);
			$("<td />").html(value.ParkSum).appendTo(tr);
			$("<td />").html(value.ParkSumPer).appendTo(tr);
			$("<td />").html(value.ParkSum_Flag).appendTo(tr);
			$("<td />").html(value.Brake_Flag).appendTo(tr);
			$("#tableBrake tbody").append(tr);
		})
		$("#tableBrake").show();
	}

	function setTableSpeed(data) {
		$("#tableSpeed tbody").html("");
		$.each(data, function(index, value){
			tr = $("<tr />");
			$("<td />").html(value.series_name).appendTo(tr);
			$("<td />").html(value.vin).appendTo(tr);
			$("<td />").html("40").appendTo(tr);
			$("<td />").html(value.SpeedValue).appendTo(tr);
			$("<td />").html(value.SpeedInaccuracy).appendTo(tr);
			$("<td />").html(value.Speed_Flag).appendTo(tr);
			$("#tableSpeed tbody").append(tr);
		})
		$("#tableSpeed").show();
	}

	function setTableGas(data) {
		$("#tableGas tbody").html("");
		$.each(data, function(index, value){
			tr = $("<tr />");
			$("<td />").html(value.series_name).appendTo(tr);
			$("<td />").html(value.vin).appendTo(tr);
			$("<td />").html(value.GasHC_Low).appendTo(tr);
			$("<td />").html(value.GasCO_Low).appendTo(tr);
			$("<td />").html(value.GasLow_Flag).appendTo(tr);
			$("<td />").html(value.GasHC_High).appendTo(tr);
			$("<td />").html(value.GasCO_High).appendTo(tr);
			$("<td />").html(value.GasHigh_Flag).appendTo(tr);
			$("<td />").html(value.Gas_Flag).appendTo(tr);
			$("#tableGas tbody").append(tr);
		})
		$("#tableGas").show();
	}
	
	function ajaxDpu (targetPage) {
		distinctLabel = [];
		
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: FAULT_QUERY_DPU,//ref:  /bms/js/service.js
		    data: { "vin": $('#vinText').val(), 
		    		"node":$("#selectNode").val(),
					"component":$("#componentText").val(),
					"mode":$("#faultModeText").val(),
					"series": getSeriesChecked(),
					"stime":$("#startTime").val(),
					"etime":$("#endTime").val()
				},
		    success:function (response) {
		    	if(response.success){
		    		
		    		
		    		drawLineChart(response.data);
		    		tempLineData = response.data;
		    		changeLineTable(response.data);
		    		
		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}

	/*
	  When ajaxDPU success,update the DPU line table.
	  @param data -> response.data.series
	*/
	function changeLineTable (data) {
		var carSeries = data.carSeries;
        var detail = data.detail;
        var total = data.total;
        //clean before
        $("#tableDpu thead").html("<tr />");
        $("#tableDpu tbody").html("<tr />");
        $.each(carSeries, function (index,value) {
            $("<tr /><tr /><tr />").appendTo($("#tableDpu tbody"));
        });
        //get tr
        var timeTr = $("#tableDpu tr:eq(0)");
        //first column descriptions
        $("<td />").html("日期").appendTo(timeTr);
		$("<td />").html("合计").appendTo(timeTr);

        $.each(carSeries, function (index,series) {
            $("<td />").html(series + "_DPU").appendTo($("#tableDpu tr:eq("+(index*3+1)+")"));
            $("<td />").html(series + "_故障数").appendTo($("#tableDpu tr:eq("+(index*3+2)+")"));
            $("<td />").html(series + "_车辆数").appendTo($("#tableDpu tr:eq("+(index*3+3)+")"));

            $("<td />").html(total[series]['dpuTotal']).appendTo($("#tableDpu tr:eq("+(index*3+1)+")"));
            $("<td />").html(total[series]['faultTotal']).appendTo($("#tableDpu tr:eq("+(index*3+2)+")"));
            $("<td />").html(total[series]['carTotal']).appendTo($("#tableDpu tr:eq("+(index*3+3)+")"));

        });

		$.each(detail, function (index,value) {
            $("<td />").html(value.time).appendTo(timeTr);
            $.each(carSeries, function (index,series) {
                $("<td />").html(value[series].dpu).appendTo($("#tableDpu tr:eq("+(index*3+1)+")"));
                $("<td />").html(value[series].faults).appendTo($("#tableDpu tr:eq("+(index*3+2)+")"));
                $("<td />").html(value[series].cars).appendTo($("#tableDpu tr:eq("+(index*3+3)+")"));
            });
        });
	}

	/*
		draw DPU line
	*/
	function drawLineChart (data) {
		lineSeries = [];
        carSeries = data.carSeries;
        lineData = data.series;
        $.each(carSeries, function (index,series) {
            lineSeries[index] = {name : series, data: prepare(lineData.y[series])};
        });
		var chart;
		chart = new Highcharts.Chart({
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
                href: '',
                text: ''
            },
            // subtitle: {
            //     text: 'Source: WorldClimate.com',
            //     x: -20
            // },
            xAxis: {
                categories: lineData.x,
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
            plotOptions:{
            
            },
            series: lineSeries	
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
		
		window.open(FAULT_EXPORT + "?&node=" + $("#selectNode").val() + 
			'&component=' + $('#componentText').val() +
			'&mode=' + $('#faultModeText').val() +
			"&series=" + getSeriesChecked() +
			"&stime=" + $("#startTime").val() +
			"&etime=" + $("#endTime").val()
		);
	}


	function ajaxPlato () {
		
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: NODE_QUERY_PLATON,//ref:  /bms/js/service.js
		    data: { "vin": $('#vinText').val(), 
		    		"node":$("#selectNode").val(),
					"series": getSeriesChecked(),
					"stime":$("#startTime").val(),
					"etime":$("#endTime").val(),
					"component":$("#componentText").val(),
					"mode":$("#faultModeText").val(),
				},
		    success:function (response) {
		    	if(response.success){
		    		
		    		drawPlato(response.data.series);
		    		changePlatoTable(response.data.detail);
		    		// drawLineChart(response.data.series);
		    		// tempLineData = response.data.series;
		    		// changeLineTable(response.data.detail);
		    		
		    	}else
		    		alert(response.message);
		    },
		    error:function(){alertError();}
		});
	}

	function drawPlato (lineData) {
		var chart;
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'platoContainer'
            },
            title: {
                text: ''
            },
            credits: {
                href: '',
                text: ''
            },
            xAxis: {
                categories: lineData.x,
                labels: {
                    rotation: -45,
                    align: 'right',
                    style : {
                    	fontSize: '12px',
                    	fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
                    	//fontWeight: "bold"
                    }
                }
            },
            yAxis: [{ // Primary yAxis
                labels: {
                    style: {
                        color: '#4572A7'
                    }
                },
                title: {
                    text: '故障数',
                    style: {
                        color: '#4572A7'
                    }
                },
                min: 0
            }, { // Secondary yAxis
                title: {
                    text: '累计百分率(%)',
                    style: {
                        color: '#AA4643'
                    }
                },
                labels: {
                    formatter: function() {
                        return Math.round(this.value * 100) + '%';
                    },
                    style: {
                        color: '#AA4643'
                    }
                },
                opposite: true
            }],
            tooltip: {
                formatter: function() {
                    var s;
                    console.log(this.point.yAxis);
                    if (this.y > 0 && this.y < 1) { // the pie chart
                        s = ''+
                            this.x  +': '+ (this.y * 100).toFixed(1) + '%';
                    } else {
                        s = ''+
                            this.x +': '+ this.y ;
                    }
                    return s;
                }
				
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'top',
                borderWidth: 0
            },
			
            series: [{
                type: 'column',
                color: '#4572A7',
                name: '故障数',
                data: lineData.column
            } ,{
                type: 'spline',
                color: '#AA4643',
                name: '累计百分率',
                data: lineData.y,
                yAxis: 1,
                marker: {
                	lineWidth: 2,
                	lineColor: Highcharts.getOptions().colors[3],
                	fillColor: 'white'
                }
            }
            ]
        });
	}

	function changePlatoTable (detail) {
		$("#tablePlato thead").html("<tr />");
		$("#tablePlato tbody").html("<tr /><tr /><tr />");
		var thTr = $("#tablePlato tr:eq(0)");
		var dataTr = $("#tablePlato tr:eq(1)");
		var percentageTr = $("#tablePlato tr:eq(2)");
		var singleLackTr = $("#tablePlato tr:eq(3)");
		$("<td />").html("故障").appendTo(thTr);
		$("<td />").html("数量").appendTo(dataTr);
		$("<td />").html("百分比").appendTo(percentageTr);
		$("<td />").html("DPU").appendTo(singleLackTr);
		$.each(detail, function (index,value) {
			$("<td />").html(value.name).appendTo(thTr);
			$("<td />").html(value.count).appendTo(dataTr);
			$("<td />").html(value.percentage).appendTo(percentageTr);
			$("<td />").html(value.dpu).appendTo(singleLackTr);
		});
	}


	function ajaxPassRate () {
		
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: NODE_QUERY_QUALIFIED,//ref:  /bms/js/service.js
		    data: { "vin": $('#vinText').val(), 
		    		"node":$("#selectNode").val(),
					"series": getSeriesChecked(),
					"stime":$("#startTime").val(),
					"etime":$("#endTime").val(),
				},
		    success:function (response) {
		    	if(response.success){
		    		
		    		drawPassRate(response.data);
		    		changePassRateTable(response.data);
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
		draw drawPassRate line
	*/
	function drawPassRate (data) {
		lineSeries = [];
        carSeries = data.carSeries;
        lineData = data.series;
        $.each(carSeries, function (index,series) {
            lineSeries[index] = {name : series, data: prepare(lineData.y[series])};
        });
		var chart;
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'passRateContainer',
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
                        return Math.round(this.value * 100) + '%';
                    }
                },
                title: {
                    text: '合格率'
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
                        return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ (this.y * 100).toFixed(1) + '%';
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
	function changePassRateTable (data) {
		carSeries = data.carSeries;
		detail = data.detail;
		total = data.total;
		$("#tablePassRate thead").html("<tr />");
		$("#tablePassRate tbody").html("");
        $.each(carSeries, function (index,value) {
            $("<tr /><tr /><tr />").appendTo($("#tablePassRate tbody"));
        });
        //get tr
        var thTr = $("#tablePassRate tr:eq(0)");
		$("<td />").html("日期").appendTo(thTr);
		$("<td />").html("合计").appendTo(thTr);

        $.each(carSeries, function (index, series) {
        	//first column descriptions
            $("<td />").html(series + "_合格率").appendTo($("#tablePassRate tr:eq("+(index*3+1)+")"));
            $("<td />").html(series + "_合格数").appendTo($("#tablePassRate tr:eq("+(index*3+2)+")"));
            $("<td />").html(series + "_车辆数").appendTo($("#tablePassRate tr:eq("+(index*3+3)+")"));

            $("<td />").html(total[series]['rateTotal']).appendTo($("#tablePassRate tr:eq("+(index*3+1)+")"));
            $("<td />").html(total[series]['qualifiedTotal']).appendTo($("#tablePassRate tr:eq("+(index*3+2)+")"));
            $("<td />").html(total[series]['carTotal']).appendTo($("#tablePassRate tr:eq("+(index*3+3)+")"));

        });        

		$.each(detail, function (index,value) {
			$("<td />").html(value.time).appendTo(thTr);
			
			$.each(carSeries, function (index,series) {
                $("<td />").html(value[series].rate).appendTo($("#tablePassRate tr:eq("+(index*3+1)+")"));
                $("<td />").html(value[series].qualified).appendTo($("#tablePassRate tr:eq("+(index*3+2)+")"));
                $("<td />").html(value[series].total).appendTo($("#tablePassRate tr:eq("+(index*3+3)+")"));
            });
		});
	}

	function ajaxStatistics () {
		
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: NODE_QUERY_CAR,//ref:  /bms/js/service.js
		    data: { "vin": $('#vinText').val(), 
		    		"node":$("#selectNode").val(),
					"series": getSeriesChecked(),
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
                href: 'http://www.bydauto.com.cn',
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
            $("<td />").html(value + "_车辆数").appendTo($("#tableStatistic tr:eq("+(index*1+1)+")"));
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

	function ajaxFautlDistribute (targetPage) {
		var ajaxData = { 
				'vin': $('#vinText').val(), 
			    'node': $('#selectNode').val(),
				'component': $('#componentText').val(),
				'mode': $('#faultModeText').val(),
				'series': getSeriesChecked(),
				'stime': $('#startTime').val(),
				'etime': $('#endTime').val()
			};
			$.ajax({
				type: 'get',//使用get方法访问后台
			    dataType: 'json',//返回json格式的数据
			    url: FAULT_QUERY_DISTRIBUTE,//ref:  /bms/js/service.js
			    data: ajaxData,
			    success: function (response) {
	    			if (response.success) {
	    				$('#pieContainer').text('');
	    				var type = $("#divRadio input:radio[name='optionsRadios']:checked").val();
	    				byd.pie.pieAjaxData = response.data;
	    				byd.pie.drawPie(type);
	    				byd.pie.updatePieTable(type);
	    			} else {
	    				alert(response.message);
	    			}
	   			 },
			    error:function(){alertError();}
			});
	}

	function ajaxDutyDistribution (targetPage) {
		var ajaxData = { 
			    'node': $('#selectNode').val(),
				'component': $('#componentText').val(),
				'mode': $('#faultModeText').val(),
				'series': getSeriesChecked(),
				'stime': $('#startTime').val(),
				'etime': $('#endTime').val()
			};
		$.ajax({
			type: 'get',//使用get方法访问后台
		    dataType: 'json',//返回json格式的数据
		    url: FAULT_QUERY_DUTY_DISTRIBUTION,//ref:  /bms/js/service.js
		    data: ajaxData,
		    success: function (response) {
    			if(response.success){
		    		drawDutyPlato(response.data.series);
		    		changeDistibutionTable(response.data.detail);
		    		
		    	}else{
		    		alert(response.message);
		    	}
   			 },
		    error:function(){alertError();}
		});
	}

	function drawDutyPlato (lineData) {
		var chart;
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'dutyDistibutionContainer'
            },
            title: {
                text: ''
            },
            credits: {
                href: '',
                text: ''
            },
            xAxis: {
                categories: lineData.x,
                labels: {
                    rotation: -45,
                    align: 'right',
                    style : {
                    	fontSize: '12px',
                    	fontFamily: 'Helvetica Neue, Microsoft YaHei, Helvetica, Arial, sans-serif',
                    	//fontWeight: "bold"
                    }
                }
            },
            yAxis: [{ // Primary yAxis
                labels: {
                    style: {
                        color: '#4572A7'
                    }
                },
                title: {
                    text: '故障数',
                    style: {
                        color: '#4572A7'
                    }
                },
                min: 0
            }, { // Secondary yAxis
                title: {
                    text: '累积百分率(%)',
                    style: {
                        color: '#AA4643'
                    }
                },
                gridLineWidth: 0,
                labels: {
                    formatter: function() {
                        return Math.round(this.value * 100) + '%';
                    },
                    style: {
                        color: '#AA4643',
                    }
                },
                min: 0,
                max: 1,
                opposite: true,
            }],
            tooltip: {
                shared: true,
                useHTML: true,
                formatter: function() {
                	// console.log(this);
                	var s = this.points[0].key +'<table>';
                	total = 0;
                	$.each(this.points, function(i, point) {
                		value = point.y === null ? 0:point.y;
                		if(point.series.name == '累积率' || point.series.name == '百分率'){
	                    	s += '<tr><td style="color: '+ point.series.color +'">'+ point.series.name +': </td>' +
            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ Math.round(value * 100) +'%</b></td></tr>';
                		} else {
                			s += '<tr><td style="color: '+ point.series.color +'">'+ point.series.name +': </td>' +
            					'<td style="text-align: right;color: '+ point.series.color +'"><b>'+ value +'</b></td></tr>';
                		}
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
			
            series: [{
                type: 'column',
                color: Highcharts.getOptions().colors[0],
                name: '故障数',
                data: lineData.column
            },{
                type: 'line',
				yAxis: 1,
				showInLegend: false,
				color: Highcharts.getOptions().colors[0],
				name: '百分率',
				data: lineData.p,
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
                	lineColor: Highcharts.getOptions().colors[0],
                	fillColor: 'white'
                },
				
				lineWidth: 0
            },{
                type: 'spline',
                color: '#AA4643',
                symbols: 'circle',
                name: '累积率',
                data: lineData.y,
                yAxis: 1,
                marker: {
                	lineWidth: 2,
                	lineColor: Highcharts.getOptions().colors[3],
                	fillColor: 'white',
                }
            }]
        });
	}

	function changeDistibutionTable (detail) {
		$("#tableFaultDistribution thead").html("<tr />");
		$("#tableFaultDistribution tbody").html("<tr /><tr /><tr />");
		var thTr = $("#tableFaultDistribution tr:eq(0)");
		var dataTr = $("#tableFaultDistribution tr:eq(1)");
		var percentageTr = $("#tableFaultDistribution tr:eq(2)");
		var singleLackTr = $("#tableFaultDistribution tr:eq(3)");
		$("<td />").html("责任部门").appendTo(thTr);
		$("<td />").html("数量").appendTo(dataTr);
		$("<td />").html("百分比").appendTo(percentageTr);
		$("<td />").html("DPU").appendTo(singleLackTr);
		$.each(detail, function (index,value) {
			$("<td />").html(value.name).appendTo(thTr);
			$("<td />").html(value.count).appendTo(dataTr);
			$("<td />").html(value.percentage).appendTo(percentageTr);
			$("<td />").html(value.dpu).appendTo(singleLackTr);
		});
	}

	$('body').tooltip(
        {
         selector: "select[rel=tooltip], a[rel=tooltip]"
    });
//-------------------END ajax query -----------------------

});


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

		// ajaxExport : function () {
		// 	window.open(FAULT_EXPORT + '?vin=' + $('#vinText').val() + 
		// 		'&node=' + $('#selectNode').val() + 
		// 		'&component=' + $('#componentText').val() +
		// 		'&mode=' + $('#faultModeText').val() +
		// 		'&series=' + ($('#checkboxF0').val() + ',' + $('#checkboxM6').val()) +
		// 		'&stime=' + $('#startTime').val() +
		// 		'&etime=' + $('#endTime').val()
		// 	);
		// }

	}
	window.byd.handler = {
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

});