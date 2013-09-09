$(document).ready(function() {

    initPage();

    function initPage() {
        //add head and left current class
        $("#headtoolsLi").addClass("active");
        $("#leftToolsCheckLi").addClass("active");
        $("#newModal").modal("hide");
        $("#editModal").modal("hide");
        //初始化线别下拉框值
        selectLine('#selectLine', 0);
    }
    /////////////////////////////////////////////////////////////////////////////
    //初始化搜索条件
    //级联
    $("#selectLine").change(function() {  //工段
        if ($("#selectLine").val() == 0) {
            $("#selectStage").html("");
            $("#selectSeat").html("");
        } else {
            selectStage("#selectLine", "#selectStage", 0, 0);
        }
    });
    $("#selectStage").change(function() { //工位
        selectSeat("#selectStage", "#selectSeat", 0, 0);
    });
    //级联函数    
    //下拉框值获取
    function selectLine(obj_str, selectId) {  //线别
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
    function selectStage(obj_str, obj_str2, parentId, selectId) {   //工段
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
                            if (selectId === 0) {
                                strs = "<option value='0' selected='selected'>选择工段</option>";
                            }
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
    function selectSeat(obj_str, obj_str2, parentId, selectId) {   //工位
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
                    //console.log(response.data);
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
    ////////////////////////////////////////////////////////////////////////////
    //搜索 -- 汇总列表(左侧列表)
    $("#btnQuery").click(function() {
        ajaxQuery(1);
    });

    //工位配备 -- 数量 -- 搜索明细列表(右侧列表)
    $("#tableToolsManage tbody").delegate(".showListDetail", "click", function(e) {
        var obj = $(e.target).closest("tr");
        $("#isCheckedAll").data("toolsCode", obj.data("toolsCode"));     //保存toolsCode
        ajaxListQuery(obj.data("toolsCode"), 1);
        return false;
    });
    //////////////////////////////////////////////////////////////////////////////
    //工位配备 -- 工艺代码 -- 悬停显示详情 
    $("#tableToolsManage tbody").delegate(".showEditModal", "hover", function(e) {        
        $(".showEditModal").popover();
    });
    //明细--点检
    $("#isCheckedAll").click(function() {
        toolsNoCheckedAll($("#isCheckedAll").data("toolsCode"));
        return false;
    });
    $("#tableToolsList tbody").delegate(".checkid", "click", function(e) {
        var obj = $(e.target).closest("tr");
        toolsNoChecked(obj.data("id"),$("#isCheckedAll").data("toolsCode"));
        return false;
    });
    //明细--退库
    $("#tableToolsList tbody").delegate(".exitStocks", "click", function(e) {
        if (confirm("确定退库吗?")) {
            var obj = $(e.target).closest("tr");
            ToolsNoExitStocks(obj.data("id"),obj.data("toolsCode"));
        }
        return false;
    });

    //////////////////////////////////////////////////////////////////////////////

    //函数：查询结果
    function ajaxQuery(pageNumber) {
        var status_array = new Array();
        $('input[name="checkboxStatus"]:checked').each(function() {
            status_array.push($(this).val());//向数组中添加元素
        });
        var idstr = status_array.join(',');//将数组元素连接起来以构建一个字符串
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSCHECK,
            data: {
                "type": 'selects',
                "status": idstr,
                "selectSeat": $("#selectSeat").val(),
                "perPage": 15,
                "curPage": pageNumber || 1
            },
            success: function(response) {
                if (response.success) {
                    $("#tableToolsManage tbody").html("");
                    $.each(response.data.listData, function(index, value) {

                        var paramenter = value.paramenter_new.replace(/\|/g, "<br />");
                        var toolsType = '';
                        if(value.type == 1){
                            toolsType = "工具";
                        }else if(value.type == 2){
                            toolsType = "器具";
                        }else if(value.type == 3){
                            toolsType = "量具";
                        }
                        var imgsrc = "/bms/img/noImg.jpg";
                        if (value.imgsrc.length > 0) {
                            //imgsrc = value.imgsrc;
                            imgsrc = '../'+value.imgsrc;
                        }
                        var tr = $("<tr />");
                        var popoverstr = "<div id=\"viewModal\" style=\"width:450px\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr>";
                        popoverstr += "<td align=\"right\" width=\"120\">工艺代码：</td><td>"+value.tools_code+"</td>";
                        popoverstr += "<td width=\"160\" rowspan=\"7\"><img src=\""+imgsrc+"\" style=\"border:2px solid #4F81BD\"></td></tr>";
                        popoverstr += "<tr><td align=\"right\">物料编码：</td><td>"+value.material_code+"</td></tr>";
                        popoverstr += "<tr><td align=\"right\">类别：</td><td>"+toolsType+"</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具种类：</td><td>"+value.tools_type+"</td></tr>";
                        popoverstr += "<tr><td align=\"right\">品牌/制造商：</td><td>"+value.maker_name+"</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具名称：</td><td>"+value.name+"</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具型号：</td><td>"+value.model+"</td></tr>";
                        popoverstr += "</table><div class=\"control-group\"><div style=\"height: 25px; text-align:left; margin-left:5px; margin-right:20px; font-weight:bold; border-bottom: 1px solid #DDDDDD;\">参数</div>";
                        popoverstr += "<div style=\"text-align:left; margin-left:20px; margin-right:20px; margin-top: 5px\"><p>"+paramenter+"<br/></p></div></div>";
                        
                        $("<td />").html(index + 1).appendTo(tr);
                        $("<td />").html("<a class='showEditModal' data-original-title='工具详情' data-content='"+popoverstr+"'>" + value.tools_code + "</a>").appendTo(tr);
                        $("<td />").html(value.name).appendTo(tr);
                        $("<td />").html(value.applications).appendTo(tr);
                        $("<td />").html("<a class='showListDetail'>" + value.numes + "</a>").appendTo(tr);

                        tr.data("id", value.id);
                        tr.data("toolsCode", value.tools_code);
                        tr.data("materialCode", value.material_code);
                        tr.data("type", value.type);
                        tr.data("toolsType", value.tools_type);
                        tr.data("makerName", value.maker_name);
                        tr.data("toolsName", value.name);
                        tr.data("makerId", value.brand_maker);
                        tr.data("model", value.model);
                        tr.data("imgsrc", value.imgsrc);
                        tr.data("paramenter", value.paramenter);

                        $("#tableToolsManage tbody").append(tr);

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
    //函数：流水列表
    function ajaxListQuery(toolsCode, pageNumber) {
        var status_array = new Array();
        $('input[name="checkboxStatus"]:checked').each(function() {
            status_array.push($(this).val());//向数组中添加元素
        });
        var idstr = status_array.join(',');//将数组元素连接起来以构建一个字符串
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSCHECK,
            data: {
                "type": 'selectList',
                "toolsCode": toolsCode,
                "perPage": 15,
                "curPage": pageNumber || 1
            },
            success: function(response) {
                if (response.success) {
                    $("#tableToolsList tbody").html("");
                    $.each(response.data.listData, function(index, value) {

                        var tr = $("<tr />");

                        $("<td />").html(index + 1).appendTo(tr);
                        $("<td />").html(value.tools_no).appendTo(tr);
                        $("<td />").html(value.certificate).appendTo(tr);
                        $("<td />").html(value.recipient).appendTo(tr);
                        $("<td />").html(value.statusName).appendTo(tr);
                        $("<td />").html("<a class='checkid'>点检</a> &nbsp; <a class='exitStocks' title='退库'><img src='/bms/img/tools_del.gif' border='0'></a>").appendTo(tr);

                        tr.data("id", value.id);
                        tr.data("toolsCode", value.tools_code);
                        tr.data("tools_no", value.tools_no);
                        tr.data("status", value.status);
                        tr.data("distributor", value.distributor);
                        tr.data("operater", value.operater);
                        tr.data("cost", value.cost);
                        tr.data("spareCycles", value.spare_cycles);
                        tr.data("useCycles", value.use_cycles);
                        tr.data("warnCycles", value.warn_cycles);

                        tr.data("lineId", value.line_id);
                        tr.data("stageId", value.stage_id);
                        tr.data("positionId", value.position_id);
                        tr.data("recipient", value.recipient);
                        tr.data("entryTime", value.entry_time);
                        tr.data("addTime", value.add_time);

                        $("#tableToolsList tbody").append(tr);

                    });
                    //pager
                    $("#page2").show();
                    if (response.data.pager.curPage == 1) {
                        $(".prePage2").hide();
                    } else {
                        $(".prePage2").show();
                    }
                    if (response.data.pager.curPage >= Math.ceil(response.data.pager.total / response.data.pager.perPage)) {
                        $(".nextPage2").hide();
                        $(".lastPage2").hide();
                    } else {
                        $(".nextPage2").show();
                        $(".lastPage2").attr("page", Math.ceil(response.data.pager.total / response.data.pager.perPage));
                        $(".lastPage2").show();
                    }
                    $(".curPage2").attr("page", response.data.pager.curPage);
                    $("#curPage2").html(response.data.pager.curPage);
                    $("#page_attr").attr("pagecode", toolsCode);

                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    //函数：点检
    function toolsNoChecked(id,toolsCode) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSCHECK,
            data: {
                "type": 'checks',
                "id": id
            },
            success: function(response) {
                if (response.success) {
                    ajaxListQuery(toolsCode, $(".curPage2").attr("page"));
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    function toolsNoCheckedAll(toolsCode) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSCHECK,
            data: {
                "type": 'checksAll',
                "toolsCode": toolsCode
            },
            success: function(response) {
                if (response.success) {
                    ajaxListQuery(toolsCode, $(".curPage2").attr("page"));
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    //函数：退库
    function ToolsNoExitStocks(id,toolsCode) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSCHECK,
            data: {
                "type": 'exitStocks',
                "id": id
            },
            success: function(response) {
                if (response.success) {
                    ajaxListQuery(toolsCode, $(".curPage2").attr("page"));
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    //同一编号流水列表
    function ajaxtoolsNoListQuery(toolsNo) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": 'selectToolsNoList',
                "toolsNo": toolsNo,
                "perPage": 99999,
                "curPage": 1
            },
            success: function(response) {
                if (response.success) {
                    $("#tableToolsNoList tbody").html("");
                    $.each(response.data.listData, function(index, value) {

                        var tr = $("<tr />");
                        $("<td />").html(index + 1).appendTo(tr);
                        $("<td />").html(value.tools_no).appendTo(tr);
                        $("<td />").html(value.operateName).appendTo(tr);
                        $("<td />").html(value.operater).appendTo(tr);
                        $("<td />").html(value.add_time).appendTo(tr);
                        $("<td />").html(value.statusName).appendTo(tr);
                        $("<td />").html(value.lineName).appendTo(tr);
                        $("<td />").html(value.seat).appendTo(tr);
                        $("<td />").html(value.recipient).appendTo(tr);
                        $("<td />").html(value.certificate).appendTo(tr);

                        $("#tableToolsNoList tbody").append(tr);

                    });

                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
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

    //分页右
    $(".firstPage2").click(
            function() {
                ajaxListQuery($("#page_attr").attr("pagecode"), 1);
            }
    );

    $(".prePage2").click(
            function() {
                ajaxListQuery($("#page_attr").attr("pagecode"), parseInt($(".curPage2").attr("page")) - 1);
            }
    );

    $(".nextPage2").click(
            function() {
                ajaxListQuery($("#page_attr").attr("pagecode"), parseInt($(".curPage2").attr("page")) + 1);
            }
    );

    $(".lastPage2").click(
            function() {
                ajaxListQuery($("#page_attr").attr("pagecode"), parseInt($(".lastPage2").attr("page")));
            }
    );
});


