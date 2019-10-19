<?php

/**
 * cookieクラス
 * Cookieの簡単操作ライブラリ
 */


class Cookie {

    const DEF_TIME = '+2 week';

	/**
	 * cookieにitemをセット
	 * @param string $name 名前
	 * @param string $value 保存する値
	 * @param int $time 保存する期間
	 */
	
	public function set($name,$value,$time=null){
		if(empty($time)){
			$time = strtotime(self::DEF_TIME);
		}	
		setcookie($name,$value,$time,'/');
		$_COOKIE[$name] = $value;
	}

	/**
	 * 配列セット
	 * @param string $name 名前
	 * @param array $array 保存する配列
	 * @param int $time 保存する期間
	 */

	public function setArray($name,$array ,$time=null){
		if(empty($time)){
			$time = strtotime(self::DEF_TIME);
		}
		$value = $this->getCookieStringFromArray($array);

		setcookie($name,$value ,$time,'/');
		$_COOKIE[$name] = $value;
	}
	/*
	 * cookie削除
	 * @param string $name クッキーの名前
	 */
	public function delete($name){
		setcookie($name,'',0,'/');
		$_COOKIE[$name] = '';
	}

	/**
	 * cookie取得
	 * @param string $name クッキーの名前
	 * @return string
	 */
	public function get($name){
		return $_COOKIE[$name];
	}
	/**
	 * cookieを取得して配列に
	 * @param string $name cookie文字列
	 * return array
	 */
	public function getArray($name){
		$user_cookie = array();

		foreach(preg_split("/&/", $this->get($name)) as $val){
			$temp = array();
			preg_match('/(.*)=(.*)/i', $val, $temp);
			$user_cookie[$temp[1]] = $temp[2];
		}

		return $user_cookie;
	}
	/**
	 * cookieに格納するための配列を1文に
	 * @param array $array 変換配列
	 * @return string
	 */
	private function getCookieStringFromArray($array){
		$cookie_str = "";

		foreach($array as $key => $tmp){
			if($key!=""){
				$cookie_str .=  $key."=".$tmp."&";
			}
		}
		return $cookie_str;
	}
}
