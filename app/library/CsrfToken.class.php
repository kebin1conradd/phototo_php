<?php

/*
 * csrf token
 * va;idateとクラス分けてるのは呼び出し時の柔軟な運用とValidateの変更加用性を保証するため
 */


class CsrfTokenGenerate
{
	private static $name = "GRACE";

    /*
     * tokenの作成
     * return string
     */
	public static function generate() {
		$session = Session::getInstance();
        $generated_key = base64_encode(openssl_random_pseudo_bytes(30));
		$session->set(self::$name.'B'.$generated_key,1);
		return $generated_key;
	}
    /*
     * tokenのvalidate
     * @param string token
     * return boolean
     */
	public static function validate($token) {
		$session = Session::getInstance();
		$session_data = $session->get(self::$name.'B'.$token);
		if (isset($session_data)) {
			$session->clear(self::$name.'B'.$token);
			return true;
		}

		return false;
	}
}

class CsrfTokenValidate
{
    /*
    * csrf tokenが有効か
    * false リクエストend
    * @param string $token
    * @return boolean
    */
    public function isAvailableToken($token){
        if (!isset($token) || !CsrfTokenGenerate::validate($token)) {
            return false;
        }
        return true;
    }
}
