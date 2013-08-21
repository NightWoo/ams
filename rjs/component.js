define(['service'], function (service) {
	function getCodeOptions (componentName, series) {
		optionsDiv = $("<div />");
		$.ajax({
			url: service.GET_COMPONENT_CODE,
			async: false, 
			dataType: "json",
			data: {
				"series" : series,
				"componentName": componentName	
			},
			success: function(response) {
				if(response.success){
					$.each(response.data, function(index, value){
						if(value.simple_code === ""){
							option = $("<option value='"+ value.component_id +"'>"+ value.component_code + "</option>");
						} else {
							option = $("<option value='"+ value.component_id +"'>"+ value.component_code + " &lt;" + value.simple_code + "&gt;</option>");							
						}
						option.appendTo(optionsDiv);
					})
				}				
			},
		})
		options = optionsDiv.children();
		return options;
	}

	function getInfo (componentId) {
		var componentInfo;
		$.ajax({
			url: service.GET_COMPONENT_INFO,
			async: false, 
			dataType: "json",
			data: {
				"componentId" : componentId,
			},
			success: function(response) {
				if(response.success){
					componentInfo = response.data;
				}				
			},
		})
		return componentInfo;
	}

	function ajaxValidateBarCode (vin, componentId, barCode) {
		result = [];
		$.ajax({
			url: service.VALIDATE_BAR_CODE,
			dataType: "json",
			data: {
				"vin" : vin,
				"componentId" : componentId,
				"barCode" : barCode,
			},
			async:false,
			success: function (response) {
				if(response.success){
					result["success"] = true; 
					result["message"] = "barCode is good";
				}else{
					result["success"] = false; 
					result["message"] = response.message;
				}
			}
		})
	}

	return {
		getCodeOptions: function (componentName, series) {
			return getCodeOptions(componentName, series);
		},

		getInfo: function (componentId) {
			return getInfo(componentId);
		},

		validateBarCode: function (vin, componentId, barCode) {
			return ajaxValidateBarCode(vin, componentId, barCode);
		}
	}
})