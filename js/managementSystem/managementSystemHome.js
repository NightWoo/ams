$(document).ready(function() {
	initPage();

	function initPage(){
		//add head class
		$("#headManagementSystemLi").addClass("active");
		page = parseInt($("#holder").attr("chapter"))
		$("#Carousel").carousel(page);
		if(page === 0)
			$("#leftOverviewLi").addClass("active");
		else if (page === 10)
			$("#leftPolicyLi").addClass("active");
		else if (page === 13)
			$("#leftGoalLi").addClass("active");
		else if (page === 21)
			$("#leftStructureLi").addClass("active");
		else if (page === 22)
			$("#leftProcessLi").addClass("active");
	}

	$("#leftOverviewLi").click(function(){
		$("#Carousel").carousel(0);
		$("#leftOverviewLi").addClass("active").siblings().removeClass("active");
	});

	$("#leftPolicyLi").click(function(){
		$("#Carousel").carousel(10);
		$("#leftPolicyLi").addClass("active").siblings().removeClass("active");
	});

	$("#leftGoalLi").click(function(){
		$("#Carousel").carousel(13);
		$("#leftGoalLi").addClass("active").siblings().removeClass("active");
	});

	$("#leftStructureLi").click(function(){
		$("#Carousel").carousel(21);
		$("#leftStructureLi").addClass("active").siblings().removeClass("active");
	});

	$("#leftProcessLi").click(function(){	
		$("#Carousel").carousel(22);
		$("#leftProcessLi").addClass("active").siblings().removeClass("active");
	
	});

	$("#Carousel").carousel("pause");

	// $("#Carousel").carousel({
	//  	interval: false
	// })

	$("#Carousel").on("slid",function() {
		activePage = $(".carousel-inner .active")
		if(activePage.index() === 0 || activePage.index() === 9){
			$("#leftOverviewLi").addClass("active").siblings().removeClass("active");
		}else if(activePage.index() === 10 || activePage.index() === 12){
			$("#leftPolicyLi").addClass("active").siblings().removeClass("active");
		}else if(activePage.index() === 13 || activePage.index() === 20){
			$("#leftGoalLi").addClass("active").siblings().removeClass("active");
		}else if(activePage.index() === 21){
			$("#leftStructureLi").addClass("active").siblings().removeClass("active");
		}else if(activePage.index() === 22){
			$("#leftProcessLi").addClass("active").siblings().removeClass("active");
		}
	});

	var slides = $('.item');

	slides.on('swipeleft', function(e) {
  		$("#Carousel").carousel('next');
	}).on('swiperight', function(e) {
  		$("#Carousel").carousel('prev');
	});

})