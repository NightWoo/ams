jQuery(document).ready(function($) {
	initPage();

	function initPage() {
		$("#headEfficiencyLi").addClass("active");
		$("#leftWarehouseAdjustLi").addClass("active");

		$("#tableResult").hide();
	}

	function ajaxQuery() {
		$("#tableResult>tbody").html("");
		$.ajax({
			url: WAREHOUSE_QUERY,
			type: "get",
			dataType: "json",
			data:{
				"area": $("#area").val(),
				"row": $("#row").val(),
				"series": $("#series").val(),
				"orderConfig": $("#orderConfig").val(),
				"color": $("#color").val(),
			},
			success: function(response) {
				if(response.success){
					$.each(response.data, function(indes, value) {
						var tr = $("<tr />");
						var editTd = $("<td />");
						var reset = "<a class='btn btn-link' href='#' rel='tooltip' data-placement='top' title='整理空位'><i class='fa fa-retweet'></i></a>";
						editTd.html(reset).appendTo(tr);

						$("<td />").html(value.row).appendTo(tr);
						$("<td />").html(value.capacity).appendTo(tr);
						// $("<td />").html(value.quantity).appendTo(tr);
						// $("<td />").html(value.free_seat).appendTo(tr);

						occupid = parseInt(value.capacity) - parseInt(value.quantity) -parseInt(value.free_seat);
						useRate = (parseInt(value.quantity) / parseInt(value.capacity) * 100) + "%";
						occupidRate = (occupid / parseInt(value.capacity) * 100) + "%";
						freeRate = (parseInt(value.free_seat) / parseInt(value.capacity) * 100) + "%";

						var progress = $("<div />").addClass("progress");
						var barOccupid = $("<div />").addClass("bar bar-warning").attr("style", "width:" + occupidRate).html(occupid);
						var barUse = $("<div />").addClass("bar bar-info").attr("style", "width:" + useRate).html(value.quantity);
						var barFree = $("<div />").addClass("bar bar-success").attr("style", "width:" + freeRate).html(value.free_seat);
						var prgoressTd = $("<td />").html(progress.append(barOccupid).append(barUse).append(barFree)).appendTo(tr);

						if(value.series == ""){
							$("<td />").html("混合").appendTo(tr);
						} else if(value.series == "") {
							$("<td />").html("思锐").appendTo(tr);
						} else {
							$("<td />").html(value.series).appendTo(tr);
						}
					
						$("<td />").html(value.order_config_name).appendTo(tr);
						$("<td />").html(value.cold).appendTo(tr);
						$("<td />").html(value.color).appendTo(tr);

						tr.data("warehouseId", value.warehouse_id);
						tr.data("row", value.row);
						tr.data("capacity", value.capacity);
						tr.data("quantity", value.quantity);
						tr.data("freeSeat", value.free_seat);
						tr.data("coldResistant", value.cold_resistant);

						$("#tableResult>tbody").append(tr);
					})
					
					$("#tableResult").show();
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alertError();
			}
		})
	}

	function ajaxResetRow(warehouseId) {
		$.ajax({
			url: RESET_FREE_SEAT,
			type: "get",
			dataType: "json",
			data: {
				"warehouseId" : warehouseId,
			},
			success: function(response) {
				if(response.success){
					ajaxQuery();
				} else {
					alert(response.message);
				}
			},
			error: function(){
				alertError();
			}
		})
	}

	function fillOrderConfig(carSeries) {
		var options = '<option value="0" selected>未选择</option>';
		$.ajax({
			url: FILL_ORDER_CONFIG,
			type: "get",
			dataType: "json",
			data: {
				"carSeries" : carSeries,
			},
			async: false,
			success: function(response) {
				if(response.success){						
					$.each(response.data, function(index,value){
						// option +='<option value="' + value.config_id +'">'+ value.config_name +'</option>';	
						options +='<option value="' + value.config_id +'">'+ value.config_name +'</option>';	
					});
				}
			},
			error: function() { 
		    	alertError(); 
		    }
		})
		return options;
	}

	function fillColor(carSeries) {
		var options = '<option value="" selected>请选择</option>';
		$.ajax({
			url: FILL_CAR_COLOR,
			type: "get",
			dataType: "json",
			data: {
				"carSeries" : carSeries
			},
			async: false,
			success: function(response) {
				if(response.success){
					$.each(response.data, function(index, value){
						options += '<option value="'+ value.color +'">'+ value.color +'</option>';
					});
				}
			},
			error: function() {
				alertError();
			}
		})
		return options;
	}


	$("#series").change(function() {
		configOptions = fillOrderConfig($(this).val());
		$("#orderConfig").html("").append(configOptions);

		colorOtions = fillColor($(this).val());
		$("#color").html("").append(colorOtions);

		$("#row").val("");
	})

	$("#btnQuery").click(function() {
		ajaxQuery();
	})

	$("#tableResult").live("click", function(e) {
		if($(e.target).is("i")){
			var tr = $(e.target).closest("tr");
			if($(e.target).hasClass("fa-retweet")){
				if(confirm("是否整理" + tr.data("row"))){
					ajaxResetRow(tr.data("warehouseId"));
				}
			}
		}
	})

	//make tooltip work
	$('body').tooltip(
        {
         selector: "a[rel=tooltip]"
	});
});