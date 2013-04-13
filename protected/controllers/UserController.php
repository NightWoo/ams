<?php
Yii::import('application.models.Car');
class UserController extends BmsBaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionShow() {
		$curUser = User::create(Yii::app()->user->id);
		$userList = array();
		$total = 0;
		$curPage = $this->validateIntVal('curPage',1);
        $perPage = $this->validateIntVal('perPage',10);
		if($curUser->isAdmin()) {
			$username = $this->validateStringVal('username','');
			$email = $this->validateStringVal('email','');
			$displayName = $this->validateStringVal('display_name','');
			$seeker = User::createSeeker();
			list($total, $userList) = $seeker->getPage($username, $email, $displayName, $curPage, $perPage);
		}
		$this->renderJsonBms(true, 'OK', array('user' => $curUser->getCommonInfo(), 'userList' => $userList, 'pager' => array('curPage' => $curPage, 'perPage' => $perPage, 'total' => $total)));
	}

	public function actionSearch() {
		try{
			$card = $this->validateStringVal('card','');
			$user = User::createByCard($card);
			$data = array(
				'id' => $user->id,
				'email' => $user->email,
				'username' => $user->username,
				'display_name' => $user->display_name,
			);
			$this->renderJsonBms(true, 'OK', $data);
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage(), null);
		}
	}

	public function actionEncode($password,$salt) {
		echo md5($salt . $password);
	}

	public function actionUpdate() {
		try{
            $email = $this->validateStringVal('email','');
			$telephone = $this->validateStringVal('telephone','');
			$cellphone = $this->validateStringVal('cellphone','');
            $user = User::create(Yii::app()->user->id);
			$user->email = $email;
			$user->cellphone = $cellphone;
			$user->telephone = $telephone;
			$user->save();	
            $this->renderJsonBms(true, 'OK', '');
        } catch(Exception $e) {
            $this->renderJsonBms(false, $e->getMessage(), null);
        }
	}


    //only admin can init user's password	
	public function actionInitPassword() {
		try {
			$opUserId = Yii::app()->user->id;
			$user = User::model()->findByPk($opUserId);
			$userId = $this->validateIntVal('id',0);
			if(!$user->admin) {
				BmsLogger::warning($opUserId . " try to init password @ user " .$userId);
				throw new Exception ('不要做坏事，有记录的！！');
			}
			$user = User::model()->findByPk($userId);
			$password = '';
			if(!empty($user)) {
				$password = $user->initPassword($user->card_number);
				$mailer = new BmsMailer();
				$mailer->sendMail('init password', $password, 'bmsadmin@163.com:admin,' . $user->email);
				BmsLogger::info('init ' .$user->username .' password to ' . $password);
			}
			$this->renderJSONBms(true,'OK', $password);
		} catch(Exception $e) {
			$this->renderJSONBms(true, $e->getMessage());
		}
	}

	public function actionResetPassword() {
        try {
            $opUserId = Yii::app()->user->id;
            $user = User::model()->findByPk($opUserId);
            $newPassword = $this->validateStringVal('newPassword', '');
			$comfirmPassword = $this->validateStringVal('comfirmPassword', '');
			$oldPassword = $this->validateStringVal('oldPassword', '');
            if(!empty($user)) {
                $password = $user->resetPassword($oldPassword, $newPassword, $comfirmPassword);
                $mailer = new BmsMailer();
                $mailer->sendMail('reset password', $newPassword, $user->email . ':' . $user->display_name);
                BmsLogger::info('user ' .$user->username . ' reset password');
            }
			$this->renderJSONBms(true,'OK');
        } catch(Exception $e) {
            $this->renderJSONBms(false, $e->getMessage());
        }
    }

	public function actionSave() {
		try {
            $opUserId = Yii::app()->user->id;
            $user = User::model()->findByPk($opUserId);
            if(!$user->admin) {
                BmsLogger::warning($opUserId . " try to add user ");
                throw new Exception ('不要做坏事，有记录的！！');
            }
			$id = $this->validateIntVal('id');
			$username = $this->validateStringVal('username');
			$displayName = $this->validateStringVal('display_name');
			$email = $this->validateStringVal('email');
			$cell = $this->validateStringVal('cell');
			$tele = $this->validateStringVal('telephone');
			$card = $this->validateStringVal('card_number');
			$card8H10D = $this->validateStringVal('card_8H10D');

			if(empty($username)) {
				throw new Exception("账号不能为空");
			}
			$exist = User::model()->find('username = ? AND id!=?', array($username,$id));
			if(!empty($exist)) {
				throw new Exception("$username 账号已存在");
			}
			if(empty($card)) {
				throw new Exception("工号不能为空");
			}
			if(empty($username)){
				throw new Exception("账号不可为空");
			}
			$exist = User::model()->find('card_number = ? AND id!=?', array($card,$id));
			if(!empty($exist)) {
				throw new Exception("$card 工号已存在");
			}
			if(empty($id)) {
				$user = new User();
			} else {
				$user = User::model()->findByPk($id);
			}
			$user->username = $username;
			$user->display_name = $displayName;
			$user->email = $email;
			$user->cellphone = $cell;
			$user->telephone = $tele;
			$user->card_number = $card;
			$user->card_8H10D = $card8H10D;
			if(empty($id)) {
				$password = $user->initPassword($card);
            	$mailer = new BmsMailer();
            	$mailer->sendMail('init password', $password, 'bmsadmin@163.com:admin,' . $user->email);
            	BmsLogger::info('init ' .$user->username .' password to ' . $password);
 			}
			$user->save();
			
            $this->renderJSONBms(true, 'OK');
        } catch(Exception $e) {
            $this->renderJSONBms(false, $e->getMessage());
        }
	}



	public function actionDisable() {
		try {
            $opUserId = Yii::app()->user->id;
            $user = User::model()->findByPk($opUserId);
			$userId = $this->validateIntVal('id',0);
            if(!$user->admin) {
                BmsLogger::warning($opUserId . " try to  disable @ user " .$userId);
                throw new Exception ('不要做坏事，有记录的！！');
            }
			if($user->id == $userId) {
				throw new Exception ('管理员不能删除自己');
			}
			
            $user = User::model()->findByPk($userId);
            if(!empty($user)) {
				$user->disable();
            }

			$this->renderJSONBms(true, 'OK');

        } catch(Exception $e) {
            $this->renderJSONBms(false, $e->getMessage());
        }


	}

	public function actionDebug() {
		$mailer = new BmsMailer();
        $mailer->sendMail('init password', 'test for smtp', 'bmsadmin@163.com:admin');
	}

	public function actionCheckCardNumber() {
		$number = $this->validateStringVal('cardNumber', '');
		try{
			$seeker = User::createSeeker();
			$data = $seeker->checkCardNumber($number);
			if(!empty($data)){
				$this->renderJsonBms(true, 'OK', $data);
			} else {
				$this->renderJsonBms(false, '用户不存在' , '');
			}
		} catch(Exception $e) {
			$this->renderJsonBms(false, $e->getMessage());
		}
	}
}
