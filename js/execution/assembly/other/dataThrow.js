$(document).ready(function() {
	initPage();
	

	$(".clearinput").click(function() {
		$(this).siblings().filter("input").val("");
		$(this).siblings().filter(".vinText").removeAttr("disabled");
		$(this).parent().siblings().filter(".btn").attr("disabled", "disabled");
	})

	$(".vinText").bind('keydown', function(event) {
		//if vinText disable,stop propogation
		if($(this).attr("disabled") == "disabled")
			return false;
		if (event.keyCode == "13"){
			//remove blanks 
		    if(jQuery.trim($(this).val()) != ""){
		        validateVin($(this));
	        }   
		    return false;
		}
	});

	$("#btnFinish").click(function() {
		vinText = $(this).siblings().children().filter(".vinText");
		url = ASSEMBLY_FINISH_DATA_THROW;
		ajaxSubmit(vinText, url);
	})

	$("#btnIn").click(function() {
		vinText = $(this).siblings().children().filter(".vinText");
		url = WAREHOUSE_IN_DATA_THROW;
		ajaxSubmit(vinText, url);
	})

	$("#btnOut").click(function() {
		vinText = $(this).siblings().children().filter(".vinText");
		url = WAREHOUSE_OUT_DATA_THROW;
		ajaxSubmit(vinText, url);
	})

	$("#btnCertificate").click(function() {
		vinText = $(this).siblings().children().filter(".vinText");
		url = CERTIFICATE_THROW_ONE;
		ajaxSubmit(vinText, url);
	})

	$("#btnMark").click(function() {
		vinText = $(this).siblings().children().filter(".vinText");
		url = MARK_PRINT_THROW;
		ajaxSubmit(vinText, url);
	})

	function initPage() {
		$("#headPlanLi").addClass("active");
		$("#leftDataThrowLi").addClass("active");
		$("#messageAlert").hide();
		resetPage();
	}

	function resetPage() {
		$(".vinText").attr("disabled", "disabled");
	}

	function validateVin(vinText) {
		$.ajax({
			url: VALIDATE_DATA_THROW,
			type: "get",
			dataType: "json",
			data:{
				"vin" : vinText.val(),
			},
			success: function(response){
				if(response.success){
					vinText.val(response.data.vin).attr("disabled", "disabled");
					vinText.parent().siblings().filter(".btn").removeAttr("disabled");
				} else {
					vinText.val("").removeAttr("disabled");
					vinText.parent().siblings().filter(".btn").attr("disabled", "disabled");
					fadeMessageAlert(response.message,"alert-error");
				}
			},
			error: function(){alertError();}
		})
	}

	function ajaxSubmit(vinText, url) {
		$.ajax({
			url: url,
			type: "get",
			dataType: "json",
			data:{
				"vin" : vinText.val(),
			},
			success: function(response){
				if(response.success){
					vinText.val("").removeAttr("disabled");
					vinText.parent().siblings().filter(".btn").attr("disabled", "disabled");
					fadeMessageAlert(response.message,"alert-success");
				} else {
					vinText.val("").removeAttr("disabled");
					vinText.parent().siblings().filter(".btn").attr("disabled", "disabled");
					fadeMessageAlert(response.message,"alert-error");
				}
			},
			error: function(){alertError();}
		})
	}


	function fadeMessageAlert(message,alertClass){
		$("#messageAlert").removeClass("alert-error alert-success").addClass(alertClass);
		$("#messageAlert").html(message);
		$("#messageAlert").show(500,function () {
			setTimeout(function() {
				$("#messageAlert").hide(1000);
			},5000);
		});
	}

});
