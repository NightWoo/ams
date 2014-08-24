$("document").ready(function() {
	
	hideAll();//隐藏下面所有

	var compArray = [];
	var recordArray = [];
	//校验
	function ajaxValidate (argument) {
		$.ajax({
			type: "get",//使用get方法访问后台
        	dataType: "json",//返回json格式的数据
			url: T21_GET_PARTS,
			data: {vin: $('#vinText').attr("value")},
			success: function(response) {
				console.log(response);
				if(response.success){
					$('#carInfo').show();
					var car = response.data.car;
					var comp = response.data.components;
					compArray = comp;//record  components
					$('#series').html(car.series);
					$('#color').html(car.color);
					$('#type').html(car.type);
					$('#vin').html(car.vin);
					$.each(comp,function (i) {
						$("<tr><td>"+comp[i].name+"</td><td id='comp"+i+"'></td></tr>")
						.insertAfter($("#componentTalbe tr:eq("+($("#componentTalbe tr").length-1)+")"));
					});
					
				}
				else{
					$('#notallowAlert').html(response.message);
					$('#notallowAlert').show();
				}
			},
			error:function(){
				alertError();
			}
		});
	}

	//进入
	function ajaxEnter (argument) {
		
		var obj = {};
		for (var i = 0; i < recordArray.length; i++) {
			console.log(recordArray[i]);
			console.log(compArray[recordArray[i]].id + "#" + $("#comp"+recordArray[i]).html());
			obj[compArray[recordArray[i]].id] = $("#comp"+recordArray[i]).html();
		};


		var jsonText = JSON.stringify(obj);
		console.log(jsonText);



		$.ajax({
				type: "post",//使用get方法访问后台
	        	dataType: "json",//返回json格式的数据
				url: T21_SUBMIT_PARTS,
				data: {"componentCode":jsonText},
				success: function(response) {
					if(response.success){
						disableButton($("#sub"));
						$('#alertVin').html($('#vinText').attr("value"));
						$('#infoAlert').show();
					}
					else
						$('#errorAlert').show();
				},
				error:function(){
					
				}
			});
	}

	//隐藏下面的所有信息
	function hideAll() {
		$('#infoAlert').hide();
		$('#errorAlert').hide();
		$('#carInfo').hide();
		$('#notallowAlert').hide();
		// $('#ComponentInfo').hide();
	}

	//输入回车，发ajax进行校验；成功则显示并更新车辆信息
	$('#vinText').bind('keydown', function (event) {
		if (event.keyCode == "13") {
			hideAll();
			ajaxValidate();
			console.log($('#vinText').attr("value"));
			enableButton($("#sub"));
			return false;
		}
	});

	//进入彩车身库事件，发ajax，根据响应做提示
	$("#sub").click(function() {
		if(!($("#sub").hasClass("disabled"))){
			ajaxEnter();
		}
		return false;
	});

	// 按钮的可用不可用状态的切换
	function disableButton(target) {
		target.addClass("disabled");
		target.attr("disabled", true);
	}

	function enableButton(target) {
		target.removeClass("disabled");
		target.attr("disabled", false);
	}

	$('#compCodeText').bind('keydown',function (event) {
		if(event.keyCode == "13"){
			
			console.log(getCompIndex($('#compCodeText').attr("value")));
			var index = getCompIndex($('#compCodeText').attr("value"));
			if(index != -1){
				$("#comp"+index).html(compArray[index].simple_code);
				recordArray.push(index);
			}
			
		}
	});

	function getCompIndex (compCode) {
		for (var i = 0; i < compArray.length; i++) {
			//判断规则
			if(compArray[i].simple_code == compCode)
				return i;
		};
		return -1;
	}
});
