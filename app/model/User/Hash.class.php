<?php
/**
 * ユーザー情報の暗号化
 */

class ModelUserHash {
	protected static $PASSWORD_SALT = 'photohitoiki';
	protected static $ACTIVATE_SALT = 'v198ght';
	protected static $IP_SALT = 'phortranV98AbD';
	protected static $users_token_SALT = 'photocchi';


	/**
	 * ipをハッシュ化
	 * @param $ip_address string IPアドレス
	 * @return string
	 */
	public function createTrip($ip_address){
		$salt = substr(self::$IP_SALT.$ip_address, -18);
		$trip = md5($salt.$ip_address);

		return substr($trip, -10);
	}

	/**
	 * パスワードを固定文字列と登録ipから算定したipハッシュでハッシュ化
	 * @param $raw_password string 生のパスワード情報
	 * @param $ip_trip string ipから生成しDBに保存してあるtrip
	 * @return string
	 */
	public function createPasswordHash($raw_password,$ip_trip){
		$result = hash('sha256',self::$PASSWORD_SALT .$ip_trip.$raw_password);
		return $result;
	}
	/**
	 * LoginToken 作成
	 * @param int $user_id
	 * @return string
	 */
	public function createToken($user_id){
		$token = bin2hex($user_id.openssl_random_pseudo_bytes(20));
		return substr($token,0,32);
	}


	/**
	 * パスワードを固定文字列と登録ipから算定したipハッシュでハッシュ化
	 * @param $mail_address　string
	 * @param $ip_trip string ipから生成しDBに保存してあるtrip
	 * @return string
	 */
	public function createActivateCode($mail_address,$ip_trip){
		$result = hash('sha256', self::$ACTIVATE_SALT .$ip_trip.$mail_address);
		return $result;
	}
}