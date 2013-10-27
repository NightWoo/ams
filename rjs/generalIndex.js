require.config({
	// "baseUrl": "/bms/rjs/lib",
	"paths":{
		"jquery": "lib/jquery-2.0.3.min",
		"bootstrap": "lib/bootstrap.min"
	},
	"shim": {
		"bootstrap": ["jquery"]
	}
});

require(["dateTimeUtil","head","service","common","jquery","bootstrap"], function(dateTimeUtil,head,service,common,$) {
	head.doInit();
	initPage();
	tumbnailUrl = {
		"manufactureReport": "/bms/execution/report?type=ManufactureReport",
		"qualityReport": "/bms/execution/report?type=QualityReport",
		"planningDivisionReport": "/bms/execution/report?type=PlanningDivisionReport",
		"componentMaintain": "/bms/generalInformation/componentMaintain",
		"faultMaintain": "/bms/generalInformation/faultMaintain",
		"providerMaintain": "/bms/generalInformation/providerMaintain",
		"distributorMaintain": "/bms/generalInformation/distributorMaintain",
		"configMaintain": "/bms/execution/configMaintain"
	}

	$(".thumbnail").click(function () {
		if($(this).attr("id")){
			window.location.href= tumbnailUrl[$(this).attr("id")];
		}
	})

	function initPage () {
		$("#headGeneralInformationLi").addClass("active");
		querySimpleDaily();
		$("#lastWorkDate").html(dateTimeUtil.getTime("lastWorkDate"));
	}

	function querySimpleDaily () {
		$.ajax({
			url: service.QUERY_SIMPLE_DAILY,
			dataType: "json",
			data:{},
			error: function () {common.alertError();},
			success: function (response) {
				if(response.success) {
					$.each(response.data.manufactureDaily, function (key, value) {
						$("#" + key).html(value);
					})

					$.each(response.data.qualificationDaily, function (key, value) {
						$("." + key).filter(".total").html((value.total*100).toFixed(0) + "%");
						$.each(value.sub, function (series, subValue) {
							$("." + key).filter(".sub").filter("." + series).html((subValue*100).toFixed(0) + "%");
						})
					})
				}
			}
		})
	}
});
