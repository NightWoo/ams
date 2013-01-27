$(document).ready(function () {
	$("#configContainer").hide();
	var fileObjNameMap = ["front","back"];
	$(".config-item").live("click", function (event) {
		if ($(event.target).is("button")) {
			var index = $(".config-item").index($(event.target).parent("div"));
			$(".uploadify").eq(index).uploadify('settings','fileObjName', fileObjNameMap[index]);
			var sessionData = {};
			sessionData[$("#sessionName").val()] = $("#sessionId").val();
			sessionData['id'] = 1;
			$(".uploadify").eq(index).uploadify('settings','formData', sessionData);
			$(".uploadify").eq(index).uploadify('upload','*');
		}
	});

	$(".btnDelect").live("click",function () {
		var index = $(".config-item").index($(this).parent("div"));
		$(".uploadify").eq(index).uploadify("cancel");
	});

	$('.file_upload').uploadify({
			'swf'      : '/bms/js/uploadify/uploadify.swf',
			'uploader' : '/bms/config/upload',
			'buttonText' : '本地文件',
			'auto'     : false,
			'queueID' : 'queue1',
		    'width'    : 86,
		    'uploadLimit' : 1,
			'fileObjName' : 'frontImage',//backImage
		    'removeTimeout' : 0,
		    'formData' : "",
		    'onSelect' : function(file) {
	    		$('#' + this.settings.button_placeholder_id).siblings("input[type=text]").val(file.name);
	        }
			// Your options here
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

	function resetConfigItem () {
		$(".config-item button").removeClass().addClass("btn btn-primary");
		$(".config-item input[type=text]").removeAttr("disabled").html("");
		$(".config-item .btnDelect").hide();
		$(".config-item .notyet").show();
		// $('.uploadify').uploadify('disable', false);
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
							$(".config-item input[type=text]").eq(index).attr("disabled", "disabled").html(response.data.front);
							$(".config-item .btnDelect").eq(index).show();
							$(".config-item .notyet").eq(index).hide();
							// $('.uploadify').eq(index).uploadify('disable', true);
						}

						index = 1;//handle back
						if (response.data.back != "") {
							$(".config-item button").eq(index).addClass("disabled");
							$(".config-item input[type=text]").eq(index).attr("disabled", "disabled").html(response.data.back);
							$(".config-item .btnDelect").eq(index).show();
							$(".config-item .notyet").eq(index).hide();
							// $('.uploadify').eq(index).uploadify('disable', true);
						}
					}
					else{
					}
				},
				error:function(){alertError();}
			});
		}
	};
	
});
