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

//	$("#chevron").hide();
//	$(".icon-chevron-left").live("click", function (e) {
//		if ($(e.target).is("i")) {
//			$(e.target).parent().parent().hide();
//			$(".bydright").removeClass("offset2");
//			$("#chevron").show();
//		}
//	})

	//added by wujun	
//	$("#toggle-left").toggle(
//      function () {
//		$("#bodyleft").hide();
//		$("#toggle-left").css({"left":"0"});
//		$("#icon-left").removeClass("icon-chevron-left");
//		$("#icon-left").addClass("icon-chevron-right");
//		$("#bodyright").removeClass("offset2");
//		$("#bodyright").addClass("marginleft");
//      },
//      function () {
//		$("#bodyright").removeClass("marginleft");
//		$("#bodyright").addClass("offset2");
//		$("#icon-left").removeClass("icon-chevron-right");
//		$("#icon-left").addClass("icon-chevron-left");
//		$("#toggle-left").css({"left":"160px"});
//		$("#bodyleft").show();
//      }
//    );
	
$("#toggle-top").toggle(
	  function () {
			$("#bmsHead").slideUp();
			$("#toggle-top").animate({"top":"0"});
			$("#icon-top").removeClass("icon-chevron-up");
			$("#icon-top").addClass("icon-chevron-down");
			$(".offhead").animate({"margin-top":"20px"});
	  },
	  function () {
			$(".offhead").animate({"margin-top":"60px"});
			$("#icon-top").removeClass("icon-chevron-down");
			$("#icon-top").addClass("icon-chevron-up");
			$("#toggle-top").animate({"top":"43px"});
			$("#bmsHead").slideDown();
	  }
	);
	
	//var timeoutHead;
	//timeoutHead = setTimeout(function(){
		//$("#bmsHead").slideUp();
		////$("#bmsHead").hide();
		//$("#toggle-top").css({"top":"0"});
		//$("#icon-top").removeClass("icon-chevron-up");
		//$("#icon-top").addClass("icon-chevron-down");
		////$(".offhead").css({"margin-top":"20px"});
		//$(".offhead").animate({"margin-top":"20px"});
	//},3000)
	//var timeoutLeaveHead;
	//$("#divHead")
	//.hover(
		//function () {
			//clearTimeout(timeoutHead);
			//timeoutEnterHead = setTimeout(function(){
				////$(".offhead").css({"margin-top":"60px"});
				//$(".offhead").animate({"margin-top":"60px"});
				//$("#icon-top").removeClass("icon-chevron-down");
				//$("#icon-top").addClass("icon-chevron-up");
				////$("#toggle-top").css({"top":"43px"});
				//$("#bmsHead").slideDown();
			//},50)			
		//},
		//function () {
			//timeoutLeaveHead = setTimeout(function(){
				//$("#bmsHead").slideUp();
				//$("#toggle-top").css({"top":"0"});
				//$("#icon-top").removeClass("icon-chevron-up");
				//$("#icon-top").addClass("icon-chevron-down");
				////$(".offhead").css({"margin-top":"20px"});
				//$(".offhead").animate({"margin-top":"20px"});				
			//},1000);
		//}
	//)
	//.mouseenter(function(){
		//clearTimeout(timeoutLeaveHead);
	//})
	//.mouseout(function(){
		//clearTimeout(timeoutEnterHead);
	//});
	var timeoutLeft;
	var timeoutLeaveLeft;
	var timeoutChevonRight;
	//timeoutLeft = setTimeout(function(){
		$("#bodyleft").hide();
		$("#toggle-left").css({"left":"0"});
		//$("#toggle-left").animate({"left":"0"});
		$("#icon-left").removeClass("icon-chevron-left");
		$("#icon-left").addClass("icon-chevron-right");
		//$("#bodyright").removeClass("offset2");
		//$("#bodyright").addClass("marginleft")
		//$("#bodyright").animate({"margin-left":"20px"});
	//},200)
	
	$("#divLeft")
	.hover( 
		function () {
			clearTimeout(timeoutLeft);
			//$("#bodyright").removeClass("marginleft");
			//$("#bodyright").addClass("offset2");
			//$("#bodyright").css({"margin-left":"180px"});
			$("#icon-left").removeClass("icon-chevron-right");
			//$("#icon-left").addClass("icon-chevron-left");
			$("#toggle-left").css({"left":"160px"});
			$("#bodyleft").show();
		  },
		function () {
			//timeoutLeaveLeft = setTimeout(function(){
				//$("#toggle-left").css({"left":"0"});
				$("#toggle-left").animate({"left":"0"});
				$("#icon-left").removeClass("icon-chevron-left");
				
				//$("#bodyright").removeClass("offset2");
				//$("#bodyright").addClass("marginleft");	
				//$("#bodyright").animate({"margin-left":"20px"});
				$("#bodyleft").hide(300);
				timeoutChevonRight = setTimeout(function(){
					$("#icon-left").addClass("icon-chevron-right");
					$("#icon-left").hide();
					$("#icon-left").show(200);
				},500)			
			//},1000)			
		}
    )
	.mouseenter(function(){
		clearTimeout(timeoutLeaveLeft);
		clearTimeout(timeoutChevonRight);
	});
	
});