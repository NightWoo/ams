
var SERVER_ADDRESS = "";

//common
var GET_SERIES_LIST = "/bms/common/getSeriesList";
var GET_SERIES_ARRAY = "/bms/common/getSeriesArray";
var GET_LINE_LIST = "/bms/common/getLineList";
var CHECK_PRIVILAGE ="/bms/user/checkPrivilage";

//database maintain
var WAREHOUSE_COUNT_REVISE_QUERY = "/bms/dataBase/queryWarehouseCountRevise";
var WAREHOUSE_COUNT_REVISE_SAVE = "/bms/dataBase/saveWarehouseCountRevise";

//select
var GET_WAREHOUSE_AREA = "/bms/warehouse/getWarehouseAreaList";

//获得车辆原始信息
var GET_CAR = "/bms/car/getCar";

//PBS校验      需返回车系、颜色、车身、VIN号
var PBS_VALIDATE = "/bms/car/validatePbs";
//PBS进入彩身车库
var PBS_ENTER_WORKSHOP = "/bms/execution/enterPbs";

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
//T11,T21,T32,C10,C21,F10 校验条码
var T11_F10_VALIDATE_BAR_CODE = "/bms/car/validateBarCode";
//T11  提交已填写的条码
var T11_F10_SUBMIT_PARTS = "/bms/execution/enterNode";

var CHECK_CONFIG_LIST = "/bms/car/checkConfigList";

//F20 扫描
var F20_GET_INFO = "/bms/car/validateF20";
//F20 打印检验跟单
var F20_PRINT_CHECK_LIST = "/bms/execution/enterF20";

//
var QUERY_DUTY_DEPARTMENT = "/bms/query/getDutyDepartment";
var GET_DUTY_GROUP_LIST = "/bms/query/getDutyGroupList";

//VQ1校验      需返回车系、颜色、车身、VIN号
var VQ1_VALIDATE = "/bms/car/validateVQ1";
var VQ1_GET_FAULT_PARTS = "/bms/fault/show";
var VQ1_SUBMIT_FAULT = "/bms/execution/enterVQ1";
var VQ1_SEARCH_PART = "/bms/fault/search";
var VQ1_VIEW_PART = "/bms/fault/view";
var VQ1_SHOW_EXCEPTION = "/bms/fault/showVQ1";
var VQ1_SUBMIT_EXCEPTION = "/bms/fault/saveVQ1";

var CHECKPAPER_VALIDATE = "/bms/car/validateCheckPaper";
var CHECKPAPER_SUBMIT = "/bms/execution/checkPaperPrint";

var RECORD_BARCODE = "/bms/execution/recordBarcode";

var CONFIGPAPER_MAIN_SUBMIT = "/bms/execution/configPaperMainPrint";
var CAR_VALIDATE = "/bms/car/validateCar";

var CAR_LABEL_ASSEMBLY_PRINT = "/bms/execution/carLabelAssemblyPrint";

var SHOW_CAR_FAULTS = "/bms/car/showFaults";
var SAVE_FAULT_DUTY_DEPARTMENT = "/bms/fault/saveDutyDepartment";

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

var WDI_GET_FAULT_PARTS = "/bms/fault/show";
var WDI_VALIDATE = "/bms/car/validateWDI";
var WDI_SUBMIT = "/bms/execution/enterWDI";
var WAREHOUSE_RELOCATE ="/bms/execution/warehouseRelocateSubmit";

//warehouse checkin
var CHECKIN_VALIDATE = "/bms/car/validateCI";
//var CHECKIN_SUBMIT = "/bms/execution/enterCI";
var CHECKIN_SUBMIT = "/bms/execution/warehouseCheckin";

//warehouse checkout
var CHECKOUT_VALIDATE = "/bms/car/validateNode";
//var CHECKOUT_SUBMIT = "/bms/execution/enterCO";
var CHECKOUT_SUBMIT = "/bms/execution/warehouseCheckout";

