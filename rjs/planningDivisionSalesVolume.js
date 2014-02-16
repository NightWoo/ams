require.config({
    "baseUrl": "/bms/rjs/lib",
    "paths":{
        "jquery": "./jquery-2.0.3.min",
        "bootstrap": "./bootstrap.min",
        "bootstrap-datetimepicker": "./bootstrap-datetimepicker.min",
        "bootstrap-datetimepicker.zh-CN": "./bootstrap-datetimepicker.zh-CN",
        "head": "../head",
        "service": "../service",
        "common": "../common",
        "dateTimeUtil": "../dateTimeUtil"
    },
    "shim": {
        "bootstrap": ["jquery"],
        "bootstrap-datetimepicker": ["jquery"]
    }
});

require(["dateTimeUtil","head","service","common","jquery","bootstrap","bootstrap-datetimepicker"], function(dateTimeUtil,head,service,common,$) {
    head.doInit();
    initPage();

    $("#startTime, #endTime").datetimepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left",
        language: "zh-CN",
        minView: "2"
    });

    $("#btnQuery").on('click', function (e) {
        queryEndSale();
    });

    function initPage () {
        $("#headGeneralInformationLi").addClass("active");
        $("#startTime").val(dateTimeUtil.getTime('firstDayOfTheMonth').substr(0,10));
        $("#endTime").val(dateTimeUtil.getTime('lastWorkDate'));
    }

    function queryEndSale () {
        $.ajax({
            url: service.QUERY_PLANNING_DIVISION_END_SALE,
            dataType: 'json',
            data: {
                'sDate': $('#startTime').val(),
                'eDate': $('#endTime').val(),
                'series': $('#seriesSelect').val()
            },
            success: function (response) {
                if(response.success) {
                    $('#tableAll, #tableSub').hide();
                    $("#day").html($('#endTime').val().substr(-2) + '日');
                    var $tbodyAll = $('#tableAll>tbody').html('');
                    $.each(response.data.all, function (pdType, values) {
                        var $tr = $('<tr />');
                        $('<td />').html(pdType).appendTo($tr);
                        $('<td />').html(values.stock).appendTo($tr);
                        $('<td />').html(values.sale).appendTo($tr);
                        $tbodyAll.append($tr);
                    });

                    var $theadSub = $('#tableSub>thead').html('');
                    var $trSubHead = $('<tr />');
                    $.each(response.data.sub.time, function (index, time) {
                        $('<th />').html(time).appendTo($trSubHead);
                    });
                    $theadSub.append($trSubHead);

                    var $tbodySub = $('#tableSub>tbody').html('');
                    $.each(response.data.sub.data, function (pdType, values) {
                        var $tr = $('<tr />');
                        $.each(values, function (index, value) {
                            $('<td />').html(value).appendTo($tr);
                        });
                        $tbodySub.append($tr);
                    });
                    $('#tableAll, #tableSub').show();
                } else {
                    alert(response.message);
                }
            }
        });
    }
});