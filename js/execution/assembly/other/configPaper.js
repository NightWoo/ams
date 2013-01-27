$(document).ready(function () {
	var sessionData = {};
	sessionData[$("#sessionName").val()] = $("#sessionId").val();
	sessionData['id'] = 1;
	$('#file_upload').uploadify({
			'swf'      : '/bms/js/uploadify/uploadify.swf',
			'uploader' : '/bms/config/upload',
			'buttonText' : '本地文件',
			'auto'     : false,
			'queueID' : 'queue1',
		    'width'    : 86,
		    'uploadLimit' : 1,
			'fileObjName' : 'frontImage',//backImage
		    'removeTimeout' : 0,
		    'formData' : sessionData,
		    'onSelect' : function(file) {
		            // alert('The file ' + file.name + ' was added to the queue.');
		            $("#testInput").val(file.name);
		        }
			// Your options here
		});


	$("#confirm").live("click", function (event) {
		console.log("hehs");
		$('#file_upload').uploadify('upload','*');
		return false;
	})
});
