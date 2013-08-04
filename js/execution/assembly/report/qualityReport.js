$(document).ready(function () {
	initPage();

//event bindings -----------------------------

	//jquery-ui-datetimepicker
    $("#startTime").datetimepicker({
	    format: 'yyyy-mm-dd',
	    autoclose: true,
		todayBtn: true,
		pickerPosition: "bottom-left",
		language: "zh-CN",
		minView: "2",
    });


    $(".print").click(function() {
    	window.print();
    })
//END event bindings -------------------------


//common function -------------------------

	function initPage(){
		//add head class
		$("#headAssemblyLi").addClass("active");
		$("#leftQualityReportLi").addClass("active");

		$("#divLeft,#divHead").addClass("notPrintable");


		$("#startTime").val(window.byd.DateUtil.lastWorkDate());
		ajaxQueryManufactureDaily();
		$("#headText").html("计划完成情况");
		ajaxQueryCompletion("monthly");
    	ajaxQueryCompletion("yearly");
		resetAll();
	}

	function resetAll (argument) {
		$(".initHide").hide();
	}

//END commonfunction --------------------


//ajax query -------------------------------------------

	
//END ajax query ---------------------------------------

});

!$(function() {
	window.report = window.report || {};
	window.report.qualification = {
		point : "",
		ajaxData: {},
		chartData: {

		},

		drawColumnLine: function(timespan) {
			point = this.point;
			columnSeries = [];
			carSeries = this.ajaxData.carSeries;
			columnSeriesData = this.ajaxData.series.column;
		},

		toggleClickPointData: function(timespan, x, y, index) {

		},

		prepare: function(dataArray) {
			return $(dataArray).map(function (index, item) {
				return {x:index, y: item, show: false}
			})
		},
	}
})