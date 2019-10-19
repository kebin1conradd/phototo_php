<?php

/**
 * logクラス
 */
class Log {

	/**
	 * エラーログ
	 * @param string $file ファイル名
	 * @param string $body 本文 メッセージ
	 * void  
	 */
	public static function write($file,$body){
		$filename = $file . '_' . date("Y-m-d") . '.log';
		error_log(date("[Y/m/d H:i:s] ") .$body. "\n", 3, dirname(__FILE__) . '/../log/' . $filename);
	}

		
		
}
