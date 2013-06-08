$(function () {
	initPage();
		
	$("#logout").click(function(){
		window.location.href='/bms/site/logout'
	})
	
	$("#quality").click(function(){
		window.location.href='/bms/execution/query?type=NodeQuery'
	})
	
	$("#efficiency").click(function(){
		window.location.href='/bms/execution/monitoringIndex'
	})
	
	$("#dataInput").click(function(){
		window.location.href='/bms/execution/index'
	})
	
	$("#query").click(function(){
		window.location.href='/bms/execution/query?type=CarQuery'
	})

	$("#warehouseMaintain").click(function(){
		window.location.href='/bms/execution/warehouseReturn'
	})
	
	$("#maintain").click(function(){
		window.location.href='/bms/execution/ConfigPlan'
	})
	
	$("#basicDatabase").click(function(){
		window.location.href='/bms/generalInformation/faultMaintain'
	})

	$("#orderMaintain").click(function(){
		window.location.href='/bms/generalInformation/orderMaintain'
	})
	
	
	$("#cost").click(function(){
		window.location.href='/bms/managementSystem/workSummaryCost'
	})
	
	
	$("#manpower").click(function(){
		window.location.href='/bms/managementSystem/workSummaryManpower'
	})
	
	$("#managementSystem").click(function(){
		window.location.href='/bms/ManagementSystem/home?chapter=0'
	})
	
	$("#safety").click(function(){
		window.location.href='#'
	})

	$("#summary").click(function(){
		// window.location.href='/bms/managementSystem/workSummaryAPD'
	})

	function initPage() {
		refresh();	
	}

	//setInterval(function () {
		//refresh();
	//},5000);

	function refresh() {
		getEfficiency();
		getQualty();
	}
	
	function getEfficiency () {
		$.ajax({
			type: "get",//使用get方法访问后台
			dataType: "json",//返回json格式的数据
			url: SHOW_HOME_INFO,//ref:  /bms/js/service.js
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
			error:function(){alertError();}
		});
	}

	function getQualty () {
		$.ajax({
			url: SHOW_MONITOR_INFO,
			type: "get",
			dataType: "json",
			data:{},
			success: function (response) {
				//$("#DRR").html(response.data.DPU.total + " / " + response.data.DRR.total)
				$("#DRR").html(response.data.DRR.total)
				$("#vq1").html("VQ1：" + response.data.DPU.VQ1 + " / " + response.data.DRR.VQ1);
				$("#vq2").html("VQ2：" + response.data.DPU.VQ2 + " / " + response.data.DRR.VQ2);
				$("#vq3").html("VQ3：" + response.data.DPU.VQ3 + " / " + response.data.DRR.VQ3);
			}
		})
	}	
	
});