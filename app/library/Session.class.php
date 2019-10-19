<?php
/**
 * Session
 */
class Session {
	private static $class_cache = null;
	final private function __construct(){
		$this->initialize();
	}
	/**
	 * @return Session
	 */
	public static function getInstance() {
		if(!is_object(self::$class_cache)) {
			self::$class_cache = new self();
		}
		return self::$class_cache;
	}

	protected function initialize(){
		session_start();
	}


    /*
     * ession取得
     * @param　string　$key
     * return
     */
	public function get($key){
		return  $_SESSION[$key];
	}
    /*
     * Sessionを保存
     * @param　string　$key
     * @param　value
     */
	public function set($key, $value){
       $_SESSION[$key] = $value;
	}
    /*
     * Sessionを削除
     * @param　string　$key
     * @param　value
     */
	public function delete($key){
        $_SESSION[$key] = '';
	}
	/*
	 * unset
	 * @param string $key
	 */
	public function clear($key){
		unset($_SESSION[$key]);
	}
    /*
     * リセット
     */
	public function destroy() {
		$_SESSION = array();
		session_destroy();
	}
	

}
