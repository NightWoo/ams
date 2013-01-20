<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>车间板</title>
		<!-- Le styles -->
        <link href="/bms/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/bms/css/execution/assembly/monitoring/workshopBoard.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="/bms/js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src="/bms/js/head.js"></script>
		<script type="text/javascript" src="/bms/js/service.js"></script>
		<script type="text/javascript" src="/bms/js/execution/assembly/monitoring/workshopBoard.js"></script>
    </head>
    <body>
        <div class="main">
            <div class="div_head">
                <span id="title"></span>
                <span style="margin-left:40px;" id="dpu"></span>
				/
				<span style="" id="qrate"></span>
                <span class="pull-right" id="time"></span>
            </div>
            <div class="div_self">
                <div class="left_desc_div">
                    <span class="left_desc_span">内饰</span>
                </div>

                <div class="block">
                    <div class="self" id="facadeT1"><div style="float: left;width:110px;">T1</div></div>
                    <div id="sectionT1">
                        <div class="section" id="T1"><span class="normal_seat">0 1</span></div>
                        <div class="section" id="T2"><span class="normal_seat">0 2</span></div>
                        <div class="section" id="T3"><span class="normal_seat">0 3</span></div>
                        <div class="section" id="T4"><span class="normal_seat">0 4</span></div>
                        <div class="section" id="T5"><span class="normal_seat">0 5</span></div>
                        <div class="section" id="T6"><span class="normal_seat">0 6</span></div>
                        <div class="section" id="T7"><span class="normal_seat">0 7</span></div>
                        <div class="section" id="T8"><span class="normal_seat">0 8</span></div>
                        <div class="section" id="T9"><span class="normal_seat">0 9</span></div>
                        <div class="section" id="T10"><span class="normal_seat">1 0</span></div>
                        <div class="section" id="T11"><span class="normal_seat">1 1</span></div>
                    </div>
                </div>
                <div class="block">
                    <div class="self" id="facadeT2"><div style="float: left;width:110px;">T2</div></div>
                    <div id="sectionT2">
                        <div class="section" id="T12"><span class="normal_seat">1 2</span></div>
                        <div class="section" id="T13"><span class="normal_seat">1 3</span></div>
                        <div class="section" id="T14"><span class="normal_seat">1 4</span></div>
                        <div class="section" id="T15"><span class="normal_seat">1 5</span></div>
                        <div class="section" id="T16"><span class="normal_seat">1 6</span></div>
                        <div class="section" id="T17"><span class="normal_seat">1 7</span></div>
                        <div class="section" id="T18"><span class="normal_seat">1 8</span></div>
                        <div class="section" id="T19"><span class="normal_seat">1 9</span></div>
                        <div class="section" id="T20"><span class="normal_seat">2 0</span></div>
                        <div class="section" id="T21"><span class="normal_seat">2 1</span></div>
                    </div>
                </div>
                <div class="block">
                    <div class="self" id="facadeT3"><div style="float: left;width:110px;">T3</div></div>
                    <div id="sectionT3">
                        <div class="section" id="T22"><span class="normal_seat">2 2</span></div>
                        <div class="section" id="T23"><span class="normal_seat">2 3</span></div>
                        <div class="section" id="T24"><span class="normal_seat">2 4</span></div>
                        <div class="section" id="T25"><span class="normal_seat">2 5</span></div>
                        <div class="section" id="T26"><span class="normal_seat">2 6</span></div>
                        <div class="section" id="T27"><span class="normal_seat">2 7</span></div>
                        <div class="section" id="T28"><span class="normal_seat">2 8</span></div>
                        <div class="section" id="T29"><span class="normal_seat">2 9</span></div>
                        <div class="section" id="T30"><span class="normal_seat">3 0</span></div>
                        <div class="section" id="T31"><span class="normal_seat">3 1</span></div>
                        <div class="section" id="T32"><span class="normal_seat">3 2</span></div>
                    </div>
                </div>
            </div>

            <div class="div_section">
                <div class="left_desc_div">
                    <span class="left_desc_span">底盘</span>
                </div>
                
                <div class="block">
                    <div class="self" id="facadeC1"><div style="float: left;width:110px;">C1</div></div>
                    <div id="sectionC1">
                        <div class="section" id="C1"><span class="normal_seat">0 1</span></div>
                        <div class="section" id="C2"><span class="normal_seat">0 2</span></div>
                        <div class="section" id="C3"><span class="normal_seat">0 3</span></div>
                        <div class="section" id="C4"><span class="normal_seat">0 4</span></div>
                        <div class="section" id="C5"><span class="normal_seat">0 5</span></div>
                        <div class="section" id="C6"><span class="normal_seat">0 6</span></div>
                        <div class="section" id="C7"><span class="normal_seat">0 7</span></div>
                        <div class="section" id="C8"><span class="normal_seat">0 8</span></div>
                        <div class="section" id="C9"><span class="normal_seat">0 9</span></div>
                        <div class="section" id="C10"><span class="normal_seat">1 0</span></div>
                    </div>
                </div>
                <div class="block">
                    <div class="self" id="facadeC2"><div style="float: left;width:110px;">C2</div></div>
                    <div id="sectionC2">
                        <div class="section" id="C11"><span class="normal_seat">1 1</span></div>
                        <div class="section" id="C12"><span class="normal_seat">1 2</span></div>
                        <div class="section" id="C13"><span class="normal_seat">1 3</span></div>
                        <div class="section" id="C14"><span class="normal_seat">1 4</span></div>
                        <div class="section" id="C15"><span class="normal_seat">1 5</span></div>
                        <div class="section" id="C16"><span class="normal_seat">1 6</span></div>
                        <div class="section" id="C17"><span class="normal_seat">1 7</span></div>
                        <div class="section" id="C18"><span class="normal_seat">1 8</span></div>
                        <div class="section" id="C19"><span class="normal_seat">1 9</span></div>
                        <div class="section" id="C20"><span class="normal_seat">2 0</span></div>
                        <div class="section" id="C21"><span class="normal_seat">2 1</span></div>
                    </div>
                </div>
                 <div class="block">
                    <div class="self" id="facadeC3"><div style="float: left;width:110px;">C3</div></div>
                </div>
            </div>
            <div class="div_section">
                <div class="left_desc_div">
                    <span class="left_desc_span">最终</span>
                </div>
                
                <div class="block">
                    <div class="self" id="facadeF1"><div style="float: left;width:110px;">F1</div></div>
                    <div id="sectionF1">
                        <div class="section" id="F1"><span class="normal_seat">0 1</span></div>
                        <div class="section" id="F2"><span class="normal_seat">0 2</span></div>
                        <div class="section" id="F3"><span class="normal_seat">0 3</span></div>
                        <div class="section" id="F4"><span class="normal_seat">0 4</span></div>
                        <div class="section" id="F5"><span class="normal_seat">0 5</span></div>
                        <div class="section" id="F6"><span class="normal_seat">0 6</span></div>
                        <div class="section" id="F7"><span class="normal_seat">0 7</span></div>
                        <div class="section" id="F8"><span class="normal_seat">0 8</span></div>
                        <div class="section" id="F9"><span class="normal_seat">0 9</span></div>
                        <div class="section" id="F10"><span class="normal_seat">1 0</span></div>
                    </div>
                </div>
                <div class="block">
                    <div class="self" id="facadeF2"><div style="float: left;width:110px;">F2</div></div>
                    <div id="sectionF2">
                        <div class="section" id="F11"><span class="normal_seat">1 1</span></div>
                        <div class="section" id="F12"><span class="normal_seat">1 2</span></div>
                        <div class="section" id="F13"><span class="normal_seat">1 3</span></div>
                        <div class="section" id="F14"><span class="normal_seat">1 4</span></div>
                        <div class="section" id="F15"><span class="normal_seat">1 5</span></div>
                        <div class="section" id="F16"><span class="normal_seat">1 6</span></div>
                        <div class="section" id="F17"><span class="normal_seat">1 7</span></div>
                        <div class="section" id="F18"><span class="normal_seat">1 8</span></div>
                        <div class="section" id="F19"><span class="normal_seat">1 9</span></div>
                        <div class="section" id="F20"><span class="normal_seat">2 0</span></div>
                    </div>
                </div>
                <div class="block"  style="width:52px">
                    <div class="self" id="facadeVQ1"  style="width:52px"><div style="float: left;width:52px;">VQ1</div></div>
                    <div id="sectionVQ1">
                        <div class="section" id="F21"><span class="normal_seat">2 1</span></div>
                        <div class="section" id="F22"><span class="normal_seat">2 2</span></div>
                        <div class="section" id="F23"><span class="normal_seat">2 3</span></div>
                        <div class="section" id="F24"><span class="normal_seat">2 4</span></div>
                        <div class="section" id="F25"><span class="normal_seat">2 5</span></div>
                    </div>
                </div>
                <div class="block"  style="width:52px">
                    <div class="self" id="QG"  style="width:52px"><div style="float: left;width:52px;" id="QGDiv">QG</div></div>
                </div>
                
            </div>

            <div style="padding-left:18px; margin-bottom:10px;">
                <div class="chain" id="L1">主链</div>
                <div class="chain" id="EF1">EF1</div>
                <div class="chain" id="EF2">EF2</div>
                <div class="chain" id="EF3">EF3</div>
            </div>
            <div class="div_foot">
                <span id="lineRate"></span>
                <div style="display:inline-block;margin-left:8px;height:12px;width:10px" class="symbol" id="symbol"></div>
                <span id="pauseTime" style="margin-left:8px;"></span>
                <span id="workingTimePercentage" style="margin-left:20px;"></span>
                <span id="productAmount" class="pull-right"></span>
            </div>
        </div>
    </body>
</html>
