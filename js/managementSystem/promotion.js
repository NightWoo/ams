$(document).ready(function() {
	initPage();

	function initPage(){
		//add head class
		$("#headManagementSystemLi").addClass("active");
		$("#leftManpowerLi").addClass("active");
	}

	$("#Carousel").carousel("pause");

	var slides = $('.item');

	slides.on('swipeleft', function(e) {
 		$("#Carousel").carousel('next');
	}).on('swiperight', function(e) {
  		$("#Carousel").carousel('prev');
	});

})