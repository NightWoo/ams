$(document).ready(function() {

    initPage();
    //弹出新增框
    $("#btnAdd").click(function() {
        $("#newModal").modal("show");
        //初始化线别下拉框值
        selectLine('#selectAddAssemblyLine', 0);
    });
    //级联
    $("#assemblyLine").change(function() {  //搜索
        selectStage("#assemblyLine", "#assemblyStage", 0, 0);
    });
    $("#selectAddAssemblyLine").change(function() {     //新增
        selectStage("#selectAddAssemblyLine", "#selectAddAssemblyStage", 0, 0);
    });
    $("#selectEditAssemblyLine").change(function() {    //编辑
        selectStage("#selectEditAssemblyLine", "#selectEditAssemblyStage", 0, 0);
    });
    //新增线别及工段
    $("#newModalAddAssemblyLine").click(function() {
        $("#newModalTextAddAssemblyLine").show();
        $("#newModalBtnAddLineConfirm").show();
    });
    $("#newModalAddAssemblyStage").click(function() {
        $("#newModalTextAddAssemblyStage").show();
        $("#newModalBtnAddStageConfirm").show();
    });
    $("#editModalAddAssemblyLine").click(function() {
        $("#editModalTextAddAssemblyLine").show();
        $("#editModalBtnAddLineConfirm").show();
    });
    $("#editModalAddAassemblyStage").click(function() {
        $("#editModalTextAddAassemblyStage").show();
        $("#editModalBtnAddStageConfirm").show();
    });

    //确定新增线别及工段
    $("#newModalBtnAddLineConfirm").click(function() {
        addLine();
        $("#newModalTextAddAssemblyLine").hide();
        $("#newModalBtnAddLineConfirm").hide();
        return false;
    });
    $("#newModalBtnAddStageConfirm").click(function() {
        var lineId = $("#selectAddAssemblyLine").val();
        addStage(lineId);
        $("#newModalTextAddAssemblyStage").hide();
        $("#newModalBtnAddStageConfirm").hide();
        return false;
    });
    $("#editModalBtnAddLineConfirm").click(function() {
        addLine();
        $("#editModalTextAddAssemblyLine").hide();
        $("#editModalBtnAddLineConfirm").hide();
        selectLine('#selectEditAssemblyLine', 0);
        return false;
    });
    $("#editModalBtnAddStageConfirm").click(function() {
        var lineId = $("#selectEditAssemblyLine").val();
        addStage(lineId);
        $("#editModalTextAddAassemblyStage").hide();
        $("#editModalBtnAddStageConfirm").hide();
        selectStage("#selectEditAssemblyLine", "#selectEditAssemblyStage", lineId, 0);
        return false;
    });

    //弹出编辑框
    $("#tableToolsList").live("click", function(e) {

        var td = $(e.target).closest("tr").data("id");  //本行记录id
        $("#editModal").data("id", td); //保存id
        if ($(e.target).html() === "工位编辑") {
            //初始化编辑各属性的值
            var lineId = $("#editModal").data("lineId" + td);
            var stageId = $("#editModal").data("stageId" + td);
            selectLine('#selectEditAssemblyLine', lineId);  //初始化线别下拉框值
            selectStage("#selectEditAssemblyLine", "#selectEditAssemblyStage", lineId, stageId);  //初始化工段下拉框值
            var siblings = $(e.target).parent("td").siblings(); //遍历搜索结果行td
            $("#textEditAssemblyPoint").val(siblings[3].innerHTML);   //初始化工位值
            if ($("#editModal").data("using" + td) < 0) {  //初始化启用
                $('#checkboxEditStartUsing').attr("checked", "checked");
            } else {
                $('#checkboxEditStartUsing').attr("checked", false);
            }
            $("#editModal").modal("show");


        } else if ($(e.target).html() === "编辑") {

            //初始化编辑各属性的值
            var lineId = $("#editModal").data("lineId" + td);
            var stageId = $("#editModal").data("stageId" + td);
            if (stageId > 0) {  //工段编辑
                selectLine('#selectEditStageAssemblyLine', lineId);  //初始化线别下拉框值
                var siblings = $(e.target).parent("td").siblings(); //遍历搜索结果行td
                $("#textEditStageAssemblyStage").val(siblings[2].innerHTML);   //初始化工段值
                if ($("#editModal").data("using" + td) < 0) {  //初始化启用
                    $('#checkboxEditStageStartUsing').attr("checked", "checked");
                } else {
                    $('#checkboxEditStageStartUsing').attr("checked", false);
                }
                $("#editStageModal").modal("show");

            } else {    //线别编辑
                var siblings = $(e.target).parent("td").siblings(); //遍历搜索结果行td
                $("#textEditLineAssemblyLine").val(siblings[1].innerHTML);   //初始化工段值
                if ($("#editModal").data("using" + td) < 0) {  //初始化启用
                    $('#checkboxEditLineStartUsing').attr("checked", "checked");
                } else {
                    $('#checkboxEditLineStartUsing').attr("checked", false);
                }
                $("#editLineModal").modal("show");
            }

        } else if ($(e.target).html() === "删除") {
            if (confirm('是否确定删除？')) {
                ajaxDelete(td);
            }
        }
    });
    //确定查询
    $("#btnQuery").click(function() {
        ajaxQuery(1);
        return false;
    });
    //确定新增
    $("#btnAddConfirm").click(function() {
        if ($("#textAddAssemblyPoint").val() == '') {
            alert('工位名为空');
            return false;
        }
        ajaxAdd();
        $("#newModal").modal("hide");
        return false;
    });
    //继续新增
    $("#btnAddMore").click(function() {
        if ($("#textAddAssemblyPoint").val() == '') {
            alert('工位名为空');
            return false;
        }
        ajaxAdd();
        emptyNewModal();
        return false;
    });
    //确定编辑
    $("#btnEditConfirm").click(function() {
        if (typeof($("#selectEditAssemblyStage").val()) != 'undefined' && $("#selectEditAssemblyStage").val() > 0) {
            if ($("#textEditAssemblyPoint").val() == '') {
                alert('工位名为空');
                return false;
            }
            ajaxEdit();
            $("#editModal").modal("hide");
        } else {
            alert("请选择工段");
            return false;
        }
        return false;
    });
    $("#btnEditLineConfirm").click(function() {
        if ($("#textEditLineAssemblyLine").val() == '') {
            alert('线别名为空');
            return false;
        }
        ajaxEditLine();
        $("#editLineModal").modal("hide");
        return false;
    });
    $("#btnEditStageConfirm").click(function() {
        if (typeof($("#selectEditStageAssemblyLine").val()) != 'undefined' && $("#selectEditStageAssemblyLine").val() > 0) {
            if ($("#textEditStageAssemblyStage").val() == '') {
                alert('工段名为空');
                return false;
            }
            ajaxEditStage();
            $("#editStageModal").modal("hide");
        } else {
            alert("请选择线别");
            return false;
        }
        return false;
    });

    //函数：初始化
    function initPage() {
        //add head and left current class
        $("#headtoolsLi").addClass("active");
        $("#leftToolsDataLi").addClass("active");
        $("#newModal").modal("hide");
        $("#editModal").modal("hide");
        //初始化线别下拉框值
        selectLine('#assemblyLine', '');
    }
    //下拉框值获取
    function selectLine(obj_str, selectId) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_TOOLSUSER,
            data: {
                "type": 'selectMenu',
                "parentId": 0
            },
            success: function(response) {
                if (response.success) {

                    var strs = "<option value='0'>全部</option>";
                    $(obj_str).html("");
                    $.each(response.data, function(index, value) {
                        if (selectId == value.id) {
                            strs += "<option value=" + value.id + " selected='selected'>" + value.name + "</option>";
                        } else {
                            strs += "<option value=" + value.id + ">" + value.name + "</option>";
                        }
                    });
                    $(obj_str).append(strs);

                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    function selectStage(obj_str, obj_str2, parentId, selectId) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_TOOLSUSER,
            data: {
                "type": 'selectMenu',
                "parentId": (parentId > 0) ? parentId : (($(obj_str).val() > 0) ? $(obj_str).val() : 0)
            },
            success: function(response) {
                if (response.success) {

                    var strs;
                    $(obj_str2).html("");
                    if (response.data.length === 0) {
                        strs = "<option value=0 selected='selected'>无记录</option>";
                    } else {
                        if (parentId > 0 || $(obj_str).val() > 0) {

                            $.each(response.data, function(index, value) {

                                if (selectId == value.id) {
                                    strs += "<option value=" + value.id + " selected='selected'>" + value.name + "</option>";
                                } else {
                                    strs += "<option value=" + value.id + ">" + value.name + "</option>";
                                }
                            });

                        } else {
                            strs = "<option value='0' selected='selected'>全部</option>";
                        }
                    }
                    $(obj_str2).append(strs);

                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    //函数：查询结果
    function ajaxQuery(pageNumber) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_TOOLSUSER,
            data: {
                "type": 'selects',
                "stageId": $("#assemblyStage").val(),
                "is_using": $("#checkboxStopUse").attr("checked") == "checked" ? '0a' : '-1', //0a，应为0，加a为防止php程序，判断时用了empty
                "is_seat": $("#checkboxIsSeat").attr("checked") == "checked" ? '-1' : '0a',
                "assemblyPoint": $("#assemblyPoint").val(),
                "perPage": 20,
                "curPage": pageNumber || 1
            },
            success: function(response) {
                if (response.success) {
                    //console.log(response.data);
                    $("#tableToolsList tbody").html("");
                    $.each(response.data.listData, function(index, value) {
                        var tr = $("<tr />");
                        $("<td />").html(index + 1).appendTo(tr);
                        $("<td />").html(value.lineName).appendTo(tr);
                        $("<td />").html(value.stageName).appendTo(tr);
                        $("<td />").html(value.name).appendTo(tr);
                        if (value.is_using < 0) {
                            $("#editModal").data("using" + value.id, value.is_using);
                            //console.log($("#editModal").data("using"+value.id));
                        }
                        $("#editModal").data("lineId" + value.id, value.lineId);
                        $("#editModal").data("stageId" + value.id, value.stageId);
                        var editTd = $("<td />").html(" ¦ ");
                        if (value.is_seat == -1) {
                            $("<button />").addClass("btn-link").html("工位编辑").prependTo(editTd);
                        } else {
                            $("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
                        }
                        $("<button />").addClass("btn-link").html("删除").appendTo(editTd);
                        editTd.appendTo(tr);

                        tr.data("id", value.id);

                        $("#tableToolsList tbody").append(tr);
                    });
                    //pager
                    $(".pagination").show();
                    if (response.data.pager.curPage == 1) {
                        $(".prePage").hide();
                    } else {
                        $(".prePage").show();
                    }
                    if (response.data.pager.curPage >= Math.ceil(response.data.pager.total / response.data.pager.perPage)) {
                        $(".nextPage").hide();
                        $(".lastPage").hide();
                    } else {
                        $(".nextPage").show();
                        $(".lastPage").attr("page", Math.ceil(response.data.pager.total / response.data.pager.perPage));
                        $(".lastPage").show();
                    }
                    $(".curPage").attr("page", response.data.pager.curPage);
                    $("#curPage").html(response.data.pager.curPage);

                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    //函数 添加线别
    function addLine() {

        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_TOOLSUSER,
            data: {
                "type": 'addLine',
                "lineName": $("#newModalTextAddAssemblyLine").val()
            },
            success: function(response) {
                if (response.success) {
                    ajaxQuery(1);
                    selectLine('#selectAddAssemblyLine', 0);
                    emptyNewModal();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    //函数 添加工段
    function addStage(lineId) {

        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_TOOLSUSER,
            data: {
                "type": 'addStage',
                "stageName": $("#newModalTextAddAssemblyStage").val(),
                "lineId": lineId
            },
            success: function(response) {
                if (response.success) {
                    ajaxQuery(1);
                    selectStage("#selectAddAssemblyLine", "#selectAddAssemblyStage", lineId, 0);
                    emptyNewModal();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    //函数：添加操作
    function ajaxAdd() {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_TOOLSUSER,
            data: {
                "type": 'adds',
                "lineId": $("#selectAddAssemblyLine").val(),
                "stageId": $("#selectAddAssemblyStage").val(),
                "assemblyPoint": $("#textAddAssemblyPoint").val()   //工位
            },
            success: function(response) {
                if (response.success) {
                    ajaxQuery(1);
                    emptyNewModal();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    //函数：编辑操作
    function ajaxEdit() {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_TOOLSUSER,
            data: {
                "type": 'updates',
                "id": $("#editModal").data("id"),
                "lineId": $("#selectEditAssemblyLine").val(),
                "stageId": $("#selectEditAssemblyStage").val(),
                "assemblyPoint": $("#textEditAssemblyPoint").val(),
                "is_using": $("#checkboxEditStartUsing").attr("checked") === "checked" ? '-1' : '0a'
            },
            success: function(response) {
                if (response.success) {
                    ajaxQuery($(".curPage").attr("page"));
                    emptyEditModel();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    function ajaxEditLine() {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_TOOLSUSER,
            data: {
                "type": 'updates',
                "id": $("#editModal").data("id"),
                "assemblyPoint": $("#textEditLineAssemblyLine").val(),
                "stageId": 0,
                "is_using": $("#checkboxEditLineStartUsing").attr("checked") === "checked" ? '-1' : '0a'
            },
            success: function(response) {
                if (response.success) {
                    ajaxQuery($(".curPage").attr("page"));
                    emptyEditModel();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    function ajaxEditStage() {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_TOOLSUSER,
            data: {
                "type": 'updates',
                "id": $("#editModal").data("id"),
                "stageId": $("#selectEditStageAssemblyLine").val(), //你类Id
                "assemblyPoint": $("#textEditStageAssemblyStage").val(),
                "is_using": $("#checkboxEditStageStartUsing").attr("checked") === "checked" ? '-1' : '0a'
            },
            success: function(response) {
                if (response.success) {
                    ajaxQuery($(".curPage").attr("page"));
                    emptyEditModel();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    //函数：删除操作
    function ajaxDelete(Id) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_TOOLSUSER,
            data: {
                "type": 'deletes',
                "id": Id
            },
            success: function(response) {
                if (response.success) {
                    ajaxQuery(1);
                } else {
                    alert(respose.message);
                }
            },
            error: function() {
                alertError();
            }

        });
    }
    //函数：清空添加表单
    function emptyNewModal() {
        $("#textAddAssemblyPoint").val("");
    }
    //函数：清空编辑表单
    function emptyEditModel() {
        $("#editModal").data("id", 0);
        $("#textEditAssemblyPoint").val("");
        $('#checkboxEditStartUsing').attr("checked", false);
    }

    //分页
    $(".firstPage").click(
            function() {
                ajaxQuery(1);
            }
    );

    $(".prePage").click(
            function() {
                ajaxQuery(parseInt($(".curPage").attr("page")) - 1);
            }
    );

    $(".nextPage").click(
            function() {
                ajaxQuery(parseInt($(".curPage").attr("page")) + 1);
            }
    );

    $(".lastPage").click(
            function() {
                ajaxQuery(parseInt($(".lastPage").attr("page")));
            }
    );

});


