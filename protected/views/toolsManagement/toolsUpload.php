<html lang="en">
    <head>
        <meta charset="utf-8">
        <script type="text/javascript" src="/bms/js/tools/jquery.js"></script>
        <script type="text/javascript" src="/bms/js/tools/ajaxfileupload.js"></script>
        <script type="text/javascript">
            function ajaxFileUpload()
            {
                $.ajaxFileUpload
                        (
                                {
                                    url: '/bms/toolsManagement/upload',   //../toolsManagement/Upload
                                    secureuri: false,
                                    fileElementId: 'img',
                                    dataType: 'json',
                                    success: function(data)
                                    {
                                        alert(data.file_infor);
                                        //console.log(data.file_infor);
                                        window.parent.document.getElementById('editModalTextEditUpfile').value=data.imgsrc;
                                    }
                                }
                        );
                return false;
            }
        </script>
    </head>
    <body style="margin-top: 0px; padding: 0px">
<!--                <form id="UpLoadForm" enctype="multipart/form-data" action="upload.php" method="post"> 
                    <input id="img" type="file" size="45" name="img" class="input">
                    <input type="submit" value="上传">
                </form>-->
        
        <input id="img" type="file" size="45" name="img" style="width:65px;height:30px;">
        <input type="button" onclick="return ajaxFileUpload();" value="上传" style="width:45px;height:30px;">

    </body>
</html>