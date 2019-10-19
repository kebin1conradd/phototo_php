<?php
	
/**
 * サーバー管理
*/
class ServerDefine{
	const DB_SERVER = 'photo-site-db.cdznda3doiif.us-west-2.rds.amazonaws.com';


	/**
	 * DBサーバー
	 * @return String
	 */
	public function getDbServer(){
			return self::DB_SERVER;
	}
		
}
