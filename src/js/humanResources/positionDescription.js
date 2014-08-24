require.config({
    baseUrl: '../src/js'
});
require(['commonConfig'], function () {
    require(['service', 'common', 'jsrender', 'printArea'], function(service,common) {
        var
            maintainPrivilage = common.checkPrivilage('POSITION_SYSTEM_MAINTAIN'),
            positionId = $('#positionId').attr('data-position-id'),
            $btnPrint = $('.btn[btn-name=print]');

        initPage();

        $btnPrint.on('click', function (e) {
             $("#descriptionPrintContent").printArea();
        });

        function initPage () {
            common.initGolbal();
            $("#headManpowerLi").addClass("active");
            getPositionDetail(positionId);
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
                        $.each(response.data, function (key, value) {
                            var text = value.replace(/\n/g, '<br>');
                            $(".panel").find('[position-detail='+ key +']').html(text);
                        });
                    } else {
                        bootbox.alert(response.message);
                    }
                }
            });
        }
    });
});