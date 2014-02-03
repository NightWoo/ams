<?php
Yii::import('application.models.AR.OrgDepartmentAR');
class OrgStructureSeeker
{
    public function __construct () {

    }

    public function getOrgStructure ($withoutId=0) {
        $sql = "SELECT
                    id,
                    parent_id AS parent,
                    name,
                    display_name,
                    short_name AS title,
                    level,
                    sort_number
                FROM
                    org_department
                WHERE
                    removed = 0 AND
                    id <> $withoutId
                ORDER BY
                    level, sort_number ASC";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();

        return $datas;
    }
}