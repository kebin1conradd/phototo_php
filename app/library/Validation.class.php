<?php
/**
 * 簡易Validator
 * PHPは各種判定が雑なので厳密に
 */
class Validation {

	/**
	 * 半角英数であるか調べる
	 * $alpha_num string チェックしたい値
	 * @return boolean
	 */
	public function isAlphaNumeric($alpha_num){
		if(preg_match("/^[a-zA-Z0-9]+$/", $alpha_num)){
			return true;
		}
		else{
			return false;
		}
	}
    /**
     * 半角数字であるか調べる
     * $num string チェックしたい値
     * @return boolean
     */
    public function isNumeric($num){
        if(preg_match("/^[0-9]+$/", $num)){
            return true;
        }
        else{
            return false;
        }
    }
    /**
     * 半角英字であるか調べる
     * @param string $alpha
     * @return boolean
     */
    public function isAlpha($alpha){
        if(preg_match("/^[a-zA-Z]+$/", $alpha)){
            return true;
        }
        else{
            return false;
        }
    }
	/**
	 * メールアドレスかどうか調べる
	 * @param string $mail_address
	 * return boolean
	 */
	public function isMailAddress($mail_address){
		if(filter_var($mail_address, FILTER_VALIDATE_EMAIL)){
			return true;
		}
		else{
			return false;
		}

	}
	/**
	 * スペースが含まれているか
	 * @param  string $str
	 * return boolean
	 */
	public function isIncludeSpace($str){
		if(preg_match("/[\s|\x{3000}]+/u", $str)){
			return true;
		}
		else{
			return false;
		}
	}


    /*
	 * 指定文字数未満か判定
	 * @param string $word
	 * @param int $min_length
	 * @return boolean
	 */
    public function isLessWordLength($word,$min_length){
        if( mb_strlen($word, "UTF-8") < (int) $min_length){
            return true;
        }else{
            return false;
        }
    }

}
