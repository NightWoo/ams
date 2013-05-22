<?php
class BmsMailer extends PHPMailer {
	// Set default variables for all new objects
	var $Enable = true;
	var $From = "";
	var $FromName = "";
	var $Host = "";
	var $Sender = "";
	var $Port = 25;
	var $Mailer = "mail";
	var $WordWrap = 75;
	var $SMTPAuth = true;
	var $Username = '';
	var $Password = '';
	var $SMTPDebug = false;
	public function __construct() {
		$this->IsSMTP();
		// $this->SMTPSecure = "ssl";
		$this->Enable = Yii::app()->params['mail_enable'];
		$this->From = Yii::app()->params['mail_from'];
		$this->FromName = Yii::app()->params['mail_fromName'];
		$this->Sender = $this->From;
		$this->Host = Yii::app()->params['mail_host'];
		$this->Port = Yii::app()->params['mail_port'];
		$this->Mailer = Yii::app()->params['mailer'];
		$this->CharSet = 'utf-8';
		if($this->Mailer === 'smtp') {
			$this->SMTPDebug = Yii::app()->params['smtp_debug'];		
			$this->Username = Yii::app()->params['smtp_username'];
			$this->Password = Yii::app()->params['smtp_password'];
		}
	}
	
	// Replace the default error_handler
	function error_handler($msg) {
		throw new Exception ($msg);
	}

	public function sendMail($subject, $content, $receivers) {
		if(!$this->Enable) {
			return 'not enable';
		}
		if(empty($receivers)) {
			return 'no receivers';
		}
		if(!empty(Yii::app()->params['SEND_ALL_MAIL_TO'])) {
			$receiverConent = $receivers . ' change to ' ;
            $receivers = Yii::app()->params['SEND_ALL_MAIL_TO'];
			$receiverConent .= $receivers . "</br>";
			$content = $receiverConent . $content;
        }

		$receivers = explode(',', $receivers);
		foreach($receivers as $receiver) {
			$receiverInfos = explode(':', $receiver);
			$receiverName = empty($receiverInfos[1]) ? $receiverInfos[0] : $receiverInfos[1];
			$this->AddAddress($receiverInfos[0], $receiverName);
		}
		$this->Subject = $subject;
		$this->Body = $content;
		$ret = $this->Send(); // send message
		return $ret;
	}
}
