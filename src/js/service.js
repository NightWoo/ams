define({
	//common
	GET_SERIES_LIST : '/bms/common/getSeriesList',
	GET_SERIES_ARRAY : '/bms/common/getSeriesArray',
	GET_LINE_LIST : '/bms/common/getLineList',
	CHECK_PRIVILAGE : '/bms/user/checkPrivilage',

	//org structure
	GET_ORG_STRUCTURE : '/bms/orgStructure/getStructure',
	GET_ORG_DEPT_LIST : '/bms/orgStructure/getDeptList',
	GET_ORG_CHILDREN : '/bms/orgStructure/getChildren',
	SAVE_ORG_DEPT : '/bms/orgStructure/departmentSave',
	REMOVE_ORG_DEPT : '/bms/orgStructure/departmentRemove',
	SORT_UP_ORG_DEPT : '/bms/orgStructure/departmentSortUp',
	SORT_DOWN_ORG_DEPT : '/bms/orgStructure/DepartmentSortDown',

	//position system
	GET_HR_GRADE_LIST : '/bms/positionSystem/getGradeList',
	GET_POSITION_LIST : '/bms/positionSystem/getPositionList',
	GET_POSITION_DETAIL : '/bms/positionSystem/getPositionDetail',
	SAVE_POSITION_DETAIL : '/bms/positionSystem/savePosition',
	REMOVE_POSITION : '/bms/positionSystem/removePosition',

	last: ''
});