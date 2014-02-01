require.config({
    baseUrl: '../src/js',

    paths:{
        "jquery": "../../vendor/jquery/jquery-2.1.0.min",
        "bootstrap": "../../vendor/bootstrap/js/bootstrap.min"
    },
    shim: {
        "bootstrap": ["jquery"]
    }
});

require(["head","service","common","jquery","bootstrap"], function(head,service,common,$) {
    head.doInit();
    initPage();

    function initPage () {
        $("#headManpowerLi").addClass("active");
    }
});
