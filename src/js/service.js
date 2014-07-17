define({
	//common
	GET_SERIES_LIST: '/bms/common/getSeriesList',
	GET_SERIES_ARRAY: '/bms/common/getSeriesArray',
	GET_LINE_LIST: '/bms/common/getLineList',
	CHECK_PRIVILAGE: '/bms/user/checkPrivilage',

	//org structure
	GET_ORG_STRUCTURE: '/bms/orgStructure/getStructure',
	GET_ORG_DEPT_LIST: '/bms/orgStructure/getDeptList',
	GET_ORG_CHILDREN: '/bms/orgStructure/getChildren',
	SAVE_ORG_DEPT: '/bms/orgStructure/departmentSave',
	REMOVE_ORG_DEPT: '/bms/orgStructure/departmentRemove',
	SORT_UP_ORG_DEPT: '/bms/orgStructure/departmentSortUp',
	SORT_DOWN_ORG_DEPT: '/bms/orgStructure/departmentSortDown',
	GET_3_LEVEL_LIST: '/bms/orgStructure/get3LevelList',

	//position system
	GET_HR_GRADE_LIST: '/bms/positionSystem/getGradeList',
	GET_HR_HIGH_LEVEL: '/bms/positionSystem/getHighLevel',
	GET_POSITION_LIST: '/bms/positionSystem/getPositionList',
	GET_POSITION_DETAIL: '/bms/positionSystem/getPositionDetail',
	SAVE_POSITION_DETAIL: '/bms/positionSystem/savePosition',
	REMOVE_POSITION: '/bms/positionSystem/removePosition',
	GET_GRADE_POSITION_LIST: '/bms/positionSystem/getGradePositionList',

	//staff
	GET_PROVINCE_CITY_LIST: '/bms/staff/getProvinceCityList',
	SAVE_STAFF: '/bms/staff/saveStaff',

	last: ''
});