var WAREHOUSE_LABEL_VALIDATE = "/bms/car/validateWarehouseLabel";
var WAREHOUSE_LEBEL_PRINT = "/bms/execution/getWarehouseLabel";

var WAREHOUSE_CHECKIN_QUERY = "/bms/warehouse/checkinDetail";
var WAREHOUSE_CHECKIN_EXPORT = "/bms/warehouse/exportCheckinDetail";
var WAREHOUSE_CHECKOUT_QUERY = "/bms/warehouse/checkoutDetail";
var WAREHOUSE_CHECKOUT_EXPORT = "/bms/warehouse/exportCheckoutDetail";

var WAREHOUSE_RETURN_VALIDATE = "/bms/car/validateWarehouseReturn";
var WAREHOUSE_RETURN_SUBMIT = "/bms/execution/returnSubmit";

var CAR_ACCESS_VALIDATE = "/bms/car/validateCarAccess";
var CAR_ACCESS_SUBMIT = "/bms/execution/carAccessSubmit";

var SHOW_TRACE = "/bms/car/showTrace";
var QUERY_SPARES_TRACE = "/bms/spares/querySparesTrace";
var TRACE_EXPORT = "/bms/car/exportTrace";
var QUERY_TESTLINE_RECORD = "/bms/car/queryTestlineRecord";
var FAULT_QUERY = "/bms/fault/query";
var FAULT_EXPORT = "/bms/fault/export";
var FAULT_QUERY_DISTRIBUTE = "/bms/fault/queryDistribute";
var FAULT_QUERY_DUTY_DISTRIBUTION = "/bms/fault/queryDutyDistribution";
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
var BALANCE_DETAIL_QUERY ="/bms/car/queryBalanceDetail";
var BALANCE_DETAIL_EXPORT ="/bms/car/exportBalanceDetail";
var QUERY_BALANCE_ASSEMBLY = "/bms/car/queryBalanceAssembly";
var QUERY_BALANCE_DISTRIBUTE = "/bms/car/queryBalanceDistribute";
var SHOW_BALANCE_CARS = "/bms/car/showBalanceCars";
var QUERY_TESTLINE_RECORDS  = "/bms/execution/queryTestlineRecords";
var EXPORT_TESTLINE_RECORDS  = "/bms/execution/exportTestlineRecords";
var QUERY_RECYCLE_BALANCE_PERIOD = "/bms/car/queryRecycleBalancePeriod";
var QUERY_MANUFACTURE_PERIOD = "/bms/car/queryManufacturePeriod";
var QUERY_BALANCE_PERIOD = "/bms/car/queryBalancePeriod";
var SHOW_RECYCLE_CARS = "/bms/car/showRecycleCars";

var SHOW_USER = "/bms/user/show";
var INIT_PASSWORD = "/bms/user/initPassword";
var RESET_PASSWORD = "/bms/user/resetPassword";
var ADD_USER = "/bms/user/save";
var EDIT_USER = "/bms/user/save";
var DISABLE_USER = "/bms/user/disable";
var UPDATE_USER = "/bms/user/update";
var CHECK_CARD_NUMBER ="/bms/user/checkCardNumber";

var QUERY_COMPONENT_LIST = "/bms/component/showList";
var SAVE_COMPONENT  = "/bms/component/save";
var REMOVE_COMPONENT = "/bms/component/remove";
var SAVE_COMPONENT_PROVIDER = "/bms/component/saveProvider";

