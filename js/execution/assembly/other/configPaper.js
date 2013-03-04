$(document).ready(function () {

	// $("#btnUpload").click(function () {
	// 	$("#demoForm").submit({"id": $("#config").val()});
	// });

	// variable to hold request
var request;
// bind to the submit event of our form
$("#demoForm").submit(function(event){
    // abort any pending request
    if (request) {
        request.abort();
    }
    // setup some local variables
    var $form = $(this);
    // let's select and cache all the fields
    var $inputs = $form.find("input, select, button, textarea");
    // serialize the data in the form
    var serializedData = $form.serialize();
    console.log( $("#config").val())
    serializedData.id = $("#config").val();

    // let's disable the inputs for the duration of the ajax request
    $inputs.prop("disabled", true);

    // fire off the request to /form.php
    var request = $.ajax({
        url: "/bms/config/upload",
        type: "post",
        data: serializedData
    });

    // callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        // log a message to the console
        console.log("Hooray, it worked!");
    });

    // callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown){
        // log the error to the console
        console.error(
            "The following error occured: "+
            textStatus, errorThrown
        );
    });

    // callback handler that will be called regardless
    // if the request failed or succeeded
    request.always(function () {
        // reenable the inputs
        $inputs.prop("disabled", false);
    });

    // prevent default posting of form
    event.preventDefault();
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
		$(".config-item input[type=text]").removeAttr("disabled").val("");
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
							$(".config-item input[type=text]").eq(index).attr("disabled", "disabled").val(response.data.front);
							$(".config-item .btnDelect").eq(index).show();
							$(".config-item .notyet").eq(index).hide();
							// $('.uploadify').eq(index).uploadify('disable', true);
						}

						index = 1;//handle back
						if (response.data.back != "") {
							$(".config-item button").eq(index).addClass("disabled");
							$(".config-item input[type=text]").eq(index).attr("disabled", "disabled").val(response.data.back);
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