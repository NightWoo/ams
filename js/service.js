/* fdhsjf
fdsf 

*/
var SERVER_ADDRESS = "";

//获得车辆原始信息
var GET_CAR = "/bms/car/getCar";

//PBS校验      需返回车系、颜色、车身、VIN号
var PSB_VALIDATE = "/bms/car/validatePbs";
//PBS进入彩身车库
var PSB_ENTER_WORKSHOP = "/bms/execution/enterPbs";

//T0  获取计划，以显示
var T0_GET_PLAN = "/bms/plan/search";
//T0  根据Vin号，匹配计划
var T0_MATCH_PLAN = "/bms/car/matchPlan";
//T0 上线并打印配置单,更新分装区的信息
var T0_ENTER_AND_PRINT = "/bms/execution/enterT0";

//T11,T21,T32,C10,C21,F10  根据VIn号 ，获取零部件信息
var T11_F10_GET_PARTS = "/bms/car/viewComponents";
//T11,T21,T32,C10,C21,F10  根据零部件条码，确认零部件
var T11_F10_VALIDATE_PART = "";
//T11  提交已填写的条码
var T11_F10_SUBMIT_PARTS = "/bms/execution/enterNode";

//F20 扫描
var F20_GET_INFO = "/bms/car/validateF20";
//F20 打印检验跟单
var F20_PRINT_CHECK_LIST = "/bms/execution/enterF20";


//VQ1校验      需返回车系、颜色、车身、VIN号
var VQ1_VALIDATE = "/bms/car/validateVQ1";
var VQ1_GET_FAULT_PARTS = "/bms/fault/show";
var VQ1_SUBMIT_FAULT = "/bms/execution/enterVQ1";
var VQ1_SEARCH_PART = "/bms/fault/search";
var VQ1_VIEW_PART = "/bms/fault/view";
var VQ1_SHOW_EXCEPTION = "/bms/fault/showVQ1";
var VQ1_SUBMIT_EXCEPTION = "/bms/fault/saveVQ1";


//work shop
var LWS_VALIDATE = "/bms/car/validateLWS";
var LWS_SUBMIT = "/bms/execution/enterLWS";

//check shop
var ECS_VALIDATE = "/bms/car/validateNode";
var ECS_SUBMIT = "/bms/execution/enterECS";


//check line
var CL_VALIDATE = "/bms/car/validateCL";
var CL_SUBMIT = "/bms/execution/enterCL";

//road test
var RTS_VALIDATE = "/bms/car/validateNode";
var RTS_DRIVER_VALIDATE = "/bms/user/search";
var RTS_SUBMIT = "/bms/execution/enterRTS";

var RTF_VALIDATE = "/bms/car/validateRTF";
var RTF_GET_FAULT_PARTS = "/bms/fault/show";
var RTF_GAS_BAG = "/bms/fault/searchGasBag";
var RTF_SUBMIT = "/bms/execution/enterRTF";

var RTF_SHOW_EXCEPTION = "/bms/fault/showVQ2Road";
var RTF_SUBMIT_EXCEPTION = "/bms/fault/saveVQ2Road";


var LEAK_VALIDATE = "/bms/car/validateVQ2Leak";
var LEAK_GET_FAULT_PARTS = "/bms/fault/showLeak";
var LEAK_SUBMIT = "/bms/execution/enterVQ2Leak";

var LEAK_SHOW_EXCEPTION = "/bms/fault/showVQ2Leak";
var LEAK_SUBMIT_EXCEPTION = "/bms/fault/saveVQ2Leak";


//vq3
var VQ3_VALIDATE = "/bms/car/validateVQ3";
var VQ3_SUBMIT = "/bms/execution/enterVQ3";
var VQ3_GET_FAULT_PARTS = "/bms/fault/show";
var VQ3_SEARCH_PART = "/bms/fault/search";

var VQ3_SHOW_EXCEPTION = "/bms/fault/showVQ3";
var VQ3_SUBMIT_EXCEPTION = "/bms/fault/saveVQ3";

//warehouse checkin
var CHECKIN_VALIDATE = "/bms/car/validateCI";
//var CHECKIN_SUBMIT = "/bms/execution/enterCI";
var CHECKIN_SUBMIT = "/bms/execution/warehouseCheckin";

//warehouse checkout
var CHECKOUT_VALIDATE = "/bms/car/validateNode";
//var CHECKOUT_SUBMIT = "/bms/execution/enterCO";
var CHECKOUT_SUBMIT = "/bms/execution/warehouseCheckout";


var SHOW_TRACE = "/bms/car/showTrace";
var TRACE_EXPORT = "/bms/car/exportTrace";
var FAULT_QUERY = "/bms/fault/query";
var FAULT_EXPORT = "/bms/fault/export";
var FAULT_QUERY_DISTRIBUTE = "/bms/fault/queryDistribute";
var FAULT_QUERY_DPU = "/bms/fault/queryDPU";
var NODE_QUERY = "/bms/fault/query";
var NODE_QUERY_PLATON = "/bms/fault/queryPlaton";
var NODE_QUERY_DPU = "/bms/fault/queryDPU";
var NODE_QUERY_QUALIFIED  = "/bms/fault/queryQualified";
var NODE_QUERY_CAR = "/bms/fault/queryCars";
var NODE_EXPORT = "/bms/fault/export";
var COMPONENT_QUERY = "/bms/component/query";
var COMPONENT_EXPORT = "/bms/component/export";
var QUERY_NODE_TRACE = "/bms/execution/queryNodeTrace";
var EXPORT_NODE_TRACE = "/bms/execution/exportNodeTrace";


