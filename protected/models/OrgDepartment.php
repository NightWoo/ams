<?php
Yii::import('application.models.AR.OrgDepartmentAR');
Yii::import('application.models.OrgStructureSeeker');

class OrgDepartment {
    private $_ar;
    private $_parent;
    public function __construct ($departmentId=0) {
        if(empty($departmentId)) {
            $this->_ar = new OrgDepartmentAR();
        } else {
            $this->_ar = OrgDepartmentAR::model()->findByPk($departmentId);
            if( !empty($this->_ar) && !empty($this->_ar->parent_id) ) {
                $this->_parent = OrgDepartmentAR::model()->findByPk($this->_ar->parent_id);
            }
        }
    }

    public static function createById ($id) {
        $c = __class__;
        return new $c($id);
    }

    public function __get ($attr) {
        return $this->{$attr};
    }

    public function generate ($deptData) {
        $this->save($deptData);
        $this->setSortNumber();
    }

    public function modify ($deptData) {
        $this->save($deptData);
    }

    private function save ($deptData) {
        $deptData = is_array($deptData) ? $deptData : CJSON::decode($deptData);
        if( $deptData['parent_id'] === $this->_ar->id) {
            throw new Exception("不允许以自身作为上级部门");
        }

        foreach($deptData as $key => $value) {
            $this->_ar->$key = $value;
        }
        $this->_ar->save();

        if(!empty($deptData['parent_id']) ) {
            $this->_parent = OrgDepartmentAR::model()->findByPk($deptData['parent_id']);
        }

        $this->setLevel();
        $this->setChildrenLevel();
    }

    private function setLevel () {
        if( !empty($this->_parent) ) {
            $this->_ar->level = $this->_parent->level + 1;
            $this->_ar->save();
        }
    }

    private function setChildrenLevel () {
        $depts = OrgDepartmentAR::model()->findAll('parent_id=?', array($this->_ar->id));
        if(!empty($depts)) {
            $level = $this->_ar->level + 1;
            foreach($depts as $dept) {
                $dept->level = $level;
                $dept->save();
                $deptObj = OrgDepartment::createById($dept->id);
                $deptObj->setChildrenLevel();
            }
        }
    }

    private function setSortNumber () {
        $sql = "SELECT MAX(sort_number) FROM org_department WHERE parent_id={$this->_parent->id} AND id<>{$this->_ar->id}";
        $max = Yii::app()->db->createCommand($sql)->queryScalar();
        $this->_ar->sort_number = $max + 1;
        $this->_ar->save();
    }

    public function remove () {
        $sql = "SELECT COUNT(*) FROM org_department WHERE parent_id={$this->_ar->id}";
        $count = Yii::app()->db->createCommand($sql)->queryScalar();
        if(!empty($count)) {
            throw new Exception ('该部门拥有子部门，无法移除，请先调整/移除子部门');
        } else {
            $this->_ar->removed = 1;
            $this->_ar->save();
        }
    }

    public function sortUp () {
        $high = OrgDepartmentAR::model()->find("parent_id=? AND sort_number<? ORDER BY sort_number DESC", array($this->_ar->parent_id, $this->_ar->sort_number));
        $highNum = 1;
        if(!empty($high)) {
            $highNum = $high->sort_number;
            $high->sort_number = $this->_ar->sort_number;
            $high->save();
        }
        $this->_ar->sort_number = $highNum;
        $this->_ar->save();
        $this->reorderSameParent();
    }

    public function sortDown () {
        $low = OrgDepartmentAR::model()->find("parent_id=? AND sort_number>? ORDER BY sort_number ASC", array($this->_ar->parent_id, $this->_ar->sort_number));
        $lowNum = 1;
        if(!empty($low)) {
            $lowNum = $low->sort_number;
            $low->sort_number = $this->_ar->sort_number;
            $low->save();
        }
        $this->_ar->sort_number = $lowNum;
        $this->_ar->save();
        $this->reorderSameParent();
    }

    public function reorderSameParent () {
        $siblings = OrgDepartmentAR::model()->findAll('parent_id=? ORDER BY sort_number ASC', array($this->_ar->parent_id));
        if(!empty($siblings)) {
            $i = 1;
            foreach($siblings as $dept) {
                $dept->sort_number = $i++;
                $dept->save();
            }
        }
    }

    public function getDeptList () {
        $seeker = new OrgStructureSeeker();
        $datas = $seeker->getOrgStructure();
        $ret = array();
        foreach($datas as $dept) {
            $level = 'level-' . $dept['level'];
            if(empty($ret[$level])) {
                $ret[$level] = array();
            }
            $ret[$level][] = array('id'=>$dept['id'], 'display_name'=>$dept['display_name']);
        }

        return $ret;
    }

    public function getChildren () {
        $seeker = new OrgStructureSeeker();
        $datas = $seeker->getOrgStructure($this->_ar->id);
        return $datas;
    }
}