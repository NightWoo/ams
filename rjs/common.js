define(['service'], function(service) {
	SeriesArray = {
		"F0" : "F0",
		"M6" : "M6",
		"6B" : "思锐"
	}

	function fadeMessageAlert (message,alertClass) {
		$("#messageAlert").removeClass("alert-error alert-success").addClass(alertClass);
		$("#messageAlert").html(message);
		$("#messageAlert").show(500,function () {
			setTimeout(function() {
				$("#messageAlert").hide(1000);
			},5000);
		});
	}

	function addCheckMessage (message) {
		checkMessage  = "<div class='alert alert-error fade in'>";
		checkMessage += "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
		checkMessage += "<strong>注意！</strong>";
		checkMessage += message;
		checkMessage += "</div>";
		$("#checkAlert").prepend(checkMessage);
	}

	function getDutyOptions (type, showDisabled) {
		showDisabled = showDisabled || false;
		optionsDiv = $("<div />");
		$.ajax({
			url: service.QUERY_DUTY_DEPARTMENT,
			async: false,
			dataType: "json",
			data: {
				"node": type
			},
			success: function (response) {
				$.each(response.data, function (index, value) {
					if(!showDisabled && value.is_enabled == 0) return true;
					option = $("<option value='"+ value.id +"'>"+ value.name +"</option>");
					if(value.is_enabled == 0) option.attr("disabled", "disabled");
					option.appendTo(optionsDiv);
				})
			}
		})
		options = optionsDiv.children();
		return options;
	}

	function getSeriesChecked () {
		var f0Checked = $("#checkboxF0").prop("checked");
		var m6Checked = $("#checkboxM6").prop("checked");
		var _6BChecked = $("#checkbox6B").prop("checked");
		
		var temp = [];
		if (f0Checked)
			temp.push($("#checkboxF0").val());
		if (m6Checked)
			temp.push($("#checkboxM6").val());
		if (_6BChecked)
			temp.push($("#checkbox6B").val());
		return temp.join(",");
	}

	return {
		seriesName: function (series) {
			return SeriesArray[series];
		},

		fadeMessageAlert: function (message, alertClass) {
			fadeMessageAlert(message,alertClass);
		},

		addCheckMessage: function (message) {
			addCheckMessage(message);
		},

		getDutyOptions: function (type, showDisabled) {
			return getDutyOptions(type, showDisabled);
		},

		getSeriesChecked: function () {
			return getSeriesChecked();
		},

		alertError: function (message) {
			message = message || 'ajax error';
			// alert(message);
		},

		attachTooltip: function () {
			$('body').tooltip(
		        {
		         selector: "*[rel=tooltip]"
		    });	
		}
	}
});