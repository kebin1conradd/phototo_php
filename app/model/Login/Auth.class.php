<?php
require_once dirname(__FILE__) . '/../DataBaseMapper/UsersToken.class.php';
require_once dirname(__FILE__) . '/../ModelCommon.class.php';
require_once dirname(__FILE__) . '/Cookie.class.php';
require_once dirname(__FILE__) . '/../User/Hash.class.php';
require_once dirname(__FILE__) . '/../DataBaseMapper/Users.class.php';
require_once dirname(__FILE__) . '/../../library/Cookie.class.php';
/**
 * フォトット認証
 */

class ModelLoginAuth {

	//エラーメッセージ
	private $message;
    //キャッシュ情報保持用
    private $user_info_cache;
	/**
	 * 通常
	 * @param string $mail_address
	 * @param string $password
	 * @return boolean
	 */
	public function login($mail_address, $password) {
		$model_common = new ModelCommon();
		$model_user_hash = new ModelUserHash();
		$users = new ModelDataBaseMapperUsers();
		$login_ip_address = $model_common->getIpAddress();

		if ($mail_address == "" || $password == "") {
			return false;
		}

		$password_hash = $model_user_hash->createPasswordHash($password, $this->getTripFromMail($mail_address));
		$rows = $users->getUserInfoFromLogin($mail_address, $password_hash);
        if(!$rows){
            $this->setBadInputMessage();
            return false;
        }
		$this->setUserInfoSession($rows);

		$this->setLoginStatus($rows['user_id'],$login_ip_address);

		return true;
	}
    /**
     * 自動
     * return boolean
     */
    public function loginAutomation() {
        $session = Session::getInstance();

        $users_token = new ModelDataBaseMapperUsersToken();
        $model_login_cookie = new ModelLoginCookie();
        $user_id = $model_login_cookie->getUserIdFromCookie();
        $token = $model_login_cookie->getTokenFromCookie();
        if ($user_id == "" || $token == "") {
            return false;
        }
        //tokenテーブルにデータが存在するか
        if (!$users_token->getAutoLogin($user_id,$_SERVER['HTTP_USER_AGENT'], $token)) {

            $model_login_cookie->deleteCookie();
            $session->destroy();
            $this->setTimeoutMessage();
            return false;
        }

        if (!$this->checkAutoLoginUserStatus($user_id)) {
            return false;
        }
        // セッションに値を保存
        $this->setUserInfoSession($user_id);
        $model_login_cookie->setAutoLoginCookie($user_id, $token);

        return true;
    }
	/**
	 * 初期
	 * @param string $mail_address
	 * @param string $password
	 */
	public function loginFirst($user_id) {
        $users = new ModelDataBaseMapperUsers();
		$model_common = new ModelCommon();
		$login_ip_address = $model_common->getIpAddress();
		$rows = $users->getUserInfo($user_id);
		$this->setUserInfoSession($rows);
		$this->setLoginStatus($user_id,$login_ip_address);
	}



	/**
	 * ログアウト
	 * @param int $user_id
	 * void
	 */
	public function logout($user_id) {
		$users_token = new ModelDataBaseMapperUsersToken();
		$session = Session::getInstance();
		$model_login_cookie = new ModelLoginCookie();

		$users_token->deleteToken($user_id);
		$session->destroy();
		$model_login_cookie->deleteCookie();

	}
	/*
	 * ユーザー状態の検証
	 * @param int $user_id
	 * @return boolean
	 */
	private function checkAutoLoginUserStatus($user_id) {
		$session = Session::getInstance();
		$model_login_cookie = new ModelLoginCookie();
		if (!$this->isAvailableUser($user_id)) {
			$model_login_cookie->deleteCookie();
			$session->destroy();
			$this->setNotFoundMessage();
			return false;
		}

		return true;
	}
    /**
     * セッションの取得
     * $param int $user_id
     * @void
     */
    public function setUserInfoSession($user_id){
        $session  = Session::getInstance();
        $rows = $this->getLoginUserInfo($user_id);
        $session->set('user_id', $rows['user_id']);
    }


    /*＊
     * ログインユーザー情報取得クラス内キャッシュ
     * @param int $user_id ログインユーザーのid
     * return mixed
     */
    public function getLoginUserInfo($user_id) {
        if (!$this->user_info_cache) {
            $users = new ModelDataBaseMapperUsers();
            $this->user_info_cache = $users->getUserInfo($user_id);
        }

        return $this->user_info_cache;
    }
    /*＊
     * ログインユーザー情報取得クラス内でキャッシュしてやる
     * @param string $mailo_address
     * return mixed
     */
    public function getLoginUserInfoFromMail($mail_address) {
        if (!$this->user_info_cache) {
            $users = new ModelDataBaseMapperUsers();
            $this->user_info_cache = $users->getUserInfoFromMail($mail_address);
        }

        return $this->user_info_cache;
    }

    /*＊
     * ip_tripをメールアドレスから取得
     * @param string
     * return string
     */
    public function getTripFromMail($mail_address) {
        $rows = $this->getLoginUserInfoFromMail($mail_address);
        if($rows['ip_trip'] != ''){
            return $rows['ip_trip'];
        }
        else{
            return '';
        }
    }
    /**
     * DBにそのユーザーが存在するか
     * @param int $user_id
     * @return boolean
     */
    protected function isAvailableUser($user_id){
        $rows = $this->getLoginUserInfo($user_id);

        return  ($rows);
    }

    /*
     * ログイン成功時に関連情報をCookieやTokenなど各種DBにINSERT updateしてSET
     * @param int $user_id
     * @param string $login_ip_address
     * void
     */
    protected function setLoginStatus($user_id,$login_ip_address){
        $users = new ModelDataBaseMapperUsers();
        $users_token = new ModelDataBaseMapperUsersToken();
        $model_login_cookie = new ModelLoginCookie();
        $model_user_hash = new ModelUserHash();
        $token = $model_user_hash->createToken($user_id);
        $model_login_cookie->setAutoLoginCookie($user_id, $token);
        $users->updateUserLoginIp($user_id, $login_ip_address);
        $users_token->insertOnDuplicateUserToken($user_id, $_SERVER['HTTP_USER_AGENT'], $token);
    }


    /*
     * パスワードかメアドが違う
     * void
     */
    protected function  setBadInputMessage(){
        $this->message = "e-mailアドレス or passwordが間違っています";
    }
    /*
     * セッションtimeout
     * void
     */
    protected function  setTimeoutMessage() {
        $this->message = "セッションがTimeOutしました。";
    }
    /*
     * ユーザーが存在しない
     * void
     */
    protected function  setNotFoundMessage(){
        $this->message = "セッション情報が正しくありません。";
    }

	public function getMessage(){
		return $this->message;
	}
}
