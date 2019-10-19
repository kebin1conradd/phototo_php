<?php
require_once dirname(__FILE__) . '/ServerDefine.class.php';
/**
 * Config class
 * 各種設定値
 */
class Config {
//セキュリティの都合上 一部情報を削除しています
	const DOMAIN = 'phototo.nl';
    const S3_DOMAIN = '';
	const MYSQL_DB = '';
    const MYSQL_USER = '';
    const MYSQL_PASSWORD = '';
    //AWS API アカウント
    const AWS_BUCKET = 'phototo';
	const AWS_SECRET_KEY = '';
	const AWS_ACCESS_KEY = '';
    const AWS_REGION ="us-west-2";
    const SAVE_COOKIE_NAME_ARRAY = '';
    const SAVE_COOKIE_NAME_ID = '';
    const SAVE_COOKIE_NAME_TOKEN = '';
        
	private static $class_cache;

        final private function __construct() {
        }

        /**
         * インスタンス取得
         * @return Config
         */
    public static function getInstance(){
                if(!is_object(self::$class_cache)){
                        self::$class_cache = new self();
                }
                return self::$class_cache;
    }

    public function getDomain(){
			return self::DOMAIN;
	}

    public function getMysqlDb(){
            return self::MYSQL_DB;
    }
    public function getMysqlUser(){
        return self::MYSQL_USER;
    }
    public function getMysqlPass(){
        return self::MYSQL_PASSWORD;
    }

    public function getAwsSecretKey(){
            return self::AWS_SECRET_KEY;
    }
    public function getAwsAccessKey(){
            return self::AWS_ACCESS_KEY;
    }
    public function getAwsBucket(){
            return self::AWS_BUCKET;
    }
    public function getS3Domain(){
            return self::S3_DOMAIN;
    }
    public function getCookieNameArray(){
        return self::SAVE_COOKIE_NAME_ARRAY;
    }
    public function getCookieNameId(){
        return self::SAVE_COOKIE_NAME_ID;
    }
    public function getCookieNameToken(){
        return self::SAVE_COOKIE_NAME_TOKEN;
    }
    public function getAwsRegion(){
        return self::AWS_REGION;
    }


}
