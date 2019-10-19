<?php
require_once dirname(__FILE__) . '/../../library/Validation.class.php';
require_once dirname(__FILE__) . '/../DataBaseMapper/Users.class.php';
require_once dirname(__FILE__) . '/../DataBaseMapper/UsersActivation.class.php';
/**
 * user関連のvalidate
 */

class ModelUserValidator {

	private static $password_min_length = 6;

	/**
	 * コンストラクタ
	 * @param
	 */
	public function __construct() {
		$this->ValidationBase =  new Validation();
	}
    /**
     * 有効でないユーザー名
     * @param string $user_name
     * @return boolean
     */
    public function isInvalidUserName($user_name){
        if($this->ValidationBase->isIncludeSpace($user_name)){
            return true;
        }
        else{
            return false;
        }
    }
	/**
	 * 有効でないメールアドレス文字列
	 * @param string $mail_address
	 * @return boolean
	 */
	public function isInvalidMail($mail_address){
		if($this->ValidationBase->isMailAddress($mail_address)){
			return false;
		}
		else{
			return true;
		}
	}

	/**
	 * 有効でないパスワード
	 * @param string $password
	 * @return boolean
	 */
	public function isInvalidPassword($password){
		if($this->ValidationBase->isAlphaNumeric($password)){
			return false;
		}
		else{
			return true;
		}
	}

	/**
	 * パスワードが最少文字列に満たない
	 * @param string $password
	 * @return boolean
	 */
	public function isLessLengthPassword($password){
		if($this->ValidationBase->isLessWordLength($password,self::$password_min_length)){
			return true;
		}
		else{
			return false;
		}
	}
	/**
	 * 重複するMailが存在
	 * @param string $mail_address
	 * @return boolean
	 */
	public function isDuplicateMail($mail_address){
		$users = new ModelDataBaseMapperUsers();
		$rows = $users->getUserInfoFromMail($mail_address);
		if($rows['user_id'] != ''){
			return true;
		}
		else{
			return false;
		}
	}


	/**
	 * 重複するmailが存在
	 * @param string $mail_address
	 * @return boolean
	 */
	public function isDuplicateMailPre($mail_address){
		$users_activation = new ModelDataBaseMapperUsersActivation();
		$rows = $users_activation->getUserInfoFromMail($mail_address);
		if($rows['reserved_user_id'] != ''){
			return true;
		}
		else{
			return false;
		}
	}
}