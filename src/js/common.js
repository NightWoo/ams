define(['service'], function(service) {
    function getSeriesArray () {
        var seriesArray = {};
        $.ajax({
            url: service.GET_SERIES_ARRAY,
            dataType: "json",
            data: {},
            async: false,
            error: function () {alertError();},
            success: function (response) {
                if(response.success){
                    seriesArray = response.data;
                } else {
                    alert(response.message);
                }
            }
        });
        return seriesArray;
    }

    function alertError (message) {
        message = message || 'ajax error';
        // alert(message);
    }

    function fadeMessageAlert (message,alertClass) {
        $("#messageAlert").removeClass("alert-error alert-success").addClass(alertClass);
        $("#messageAlert").html(message);
        $("#messageAlert").show(500,function () {
            setTimeout(function() {
                $("#messageAlert").hide(1000);
            },5000);
        });
    }

    function addCheckMessage (message) {
        checkMessage  = "<div class='alert alert-error fade in'>";
        checkMessage += "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
        checkMessage += "<strong>注意！</strong>";
        checkMessage += message;
        checkMessage += "</div>";
        $("#checkAlert").prepend(checkMessage);
    }

    function getDutyOptions (type, showDisabled) {
        showDisabled = showDisabled || false;
        optionsDiv = $("<div />");
        $.ajax({
            url: service.QUERY_DUTY_DEPARTMENT,
            async: false,
            dataType: "json",
            data: {
                "node": type
            },
            success: function (response) {
                $.each(response.data, function (index, value) {
                    if(!showDisabled && value.is_enabled == 0) {
                        return true;
                    }
                    option = $("<option value='"+ value.id +"'>"+ value.name +"</option>");
                    if(value.is_enabled == 0) {
                        option.attr("disabled", "disabled");
                    }
                    option.appendTo(optionsDiv);
                });
            }
        });
        options = optionsDiv.children();
        return options;
    }

    function getSeriesChecked () {
        seriesArray = getSeriesArray();
        var temp = [];
        $.each(seriesArray, function (series, seriesName) {
            if($("#checkbox" + series).prop("checked")) {
                temp.push($("#checkbox" + series).val());
            }
        });
        return temp.join(",");
    }

    function fillSeriesCheckbox () {
        $.ajax({
            url: service.GET_SERIES_LIST,
            dataType: "json",
            data: {},
            async: false,
            error: function () {alertError();},
            success: function (response) {
                if(response.success){
                    options = $.templates("#tmplSeriesCheckbox").render(response.data);
                    $(".seriesCheckbox").append(options);
                } else {
                    alert(response.message);
                }
            }
        });
    }

    function fillLineSelect () {
        $.ajax({
            url: service.GET_LINE_LIST,
            dataType: "json",
            data: {},
            async: false,
            error: function () {alertError();},
            success: function (response) {
                if(response.success){
                    options = $.templates("#tmplLineSelect").render(response.data);
                    $(".lineSelect").append(options);
                } else {
                    alert(response.message);
                }
            }
        });
    }

    return {
        seriesName: function (series) {
            SeriesArray = getSeriesArray();
            return SeriesArray[series];
        },

        getSeriesArray: function () {
            return getSeriesArray();
        },

        fadeMessageAlert: function (message, alertClass) {
            fadeMessageAlert(message,alertClass);
        },

        addCheckMessage: function (message) {
            addCheckMessage(message);
        },

        getDutyOptions: function (type, showDisabled) {
            return getDutyOptions(type, showDisabled);
        },

        getSeriesChecked: function () {
            return getSeriesChecked();
        },

        alertError: function (message) {
            return alertError(message);
        },

        fillSeriesCheckbox: function () {
            return fillSeriesCheckbox();
        },

        fillLineSelect: function () {
            return fillLineSelect();
        },

        attachTooltip: function () {
            $('body').tooltip(
                {
                 selector: "*[rel=tooltip]"
            });
        }
    }
});