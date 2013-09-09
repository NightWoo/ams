
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
		$("#selfTel").val($("#userInfoTr").data("tel"));
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


	$("#queryUsername, #queryDisplayName, #queryEmail").bind("keydown", function (event) {
		if(event.keyCode == '13'){
			if($.trim($(this).val()) != ""){
				ajaxQuery();
			}
			return false;
		}	
	});

	function optDelete () {
		console.log($(this).closest("tr").data("userId"));
		if(confirm("是否删除账号" + $(this).closest("tr").data("userName") +"?")){
			ajaxDelete($(this).closest("tr").data("userId"));
		}
	}

	function optReset () {
		// $('#initPasswordModal').modal("toggle");
		if(confirm("是否初始化账号" + $(this).closest("tr").data("userName") +"密码为工号？")){
			ajaxReset($(this).closest("tr").data("userId"));
		}
	}


	function optModify (e) {
		var siblings = $(e.target).closest("td").siblings();
		var tr = $(this).closest("tr");
		$("#editUsername").val(tr.data("userName"));
		$("#editDisplayName").val(siblings[2].innerHTML);
		$("#editEmail").val(tr.data("email"));
		$("#editCardNumber").val(tr.data("cardNumber"));
		if(tr.data("card8H10D") == 0){
			$("#editCard8H10D").val("");
		} else {
			$("#editCard8H10D").val(tr.data("card8H10D"));
		}
		$("#editCellPhone").val(tr.data("cellphone"));
		$("#editTelephone").val(tr.data("telephone"));
		$("#editCertificate").val(tr.data("certificate"));

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
		    		"telephone" : $("#selfTel").val()
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
		    data: {
		    	"oldPassword" : $("#inputPasswordOld").val(),
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
		    data : {
		    	"username" : $("#queryUsername").val(),
		    	"display_name" : $("#queryDisplayName").val(),
		    	"email" : $("#queryEmail").val(),
		    	"perPage":5,
				"curPage": argument || 1
				},

		    success : function (response) {
		    	if (response.success) {
		    		//render user info
		    		var user = response.data.user;
		    		$("#userId").html(user.username);
		    		$("#userName").html(user.display_name);
		    		$("#userEmail").html(user.email);
		    		$("#userCell").html(user.cellphone);
		    		$("#userTel").html(user.telephone);
		    		$("#userInfoTr").data("email", user.email);
		    		$("#userInfoTr").data("cell", user.cellphone);
		    		$("#userInfoTr").data("tel", user.telephone);
		    		//if 'admin' ,render userlist
		    		var userList = response.data.userList;
		    		// console.log(userList.length);
		    		if(response.data.user.admin == "1"){
		    			$("#adminThing").show();
			    		if (userList.length > 0) {
			    			$.each(userList,function (index,value) {
				    			var tr = $("<tr />");
				    			$("<td />").html(value.id).appendTo(tr);
				    			$("<td />").html(value.username).appendTo(tr);
				    			$("<td />").html(value.display_name).appendTo(tr);
				    			emailTd = $("<td><a href='Mailto:" + value.email + "'>"+ value.email +"</a></td>");
				    			emailTd.appendTo(tr);
				    			// $("<td />").html(value.email).appendTo(tr);
				    			var optionTd = $("<td />");
				    			var groupDiv = $("<div />", {
				    				'class' : 'btn-group'
				    			})
				    			$("<input />", {
				    				'type' : 'button',
				    				"class" : "btn btn-primary btn-small optReset",
				    				'value' : "初始化密码"
				    			}).appendTo(groupDiv);
				    			$("<input />", {
				    				'type' : 'button',
				    				"class" : "btn btn-small optModify",
				    				'value' : "用户信息"
				    			}).appendTo(groupDiv);
				    			$("<input />", {
				    				'type' : 'button',
				    				"class" : "btn btn-danger btn-small optRight",
				    				'value' : "权限编辑"
				    			}).appendTo(groupDiv);
				    			$("<input />", {
				    				'type' : 'button',
				    				"class" : "btn btn-danger btn-small optDelete",
				    				'value' : "账号删除"
				    			}).appendTo(groupDiv);

				    			groupDiv.appendTo(optionTd);
				    			optionTd.appendTo(tr);

				    			tr.data("userId", value.id);
				    			tr.data("userName", value.username);
				    			tr.data("cardNumber", value.card_number);
				    			tr.data("card8H10D", value.card_8H10D);
				    			tr.data("email", value.email);
				    			tr.data("cellphone", value.cell);
				    			tr.data("telephone", value.telephone);
				    			tr.data("certificate", value.certificate);
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
				    		if(response.data.pager.curPage * 5 >= response.data.pager.total )
				    			$(".nextPage").hide();
				    		else
				    			$(".nextPage").show();
				    		$(".curPage").attr("page", response.data.pager.curPage);
				    		$(".curPage").html("第" + response.data.pager.curPage + "页");

				    		ajaxGetRights();
				    		$("#resultTable>tbody").show();
			    		} else {
			    			$("#resultTable>tbody").hide();
			    			$(".pager").hide();
			    		}
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
		    data: {
		    	"card_number" : $("#inputCardNumber").val(),
		    	"card_8H10D" : $.trim($("#inputCard8H10D").val()),
		    	"username" : $("#inputUsername").val(),
				"display_name" : $("#inputDisplayName").val(),
				"email" : $("#inputEmail").val(),
				"cell" : $("#inputCellPhone").val(),
				"telephone" : $("#inputTelephone").val(),
				"certificate" : $("#inputCertificate").val()
			},
		    success:function (response) {
		    	if (response.success) {
		    		alert("用户添加成功！");
		    		$("#newModal").modal('hide');
		    		ajaxQuery ($(".curPage").attr("page"));
		    		emptyNewModal();
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
		    data: {
		    	"id" : $('#editModal').data("userId"),
		    	"card_number" : $("#editCardNumber").val(),
		    	"card_8H10D" : $.trim($("#editCard8H10D").val()),
		    	"username" : $("#editUsername").val(),
				"display_name" : $("#editDisplayName").val(),
				"email" : $("#editEmail").val(),
				"cell" : $("#editCellPhone").val(),
				"telephone" : $("#editTelephone").val(),
				"certificate" : $("#editCertificate").val()
			},
		    success:function (response) {
		    	if (response.success) {
		    		alert("修改成功");
		    		$("#editModal").modal('hide');
		    		ajaxQuery ($(".curPage").attr("page"));

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
		    		alert("删除成功");
		    	}else{
		    		alert(response.message);
		    		ajaxQuery ($(".curPage").attr("page"));
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
		    		$("#rightControls").html("");
		    		$(response.data).each(function (index, value) {
		    			var label = $("<label />").addClass("checkbox");
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
		$.ajax({
			type: "get",//使用get方法访问后台
    	    dataType: "json",//返回json格式的数据
		    url: ROLE_ADD_TO_USER,//ref:  /bms/js/service.js
		    data: {"userId" : $('#rightModal').data("userId"),
			"roleIds" : list},
		    success:function (response) {
		    	if(response.success){
		    		ajaxQuery();
		    	}else{
		    		// $("#vinText").val("");
		    		alert(response.message);
		    	}
		    },
		    error:function(){alertError();}
		});
		$('#rightModal').modal("toggle");
	}

	function emptyNewModal(){
		$("#newModal input[type=text]").val("");
	}

});
