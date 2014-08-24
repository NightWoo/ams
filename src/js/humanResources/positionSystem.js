require.config({
    baseUrl: '../src/js'
});
require(['commonConfig'], function () {
    require(["service", "common", "jsrender"], function(service,common) {
        var
            maintainPrivilage = common.checkPrivilage('POSITION_SYSTEM_MAINTAIN'),
            $btnAdd = $('#btnAdd'),
            $editModal = $('#editModal'),
            $liDefinition = $('#liDefinition'),
            $liDescription = $('#liDescription'),
            $paneDefinition = $('#paneDefinition'),
            $paneDescription = $('#paneDescription'),
            $editCommit = $('#editCommit'),
            $titleSuffix = $('#titleSuffix'),
            $pyramid = $('#positionPyramid'),
            $positionLevel = $('#positionLevel'),
            $positionLevels = $('.position-level'),
            $pyramidGrades = $('.pyramid-grade'),
            $positionList = $('#positionList'),
            $inputPositionNumber = $('#inputPositionNumber'),
            $selectGrade = $('#selectGrade'),
            $inputDisplayName = $('#inputDisplayName'),
            $inputName = $('#inputName'),
            $inputShortName = $('#inputShortName'),
            $textEducation = $('#textEducation'),
            $textExperiences = $('#textExperiences'),
            $textDescription = $('#textDescription');

        initPage();

        $btnAdd.on('click', function (e) {
            initEditModal();
            $titleSuffix.html('新增');
            $editModal.modal('show');
        });

        $editCommit.on('click', function (e) {
            savePosition();
            $editModal.modal('hide');
        });

        $pyramid.on('click', function (e) {
            var $target = $(e.target);
            $target = $target.hasClass('pyramid-grade') ? $target : $target.parent('.pyramid-grade');
            clearPyramid();
            $target.addClass('active');
            $positionLevel.find('[level='+ $target.attr('level') +']').addClass('active');
            getPositionList($target.attr('channel'), $target.attr('level'));
        });

        $positionList.on('click', function (e) {
            var $target = $(e.target);
            var positionId = $target.closest('li').attr('data-position-id');
            if ($target.hasClass('btn') || $target.parent('.btn').length > 0) {
                var $btn = $target.hasClass('btn') ? $target : $target.parent('.btn');
                var btnName = $btn.attr('btn-name');
                if(btnName === 'edit') {
                    getPositionDetail(positionId);
                } else if(btnName === 'remove') {
                    var msg = '是否移除岗位[' + $btn.attr('data-display-name') +']？<br>注：移除的岗位可从后台恢复';
                    bootbox.confirm(msg, function(confirm) {
                        if(confirm) {
                            removePosition(positionId);
                        }
                    });
                }
            } else {
                href = '/bms/humanResources/positionDescription?positionId=' + positionId;
                window.open(href, '_blank');
            }
        });

        function initPage () {
            common.initGolbal();
            $("#headManpowerLi").addClass("active");
            fillGradeSelect();
            if(maintainPrivilage) {
                $btnAdd.show();
            }
        }

        function initEditModal () {
            $editModal.find('.form-control').val('');
            $editModal.data('position_id', 0);
            $paneDescription.removeClass('active in');
            $liDescription.removeClass('active');
            $paneDefinition.addClass('active in');
            $liDefinition.addClass('active');
        }

        function clearPyramid () {
            $positionLevels.removeClass('active');
            $pyramidGrades.removeClass('active');
        }

        function fillGradeSelect () {
            $.ajax({
                url: service.GET_HR_GRADE_LIST,
                dataType: 'json',
                data: {
                },
                async: false,
                error: function () {
                    common.alertError();
                },
                success: function (response) {
                    if(response.success) {
                        $allOpts = $('<div />');
                        $.each(response.data, function (channel, grades) {
                            $options = $('<option />').attr('disabled', 'disabled').val('').html('-----' + channel + '-----').appendTo($allOpts);
                            $.each(grades, function (index, grade) {
                                $opt = $('<option />').val(grade.id).html(grade.grade_name).appendTo($allOpts);
                            });
                        });
                        $('#selectGrade').html('').html($allOpts.html());
                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });
        }

        function getPositionList (channel, level) {
            $.ajax({
                url: service.GET_POSITION_LIST,
                dataType: 'json',
                data: {
                    'channel' : channel,
                    'level': level
                },
                error: function () {
                    common.alertError();
                },
                success: function (response) {
                    if(response.success) {
                        $positionList.html('').hide();
                        $positionList.append($.templates("#tmplPositionList").render(response.data)).show();
                        if(!maintainPrivilage) {
                           $positionList.find('.btn-group').hide();
                        }
                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });
        }

        function getPositionDetail (positionId) {
            $.ajax({
                url: service.GET_POSITION_DETAIL,
                dataType: 'json',
                data: {
                    'positionId' : positionId
                },
                error: function () {
                    common.alertError();
                },
                success: function (response) {
                    if(response.success) {
                        initEditModal();
                        $inputPositionNumber.val(response.data.position_number);
                        $selectGrade.val(response.data.grade_id);
                        $inputDisplayName.val(response.data.display_name);
                        $inputName.val(response.data.name);
                        $inputShortName.val(response.data.short_name);
                        $textEducation.val(response.data.education);
                        $textExperiences.val(response.data.experiences);
                        $textDescription.val(response.data.description);
                        $editModal.data('position_id', response.data.id).modal('show');
                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });
        }

        function savePosition () {
            $.ajax({
                url: service.SAVE_POSITION_DETAIL,
                type: 'post',
                dataType: 'json',
                data: {
                    'positionId' : $editModal.data('position_id'),
                    'positionDetail' : packEditData()
                },
                error: function () {
                    common.alertError();
                },
                success: function (response) {
                    if(response.success) {
                        $editModal.modal('hide');
                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });
        }

        function removePosition (positionId) {
            $.ajax({
                url: service.REMOVE_POSITION,
                dataType: 'json',
                data: {
                    'positionId' : positionId
                },
                error: function () {
                    common.alertError();
                },
                success: function (response) {
                    if(response.success) {
                        var channel = $pyramid.find('.active').attr('channel'),
                            level = $pyramid.find('.active').attr('level');
                        getPositionList(channel, level);
                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });
        }

        function packEditData () {
            var packData = {};
            packData.position_number = $inputPositionNumber.val();
            packData.name = $inputName.val();
            packData.display_name = $inputDisplayName.val();
            packData.short_name = $inputShortName.val();
            packData.grade_id = $selectGrade.val();
            packData.education = $textEducation.val();
            packData.experiences = $textExperiences.val();
            packData.description = $textDescription.val();

            retData = JSON.stringify(packData);

            return retData;
        }
    });
});