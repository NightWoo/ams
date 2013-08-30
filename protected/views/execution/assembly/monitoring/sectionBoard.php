<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>工段板</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/bms/css/execution/assembly/monitoring/sectionBoard.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
        <!-- // <script type="text/javascript" src="/bms/js/bootstrap.min.js"></script> -->
		<!-- // <script type="text/javascript" src="/bms/js/head.js"></script> -->
		<script type="text/javascript" src="/bms/js/service.js"></script>
		<script type="text/javascript" src="/bms/js/execution/assembly/monitoring/sectionBoard.js"></script>
    </head>
    <body>
        <div class="main">
            <div class="board_top">
				<input type='hidden' id='section' value='<?php echo $section;?>'>
                <span id="title"></span>
                <span id="dpu" style="margin-left:10px;"></span>
                /
                <span style="" id="qrate"></span>
                <span style="margin-left:10px;color:red;" id="otherSectionInfo"> </span>
                <span id="time" class="pull-right"></span>
            </div>
            <div id="board_middle">
                <!-- <div id="multiCallDiv">
                </div> -->
                <div class="div_self" id="seatDiv">
                    <!-- <div class="self">01</div>
                    <div class="self">02</div>
                    <div class="self">03</div>
                    <div class="self">04</div>
                    <div class="self">05</div>
                    <div class="self">06</div>
                    <div class="self">07</div>
                    <div class="self">08</div>
                    <div class="self">09</div>
                    <div class="self">10</div>
                    <div class="self">11</div> -->
                </div>

                <!-- <div class="div_section" id="divSection">
                    <div class="section" id="EF1">EF1</div>
                    <div class="section" id="EF2">EF2</div>
                    <div class="section" id="EF3">EF3</div>
                </div> -->
            </div>

             <div class="board_bottom" id="bottomDiv">
                <span id="lineRate">120sec</span>
                <span style="display:inline-block;margin-left:8px;height:12px;width:10px;vertical-align: middle" class="symbol" id="symbol"></span>
                <span id="pauseTime" style="margin-left:8px;">32/101min</span>
                <span id="workingTimePercentage" style="margin-left:20px;">49.5%</span>
                <span id="productAmount" class="pull-right">121/2000辆</span>
            </div>
        </div>
    </body>
</html>
