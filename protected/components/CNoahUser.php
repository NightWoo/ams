<?php
/**
 * CNoahUser class file
 */

require_once(dirname(dirname(__FILE__)) . '/config.php');

/**
 * CNoahUser represents the persistent state for a Web application user.
 *
 * CNoahUser is used as an application component whose ID is 'user'.
 * Therefore, at any place one can access the user state via
 * <code>Yii::app()->user</code>.
 *
 * The property {@link id} and {@link name} are both unique identifiers
 * for the user. The former is mainly used internally (e.g. primary key), while
 * the latter is for display purpose (e.g. username).  is a unique identifier for a user that is persistent
 * during the whole user session. It can be a username, or something else,
 *
 * Both {@link id} and {@link name} are persistent during the user session.
 */
class CNoahUser extends CApplicationComponent //implements IWebUser
{
	/**
	 * @var boolean whether to enable cookie-based login. Defaults to false.
	 */
	public $allowAutoLogin=false;

	/**
	 * @var string the name for a guest user. Defaults to 'Guest'.
	 * This is used by {@link getName} when the current user is a guest (not authenticated).
	 */
	public $guestName='guest';

	/**
	 * @var string|array the URL for login. If using array, the first element should be
	 * the route to the login action, and the rest name-value pairs are GET parameters
	 * to construct the login URL (e.g. array('site/login')). If this property is null,
	 * a 403 HTTP exception will be raised instead.
	 * @see CController::createUrl
	 */
	public $loginUrl=array('site/login');

	/**
	 * @var array the property values (in name-value pairs) used to initialize the identity cookie.
	 * Any property of {@link CHttpCookie} may be initialized.
	 * This property is effective only when {@link allowAutoLogin} is true.
	 * @since 1.0.5
	 */
	public $identityCookie;

	private $_keyPrefix;
	private $_access=array();
	private $_id;
	private $_name;
	private $_SKEY_ARRAY = array();
	private $_SKEY_HEAD_ID = 0;

	/**
	 * Initializes the application component.
	 * performing cookie-based authentication if enabled, and updating the flash variables.
	 */
	public function init()
	{
		parent::init();
		$this->loadAllSKey();
		$this->restoreFromCookie();
	}
	
	/**
	 * Load all SKEY from file ../../config/SKEY (for generating signature cookie)
	 *  to $_SKEY_ARRAY
	 */
	private function loadAllSKey()
	{
		$keyfile_name = dirname(__FILE__) . '/../../config/SKEY';
		$keyfile=@fopen($keyfile_name,"r");
		$keyfile_stat = @stat($keyfile_name);
		if ($keyfile && $keyfile_stat) {
			$keyfile_ctime = $keyfile_stat['ctime'];
			$current = time();
			$lineNum = -1;
			while (!feof($keyfile))
			{
				++$lineNum;
				$line = trim(fgets($keyfile), "\n");
				if (!empty($line)) {
					if (abs($current - $keyfile_ctime ) < 3600 && $lineNum == 0) {
						continue;
					}
					$this->_SKEY_ARRAY[] = $line;
				}
			}
			fclose($keyfile);
			
			//if the key file was recently updated ( <1 hour ), use the old key for cookie generating
			if (abs($current - $keyfile_ctime ) < 3600 && count($this->_SKEY_ARRAY) > 1) {
				$this->_SKEY_HEAD_ID = 1;
			} else {
				$this->_SKEY_HEAD_ID = 0;
			}
		} 

		if (count($this->_SKEY_ARRAY) == 0) {
			$this->_SKEY_ARRAY[] = "dEfaUltSKy";
		}
	}

	/**
	 * Logs in a user.
	 *
	 * The user identity information will be saved in storage that is
	 * persistent during the user session. By default, the storage is simply
	 * the cookie storage. If the duration parameter is greater than 0,
	 * a cookie will be sent to prepare for cookie-based login in future.
	 *
	 * @param string the user name (which should already be authenticated)
	 * @param integer the user id (which should already be authenticated)
	 * @param integer number of seconds that the user can remain in logged-in status. Defaults to 0, meaning login till the user closes the browser.
	 * If greater than 0, cookie-based login will be used. In this case, {@link allowAutoLogin}
	 * must be set true, otherwise an exception will be thrown.
	 */
	public function login($name, $id, $duration=0)
	{
		$this->changeIdentity($id, $name);
		$this->saveToCookie($duration);
	}

	/**
	 * Logs out the current user.
	 */
	public function logout()
	{
		//Yii::app()->getRequest()->getCookies()->remove($this->getStateKeyPrefix());
		//Yii::app()->getRequest()->getCookies()->clear();
		$this->setId(null);
		$this->setName(null);

		$name = $this->getStateKeyPrefix();
		$domain = DOMAIN_NAME;
		setcookie($name, false, 0, '/', $domain);
	}

	/**
	 * @return boolean whether the current application user is a guest.
	 */
	public function getIsGuest()
	{
		return $this->_id===null;
	}