var SEARCH_COMPONENT_NAME_LIST = "/bms/fault/search";
var QUERY_FAULT_BASE = "/bms/fault/queryBase";
var SAVE_FAULT_STANDARD  = "/bms/fault/saveFaultStandard";
var REMOVE_FAULT_STANDARD = "/bms/fault/removeFaultStandard";
var GENERATE_FAULT_CODE = "/bms/fault/generateFaultCode";
var UPLOAD_FAULT_STANDARD_IMAGE = "/bms/fault/uploadImage";
var DELETE_FAULT_STANDARD_IMAGE = "/bms/fault/deleteImage";
var SHOW_FAULT_STANDARD_IMAGE = "/bms/fault/showImage";

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
var EXPORT_PLAN = "/bms/plan/export";
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
var MONITOR_AREA_INFO = "/bms/monitor/showWarehouseAreaBalance";
var MONITOR_BLOCK_INFO = "/bms/monitor/showWarehouseBlockBalance";
var MONITOR_ROW_BALANCE_DETAIL = "/bms/monitor/showWarehouseBalanceDetail";
var MONITOR_LANE_INFO = "/bms/monitor/showLaneInfo";

var GET_YEAR_CODE = "/bms/plan/getYearCode";

//added by wujun
var SEARCH_CONFIG = "/bms/config/search";
var SEARCH_ORDER_CONFIG = "/bms/config/searchOrderConfig";
var SAVE_CONFIG = "/bms/config/save";
var DELETE_CONFIG = "/bms/config/delete";
var GET_OIL_FILLING = "/bms/config/getOilFilling";
var GET_CONFIG_SAP = "/bms/config/getConfigSap";
var SAVE_CONFIG_SAP_ALL = "/bms/config/saveConfigSapAll";
//added by wujun
//plan maintain
var GET_YEAR_CODE = "/bms/plan/getYearCode";
var GET_BATCH_NUMBER = "/bms/plan/getBatchNumber";

//added by wujun
//config
var SEARCH_CONFIG = "/bms/config/search";
var SAVE_CONFIG = "/bms/config/save";
var SAVE_ORDER_CONFIG = "/bms/config/saveOrderConfig";
var DELETE_CONFIG = "/bms/config/delete";
var DELETE_ORDER_CONFIG = "/bms/config/deleteOrderConfig";
var QUERY_CONFIG_LIST = "/bms/config/searchConfigList";
var FILL_CONFIG = "/bms/config/getConfig";
var FILL_CAR_TYPE = "/bms/config/getCarType";
var FILL_ORDER_CAR_TYPE = "/bms/config/getOrderCarType";
var FILL_CAR_COLOR = "/bms/config/getCarColor";
var CHECK_REPLACEMENT = "/bms/config/checkReplacement";
var SAVE_CONFIG_DETAIL = "/bms/config/saveDetail";
var COPY_CONFIG_LIST = "/bms/config/copyList";
var DELETE_CONFIG_DETAIL = "/bms/config/deleteDetail";
var GET_COMPONENT_CODE = "/bms/component/getCode";
var GET_PROVIDER_CODE = "/bms/provider/getCode";
var GET_COMPONENT_NAME_LIST = "/bms/component/search";
var GET_PROVIDER_NAME_LIST = "/bms/provider/getNameList";

var QUERY_ACCESSORY_LIST = "/bms/config/queryAccessoryList";
var SAVE_ACCESSORY_DETAIL = "/bms/config/saveAccessoryDetail";
var DELETE_ACCESSORY_DETAIL = "/bms/config/deleteAccessoryDetail";
var COPY_ACCESSORY_LIST = "/bms/config/copyAccessoryList";
var QUERY_BOARD_ACCESSORY_LIST = "/bms/order/queryBoardAccessoryList";
var PRINT_ACCESSORY_LIST = "/bms/config/printAccessoryList";
var QUERY_BOARD_NUMBER_BY_VIN = "/bms/car/queryBoardNumber";

