<?php
require_once dirname(__FILE__) . '/../../library/Session.class.php';
/**
 *　ログインステータス
 */

class ModelLoginState {
    /**
     * ログインしていればtrue
     * @void
     */
    public function isLogin() {
        if($this->getLoginUserId() != ''){
            return true;
        }
        return false;
    }
	/**
	 * id取得
	 * @void
	 */
	public function getLoginUserId() {
		$session = Session::getInstance();
		return $session->get('user_id');
	}





}