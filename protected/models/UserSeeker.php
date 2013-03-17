<?php
class UserSeeker
{
	private $department;
	public function __construct($department = '') {
		$this->department = $department;
	}
		
	public function getAll(){
		$sql = "SELECT id,username,display_name,email FROM user WHERE isdelete=0";
		if(empty($this->department)) {
		}
		$userList = Yii::app()->db->createCommand($sql)->queryAll();
		$userList = $this->fillRoles($userList);
		return $userList;
	}

	public function getPage($username, $email, $displayName, $curPage, $perPage){
		$conditions = array();
		if(!empty($username)) {
			$conditions[] = "username LIKE '%$username%'";
		}
		if(!empty($email)) {
            $conditions[] = "email LIKE '%$email%'";
        }
		if(!empty($displayName)) {
            $conditions[] = "display_name LIKE '%$displayName%'";
        }

		$condition = join(' OR ' , $conditions);
		if(empty($condition)) {
			$condition = "isdelete=0";
		} else {
			$condition .= " AND isdelete=0";
		}
		
		$limit = $perPage;
		$offset = ($curPage - 1) * $perPage;
        $sql = "SELECT id,username,display_name,email,card_number,card_8H10D,telephone,cellphone as cell FROM user WHERE $condition LIMIT $offset,$limit";
        $userList = Yii::app()->db->createCommand($sql)->queryAll();
		$userList = $this->fillRoles($userList);
		// $sql = "SELECT count(*) FROM user WHERE $condition LIMIT $offset,$limit";
		$sql = "SELECT count(*) FROM user WHERE $condition";
		$total = Yii::app()->db->createCommand($sql)->queryScalar();
		

        return array($total, $userList);
    }
	
	private function fillRoles($userList) {
		foreach($userList as &$user)	{
			$sql = "SELECT role_id FROM user_role WHERE user_id={$user['id']}";
			$user['roleIds'] = Yii::app()->db->createCommand($sql)->queryColumn();
		}
		return $userList;
	}

	public function checkCardNumber($number) {
		if(strlen($number) == 10){
			$sql = "SELECT id, card_number, display_name as name FROM user WHERE card_8H10D = '$number'";
			$user = Yii::app()->db->createCommand($sql)->queryRow();
			if(empty($user) || $user['card_number'] == '0'){
				$sql = "SELECT id, card_number, display_name as name FROM user WHERE card_number = '$number'";
				$user = Yii::app()->db->createCommand($sql)->queryRow();
			}
		} else {
			$sql = "SELECT id, card_number, display_name as name FROM user WHERE card_number = '$number'";
			$user = Yii::app()->db->createCommand($sql)->queryRow();
		}
		return $user;
	}
}
