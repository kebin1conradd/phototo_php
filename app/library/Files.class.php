<?php

/**
 * 便利な簡易ファイル操作　validate付き
 * setter getter付ければ複数条件対応可能
 */

class Files {


	private $error_message;
    //下記を書き換えて設定
    //名前
	private $name = "up_image";
    //最大サイズを指定
	private $max_file_size = '10485760';
    //許可タイプを指定
	private $accept_mime_type =array(
        'gif' => 'image/gif',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
    );


	/*
	 * tmp_nameの取得
	 * @return string
	 */
	public function getTmpFileName(){
		if($this->isFileError()){
			return '';
		}
		return $_FILES[$this->name]['tmp_name'];
	}
	/*
	 * エラーチェック
	 * @return bool
	 */
	private function isFileError(){
		if($this->isEmptyError()){
			return true;
		}
		if($this->isErrorCase()){
			$this->setErrorCaseMsg();
			return true;
		}
		if($this->rejectedMimeType()){
			$this->error_message = '対応していない拡張子です';
			return true;
		}
        if($this->isOverMaxSize()) {
            $this->error_message = '画像サイズ上限は'.$this->max_file_size.'バイトとなっています';
            return true;
        }
		return false;
	}


	/**
	 * サイズ超過
	 * @return bool
	 */
	private function isOverMaxSize(){
		return ($_FILES[$this->name]['size'] > $this->max_file_size);
	}
    /**
     * エラーメッセージSET
     * void
     */
    private function setErrorCaseMsg() {
        switch ($_FILES[$this->name]['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $this->error_message = '画像サイズ上限は'.$this->max_file_size.'バイトとなっています';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $this->error_message = '画像サイズ上限は'.$this->max_file_size.'バイトとなっています';
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->error_message = 'ファイルが選択されていません。';
                break;
            default:
                $this->error_message = '不明なエラーが発生しました。';
        }
    }
	/**
     * eroor値が空
	 * @return bool
	 */
	private function isEmptyError(){
		if (!isset($_FILES[$this->name]['error']) || !is_int($_FILES[$this->name]['error'])) {
			$this->error_message = '不明なエラーが発生しました。';
			return true;
		}
		return false;
	}


	/*
	 * 規制する拡張子
	 * @return bool
	 */
	private function rejectedMimeType() {
		if (array_search($this->getMimeType(), $this->accept_mime_type, true)) {
			return false;
		}
		return true;
	}
	/*
	 * ファイルタイプ取得
	 * @return mixed
	 */
	public function getMimeType(){
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		return $finfo->file($_FILES[$this->name]['tmp_name']);
	}
	/*
	 * エラーかどうか
	 * void
	 */
	private function isErrorCase() {
		if($_FILES[$this->name]['error'] == UPLOAD_ERR_OK) {
			return false;
		}
		return true;
	}

	/*
	 * @return string
	 */
	public function getErrorMessage(){
		return $this->error_message;
	}

    /*
     * name取得
     * @return string
     */
    public function getFileName(){
        if($this->isFileError()){
            return '';
        }
        return $_FILES[$this->name]['name'];
    }




}