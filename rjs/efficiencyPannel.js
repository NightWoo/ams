require.config({
	"baseUrl": "/bms/rjs/lib",
	"paths":{
		"jquery": "./jquery-2.0.3.min",
		"bootstrap": "./bootstrap.min",
		"head": "../head",
		"service": "../service",
		"common": "../common"
	},
	"shim": {
		"bootstrap": ["jquery"]
	}
})

require(["head","service","common","jquery","bootstrap"], function(head,service,common,$) {
	head.doInit();
	initPage();
	tumbnailUrl = {
		"planMaintain": "/bms/execution/planMaintain",
		"orderMaintain": "/bms/execution/orderMaintain",
		"planPause": "/bms/execution/planPause",
		"pauseEdit": "/bms/execution/pauseEdit",
		"configMaintain": "/bms/execution/configMaintain",
		"subQueueMaintain": "/bms/execution/subQueueMaintain",
		"spsQueueMaintain": "/bms/execution/spsQueueMaintain",
		"dataThrow": "/bms/execution/dataThrow",
		"warehouseAdjust": "/bms/execution/warehouseAdjust"
	}

	$(".thumbnail").click(function () {
		window.location.href= tumbnailUrl[$(this).attr("id")];
	})

	function initPage () {
		$("#headEfficiencyLi").addClass("active");
	}
});
