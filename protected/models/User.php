<?php
Yii::import('application.models.UserSeeker');
class User extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_user':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $salt
	 * @var string $email
	 * @var string $profile
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, salt', 'required'),
			array('username, password, salt', 'length', 'max'=>128),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		//user must be not disabled
		if($this->isdelete) {
			throw new Exception ('you are disabled');
		}
		return $this->hashPassword($password,$this->salt)===$this->password;
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @param string salt
	 * @return string hash
	 */
	public function hashPassword($password,$salt)
	{
		return md5($salt.$password);
	}

	/**
	 * Generates a salt that can be used to generate a password hash.
	 * @return string the salt
	 */
	protected function generateSalt()
	{
		return uniqid('',true);
	}

	protected function generatePassword($length = 6) {
		// 密码字符集，可任意添加你需要的字符
    	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+|';

    	$password = '';
		$endPos = strlen($chars) - 1;
		for ( $i = 0; $i < $length; $i++ ) {
			$password .= $chars[ mt_rand(0, $endPos) ];
		}

		return $password;
	} 

	public function initPassword($default = '') {
		$password = empty($default) ? $this->generatePassword() : $default;
		$this->salt = $this->generateSalt();
        $this->password = $this->hashPassword($password, $this->salt );
        $this->save();
        return $password;
	}

	public function resetPassword($oldPassword, $newPassword, $comfirmPassword) {
		if(!$this->validatePassword($oldPassword)) {
			throw new Exception('原密码有误');
		}
		if(strlen($newPassword) < 6) {
			throw new Exception('新密码最少6字符');
		}
		if($newPassword !== $comfirmPassword) {
			throw new Exception('新密码和确认密码不一致');
		}
		$this->salt = $this->generateSalt();
        $this->password = $this->hashPassword($newPassword, $this->salt );
        $this->save();
        return $newPassword;
	}

	public function isAdmin() {
		return $this->admin == 1;
	}

	public function disable() {
		$this->isdelete = '1';
		$this->save();
	}

	public static function createSeeker() {
		$seeker = new UserSeeker();
		return $seeker;
	}

	public static function createByCard($card) {
		$user = self::model()->find('card_number=?',array($card));
		if(empty($user)) {
			throw new Exception($card . ' is not valid');
		}
        return $user;
    }

	public static function create($userId) {
		$user = self::model()->findByPk($userId);
        if(empty($user)) {
            throw new Exception($userId . ' is not valid');
        }
        return $user;
    }

	public function getCommonInfo() {
		return array(
			'id' => $this->id,
			'card_number' => $this->card_number,
			'username'    => $this->username,
			'email' => $this->email,
			'telephone' => $this->telephone,
			'cellphone' => $this->cellphone,
			'display_name' => $this->display_name,
			'admin' => $this->admin,
		);
	}
}
