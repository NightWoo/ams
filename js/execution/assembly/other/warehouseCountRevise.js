$(document).ready(function(e) {
	initPage();
	$("#btnUndistributedConfirm").click(function () {
		saveUndistributed();
	})

	function initPage () {
		$("#headGeneralInformationLi").addClass("active");
		ajaxQueryWarehouseCountRevise("未发");
	}

	function ajaxQueryWarehouseCountRevise (type) {
		$.ajax({
			url: WAREHOUSE_COUNT_REVISE_QUERY,
			type: "get",
			dataType: "json",
			data: {
				"type" : type
			},
			error: function () {alertError();},
			success: function (response) {
				if(response.success) {
					$.each(response.data, function (indx, data) {
						$(".revise").filter("[series="+ data.series +"][counttype=" + data.count_type +"]").val(data.count);
					})
				} else {
					alert(response.message);
				}
			}
		})
	}

	function saveUndistributed () {
		$(".revise[counttype='未发']").each(function (index, ele) {
			series = $(this).attr("series");
			value = $(this).attr("value")
			ajaxSaveWarehouseCountRevise ("未发", series, value);
		})
		alert("修改成功");
	}

	function ajaxSaveWarehouseCountRevise (type, series, value) {
		var ok = false;
		$.ajax({
			url: WAREHOUSE_COUNT_REVISE_SAVE,
			type: "get",
			dataType: "json",
			data: {
				"type": type,
				"series": series,
				"value": value
			},
			error: function () {alertError();},
			success: function (response) {
				if(response.success) {
				} else {
					alert(response.message);
				}
			}
		})
	}
});