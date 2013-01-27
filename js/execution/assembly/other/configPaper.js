$(document).ready(function () {
	$('#file_upload').uploadify({
			'swf'      : '/bms/js/uploadify/uploadify.swf',
			'uploader' : 'uploadify.php',
			'buttonText' : '本地文件',
			 'auto'     : false,
			 'queueID' : 'queue1',
			  'width'    : 86,
			  'uploadLimit' : 1,
			  'removeTimeout' : 0

			// Your options here
		});


	$("#confirm").live("click", function (event) {
		console.log("hehs")
		$('#file_upload').uploadify('upload','*');
		return false;
	})
});