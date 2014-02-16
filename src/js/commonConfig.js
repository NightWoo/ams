require.config({
    paths:{
        "jquery": "../../vendor/jquery/jquery-2.1.0.min",
        "bootstrap": "../../vendor/bootstrap/js/bootstrap.min",
        "jsrender" : "../../vendor/jsrender/jsrender.min",
        "confirmation": "../../vendor/bootstrap/js/bootstrap3-confirmation",
        "bootbox": "../../vendor/bootbox/bootbox.min",
        "jquery-ui": "../../vendor/jquery-ui/js/jquery-ui.custom.min",
        "primitives": "../../vendor/primitives/primitives.min",
        "printArea": "../../vendor/printArea/jquery.PrintArea",

        "": ""
    },
    shim: {
        "bootstrap": ["jquery","jquery-ui"],
        "jsrender": ["jquery"],
        "bootbox": ["bootstrap"],
        "confirmation": ["bootstrap"],
        "jquery-ui": ["jquery"],
        "primitives": ["bootstrap"],
        "printArea": ["jquery"],

        "": ""
    }
});