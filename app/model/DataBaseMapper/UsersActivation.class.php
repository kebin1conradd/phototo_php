<?php
require_once dirname(__FILE__) . '/Base.class.php';
	
/**
 * DataBaseMapperUserActivationクラス
 */

class ModelDataBaseMapperUsersActivation extends ModelDataBaseMapperBase{
	
	/**
	 * テーブル名
	 */
    public $table = 'users_activation';

	/**
	 * ativate_hashに該当するデータをSELECT
	 * @param int $activation_code
	 * @param int $reserved_user_id
	 * @return mixed
	 */
	public function getUserInfoFromActivate($activation_code,$reserved_user_id) {
		$param = array("activation_code" => $activation_code,"reserved_user_id" => $reserved_user_id,"is_activated" => 0);
		return $this->selectFirstColumn($param);
	}

	/**
	 * 特定のユーザー情報をreserved_user_idから取得
	 * @param int $reserved_user_id
	 * @return mixed
	 */
	public function getUserInfoFromPreId($reserved_user_id) {
		$param = array("reserved_user_id" => $reserved_user_id);
		return $this->selectFirstColumn($param);
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
	 * 仮ユーザー情報の登録
	 * void
	 */
	public function insertUser($password_hash,$user_name,$mail_address,$ip_address,$activation_code) {
		$insert_value = array(
			'password_hash' => $password_hash,
			'user_name'     => $user_name,
			'mail_address'  => $mail_address,
			'ip_address'    => $ip_address,
			'activation_code' => $activation_code,
			'is_activated'       => 0,
			'is_send'       => 0,
			'add_date'      => date("Y-m-d H:i:s")
		);
		$this->insert($insert_value);
	}


	/**
	 * 特定予約済みユーザーidのメール送信済みフラグをたてる
	 * @param int $reserved_user_id 予約済みユーザーid
	 * void
	 */
	public function updateIsSend($reserved_user_id) {
		$update_value = array(
			'is_send' => 1,
		);

		$where = array('reserved_user_id' => $reserved_user_id);
		$this->update($update_value, $where, 1);
	}


	/**
	 * 特定予約済みユーザーidの認証フラグをたてる
	 * @param int $reserved_user_id 予約済みユーザーid
	 * void
	 */
	public function updateIsActivated($reserved_user_id) {
		$update_value = array(
			'is_activated' => 1,
		);

		$where = array('reserved_user_id' => $reserved_user_id);
		$this->update($update_value, $where, 1);
	}

}