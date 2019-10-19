<?php
require_once dirname(__FILE__) . '/../DataBaseMapper/UsersActivation.class.php';
require_once dirname(__FILE__) . '/../DataBaseMapper/Users.class.php';
require_once dirname(__FILE__) . '/Hash.class.php';
require_once dirname(__FILE__) . '/Mail.class.php';
/**
 * ユーザー作成
 */


class ModelUserCreate  {


	/**
	 * 認証前ユーザーの作成
	 * @param string $password ユーザー入力のパスワード
	 * @param string $user_name ユーザーネーム
	 * @param string $mail_address メールアドレス
	 * @return void
	 */
	public function createPreUser($password,$user_name,$mail_address){
		$users_activation = new ModelDataBaseMapperUsersActivation();
		$model_user_hash = new ModelUserHash();
		$model_common = new ModelCommon();
		$model_user_mail = new ModelUserMail();
		$ip_address = $model_common->getIpAddress();
		$ip_trip = $model_user_hash->createTrip($ip_address);
		$activation_code = $model_user_hash->createActivateCode($mail_address,$ip_trip);
		$password_hash = $model_user_hash->createPasswordHash($password,$ip_trip);

		$users_activation->insertUser($password_hash,$user_name,$mail_address,$ip_address,$activation_code);
		$reserved_user_id = $users_activation->getLastInsertId();

		$model_user_mail->sendActivateMail($activation_code,$mail_address,$reserved_user_id);
		$users_activation->updateIsSend($reserved_user_id);
	}


	/**
	 * PreIdからuserを作成する。
	 * @param string $password ユーザー入力のパスワード
	 * @return mixed int or false 作成したuser_idかfalse
	 */
	public function createUserFromPreId($reserved_user_id){
		$users_activation = new ModelDataBaseMapperUsersActivation();
		$rows = $users_activation->getUserInfoFromPreId($reserved_user_id);

		if($rows) {
			$user_id = $this->createUser($rows['password_hash'], $rows['user_name'], $rows['mail_address'], $rows['ip_address']);
			$users_activation->updateIsActivated($reserved_user_id);
			return $user_id;
		}
		else{
			return false;
		}
	}
    /**
     * activateと一致するID名を取得
     * @param string $activation_code
     * @param int $reserved_user_id
     * @return boolean
     */
    public function isActivate($activation_code,$reserved_user_id){
        $users_activation = new ModelDataBaseMapperUsersActivation();
        $rows = $users_activation->getUserInfoFromActivate($activation_code,$reserved_user_id);

        if($rows['reserved_user_id']){
            return true;
        }
        else{
            return false;
        }
    }
	/**
	 * ユーザーの作成
	 * @param string $password ユーザー入力のパスワード
	 * @param string $user_name ユーザーネーム
	 * @param string $mail_address メールアドレス
     * @param string or null $ip_address
	 * @return int insertしたuser_id
	 */
	public function createUser($password_hash,$user_name,$mail_address,$ip_address=null) {
		$users = new ModelDataBaseMapperUsers();
		$model_user_hash = new ModelUserHash();
		$model_common      = new ModelCommon();

		if ($ip_address == null) {
			$ip_address = $model_common->getIpAddress();
		}
		$ip_trip = $model_user_hash->createTrip($ip_address);
		$users->insertUserOrigin($password_hash,$user_name,$mail_address,$ip_address,$ip_trip);
		return $users->getLastInsertId();
	}


}