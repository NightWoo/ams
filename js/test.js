$(document).ready(function($) {
    $("#newGetOrder").on("click", function() {
        if($.trim($("#newOrderNumber").val()) != ''){
            ajaxGetOriginalOrders();
        }
    })

    function ajaxGetOriginalOrders() {
        $("#tableXF>tbody, #tableCRM>tbody").html("");
        $.ajax({
            url: DEBUG_TEST_CRM,
            type: "post",
            dataType: "json",
            data: {
                "orderNumber" : $.trim($("#newOrderNumber").val())
            },
            success: function(response) {
                if(response.success){
                    trXF = $.templates("#tmplOrderDetail").render(response.data.xf);
                    trCRM = $.templates("#tmplOrderDetail").render(response.data.crm);
                    $("#tableXF>tbody").append(trXF);
                    $("#tableCRM>tbody").append(trCRM);
                    $("#tableXF>tbody, #tableCRM>tbody").show();
                } else {
                    alert("销服系统查无订单[" + $("#newOrderNumber").val() + "]");
                    resetNewModal();
                }
            },
            error: function(){
                alertError();
            }
        })
    }
});