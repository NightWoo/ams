define(function() {
	function attachDropDown () {
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
	}

	function attachToggleTop () {
		$("#toggle-top").on("click", function() {
			if($("#bmsHead").css("display") == "none"){
				$(".offhead").animate({"margin-top":"55px"});
				$("#icon-top").removeClass("icon-chevron-down");
				$("#icon-top").addClass("icon-chevron-up");
				$("#toggle-top").animate({"top":"41px"});
				$("#bmsHead").slideDown();
			} else if($("#bmsHead").css("display") == "block"){
			$("#bmsHead").slideUp();
				$("#toggle-top").animate({"top":"0"});
				$("#icon-top").removeClass("icon-chevron-up");
				$("#icon-top").addClass("icon-chevron-down");
				$(".offhead").animate({"margin-top":"20px"});
			}
		})

		$("#toggle-top").hover( 
			function () {
				$("#icon-top").show();
			},
			function () {
				$("#icon-top").hide();
			}
		)
	}

	function attachTootip () {
		$('body').tooltip(
			{
	         selector: "*[rel=tooltip]",
	    	}
	    );	
	}

	return {
		doInit: function() {
			attachDropDown();
			attachToggleTop();
			attachTootip();
		}
	}
})