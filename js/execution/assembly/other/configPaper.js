$(document).ready(function () {
	initPage();
	$("#configContainer").hide();

	$("#frontForm").submit(function () {
		$("#frontForm").ajaxSubmit({
			type: "post",
			url : "/bms/config/upload",
			data : {id: $('#config').val()},
			dataType : "json",
			success : function (response) {
				if (response.success) {
					alert("success");
					var index =0;
					$(".config-item .btnDelect").eq(index).show();
					$(".config-item .viewImage").eq(index).data("path", response.data.image.front).show();
					$(".config-item .notyet").eq(index).hide();
				} else {
					alert(response.message);
				}
				
			}
		});
		return false;
	});
	
	$("#backForm").submit(function () {
		$("#backForm").ajaxSubmit({
			type: "post",
			url : "/bms/config/upload",
			data : {id: $('#config').val()},
			dataType : "json",
			success : function (response) {
				if (response.success) {
					alert("success");
					var index = 1;
					$(".config-item .btnDelect").eq(index).show();
					$(".config-item .viewImage").eq(index).data("path", response.data.image.back).show();
					$(".config-item .notyet").eq(index).hide();
				} else {
					alert(response.message);
				}
			}
		});
		return false;
	});

	$("#front2Form").submit(function () {
		$("#front2Form").ajaxSubmit({
			type: "post",
			url : "/bms/config/upload",
			data : {id: $('#config').val()},
			dataType : "json",
			success : function (response) {
				if (response.success) {
					alert("success");
					var index =2;
					$(".config-item .btnDelect").eq(index).show();
					$(".config-item .viewImage").eq(index).data("path", response.data.image.front2).show();
					$(".config-item .notyet").eq(index).hide();
				} else {
					alert(response.message);
				}
				
			}
		});
		return false;
	});
	$("#back2Form").submit(function () {
		$("#back2Form").ajaxSubmit({
			type: "post",
			url : "/bms/config/upload",
			data : {id: $('#config').val()},
			dataType : "json",
			success : function (response) {
				if (response.success) {
					alert("success");
					var index = 3;
					$(".config-item .btnDelect").eq(index).show();
					$(".config-item .viewImage").eq(index).data("path", response.data.image.back2).show();
					$(".config-item .notyet").eq(index).hide();
				} else {
					alert(response.message);
				}
			}
		});
		return false;
	});

	$("#subInstrumentForm").submit(function () {
		$("#subInstrumentForm").ajaxSubmit({
			type: "post",
			url : "/bms/config/upload",
			data : {id: $('#config').val()},
			dataType : "json",
			success : function (response) {
				if (response.success) {
					alert("success");
					var index = 4;
					$(".config-item .btnDelect").eq(index).show();
					$(".config-item .viewImage").eq(index).data("path", response.data.image.subInstrument).show();
					$(".config-item .notyet").eq(index).hide();
				} else {
					alert(response.message);
				}
			}
		});
		return false;
	});

	$("#subEngineForm").submit(function () {
		$("#subEngineForm").ajaxSubmit({
			type: "post",
			url : "/bms/config/upload",
			data : {id: $('#config').val()},
			dataType : "json",
			success : function (response) {
				if (response.success) {
					alert("success");
					var index = 5;
					$(".config-item .btnDelect").eq(index).show();
					$(".config-item .viewImage").eq(index).data("path", response.data.image.subEngine).show();
					$(".config-item .notyet").eq(index).hide();
				} else {
					alert(response.message);
				}
			}
		});
		return false;
	});

var fileObjNameMap = ["front", "back", "front2", "back2", "subInstrument", "subEngine"];
$(".btnDelect").live("click",function () {
		var index = $(".config-item").index($(this).parent().parent("div"));
		ajaxSender.ajaxDeleteConfig($("#config").val(), fileObjNameMap[index], index);
	});

	$("#series").change(function () {
		ajaxSender.ajaxGetCarType($(this).val());
	});

	$("#carType").change(function () {
		ajaxSender.ajaxGetConfigList($("#series").val(), $(this).val());
	});

	$("#config").change(function () {
		resetConfigItem();
		ajaxSender.ajaxGetConfigDetail($(this).val());
		$("#configContainer").show();
	});

	$("#queryRefresh").click(function() {
		resetConfigItem();
		ajaxSender.ajaxGetConfigDetail($("#config").val());
		$("#configContainer").show();
	})

	$(".viewImage").live("click", function () {
		window.open($(this).data("path"));
	})

	function initPage() {
		$("#headPlanLi").addClass("active");
		$("#leftConfigMaintainLi").addClass("active");
		getSeries();
	}

	function resetConfigItem () {
		$(".config-item button").removeClass().addClass("btn btn-primary");
		$(".config-item input[type=text]").removeAttr("disabled").val("");
		$(".config-item .btnDelect").hide();
		$(".config-item .viewImage").hide();
		$(".config-item .notyet").show();
		// $('.uploadify').uploadify('disable', false);
	}

	function getSeries () {
		$.ajax({
			url: GET_SERIES_LIST,
			dataType: "json",
			data: {},
			async: false,
			error: function () {common.alertError();},
			success: function (response) {
				if(response.success){
					options = $.templates("#tmplSeriesSelect").render(response.data);
					$(".carSeries").append(options);
				} else {
					alert(response.message);
				}
			}
		})
	}

	var ajaxSender = {
		ajaxGetCarType : function (series) {
			$.ajax({
				type: "get",//使用get方法访问后台
	        	dataType: "json",//返回json格式的数据
				url: SEARCH_CONFIG,//ref:  /bms/js/service.js
				data:  {"car_series": series, "column" : "car_type"},
				success: function(response){
					if(response.success){
						$("#carType").text("");
						$("#config").text("");
						$("<option />").attr("value", "").html("").appendTo($("#carType"));
					  	$(response.data).each(function () {
					  		$("<option />").attr("value", this.id).html(this.name).appendTo($("#carType"));
					  	});
					}
					else{
					}
				},
				error:function(){alertError();}
			});
		},
		ajaxGetConfigList : function (series, type) {
			$.ajax({
				type: "get",//使用get方法访问后台
	        	dataType: "json",//返回json格式的数据
				url: SEARCH_CONFIG,//ref:  /bms/js/service.js
				data:  {"car_series": series, "car_type": type, "column": "name"},
				success: function(response){
					if(response.success){
						$("#config").text("");
						$("<option />").attr("value", "").html("").appendTo($("#config"));
					  	$(response.data).each(function () {
					  		$("<option />").attr("value", this.id).html(this.name).appendTo($("#config"));
					  	});
					}
					else{
					}
				},
				error:function(){alertError();}
			});
		},
		ajaxGetConfigDetail : function (id) {
			$.ajax({
				type: "get",//使用get方法访问后台
	        	dataType: "json",//返回json格式的数据
				url: CONFIG_SHOW_IMAGE,//ref:  /bms/js/service.js
				data:  {"id": id},
				success: function(response){
					if(response.success){
						var index = 0;//handle front
						if (response.data.front != "") {
							$(".config-item button").eq(index).addClass("disabled");
							$(".config-item input[type=text]").eq(index).attr("disabled", "disabled").val(response.data.front);
							$(".config-item .btnDelect").eq(index).show();
							$(".config-item .viewImage").eq(index).data("path", response.data.image.front).show();
							$(".config-item .notyet").eq(index).hide();
							// $('.uploadify').eq(index).uploadify('disable', true);
						}

						index = 1;//handle back
						if (response.data.back != "") {
							$(".config-item button").eq(index).addClass("disabled");
							$(".config-item input[type=text]").eq(index).attr("disabled", "disabled").val(response.data.back);
							$(".config-item .btnDelect").eq(index).show();
							$(".config-item .viewImage").eq(index).data("path", response.data.image.back).show();
							$(".config-item .notyet").eq(index).hide();
							// $('.uploadify').eq(index).uploadify('disable', true);
						}

						index = 2;//handle front
						if (response.data.front != "") {
							$(".config-item button").eq(index).addClass("disabled");
							$(".config-item input[type=text]").eq(index).attr("disabled", "disabled").val(response.data.front2);
							$(".config-item .btnDelect").eq(index).show();
							$(".config-item .viewImage").eq(index).data("path", response.data.image.front2).show();
							$(".config-item .notyet").eq(index).hide();
							// $('.uploadify').eq(index).uploadify('disable', true);
						}

						index = 3;//handle back
						if (response.data.back != "") {
							$(".config-item button").eq(index).addClass("disabled");
							$(".config-item input[type=text]").eq(index).attr("disabled", "disabled").val(response.data.back2);
							$(".config-item .btnDelect").eq(index).show();
							$(".config-item .viewImage").eq(index).data("path", response.data.image.back2).show();
							$(".config-item .notyet").eq(index).hide();
							// $('.uploadify').eq(index).uploadify('disable', true);
						}

						index = 4;//handle subInstrument
						if (response.data.subInstrument != "") {
							$(".config-item button").eq(index).addClass("disabled");
							$(".config-item input[type=text]").eq(index).attr("disabled", "disabled").val(response.data.subInstrument);
							$(".config-item .btnDelect").eq(index).show();
							$(".config-item .viewImage").eq(index).data("path", response.data.image.subInstrument).show();
							$(".config-item .notyet").eq(index).hide();
							// $('.uploadify').eq(index).uploadify('disable', true);
						}

						index = 5;//handle subEngine
						if (response.data.subEngine != "") {
							$(".config-item button").eq(index).addClass("disabled");
							$(".config-item input[type=text]").eq(index).attr("disabled", "disabled").val(response.data.subEngine);
							$(".config-item .btnDelect").eq(index).show();
							$(".config-item .viewImage").eq(index).data("path", response.data.image.subEngine).show();
							$(".config-item .notyet").eq(index).hide();
							// $('.uploadify').eq(index).uploadify('disable', true);
						}
					}else{
						alert(response.message);
					}
				},
				error:function(){alertError();}
			});
		},
		ajaxDeleteConfig : function (id, type, index) {
			$.ajax({
				type: "get",//使用get方法访问后台
	        	dataType: "json",//返回json格式的数据
				url: CONFIG_DELETE_IMAGE,//ref:  /bms/js/service.js
				data:  {"id": id, "type" : type},
				success: function(response){
					if(response.success){
						$(".config-item button").eq(index).removeClass().addClass("btn btn-primary");
						$(".config-item input[type=text]").eq(index).removeAttr("disabled").val("");
						$(".config-item .btnDelect").eq(index).hide();
						$(".config-item .viewImage").eq(index).hide();
						$(".config-item .notyet").eq(index).show();
					}
					else{

					}
				},
				error:function(){alertError();}
			});
		}
		
	};
	
});