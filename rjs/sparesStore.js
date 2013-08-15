require.config({
	"baseUrl": "/bms/rjs/lib",
	"paths":{
		"jquery": "./jquery-2.0.3.min",
		"bootstrap": "./bootstrap.min",
		"head": "../head",
		"left": "../left",
	},
	"shim": {
		"bootstrap": ["jquery"],
	}
})

require(["head","left","jquery","bootstrap"], function(head,left,$) {
	head.doInit();
	left.doInit();
	$("#btnSubmit").click(function() {
		$("#modaltest").modal("show");
	})

});
