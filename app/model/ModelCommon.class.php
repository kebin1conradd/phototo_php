<?php
require_once dirname(__FILE__) . '/../config/Config.class.php';
require_once dirname(__FILE__) . '/../library/Session.class.php';
require_once dirname(__FILE__) . '/../library/Cookie.class.php';

/**
 * ModelCommon 基本クラス
 */
class ModelCommon
{
	/**
	 *  コンストラクタ
	 */
	public function __construct() {
	}


	/**
	 * IPアドレスを取得
	 * @return string
	 */
	public function getIpAddress() {
        if ($_SERVER['HTTP_X_REAL_IP']) {
            $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
        } elseif ($_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ipaddress = trim(str_replace("unknown,", "", $_SERVER['HTTP_X_FORWARDED_FOR']));
        } else {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        }
		return $ipaddress;
	}

	/**
	 * UserAgent
	 * @return string
	 */
	public function getUserAgent() {
		return $_SERVER['HTTP_USER_AGENT'];
	}





}
