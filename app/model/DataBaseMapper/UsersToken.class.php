<?php
require_once dirname(__FILE__) . '/Base.class.php';

	
/**
 * DataBaseMapperUsersTokenクラス
 */

class ModelDataBaseMapperUsersToken extends ModelDataBaseMapperBase
{

	/**
	 * テーブル名
	 */
	public $table = 'users_token';
	
	/**
	 * getAutoLogin
	 * @param int $user_id
	 * @param string $user_agent
	 * @return string
	 */
	public function getAutoLogin($user_id, $user_agent,$token) {
		$param = array("user_id" => $user_id, "user_agent" => $user_agent,"token" => $token);

		return $this->selectFirstColumn($param,'token');
	}

    /**
     * LoginToken生成
     * @param $user_id
     * @param $user_agent
     * @param $token
     * void
     */
    public function insertOnDuplicateUserToken($user_id, $user_agent, $token){
        $update_value = array(
            'user_agent' => $user_agent,
            'token' => $token,
        );
        $insert_value = array(
            'user_id' => $user_id,
            'user_agent' => $user_agent,
            'token' => $token,
        );
        $this->insertDuplicated($insert_value, $update_value);

        return;
    }
	/**
	 * checkUser
	 * テーブルに該当のユーザが登録されているか確認する
	 * @param $user_id string
	 * @return bool
	 */
	public function checkUser($user_id){
		$sql = "SELECT `token` FROM " . $this->getTableName() . " WHERE `user_id` = :user_id";
		$param = array("user_id" => $user_id);
		if($this->db->getOneLine($sql, $param)){
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * ログイン情報を削除
	 * @param int $user_id
	 * return boolean
	 */
	public function deleteToken($user_id){
		if($user_id == ''){
			return false;
		}
		
		$param = array("user_id" => $user_id);
		return $this->delete($param,1);
	}
}