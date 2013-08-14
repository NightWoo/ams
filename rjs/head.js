define(function() {
	return {
		headDropDown: function(){
			var timeoutDrop;
			$("#bmsHead .dropdown")
			.hover(
				function(){
					var ulNode=$(this).children("ul");
					timeoutDrop= setTimeout(function(){
						ulNode.show();
					},100);
				},function(){
					$(this).children("ul").hide();
				})
			.mouseout(function(){
				clearTimeout(timeoutDrop);
			});

			$("#toggle-top").toggle(
				function () {
					$("#bmsHead").slideUp();
					$("#toggle-top").animate({"top":"0"});
					$("#icon-top").removeClass("icon-chevron-up");
					$("#icon-top").addClass("icon-chevron-down");
					$(".offhead").animate({"margin-top":"20px"});
				},
				function () {
					$(".offhead").animate({"margin-top":"55px"});
					$("#icon-top").removeClass("icon-chevron-down");
					$("#icon-top").addClass("icon-chevron-up");
					$("#toggle-top").animate({"top":"41px"});
					$("#bmsHead").slideDown();
				}
			);

			var timeoutLeft;
			var timeoutLeaveLeft;
			var timeoutChevonRight;

			$("#bodyleft").hide();
			$("#toggle-left").css({"left":"0"});
			$("#icon-left").addClass("icon-caret-right");

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

	}
})