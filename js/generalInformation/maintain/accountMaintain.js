
$(document).ready(function () {
	initPage();
	ajaxQuery();

	// var list = [];
	// 	$("#rightControls input:checked").each(function () {
	// 		list.push($(this).val());
	// 	});
	// 	console.log(list);
	$("#modifySelf").live("click", function () {
		$("#emailModal").modal("show");
		$("#inputEmailChange").val($("#userInfoTr").data("email"));
		$("#selfCell").val($("#userInfoTr").data("cell"));
		$("#sellTel").val($("#userInfoTr").data("tel"));
	});
	//------------------- common functions -----------------------	
	//initialize this page
	/*
		1.add head class and resetPage
		2.resetPage();
		3.hide alert
	*/
	function initPage(){
		//add head class
		$("#headGeneralInformationLi").addClass("active");
		$(".pager").hide();
		
		$('#initPasswordModal').modal({
			"show" : false
		});
		$('#editModal').modal({
			"show" : false
		});

		$('#rightModal').modal({
			"show" : false
		});
	}

	$("#btnEmailChange").click( 
		function () {
			ajaxUserChangeEmail();
	});

	$("#btnPasswordChange").click( 
		function () {
			if ($("#inputPasswordNew").val() == $("#inputPasswordConfirm").val()) {
				ajaxUserChangePassword();
			} else { //check the new pword and confirmed pword
				alert("请确认两次输入的密码相同");
				return false;
			}
	});
	

	$("#btnQuery").click (function () {
		//clear last
		$("#resultTable tbody").text("");
		ajaxQuery();
		return false;
	});

	$(".prePage").click(
		function (){
			ajaxQuery(parseInt($(".curPage").attr("page")) - 1);
		}
	);

	$(".nextPage").click(
		function (){
			ajaxQuery(parseInt($(".curPage").attr("page")) + 1);
		}
	);

	$("#btnAdd").click (function () {
		ajaxAdd();
		return false;
	});

	$("#btnExport").click(
		function () {
			ajaxExport();
			return false;
		}
	);


	$("#vinText").bind("keydown", function (event) {
		if(event.keyCode == '13'){
			if($.trim($("#vinText").val()) != ""){
				$("#resultTable tbody").text("");
				ajaxQuery();
			}
			return false;
		}	
	});

	function optDelete () {
		console.log($(this).closest("tr").data("userId"));
		ajaxDelete($(this).closest("tr").data("userId"));
	}

	function optReset () {
		// $('#initPasswordModal').modal("toggle");
		ajaxReset($(this).closest("tr").data("userId"));
	}


	function optModify (e) {
		var siblings = $(e.target).parent("td").siblings();
		$("#editUsername").val(siblings[0].innerHTML);
		$("#editDisplayName").val(siblings[1].innerHTML);
		$("#editEmail").val(siblings[2].innerHTML);
		var tr = $(this).closest("tr");
		$("#editCardNumber").val(tr.data("cardNumber"));
		$("#editCellPhone").val(tr.data("cellphone"));
		$("#editTelephone").val(tr.data("telephone"));

		$('#editModal').data("userId",tr.data("userId"));
		$('#editModal').modal("show");
	}

	$(".optRight").live("click", function () {
		var tr = $(this).closest("tr");
		$('#rightModal').data("userId",tr.data("userId"));
		$("#rightControls input:checked").removeAttr("checked");

		$(tr.data("roleIds")).each(function (index, value) {
			$("#rightControls input[value=" + value + "]").attr("checked", "checked");
		});
		$('#rightModal').modal("show");
	});

	function ajaxUserChangeEmail () {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: UPDATE_USER,//ref:  /bms/js/service.js
		    data: {"email" : $("#inputEmailChange").val(),
		    		"cellphone" : $("#selfCell").val(),
		    		"telephone" : $("#sellTel").val()
		},

		    success:function (response) {
		    	if(response.success){
		    		location.reload();
		    		// $.each(response.data,function (index,value) {
		    		// 	var tr = $("<tr />");
		    		// 	var userNameTd = "<td>" + value.username + '<input type="hidden" value="' + value.id + '" />' + "</td>";
		    		// 	var displayNameTd = "<td>" + value.display_name + "</td>";
		    		// 	var emailTd = "<td>" + value.email + "</td>";
		    		// 	var optionTd = "<td>" + '<input type="button" class="btn btn-danger optDelete" value ="删除"></input>' + 
		    		// 		'<input type="button" class="btn btn-danger optReset" value ="改密码"></input>' + "</td>";
		    		// 	var tr = "<tr>" + userNameTd + displayNameTd + emailTd + optionTd + "</tr>";
		    		// 	$("#resultTable tbody").append(tr);
		    		// });
		    		// $(".optDelete").bind("click", optDelete);
		    		// $(".optReset").bind("click", optReset);
		    	}else{
		    		$("#vinText").val("");
		    		alert(response.message);

		    	}
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxUserChangePassword () {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: RESET_PASSWORD,//ref:  /bms/js/service.js
		    data: {"oldPassword" : $("#inputPasswordOld").val(),
		   			"newPassword" : $("#inputPasswordNew").val(),
		    		"comfirmPassword" : $("#inputPasswordConfirm").val()
		},

		    success:function (response) {
		    	if(response.success){
		    		confirm("密码修改成功！");
		    		$("#passwordModal").modal("hide");
		    		$("#inputPasswordOld").val("");
		    		$("#inputPasswordNew").val("");
		    		$("#inputPasswordConfirm").val("");
		    	} else {
		    		$("#vinText").val("");
		    		alert(response.message);
		    	}
		    },
		    error:function(){alertError();}
		});
	}
	
	function ajaxQuery (argument) {
		//clear before render
		$("#resultTable tbody").html("");
		$.ajax({
			type : "get",//使用get方法访问后台
    	    dataType : "json",//返回json格式的数据
		    url : SHOW_USER,//ref:  /bms/js/service.js
		    data : {"username" : $("#queryUsername").val(),
		    	"display_name" : $("#queryDisplayName").val(),
		    	"email" : $("#queryEmail").val(),
		    	"perPage":20,
				"curPage": argument || 1
				},

		    success : function (response) {
		    	if (response.success) {
		    		//render user info
		    		var user = response.data.user;
		    		$("#userId").html(user.username);
		    		$("#userName").html(user.display_name);
		    		$("#userEmail").html(user.email);
		    		$("#userInfoTr").data("email", user.email);
		    		$("#userInfoTr").data("cell", user.cellphone);
		    		$("#userInfoTr").data("tel", user.telephone);
		    		//if 'admin' ,render userlist
		    		var userList = response.data.userList;
		    		// console.log(userList.length);
		    		if (userList.length > 0) {
		    			$("#adminThing").show();
		    			$.each(userList,function (index,value) {
			    			var tr = $("<tr />");
			    			$("<td />").html(value.username).appendTo(tr);
			    			$("<td />").html(value.display_name).appendTo(tr);
			    			$("<td />").html(value.email).appendTo(tr);
			    			var optionTd = $("<td />");
			    			$("<input />", {
			    				'type' : 'button',
			    				"class" : "btn btn-danger optDelete",
			    				'value' : "删除账户"
			    			}).appendTo(optionTd);
			    			$("<input />", {
			    				'type' : 'button',
			    				"class" : "btn btn-primary optReset",
			    				'value' : "初始化密码"
			    			}).appendTo(optionTd);
			    			$("<input />", {
			    				'type' : 'button',
			    				"class" : "btn btn-primary optModify",
			    				'value' : "编辑"
			    			}).appendTo(optionTd);
			    			$("<input />", {
			    				'type' : 'button',
			    				"class" : "btn btn-primary optRight",
			    				'value' : "编辑权限"
			    			}).appendTo(optionTd);
			    			optionTd.appendTo(tr);
			    			tr.data("userId", value.id);
			    			tr.data("cardNumber", value.card_number);
			    			tr.data("cellphone", value.cell);
			    			tr.data("telephone", value.telephone);
			    			tr.data("roleIds", value.roleIds);
			    			$("#resultTable tbody").append(tr);
			    		});
			    		$(".optDelete").bind("click", optDelete);
			    		$(".optReset").bind("click", optReset);
			    		$(".optModify").bind("click", optModify);
			    		//deal with pager
			    		$(".pager").show();
			    		if(response.data.pager.curPage == 1)
			    			$(".prePage").hide();
			    		else
			    			$(".prePage").show();
			    		if(response.data.pager.curPage * 20 >= response.data.pager.total )
			    			$(".nextPage").hide();
			    		else
			    			$(".nextPage").show();
			    		$(".curPage").attr("page", response.data.pager.curPage);
			    		$(".curPage").html("第" + response.data.pager.curPage + "页");

			    		ajaxGetRights();
		    		} else {
		    			$("#adminThing").hide();
		    		}
		    		
		    	}else{
		    		$("#vinText").val("");
		    		alert(response.message);

		    	}
		    },
		    error : function() { 
		    	alertError(); 
		    }
		});
	}

	function ajaxAdd (argument) {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: ADD_USER,//ref:  /bms/js/service.js
		    data: {"card_number" : $("#inputCardNumber").val(),
		    	"username" : $("#inputUsername").val(),
				"display_name" : $("#inputDisplayName").val(),
				"email" : $("#inputEmail").val(),
				"cell" : $("#inputCellPhone").val(),
				"telephone" : $("#inputTelephone").val()
			},
		    success:function (response) {
		    	if (response.success) {
		    		alert("yep");
		    	} else {
		    		// $("#vinText").val("");
		    		alert(response.message);
		    	}
		    },
		    error:function(){alertError();}
		});
	}

	$("#btnEdit").click(function () {
		ajaxEdit();
	})
	function ajaxEdit (argument) {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: EDIT_USER,//ref:  /bms/js/service.js
		    data: {"id" : $('#editModal').data("userId"),
		    	"card_number" : $("#editCardNumber").val(),
		    	"username" : $("#editUsername").val(),
				"display_name" : $("#editDisplayName").val(),
				"email" : $("#editEmail").val(),
				"cell" : $("#editCellPhone").val(),
				"telephone" : $("#editTelephone").val()
			},
		    success:function (response) {
		    	if (response.success) {
		    		alert("yep");
		    	} else {
		    		// $("#vinText").val("");
		    		alert(response.message);
		    	}
		    },
		    error:function(){alertError();}
		});
	}
	
	function ajaxDelete (userId) {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: DISABLE_USER,//ref:  /bms/js/service.js
		    data: {"id" : userId},
		    success:function (response) {
		    	if(response.success){
		    		alert("yep");
		    	}else{
		    		// $("#vinText").val("");
		    		alert(response.message);
		    	}
		    },
		    error:function(){alertError();}
		});
	}
	function ajaxReset (userId) {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: INIT_PASSWORD,//ref:  /bms/js/service.js
		    data: {"id" : userId},
		    success:function (response) {
		    	if(response.success){
		    		$('#initPasswordModal').modal("toggle");
		    		$('#resetedPassword').html(response.data);
		    	}else{
		    		// $("#vinText").val("");
		    		alert(response.message);
		    	}
		    },
		    error:function(){alertError();}
		});
	}

	function ajaxGetRights () {
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: ROLE_SHOW_ALL,//ref:  /bms/js/service.js
		    data: {},
		    success:function (response) {
		    	if(response.success){
		    		$(response.data).each(function (index, value) {
		    			var label = $("<label />").addClass("checkbox").addClass("inline");
		    			var checkHtml = '<input type="checkbox" value="' + value.id + '">' + value.name;
		    			label.html(checkHtml);
		    			// $("<input/>" , {'type' : 'checkbox'}).attr("value", ).html().appendTo(label);
		    			label.appendTo($("#rightControls"));
		    		});
		    	}else{
		    		// $("#vinText").val("");
		    		alert(response.message);
		    	}
		    },
		    error:function(){alertError();}
		});
	}

	$("#btnRight").click(function () {
		ajaxEditRight();
	});

	function ajaxEditRight () {
		var list = [];
		$("#rightControls input:checked").each(function () {
			list.push($(this).val());
		});
		console.log(list);
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: ROLE_ADD_TO_USER,//ref:  /bms/js/service.js
		    data: {"userId" : $('#rightModal').data("userId"),
			"roleIds" : list},
		    success:function (response) {
		    	if(response.success){
		    		
		    	}else{
		    		// $("#vinText").val("");
		    		alert(response.message);
		    	}
		    },
		    error:function(){alertError();}
		});
	}


	function ajaxExport () {
		window.open(TRACE_EXPORT + "?vin=" + $('#vinText').val() + 
			"&node=" + $("#selectNode").val()
		);
	}
});
