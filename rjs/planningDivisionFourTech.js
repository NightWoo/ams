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

	$("#startTime, #endTime").datetimepicker({
	    format: 'yyyy-mm-dd',
	    autoclose: true,
		todayBtn: true,
		pickerPosition: "bottom-left",
		language: "zh-CN",
		minView: "2"
    });


    $("#btnQuery").click(function () {

    })


	function initPage () {
		$("#headGeneralInformationLi").addClass("active");
        $("#startTime").val(dateTimeUtil.getTime('firstDayOfTheMonth').substr(0,10));
		$("#endTime").val(dateTimeUtil.getTime('lastWorkDate'));
	}
});