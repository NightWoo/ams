require.config({
	"paths":{
		"jquery": "lib/jquery-2.0.3.min",
		"bootstrap": "lib/bootstrap.min"
	},
	"shim": {
		"bootstrap": ["jquery"]
	}
});

require(["service","common","jquery","bootstrap"], function(service,common,$) {
	initPage();
	common.attachTooltip();

	tumbnailUrl = {
		"managementSystemPannel": "/bms/ManagementSystem/home?chapter=0",
		"technologyPannel": "",
		"manufacturePannel": "/bms/execution/index",
		"monitorPannel": "/bms/execution/monitoringIndex",
		"qualityPannel": "/bms/execution/query?type=NodeQuery",
		"efficiencyPannel": "/bms/site/pannelIndex?pannel=efficiencyPannel",
		"fieldPannel": "whatever",
		"costPannel": "/bms/site/pannelIndex?pannel=costPannel",
		"manpowerPannel": "/bms/humanResources",
		"databasePannel": "/bms/generalInformation"
	};

	$(".pannel,#monitorPannel").click(function () {
		window.location.href= tumbnailUrl[$(this).attr("id")];
	});

	$("#shortcutUl").on("click", ".shortcut", function (e) {
		if($(e.target).closest("li").attr("href")) {
			window.open($(e.target).closest("li").attr("href"),"_blank");
		}
	});

	$(window).resize(function () {
		$("#shortcutContainer").css("max-height", $(window).height() - 80 + "px");
	});

	function initPage() {
		$("#shortcutContainer").css("max-height", $(window).height() - 80 + "px");
		getEfficiency();
		getQuality();
		getCost();
	}

	function getEfficiency () {
		$.ajax({
			type: "get",//使用get方法访问后台
			dataType: "json",//返回json格式的数据
			url: service.SHOW_HOME_EFFICIENCY_INFO,//ref:  /bms/js/service.js
			data: {"section" : $("#section").val()},
			success:function (response) {
				if (response.success){
					$("#workingTimePercentage").html(response.data.lineURate);
					$("#onLine").html("上线：" + response.data.onLine);
					$("#checkin").html("入库：" + response.data.checkin);
					$("#checkout").html("发车：" + response.data.checkout);
				} else {
					alert(response.message);
				}
			},
			error:function(){common.alertError();}
		});
	}

	function getQuality () {
		$.ajax({
			url: service.SHOW_MONITOR_INFO,
			type: "get",
			dataType: "json",
			data:{},
			success: function (response) {
				$("#DRR").html(response.data.DRR.total);
				$("#vq1").html("VQ1：" + response.data.DPU.VQ1 + " / " + response.data.DRR.VQ1);
				$("#vq2").html("VQ2：" + response.data.DPU.VQ2 + " / " + response.data.DRR.VQ2);
				$("#vq3").html("VQ3：" + response.data.DPU.VQ3 + " / " + response.data.DRR.VQ3);
				$("#pauseTime").html(response.data.pause_time.total);
			}
		});
	}

	function getCost () {
		$.ajax({
			url: service.SHOW_HOME_COST_INFO,
			type: "get",
			dataType: "json",
			data:{},
			success: function (response) {
				if(response.success) {
					$("#uintCost").html(response.data['total']);
					$("#costF0").html("F0：" + response.data['F0']);
					$("#costM6").html("M6：" + response.data['M6']);
					$("#cost6B").html("思锐：" + response.data['6B']);
					$("#costG6").html("G6：" + response.data['G6']);
				} else {
					alert(response.message);
				}
			}
		});
	}
});