//added by wujun
//pause edit
var QUERY_PAUSE_RECORD = "/bms/pause/query";
var QUERY_PAUSE_DISTRIBUTE = "/bms/pause/queryDistribute";
var EXPORT_PAUSE_RECORD = "/bms/pause/exportRecord";
var QUERY_USE_RATE = "/bms/pause/queryUseRate";
var PAUSE_EDIT_SAVE = "/bms/pause/editSave";
var GET_PAUSE_DUTY_DEPARTMENT_LIST = "/bms/pause/getDutyDepartment";
var PLAN_PAUSE_SAVE = "/bms/pause/planPauseSave";
var PAUSE_DELETE = "/bms/pause/delete";

var QUERY_SHIFT_RECORD = "/bms/shift/query";
var SHIFT_RECORD_SAVE = "/bms/shift/save";
var SHIFT_RECORD_DELETE = "/bms/shift/delete";

var DEVICE_PARAMETER_QUERY = "/bms/deviceParameter/query";
var DEVICE_PARAMETER_SAVE = "/bms/deviceParameter/save";

//added by wujun
//order maintain
var GET_ORIGIANAL_ORDERS = "/bms/order/getOriginalOrders";
var GET_SPECIAL_ORDERS = "/bms/order/getSpecialOrders";
var ORDER_CHECK_DETAIL = "/bms/order/checkDetail";
var ORDER_GENERATE = "/bms/order/generate";
var ORDER_QUERY = "/bms/order/query";
var QUERY_BOARD_ORDERS = "/bms/order/queryBoardOrders";
var ORDER_SAVE = "/bms/order/save";
var ORDER_TOP_PRI = "/bms/order/top";
var ORDER_INC_PRI = "/bms/order/inc";
var ORDER_DELETE = "/bms/order/delete";
var ORDER_SPLIT = "/bms/order/split";
var SET_BOARD_TOP = "/bms/order/setBoardTop";
var ACTIVATE_BOARD = "/bms/order/activateBoard";
var FROZEN_BOARD = "/bms/order/frozenBoard";
var ORDER_GET_CAR_STANDBY = "/bms/order/getCarStandby";
var ORDER_HOLD_RELEASE = "/bms/order/holdRelease";
var FILL_ORDER_CONFIG = "/bms/order/getOrderConfig";
var FILL_LANE = "/bms/order/getLaneList";
var GET_DISTRIBUTOR_NAME_LIST = "/bms/order/getDistributorList";
var GET_DISTRIBUTOR_ID = "/bms/order/getDistributorId";
var QUERY_ORDER_CARS = "/bms/order/queryOrderCars";
var EXPORT_ORDER_CARS = "/bms/order/exportOrderCars";
var QUERY_ORDER_DETAIL = "/bms/order/query";
var QUERY_ORDER_BY_BOARD ="/bms/order/queryByBoard";
var GET_BOARD_NUMBER = "/bms/order/getBoardNumber";
var QUERY_CARS_BY_ORDER_ID = '/bms/order/queryCarsById';
var QUERY_CARS_BY_ORDER_IDS = '/bms/order/queryCarsByIds';
var WAREHOUSE_PRINT_BY_ORDER = '/bms/order/printByOrder';
var WAREHOUSE_PRINT_BY_ORDERS = '/bms/order/printByOrders';
var WAREHOUSE_PRINT_BY_BOARD = '/bms/order/printByBoard';
var QUERY_CARS_BY_SPECIAL_ORDER = '/bms/order/queryCarsBySpecialOrder';
var PRINT_BY_SPECIAL_ORDER = '/bms/order/printBySpecialOrder';
var ORDER_MANUAL_QUERY = '/bms/car/queryVins';
var ORDER_MATCH_MANUALLY = '/bms/order/matchManually';

var QUERY_DISTRIBUTE_PERIOD = '/bms/order/queryPeriod';

var GET_LANE_INFO ="/bms/lane/queryOrderInfo";
var GET_BOARD_INFO ="/bms/order/queryBoardInfo";
var GET_ORDER_In_BOARD_INFO = "/bms/order/queryOrderInBoardInfo";

var RELEASE_LANE_ORDERS = "/bms/lane/releaseOrders";

