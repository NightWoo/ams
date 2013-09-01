require.config({
	"baseUrl": "/bms/rjs/lib",
	"paths" : {
		"jquery": "jquery-2.0.3.min",
		"bootstrap": "bootstrap.min",
		"jsrender": "jsrender.min",
		"jquery-ui": "jquery-ui-1.10.3.custom.min",
		"jquery-ui-timepicker-addon": "jquery-ui-timepicker-addon",
		"head": "../head",
		"service": "../service",
		"common": "../common",
		"dateTimeUtil": "../dateTimeUtil"
	},
	"shim": {
		"bootstrap": ["jquery"],
		"jsrender": ["jquery"],
		"jquery-ui": ["jquery"],
		"jquery-ui-timepicker-addon": ["jquery-ui"]
	}
})

require(["head","service","common","dateTimeUtil","jquery","bootstrap","jsrender","jquery-ui","jquery-ui-timepicker-addon"], function (head, service, common, dateTimeUtil, $) {
	initPage();

	$('#startTime, #endTime').datetimepicker({
		timeFormat: "HH:mm",
		changeMonth: true,
	    changeYear: true,
	    showOtherMonths: true,
	    selectOtherMonths: true,
	    duration: "fast",
	    buttonImageOnly: true,
	});

	$("#tabs li").click(function () {
		var index = $("#tabs li").index(this);
		if(index<1) {
			$(".pagination").hide();
		}

		switch (index) {
			case 0 :
				ajaxQueryReplacementDetail(1);
				break;
			default: 
				break;
		}
	});

	$("#exportDetail").click(function () {
		ajaxExportReplacementDetail();
	})


	function initPage () {
		$("#headCostLi").addClass("active");
		$("#startTime").val(dateTimeUtil.getTime("firstDayOfTheMonth"));
		$("#endTime").val(dateTimeUtil.getTime("currentTime"));

		options = common.getDutyOptions("SparesStore", true);
		$("#dutyDepartment").append(options);
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
})