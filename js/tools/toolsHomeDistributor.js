$(document).ready(function() {

    initPage();
    //弹出新增框
    $("#btnAdd").click(function() {
        $("#newModal").modal("show");
    });
    //弹出编辑框
    $("#tableToolsList").live("click", function(e) {
        if ($(e.target).html() === "编辑") {
            var siblings = $(e.target).parent("td").siblings();
            $("#textEditDistributor").val(siblings[1].innerHTML);
            $("#textEditDisplayName").val(siblings[2].innerHTML);
            $("#textEditContact").val(siblings[3].innerHTML);
            $("#editModal").data("id", $(e.target).closest("tr").data("id"));
            if ($("#editModal").data("using" + $(e.target).closest("tr").data("id")) < 0) {
                $('#checkboxEditStartUsing').attr("checked", "checked");
                //console.log($("#editModal").data("using"+$(e.target).closest("tr").data("id")));
            } else {
                $('#checkboxEditStartUsing').attr("checked", false);
            }
            $("#editModal").modal("show");

        } else if ($(e.target).html() === "删除") {
            if (confirm('是否删除供应商？')) {
                ajaxDelete($(e.target).closest("tr").data("id"));
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
        if($("#textAddContact").val()==''||$("#textAddDistributor").val()==''){
            alert('联系方式或供应商为空');
            return false;
        }
        ajaxAdd();
        $("#newModal").modal("hide");
        return false;
    });
    //继续新增
    $("#btnAddMore").click(function() {
        if($("#textAddContact").val()==''||$("#textAddDistributor").val()==''){
            alert('联系方式或供应商为空');
            return false;
        }
        ajaxAdd();
        emptyNewModal();
        return false;
    });
    //确定编辑
    $("#btnEditConfirm").click(function() {
        if($("#textEditContact").val()==''||$("#textEditDistributor").val()==''){
            alert('联系方式或供应商为空');
            return false;
        }
        ajaxEdit();
        $("#editModal").modal("hide");
        return false;
    });

    //函数：初始化
    function initPage() {
        //add head and left current class
        $("#headtoolsLi").addClass("active");
        $("#leftToolsDataLi").addClass("active");
        $("#newModal").modal("hide");
        $("#editModal").modal("hide");
    }

    //函数：查询结果
    function ajaxQuery(pageNumber) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_DISTRIBUTOR,
            data: {
                "type": 'selects',
                "is_using": $("#checkboxStopUse").attr("checked") == "checked" ? '0a' : '-1', //0a，应为0，加a为防止php程序，判断时用了empty
                "distributor": $("#distributor").val(),
                "perPage": 20,
                "curPage": pageNumber || 1
            },
            success: function(response) {
                if (response.success) {
                    $("#tableToolsList tbody").html("");
                    $.each(response.data.listData, function(index, value) {
                        var tr = $("<tr />");
                        $("<td />").html(index+1).appendTo(tr);
                        $("<td />").html(value.name).appendTo(tr);
                        $("<td />").html(value.display_name).appendTo(tr);
                        $("<td />").html(value.contact).appendTo(tr);
                        if (value.is_using < 0) {
                            $("#editModal").data("using" + value.id, value.is_using);
                            //console.log($("#editModal").data("using"+value.id));
                        }
                        var editTd = $("<td />").html(" ¦ ");
                        $("<button />").addClass("btn-link").html("编辑").prependTo(editTd);
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
        })
    }
    //函数：添加操作
    function ajaxAdd() {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_DISTRIBUTOR,
            data: {
                "type": 'adds',
                "contact": $("#textAddContact").val(),
                "distributor": $("#textAddDistributor").val(),
                "displayName": $("#textAddDisplayName").val()
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
        })
    }
    //函数：编辑操作

    function ajaxEdit() {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_DISTRIBUTOR,
            data: {
                "type": 'updates',
                "id": $("#editModal").data("id"),
                "is_using": $("#checkboxEditStartUsing").attr("checked") === "checked" ?  '-1' : '0a' ,
                "contact": $("#textEditContact").val(),
                "distributor": $("#textEditDistributor").val(),
                "displayName": $("#textEditDisplayName").val()
            },
            success: function(response) {
                if (response.success) {
                    ajaxQuery(1);
                    //console.log($("#checkboxEditStartUsing").attr("checked"));
                    emptyEditModel();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        })
    }
    //函数：删除操作
    function ajaxDelete(providerId) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_HOME_DISTRIBUTOR,
            data: {
                "type": 'deletes',
                "id": providerId
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
        $("#textAddContact").val("");
        $("#textAddDistributor").val("");
        $("#textAddDisplayName").val("");
    }
    //函数：清空编辑表单
    function emptyEditModel() {
        $("#textEditContact").val("");
        $("#textEditDistributor").val("");
        $("#textEditDisplayName").val("");
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


