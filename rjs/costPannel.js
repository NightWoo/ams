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
		"toolsManagement": "/bms/toolsManagement"
	}

	$(".thumbnail").click(function () {
		window.location.href= tumbnailUrl[$(this).attr("id")];
	})

	function initPage () {
		$("#headCostLi").addClass("active");
	}
});
