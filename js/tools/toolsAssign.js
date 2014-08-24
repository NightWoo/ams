$(document).ready(function() {

    initPage();

    function initPage() {
        //add head and left current class
        $("#headtoolsLi").addClass("active");
        $("#leftToolsAssignLi").addClass("active");
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
    //工位配备 -- 工艺代码 -- 悬停显示详情 
    $("#tableToolsManage tbody").delegate(".showDetailModal", "hover", function(e) {
        $(".showDetailModal").popover();
    });
    //////////////////////////////////////////////////////////////////////////////

    //新增
    $("#btnAdd").click(function() {
        if (!($("#selectSeat").val() > 0)) {
            alert("工位不能为空");
            return false;
        }
        $("#addSelectsList tbody").html("");
        $("#addSelectsList2 tbody").html("");
        $("#newModal").modal("show");
    });
    //新增 -- 搜索 -- 结果列表
    $("#newModalbtnQuery").click(function() {
        ajaxAddQuery(1);
        $("#btnAddMore").css("display", "none");
        $("#btnAddConfirm").css("display", "none");
    });
    //新增 -- 搜索 -- 结果列表 -- 悬停显示详情 
    $("#addSelectsList tbody").delegate(".showDetailModal", "hover", function(e) {
        $(".showDetailModal").popover();
    });
    //新增 -- 搜索 -- 结果列表 -- 检验凭证
    $("#btnAddCheck").click(function() {
        var checkFalseStr = '';
        $('input[name="checkboxAdds"]:checked').each(function() {
            ajaxCertificateAdd($("#textAddRecipient_" + $(this).val()).val(), "#textAddCertificate_" + $(this).val(), "#textAddCertificateSpan_" + $(this).val());
            if ($("#textAddRecipient_" + $(this).val()).val().length > 0 && $("#textAddCertificate_" + $(this).val()).val().length > 0) {
                //检测成功
            } else {
                //检测未通过
                checkFalseStr += $("#textAddRecipient_" + $(this).val()).val() + ',';
            }
        });
        if (checkFalseStr.length > 0) {
            alert('领用人：' + checkFalseStr + ' -- 检测未通过!');
            return false;
        }
        $("#btnAddMore").css("display", "");
        $("#btnAddConfirm").css("display", "");
        return false;
    });
    //继续新增
    $("#btnAddMore").click(function() {
        ajaxAdd();
        return false;
    });
    //新增 -- 搜索 -- 结果列表 -- 确定新增
    $("#btnAddConfirm").click(function() {
        ajaxAdd();
        return false;
    });

    //------------------------- 加号+ --------------------------------------------------

    //工位配备 -- 加号+ -- 新增工具明细 -- 弹框及初始化搜索
    $("#tableToolsManage tbody").delegate(".addDetails", "click", function(e) {
        $("#addSelectsList tbody").html("");
        $("#addSelectsList").data("addToolsCode", $(e.target).closest("tr").data("toolsCode"));     //保存，在添加后刷新页面用
        ajaxAddQuery2($(e.target).closest("tr").data("toolsCode"), 1);
        $("#newModal2").modal("show");
        $("#btnAddMore2").css("display", "none");
        $("#btnAddConfirm2").css("display", "none");
    });
    //加号+ 新增 -- 搜索 -- 结果列表 -- 悬停显示详情 
    $("#addSelectsList2 tbody").delegate(".showDetailModal", "hover", function(e) {
        $(".showDetailModal").popover();
    });
    //加号 -- 搜索 -- 结果列表 -- 检验凭证
    $("#btnAddCheck2").click(function() {
        var checkFalseStr = '';
        $('input[name="checkboxAdds"]:checked').each(function() {
            ajaxCertificateAdd($("#textAddRecipient_" + $(this).val()).val(), "#textAddCertificate_" + $(this).val(), "#textAddCertificateSpan_" + $(this).val());
            if ($("#textAddRecipient_" + $(this).val()).val().length > 0 && $("#textAddCertificate_" + $(this).val()).val().length > 0) {
                //检测成功
            } else {
                //检测未通过
                checkFalseStr += $("#textAddRecipient_" + $(this).val()).val() + ',';
            }
        });
        if (checkFalseStr.length > 0) {
            alert('领用人：' + checkFalseStr + ' -- 检测未通过!');
            return false;
        }
        $("#btnAddMore2").css("display", "");
        $("#btnAddConfirm2").css("display", "");
        return false;
    });
    //加号+ 新增 -- 搜索 -- 结果列表 -- 确定新增
    $("#btnAddConfirm2").click(function() {
        ajaxAdd();
        return false;
    });
    //加号+ 继续新增
    $("#btnAddMore2").click(function() {
        ajaxAdd();
        return false;
    });

    //----------------------- 交换 ----------------------------------------------------

    //交换 

    //显示明细 -- 工具编号 -- 显示 交换弹框列表
    $("#tableToolsList tbody").delegate(".changeDetails", "click", function(e) {
        var obj = $(e.target).closest("tr");
        $("#changeDetailModal").data("id", obj.data("id"));                     //保存id
        $("#changeDetailModal").data("toolsCode", obj.data("toolsCode"));       //保存toolsCode
        $("#changeDetailModal").modal("show");
    });
    //交换 -- 搜索 -- 结果列表
    $("#changeDetailModalBtnQuery").click(function() {
        ajaxExchangeQuery(1);
        $("#btnExchangeConfirm").css("display", "none");
    });
    //交换 -- 搜索 -- 结果列表 -- 检验凭证
    $("#btnExchangeCheck").click(function() {
        var checkFalseStr = '';
        $('input[name="radioAdds"]:checked').each(function() {
            ajaxCertificateAdd($("#textAddExchangeRecipient_" + $(this).val()).val(), "#textAddExchangeCertificate_" + $(this).val(), "#textAddExchangeCertificateSpan_" + $(this).val());
            if ($("#textAddExchangeRecipient_" + $(this).val()).val().length > 0 && $("#textAddExchangeCertificate_" + $(this).val()).val().length > 0) {
                //检测成功
            } else {
                //检测未通过
                checkFalseStr += $("#textAddExchangeRecipient_" + $(this).val()).val() + ',';
            }
        });
        if (checkFalseStr.length > 0) {
            alert('领用人：' + checkFalseStr + ' -- 检测未通过!');
            return false;
        }
        $("#btnExchangeConfirm").css("display", "");
        return false;
    });
    //交换 -- 搜索 -- 结果列表 -- 悬停显示详情 
    $("#tableExchangeList tbody").delegate(".showDetailModal", "hover", function(e) {
        $(".showDetailModal").popover();
    });
    //交换 -- 搜索 -- 结果列表 -- 确定交换
    $("#btnExchangeConfirm").click(function() {
        ajaxAddExchange($("#changeDetailModal").data("toolsCode"), $("#changeDetailModal").data("id"));
        return false;
    });

    //////////////////////////////////////////////////////////////////////////////
    //显示明细 -- 工具编号 -- 显示No流水列表
    $("#tableToolsList tbody").delegate(".showToolsNoListModal", "click", function(e) {
        var obj = $(e.target).closest("tr");
        ajaxtoolsNoListQuery(obj.data("tools_no"));
        $("#toolsNoListModal").modal("show");
    });

    //显示明细 -- 工具状态 -- 编辑框  -- 显示并初始化 
    $("#tableToolsList tbody").delegate(".statusInfo", "click", function(e) {
        var obj = $(e.target).closest("tr");
        $("#toolsNoListEditModal").data("id", obj.data("id"));                              //保存本行id
        $("#toolsNoListEditModal").data("toolsNo", obj.data("tools_no"));                  //保存工具编号
        $("#toolsNoListEditModal").data("toolsCode", obj.data("toolsCode"));                //保存工艺代码

        $("#toolsNoListEditModal").data("status", obj.data("status"));                      //保存状态
        $("#toolsNoListEditModal").data("positionId", obj.data("positionId"));              //保存工位
        $("#toolsNoListEditModal").data("recipient", obj.data("recipient"));                //保存领用人
        $("#toolsNoListEditModal").data("entryTime", obj.data("entryTime"));                //保存工具入库时间
        $("#toolsNoListEditModal").data("addTime", obj.data("addTime"));                    //保存状态起始时间
        if (obj.data("status") > 0) {
            //$("#toolsNoListEditModalStatus")[0].selectedIndex = 1;                    //初始化状态第二个选中
            $("#toolsNoListEditModalStatus").attr("value", obj.data("status"));
        }
        selectDistributor('#toolsNoListEditModalDistributor', obj.data("distributor")); //供应商
        $("#toolsNoListEditModalOperater").val(obj.data("operater"));                   //验收人
        $("#toolsNoListEditModalCost").val(obj.data("cost"));                           //成本
        $("#toolsNoListEditModalSpareCycles").val(obj.data("spareCycles"));             //闲置点检周期
        $("#toolsNoListEditModalUseCycles").val(obj.data("useCycles"));                 //使用点检周期
        $("#toolsNoListEditModalWarnCycles").val(obj.data("warnCycles"));               //警告周期

        selectLine('#toolsNoListEditModalLine', obj.data("lineId"));                    //初始化线别
        selectStage("#toolsNoListEditModalLine", "#toolsNoListEditModalStage", obj.data("lineId"), obj.data("stageId"));        //初始化工段
        selectSeat("#toolsNoListEditModalStage", "#toolsNoListEditModalSeat", obj.data("stageId"), obj.data("positionId"));     //工位

        $("#toolsNoListEditModalRecipient").val(obj.data("recipient"));                 //领用人
        $("#toolsNoListEditModalCertificate").val(obj.data("certificate"));             //凭证隐藏域
        $("#toolsNoListEditModalCertificateSpan").html('&nbsp;' + obj.data("certificate"));             //显示凭证
        //初始化关键指标
        $("#toolsNoListEditModalIndexMeasure tbody").html("");
        if ( !(obj.data("indexMeasure") == undefined) && obj.data("indexMeasure").length > 0) { 
            var paramenterData = obj.data("indexMeasure").split('|');
            var para_name = '<input type=\"text\" name=\"editTableIndexMeasureName\" class=\"input-small\" >';
            var para_val = '<input type=\"text\" name=\"editTableIndexMeasureValue\" class=\"input-small\" >';
            var para_unit = '<input type=\"text\" name=\"editTableIndexMeasureUnit\" class=\"input-small\" >';
            var val = '';
            $.each(paramenterData, function(index, value) {
                var tr = $("<tr />");

                val = value.split(";");
                if (typeof(val[0]) != 'undefined' && val[0].length > 0) {
                    para_name = '<input type=\"text\" name=\"editTableIndexMeasureName\" class=\"input-small\" value=\"' + val[0] + '\">';
                }
                if (typeof(val[1]) != 'undefined' && val[1].length > 0) {
                    para_val = '<input type=\"text\" name=\"editTableIndexMeasureValue\" class=\"input-small\" value=\"' + val[1] + '\">';
                }
                if (typeof(val[2]) != 'undefined' && val[2].length > 0) {
                    para_unit = '<input type=\"text\" name=\"editTableIndexMeasureUnit\" class=\"input-small\" value=\"' + val[2] + '\">';
                }
                $("<td />").html(index + 1).appendTo(tr);
                $("<td />").html(para_name).appendTo(tr);
                $("<td />").html(para_val).appendTo(tr);
                $("<td />").html(para_unit).appendTo(tr);
                $("#toolsNoListEditModalIndexMeasure tbody").append(tr);
            });
        }

        $("#toolsNoListEditModal").modal("show");
    });

    //显示明细 -- 工具状态 -- 编辑框  -- 验证凭证
    $("#toolsNoListEditModalRecipient").change(function() {
        if ($("#toolsNoListEditModalRecipient").val().length > 0) {
            $("#toolsNoListEditModalCertificate").val("");
            $("#toolsNoListEditModalCertificateSpan").html("<font color=red>检验中...</font>");
            $("#btnToolsNoEditConfirm").css("display", "none");
            ajaxCertificate($("#toolsNoListEditModalRecipient").val());
        }
    });
    //显示明细 -- 工具状态 -- 编辑框 -- 添加一行 关键指标
    $("#editModalAddOneIndexMeasure").click(function() {
        var para_name = '<input type=\"text\" name=\"editTableIndexMeasureName\" class=\"input-small\" >';
        var para_val = '<input type=\"text\" name=\"editTableIndexMeasureValue\" class=\"input-small\" >';
        var para_unit = '<input type=\"text\" name=\"editTableIndexMeasureUnit\" class=\"input-small\" >';
        var tr = $("<tr />");
        $("<td />").html($("#toolsNoListEditModalIndexMeasure tbody tr").size() + 1).appendTo(tr);
        $("<td />").html(para_name).appendTo(tr);
        $("<td />").html(para_val).appendTo(tr);
        $("<td />").html(para_unit).appendTo(tr);
        $("#toolsNoListEditModalIndexMeasure tbody").append(tr);
        return false;
    });

    //显示明细 -- 工具状态 -- 编辑框  -- 确认编辑
    $("#btnToolsNoEditConfirm").click(function() {
        if ($("#toolsNoListEditModalSeat").val() == '' || $("#toolsNoListEditModalWarnRecipient").val() == '') {
            alert('各项参数不能为空');
            return false;
        }
        if ($("#toolsNoListEditModalStatus").val() != $("#toolsNoListEditModal").data("status")) {
            //新增
            $("#toolsNoListEditModalType").val('ToolsNoAdd');
        } else {
            if ($("#toolsNoListEditModalSeat").val() != $("#toolsNoListEditModal").data("positionId") || $("#toolsNoListEditModalRecipient").val() != $("#toolsNoListEditModal").data("recipient")) {
                //新增，且不改addTime
                $("#toolsNoListEditModalType").val('ToolsNoAdd2');
            } else {
                //更新
                $("#toolsNoListEditModalType").val('ToolsNoUpdate');
            }
        }
        var IndexMeasureStr = null;
        var ParameterName = '';
        var ParameterVal = '';
        var ParameterUnit = '';
        for (var i = 0; i < $("#toolsNoListEditModalIndexMeasure tbody tr").size(); i++) {
            ParameterName = (typeof($('input[name="editTableIndexMeasureName"]').eq(i).val()) != 'undefined') ? $('input[name="editTableIndexMeasureName"]').eq(i).val() : '';
            ParameterVal = (typeof($('input[name="editTableIndexMeasureValue"]').eq(i).val()) != 'undefined') ? $('input[name="editTableIndexMeasureValue"]').eq(i).val() : '';
            ParameterUnit = (typeof($('input[name="editTableIndexMeasureUnit"]').eq(i).val()) != 'undefined') ? $('input[name="editTableIndexMeasureUnit"]').eq(i).val() : '';
            if (ParameterName.length > 0 || ParameterVal.length > 0 || ParameterUnit.length > 0) {
                if (i === 0) {
                    IndexMeasureStr = ParameterName + ';' + ParameterVal + ';' + ParameterUnit;
                } else {
                    IndexMeasureStr += '|' + ParameterName + ';' + ParameterVal + ';' + ParameterUnit;
                }
            }
        }
        ajaxToolsNoEdit(IndexMeasureStr);
        $("#toolsNoListEditModal").modal("hide");
        return false;
    });
    //显示明细 -- 工具状态 -- 编辑框  -- 级联变化
    $("#toolsNoListEditModalLine").change(function() {  //工段
        if ($("#toolsNoListEditModalLine").val() == 0) {
            $("#toolsNoListEditModalStage").html("");
            $("#toolsNoListEditModalSeat").html("");
        } else {
            selectStage("#toolsNoListEditModalLine", "#toolsNoListEditModalStage", 0, 0);
        }
    });
    $("#toolsNoListEditModalStage").change(function() { //工位
        selectSeat("#toolsNoListEditModalStage", "#toolsNoListEditModalSeat", $("#toolsNoListEditModalStage").val(), $("#toolsNoListEditModal").data("positionId"));
    });

    //明细--退库
    $("#tableToolsList tbody").delegate(".exitStocks", "click", function(e) {
        if (confirm("确定退库吗?")) {
            var obj = $(e.target).closest("tr");
            ToolsNoExitStocks(obj.data("id"), obj.data("toolsCode"));
        }
        return false;
    });
    //////////////////////////////////////////////////////////////////////////////////
    //制造商下拉框值获取
    function selectMaker(obj_str, selectId) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": 'selectMaker'
            },
            success: function(response) {
                if (response.success) {
                    var strs = "<option value='0'>选择制造商</option>";
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
    //供应商下拉框值获取
    function selectDistributor(obj_str, selectId) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": 'selectDistributor'
            },
            success: function(response) {
                if (response.success) {
                    var strs = "<option value='0'>选择供应商</option>";
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
    //函数：查询结果
    function ajaxQuery(pageNumber) {

        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSASSIGN,
            data: {
                "type": 'selects',
                "status": '1,1.2,1.3',
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
                        if (value.type == 1) {
                            toolsType = "工具";
                        } else if (value.type == 2) {
                            toolsType = "器具";
                        } else if (value.type == 3) {
                            toolsType = "量具";
                        }
                        var imgsrc = "/bms/img/noImg.jpg";
                        if (value.imgsrc.length > 0) {
                            //imgsrc = value.imgsrc;
                            imgsrc = '../' + value.imgsrc;
                        }
                        var tr = $("<tr />");
                        var popoverstr = "<div id=\"viewModal\" style=\"width:450px\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr>";
                        popoverstr += "<td align=\"right\" width=\"120\">工艺代码：</td><td>" + value.tools_code + "</td>";
                        popoverstr += "<td width=\"160\" rowspan=\"7\"><img src=\"" + imgsrc + "\" style=\"border:2px solid #4F81BD\"></td></tr>";
                        popoverstr += "<tr><td align=\"right\">物料编码：</td><td>" + value.material_code + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">类别：</td><td>" + toolsType + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具种类：</td><td>" + value.tools_type + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">品牌/制造商：</td><td>" + value.maker_name + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具名称：</td><td>" + value.name + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具型号：</td><td>" + value.model + "</td></tr>";
                        popoverstr += "</table><div class=\"control-group\"><div style=\"height: 25px; text-align:left; margin-left:5px; margin-right:20px; font-weight:bold; border-bottom: 1px solid #DDDDDD;\">参数</div>";
                        popoverstr += "<div style=\"text-align:left; margin-left:20px; margin-right:20px; margin-top: 5px\"><p>" + paramenter + "<br/></p></div></div>";

                        $("<td />").html(index + 1).appendTo(tr);
                        $("<td />").html("<a class='showDetailModal' data-original-title='工具详情' data-content='" + popoverstr + "'>" + value.tools_code + "</a>").appendTo(tr);
                        $("<td />").html(value.name).appendTo(tr);
                        $("<td />").html(value.applications).appendTo(tr);
                        $("<td />").html("<a class='showListDetail'>" + value.numes + "</a>").appendTo(tr);
                        $("<td />").html("<a class=\"addDetails\" href=\"#\"><span style=\"font-size: 28px; font-weight:bold; color: #51A351;\">+</span></a>").appendTo(tr);

                        tr.data("id", value.id);
                        tr.data("toolsCode", value.tools_code);
                        tr.data("toolsName", value.name);
                        tr.data("makerId", value.brand_maker);
                        tr.data("makerName", value.maker_name);
                        tr.data("model", value.model);
                        tr.data("materialCode", value.material_code);
                        tr.data("type", value.type);
                        tr.data("toolsType", value.tools_type);
                        tr.data("imgsrc", imgsrc);
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
    //函数：新增弹框 -- 查询结果
    function ajaxAddQuery(pageNumber) {

        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSASSIGN,
            data: {
                "type": 'addSelects',
                "status": '1',
                "selectStage": $("#selectStage").val(),
                "selectSeat": $("#selectSeat").val(),
                "toolsCode": $("#toolsCode").val(),
                "toolsName": $("#toolsName").val(),
                "toolsType": $("#toolsType").val(),
                "brandMaker": $("#brandMaker").val(),
                "toolsModel": $("#toolsModel").val(),
                "perPage": 9999,
                "curPage": pageNumber || 1
            },
            success: function(response) {
                if (response.success) {
                    $("#addSelectsList tbody").html("");
                    $.each(response.data.listData, function(index, value) {

                        var paramenter = value.paramenter_new.replace(/\|/g, "<br />");
                        var toolsType = '';
                        if (value.type == 1) {
                            toolsType = "工具";
                        } else if (value.type == 2) {
                            toolsType = "器具";
                        } else if (value.type == 3) {
                            toolsType = "量具";
                        }
                        var imgsrc = "/bms/img/noImg.jpg";
                        if (value.imgsrc.length > 0) {
                            //imgsrc = value.imgsrc;
                            imgsrc = '../' + value.imgsrc;
                        }
                        var tr = $("<tr />");
                        var popoverstr = "<div id=\"viewModal\" style=\"width:450px\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr>";
                        popoverstr += "<td align=\"right\" width=\"120\">工艺代码：</td><td>" + value.tools_code + "</td>";
                        popoverstr += "<td width=\"160\" rowspan=\"7\"><img src=\"" + imgsrc + "\" style=\"border:2px solid #4F81BD\"></td></tr>";
                        popoverstr += "<tr><td align=\"right\">物料编码：</td><td>" + value.material_code + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">类别：</td><td>" + toolsType + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具种类：</td><td>" + value.tools_type + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">品牌/制造商：</td><td>" + value.maker_name + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具名称：</td><td>" + value.name + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具型号：</td><td>" + value.model + "</td></tr>";
                        popoverstr += "</table><div class=\"control-group\"><div style=\"height: 25px; text-align:left; margin-left:5px; margin-right:20px; font-weight:bold; border-bottom: 1px solid #DDDDDD;\">参数</div>";
                        popoverstr += "<div style=\"text-align:left; margin-left:20px; margin-right:20px; margin-top: 5px\"><p>" + paramenter + "<br/></p></div></div>";

                        $("<td />").html("<input type=\"checkbox\" name=\"checkboxAdds\" value=\"" + value.listId + "\">").appendTo(tr);
                        $("<td />").html("<a class='showDetailModal' data-original-title='工具详情' data-content='" + popoverstr + "'>" + value.tools_no + "</a>").appendTo(tr);
                        $("<td />").html(value.tools_type).appendTo(tr);
                        $("<td />").html(value.name).appendTo(tr);
                        $("<td />").html(value.maker_name).appendTo(tr);
                        $("<td />").html(value.model).appendTo(tr);
                        $("<td />").html("<input type=\"text\" class=\"input-small\" id=\"textAddRecipient_" + value.listId + "\" value=\"" + value.recipient + "\" style=\"height: 20px;border:1px solid #666\">").appendTo(tr);
                        $("<td />").html("<input type=\"hidden\" class=\"input-small\" id=\"textAddCertificate_" + value.listId + "\" value=\"\"><span id=\"textAddCertificateSpan_" + value.listId + "\">" + value.certificate + "</span>").appendTo(tr);

                        tr.data("id", value.id);
                        tr.data("toolsCode", value.tools_code);
                        tr.data("toolsName", value.name);
                        tr.data("makerId", value.brand_maker);
                        tr.data("makerName", value.maker_name);
                        tr.data("model", value.model);
                        tr.data("materialCode", value.material_code);
                        tr.data("type", value.type);
                        tr.data("toolsType", value.tools_type);
                        tr.data("imgsrc", imgsrc);
                        tr.data("paramenter", value.paramenter);

                        $("#addSelectsList tbody").append(tr);

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
    //函数：加号+ 弹框 -- 查询结果
    function ajaxAddQuery2(toolsCode, pageNumber) {

        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSASSIGN,
            data: {
                "type": 'addSelects',
                "status": '1',
                "selectStage": $("#selectStage").val(),
                "selectSeat": $("#selectSeat").val(),
                "toolsCode": toolsCode,
                "perPage": 9999,
                "curPage": pageNumber || 1
            },
            success: function(response) {
                if (response.success) {
                    $("#addSelectsList2 tbody").html("");
                    $.each(response.data.listData, function(index, value) {

                        var paramenter = value.paramenter_new.replace(/\|/g, "<br />");
                        var toolsType = '';
                        if (value.type == 1) {
                            toolsType = "工具";
                        } else if (value.type == 2) {
                            toolsType = "器具";
                        } else if (value.type == 3) {
                            toolsType = "量具";
                        }
                        var imgsrc = "/bms/img/noImg.jpg";
                        if (value.imgsrc.length > 0) {
                            //imgsrc = value.imgsrc;
                            imgsrc = '../' + value.imgsrc;
                        }
                        var tr = $("<tr />");
                        var popoverstr = "<div id=\"viewModal\" style=\"width:450px\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr>";
                        popoverstr += "<td align=\"right\" width=\"120\">工艺代码：</td><td>" + value.tools_code + "</td>";
                        popoverstr += "<td width=\"160\" rowspan=\"7\"><img src=\"" + imgsrc + "\" style=\"border:2px solid #4F81BD\"></td></tr>";
                        popoverstr += "<tr><td align=\"right\">物料编码：</td><td>" + value.material_code + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">类别：</td><td>" + toolsType + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具种类：</td><td>" + value.tools_type + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">品牌/制造商：</td><td>" + value.maker_name + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具名称：</td><td>" + value.name + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具型号：</td><td>" + value.model + "</td></tr>";
                        popoverstr += "</table><div class=\"control-group\"><div style=\"height: 25px; text-align:left; margin-left:5px; margin-right:20px; font-weight:bold; border-bottom: 1px solid #DDDDDD;\">参数</div>";
                        popoverstr += "<div style=\"text-align:left; margin-left:20px; margin-right:20px; margin-top: 5px\"><p>" + paramenter + "<br/></p></div></div>";

                        $("<td />").html("<input type=\"checkbox\" name=\"checkboxAdds\" value=\"" + value.listId + "\">").appendTo(tr);
                        $("<td />").html("<a class='showDetailModal' data-original-title='工具详情' data-content='" + popoverstr + "'>" + value.tools_no + "</a>").appendTo(tr);
                        $("<td />").html(value.tools_type).appendTo(tr);
                        $("<td />").html(value.name).appendTo(tr);
                        $("<td />").html(value.maker_name).appendTo(tr);
                        $("<td />").html(value.model).appendTo(tr);
                        $("<td />").html("<input type=\"text\" class=\"input-small\" id=\"textAddRecipient_" + value.listId + "\" value=\"" + value.recipient + "\" style=\"height: 20px;border:1px solid #666\">").appendTo(tr);
                        $("<td />").html("<input type=\"hidden\" class=\"input-small\" id=\"textAddCertificate_" + value.listId + "\" value=\"\"><span id=\"textAddCertificateSpan_" + value.listId + "\">" + value.certificate + "</span>").appendTo(tr);

                        tr.data("id", value.id);
                        tr.data("toolsCode", value.tools_code);
                        tr.data("toolsName", value.name);
                        tr.data("makerId", value.brand_maker);
                        tr.data("makerName", value.maker_name);
                        tr.data("model", value.model);
                        tr.data("materialCode", value.material_code);
                        tr.data("type", value.type);
                        tr.data("toolsType", value.tools_type);
                        tr.data("imgsrc", imgsrc);
                        tr.data("paramenter", value.paramenter);

                        $("#addSelectsList2 tbody").append(tr);

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
    //函数：流水列表
    function ajaxListQuery(toolsCode, pageNumber) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
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
                        $("<td />").html("<a class='showToolsNoListModal'>" + value.tools_no + "</a>").appendTo(tr);
                        $("<td />").html(value.certificate).appendTo(tr);
                        $("<td />").html(value.recipient).appendTo(tr);
                        $("<td />").html("<a class='statusInfo' title='" + value.date_cycles + "'>" + value.statusName + "</a>").appendTo(tr);
                        $("<td />").html("<a class=\"changeDetails\" href=\"#\"><img src=\"/bms/img/tools_up.gif\" border=\"0\"></a> &nbsp; <a class='exitStocks' href=\"#\"><img src=\"/bms/img/tools_del.gif\" border=\"0\"></a>").appendTo(tr);

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
                        tr.data("certificate", value.certificate);
                        tr.data("indexMeasure", value.index_measure);
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
    //函数：退库
    function ToolsNoExitStocks(id, toolsCode) {
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
    //函数：工具状态 -- 管理工具 -- 编辑操作
    function ajaxToolsNoEdit(IndexMeasureStr) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": $("#toolsNoListEditModalType").val(),
                "id": $("#toolsNoListEditModal").data("id"),
                "status": $("#toolsNoListEditModalStatus").val(),
                "distributor": $("#toolsNoListEditModalDistributor").val(),
                "operater": $("#toolsNoListEditModalOperater").val(),
                "cost": $("#toolsNoListEditModalCost").val(),
                "spareCycles": $("#toolsNoListEditModalSpareCycles").val(),
                "useCycles": $("#toolsNoListEditModalUseCycles").val(),
                "warnCycles": $("#toolsNoListEditModalWarnCycles").val(),
                "lineId": $("#toolsNoListEditModalLine").val(),
                "stageId": $("#toolsNoListEditModalStage").val(),
                "positionId": $("#toolsNoListEditModalSeat").val(),
                "recipient": $("#toolsNoListEditModalRecipient").val(),
                "certificate": $("#toolsNoListEditModalCertificate").val(),
                "indexMeasure": IndexMeasureStr,
                "entryTime": $("#toolsNoListEditModal").data("entryTime"),
                "addTime": $("#toolsNoListEditModal").data("addTime")
            },
            success: function(response) {
                if (response.success) {
                    ajaxListQuery($("#toolsNoListEditModal").data("toolsCode"), $(".curPage2").attr("page"));
                    emptyToolsNoEditModel();
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
        var id_array = new Array();
        var Recipient_array = new Array();
        var Certificate_array = new Array();
        $('input[name="checkboxAdds"]:checked').each(function() {
            if ($("#textAddCertificate_" + $(this).val()).val().length > 0) {   //保证只添加通过检测的
                id_array.push($(this).val());//向数组中添加元素
                Recipient_array.push($("#textAddRecipient_" + $(this).val()).val());
                Certificate_array.push($("#textAddCertificate_" + $(this).val()).val());
            }
        });
        var idstr = id_array.join(',');//将数组元素连接起来以构建一个字符串
        var Recipientstr = Recipient_array.join(',');//将数组元素连接起来以构建一个字符串
        var Certificatestr = Certificate_array.join(',');//将数组元素连接起来以构建一个字符串
        if(Certificatestr.length == 0){
            alert('请勾选【检验通过】的记录');
            return false;
        }
        //alert(idstr + "|" + Recipientstr + "|" + Certificatestr);
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSASSIGN,
            data: {
                "type": 'adds',
                "noListId": idstr,
                "recipient": Recipientstr,
                "certificate": Certificatestr
            },
            success: function(response) {
                if (response.success) {
                    if (typeof($("#addSelectsList").data("addToolsCode")) != 'undefined' && $("#addSelectsList").data("addToolsCode").length > 0){
                        ajaxAddQuery2($("#addSelectsList").data("addToolsCode"), 1);
                    }
                    if ($("#toolsCode").val().length > 0 || $("#toolsName").val().length > 0 || $("#toolsType").val().length > 0 || $("#brandMaker").val().length > 0 || $("#toolsModel").val().length > 0 ) {
                        ajaxAddQuery(1);
                    }
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }

    //函数：检查凭证
    function ajaxCertificate(Recipient) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": 'checkCertificate',
                "recipient": Recipient
            },
            success: function(response) {
                if (response.success) {
                    //console.log(response.data);
                    if (response.data.length > 0) {
                        $("#toolsNoListEditModalCertificate").val(response.data);
                        $("#toolsNoListEditModalCertificateSpan").html(response.data + "&nbsp;&nbsp;检验通过");
                        $("#btnToolsNoEditConfirm").css("display", "");
                    } else {
                        $("#toolsNoListEditModalCertificateSpan").html("&nbsp;&nbsp;<font color=green>凭证不存在,检验未通过<font>");
                    }
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }
    function ajaxCertificateAdd(Recipient, CertificateInputId, CertificateSpanId) {
        $.ajax({
            type: "get",
            async: false,
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": 'checkCertificate',
                "recipient": Recipient
            },
            success: function(response) {
                if (response.success) {
                    //console.log(response.data);
                    if (response.data.length > 0) {
                        $(CertificateInputId).val(response.data);
                        $(CertificateSpanId).html(response.data + "&nbsp;&nbsp;检验通过");
                    } else {
                        $(CertificateInputId).val("");
                        $(CertificateSpanId).html("&nbsp;&nbsp;<font color=green>检验未通过<font>");
                    }
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }

    //函数：交换弹框 -- 查询结果
    function ajaxExchangeQuery(pageNumber) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSASSIGN,
            data: {
                "type": 'addSelects',
                "status": '1',
                "selectStage": $("#selectStage").val(),
                "selectSeat": $("#selectSeat").val(),
                "toolsCode": $("#exchangeToolsCode").val(),
                "toolsName": $("#exchangeToolsName").val(),
                "toolsType": $("#exchangeToolsType").val(),
                "brandMaker": $("#exchangeBrandMaker").val(),
                "toolsModel": $("#exchangeToolsModel").val(),
                "perPage": 9999,
                "curPage": pageNumber || 1
            },
            success: function(response) {
                if (response.success) {
                    $("#tableExchangeList tbody").html("");
                    $.each(response.data.listData, function(index, value) {

                        var paramenter = value.paramenter_new.replace(/\|/g, "<br />");
                        var toolsType = '';
                        if (value.type == 1) {
                            toolsType = "工具";
                        } else if (value.type == 2) {
                            toolsType = "器具";
                        } else if (value.type == 3) {
                            toolsType = "量具";
                        }
                        var imgsrc = "/bms/img/noImg.jpg";
                        if (value.imgsrc.length > 0) {
                            //imgsrc = value.imgsrc;
                            imgsrc = '../' + value.imgsrc;
                        }
                        var tr = $("<tr />");
                        var popoverstr = "<div id=\"viewModal\" style=\"width:450px\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr>";
                        popoverstr += "<td align=\"right\" width=\"120\">工艺代码：</td><td>" + value.tools_code + "</td>";
                        popoverstr += "<td width=\"160\" rowspan=\"7\"><img src=\"" + imgsrc + "\" style=\"border:2px solid #4F81BD\"></td></tr>";
                        popoverstr += "<tr><td align=\"right\">物料编码：</td><td>" + value.material_code + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">类别：</td><td>" + toolsType + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具种类：</td><td>" + value.tools_type + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">品牌/制造商：</td><td>" + value.maker_name + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具名称：</td><td>" + value.name + "</td></tr>";
                        popoverstr += "<tr><td align=\"right\">工具型号：</td><td>" + value.model + "</td></tr>";
                        popoverstr += "</table><div class=\"control-group\"><div style=\"height: 25px; text-align:left; margin-left:5px; margin-right:20px; font-weight:bold; border-bottom: 1px solid #DDDDDD;\">参数</div>";
                        popoverstr += "<div style=\"text-align:left; margin-left:20px; margin-right:20px; margin-top: 5px\"><p>" + paramenter + "<br/></p></div></div>";

                        $("<td />").html("<input type=\"radio\" name=\"radioAdds\" value=\"" + value.listId + "\">").appendTo(tr);
                        $("<td />").html("<a class='showDetailModal' data-original-title='工具详情' data-content='" + popoverstr + "'>" + value.tools_no + "</a>").appendTo(tr);
                        $("<td />").html(value.tools_type).appendTo(tr);
                        $("<td />").html(value.name).appendTo(tr);
                        $("<td />").html(value.maker_name).appendTo(tr);
                        $("<td />").html(value.model).appendTo(tr);
                        $("<td />").html("<input type=\"text\" class=\"input-small\" id=\"textAddExchangeRecipient_" + value.listId + "\" value=\"" + value.recipient + "\" style=\"height: 20px;border:1px solid #666\">").appendTo(tr);
                        $("<td />").html("<input type=\"hidden\" class=\"input-small\" id=\"textAddExchangeCertificate_" + value.listId + "\" value=\"\"><span id=\"textAddExchangeCertificateSpan_" + value.listId + "\">" + value.certificate + "</span>").appendTo(tr);

                        tr.data("id", value.id);
                        tr.data("toolsCode", value.tools_code);
                        tr.data("toolsName", value.name);
                        tr.data("makerId", value.brand_maker);
                        tr.data("makerName", value.maker_name);
                        tr.data("model", value.model);
                        tr.data("materialCode", value.material_code);
                        tr.data("type", value.type);
                        tr.data("toolsType", value.tools_type);
                        tr.data("imgsrc", imgsrc);
                        tr.data("paramenter", value.paramenter);

                        $("#tableExchangeList tbody").append(tr);

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
    //函数：交换 -- 添加操作
    function ajaxAddExchange(toolsCode, toolsId) {
        var id_array = new Array();
        var Recipient_array = new Array();
        var Certificate_array = new Array();
        $('input[name="radioAdds"]:checked').each(function() {
            if ($("#textAddExchangeCertificate_" + $(this).val()).val().length > 0) {   //保证只添加通过检测的
            id_array.push($(this).val());//向数组中添加元素
            Recipient_array.push($("#textAddExchangeRecipient_" + $(this).val()).val());
            Certificate_array.push($("#textAddExchangeCertificate_" + $(this).val()).val());
            }
        });
        var idstr = id_array.join(',');//将数组元素连接起来以构建一个字符串
        var Recipientstr = Recipient_array.join(',');//将数组元素连接起来以构建一个字符串
        var Certificatestr = Certificate_array.join(',');//将数组元素连接起来以构建一个字符串
        if(Certificatestr.length == 0){
            alert('请选择【检验通过】的记录');
            return false;
        }
        //alert(idstr + "|" + Recipientstr + "|" + Certificatestr);
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSASSIGN,
            data: {
                "type": 'addExchange',
                "noListId": idstr,
                "toolsId": toolsId,
                "recipient": Recipientstr,
                "certificate": Certificatestr
            },
            success: function(response) {
                if (response.success) {
                    $("#changeDetailModal").modal("hide");
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

    //函数：清空编辑表单
    function emptyToolsNoEditModel() {
        $("#toolsNoListEditModalOperater").val("");
        $("#toolsNoListEditModalSeat").val("");
        $("#toolsNoListEditModalRecipient").val("");
    }
});


