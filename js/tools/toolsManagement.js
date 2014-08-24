$(document).ready(function() {

    initPage();

    function initPage() {
        //add head and left current class
        $("#headtoolsLi").addClass("active");
        $("#leftToolsManagementLi").addClass("active");
        $("#newModal").modal("hide");
        $("#editModal").modal("hide");
        //初始化线别下拉框值
        selectLine('#selectLine', 0);
        ReturnSelectUnitArray();
    }
    /////////////////////////////////////////////////////////////////////////
    //新增弹框
    $("#btnAdd").click(function() {
        $("#newModal").modal("show");
        //初始化供应商下拉框值
        selectDistributor('#newModalSelectDistributor', 0);
        return false;
    });
    //检查工艺代码
    $("#checkToolsCode").click(function() {
        if ($("#newModaltextAddToolsCode").val().length === 0) {
            alert('工艺代码为空');
            return false;
        }
        ajaxCheckToolsCode();
        return false;
    });
    //导出汇总明细
    $("#exportToolsManagement").click(function() {
        if ($("#toolsCode").val().length === 0 && $("#materialCode").val().length === 0 && $("#toolsName").val().length === 0 && $("#toolsType").val().length === 0 && $("#brandMaker").val().length === 0 && $("#toolsModel").val().length === 0) {
            alert('工艺代码,物料编码,工具名称,工具种类,制造商,型号至少一项不为空');
            return false;
        }
        ajaxExportQuery();
        return false;
    });
    //新增 添加参数行
    $("#AddOneParameter").click(function() {
        var para_name = '<input type=\"text\" name=\"newModelTableParameterListName\" class=\"input-small\" >';
        var para_val = '<input type=\"text\" name=\"newModelTableParameterListValue\" class=\"input-small\" >';
        var para_unit = ReturnNewModelUnitArray(0);
        var tr = $("<tr />");
        $("<td />").html($("#tableParameterList tbody tr").size() + 1).appendTo(tr);
        $("<td />").html(para_name).appendTo(tr);
        $("<td />").html(para_val).appendTo(tr);
        $("<td />").html(para_unit).appendTo(tr);
        $("#tableParameterList tbody").append(tr);
        return false;
    });
    //确定新增工具
    $("#btnAddConfirm").click(function() {
        if (checkNewForm()) {
            ajaxAdd();
            emptyNewModal();
            $("#newModal").modal("hide");
        }
        return false;
    });
    //继续新增
    $("#btnAddMore").click(function() {
        if (checkNewForm()) {
            ajaxAdd();
            emptyNewModal();
        }
        return false;
    });

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

    //搜索 -- 汇总列表(左侧列表)
    $("#btnQuery").click(function() {
        ajaxQuery(1);
    });
    //汇总列表 -- 工艺代码 -- 编辑工具框 -> 显示并初始化 
//    $(".showEditModal").live("click", function() {
    $("#tableToolsManage tbody").delegate(".showEditModal", "click", function(e) {
        var obj = $(e.target).closest("tr");

        $("#editModal").data("id", obj.data("id"));                             //保存本行id
        $("#editModalTextEditToolsCode").val(obj.data("toolsCode"));            //初始化工艺代码
        $("#editModalTextEditToolsCode2").val(obj.data("toolsCode"));           //初始化工艺代码隐藏域
        $("#editModalTextEditMaterialCode").val(obj.data("materialCode"));      //初始化物料编码
        if (obj.data("type") > 0) {
            $("#editModalTextEditType")[0].selectedIndex = obj.data("type") - 1;   //初始化类别
        }
        $("#editModalTextEditToolsType").val(obj.data("toolsType"));                //初始化工具种类
        if (obj.data("makerId") > 0) {
            selectMaker('#editModalTextEditBrandMaker', obj.data("makerId"));       //品牌/制造商
        } else {
            selectMaker('#editModalTextEditBrandMaker', 0);                         //品牌/制造商
        }
        $("#editModalTextEditName").val(obj.data("toolsName"));                     //初始化工具名称
        $("#editModalTextEditModel").val(obj.data("model"));                        //初始化工具型号
        $("#editModalTextEditApplication").val(obj.data("application"));            //初始化工具型号
        $("#editModalTextEditUpfile").val(obj.data("imgsrc"));                      //初始化图片

        //初始化参数
        $("#editTableParameterList tbody").html("");
        if (!(obj.data("paramenter") == undefined) && obj.data("paramenter").length > 0) {
            var paramenterData = obj.data("paramenter").split('|');
            var para_name = '<input type=\"text\" name=\"editTableParameterListName\" class=\"input-small\" >';
            var para_val = '<input type=\"text\" name=\"editTableParameterListValue\" class=\"input-small\" >';
            var para_unit = ReturnUnitArray(0);
            var val = '';
            $.each(paramenterData, function(index, value) {
                var tr = $("<tr />");

                val = value.split(";");
                if (typeof(val[0]) != 'undefined' && val[0].length > 0) {
                    para_name = '<input type=\"text\" name=\"editTableParameterListName\" class=\"input-small\" value=\"' + val[0] + '\">';
                }
                if (typeof(val[1]) != 'undefined' && val[1].length > 0) {
                    para_val = '<input type=\"text\" name=\"editTableParameterListValue\" class=\"input-small\" value=\"' + val[1] + '\">';
                }
                if (typeof(val[2]) != 'undefined' && val[2].length > 0) {
                    para_unit = ReturnUnitArray(val[2]);
                }
                $("<td />").html(index + 1).appendTo(tr);
                $("<td />").html(para_name).appendTo(tr);
                $("<td />").html(para_val).appendTo(tr);
                $("<td />").html(para_unit).appendTo(tr);
                $("#editTableParameterList tbody").append(tr);
            });
        }
        $("#editModal").modal("show");
        return false;
    });
    /////////////////////////////////////////////////////////////////////////////////////////
    //汇总列表 -- 工艺代码 -- 悬停显示详情 
    $("#tableToolsManage tbody").delegate(".showEditModal", "hover", function(e) {
        $(".showEditModal").popover();
    });
    //汇总列表 -- 数量 -- 搜索明细列表(右侧列表)
//    $(".showListDetail").live("click", function() {
    $("#tableToolsManage tbody").delegate(".showListDetail", "click", function(e) {
        var obj = $(e.target).closest("tr");
        ajaxListQuery(obj.data("toolsCode"), 1);
        return false;
    });
    //汇总列表 -- 工艺代码 -- 编辑工具框 -- 添加参数行
    $("#editModalAddOneParameter").click(function() {
        var para_name = '<input type=\"text\" name=\"editTableParameterListName\" class=\"input-small\" >';
        var para_val = '<input type=\"text\" name=\"editTableParameterListValue\" class=\"input-small\" >';
        var para_unit = ReturnUnitArray(0);
        var tr = $("<tr />");
        $("<td />").html($("#editTableParameterList tbody tr").size() + 1).appendTo(tr);
        $("<td />").html(para_name).appendTo(tr);
        $("<td />").html(para_val).appendTo(tr);
        $("<td />").html(para_unit).appendTo(tr);
        $("#editTableParameterList tbody").append(tr);
        return false;
    });

    //汇总列表 -- 工艺代码 -- 编辑工具框 -- 确定编辑
    $("#btnEditConfirm").click(function() {
        if ($("#editModalTextEditMaterialCode").val() == '' || $("#editModalTextEditName").val() == '' || $("#editModalTextEditModel").val() == '') {
            alert('物料编码或工具种类或工具模型为空');
            return false;
        }
        var ParameterStr = null;
        var ParameterName = '';
        var ParameterVal = '';
        var ParameterUnit = '';
        for (var i = 0; i < $("#editTableParameterList tbody tr").size(); i++) {
            ParameterName = (typeof($('input[name="editTableParameterListName"]').eq(i).val()) != 'undefined') ? $('input[name="editTableParameterListName"]').eq(i).val() : '';
            ParameterVal = (typeof($('input[name="editTableParameterListValue"]').eq(i).val()) != 'undefined') ? $('input[name="editTableParameterListValue"]').eq(i).val() : '';
            ParameterUnit = (typeof($('select[name="editTableParameterListUnit"]').eq(i).val()) != 'undefined') ? $('select[name="editTableParameterListUnit"]').eq(i).val() : 0;
            if (ParameterName.length > 0 || ParameterVal.length > 0 || ParameterUnit.length > 0) {
                if (i === 0) {
                    ParameterStr = ParameterName + ';' + ParameterVal + ';' + ParameterUnit;
                } else {
                    ParameterStr += '|' + ParameterName + ';' + ParameterVal + ';' + ParameterUnit;
                }
            }
        }
        //console.log(ParameterStr);
        ajaxEdit(ParameterStr);
        $("#editModal").modal("hide");
        return false;
    });

    //////////////////////////////////////////////////////////////////////////
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
        if ($("#toolsNoListEditModalOperater").val() == '' || $("#toolsNoListEditModalCost").val() == '' || $("#toolsNoListEditModalSeat").val() == '' || $("#toolsNoListEditModalWarnRecipient").val() == '' || $("#toolsNoListEditModalCertificate").val() == '') {
            alert('各项参数不能为空');
            return false;
        }
        if ($("#toolsNoListEditModalStatus").val() != $("#toolsNoListEditModal").data("status")) {
            //新增
            $("#toolsNoListEditModalType").val('ToolsNoAdd');
        } else {
            if ($("#toolsNoListEditModalSeat").val() != $("#toolsNoListEditModal").data("positionId") || $("#toolsNoListEditModalRecipient").val() != $("#toolsNoListEditModal").data("recipient")) {
                //新增，便不改addTime
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

    //初始化 单位下拉框值
    function selectUnit(obj_str, selectId) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": 'selectUnit'
            },
            success: function(response) {
                if (response.success) {
                    var strs = "<option value='0'>选择单位</option>";
                    $(obj_str).html("");
                    $.each(response.data, function(index, value) {
                        if (selectId == value.id) {
                            strs += "<option value=" + value.id + " selected='selected'>" + value.unit + "</option>";
                        } else {
                            strs += "<option value=" + value.id + ">" + value.unit + "</option>";
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

    //获取单位下拉框值 数组
    function ReturnSelectUnitArray() {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": 'selectUnit'
            },
            success: function(response) {
                if (response.success) {
                    $("#editTableParameterList").data("unitArr", response.data);
                    //console.log($("#editTableParameterList").data("unitArr"));
                } else {
                    $("#editTableParameterList").data("unitArr", array());
                }
            },
            error: function() {
                alertError();
            }
        });

    }
    //处理单位数组[编辑]
    function ReturnUnitArray(selectId) {
        var Arr = $("#editTableParameterList").data("unitArr");          //初始化时，已经缓存的数据
        //console.log(Arr);
        var str = "<select name=\"editTableParameterListUnit\"  class=\"input-small\">";
        for (var i = 0; i < Arr.length; i++) {
            if (Arr[i]["id"] == selectId) {
                str += "<option value=" + Arr[i]["id"] + " selected=\"selected\">" + Arr[i]["unit"] + "</option>";
            } else {
                if (selectId == 0 && i == 0) {
                    str += "<option value=" + Arr[i]["id"] + " selected=\"selected\">" + Arr[i]["unit"] + "</option>";
                } else {
                    str += "<option value=" + Arr[i]["id"] + ">" + Arr[i]["unit"] + "</option>";
                }
            }
        }
        str += "</select>";
        return str;
    }
    //处理单位数组[新增]
    function ReturnNewModelUnitArray(selectId) {
        var Arr = $("#editTableParameterList").data("unitArr");          //初始化时，已经缓存的数据
        //console.log(Arr);
        var str = "<select name=\"newModelTableParameterListUnit\"  class=\"input-small\">";
        for (var i = 0; i < Arr.length; i++) {
            if (Arr[i]["id"] == selectId) {
                str += "<option value=" + Arr[i]["id"] + " selected=\"selected\">" + Arr[i]["unit"] + "</option>";
            } else {
                if (selectId == 0 && i == 0) {
                    str += "<option value=" + Arr[i]["id"] + " selected=\"selected\">" + Arr[i]["unit"] + "</option>";
                } else {
                    str += "<option value=" + Arr[i]["id"] + ">" + Arr[i]["unit"] + "</option>";
                }
            }
        }
        str += "</select>";
        return str;
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
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": 'selects',
                "status": idstr,
                "toolsCode": $("#toolsCode").val(),
                "materialCode": $("#materialCode").val(),
                "toolsName": $("#toolsName").val(),
                "toolsType": $("#toolsType").val(),
                "brandMaker": $("#brandMaker").val(),
                "toolsModel": $("#toolsModel").val(),
                "selectSeat": $("#selectSeat").val(),
                "perPage": 10,
                "curPage": pageNumber || 1
            },
            success: function(response) {
                if (response.success) {
                    $("#tableToolsManage tbody").html("");
                    $.each(response.data.listData, function(index, value) {
                        //console.log(value.paramenter_new);
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
                        $("<td />").html("<a class='showEditModal' data-original-title='工具详情' data-content='" + popoverstr + "'>" + value.tools_code + "</a>").appendTo(tr);
                        $("<td />").html(value.name).appendTo(tr);
                        $("<td />").html(value.maker_name).appendTo(tr);
                        $("<td />").html(value.model).appendTo(tr);
                        $("<td />").html("<a class='showListDetail'>" + value.numes + "</a>").appendTo(tr);

                        tr.data("id", value.id);
                        tr.data("toolsCode", value.tools_code);
                        tr.data("toolsName", value.name);
                        tr.data("makerId", value.brand_maker);
                        tr.data("makerName", value.maker_name);
                        tr.data("model", value.model);
                        tr.data("materialCode", value.material_code);
                        tr.data("type", value.type);
                        tr.data("toolsType", value.tools_type);
                        tr.data("imgsrc", value.imgsrc);
                        tr.data("application", value.applications);
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
    //函数：导出汇总excel
    function ajaxExportQuery() {
        var status_array = new Array();
        $('input[name="checkboxStatus"]:checked').each(function() {
            status_array.push($(this).val());//向数组中添加元素
        });
        var idstr = status_array.join(',');//将数组元素连接起来以构建一个字符串
        window.open(
                "/bms/toolsManagement/toolsListExport" +
                "?&status=" + idstr +
                "&toolsCode=" + $("#toolsCode").val() +
                "&materialCode=" + $("#materialCode").val() +
                "&toolsName=" + $("#toolsName").val() +
                "&toolsType=" + $("#toolsType").val() +
                "&brandMaker=" + $("#brandMaker").val() +
                "&toolsModel=" + $("#toolsModel").val() +
                "&selectSeat=" + $("#selectSeat").val()
                );
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
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": 'selectList',
                "toolsCode": toolsCode,
                "perPage": 10,
                "curPage": pageNumber || 1
            },
            success: function(response) {
                if (response.success) {
                    $("#tableToolsList tbody").html("");
                    $.each(response.data.listData, function(index, value) {

                        var tr = $("<tr />");

                        $("<td />").html(index + 1).appendTo(tr);
                        $("<td />").html("<a class='showToolsNoListModal'>" + value.tools_no + "</a>").appendTo(tr);
                        $("<td />").html(value.lineName).appendTo(tr);
                        $("<td />").html(value.seat).appendTo(tr);
                        $("<td />").html(value.recipient).appendTo(tr);
                        $("<td />").html("<a class='statusInfo' title='" + value.date_cycles + "'>" + value.statusName + "</a>").appendTo(tr);

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
        var ParameterStr = null;
        var ParameterName = '';
        var ParameterVal = '';
        var ParameterUnit = '';
        for (var i = 0; i < $("#tableParameterList tbody tr").size(); i++) {
            ParameterName = (typeof($('input[name="newModelTableParameterListName"]').eq(i).val()) != 'undefined') ? $('input[name="newModelTableParameterListName"]').eq(i).val() : '';
            ParameterVal = (typeof($('input[name="newModelTableParameterListValue"]').eq(i).val()) != 'undefined') ? $('input[name="newModelTableParameterListValue"]').eq(i).val() : '';
            ParameterUnit = (typeof($('select[name="newModelTableParameterListUnit"]').eq(i).val()) != 'undefined') ? $('select[name="newModelTableParameterListUnit"]').eq(i).val() : 0;       /////有坑，值获取不到
            if (ParameterName.length > 0 || ParameterVal.length > 0 || ParameterUnit.length > 0) {
                if (i === 0) {
                    ParameterStr = ParameterName + ';' + ParameterVal + ';' + ParameterUnit;
                } else {
                    ParameterStr += '|' + ParameterName + ';' + ParameterVal + ';' + ParameterUnit;
                }
            }
        }
        var toolsCode = $("#newModaltextAddToolsCode").val();
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": $("#addQuantityCheckbox").attr("checked") === "checked" ? 'addsQty' : 'adds',
                "quantity": $("#newModaltextAddQuantity").val(),
                "distributor": $("#newModalSelectDistributor").val(),
                "operater": $("#newModaltextAddOperater").val(),
                "cost": $("#newModaltextAddCost").val(),
                "useCycles": $("#newModaltextAddUseCycles").val(),
                "spareCycles": $("#newModaltextAddSpareCycles").val(),
                "warnCycles": $("#newModaltextAddWarnCycles").val(),
                "toolsCode": $("#newModaltextAddToolsCode").val(),
                "paramenter": ParameterStr
            },
            success: function(response) {
                if (response.success) {
                    $("#toolsCode").val(toolsCode);
                    ajaxQuery(1);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alertError();
            }
        });
    }

    //函数：检查工艺代码
    function ajaxCheckToolsCode() {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": 'checkCode',
                "toolsCode": $("#newModaltextAddToolsCode").val()
            },
            success: function(response) {
                if (response.success) {

                    $("#tableParameterList tbody").html("");
                    if (response.data) {
                        $('#addQuantityCheckbox').attr("checked", true);
                        $("#addQuantity").css("display", '');
                        $.each(response.data, function(index, value) {
                            var tr = $("<tr />");

                            var unitStr = '';
                            if (typeof(value.unit) != 'undefined' && value.unit.length > 0) {
                                unitStr = ReturnNewModelUnitArray(value.unit);
                            } else {
                                unitStr = ReturnNewModelUnitArray(0);
                            }
                            $("<td />").html(index + 1).appendTo(tr);
                            $("<td />").html('<input type=\"text\" name=\"newModelTableParameterListName\" class=\"input-small\" value=\"' + value.names + '\">').appendTo(tr);
                            $("<td />").html('<input type=\"text\" name=\"newModelTableParameterListValue\" class=\"input-small\" value=\"' + value.vals + '\">').appendTo(tr);
                            $("<td />").html(unitStr).appendTo(tr);

                            tr.data("id", value.id);

                            $("#tableParameterList tbody").append(tr);
                        });
                    } else {
                        $('#addQuantityCheckbox').attr("checked", false);
                        $("#addQuantity").css("display", 'none');
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

    //函数：编辑操作
    function ajaxEdit(ParameterStr) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": 'updates',
                "id": $("#editModal").data("id"),
                "toolsCode": $("#editModalTextEditToolsCode2").val(),
                "materialCode": $("#editModalTextEditMaterialCode").val(),
                "types": $("#editModalTextEditType").val(),
                "toolsType": $("#editModalTextEditToolsType").val(),
                "makerId": $("#editModalTextEditBrandMaker").val(),
                "toolsName": $("#editModalTextEditName").val(),
                "toolsModel": $("#editModalTextEditModel").val(),
                "toolsApplication": $("#editModalTextEditApplication").val(),
                "imgsrc": $("#editModalTextEditUpfile").val(),
                "paramenter": ParameterStr
            },
            success: function(response) {
                if (response.success) {
                    ajaxQuery($(".curPage").attr("page"));
                    //console.log($("#checkboxEditStartUsing").attr("checked"));
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
    function ajaxDelete(providerId) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: QUERY_TOOLS_TOOLSMANAGEMENT,
            data: {
                "type": 'deletes',
                "id": providerId
            },
            success: function(response) {
                if (response.success) {
                    ajaxQuery($(".curPage").attr("page"));
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
        $("#newModaltextAddQuantity").val("");
        $("#newModaltextAddCost").val("");
        $('#addQuantityCheckbox').attr("checked", false);
        $("#addQuantity").css("display", 'none');
        $("#tableParameterList tbody").html("");
    }
    //函数：清空编辑表单
    function emptyEditModel() {
        $("#editModalTextEditToolsCode").val("");
        $("#editModalTextEditToolsCode2").val("");
        $("#editModalTextEditMaterialCode").val("");
        $("#editModalTextEditToolsType").val("");
        $("#editModalTextEditName").val("");
        $("#editModalTextEditModel").val("");
        $("#editModalTextEditApplication").val("");
    }
    //函数：清空编辑表单
    function emptyToolsNoEditModel() {
        $("#toolsNoListEditModalOperater").val("");
        $("#toolsNoListEditModalSeat").val("");
        $("#toolsNoListEditModalRecipient").val("");
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

    //检验表单
    function checkNewForm() {

        if (!parseInt($("#newModaltextAddQuantity").val()) > 0) {
            alert('数量必须填写且大于0');
            return false;
        }
        if (!$("#newModalSelectDistributor").val() > 0) {
            alert('供应商未选择');
            return false;
        }
        if ($("#newModaltextAddOperater").val().length === 0) {
            alert('验收人不能为空');
            return false;
        }
        if (!parseInt($("#newModaltextAddCost").val()) > 0) {
            alert('成本必须填写且大于0');
            return false;
        }
        if (!parseInt($("#newModaltextAddUseCycles").val()) > 0) {
            alert('使用点检周期 必须填写且大于0');
            return false;
        }
        if (!parseInt($("#newModaltextAddSpareCycles").val()) > 0) {
            alert('闲置点检周期 必须填写且大于0');
            return false;
        }
        if (!parseInt($("#newModaltextAddWarnCycles").val()) > 0) {
            alert('警告周期 必须填写且大于0');
            return false;
        }
        if ($("#newModaltextAddToolsCode").val().length === 0) {
            alert('工艺代码不能为空');
            return false;
        }
        return true;
    }

});


