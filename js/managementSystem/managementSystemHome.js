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
			$("#leftGoalLi").addClass("active");
		else if (page === 15)
			$("#leftStructureLi").addClass("active");
		else if (page === 16)
			$("#leftProcessLi").addClass("active");
	}

	$("#leftOverviewLi").click(function(){
		$("#Carousel").carousel(0);
		$("#leftOverviewLi").addClass("active").siblings().removeClass("active");
	});

	$("#leftGoalLi").click(function(){
		$("#Carousel").carousel(10);
		$("#leftGoalLi").addClass("active").siblings().removeClass("active");
	});

	$("#leftStructureLi").click(function(){
		$("#Carousel").carousel(15);
		$("#leftStructureLi").addClass("active").siblings().removeClass("active");
	});

	$("#leftProcessLi").click(function(){	
		$("#Carousel").carousel(16);
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
		}else if(activePage.index() === 10 || activePage.index() === 14){
			$("#leftGoalLi").addClass("active").siblings().removeClass("active");
		}else if(activePage.index() === 15){
			$("#leftStructureLi").addClass("active").siblings().removeClass("active");
		}else if(activePage.index() === 16){
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