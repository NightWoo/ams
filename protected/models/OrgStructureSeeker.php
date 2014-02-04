<?php
Yii::import('application.models.AR.OrgDepartmentAR');
class OrgStructureSeeker
{
    public function __construct () {

    }

    public function getOrgStructure ($parentId=0) {
        $parentCondition = empty($parentId) ? '' : " AND parent_id={$parentId}";
        $sql = "SELECT
                    id,
                    parent_id AS parent,
                    name,
                    display_name,
                    short_name,
                    level,
                    sort_number
                FROM
                    org_department
                WHERE
                    removed = 0 $parentCondition
                ORDER BY
                    level, sort_number ASC";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();

        return $datas;
    }
}