$(document).ready(function(){
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
			$("#icon-top").removeClass("fa fa-caret-up");
			$("#icon-top").addClass("fa fa-caret-down");
			$(".offhead").animate({"margin-top":"20px"});
	  },
	  function () {
			$(".offhead").animate({"margin-top":"55px"});
			$("#icon-top").removeClass("fa fa-caret-down");
			$("#icon-top").addClass("fa fa-caret-up");
			$("#toggle-top").animate({"top":"41px"});
			$("#bmsHead").slideDown();
	  }
	);

	$("#toggle-top").hover( 
		function () {
			$("#icon-top").show();
		},
		function () {
			$("#icon-top").hide();
		}
	)
	
	var timeoutLeft;
	var timeoutLeaveLeft;
	var timeoutChevonRight;

	$("#bodyleft").hide();
	$("#toggle-left").css({"left":"0"});
	$("#icon-left").addClass("fa fa-caret-right");
	
	$("#divLeft")
	.hover( 
		function () {
			clearTimeout(timeoutLeft);

			$("#icon-left").removeClass("fa fa-caret-right");
			$("#toggle-left").css({"left":"160px"});
			$("#bodyleft").show();
		  },
		function () {

				$("#toggle-left").animate({"left":"0"});
				$("#bodyleft").hide(300);
				timeoutChevonRight = setTimeout(function(){
					$("#icon-left").addClass("fa fa-caret-right");
					$("#icon-left").show();
				},500)					
		}
    )
	.mouseenter(function(){
		clearTimeout(timeoutLeaveLeft);
		clearTimeout(timeoutChevonRight);
	});

	$('body').tooltip(
		{
         selector: "*[rel=tooltip]",
    	}
    );

});