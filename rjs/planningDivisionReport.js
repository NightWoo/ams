require.config({
	"baseUrl": "/bms/rjs/lib",
	"paths":{
		"jquery": "./jquery-2.0.3.min",
		"bootstrap": "./bootstrap.min",
		"bootstrap-datetimepicker": "./bootstrap-datetimepicker.min",
		"bootstrap-datetimepicker.zh-CN": "./bootstrap-datetimepicker.zh-CN",
		"head": "../head",
		"service": "../service",
		"common": "../common",
		"dateTimeUtil": "../dateTimeUtil"
	},
	"shim": {
		"bootstrap": ["jquery"],
		"bootstrap-datetimepicker": ["jquery"]
	}
})

require(["dateTimeUtil","head","service","common","jquery","bootstrap","bootstrap-datetimepicker"], function(dateTimeUtil,head,service,common,$) {
	head.doInit();
	initPage();

	$("#startTime").datetimepicker({
	    format: 'yyyy-mm-dd',
	    autoclose: true,
		todayBtn: true,
		pickerPosition: "bottom-left",
		language: "zh-CN",
		minView: "2"
    });


    $("#btnQuery").click(function () {
    	queryReportDaily();
        querySmsText();
    })

    function queryReportDaily () {
    	$("#tableDaily>tbody").html("");
    	$.ajax({
    		url: service.QUERY_PLANNING_DIVISION_DAILY,
    		dataType: "json",
    		data: {
    			"date": $("#startTime").val(),
    		},
    		error: function () {common.alertError();},
    		success: function (response) {
    			if(response.success) {
    				$.each(response.data.countData, function (series, pdTypes) {
    					var num = 0;
    					$.each(pdTypes, function () {
    						num++;
    					})
    					var tmp = $("<tbody />");
    					for(var i=0;i<num;i++) {
    						$("<tr />").appendTo(tmp);
    					}
    					var firstTr = tmp.children("tr:eq(0)");
    					firstTr.addClass("thickBorder");
    					seriesTd = $("<td />").attr("rowspan", num).addClass("rowSpanTd").html(common.seriesName(series)).appendTo(firstTr);
    					index = 0;
    					$.each(pdTypes, function (pdType, values) {
    						var tr = tmp.children("tr:eq("+ index +")");
    						typeTd = $("<td />").html(pdType).appendTo(tr);
    						if(pdType == "合计") {
	    						tr.addClass("info");
	    						typeTd.addClass("alignRight");
    						}
    						$.each(values, function (point, timespans) {
    							$.each(timespans, function (timespan, counts) {
    								$.each(counts, function (count, value) {
    									$("<td />").addClass("alignRight").html(value).appendTo(tr);
    								})
    							})
    						})
    						index++;
    					})
						$("#tableDaily>tbody").append(tmp.children("tr"));
    				});

					totalTr = $("<tr />").addClass("thickBorder").addClass("warning");
					$("<td />").attr("colspan", "2").addClass("alignRight").html("长沙基地合计").appendTo(totalTr);
					$.each(response.data.countTotal, function (point, timespans) {
						$.each(timespans, function (timespan, counts) {
							$.each(counts, function (count, value) {
								$("<td />").addClass("alignRight").html(value).appendTo(totalTr);
							})
						})
					})
					$("#tableDaily>tbody").append(totalTr);

    				$("#tableDaily").show();
    			} else {
    				alert(response.message);
    			}
    		}
    	})
    }

    function querySmsText () {
        $("#tableSms>tbody").html("");
        $.ajax({
            url: service.QUERY_PLANNING_DIVISION_SMS_DAILY,
            dataType: "json",
            data: {
                "date": $("#startTime").val(),
            },
            error: function () {common.alertError();},
            success: function (response) {
                if(response.success) {
                    var text = {}
                    $.each(response.data, function (time, seriesNames) {
                        var 
                            num = 0,
                            assemblyTotal = 0, 
                            warehouseTotal = 0,
                            distributeTotal = 0,
                            distributeMonthTotal = 0,
                            warehouseMonthTotal = 0,
                            inventoryTotal = 0,
                            unDistributeTotal = 0,

                            seriesArr = [];
                            distributeMonthArr = [];
                            warehouseMonthArr = [];
                            inventoryArr = [];
                            unDistributeArr = [];
                        var tmp = $("<tbody />");

                        $.each(seriesNames, function (series, datas) {
                            textSeries = series;
                            tr = $("<tr />");
                            assembly = parseInt(datas['上线']) || 0;
                            warehouse = parseInt(datas['入库']) || 0;
                            distribute = parseInt(datas['出库']) || 0;
                            distributeMonth = parseInt(datas['已发']) || 0;
                            warehouseMonth = parseInt(datas['已入']) || 0;
                            inventory = parseInt(datas['库存']) || 0;
                            unDistribute = parseInt(datas['未发']) || 0;
                            $("<td />").html(series).appendTo(tr);
                            $("<td />").html(assembly).appendTo(tr);
                            $("<td />").html(warehouse).appendTo(tr);
                            $("<td />").html(distribute).appendTo(tr);
                            $("<td />").html(distributeMonth).appendTo(tr);
                            $("<td />").html(warehouseMonth).appendTo(tr);
                            $("<td />").html(inventory).appendTo(tr);
                            $("<td />").html(unDistribute).appendTo(tr);
                            tr.appendTo(tmp);
                            num++;
                            
                            assemblyTotal += assembly;
                            warehouseTotal += warehouse;
                            distributeTotal += distribute;
                            distributeMonthTotal += distributeMonth;
                            warehouseMonthTotal += distributeMonth;
                            inventoryTotal += inventory;
                            unDistributeTotal += unDistribute;

                            textSeries += " 上" + assembly + " 入" + warehouse + " 发" + distribute;
                            seriesArr.push(textSeries); 

                            // textDistributeMonth = series + ":" + distributeMonth;
                            // textWarehouseMonth = series + ":" + warehouseMonth;
                            // textInventory = series + ":" + inventory;
                            // textUndistribute = series + ":" + unDistribute;
                            distributeMonthArr.push(series + ":" + distributeMonth);
                            warehouseMonthArr.push(series + ":" + warehouseMonth);
                            inventoryArr.push(series + ":" + inventory);
                            unDistributeArr.push(series + ":" + unDistribute);
                        })
                        firstTr = tmp.children("tr:eq(0)");
                        $("<td />").html(time).attr("rowspan", num).prependTo(firstTr);
                        $("#tableSms tbody").append(tmp.children("tr"));

                        seriesText = seriesArr.join("\n");
                        seriesText += "\n合计 上" + assemblyTotal + " 入" + warehouseTotal + " 发" + distributeTotal;

                        distributeMonthText = "\n已发" + distributeMonthTotal + " " + distributeMonthArr.join(" ");
                        warehouseMonthText = "\n已入" + warehouseMonthTotal + " " + warehouseMonthArr.join(" ");
                        inventoryText = "\n库存" + inventoryTotal + " " + inventoryArr.join(" ");
                        unDistributeText = "\n未发" + unDistributeTotal + " " + unDistributeArr.join(" ");

                        textAll ="【长沙基地生产统计】" + $("#startTime").val() + " " + time + "\n"
                                +seriesText
                                + "\n" 
                                + distributeMonthText + warehouseMonthText + inventoryText + unDistributeText;
                        console.log(time);
                        console.log(textAll);
                        console.log($("#" + time));
                        $("#" + time).val(textAll);
                    })
                    $("#tableSms").show();
                } else {
                    alert(response.message);
                }
            }
        })
    }

	function initPage () {
		$("#headGeneralInformationLi").addClass("active");
		$("#startTime").val(dateTimeUtil.getTime('lastWorkDate'));
        queryReportDaily();
        querySmsText();
	}
});