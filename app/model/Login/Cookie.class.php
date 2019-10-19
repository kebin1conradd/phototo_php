<?php
require_once dirname(__FILE__) . '/../../library/Cookie.class.php';
require_once dirname(__FILE__) . '/../../config/Config.class.php';
/**
 * cookie操作
 */

class ModelLoginCookie{

    private $config;
	//保存日数
	const SAVE_DAYS = 30;
	private $cookie_temp;
    private $cookie;
    public function __construct(){
        $this->config = $config = Config::getInstance();
        $this->cookie = new Cookie();
    }

	/**
	 * 自動ログインcookieを取得
	 * @return array
	 */
	public function getAutoLoginCookie(){
		if(!$this->cookie_temp){
			$this->cookie_temp = $this->cookie->getArray($this->config->getCookieNameArray());
		}
		return $this->cookie_temp;
	}
	/*
	 * user_idの取得
	 * @return string
	 */
	public function getUserIdFromCookie() {
        $user_cookie = $this->getAutoLoginCookie();
		return $user_cookie[$this->config->getCookieNameId()];

	}
	/*
	 * tokenの取得
	 * @return string
	 */
	public function getTokenFromCookie() {
		$user_cookie = $this->getAutoLoginCookie();
		return $user_cookie[$this->config->getCookieNameToken()];
	}
	/*
	 * user自動ログイン情報cookieの削除
	 * void
	 */
	public function deleteCookie(){
		$this->cookie->delete($this->config->getCookieNameArray());
	}
	/*
	 * 自動ログインcookieを保存
	 * @param int $user_id
	 * @param string $token
	 * void
	 */
	public function setAutoLoginCookie($user_id,$token){
        $term = time() + 3600 * 24 * self::SAVE_DAYS;
		// クッキーの処理
		$user_cookie[$this->config->getCookieNameId()] = $user_id;
		$user_cookie[$this->config->getCookieNameToken()]  = $token;
        $this->cookie->setArray($this->config->getCookieNameArray(),$user_cookie,$term);
	}

}