<?php
require_once dirname(__FILE__) . '/../../library/FileStrage.class.php';
require_once dirname(__FILE__) . '/../../library/Files.class.php';
require_once dirname(__FILE__) . '/../../library/ImagickEditor.class.php';
require_once dirname(__FILE__) . '/../DataBaseMapper/Photos.class.php';

/*
 *　画像のupload
 */

class ModelPhotoUpload{

	protected $err_message;
	protected $photo_id;
    const WIDTH = 400;
    const HEIGHT = 0;

	/*
	 * 画像のアップロード
	 * @param string $title
	 * @param int $user_no
	 * @return bool
	 */
	public function upload($title,$user_no) {
		$files = new Files();
		$photos = new ModelDataBaseMapperPhotos();

		$file_name = $files->getFileName();
		$tmp_name =  $files->getTmpFileName();

		if($file_name == '' || $tmp_name == ''){
			$this->err_message = $files->getErrorMessage();
			return false;
		}
		$mime_type = $files->getMimeType();
		$ext =$this->getImageExt($mime_type);
		$photos->insertImage($title,$ext,$mime_type,$user_no);
		$this->setPhotoId($photos->getLastInsertId());
		$this->uploadS3($tmp_name,$mime_type);
		return true;
	}


	/*
	 * エラーメッセージの取得
	 * return string
	 */
	public function getErrorMessage(){
		return $this->err_message;
	}
	/*
	 * uploadした画像のphoto_idを取得
	 * return int
	 */
	public function getUploadPhotoId() {
		return $this->photo_id;
	}
	/*
	 * uploadした画像のphoto_idをセット
	 * @param int $photo_id
	 * void
	 */
	public function setPhotoId($photo_id) {
		$this->photo_id = $photo_id;
	}

	/*
	 * 画像をs3へアップロード
	 * @param string $tmp_name $_FILESのtmp_name
	 * @param string $mime_type mime_type
	 * void
	*/
	protected function uploadS3($tmp_name,$mime_type){
		$s3_manager = FileStrage::getInstance();
		$ext =$this->getImageExt($mime_type);
		$tmp_name = $this->format($tmp_name);
		$s3_manager ->uploadImageFiles($tmp_name,$this->photo_id,$ext);
	}

	/*
	 * 画像をFIX
	 * @param string #$file_path
	 * @return string
	 */
	protected function format($file_path){
		$image_editor = new ImagickEditor($file_path);
        $image_editor->removeExif();
        $image_editor->resize(self::WIDTH,self::HEIGHT);
		return $image_editor->getBlob();
	}

    /*
     * 拡張子
     * @param string $mime_type mime_type
     * @return string
     */
    protected function getImageExt($mime_type){
        switch ($mime_type){
            case 'image/gif':
                return 'gif';
                break;
            case 'image/png':
                return 'png';
                break;
            case 'image/jpg':
            case 'image/jpeg':
                return 'jpg';
                break;
            default:
                return '';
                break;
        }
    }
}