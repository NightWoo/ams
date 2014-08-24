require.config({
    baseUrl: 'src/js'
});

require(['commonConfig'], function () {
    require(["service", "common"], function(service,common) {
        initPage();

        function initPage () {
            common.initGolbal();
            $("#headManpowerLi").addClass("active");
        }
    });
});
