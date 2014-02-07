require.config({
    baseUrl: '../src/js'
});
require(['commonConfig'], function () {
    require(["service", "common"], function(service,common) {
        var
            maintainPrivilage = common.checkPrivilage('POSITION_SYSTEM_MAINTAIN');

        initPage();

        function initPage () {
            common.initGolbal();
            $("#headManpowerLi").addClass("active");
        }
    });
});