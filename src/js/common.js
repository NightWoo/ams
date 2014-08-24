define(["service", "jquery", "bootstrap", "bootbox"], function(service, $) {

    function initGolbal () {
        $(".body-main").css({
            "max-height" : $(window).height() - 50 + "px"
        });

        $('body').tooltip({
            selector: '*[rel=tooltip]',
            container: 'body'
        });
        maxHeightMain();
    }

    function maxHeightMain () {
        $(window).resize(function () {
            $(".body-main").css("max-height", $(window).height() - 50 + "px");
        });
    }

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

    function checkPrivilage (privilagePoint) {
        var permit = false;
        $.ajax({
            url: service.CHECK_PRIVILAGE,
            dataType: "json",
            data: {
                "privilagePoint" : privilagePoint
            },
            async: false,
            error: function () {alertError();},
            success: function (response) {
                if(response.success) {
                    permit = response.data;
                } else {
                    alert(response.message);
                }
            }
        });
        return permit;
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
                    if(!showDisabled && ~~value.is_enabled === 0) {
                        return true;
                    }
                    option = $("<option value='"+ value.id +"'>"+ value.name +"</option>");
                    if(~~value.is_enabled === 0) {
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
        initGolbal: function () {
            initGolbal();
        },

        maxHeightMain: function () {
            maxHeightMain();
        },

        seriesName: function (series) {
            SeriesArray = getSeriesArray();
            return SeriesArray[series];
        },

        getSeriesArray: function () {
            return getSeriesArray();
        },

        checkPrivilage: function (privilagePoint) {
            return checkPrivilage(privilagePoint);
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

        getDateString: function (date) {
            var theDate = date || new Date();
            var year = theDate.getFullYear(),
                month = theDate.getMonth() + 1,
                day = theDate.getDate();

            month = month < 10 ? '0' + month : month;
            day = day < 10 ? '0' + day : day;

            var dateString = year + '-' + month + '-' + day;
            return dateString;
        },
        getDateTimeString: function (date) {
            var theDate = date || new Date();
            var dateString = this.getDateString(theDate),
                hh = theDate.getHours(),
                mm = theDate.getMinutes(),
                ss = theDate.getSeconds();
            hh = hh < 10 ? '0' + hh : hh;
            mm = mm < 10 ? '0' + mm : mm;
            ss = ss < 10 ? '0' + ss : ss;
            var dateTimeString = dateString + ' ' + hh + ':' + mm + ':' + ss;
            return dateTimeString;
        }
    };
});