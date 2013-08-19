define(['service'], function(service) {
	SeriesArray = {
		"F0" : "F0",
		"M6" : "M6",
		"6B" : "思锐",
	}

	function getDutyOptions (type, showDisabled) {
		showDisabled = showDisabled || false;
		optionsDiv = $("<div />");
		$.ajax({
			url: service.QUERY_DUTY_DEPARTMENT,
			async: false,
			dataType: "json",
			data: {
				"node": type,
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

	return {
		seriesName: function (series) {
			return SeriesArray[series];
		},

		getDutyOptions: function (type, showDisabled) {
			return getDutyOptions(type, showDisabled);
		},

		alertError: function (message) {
			message = message || 'ajax error';
			// alert(message);
		},
	}
})