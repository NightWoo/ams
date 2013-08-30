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
		"dateTimeUtil": "../dateTimeUtil",
	},
	"shim": {
		"bootstrap": ["jquery"],
		"bootstrap-datetimepicker": ["jquery"],
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
		minView: "2",
    });


    $("#btnQuery").click(function () {
    	queryReportDaily();
    })

    function queryReportDaily () {
    	$("tableDaily>tbody").html("");
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

	function initPage () {
		$("#headGeneralInformationLi").addClass("active");
		$("#startTime").val(dateTimeUtil.getTime('lastWorkDate'));
	}
});