var WAREHOUSE_QUERY = "/bms/warehouse/queryRow";
var RESET_FREE_SEAT = "/bms/warehouse/resetFreeSeat";

var CONFIG_SHOW_IMAGE = '/bms/config/showImages';
var CONFIG_UPLOAD_IMAGE = '/bms/config/upload';
var CONFIG_DELETE_IMAGE = '/bms/config/deleteImage';
var SUB_CONFIG_SEARCH = "/bms/car/searchSubConfigQueue";
var SUB_CONFIG_PRINT = "/bms/car/printSubConfig";
var SUB_CONFIG_VALIDATE = "/bms/car/validateSubConfig";
var SUB_CONFIG_SAVE = "/bms/config/saveSub";

var SPS_QUEUE_VALIDATE = "/bms/car/validateSpsQueue";
var SPS_QUEUE_QUERY = "/bms/car/querySpsQueue";
var SPS_QUEUE_SUBMIT = "/bms/car/printSpsPaper";
var SPS_QUEUE_SAVE ="/bms/car/saveSpsQueue";

var VALIDATE_DATA_THROW = "/bms/car/validateDataThrow";
var ASSEMBLY_FINISH_DATA_THROW = "/bms/car/throwAssemblyFinish";
var WAREHOUSE_IN_DATA_THROW = "/bms/car/throwStoreIn";
var WAREHOUSE_OUT_DATA_THROW = "/bms/car/throwStoreOut";
var MARK_PRINT_THROW = "/bms/car/throwMarkPrint";
var CERTIFICATE_THROW_ONE = "/bms/car/throwOutPrintDataOne";

var ROLE_SHOW_ALL = "/bms/role/showAll";
var ROLE_ADD_TO_USER = "/bms/role/addToUser";

var QUERY_MANUFACTURE_DAILY = "/bms/report/queryManufactureDaily";
var MANUFACTURE_REPORT_EXPORT_CARS = "/bms/report/exportCars";
var QUERY_COMPLETION_REPORT = "/bms/report/queryCompletion";
var QUERY_USE_REPORT = "/bms/report/queryUse";
var QUERY_RECYCLE_REPROT_CHART = "/bms/report/queryRecycleChart";
var QUERY_OVERTIME_CARS = "/bms/report/queryOvertimeCars";
var QUERY_WAREHOUSE_CHART = "/bms/report/queryWarehouseChart";
var QUERY_OVERTIME_ORDERS = "/bms/report/queryOvertimeOrders";
var QUERY_QUALIFICATION_REPORT = "/bms/report/queryQualification";
var QUERY_FAULT_DISTRIBUTE_REPORT = "/bms/report/queryFaultDistribute";
var QUERY_REPLACEMENT_COST_REPORT = "/bms/report/queryReplacementCost";
var QUERY_COST_DISTRIBUTE_REPORT = "/bms/report/queryCostDistribute";

// ---- SOBIN 2013/7/19
var QUERY_TOOLS_HOME_MAKER = "/bms/toolsManagement/searchMaker";
var QUERY_TOOLS_HOME_DISTRIBUTOR = "/bms/toolsManagement/searchDistributor";
var QUERY_TOOLS_HOME_PARAMETER = "/bms/toolsManagement/searchParameter";
var QUERY_TOOLS_HOME_TOOLSUSER = "/bms/toolsManagement/searchToolsUser";
var QUERY_TOOLS_TOOLSMANAGEMENT = "/bms/toolsManagement/searchManagement";
var QUERY_TOOLS_TOOLSASSIGN = "/bms/toolsManagement/searchAssign";
var QUERY_TOOLS_TOOLSCHECK = "/bms/toolsManagement/searchCheck";
// ---- SOBIN 2013/7/19

var DEBUG_TEST_CRM = "/bms/debug/testCRM";

function alertError (message) {
	message = 'ajax error'
	// alert(message);
}