	/**
	 * @return mixed the unique identifier for the user. If null, it means the user is a guest.
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	/**
	 * @return : client ip
	 */
	public function getIP()
	{
		if (isset($_SERVER['HTTP_CLIENTIP'])) return $_SERVER['HTTP_CLIENTIP'];
		if (isset($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
		return 'UNKNOWN_IP';
	}

	/**
	 * @param mixed the unique identifier for the user. If null, it means the user is a guest.
	 */
	public function setId($value)
	{
		$this->_id = $value;
	}

	/**
	 * Returns the unique identifier for the user (e.g. username).
	 * This is the unique identifier that is mainly used for display purpose.
	 * @return string the user name. If the user is not logged in, this will be {@link guestName}.
	 */
	public function getName()
	{
		if(($name=$this->_name)!==null) {
			return $name;
		}
		else {
			return $this->guestName;
		}
	}

	/**
	 * Sets the unique identifier for the user (e.g. username).
	 * @param string the user name.
	 * @see getName
	 */
	public function setName($value)
	{
		$this->_name = $value;
	}

	/**
	 * Returns the URL that the user should be redirected to after successful login.
	 * This property is usually used by the login action. If the login is successful,
	 * the action should read this property and use it to redirect the user browser.
	 * @return string the URL that the user should be redirected to after login. Defaults to the application entry URL.
	 * @see loginRequired
	 */
	public function getReturnUrl()
	{
		return Yii::app()->getRequest()->getScriptUrl();
	}

	/**
	 * @param string the URL that the user should be redirected to after login.
	 */
	public function setReturnUrl($value)
	{
	}

	/**
	 * Redirects the user browser to the login page.
	 * Before the redirection, the current URL will be kept in {@link returnUrl}
	 * so that the user browser may be redirected back to the current page after successful login.
	 * Make sure you set {@link loginUrl} so that the user browser
	 * can be redirected to the specified login URL after calling this method.
	 * After calling this method, the current request processing will be terminated.
	 */
	public function loginRequired()
	{
		$app=Yii::app();
		$request=$app->getRequest();
		$this->setReturnUrl($request->getUrl());
		if(($url=$this->loginUrl)!==null)
		{
			if(is_array($url))
			{
				$route=isset($url[0]) ? $url[0] : $app->defaultController;
				$url=$app->createUrl($route,array_splice($url,1));
			}
			$request->redirect($url);
		}
		else
			throw new CHttpException(403,Yii::t('yii','Login Required'));
	}

	/**
	 * Populates the current user object with the information obtained from cookie.
	 * This method is used when automatic login ({@link allowAutoLogin}) is enabled.
	 * The user identity information is recovered from cookie.
	 * Sufficient security measures are used to prevent cookie data from being tampered.
	 * @see saveToCookie
	 */
	protected function restoreFromCookie()
	{
		$data = self::getCookieValue($this->getStateKeyPrefix());
		if($data!==false)
		{
			$data=unserialize($data);
			if(isset($data[0],$data[1]))
			{
				list($id,$name)=$data;
				$this->changeIdentity($id,$name);
				if (!self::verifySignature()) {
					$this->logout();
				}
			}
		}
	}

	/**
	 * Saves necessary user data into a cookie.
	 * This method is used when automatic login ({@link allowAutoLogin}) is enabled.
	 * This method saves user ID, username and a validation key to cookie.
	 * These information are used to do authentication next time when user visits the application.
	 * @param integer number of seconds that the user can remain in logged-in status. Defaults to 0, meaning login till the user closes the browser.
	 * @see restoreFromCookie
	 */
	protected function saveToCookie($duration)
	{
		$app=Yii::app();
		$cookie=$this->createIdentityCookie($this->getStateKeyPrefix());
		$cookie->domain = DOMAIN_NAME;
		if ($duration > 0) {
			$cookie->expire=time()+$duration;
		}
		else {
			$cookie->expire=0;
		}
		$data=array(
			$this->getId(),
			$this->getName(),
		);
		$cookie->value=serialize($data);
		setcookie($cookie->name, $cookie->value, $cookie->expire, '/', $cookie->domain);
		self::saveSignatureCookie();
		//$app->getRequest()->getCookies()->add($cookie->name,$cookie);
	}
	
	
	/**
	 * generate signature cookie , save to client
	 * @input/output : void
	 */
	protected function saveSignatureCookie()
	{
		$generate_time = time();
		$ttl = $generate_time + SIGNATURE_COOKIE_MAX_LIFETIME;

		setcookie('NOAH_SIGNATURE', 
					$generate_time."," . $generate_time."," . self::getSignatureCKEY($generate_time, $generate_time)."," . self::getIP(), 
					$ttl, '/', DOMAIN_NAME);
	}
	
	/**
	 * Get expected hash CKEY for client
	 * @SKEYID : id for SKEY, 0 for the newest, -1 for auto choosing head key ( see self::loadAllSKey() )
	 * return : [string] client key
	 */
	protected function getSignatureCKEY($generate_time, $refresh_time, $SKEYID = -1)
	{
		if ($SKEYID == -1) $SKEYID = $this->_SKEY_HEAD_ID;
		$agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : "";
		return sha1($generate_time.$this->_name.$this->_SKEY_ARRAY[$SKEYID].$agent.$refresh_time);
	}
	
	/**
	 * verify noah signature cookie
	 * @update : if update=true, renew signture anyway
	 * @return : true if valid
	 */
	public function verifySignature($update = false)
	{
		$current = time();
		
		// get signature cookies
		$CKEY_STR = self::getCookieValue('NOAH_SIGNATURE');
		if ($CKEY_STR === false) return false;
		
		// split cookie_str into needed parts
		$CKEY_DATA = explode(",", $CKEY_STR);
		if (count($CKEY_DATA) != 4) return false;
		$generate_time = $CKEY_DATA[0];  
		$refresh_time = $CKEY_DATA[1];  
		$CKEY = $CKEY_DATA[2];
		
		// see if expired, or need renew
		if ($generate_time === false && abs($generate_time - $current) > SIGNATURE_COOKIE_MAX_LIFETIME) return false;
		if ($refresh_time === false && abs($refresh_time - $current) > SIGNATURE_COOKIE_EXPIRE_TIME) return false;
		if (abs($refresh_time - $current) > SIGNATURE_COOKIE_RENEW_CYCLE) $update = true; //update key every 15min
		

		// check legality, also try using lagecy key(s), if match old key, renew by current.
		for ($i = 0; $i < count($this->_SKEY_ARRAY); ++$i) {
			if (self::getSignatureCKEY($generate_time, $refresh_time, $i) == $CKEY) {
				if ($update || $i > $this->_SKEY_HEAD_ID) {
					$ttl = $generate_time + SIGNATURE_COOKIE_MAX_LIFETIME;
					@setcookie('NOAH_SIGNATURE', 
								$generate_time."," . $current."," . self::getSignatureCKEY($generate_time, $current)."," . self::getIP(), 
								$ttl, '/', DOMAIN_NAME);
				}
				return true;
			}
		}
		
		// illegal, expire CKEY cookie
		setcookie('NOAH_SIGNATURE', "0", 1, '/', DOMAIN_NAME);
		
		return false;
	}
	
	/**
	 * get cookie value by name
	 * @return : cookie value,   (false if not found)
	 */
	private function getCookieValue($name)
	{
		$cookie=Yii::app()->getRequest()->getCookies()->itemAt($name);
		if($cookie && !empty($cookie->value) && ($data=$cookie->value)!==false) {
			return $data;
		} else {
			return false;
		}
	}
	
	/**
	 * Creates a cookie to store identity information.
	 * @param string the cookie name
	 * @return CHttpCookie the cookie used to store identity information
	 * @teIdentityCookiesince 1.0.5
	 */
	protected function createIdentityCookie($name)
	{
		$cookie=new CHttpCookie($name,'');
		if(is_array($this->identityCookie))
		{
			foreach($this->identityCookie as $name=>$value)
				$cookie->$name=$value;
		}
		return $cookie;
	}

	/**
	 * @return string a prefix for the name of the session variables storing user session data.
	 */
	protected function getStateKeyPrefix()
	{
		if($this->_keyPrefix!==null)
			return $this->_keyPrefix;
		else
			//return $this->_keyPrefix=md5('Yii.'.get_class($this).'.'.Yii::app()->getId());
			return $this->_keyPrefix='NOAH_COMMON_USER';
	}

	/**
	 * Changes the current user with the specified identity information.
	 * This method is called by {@link login} and {@link restoreFromCookie}
	 * when the current user needs to be populated with the corresponding
	 * identity information. Derived classes may override this method
	 * by retrieving additional user-related information. Make sure the
	 * parent implementation is called first.
	 * @param mixed a unique identifier for the user
	 * @param string the display name for the user
	 * @param array identity states
	 */
	protected function changeIdentity($id,$name)
	{
		$this->setId($id);
		$this->setName($name);
	}	

	/**
	 * Performs access check for this user.
	 * @param string the name of the operation that need access check.
	 * @param array name-value pairs that would be passed to business rules associated
	 * with the tasks and roles assigned to the user.
	 * @param boolean whether to allow caching the result of access checki.
	 * This parameter has been available since version 1.0.5. When this parameter
	 * is true (default), if the access check of an operation was performed before,
	 * its result will be directly returned when calling this method to check the same operation.
	 * If this parameter is false, this method will always call {@link CAuthManager::checkAccess}
	 * to obtain the up-to-date access result. Note that this caching is effective
	 * only within the same request.
	 * @return boolean whether the operations can be performed by this user.
	 */
	public function checkAccess($operation,$params=array(),$allowCaching=true)
	{
		if($allowCaching && isset($this->_access[$operation]))
			return $this->_access[$operation];
		else
			return $this->_access[$operation]=Yii::app()->getAuthManager()->checkAccess($operation,$this->getId(),$params);
	}
}
