require.config({
	"baseUrl": "/bms/rjs/lib",
	"paths":{
		"jquery": "./jquery-2.0.3.min",
		"bootstrap": "./bootstrap.min",
		"head": "../head",
		"service": "../service",
		"common": "../common",
	},
	"shim": {
		"bootstrap": ["jquery"],
	}
})

require(["head","service","common","jquery","bootstrap"], function(head,service,common,$) {
	head.doInit();
	initPage();
	tumbnailUrl = {
		"manufactureReport": "/bms/execution/report?type=ManufactureReport",
		"qualityReport": "/bms/execution/report?type=QualityReport",
		"componentMaintain": "/bms/generalInformation/componentMaintain",
		"faultMaintain": "/bms/generalInformation/faultMaintain",
		"providerMaintain": "/bms/generalInformation/providerMaintain",
		"distributorMaintain": "/bms/generalInformation/distributorMaintain",
	}

	$(".thumbnail").click(function () {
		if($(this).attr("id")){
			window.location.href= tumbnailUrl[$(this).attr("id")];
		}
	})

	function initPage () {
		$("#headGeneralInformationLi").addClass("active");
		querySimpleDaily();
	}

	function querySimpleDaily () {
		$.ajax({
			url: service.QUERY_SIMPLE_Daily,
			dataType: "json",
			data:{},
			error: function () {common.alertError();},
			success: function (response) {
				if(response.success) {
					$.each(response.data.manufactureDaily, function (key, value) {
						$("#" + key).html(value);
					})

					$.each(response.data.qualificationDaily, function (key, value) {
						$("." + key).filter(".total").html(value.total*100 + "%");
						$.each(value.sub, function (series, subValue) {
							$("." + key).filter(".sub").filter("." + series).html(subValue*100 + "%");
						})
					})
				}
			}
		})
	}
});
