<?php


/**
 * 画像操作　
 */
class ImagickEditor {
	

	/**
	 * imagick
	 */
	private $imagick;
        
	/**
	 * 初期化
	 * imagickオブジェクトの作成
	 * @return void
	 */
	public function __construct($file_path) {
		$this->imagick = new Imagick($file_path);
	}


	/*
	 * blobを返す
	 * @return string
	 */

	public function getBlob(){
		return $this->imagick->getImageBlob();
	}
	/*
	 * 画像をリサイズ
	 * @param int $width
	 * @param int $height
	 * void
	 */
	public function resize($width,$height){
		$this->imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
	}

	/*
	 * 追加
	 * @param string $path
	 * void
	 */

	public function add($path){
		return $this->imagick->writeImage($path);
	}
	/**
     * exif消す
	 * void
	 */
	public function removeExif() {
		$this->imageOrientation();
		$this->imagick->stripImage();
	}

	/**
	 * exifを削除したあと正しい向きに回転する
     * https://blog.ver001.com/php_exif_orientation/ 参考
	 * @param string $file_path
	 * void
	 */
	private function imageOrientation() {
		
		$orientation = $this->imagick->getImageOrientation();
		switch ($orientation)
		{
            case 6:
                //90度
                $this->imagick->rotateImage('#000000', 90);
                break;
            case 3:
                //180度
                $this->imagick->rotateImage('#000000', 180);
                break;
            case 8:
                //270度
                $this->imagick->rotateImage('#000000', 270);
                break;
			case 2:
                //反転
				$this->imagick->flopImage();
				break;
            case 7:
                //反転して右90
                $this->imagick->flopImage();
                $this->imagick->rotateImage('#000000', 90);
                break;
			case 4:
                //縦反転
				$this->imagick->flipImage();
				break;
			case 5:
                //反転して270
				$this->imagick->flopImage();
				$this->imagick->rotateImage('#000000', 270);
				break;



		}


	}



	
}
