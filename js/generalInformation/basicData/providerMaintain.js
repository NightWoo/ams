$(document).ready(function() {
	initPage();

	$("#btnAdd").click(function (argument){
		$("#newModal").modal("show");
	})
	
	$("#btnQuery").click(function (){
		//clear last
		ajaxQuery();
		return false;
	})
	
	$("#btnAddConfirm").click(function (){
		ajaxAdd();
		$("#newModal").modal("hide");
		return false;	
	})
	
	$("#btnEditConfirm").click(function (){
		ajaxEdit();
		$("#editModal").modal("hide");
		return false;	
	})
	
	$("#formProvider").bind("keydown",function(event){
		if(event.keyCode=="13"){
			ajaxQuery();
			return false;	
		}
	})

	$("#tableProvider").live("click", function(e) {
		if($(e.target).html()==="编辑"){
			var siblings = $(e.target).parent("td").siblings();
			$("#editProviderCode").val(siblings[1].innerHTML);
			$("#editProviderName").val(siblings[2].innerHTML);
			$("#editDisplayName").val(siblings[3].innerHTML);
			
			$("#editModal").data("id",$(e.target).closest("tr").data("id"));
			$("#editModal").modal("show");
			
		} else if($(e.target).html()==="删除"){
			if(confirm('是否删除供应商？')){
				ajaxDelete($(e.target).closest("tr").data("id"));
			}
		}	
	})

	function initPage() {
		$("#headGeneralInformationLi").addClass("active");
		$("#leftProviderMaintainLi").addClass("active");

		$("#newModal").modal("hide");
		$("#editModal").modal("hide");
	}

	function ajaxQuery()  {
		$.ajax({
			type: "get",
			dataType: "json",
			url: SEARCH_PROVIDER,
			data: {
				"providerCode": $("#providerCode").val(),
				"providerName": $("#providerName").val(),
			},

			success: function (response) {
				if (response.success) {
					$("#tableProvider tbody").html("");
					$.each(response.data, function (index, value){
						var tr = $("<tr />");
						$("<td />").html(value.id).appendTo(tr);
						$("<td />").html(value.code).appendTo(tr);
						$("<td />").html(value.name).appendTo(tr);
						$("<td />").html(value.display_name).appendTo(tr);
						var editTd = $("<td />").html(" ¦ ");
						$("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
						$("<button />").addClass("btn-link").html("删除").appendTo(editTd);
						editTd.appendTo(tr);

						tr.data("id", value.id);

						$("#tableProvider tbody").append(tr);
					})

				} else {
					alert(response.message);
				}
			},
			error: function () {alertError();}
		})
	}

	function ajaxAdd() {
		$.ajax({
			type: "get",
			dataType: "json",
			url: SAVE_PROVIDER,
			data: {
				"providerCode": $("#newProviderCode").val(),
				"providerName": $("#newProviderName").val(),
				"displayName": $("#newDisplayName").val(),
			},
			success: function (response) {
				if(response.success) {
					ajaxQuery();
					emptyNewModel();
				} else {
					alert(response.message);
				}
			},
			error: function () {alertError();}
		})
	}

	function ajaxEdit() {
		$.ajax({
			type: "get",
			dataType: "json",
			url: SAVE_PROVIDER,
			data: {
				"id": $("#editModal").data("id"),
				"providerCode": $("#editProviderCode").val(),
				"providerName": $("#editProviderName").val(),
				"displayName": $("#editDisplayName").val(),
			},
			success: function (response) {
				if(response.success) {
					ajaxQuery();
					emptyEditModel();
				} else {
					alert(response.message);
				}
			},
			error: function () {alertError();}
		})
	}

	function ajaxDelete(providerId) {
		$.ajax({
			type: "get",
			dataType:"json",
			url: DELETE_PROVIDER,
			data: {"id" : providerId},
			success: function (response) {
				if(response.success){
					ajaxQuery();	
				} else {
					alert(respose.message);
				}	
			},
			error: function () {
				alertError();
			}
				
		});
	}

	function emptyEditMoel() {
		$("#editModal").data("id","");
		$("#editProviderCode").val("");
		$("#editProviderName").val("");
		$("#editDisplayName").val("");
	}

	function emptyNewModel() {
		$("#newProviderCode").val("");
		$("#newProviderName").val("");
		$("#newDisplayName").val("");
	}
})