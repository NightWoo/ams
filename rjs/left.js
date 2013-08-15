define(function() {
	function attachHover() {
		var timeoutLeft;
		var timeoutLeaveLeft;
		var timeoutChevonRight;
		$("#divLeft")
		.hover( 
			function () {
				clearTimeout(timeoutLeft);
				$("#icon-left").removeClass("icon-caret-right");
				$("#toggle-left").css({"left":"160px"});
				$("#bodyleft").show();
			},
			function () {
				$("#toggle-left").animate({"left":"0"});
				$("#bodyleft").hide(300);
				timeoutChevonRight = setTimeout(function(){
					$("#icon-left").addClass("icon-caret-right");
					$("#icon-left").show();
				},500)					
			}
		)
		.mouseenter(function(){
			clearTimeout(timeoutLeaveLeft);
			clearTimeout(timeoutChevonRight);
		});
	}

	return {
		doInit: function() {
			attachHover();
		}
	}	
})