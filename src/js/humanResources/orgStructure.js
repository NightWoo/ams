require.config({
    baseUrl: '../src/js'
});
require(['commonConfig'], function () {
    require(["service", "common", "jquery-ui", "primitives"], function(service,common) {
        var
            maintainPrivilage = common.checkPrivilage('ORG_STRUCTURE_MAINTAIN'),
            $editModal = $('#editModal'),
            $inputDisplayName = $('#inputDisplayName'),
            $inputName = $('#inputName'),
            $inputShortName = $('#inputShortName'),
            $selectParentDept = $('#selectParentDept'),
            $titleSuffix = $('#titleSuffix'),
            $liChildrenSort = $('#liChildrenSort'),
            $liDetailEdit = $('#liDetailEdit'),
            $paneChildrenSort = $('#paneChildrenSort'),
            $paneDetailEdit = $('#paneDetailEdit'),
            $managerNumber = $('#managerNumber'),
            $tbodyChildren = $('#tableChildren>tbody');

        initPage();

        $('#editCommit').on('click', function (e) {
            save();
        });

        $('#editCancel').on('click', function (e) {
            initEditModel();
            doPrimitives();
        });

        $tbodyChildren.on('click', 'button', function (e) {
            $target = $(e.target);
            if ($target.hasClass("btn") || $target.parent(".btn").length > 0) {
                var $btn = $target.hasClass("btn") ? $target : $target.parent(".btn");
                var btnName = $btn.data("btn_name");
                var deptId = $btn.closest('tr').data('dept_id');
                var parentId = $editModal.data('dept_id');

                switch (btnName) {
                    case 'sortUp' :
                        sortUp(deptId);
                        break;
                    case 'sortDown' :
                        sortDown(deptId);
                        break;
                }
                getChildren(parentId);
            }
        });

        function initPage () {
            common.initGolbal();
            $("#headManpowerLi").addClass("active");
            doPrimitives();
        }

        function doPrimitives () {
            $.ajax({
                url: service.GET_ORG_STRUCTURE,
                dataType: "json",
                error: function () {
                    common.alertError();
                },
                success: function (response) {
                    if(response.success) {
                        var options = new primitives.orgdiagram.Config(),
                            items = [],
                            colors = ['#428bca', '#f0ad4e', '#5cb85c', '#5bc0de', '#999999'];
                        $.each(response.data, function (index, dept) {
                            items.push(
                                new primitives.orgdiagram.ItemConfig({
                                    id: dept.id,
                                    parent: dept.parent,
                                    title: dept.short_name,
                                    short_name: dept.short_name,
                                    name: dept.name,
                                    display_name: dept.display_name,
                                    itemTitleColor: colors[dept.level],
                                    templateName: dept.templateName,
                                    manager_name: dept.manager_name,
                                })
                            );
                        });

                        options.hasSelectorCheckbox = primitives.common.Enabled.False;
                        options.leavesPlacementType = primitives.common.ChildrenPlacementType.Vertical;
                        options.hasButtons = primitives.common.Enabled.False;

                        options.templates = [getorgStructureTpl()];
                        options.onItemRender = onTemplateRender;
                        options.onMouseClick = onMouseClick;
                        options.defaultTemplateName = "orgStructureTpl";

                        options.items = items;
                        options.cursorItem = 0;
                        $("#orgDiagram").orgDiagram(options);
                        $("#orgDiagram").orgDiagram("update", primitives.orgdiagram.UpdateMode.Refresh);


                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });

            function onTemplateRender(event, data) {
                switch (data.renderingMode) {
                    case primitives.common.RenderingMode.Create:
                        /* Initialize widgets here */
                        break;
                    case primitives.common.RenderingMode.Update:
                        /* Update widgets here */
                        break;
                }

                var itemConfig = data.context;

                if (data.templateName == "orgStructureTpl") {
                    data.element.find("[name=titleBackground]")
                        .css({
                            "background": itemConfig.itemTitleColor
                        });
                    data.element.attr('title', itemConfig.name);

                    var fields = ["title", "display_name", "manager_name"];
                    for (var index = 0; index < fields.length; index++) {
                        var field = fields[index];

                        var element = data.element.find("[name=" + field + "]");
                        if (element.text() != itemConfig[field]) {
                            element.text(itemConfig[field]);
                        }
                    }
                }
            }

            function getorgStructureTpl() {
                var result = new primitives.orgdiagram.TemplateConfig();
                result.name = "orgStructureTpl";

                result.itemSize = new primitives.common.Size(120, 65);
                result.minimizedItemSize = new primitives.common.Size(10, 10);
                result.highlightPadding = new primitives.common.Thickness(3, 3, 2, 2);
                if(maintainPrivilage) {
                    result.cursorPadding = new primitives.common.Thickness(2, 2, 50, 2);
                } else {
                    result.cursorPadding = new primitives.common.Thickness(0, 0, 0, 0);
                }

                var itemTemplate = $(
                    '<div class="bp-item bp-corner-all bt-item-frame" rel="tooltip">'+
                        '<div class="bp-item-wrap">' +
                            '<div name="titleBackground" class="bp-item bp-corner-all bp-title-frame">' +
                                '<div class="bp-item bp-title">' +
                                    '<span name="title"></span>'+
                                    '<span class="pull-right" name="manager_name"></span>'+
                                '</div>' +
                            '</div>' +
                            '<div name="display_name" class="bp-item bp-item-dispname"></div>'+
                        '</div>' +
                    '</div>'
                );

                result.itemTemplate = itemTemplate.wrap('<div>').parent().html();
                if(maintainPrivilage) {
                    var cursorTemplate = $("<div></div>")
                    .css({
                        position: "absolute",
                        overflow: "hidden",
                        width: (result.itemSize.width + result.cursorPadding.left + result.cursorPadding.right) + "px",
                        height: (result.itemSize.height + result.cursorPadding.top + result.cursorPadding.bottom) + "px"
                    });

                    var cursorBorder = $("<div></div>")
                    .css({
                        width: (result.itemSize.width + result.cursorPadding.left + 1) + "px",
                        height: (result.itemSize.height + result.cursorPadding.top + 1) + "px"
                    }).addClass("bp-item bp-corner-all bp-cursor-frame");
                    cursorTemplate.append(cursorBorder);

                    var bootStrapVerticalButtonsGroup = $("<div></div>")
                    .css({
                        position: "absolute",
                        overflow: "hidden",
                        top: result.cursorPadding.top + "px",
                        left: (result.itemSize.width + result.cursorPadding.left + 10) + "px",
                        width: "35px",
                        height: (result.itemSize.height + 1) + "px"
                    }).addClass("btn-group-vertical btn-group-xs");

                    bootStrapVerticalButtonsGroup.append('<button class="btn btn-default" data-buttonname="edit" type="button"><span class="glyphicon glyphicon-edit"></span></button>');
                    bootStrapVerticalButtonsGroup.append('<button class="btn btn-default" data-buttonname="new" type="button"><span class="glyphicon glyphicon-plus"></span></button>');
                    bootStrapVerticalButtonsGroup.append('<button class="btn btn-default" data-toggle="confirmation" data-buttonname="remove" type="button"><span class="fa fa-trash-o fa-lg"></span></button>');

                    cursorTemplate.append(bootStrapVerticalButtonsGroup);

                    result.cursorTemplate = cursorTemplate.wrap('<div>').parent().html();
                }

                return result;
            }

            function onMouseClick(event, data) {

                var target = $(event.originalEvent.target);
                if (target.hasClass("btn") || target.parent(".btn").length > 0) {
                    var button = target.hasClass("btn") ? target : target.parent(".btn");
                    var buttonname = button.data("buttonname");
                    // alert(data.context.id);
                    // alert(data.parentItem.title)
                    initEditModel();
                    fillDepartmentSelect(data.context.id);

                    $editModal = $('#editModal');
                    switch (buttonname) {
                        case 'new' :
                            $titleSuffix.html('新增');
                            $selectParentDept.val(data.context.id).attr('disabled', 'disabled');
                            $liChildrenSort.hide();
                            $editModal.modal('show');
                            break;
                        case 'edit' :
                            $titleSuffix.html('编辑');
                            $selectParentDept.val(data.context.parent).removeAttr('disabled');
                            $inputDisplayName.val(data.context.display_name);
                            $inputName.val(data.context.name);
                            $inputShortName.val(data.context.title);
                            $liChildrenSort.show();
                            $editModal.data('dept_id', data.context.id).modal('show');
                            getChildren(data.context.id);
                            break;
                        case 'remove' :
                            var msg = '是否移除[' + data.context.display_name +']？<br>注：移除的部门可从后台恢复';
                            bootbox.confirm(msg, function(confirm) {
                                if(confirm) {
                                    remove(data.context.id);
                                }
                            });
                            break;
                    }

                    data.cancel = true;
                }
            }
        }

        function save () {
            $.ajax({
                url: service.SAVE_ORG_DEPT,
                dataType: 'json',
                data: {
                    'deptId' : $editModal.data('dept_id'),
                    'deptData' : packEditData(),
                    'managerNumber': $managerNumber.val()
                },
                error: function () {
                    common.alertError();
                },
                success: function (response) {
                    if(response.success) {
                        doPrimitives();
                        $editModal.modal('hide');
                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });
        }

        function remove (deptId) {
            $.ajax({
                url: service.REMOVE_ORG_DEPT,
                dataType: 'json',
                data: {
                    'deptId' : deptId
                },
                error: function () {
                    common.alertError();
                },
                success: function (response) {
                    if(response.success) {
                        doPrimitives();
                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });
        }

        function sortUp (deptId) {
            $.ajax({
                url: service.SORT_UP_ORG_DEPT,
                dataType: 'json',
                data: {
                    'deptId' : deptId
                },
                error: function () {
                    common.alertError();
                },
                success: function (response) {
                    if(response.success) {
                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });
        }
        function sortDown (deptId) {
            $.ajax({
                url: service.SORT_DOWN_ORG_DEPT,
                dataType: 'json',
                data: {
                    'deptId' : deptId
                },
                error: function () {
                    alertError();
                },
                success: function (response) {
                    if(response.success) {
                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });
        }

        function packEditData () {
            var packData = {};
            packData.name = $inputName.val();
            packData.display_name = $inputDisplayName.val();
            packData.short_name = $inputShortName.val();
            packData.parent_id = $selectParentDept.val();

            retData = JSON.stringify(packData);

            return retData;
        }

        function fillDepartmentSelect (deptId) {
            $.ajax({
                url: service.GET_ORG_DEPT_LIST,
                dataType: 'json',
                data: {
                    'deptId': deptId
                },
                async: false,
                error: function () {
                    common.alertError();
                },
                success: function (response) {
                    if(response.success) {
                        $allOpts = $('<div />');
                        $.each(response.data, function (level, depts) {
                            $options = $('<option />').attr('disabled', 'disabled').val('').html(level).appendTo($allOpts);
                            $.each(depts, function (index, dept) {
                                $opt = $('<option />').val(dept.id).html(dept.display_name).appendTo($allOpts);
                            });
                        });
                        $('#selectParentDept').html('').html($allOpts.html());
                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });
        }

        function getChildren (deptId) {
            $.ajax({
                url: service.GET_ORG_CHILDREN,
                dataType: 'json',
                data: {
                    'deptId': deptId
                },
                async: false,
                error: function () {
                    common.alertError();
                },
                success: function (response) {
                    if(response.success) {
                        $tbodyChildren.html('');
                        $.each(response.data, function (index, dept) {
                            $tr = $('<tr />');
                            $btnUp = $('<button />').data('btn_name', 'sortUp').addClass('btn  btn-default btn-xs').append('<i class="fa fa-arrow-up"></i>');
                            if( index === 0 ) {
                                $btnUp.attr('disabled', 'disabled');
                            }
                            $btnDown = $('<button />').data('btn_name', 'sortDown').addClass('btn  btn-default btn-xs').append('<i class="fa fa-arrow-down"></i>');
                            if( index === response.data.length - 1 ) {
                                $btnDown.attr('disabled', 'disabled');
                            }
                            $btnGroup = $('<div />').addClass('btn-group btn-group-xs').append($btnUp).append($btnDown);
                            $('<td />').append($btnGroup).appendTo($tr);
                            $('<td />').html(dept.sort_number).appendTo($tr);
                            $('<td />').html(dept.short_name).appendTo($tr);
                            $('<td />').html(dept.display_name).appendTo($tr);

                            $tr.data('dept_id', dept.id);
                            $tbodyChildren.append($tr);
                        });
                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });
        }

        function initEditModel () {
            $editModal.find('.form-control').val('');
            $editModal.data('dept_id', 0);
            $paneChildrenSort.removeClass('active in');
            $liChildrenSort.removeClass('active');
            $paneDetailEdit.addClass('active in');
            $liDetailEdit.addClass('active');
        }
    });
});