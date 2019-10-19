<?php

require_once('AWSSDKforPHP/aws.phar');
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

/**
 *ストレージ
 * シンプルなS3制御 PATH固定
 * initialize()に設定値を突っ込むこと。
 */
class FileStrage {
	private static $class_cache = null;
	const PHOTO_UPLOAD_PATH = 'phototo/pc/';

	private  $bucket;
	private  $error_message;
    private  $result;
    /**
     * s3
     */
    private $s3;
	/**
	 * コンストラクタ
     * access
	 */
	final private function __construct() {
        $this->s3 = $this->initialize();
	}

	/**
	 * FileStrage::getInstance();
	 * @return FileStrage
	 */
	public static function getInstance() {
		if(!is_object(self::$class_cache)) {
			self::$class_cache = new self();
		}
		return self::$class_cache;
	}




	/**
	 * ここに各種設定値突っ込む
	 * @return s3
	 */
	private function initialize(){
        require_once dirname(__FILE__) . '/../config/Config.class.php';
		$config = Config::getInstance();

        $credentials = new \Aws\Credentials\Credentials(
            $config->getAwsAccessKey() ,$config->getAwsSecretKey()
        );
        $params      = array(
            'credentials' => $credentials,
            'region'      =>$config->getAwsRegion(),
            'version' => 'latest',
        );
        $client = new S3Client($params);

		if(!$client) {
            return null;
        }

		$this->bucket = $config->getAwsBucket();

		return $client;
	}



	/**
	 * 画像を$_filesから上げる
	 * @param string $tmp_name
	 * @param string $file_name
     * @param　string $ext 拡張子
	 * @return boolean
	 */
	public function uploadImageFiles($tmp_name, $file_name, $extention){
		$upload_path = self::PHOTO_UPLOAD_PATH.$file_name.'.'.$extention;
		return $this->uploadBlob($tmp_name, $upload_path,'public-read');
	}

	/**
	 * blob upload
	 * @param string $tmp_name
	 * @return boolean
	 */
	public function uploadBlob($tmp_name, $upload_path, $acl){

		if(!$this->s3){
            return false;
        }
		try {
			$this->result =
                $this->s3->putObject(
                    array(
                        'Bucket' => $this->bucket,
                        'Key' => $upload_path,
                        'Body' => $tmp_name,
                        "ACL" => $acl
			          )
                );
		}catch (S3Exception $e) {
			$this->error_message = $e;
			return false;
		}

	}

	/**
	 * 削除
	 * @param string $file_name
	 * @param string $extention
	 * @return boolean
	 */
	public function delete($file_name,$extention){
		$upload_path = self::PHOTO_UPLOAD_PATH.$file_name.'.'.$extention;

		return $this->deleteFile($upload_path);
	}
	/*
	 * s3ファイルの削除 最新版AWSSDKに対応しているか不明
	  * @param string $file_path
	 *  @param string $acl
	 * return boolean
	 */
	protected function deleteFile($file_path) {
		if (!$this->s3) return false;
		try {
			$this->s3->deleteObject(
				array(
					'Bucket' => $this->bucket,
					'Key' => $file_path,
				)

			);
			return true;
		} catch (S3Exception $e) {
			$this->error_message = $e;
			return false;
		}
	}
	/*
	 * エラーの取得
	 */
	public function getErrorMessage(){
		return $this->error_message;
	}




	
}
