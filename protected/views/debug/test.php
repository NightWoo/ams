<!DOCTYPE HTML>
<html lang="en">
    <<head>
        <meta charset="utf-8">
        <title>测试页</title>
        <link href="/bms/css/bootstrap.css" rel="stylesheet">
        <link href="/bms/css/common.css" rel="stylesheet">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/bms/js/service.js"></script>
        <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/bms/rjs/lib/jsrender.min.js"></script>
        <script type="text/javascript" src="/bms/js/head.js"></script>
        <script type="text/javascript" src="/bms/js/test.js"></script>
    </head>
    <body>
        <?php
            require_once(dirname(__FILE__)."/../common/head.php");
        ?>
        <div class="offhead">
            <div id="bodyright" class="offset2">
                <legend>测试</legend>
                <div class="input-append">
                    <input type="text" id="newOrderNumber" class="input-medium" placeholder="订单号...">
                    <a class="btn appendBtn" id="newGetOrder"><i class="fa fa-search"></i></a>
                </div>
                
                <table class="table" id="tableXF" style="display:none">
                    <caption>销服数据库读取结果</caption>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>order_number</th>
                            <th>series</th>
                            <th>car_type_code</th>
                            <th>sell_car_type</th>
                            <th>car_model</th>
                            <th>car_type_description</th>
                            <th>sell_color</th>
                            <th>color</th>
                            <th>amount</th>
                            <th>options</th>
                            <th>order_nature</th>
                            <th>cold_resistant</th>
                            <th>remark</th>
                            <th>additions</th>
                            <th>distributor_code</th>
                            <th>distributor</th>
                            <th>production_base</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <table class="table" id="tableCRM" style="display:none">
                    <caption>CRM数据库读取结果</caption>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>order_number</th>
                            <th>series</th>
                            <th>car_type_code</th>
                            <th>sell_car_type</th>
                            <th>car_model</th>
                            <th>car_type_description</th>
                            <th>sell_color</th>
                            <th>color</th>
                            <th>amount</th>
                            <th>options</th>
                            <th>order_nature</th>
                            <th>cold_resistant</th>
                            <th>remark</th>
                            <th>additions</th>
                            <th>distributor_code</th>
                            <th>distributor</th>
                            <th>production_base</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script id="tmplOrderDetail" type="text/x-jsrander">
                    <tr>
                        <td>{{:order_detail_id}}</td>
                        <td>{{:order_number}}</td>
                        <td>{{:series}}</td>
                        <td>{{:car_type_code}}</td>
                        <td>{{:sell_car_type}}</td>
                        <td>{{:car_model}}</td>
                        <td>{{:car_type_description}}</td>
                        <td>{{:sell_color}}</td>
                        <td>{{:color}}</td>
                        <td>{{:amount}}</td>
                        <td>{{:options}}</td>
                        <td>{{:order_nature}}</td>
                        <td>{{:cold_resistant}}</td>
                        <td>{{:remark}}</td>
                        <td>{{:distributor_code}}</td>
                        <td>{{:distributor}}</td>
                        <td>{{:production_base}}</td>
                    </tr>
                </script>
            </div>
        </div>
    </body>
</html>