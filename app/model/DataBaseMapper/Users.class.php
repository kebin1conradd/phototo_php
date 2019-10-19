<?php
require_once dirname(__FILE__) . '/Base.class.php';
	
/**
 * DataBaseMapperUsersクラス
 */

class ModelDataBaseMapperUsers extends ModelDataBaseMapperBase{
	
	/**
	 * テーブル名
	 */
    public $table = 'users';

	/**
	 * 特定ユーザーidを一行選んで取得
	 * @param int $user_id ユーザーid
	 * @return mixed
	 */
	public function getUserInfo($user_id) {
		$param = array("user_id" => $user_id);

		return $this->selectFirstColumn($param);
	}

	/**
	 * login情報で取得
	 * @param string  $mail_address メール
	 * @param string  $password_hash
	 * @return mixed
	 */
	public function getUserInfoFromLogin($mail_address,$password_hash) {
		$target = "user_id";
		$param = array("mail_address" => $mail_address,"password_hash" => $password_hash);
		return $this->selectFirstColumn($param,$target);
	}



	/**
	 * 特定のユーザー情報をmail_addressから取得
	 * @param $mail_address
	 * @return mixed
	 */
	public function getUserInfoFromMail($mail_address) {
		$param = array("mail_address" => $mail_address);

		return $this->selectFirstColumn($param);
	}

	/**
	 * ユーザー情報の登録
	 * void
	 */
	public function insertUserOrigin($password_hash,$user_name,$mail_address,$ip_address,$ip_trip) {
		$insert_value = array(
			'password_hash' => $password_hash,
			'user_name'     => $user_name,
			'mail_address'  => $mail_address,
			'add_date'      => date("Y-m-d H:i:s"),
			'register_ip_address'    => $ip_address,
			'ip_trip'       => $ip_trip,
		);
		$this->insert($insert_value);
	}


	/**
	 * 特定ユーザーNoのログインIP更新
	 * @param int $user_id ユーザーid
	 * @param string $login_ip_address
	 * void
	 */
	public function updateUserLoginIp($user_id,$login_ip_address) {
		$update_value = array(
			'login_ip_address' => $login_ip_address,
		);

		$where = array('user_id' => $user_id);
		$this->update($update_value, $where, 1);
	}





}