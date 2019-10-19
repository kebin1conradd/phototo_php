<?php
require_once dirname(__FILE__) . '/../../library/PhpMailer/PHPMailerAutoload.php';
require_once dirname(__FILE__) . '/../../config/Config.class.php';
/**
 * ユーザー認証メール
 */

class ModelUserMail {


	const CHAR_SET = 'utf-8';
	const HOST = 'localhost';
	const PORT = 25;
	const SMTP_SECURE ='tls';
    const FROM = 'admin@phototo.nl';
    const SUBJECT = 'フォトット 会員登録認証メール';
	private $mail;

    /*
     * コンストラクタ
     */
    public function __construct() {
        $this->mail = new PHPMailer();

        $this->mail->CharSet    = self::CHAR_SET;
        $this->mail->Host       = self::HOST;
        $this->mail->Port       = self::PORT;
        $this->mail->Username = self::FROM;
        $this->mail->isSMTP();
        $this->mail->setFrom(self::FROM);
        $this->mail->addReplyTo(self::FROM);
    }
	/**
	 * ユーザー認証メール送信
	 * @param string $activation_code
	 * @param string $mail_address
	 * @param int $reserved_user_id
	 * void
	 */
	public function sendActivateMail($activation_code,$mail_address,$reserved_user_id){
		$log = new Log();

		$body = $this->getActivateMessage($activation_code,$reserved_user_id);
		$subject = self::SUBJECT;

		$result = $this->send($subject, $body, $mail_address);
		if(!$result){
			$error = $this->mail->ErrorInfo;
			$log->write('MailSendError',$error);
		}
	}

	/*
	 * 送信メッセージ
	 * @param string activation_code
	 * @param int reserved_user_id
	 * @return string
	 */
	private function getActivateMessage($activation_code,$reserved_user_id){
		$config = Config::getInstance();
		return 'このたびは、フォトットへの登録ありがとうございます
　　　確認のため、下記URLへアクセスしアカウントの登録を完了させてください。
		http://'.$config->getDomain().'/user/mailconfirmation/?activation_code='.$activation_code.'&reserved_user_id='.$reserved_user_id;
	}

    /*
 　* 送信
 　* @param string $subject
 　* @param string $body
　 * @param string $to
　 * @return mixed
　 */
    public function send($subject, $body, $to) {
        $this->mail->addAddress($to);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        return $this->mail->send();
    }



}