var SHOW_USER = "/bms/user/show";
var INIT_PASSWORD = "/bms/user/initPassword";
var RESET_PASSWORD = "/bms/user/resetPassword";
var ADD_USER = "/bms/user/save";
var EDIT_USER = "/bms/user/save";
var DISABLE_USER = "/bms/user/disable";
var UPDATE_USER = "/bms/user/update";


var QUERY_COMPONENT_LIST = "/bms/component/showList";
var SAVE_COMPONENT  = "/bms/component/save";
var REMOVE_COMPONENT = "/bms/component/remove";

var SEARCH_COMPONENT_NAME_LIST = "/bms/fault/search";
var QUERY_FAULT_BASE = "/bms/fault/queryBase";
var SAVE_FAULT_STANDARD  = "/bms/fault/saveFaultStandard";
var REMOVE_FAULT_STANDARD = "/bms/fault/removeFaultStandard";
var GENERATE_FAULT_CODE = "/bms/fault/generateFaultCode";

var SEARCH_PROVIDER = "/bms/provider/search";
var SAVE_PROVIDER = "/bms/provider/save";
var DELETE_PROVIDER = "/bms/provider/delete";

var SEARCH_PLAN = "/bms/plan/search";
var SAVE_PLAN = "/bms/plan/save";
var DELETE_PLAN = "/bms/plan/remove";
var INC_PRI_PLAN = "/bms/plan/inc";
var TOP_PRI_PLAN = "/bms/plan/top";
var REDUCE_PRI_PLAN = "/bms/plan/reduce";		//added by wujun
var QUERY_PLAN = "/bms/plan/query";
var PLAN_QUERY_COMPLETION = "/bms/plan/queryCompletion";

var QUERY_SECTION = "/bms/monitor/querySection";
var SHOW_SECTION_PANEL = "/bms/monitor/showSectionPanel";
var SHOW_SHOP_PANEL = "/bms/monitor/showShopPanel";
var SHOW_SECTION_STATUS = "/bms/monitor/ShowSectionStatus";
var SHOW_MONITOR_INFO = "/bms/monitor/showInfo";
var SHOW_HOME_INFO = "/bms/monitor/showHomeEfficiency";		//added by wujun

var SHOW_MONITOR_LABEL = "/bms/monitor/showLabel";
var SHOW_BALANCE_DETAIL = "/bms/monitor/showBalanceDetail";
var MONITOR_PRODUCT_INFO = "/bms/monitor/showProductInfo";
var MONITOR_BLOCK_INFO = "/bms/monitor/ShowWarehouseBlockBalance";
var MONITOR_ROW_BALANCE_DETAIL = "/bms/monitor/showWarehouseBalanceDetail";

var GET_YEAR_CODE = "/bms/plan/getYearCode";

//added by wujun
var SEARCH_CONFIG = "/bms/config/search";
var SAVE_CONFIG = "/bms/config/save";
var DELETE_CONFIG = "/bms/config/delete";

//added by wujun
//plan maintain
var GET_YEAR_CODE = "/bms/plan/getYearCode";
var GET_BATCH_NUMBER = "/bms/plan/getBatchNumber";

//added by wujun
//config
var SEARCH_CONFIG = "/bms/config/search";
var SAVE_CONFIG = "/bms/config/save";
var DELETE_CONFIG = "/bms/config/delete";
var QUERY_CONFIG_LIST = "/bms/config/searchConfigList";
var FILL_CONFIG = "/bms/config/getConfig";
var FILL_CAR_TYPE = "/bms/config/getCarType";
var SAVE_CONFIG_DETAIL = "/bms/config/saveDetail";
var COPY_CONFIG_LIST = "/bms/config/copyList";
var DELETE_CONFIG_DETAIL = "/bms/config/deleteDetail";
var GET_COMPONENT_CODE = "/bms/component/getCode";
var GET_PROVIDER_CODE = "/bms/provider/getCode";
var GET_COMPONENT_NAME_LIST = "/bms/component/search";
var GET_PROVIDER_NAME_LIST = "/bms/provider/getNameList";

//added by wujun
//pause edit
var QUERY_PAUSE_RECORD = "/bms/pause/query";
var QUERY_PAUSE_DISTRIBUTE = "/bms/pause/queryDistribute";
var QUERY_USE_RATE = "/bms/pause/queryUseRate";
var PAUSE_EDIT_SAVE = "/bms/pause/editSave";
var GET_PAUSE_DUTY_DEPARTMENT_LIST = "/bms/pause/getDutyDepartment";

//added by wujun
//order maintain
var ORDER_SEARCH = "/bms/order/searchByDate";
var ORDER_SAVE = "/bms/order/save";
var ORDER_TOP_PRI = "/bms/order/top";
var ORDER_INC_PRI = "/bms/order/inc";
var ORDER_DELETE = "/bms/order/delete";
var ORDER_GET_CAR_STANDBY = "/bms/order/getCarStandby";
var ORDER_HOLD_RELEASE = "/bms/order/holdRelease";
var GET_DISTRIBUTOR_NAME_LIST = "/bms/order/getDistributorList";
var GET_DISTRIBUTOR_ID = "/bms/order/getDistributorId";

var CONFIG_SHOW_IMAGE = '/bms/config/showImages';
var CONFIG_UPLOAD_IMAGE = '/bms/config/upload';
var CONFIG_DELETE_IMAGE = '/bms/config/deleteImage';

function alertError (message) {
	message = 'ajax error'
	//alert(message);
}
