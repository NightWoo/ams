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

	function ajaxCheckBarCode (vin, componentId, barCode) {
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
		return result;
	}

	function validateBarCode (barCode, simpleCode) {
		var matched = false;
		var barSimpleCode = "";
		var providerCode = "";
		switch(barCode.length) {
			case 17:
				if(barCode.substring(0,3) == 'BYD')	//len 17 may be an engine
					barSimpleCode = barCode.substring(0,8);
				else{
					barSimpleCode = barCode.substring(5,8);
					providerCode = barCode.substring(0,5);
				}
				break;
			case 18:
				//len 18 may be an DongAN gearbox
				if(barCode.substring(0,5) == 'F4A4B'){
					barSimpleCode = barCode.substring(0,5)
				} else if(barCode.substring(0,9) == 'BYD476ZQA'){ //len 18 may be an 475ZQA engine
					barSimpleCode = barCode.substring(0,9)
				} else{
					barSimpleCode = barCode.substring(6,9);
					providerCode = barCode.substring(0,6);
				}
				break;
			case 16:
				barSimpleCode = barCode.substring(0,7);
				break;
			case 15:	//liandian ECU
				barSimpleCode = barCode.substring(0,3);
				break;
			case 3:
				barSimpleCode = barCode;
				break;
			case 14:	//4G69 engine
				barSimpleCode = barCode.substring(0,4);
				break;
			default: break;
		}

		if(barSimpleCode == simpleCode) matched = true;
		return matched;
	}

	return {
		getCodeOptions: function (componentName, series) {
			return getCodeOptions(componentName, series);
		},

		getInfo: function (componentId) {
			return getInfo(componentId);
		},

		ajaxCheckBarCode: function (vin, componentId, barCode) {
			return ajaxCheckBarCode(vin, componentId, barCode);
		},

		validateBarCode: function (barCode, simpleCode) {
			return validateBarCode(barCode, simpleCode);
		}
	}
})