$(document).ready(function() {
	initPage();

	function initPage() {
		$("#headPlanLi").addClass("active");
		$("#leftDataThrowLi").addClass("active");
		$("#messageAlert").hide();
		resetPage();
	}

	function resetPage() {
		$("#printAll").attr("disabled", "disabled");
		toggleVinHint(true);
	}

	
	function toggleVinHint (showVinHint) {
		if(showVinHint){
			$("#orderInfo").hide();
			$("#orderHint").fadeIn(1000);

		}else{
			$("#orderHint").hide();
			$("#orderInfo").fadeIn(1000);
		}
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

	$(".clearinput").click(function() {
		$(this).siblings().filter("input").val("");
		$(this).siblings().filter(".vinText").removeAttr("disabled");
		$(this).parent().siblings().filter(".btn").attr("disabled", "disabled");
	})

	$('body').tooltip(
        {
         selector: "a[rel=tooltip]"
	});

});
